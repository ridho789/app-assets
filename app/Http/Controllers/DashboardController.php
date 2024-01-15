<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class DashboardController extends Controller
{
    public function index() {
        $assets = Asset::all();
        return view('/dashboard', ['assets' => $assets]);
    }

    public function search(Request $request) {
        $search = $request->input('search');

        $asset = Asset::where('name', 'like', "%$search%")
            ->orWhereRaw('location like ?', ["%$search%"])
            ->get();

        if ($asset->count() === 0) {
            // dashboard
            return redirect('/');

        } else {
            return view('/dashboard', ['assets' => $asset]);
        }
    }
}
