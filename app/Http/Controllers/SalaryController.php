<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Salary;
use Illuminate\Support\Facades\Crypt;

class SalaryController extends Controller
{
    public function index($id) {
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();
        $salary = Salary::where('id_asset', $id)->orderBy('date', 'asc')->get();

        return view('/components/salary', compact('asset', 'salary'));
    }
}
