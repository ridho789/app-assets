<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Fuel;
use Illuminate\Support\Facades\Crypt;

class FuelController extends Controller
{
    public function index($id) {
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();
        $fuel = Fuel::where('id_asset', $id)->orderBy('date', 'asc')->get();

        return view('/components/fuel', compact('asset', 'fuel'));
    }
}
