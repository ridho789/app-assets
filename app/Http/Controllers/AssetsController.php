<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Crypt;

class AssetsController extends Controller
{
    public function create() {
        $asset = '';
        return view('/components/asset', compact('asset'));
    }

    public function store(Request $request) {
        Asset::insert([
            'name' => $request->name,
            'location' =>$request->location,
            'purchase_price' =>$request->purchase_price,
            'purchase_date' =>\Carbon\Carbon::createFromFormat('m/d/Y', $request->purchase_date)->toDateString(),
            'description' =>$request->description,
            'status' => $request->status,
        ]);

        // dashboard
        return redirect('/');
    }

    public function edit($id) {
        $id = Crypt::decrypt($id);

        $asset = Asset::where('id_asset', $id)->first();
        return view('/components/asset', compact('asset'));
    }

    public function update(Request $request) {
        $request->validate([
            'purchase_price' => 'numeric',
        ]);

        $assetData = [
            'name' => $request->name,
            'location' =>$request->location,
            'purchase_price' =>$request->purchase_price,
            'purchase_date' =>\Carbon\Carbon::createFromFormat('m/d/Y', $request->purchase_date)->toDateString(),
            'description' =>$request->description,
            'status' => $request->status,
        ];

        Asset::where('id_asset', $request->id)->update($assetData);

        // dashboard
        return redirect('/');
    }
}
