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
        $fuel = Fuel::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        return view('/components/fuel', compact('asset', 'fuel'));
    }

    public function update(Request $request) {
        $fueltData = [
            'name' => $request->f_name,
            'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->f_date)->toDateString(),
            'price' => $request->f_price,
            'description' =>$request->f_description,
        ];

        Fuel::where('id_fuel', $request->id)->update($fueltData);
        return redirect()->back();
    }

    public function delete($id) {
        Fuel::where('id_fuel', $id)->delete();
        return redirect()->back();
    }
}
