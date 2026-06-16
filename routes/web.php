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
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\GoodsReceivedNoteController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AttendanceController;

Route::get('/', fn() => redirect()->route('login'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/ledger',     [CustomerController::class, 'ledger'])->name('customers.ledger');
    Route::get('/customers/{customer}/ledger/pdf', [CustomerController::class, 'ledgerPdf'])->name('customers.ledger.pdf');
    Route::get('/customers/list/pdf',              [CustomerController::class, 'listPdf'])->name('customers.index.pdf');

    // Sales
    Route::resource('sales', SaleController::class);
    Route::get('/sales/{sale}/print', [SaleController::class, 'invoice'])->name('sales.print');

    // Recoveries
    Route::get('/recoveries',               [RecoveryController::class, 'index'])->name('recoveries.index');
    Route::get('/recoveries/pending',       [RecoveryController::class, 'pending'])->name('recoveries.pending');
    Route::get('/recoveries/create',        [RecoveryController::class, 'create'])->name('recoveries.create');
    Route::post('/recoveries',              [RecoveryController::class, 'store'])->name('recoveries.store');
    Route::delete('/recoveries/{recovery}', [RecoveryController::class, 'destroy'])->name('recoveries.destroy');

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Reports
    Route::get('/reports',                     [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales',               [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/recovery',            [ReportController::class, 'recovery'])->name('reports.recovery');
    Route::get('/reports/stock',               [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/profit-loss',         [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
    Route::get('/reports/customer-ledger',     [ReportController::class, 'customerLedger'])->name('reports.customer-ledger');
    Route::get('/reports/profit-loss/pdf',     [ReportController::class, 'profitLossPdf'])->name('reports.profit-loss.pdf');
    Route::get('/reports/sales/pdf',           [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
    Route::get('/reports/recovery/pdf',        [ReportController::class, 'recoveryPdf'])->name('reports.recovery.pdf');
    Route::get('/reports/customer-ledger/pdf', [ReportController::class, 'customerLedgerPdf'])->name('reports.customer-ledger.pdf');

    // Purchase Orders
    Route::get('/purchase-orders',                          [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('/purchase-orders/create',                   [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/purchase-orders',                         [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/purchase-orders/{purchaseOrder}',          [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('/purchase-orders/{purchaseOrder}/edit',     [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('/purchase-orders/{purchaseOrder}',          [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    Route::post('/purchase-orders/{purchaseOrder}/cancel',  [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');
    Route::delete('/purchase-orders/{purchaseOrder}',       [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

    // GRN
    Route::get('/grns',        [GoodsReceivedNoteController::class, 'index'])->name('grns.index');
    Route::get('/grns/create', [GoodsReceivedNoteController::class, 'create'])->name('grns.create');
    Route::post('/grns',       [GoodsReceivedNoteController::class, 'store'])->name('grns.store');
    Route::get('/grns/{grn}',  [GoodsReceivedNoteController::class, 'show'])->name('grns.show');

    // Warehouses
    Route::resource('warehouses', WarehouseController::class);
    Route::post('/warehouses/{warehouse}/adjust', [WarehouseController::class, 'adjust'])->name('warehouses.adjust');

    // Accounting
    Route::resource('accounts', AccountController::class);
    Route::get('/trial-balance',    [AccountController::class, 'trialBalance'])->name('accounts.trial-balance');
    Route::get('/balance-sheet',    [AccountController::class, 'balanceSheet'])->name('accounts.balance-sheet');
    Route::get('/income-statement', [AccountController::class, 'incomeStatement'])->name('accounts.income-statement');
    Route::resource('journal-entries', JournalEntryController::class);

    // HR & Payroll
    Route::resource('employees', EmployeeController::class);
    Route::resource('salaries', SalaryController::class);
    Route::post('/salaries/{salary}/mark-paid', [SalaryController::class, 'markPaid'])->name('salaries.mark-paid');
    Route::get('/attendance',        [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance',       [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);

        Route::get('/settings/company', [CompanySettingController::class, 'edit'])->name('settings.company.edit');
        Route::put('/settings/company', [CompanySettingController::class, 'update'])->name('settings.company.update');
    });

});