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
        $numericFuel = preg_replace("/[^0-9]/", "", explode(",", $request->f_price)[0]);

        if ($request->f_price[0] === '-') {
            $numericFuel *= -1;
        }

        $fueltData = [
            'name' => $request->f_name,
            'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->f_date)->toDateString(),
            'price' => $numericFuel,
            'description' =>$request->f_description,
        ];

        Fuel::where('id_fuel', $request->id)->update($fueltData);
        return redirect()->back();
    }

    public function delete($id) {
        Fuel::where('id_fuel', $id)->delete();
        return redirect()->back();
    }

    public function search(Request $request, $id) {
        $search = $request->search;
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();

        $fuel = Fuel::where('id_asset', $id)->where('date', 'like', "%$search%")->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        if ($fuel->isEmpty()) {
            return redirect()->back();
        }

        return view('/components/fuel', compact('asset', 'fuel'));
    }
}
