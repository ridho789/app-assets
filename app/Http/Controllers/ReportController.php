<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Models\Asset;
use App\Models\Unexpected;

class ReportController extends Controller
{
    public function reportExcel($id) {
        $asset = Asset::where('id_asset', $id)->first();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('date', 'asc')->get();

        return Excel::download(new ReportExport($asset, $unexpected), 'report.xlsx');
    }
}
