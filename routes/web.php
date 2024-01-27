<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
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

Route::get('/', function () {
    return view('/auth/login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('login-auth', [LoginController::class, 'authenticate']);
Route::get('logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
    Route::get('asset-search', [DashboardController::class, 'search'])->middleware('auth');

    // asset
    Route::get('/asset-create', [AssetsController::class, 'create'])->middleware('auth');
    Route::post('asset-store', [AssetsController::class, 'store']);
    Route::get('asset-edit/{id_asset}', [AssetsController::class, 'edit'])->middleware('auth');
    Route::post('asset-update', [AssetsController::class, 'update']);
    
    // component
    Route::post('expense-store', [ExpensesController::class, 'store']);
    
    Route::get('/unexpected-index/{id_asset}', [UnexpectedController::class, 'index'])->middleware('auth');
    Route::post('unexpected-update', [UnexpectedController::class, 'update']);
    Route::get('unexpected-delete/{id_unexpected}', [UnexpectedController::class, 'delete']);
    
    Route::get('/material-index/{id_asset}', [MaterialsController::class, 'index'])->middleware('auth');
    Route::post('material-update', [MaterialsController::class, 'update']);
    Route::get('material-delete/{id_material}', [MaterialsController::class, 'delete']);
    
    Route::get('/salary-index/{id_asset}', [SalaryController::class, 'index'])->middleware('auth');
    Route::post('salary-update', [SalaryController::class, 'update']);
    Route::get('salary-delete/{id_salary}', [SalaryController::class, 'delete']);
    
    Route::get('/sparepart-index/{id_asset}', [SparepartsController::class, 'index'])->middleware('auth');
    Route::post('sparepart-update', [SparepartsController::class, 'update']);
    Route::get('sparepart-delete/{id_sparepart}', [SparepartsController::class, 'delete']);
    
    Route::get('/fuel-index/{id_asset}', [FuelController::class, 'index'])->middleware('auth');
    Route::post('fuel-update', [FuelController::class, 'update']);
    Route::get('fuel-delete/{id_fuel}', [FuelController::class, 'delete']);
    
    // report
    Route::get('asset-pdf-report-with-details/{id_asset}', [AssetsController::class, 'report_with_details']);
    Route::get('asset-pdf-report-without-details/{id_asset}', [AssetsController::class, 'report_without_details']);
    Route::get('report-excel/{id}', [ReportController::class, 'reportExcel']);
});