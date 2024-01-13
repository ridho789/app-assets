<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;

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
Route::post('asset-pdf-report', [AssetsController::class, 'report']);

Route::post('expense-store', [ExpensesController::class, 'store']);