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
        $material = Material::where('id_asset', $id)->orderBy('purchase_date', 'asc')->get();

        return view('/components/material', compact('asset', 'material'));
    }
}
