<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\Fuel;
use App\Models\Material;
use App\Models\Salary;
use App\Models\Sparepart;
use App\Models\Unexpected;
use App\Models\Expense;
use Illuminate\Support\Facades\Crypt;

class ExpensesController extends Controller
{
    public function store(Request $request) {
        $numericPrice = preg_replace("/[^0-9]/", "", explode(",", $request->price)[0]);

        if ($request->price[0] === '-') {
            $numericPrice *= -1;
        }

        $existingRecord = Expense::where('id_asset', $request->id)
            ->where('id_category', $request->id_category)
            ->where('name', $request->name)
            ->where('date', \Carbon\Carbon::createFromFormat('m/d/Y', $request->date)->toDateString())
            ->where('price', $numericPrice)
            ->where('desc', $request->description)
            ->first();

        if ($existingRecord) {
            return redirect()->back()->with([
                'error' => 'Your data is already in the system.',
                'error_type' => 'sweet-alert',
                'input' => $request->all(),
            ]);

        } else {
            Expense::insert([
                'id_asset' => $request->id,
                'id_category' => $request->id_category,
                'name' => $request->name,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->date)->toDateString(),
                'price' => $numericPrice,
                'desc' =>$request->description,
            ]);

            $asset = Asset::where('id_asset', $request->id)->first();
            if ($asset->status === 'No Activity') {
                $asset->update(['status' => 'On Progress']);
            }

            return redirect()->back();
        }
    }

    public function showCategoryExpenses($category, $id_asset) {
        $id_asset = Crypt::decrypt($id_asset);
        $asset = Asset::where('id_asset', $id_asset)->first();
        $formattedCategory = strtoupper(str_replace('-', ' ', $category));

        $expenses = Expense::with('category')
            ->whereHas('category', function ($query) use ($formattedCategory) {
                $query->where('name', $formattedCategory);
            })
            ->where('id_asset', $id_asset)
            ->get();

        return view('components.expense', compact('asset', 'expenses', 'category', 'formattedCategory'));
    }

    public function updateExpense(Request $request) {
        $numericPrice = preg_replace("/[^0-9]/", "", explode(",", $request->price)[0]);

        if ($request->price[0] === '-') {
            $numericPrice *= -1;
        }

        $dataExpense = [
            'name' => $request->name,
            'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->date)->toDateString(),
            'price' => $numericPrice,
            'desc' =>$request->desc,
        ];

        Expense::where('id_expense', $request->id)->update($dataExpense);
        return redirect()->back();
    }

    public function deleteExpense($id) {
        $id_expense = Crypt::decrypt($id);
        Expense::where('id_expense', $id_expense)->delete();
        return redirect()->back();
    }
    
}