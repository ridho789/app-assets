<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Expense;

class ReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $asset;
    private $expenses;

    public function __construct($asset, $expenses)
    {
        $this->asset = $asset;
        $this->$expenses = $expenses;
    }

    public function headings(): array
    {
        return [
            'Asset Name',
            'Location',
            'Purchase Date',
            'Description',
            'Purchase Price',
            'Total Expenses',
            'Overall Expenses'
        ];
    }

    public function collection()
    {
        $data = [
            [
                'Name' => $this->asset->name . ' (' . $this->asset->status . ')',
                'Location' => $this->asset->location,
                'Purchase Date' => date('l, j F Y', strtotime($this->asset->purchase_date)),
                'Description' => $this->asset->description,
                'Purchase Price' => 'IDR ' . number_format($this->asset->purchase_price ?? 0, 0, ',', '.'),
                'Total Expenses' => 'IDR ' . number_format($this->asset->tot_expenses ?? 0, 0, ',', '.'),
                'Overall Expenses' => 'IDR ' . number_format($this->asset->tot_overall_expenses ?? 0, 0, ',', '.'),
            ],
            [''],
        ];

        $totalExpenses = 0;

        // Mendapatkan semua pengeluaran berdasarkan kategori
        $expenses = Expense::with('category')
            ->where('id_asset',  $this->asset->id_asset)
            ->get()
            ->groupBy('category.name');

        // Mengiterasi setiap kategori pengeluaran
        foreach ($expenses as $categoryName => $expenseGroup) {
            $expenseSum = $expenseGroup->sum('price');
            $totalExpenses += $expenseSum;

            // Menambahkan judul kategori dan header tabel
            $data[] = [$categoryName];
            if ($categoryName === 'Materials') {
                $data[] = ['Name', 'Price', 'Date', 'Description'];
            } elseif ($categoryName === 'Salary') {
                $data[] = ['Name', 'Price', 'Date', 'Description'];
            } elseif ($categoryName === 'Spareparts') {
                $data[] = ['Name', 'Price', 'Date', 'Description'];
            } elseif ($categoryName === 'Fuel') {
                $data[] = ['Name', 'Price', 'Date', 'Description'];
            }

            // Menambahkan data pengeluaran untuk kategori ini
            foreach ($expenseGroup as $expense) {
                $data[] = [
                    'Name' => $expense->name ?? null,
                    'Price' => 'IDR ' . number_format($expense->price ?? 0, 0, ',', '.'),
                    'Date' => isset($expense->date) ? date('l, j F Y', strtotime($expense->date)) : null,
                    'Description' => $expense->desc ?? null,
                    // Hanya tambahkan kolom 'Amount' jika kategori adalah 'Materials'
                ];
            }

            // Tambahkan total pengeluaran untuk kategori ini
            $data[] = ['Total for ' . $categoryName => 'IDR ' . number_format($expenseSum, 0, ',', '.')];

            // Tambahkan pemisah
            $data[] = [''];
        }

        // Tambahkan total keseluruhan
        $data[] = ['Total Expenses' => 'IDR ' . number_format($totalExpenses, 0, ',', '.')];

        // Jika tidak ada pengeluaran yang ditemukan
        if (empty($data)) {
            $data[] = ['No data available'];
        }


        return collect($data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Asset ' . $this->asset->name);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFont()->setSize(12);

        $lastRow = $sheet->getHighestRow();

        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValueA = $sheet->getCell('A' . $row)->getValue();

            if (stripos($cellValueA, 'Expenses') !== false) {
                $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
            }
        }
    }
}
