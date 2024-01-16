<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\UnexpectedController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SparepartsController;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// });

Route::get('/', [DashboardController::class, 'index']);
Route::get('asset-search', [DashboardController::class, 'search']);

Route::get('/asset-create', [AssetsController::class, 'create']);
Route::post('asset-store', [AssetsController::class, 'store']);
Route::get('asset-edit/{id_asset}', [AssetsController::class, 'edit']);
Route::post('asset-update', [AssetsController::class, 'update']);
Route::get('asset-pdf-report/{id_asset}', [AssetsController::class, 'report']);

Route::post('expense-store', [ExpensesController::class, 'store']);

Route::get('/unexpected-index/{id_asset}', [UnexpectedController::class, 'index']);
Route::post('unexpected-update', [UnexpectedController::class, 'update']);

Route::get('/material-index/{id_asset}', [MaterialsController::class, 'index']);
Route::post('material-update', [MaterialsController::class, 'update']);

Route::get('/salary-index/{id_asset}', [SalaryController::class, 'index']);
Route::post('salary-update', [SalaryController::class, 'update']);

Route::get('/sparepart-index/{id_asset}', [SparepartsController::class, 'index']);
Route::post('sparepart-update', [SparepartsController::class, 'update']);

Route::get('/fuel-index/{id_asset}', [FuelController::class, 'index']);
Route::post('fuel-update', [FuelController::class, 'update']);

Route::get('report-excel/{id}', [ReportController::class, 'reportExcel']);