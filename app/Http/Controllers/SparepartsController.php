<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Sparepart;
use Illuminate\Support\Facades\Crypt;

class SparepartsController extends Controller
{
    public function index($id) {
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();
        $sparepart = Sparepart::where('id_asset', $id)->orderBy('purchase_date', 'asc')->get();

        return view('/components/sparepart', compact('asset', 'sparepart'));
    }
}