<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Fuel;
use App\Models\Material;
use App\Models\Salary;
use App\Models\Sparepart;
use App\Models\Unexpected;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
use PDF;

class AssetsController extends Controller
{
    public function create() {
        $asset = '';
        return view('/components/asset', compact('asset'));
    }

    public function store(Request $request) {
        $request->validate([
            'purchase_price' => 'numeric',
        ]);

        Asset::insert([
            'name' => $request->name,
            'location' =>$request->location,
            'purchase_price' =>$request->purchase_price,
            'purchase_date' =>\Carbon\Carbon::createFromFormat('m/d/Y', $request->purchase_date)->toDateString(),
            'description' =>$request->description,
            'status' => $request->status,
            'tot_overall_expenses' =>$request->purchase_price,
        ]);

        // dashboard
        return redirect('/dashboard');
    }

    public function edit($id) {
        $id = Crypt::decrypt($id);

        $asset = Asset::where('id_asset', $id)->first();
        $allFuelExpenses = Fuel::where('id_asset', $id)->sum('price');
        $allMaterialExpenses = Material::where('id_asset', $id)->sum('purchase_price');
        $allSalaryExpenses = Salary::where('id_asset', $id)->sum('amount_paid');
        $allSparepartExpenses = Sparepart::where('id_asset', $id)->sum('price');
        $allUnexpectedExpenses = Unexpected::where('id_asset', $id)->sum('price');

        $fuel = Fuel::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();
        $material = Material::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $salary = Salary::where('id_asset', $id)->orderBy('period', 'asc')->orderBy('date', 'asc')->get();
        $sparepart = Sparepart::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        $totalExpenses = $allFuelExpenses + $allMaterialExpenses + $allSalaryExpenses + $allSparepartExpenses + $allUnexpectedExpenses;

        // Update total overall expenses
        Asset::where('id_asset', $id)->update([
            'tot_expenses' => $totalExpenses,
            'tot_overall_expenses' => $asset->purchase_price + $totalExpenses,
        ]);

        return view('/components/asset', compact(
            'asset', 'allFuelExpenses', 'allMaterialExpenses', 'allSalaryExpenses', 'allSparepartExpenses', 'allUnexpectedExpenses', 'totalExpenses',
            'fuel', 'material', 'salary', 'sparepart', 'unexpected'
        ));
    }

    public function update(Request $request) {
        $request->validate([
            'purchase_price' => 'numeric',
        ]);

        $formatTotExpenses = (int) str_replace(',', '', number_format((int) str_replace(['IDR ', '.'], '', $request->total_expenses), 0, ',', ''));

        $assetData = [
            'name' => $request->name,
            'location' =>$request->location,
            'purchase_price' =>$request->purchase_price,
            'purchase_date' =>\Carbon\Carbon::createFromFormat('m/d/Y', $request->purchase_date)->toDateString(),
            'description' =>$request->description,
            'status' => $request->status,
            'tot_overall_expenses' => $request->purchase_price + $formatTotExpenses,
        ];

        Asset::where('id_asset', $request->id)->update($assetData);

        // dashboard
        return redirect('/dashboard');
    }

    public function report_with_details($id) {
        $asset = Asset::where('id_asset', $id)->first();
        $fuel = Fuel::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();
        $material = Material::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $salary = Salary::where('id_asset', $id)->orderBy('period', 'asc')->orderBy('date', 'asc')->get();
        $sparepart = Sparepart::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        $pdf = PDF::loadView('reports.pdf_report_asset_with_details', compact(
            'asset', 'fuel', 'material', 'salary', 'sparepart', 'unexpected'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Report Asset (With Details) - ' . $asset->name . '.pdf');
    }

    public function report_without_details($id) {
        $asset = Asset::where('id_asset', $id)->first();
        $fuel = Fuel::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();
        $material = Material::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $salary = Salary::where('id_asset', $id)->orderBy('period', 'asc')->orderBy('date', 'asc')->get();
        $sparepart = Sparepart::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        $totalFuelPricePerYear = $fuel->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->date)->format('Y');
        })->map(function ($item) {
            return $item->sum('price');
        });

        $totalMaterialPricePerYear = $material->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->purchase_date)->format('Y');
        })->map(function ($item) {
            return $item->sum('purchase_price');
        });

        $totalSalaryPricePerYear = $salary->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->date)->format('Y');
        })->map(function ($item) {
            return $item->sum('amount_paid');
        });

        $totalSparepartPricePerYear = $sparepart->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->purchase_date)->format('Y');
        })->map(function ($item) {
            return $item->sum('price');
        });

        $totalUnexpectedPricePerYear = $unexpected->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->date)->format('Y');
        })->map(function ($item) {
            return $item->sum('price');
        });

        $pdf = PDF::loadView('reports.pdf_report_asset_without_details', compact(
            'asset', 'fuel', 'material', 'salary', 'sparepart', 'unexpected', 'totalFuelPricePerYear', 
            'totalMaterialPricePerYear', 'totalSalaryPricePerYear', 'totalSparepartPricePerYear', 'totalUnexpectedPricePerYear'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Report Asset (Without Details) - ' . $asset->name . '.pdf');
    }
}
