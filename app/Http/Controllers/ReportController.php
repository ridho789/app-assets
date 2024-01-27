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

class ReportController extends Controller
{
    public function reportExcel($id) {
        $asset = Asset::where('id_asset', $id)->first();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();
        $materials = Material::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $salary = Salary::where('id_asset', $id)->orderBy('period', 'asc')->orderBy('date', 'asc')->get();
        $spareparts = Sparepart::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $fuel = Fuel::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        return Excel::download(new ReportExport($asset, $unexpected, $materials, $salary, $spareparts, $fuel), 'Report Asset (With Details) - ' . $asset->name. '.xlsx');
    }

    public function reportExcelWithoutDetails($id) {
        $asset = Asset::where('id_asset', $id)->first();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();
        $materials = Material::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $salary = Salary::where('id_asset', $id)->orderBy('period', 'asc')->orderBy('date', 'asc')->get();
        $spareparts = Sparepart::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $fuel = Fuel::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        return Excel::download(new ReportExportExcelWithoutDetails($asset, $unexpected, $materials, $salary, $spareparts, $fuel,), 'Report Asset (Without Details) - ' . $asset->name. '.xlsx');
    }
}
