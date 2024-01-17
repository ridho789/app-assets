<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $asset;
    private $unexpected;
    private $materials;
    private $salary;
    private $spareparts;
    private $fuel;

    public function __construct($asset, $unexpected, $materials, $salary, $spareparts, $fuel)
    {
        $this->asset = $asset;
        $this->unexpected = $unexpected;
        $this->materials = $materials;
        $this->salary = $salary;
        $this->spareparts = $spareparts;
        $this->fuel = $fuel;
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
            ['Unexpected Expenses'],
            ['Name', 'Price', 'Date', 'Description']
        ];

        if (count($this->unexpected) > 0) {
            foreach ($this->unexpected as $unexpected) {
                $data[] = [
                    'Name' => $unexpected->name,
                    'Price' => 'IDR ' . number_format($unexpected->price ?? 0, 0, ',', '.'),
                    'Date' => date('l, j F Y', strtotime($unexpected->date)),
                    'Description' => $unexpected->description
                ];
            }

        } else {
            $data[] = ['No data available'];
        }

        $data[] = [''];

        $data[] = [
            ['Materials Expenses'],
            ['Name', 'Purchase Price', 'Purchase Date', 'Description', 'Amount']
        ];

        if (count($this->materials) > 0) {
            foreach ($this->materials as $material) {
                $data[] = [
                    'Name' => $material->name,
                    'Purchase Price' => 'IDR ' . number_format($material->purchase_price ?? 0, 0, ',', '.'),
                    'Purchase Date' => date('l, j F Y', strtotime($material->purchase_date)),
                    'Description' => $material->description,
                    'Amount' => $material->amount,
                ];
            }

        } else {
            $data[] = ['No data available'];
        }

        $data[] = [''];

        $data[] = [
            ['Salary Expenses'],
            ['Period', 'Amount Paid', 'Date', 'Description']
        ];

        if (count($this->salary) > 0) {
            foreach ($this->salary as $salary) {
                $data[] = [
                    'Period' => $salary->period,
                    'Amount Paid' => 'IDR ' . number_format($salary->amount_paid ?? 0, 0, ',', '.'),
                    'Date' => date('l, j F Y', strtotime($salary->date)),
                    'Description' => $salary->description,
                ];
            }

        } else {
            $data[] = ['No data available'];
        }

        $data[] = [''];

        $data[] = [
            ['Spareparts Expenses'],
            ['Name', 'Price', 'Purchase Date', 'Description']
        ];

        if (count($this->spareparts) > 0) {
            foreach ($this->spareparts as $sparepart) {
                $data[] = [
                    'Name' => $sparepart->name,
                    'Price' => 'IDR ' . number_format($sparepart->price ?? 0, 0, ',', '.'),
                    'Purchase Date' => date('l, j F Y', strtotime($sparepart->purchase_date)),
                    'Description' => $sparepart->description,
                ];
            }

        } else {
            $data[] = ['No data available'];
        }

        $data[] = [''];

        $data[] = [
            ['Fuel Expenses'],
            ['Name', 'Price', 'Date', 'Description']
        ];
        
        if (count($this->fuel) > 0) {
            foreach ($this->fuel as $fuel) {
                $data[] = [
                    'Name' => $fuel->name,
                    'Price' => 'IDR ' . number_format($fuel->price ?? 0, 0, ',', '.'),
                    'Date' => date('l, j F Y', strtotime($fuel->date)),
                    'Description' => $fuel->description,
                ];
            }

        } else {
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
