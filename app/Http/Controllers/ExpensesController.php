<?php

namespace App\Http\Controllers;

use App\Models\Asset;
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
            $numericPrice = preg_replace("/[^0-9]/", "", explode(",", $request->ux_price)[0]);

            if ($request->ux_price[0] === '-') {
                $numericPrice *= -1;
            }

            Unexpected::insert([
                'id_asset' => $request->id,
                'name' => $request->ux_name,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->ux_date)->toDateString(),
                'price' => $numericPrice,
                'description' =>$request->ux_description,
            ]);

            return redirect()->back();
        }

        if($expenseCategory == 'Material') {
            $numericPurchasePrice = preg_replace("/[^0-9]/", "", explode(",", $request->m_purchase_price)[0]);

            if ($request->m_purchase_price[0] === '-') {
                $numericPurchasePrice *= -1;
            }

            Material::insert([
                'id_asset' => $request->id,
                'name' => $request->m_name,
                'purchase_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->m_purchase_date)->toDateString(),
                'amount' => $request->m_amount,
                'purchase_price' => $numericPurchasePrice,
                'description' =>$request->m_description,
            ]);

            $asset = Asset::where('id_asset', $request->id)->first();
            if ($asset->status === 'No Activity') {
                $asset->update(['status' => 'On Progress']);
            }

            return redirect()->back();
        }

        if($expenseCategory == 'Salary') {
            $numericSalary = preg_replace("/[^0-9]/", "", explode(",", $request->s_amount_paid)[0]);

            if ($request->s_amount_paid[0] === '-') {
                $numericSalary *= -1;
            }

            Salary::insert([
                'id_asset' => $request->id,
                'period' => $request->s_period,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->s_date)->toDateString(),
                'amount_paid' => $numericSalary,
                'description' =>$request->s_description,
            ]);

            $asset = Asset::where('id_asset', $request->id)->first();
            if ($asset->status === 'No Activity') {
                $asset->update(['status' => 'On Progress']);
            }

            return redirect()->back();
        }

        if($expenseCategory == 'Sparepart') {
            $numericSparepart = preg_replace("/[^0-9]/", "", explode(",", $request->sp_price)[0]);

            if ($request->sp_price[0] === '-') {
                $numericSparepart *= -1;
            }

            Sparepart::insert([
                'id_asset' => $request->id,
                'name' => $request->sp_name,
                'purchase_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->sp_purchase_date)->toDateString(),
                'price' => $numericSparepart,
                'description' =>$request->sp_description,
            ]);

            $asset = Asset::where('id_asset', $request->id)->first();
            if ($asset->status === 'No Activity') {
                $asset->update(['status' => 'On Progress']);
            }

            return redirect()->back();
        }

        if($expenseCategory == 'Fuel') {
            $numericFuel = preg_replace("/[^0-9]/", "", explode(",", $request->f_price)[0]);

            if ($request->f_price[0] === '-') {
                $numericFuel *= -1;
            }

            Fuel::insert([
                'id_asset' => $request->id,
                'name' => $request->f_name,
                'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->f_date)->toDateString(),
                'price' => $numericFuel,
                'description' =>$request->f_description,
            ]);

            $asset = Asset::where('id_asset', $request->id)->first();
            if ($asset->status === 'No Activity') {
                $asset->update(['status' => 'On Progress']);
            }

            return redirect()->back();
        }
    }
}