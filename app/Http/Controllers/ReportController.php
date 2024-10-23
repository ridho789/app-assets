<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Exports\ReportExportExcelWithoutDetails;
use App\Models\Asset;
use App\Models\Unexpected;
use App\Models\Material;
use App\Models\Salary;
use App\Models\Sparepart;
use App\Models\Fuel;
use App\Models\Expense;
use Illuminate\Support\Facades\Crypt;

class ReportController extends Controller
{
    public function reportExcel($id, Request $request) {
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();
        $totalExpenses = 0;
        $expenses = Expense::with('category')
            ->where('id_asset', $id)
            ->get()
            ->groupBy('category.name');
    
        foreach ($expenses as $categoryName => $expenseGroup) {
            $expenseSum = $expenseGroup->sum('price');
            $totalExpenses += $expenseSum;
            $expenses[$categoryName]['total'] = $expenseSum;
        }
    
        $type = $request->query('type');
    
        if ($type === 'without-details') {
            return Excel::download(new ReportExportExcelWithoutDetails($asset, $expenses), 'Report Asset (Without Details) - ' . $asset->name . '.xlsx');
        }
    
        // Default to the detailed report if type is not specified or is something else
        return Excel::download(new ReportExport($asset, $expenses), 'Report Asset (With Details) - ' . $asset->name . '.xlsx');
    }
}
