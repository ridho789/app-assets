<?php

namespace App\Exports;

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
        $years = collect([]);
        $data = collect([
            [$this->asset->name . ' (' . $this->asset->status . ')'],
            [$this->asset->location],
            [date('l, j F Y', strtotime($this->asset->purchase_date))],
            [$this->asset->description],
            [''] // empty row
        ]);

        // Create header row
        $headerRow = collect(['Category']);

        // Process each category and calculate total expenses per year
        foreach ($this->expenses as $category => $expenseGroup) {
            $yearlyTotals = $this->calculateTotalExpensesPerYear($expenseGroup);
            $years = $years->merge($yearlyTotals->keys());
            // Add yearly totals to the header row
            foreach ($yearlyTotals as $year => $total) {
                if (!$headerRow->contains($year)) {
                    $headerRow->push($year);
                }
            }
        }
        $headerRow->push('Total');

        // Merge years and remove duplicates
        $years = $years->unique()->sort();

        // Add header row to data
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