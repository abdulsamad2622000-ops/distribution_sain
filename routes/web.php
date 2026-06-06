<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::get('/', fn() => redirect()->route('login'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/ledger', [CustomerController::class, 'ledger'])->name('customers.ledger');

    Route::resource('sales', SaleController::class);
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');

    Route::get('/recoveries', [RecoveryController::class, 'index'])->name('recoveries.index');
    Route::get('/recoveries/create', [RecoveryController::class, 'create'])->name('recoveries.create');
    Route::post('/recoveries', [RecoveryController::class, 'store'])->name('recoveries.store');
    Route::delete('/recoveries/{recovery}', [RecoveryController::class, 'destroy'])->name('recoveries.destroy');

    Route::resource('expenses', ExpenseController::class);
Route::get('/reports/profit-loss/pdf', [ReportController::class, 'profitLossPdf'])->name('reports.profit-loss.pdf');
Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
Route::get('/reports/recovery/pdf', [ReportController::class, 'recoveryPdf'])->name('reports.recovery.pdf');
Route::get('/reports/customer-ledger/pdf', [ReportController::class, 'customerLedgerPdf'])->name('reports.customer-ledger.pdf');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/recovery', [ReportController::class, 'recovery'])->name('reports.recovery');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
    Route::get('/reports/customer-ledger', [ReportController::class, 'customerLedger'])->name('reports.customer-ledger');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
    });
});