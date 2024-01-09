<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class AssetsController extends Controller
{
    public function create() {
        return view('/components/asset');
    }

    public function store(Request $request) {
        Asset::insert([
            'name' => $request->name,
            'location' =>$request->location,
            'purchase_price' =>$request->purchase_price,
            'purchase_date' =>\Carbon\Carbon::createFromFormat('d/m/Y', $request->purchase_date)->toDateString(),
            'description' =>$request->description,
        ]);

        // dashboard
        return redirect('/');
    }

    public function edit() {
        
    }
}
