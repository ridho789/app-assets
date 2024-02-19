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
        $numericSalary = preg_replace("/[^0-9]/", "", explode(",", $request->s_amount_paid)[0]);

        if ($request->s_amount_paid[0] === '-') {
            $numericSalary *= -1;
        }

        $salaryData = [
            'period' => $request->s_period,
            'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->s_date)->toDateString(),
            'amount_paid' => $numericSalary,
            'description' =>$request->s_description,
        ];

        Salary::where('id_salary', $request->id)->update($salaryData);
        return redirect()->back();
    }

    public function delete($id) {
        Salary::where('id_salary', $id)->delete();
        return redirect()->back();
    }

    public function search(Request $request, $id) {
        $search = $request->search;
        $id = Crypt::decrypt($id);
        $asset = Asset::where('id_asset', $id)->first();

        $salary = Salary::where('id_asset', $id)->where('date', 'like', "%$search%")->orderBy('period', 'asc')->orderBy('date', 'asc')->get();

        if ($salary->isEmpty()) {
            return redirect()->back();
        }

        return view('/components/salary', compact('asset', 'salary'));
    }
}
