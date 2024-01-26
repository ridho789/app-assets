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
        $salary = Salary::where('id_asset', $id)->orderBy('period', 'asc')->orderBy('date', 'asc')->get();

        return view('/components/salary', compact('asset', 'salary'));
    }

    public function update(Request $request) {
        $salaryData = [
            'period' => $request->s_period,
            'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->s_date)->toDateString(),
            'amount_paid' => $request->s_amount_paid,
            'description' =>$request->s_description,
        ];

        Salary::where('id_salary', $request->id)->update($salaryData);
        return redirect()->back();
    }

    public function delete($id) {
        Salary::where('id_salary', $id)->delete();
        return redirect()->back();
    }
}
