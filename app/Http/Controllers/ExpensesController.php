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

            $existingUnexpectedRecord = Unexpected::where('id_asset', $request->id)->where('name', $request->ux_name)->where(
                'date', \Carbon\Carbon::createFromFormat('m/d/Y', $request->ux_date
                )->toDateString())->where('price', $numericPrice)->where('description', $request->ux_description)->first();

            if ($existingUnexpectedRecord) {
                return redirect()->back()->with([
                    'error' => 'Unexpected data you added is already in the system',
                    'error_type' => 'sweet-alert',
                    'input' => $request->all(),
                ]);

            } else {
                Unexpected::insert([
                    'id_asset' => $request->id,
                    'name' => $request->ux_name,
                    'date' => \Carbon\Carbon::createFromFormat('m/d/Y', $request->ux_date)->toDateString(),
                    'price' => $numericPrice,
                    'description' =>$request->ux_description,
                ]);

                return redirect()->back();
            }
        }

        if($expenseCategory == 'Material') {
            $numericPurchasePrice = preg_replace("/[^0-9]/", "", explode(",", $request->m_purchase_price)[0]);

            if ($request->m_purchase_price[0] === '-') {
                $numericPurchasePrice *= -1;
            }

            $existingMaterialRecord = Material::where('id_asset', $request->id)
                ->where('name', $request->m_name)
                ->where('purchase_date', \Carbon\Carbon::createFromFormat('m/d/Y', $request->m_purchase_date)->toDateString())
                ->where('amount', $request->m_amount)
                ->where('purchase_price', $numericPurchasePrice)
                ->where('description', $request->m_description)
                ->first();

            if ($existingMaterialRecord) {
                return redirect()->back()->with([
                    'error' => 'Material data you added is already in the system',
                    'error_type' => 'sweet-alert',
                    'input' => $request->all(),
                ]);

            } else {
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
        }

        if($expenseCategory == 'Salary') {
            $numericSalary = preg_replace("/[^0-9]/", "", explode(",", $request->s_amount_paid)[0]);

            if ($request->s_amount_paid[0] === '-') {
                $numericSalary *= -1;
            }

            $existingSalaryRecord = Salary::where('id_asset', $request->id)
                ->where('period', $request->s_period)
                ->where('date', \Carbon\Carbon::createFromFormat('m/d/Y', $request->s_date)->toDateString())
                ->where('amount_paid', $numericSalary)
                ->where('description', $request->s_description)
                ->first();

            if ($existingSalaryRecord) {
                return redirect()->back()->with([
                    'error' => 'Salary data you added is already in the system',
                    'error_type' => 'sweet-alert',
                    'input' => $request->all(),
                ]);

            } else {
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
        }

        if($expenseCategory == 'Sparepart') {
            $numericSparepart = preg_replace("/[^0-9]/", "", explode(",", $request->sp_price)[0]);

            if ($request->sp_price[0] === '-') {
                $numericSparepart *= -1;
            }

            $existingSparepartRecord = Sparepart::where('id_asset', $request->id)
                ->where('name', $request->sp_name)
                ->where('purchase_date', \Carbon\Carbon::createFromFormat('m/d/Y', $request->sp_purchase_date)->toDateString())
                ->where('price', $numericSparepart)
                ->where('description', $request->sp_description)
                ->first();

            if ($existingSparepartRecord) {
                return redirect()->back()->with([
                    'error' => 'Sparepart data you added is already in the system',
                    'error_type' => 'sweet-alert',
                    'input' => $request->all(),
                ]);

            } else {
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
        }

        if($expenseCategory == 'Fuel') {
            $numericFuel = preg_replace("/[^0-9]/", "", explode(",", $request->f_price)[0]);

            if ($request->f_price[0] === '-') {
                $numericFuel *= -1;
            }

            $existingFuelRecord = Fuel::where('id_asset', $request->id)
                ->where('name', $request->f_name)
                ->where('date', \Carbon\Carbon::createFromFormat('m/d/Y', $request->f_date)->toDateString())
                ->where('price', $numericFuel)
                ->where('description', $request->f_description)
                ->first();

            if ($existingFuelRecord) {
                return redirect()->back()->with([
                    'error' => 'Fuel data you added is already in the system',
                    'error_type' => 'sweet-alert',
                    'input' => $request->all(),
                ]);

            } else {
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
}