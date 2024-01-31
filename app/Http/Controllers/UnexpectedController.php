<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Unexpected;
use Illuminate\Support\Facades\Crypt;

class UnexpectedController extends Controller
{
    public function index($id) {
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        return view('/components/unexpected', compact('asset', 'unexpected'));
    }

    public function update(Request $request) {
        $unexpectedData = [
            'name' => $request->ux_name,
            'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->ux_date)->toDateString(),
            'price' => $request->ux_price,
            'description' =>$request->ux_description,
        ];

        Unexpected::where('id_unexpected_expenses', $request->id)->update($unexpectedData);
        return redirect()->back();
    }

    public function delete($id) {
        Unexpected::where('id_unexpected_expenses', $id)->delete();
        return redirect()->back();
    }

    public function search(Request $request, $id) {
        $search = $request->search;
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();

        $unexpected = Unexpected::where('id_asset', $id)->where('date', 'like', "%$search%")->orderBy('name', 'asc')->orderBy('date', 'asc')->get();

        if ($unexpected->isEmpty()) {
            return redirect()->back();
        }

        return view('/components/unexpected', compact('asset', 'unexpected'));
    }
}
