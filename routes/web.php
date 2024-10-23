<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;

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

    // master data - category
    Route::get('/category', [CategoryController::class, 'index'])->middleware('auth');
    Route::post('category-store', [CategoryController::class, 'store']);
    Route::post('category-update', [CategoryController::class, 'update']);
    
    // component
    Route::post('expense-store', [ExpensesController::class, 'store'])->middleware('auth');
    Route::get('/{category}/{id_asset}', [ExpensesController::class, 'showCategoryExpenses'])->middleware('auth');
    Route::post('/update-{category}', [ExpensesController::class, 'updateExpense']);
    Route::get('/delete-expense-{id_expense}', [ExpensesController::class, 'deleteExpense']);
    
    // report
    Route::get('report-pdf-{id_asset}', [AssetsController::class, 'reportPDF']);
    Route::get('report-excel-{id_asset}', [ReportController::class, 'reportExcel']);
});