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
        $sparepart = Sparepart::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();

        return view('/components/sparepart', compact('asset', 'sparepart'));
    }

    public function update(Request $request) {
        $numericSparepart = preg_replace("/[^0-9]/", "", explode(",", $request->sp_price)[0]);

        if ($request->sp_price[0] === '-') {
            $numericSparepart *= -1;
        }

        $sparepartData = [
            'name' => $request->sp_name,
            'purchase_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->sp_purchase_date)->toDateString(),
            'price' => $numericSparepart,
            'description' =>$request->sp_description,
        ];

        Sparepart::where('id_sparepart', $request->id)->update($sparepartData);
        return redirect()->back();
    }

    public function delete($id) {
        Sparepart::where('id_sparepart', $id)->delete();
        return redirect()->back();
    }

    public function search(Request $request, $id) {
        $search = $request->search;
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();

        $sparepart = Sparepart::where('id_asset', $id)->where('purchase_date', 'like', "%$search%")->orderBy('name', 'asc')->orderBy('purchase_date', 'asc')->get();

        if ($sparepart->isEmpty()) {
            return redirect()->back();
        }

        return view('/components/sparepart', compact('asset', 'sparepart'));
    }
}
