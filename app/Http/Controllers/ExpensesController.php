<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fuel;
use App\Models\Material;
use App\Models\Salary;
use App\Models\Sparepart;
use App\Models\Unexpected;

class ExpensesController extends Controller
{
    public function store(Request $request) {
        $expenseCategory = $request->expense_category;
        
        if($expenseCategory == 'Unexpected') {
            Unexpected::insert([
                'id_asset' => $request->id,
                'name' => $request->ux_name,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->ux_date)->toDateString(),
                'price' => $request->ux_price,
                'description' =>$request->ux_description,
            ]);

            return redirect()->back();
        }

        if($expenseCategory == 'Material') {
            Material::insert([
                'id_asset' => $request->id,
                'name' => $request->m_name,
                'purchase_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->m_purchase_date)->toDateString(),
                'amount' => $request->m_amount,
                'purchase_price' => $request->m_purchase_price,
                'description' =>$request->m_description,
            ]);

            return redirect()->back();
        }

        if($expenseCategory == 'Salary') {
            Salary::insert([
                'id_asset' => $request->id,
                'period' => $request->s_period,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->s_date)->toDateString(),
                'amount_paid' => $request->s_amount_paid,
                'description' =>$request->s_description,
            ]);

            return redirect()->back();
        }

        if($expenseCategory == 'Sparepart') {
            Sparepart::insert([
                'id_asset' => $request->id,
                'name' => $request->sp_name,
                'purchase_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->sp_purchase_date)->toDateString(),
                'price' => $request->sp_price,
                'description' =>$request->sp_description,
            ]);

            return redirect()->back();
        }

        if($expenseCategory == 'Fuel') {
            Fuel::insert([
                'id_asset' => $request->id,
                'name' => $request->f_name,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->f_date)->toDateString(),
                'price' => $request->f_price,
            ]);

            return redirect()->back();
        }
    }
}
