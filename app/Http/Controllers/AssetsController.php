<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Fuel;
use App\Models\Material;
use App\Models\Salary;
use App\Models\Sparepart;
use App\Models\Unexpected;
use App\Models\Category;
use App\Models\Expense;
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
        $numericPrice = preg_replace("/[^0-9]/", "", explode(",", $request->purchase_price)[0]);
        if ($request->purchase_price[0] === '-') {
            $numericPrice *= -1;
        }

        Asset::insert([
            'name' => $request->name,
            'location' =>$request->location,
            'purchase_price' =>$numericPrice,
            'purchase_date' =>\Carbon\Carbon::createFromFormat('m/d/Y', $request->purchase_date)->toDateString(),
            'description' =>$request->description,
            'status' => $request->status,
            'tot_overall_expenses' =>$numericPrice,
        ]);

        // dashboard
        return redirect('/dashboard');
    }

    public function edit($id) {
        $id = Crypt::decrypt($id);

        $asset = Asset::where('id_asset', $id)->first();
        $category = Category::orderBy('name')->get();
        $totalExpenses = 0;

        // Ambil semua expense terkait asset dan join dengan category
        $expenses = Expense::with('category')
            ->where('id_asset', $id)
            ->get()
            ->groupBy('category.name');

        // Hitung total setiap jenis pengeluaran
        foreach ($expenses as $categoryName => $expenseGroup) {
            $expenseSum = $expenseGroup->sum('price');
            $totalExpenses += $expenseSum;
            $expenses[$categoryName]['total'] = $expenseSum;
        }
        
        $purchasePrice = $asset->purchase_price ?? 0;

        // Update total overall expenses
        Asset::where('id_asset', $id)->update([
            'tot_expenses' => $totalExpenses,
            'tot_overall_expenses' => $purchasePrice + $totalExpenses,
        ]);

        return view('/components/asset', compact(
            'asset', 'category', 'expenses', 'totalExpenses'
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

    public function reportPDF($id_asset) {
        // Dekripsi ID aset
        $id = Crypt::decrypt($id_asset);
        $asset = Asset::where('id_asset', $id)->first();

        // Periksa jenis laporan
        $type = request()->query('type');

        if ($type === 'with-details') {
            // Mengambil data untuk laporan dengan detail
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

            // Membuat PDF untuk laporan dengan detail
            $pdf = PDF::loadView('reports.pdf_report_asset_with_details', compact('asset', 'expenses'))
                ->setPaper('a4', 'landscape');

            return $pdf->stream('Report Asset (With Details) - ' . $asset->name . '.pdf');

        } elseif ($type === 'without-details') {
            // Mengambil semua data pengeluaran berdasarkan id_asset
            $expenses = Expense::with('category')
            ->where('id_asset', $id)
            ->get()
            ->groupBy('category.name');

            // Inisialisasi array untuk total harga per tahun
            $totalPricesPerYear = [];

            foreach ($expenses as $categoryName => $items) {
            $totalPricesPerYear[$categoryName] = $items->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y');
            })->map(function ($itemGroup) {
                return $itemGroup->sum('price');
            });
            }

            // Membuat PDF untuk laporan dengan detail
            $pdf = PDF::loadView('reports.pdf_report_asset_without_details', compact('asset', 'expenses'))
                ->setPaper('a4', 'landscape');

            return $pdf->stream('Report Asset (Without Details) - ' . $asset->name . '.pdf');
        }

        // Jika jenis tidak dikenali, bisa mengembalikan response error atau redirect
        return redirect()->back()->withErrors(['error' => 'Invalid report type specified.']);
    }

}
