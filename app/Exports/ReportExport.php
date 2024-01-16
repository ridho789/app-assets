<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $asset;
    private $unexpected;

    public function __construct($asset, $unexpected) {
        $this->asset = $asset;
        $this->unexpected = $unexpected;
    }

    public function collection() {
        $data = [
            [
                'Name' => $this->asset->name,
                'Location' => $this->asset->location,
                'Purchase Date' => date('l, j F Y', strtotime($this->asset->purchase_date)),
                'Description' => $this->asset->description,
            ],
            [''],
            ['Unexpected Expenses'],
            ['Name', 'Date']
        ];

        foreach ($this->unexpected as $unexpected) {
            $data[] = [
                'Name' => $unexpected->name,
                'Date' => date('l, j F Y', strtotime($unexpected->date)),
            ];
        }

        return collect($data);
    }

    public function headings(): array {
        return [
            'Name',
            'Location',
            'Purchase Date',
            'Description',
        ];
    }
}
