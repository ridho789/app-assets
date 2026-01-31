<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExportExcelWithoutDetails implements FromCollection, ShouldAutoSize, WithStyles
{
    protected $asset;
    protected $expenses;

    public function __construct($asset, $expenses)
    {
        $this->asset = $asset;
        $this->expenses = $expenses;
    }

    public function calculateTotalExpensesPerYear(Collection $items)
    {
        // Ambil semua tahun yang ada di dalam data
        $allYears = $items->map(function ($item) {
            if (is_object($item) && isset($item->date)) {
                return \Carbon\Carbon::parse($item->date)->format('Y');
            }
            return null;
        })->filter()->unique();

        // Hitung total pengeluaran per tahun
        $yearlyTotals = $items->groupBy(function ($item) {
            if (is_object($item) && isset($item->date)) {
                return \Carbon\Carbon::parse($item->date)->format('Y');
            }
            return null;
        })->filter()->map(function ($group) {
            return $group->sum(function ($item) {
                return is_object($item) && isset($item->price) ? $item->price : 0;
            });
        });

        // Buat array hasil untuk setiap tahun
        $results = collect();
        foreach ($allYears as $year) {
            $results[$year] = $yearlyTotals->get($year, 0); // Jika tidak ada, set 0
        }

        return $results;
    }

    public function formatPriceIDR($price)
    {
        return 'IDR ' . number_format($price, 0, ',', '.');
    }

    public function collection()
    {
        $data = collect([
            [($this->asset->name . (' ' . $this->asset->sub_name ?? '') . ' (' . ($this->asset->status ?? '') . ')')],
            [$this->asset->location],
            [date('l, j F Y', strtotime($this->asset->purchase_date))],
            [$this->asset->description],
            [''] // empty row
        ]);

        $years = collect([]);
        foreach ($this->expenses as $expenseGroup) {
            $years = $years->merge(
                collect($expenseGroup)
                    ->filter(fn ($item) => is_object($item) && isset($item->date))
                    ->map(fn ($item) => Carbon::parse($item->date)->format('Y'))
            );
        }

        foreach ($this->expenses as $expenseGroup) {
            $years = $years->merge(
                collect($expenseGroup)
                    ->filter(fn ($item) => is_object($item) && isset($item->date))
                    ->map(fn ($item) => Carbon::parse($item->date)->format('Y'))
            );
        }

        $years = $years->unique()->sort()->values();

        // Create header row
        $headerRow = collect(['Category']);

        foreach ($years as $year) {
            $headerRow->push($year);
        }

        $headerRow->push('Total');
        $data->push($headerRow);

        // Process each category
        foreach ($this->expenses as $categoryName => $expenseGroup) {
            $yearlyTotals = $this->calculateTotalExpensesPerYear($expenseGroup);
            $categoryRow = collect([$categoryName]);
            
            // Add yearly totals to the row
            foreach ($years as $year) {
                $categoryRow->push($this->formatPriceIDR($yearlyTotals[$year] ?? 0));
            }

            // Add total for this category
            $categoryRow->push($this->formatPriceIDR($yearlyTotals->sum()));
            $data->push($categoryRow);
        }

        // Add additional overall total expenses
        $totalExpenses = $this->expenses->sum(function ($expenseGroup) {
            return $expenseGroup->sum('price');
        });

        // Add additional information
        $data->push(['']);
        $data->push([
            ['Title' => 'Purchase Price', 'Value' => 'IDR ' . number_format($this->asset->purchase_price ?? 0, 0, ',', '.')],
            ['Title' => 'Total Expenses', 'Value' => $this->formatPriceIDR($totalExpenses)],
            ['Title' => 'Overall Expenses', 'Value' => 'IDR ' . number_format($this->asset->tot_overall_expenses ?? 0, 0, ',', '.')]
        ]);

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Atur lebar kolom A agar tidak terlalu panjang
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getStyle('A1:A' . $sheet->getHighestRow())->getAlignment()->setWrapText(true);
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            2 => [
                'font' => ['bold' => true],
            ],
            3 => [
                'font' => ['bold' => true],
            ],
        ];
    }
}