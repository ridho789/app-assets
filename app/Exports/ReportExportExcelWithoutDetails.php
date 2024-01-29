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
    protected $unexpected;
    protected $materials;
    protected $salary;
    protected $spareparts;
    protected $fuel;

    public function __construct($asset, $unexpected, $materials, $salary, $spareparts, $fuel)
    {
        $this->asset = $asset;
        $this->unexpected = $unexpected;
        $this->materials = $materials;
        $this->salary = $salary;
        $this->spareparts = $spareparts;
        $this->fuel = $fuel;
    }

    public function calculateTotalExpensesPerYear(Collection $items, $dateField, $amountField)
    {
        $totalPricePerYear = $items->groupBy(function ($item) use ($dateField) {
            return \Carbon\Carbon::parse($item->$dateField)->format('Y');
        })->map(function ($group) use ($amountField) {
            return $group->sum($amountField);
        });

        return $totalPricePerYear;
    }

    public function formatPriceIDR($price)
    {
        return 'IDR ' . number_format($price, 0, ',', '.');
    }

    public function collection()
    {
        // Calculate total expenses per year for Unexpected and Materials
        $unexpectedExpenses = $this->calculateTotalExpensesPerYear($this->unexpected, 'date', 'price');
        $materialsExpenses = $this->calculateTotalExpensesPerYear($this->materials, 'purchase_date', 'purchase_price');
        $salaryExpenses = $this->calculateTotalExpensesPerYear($this->salary, 'date', 'amount_paid');
        $sparepartsExpenses = $this->calculateTotalExpensesPerYear($this->spareparts, 'purchase_date', 'price');
        $fuelExpenses = $this->calculateTotalExpensesPerYear($this->fuel, 'date', 'price');

        // Combine years from all expenses
        $combinedYears = collect([]);
        $combinedYears = $combinedYears->merge($unexpectedExpenses->keys());
        $combinedYears = $combinedYears->merge($materialsExpenses->keys());
        $combinedYears = $combinedYears->merge($salaryExpenses->keys());
        $combinedYears = $combinedYears->merge($sparepartsExpenses->keys());
        $combinedYears = $combinedYears->merge($fuelExpenses->keys());
        $combinedYears = $combinedYears->unique()->sort();

        // Create the header row
        $headerRow = collect(['']);
        foreach ($combinedYears as $year) {
            $headerRow->push($year);
        }

        $headerRow->push('Total');

        // Prepare data for the spreadsheet
        $data = collect([
            [$this->asset->name . ' (' . $this->asset->status . ')'],
            [$this->asset->location],
            [date('l, j F Y', strtotime($this->asset->purchase_date))],
            [$this->asset->description],
            [''] // empty row
        ]);

        // Merge header and total expenses rows
        $data->push($headerRow);

        // Calculate and merge total expenses for Unexpected
        $unexpectedExpensesRow = collect(['Unexpected Expenses']);
        foreach ($combinedYears as $year) {
            $unexpectedExpensesRow->push($this->formatPriceIDR($unexpectedExpenses[$year] ?? 0));
        }
        
        $unexpectedTotalAllYears = $unexpectedExpenses->sum();
        $unexpectedExpensesRow->push($this->formatPriceIDR($unexpectedTotalAllYears));
        $data->push($unexpectedExpensesRow);

        // Calculate and merge total expenses for Materials
        $materialsExpensesRow = collect(['Materials Expenses']);
        foreach ($combinedYears as $year) {
            $materialsExpensesRow->push($this->formatPriceIDR($materialsExpenses[$year] ?? 0));
        }

        $materialsTotalAllYears = $materialsExpenses->sum();
        $materialsExpensesRow->push($this->formatPriceIDR($materialsTotalAllYears));
        $data->push($materialsExpensesRow);

        // Calculate and merge total expenses for Salary
        $salaryExpensesRow = collect(['Salary Expenses']);
        foreach ($combinedYears as $year) {
            $salaryExpensesRow->push($this->formatPriceIDR($salaryExpenses[$year] ?? 0));
        }

        $salaryTotalAllYears = $salaryExpenses->sum();
        $salaryExpensesRow->push($this->formatPriceIDR($salaryTotalAllYears));
        $data->push($salaryExpensesRow);

        // Calculate and merge total expenses for Spare Parts
        $sparepartsExpensesRow = collect(['Spare Parts Expenses']);
        foreach ($combinedYears as $year) {
            $sparepartsExpensesRow->push($this->formatPriceIDR($sparepartsExpenses[$year] ?? 0));
        }

        $sparepartsTotalAllYears = $sparepartsExpenses->sum();
        $sparepartsExpensesRow->push($this->formatPriceIDR($sparepartsTotalAllYears));
        $data->push($sparepartsExpensesRow);

        // Calculate and merge total expenses for Fuel
        $fuelExpensesRow = collect(['Fuel Expenses']);
        foreach ($combinedYears as $year) {
            $fuelExpensesRow->push($this->formatPriceIDR($fuelExpenses[$year] ?? 0));
        }

        $fuelTotalAllYears = $fuelExpenses->sum();
        $fuelExpensesRow->push($this->formatPriceIDR($fuelTotalAllYears));
        $data->push($fuelExpensesRow);

        $data->push(['']);
        $data->push([
            ['Title' => 'Purchase Price', 'Value' => 'IDR ' . number_format($this->asset->purchase_price ?? 0, 0, ',', '.')],
            ['Title' => 'Total Expenses', 'Value' => 'IDR ' . number_format($this->asset->tot_expenses ?? 0, 0, ',', '.')],
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
        ];
    }
}
