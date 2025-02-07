<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Material;
use Illuminate\Support\Facades\Crypt;

class MaterialsController extends Controller
{
    public function index($id) {
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();
        $material = Material::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();

        return view('/components/material', compact('asset', 'material'));
    }

    public function update(Request $request) {
        $numericPurchasePrice = preg_replace("/[^0-9]/", "", explode(",", $request->m_purchase_price)[0]);

        if ($request->m_purchase_price[0] === '-') {
            $numericPurchasePrice *= -1;
        }

        $materialData = [
            'name' => $request->m_name,
            'purchase_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->m_purchase_date)->toDateString(),
            'amount' => $request->m_amount,
            'purchase_price' => $numericPurchasePrice,
            'description' =>$request->m_description,
        ];

        Material::where('id_material', $request->id)->update($materialData);
        return redirect()->back();
    }

    public function delete($id) {
        Material::where('id_material', $id)->delete();
        return redirect()->back();
    }

    public function search(Request $request, $id) {
        $search = $request->search;
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();

        $material = Material::where('id_asset', $id)->where('purchase_date', 'like', "%$search%")->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();

        if ($material->isEmpty()) {
            return redirect()->back();
        }

        return view('/components/material', compact('asset', 'material'));
    }
}
