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
}
