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
        $unexpected = Unexpected::where('id_asset', $id)->orderBy('date', 'asc')->get();

        return view('/components/unexpected', compact('asset', 'unexpected'));
    }
}
