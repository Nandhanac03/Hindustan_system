<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

// Fallback routes to serve storage files directly if public/storage symlink is not created or accessible
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    if (!file_exists($file)) {
        abort(404);
    }
    return response()->file($file);
})->where('path', '.*')->name('storage.local');

Route::get('/{any}/storage/{path}', function ($any, $path) {
    $file = storage_path('app/public/' . $path);
    if (!file_exists($file)) {
        abort(404);
    }
    return response()->file($file);
})->where('any', '.*')->where('path', '.*')->name('storage.local.any');

// Authenticated and active system routes
Route::middleware(['auth', 'system.active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Approvals Inbox
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    // User Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Project Settings (Single project configuration)
    // Route::get('project-settings', [ProjectController::class, 'settings'])->name('project.settings');
    // Route::put('project-settings', [ProjectController::class, 'updateSettings'])->name('project.settings.update');
    Route::get('project/{project}/bulk-generate', [ProjectController::class, 'bulkGenerateShow'])->name('project.bulk-generate');
    Route::post('project/{project}/bulk-generate', [ProjectController::class, 'bulkGenerateStore'])->name('project.bulk-generate.store');
    Route::resource('projects', ProjectController::class);

    Route::get('units/{unit}/json', [UnitController::class, 'showJson'])->name('units.json');
    Route::post('units/{unit}/rate', [UnitController::class, 'updateRate'])->name('units.update-rate');
    Route::post('units/{unit}/status', [UnitController::class, 'updateStatus'])->name('units.update-status');

    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
    Route::post('/units/bulk', [UnitController::class, 'bulkStore'])->name('units.bulk-store');

    // EMI & Collections Module (linked to Sales → Receipts workflow)
    Route::get('/emi-collections',                      [\App\Http\Controllers\EmiCollectionController::class, 'index'])->name('emi-collections.index');
    Route::get('/emi-collections/schedules',            [\App\Http\Controllers\EmiCollectionController::class, 'schedules'])->name('emi-collections.schedules');
    Route::post('/emi-collections/schedules/generate',  [\App\Http\Controllers\EmiCollectionController::class, 'generateSchedule'])->name('emi-collections.schedules.generate');
    Route::delete('/emi-collections/schedules/{sale}',  [\App\Http\Controllers\EmiCollectionController::class, 'deleteSchedule'])->name('emi-collections.schedules.delete');
    Route::post('/emi-collections/schedules/{sale}/bulk-update', [\App\Http\Controllers\EmiCollectionController::class, 'bulkUpdateSchedule'])->name('emi-collections.schedules.bulk-update');
    Route::put('/emi-collections/installments/{installment}', [\App\Http\Controllers\EmiCollectionController::class, 'updateInstallment'])->name('emi-collections.installments.update');
    Route::get('/emi-collections/receipts',             [\App\Http\Controllers\EmiCollectionController::class, 'receipts'])->name('emi-collections.receipts');
    Route::post('/emi-collections/receipts',            [\App\Http\Controllers\EmiCollectionController::class, 'store'])->name('emi-collections.store');
    Route::get('/emi-collections/outstanding',          [\App\Http\Controllers\EmiCollectionController::class, 'outstanding'])->name('emi-collections.outstanding');
    Route::get('/emi-collections/cash-book',            [\App\Http\Controllers\EmiCollectionController::class, 'cashBook'])->name('emi-collections.cash-book');
    Route::get('/emi-collections/ledger/{sale}',        [\App\Http\Controllers\EmiCollectionController::class, 'customerLedger'])->name('emi-collections.ledger');
    Route::get('/emi-collections/loans',                [\App\Http\Controllers\EmiCollectionController::class, 'loans'])->name('emi-collections.loans');
    Route::post('/emi-collections/loans',               [\App\Http\Controllers\EmiCollectionController::class, 'storeLoan'])->name('emi-collections.loans.store');
    Route::get('/emi-collections/loans/{loan}/amortization', [\App\Http\Controllers\EmiCollectionController::class, 'loanAmortization'])->name('emi-collections.loans.amortization');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Sales Module (primary sales flow: Sale → Receipt → EMI)
    Route::get('/sales', [\App\Http\Controllers\SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [\App\Http\Controllers\SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [\App\Http\Controllers\SalesController::class, 'show'])->name('sales.show');
    Route::put('/sales/{id}', [\App\Http\Controllers\SalesController::class, 'update'])->name('sales.update');
    Route::post('/sales/{id}/receipt', [\App\Http\Controllers\SalesController::class, 'addReceipt'])->name('sales.add-receipt');
    Route::post('/sales/{id}/status', [\App\Http\Controllers\SalesController::class, 'changeStatus'])->name('sales.change-status');
    Route::get('/sales/available-units/{projectId}', [\App\Http\Controllers\SalesController::class, 'availableUnits'])->name('sales.available-units');

    // Sales Register & Bookings (legacy — kept for backward compat)
    Route::resource('bookings', \App\Http\Controllers\BookingController::class)->only(['index', 'create', 'store']);
    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/resale', [\App\Http\Controllers\BookingController::class, 'resale'])->name('bookings.resale');

    // Bank Master
    Route::get('/bank', [\App\Http\Controllers\BankController::class, 'index'])->name('bank.index');
    Route::post('/bank', [\App\Http\Controllers\BankController::class, 'store'])->name('bank.store');
    Route::put('/bank/{bank}', [\App\Http\Controllers\BankController::class, 'update'])->name('bank.update');
    Route::delete('/bank/{bank}', [\App\Http\Controllers\BankController::class, 'destroy'])->name('bank.destroy');

    // GST Master
    Route::get('/gst', function() {
        return view('gst.index');
    })->name('gst.index');

    // Floor & Unit Type Master
    Route::resource('floors', \App\Http\Controllers\FloorController::class)->except(['create', 'show', 'edit']);
    Route::resource('unit-types', \App\Http\Controllers\UnitTypeController::class)->except(['create', 'show', 'edit']);

    // Partner Management
    Route::get('/partners', [\App\Http\Controllers\PartnerController::class, 'index'])->name('partners.index');
    Route::post('/partners', [\App\Http\Controllers\PartnerController::class, 'storePartner'])->name('partners.store');
    Route::get('/partners/shares/{project}', [\App\Http\Controllers\PartnerController::class, 'shares'])->name('partners.shares');
    Route::post('/partners/shares/{project}', [\App\Http\Controllers\PartnerController::class, 'updateShares'])->name('partners.shares.update');
    Route::get('/partners/{partner}/statement', [\App\Http\Controllers\PartnerController::class, 'statement'])->name('partners.statement');
    Route::post('/partners/{partner}/payout', [\App\Http\Controllers\PartnerController::class, 'recordPayout'])->name('partners.payout');
    Route::delete('/partners/{partner}', [\App\Http\Controllers\PartnerController::class, 'destroy'])->name('partners.destroy');

    // Brokerage & Commission Management
    Route::get('/brokers', [\App\Http\Controllers\BrokerController::class, 'index'])->name('brokers.index');
    Route::post('/brokers', [\App\Http\Controllers\BrokerController::class, 'store'])->name('brokers.store');
    Route::put('/brokers/{broker}', [\App\Http\Controllers\BrokerController::class, 'update'])->name('brokers.update');
    Route::get('/brokers/payable-report', [\App\Http\Controllers\BrokerController::class, 'payableReport'])->name('brokers.payable-report');
    Route::post('/brokers/payout', [\App\Http\Controllers\BrokerController::class, 'recordPayout'])->name('brokers.payout');

    // Customers
    Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    //Sales
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/available-units/{project}', [SalesController::class, 'availableUnits'])->name('sales.available-units');
    Route::get('/sales/{sale}/json', [SalesController::class, 'show'])->name('sales.show');
    Route::put('/sales/{sale}', [SalesController::class, 'update'])->name('sales.update');
    Route::post('/sales/{sale}/status', [SalesController::class, 'changeStatus'])->name('sales.status');
    Route::post('/sales/{sale}/receipts', [SalesController::class, 'addReceipt'])->name('sales.receipts.store');

    // Bank Loans Repayment Schedule Management
    Route::get('/loans', [\App\Http\Controllers\LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [\App\Http\Controllers\LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/reports', [\App\Http\Controllers\LoanController::class, 'reports'])->name('loans.reports');
    Route::get('/loans/{loan}/schedule', [\App\Http\Controllers\LoanController::class, 'showSchedule'])->name('loans.schedule');
    Route::post('/loans/{loan}/pay-emi/{installment}', [\App\Http\Controllers\LoanController::class, 'payEmi'])->name('loans.pay-emi');
    Route::post('/loans/{loan}/prepay', [\App\Http\Controllers\LoanController::class, 'prepay'])->name('loans.prepay');
    Route::post('/loans/{loan}/update-interest', [\App\Http\Controllers\LoanController::class, 'updateInterest'])->name('loans.update-interest');

    // Site Expenses Module
    Route::get('/expenses/bills/create', [\App\Http\Controllers\ExpenseController::class, 'createBill'])->name('expenses.bills.create');
    Route::post('/expenses/bills', [\App\Http\Controllers\ExpenseController::class, 'storeBill'])->name('expenses.bills.store');
    Route::get('/expenses/bills', [\App\Http\Controllers\ExpenseController::class, 'listBills'])->name('expenses.bills.index');
    Route::get('/expenses/ledger', [\App\Http\Controllers\ExpenseController::class, 'expenseLedger'])->name('expenses.ledger');
    Route::get('/expenses/project/{project}/metrics', [\App\Http\Controllers\ExpenseController::class, 'projectMetrics'])->name('expenses.project.metrics');

    // Suppliers Master Module
    Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [\App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Employee Master Module
    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Vouchers & Core Accounting Engine
    Route::get('/vouchers/approvals', [\App\Http\Controllers\VoucherController::class, 'approvalsIndex'])->name('vouchers.approvals');
    Route::post('/vouchers/{id}/approve', [\App\Http\Controllers\VoucherController::class, 'approveVoucher'])->name('vouchers.approve');
    Route::get('/vouchers/source-details', [\App\Http\Controllers\VoucherController::class, 'fetchSourceDetails'])->name('vouchers.source.details');
    Route::get('/vouchers/project/{projectId}/units', [\App\Http\Controllers\VoucherController::class, 'getProjectUnits'])->name('vouchers.project.units');
    Route::get('/vouchers/receipt', [\App\Http\Controllers\VoucherController::class, 'createReceipt'])->name('vouchers.receipt.create');
    Route::post('/vouchers/receipt', [\App\Http\Controllers\VoucherController::class, 'storeReceipt'])->name('vouchers.receipt.store');
    Route::get('/vouchers/receipt/{id}/posted', [\App\Http\Controllers\VoucherController::class, 'receiptPosted'])->name('vouchers.receipt.posted');
    Route::get('/api/receipt/targets', [\App\Http\Controllers\VoucherController::class, 'receiptTargets'])->name('api.receipt.targets');
    Route::get('/api/receipt/{id}/detail', [\App\Http\Controllers\VoucherController::class, 'receiptDetail'])->name('api.receipt.detail');
    Route::get('/api/receipts/unallocated', [\App\Http\Controllers\VoucherController::class, 'unallocatedReceipts'])->name('api.receipts.unallocated');
    Route::get('/vouchers/payment', [\App\Http\Controllers\VoucherController::class, 'createPayment'])->name('vouchers.payment.create');
    Route::post('/vouchers/payment', [\App\Http\Controllers\VoucherController::class, 'storePayment'])->name('vouchers.payment.store');
    Route::get('/vouchers/contra', [\App\Http\Controllers\VoucherController::class, 'createContra'])->name('vouchers.contra.create');
    Route::post('/vouchers/contra', [\App\Http\Controllers\VoucherController::class, 'storeContra'])->name('vouchers.contra.store');
    Route::get('/vouchers/journal', [\App\Http\Controllers\VoucherController::class, 'createJournal'])->name('vouchers.journal.create');
    Route::post('/vouchers/journal', [\App\Http\Controllers\VoucherController::class, 'storeJournal'])->name('vouchers.journal.store');
    Route::get('/vouchers/sales-purchase', [\App\Http\Controllers\VoucherController::class, 'createSalesPurchase'])->name('vouchers.sales-purchase.create');
    Route::post('/vouchers/sales-purchase', [\App\Http\Controllers\VoucherController::class, 'storeSalesPurchase'])->name('vouchers.sales-purchase.store');
    Route::get('/vouchers/ledger-directory', [\App\Http\Controllers\VoucherController::class, 'ledgerIndex'])->name('vouchers.ledger.index');
    Route::get('/vouchers/cash-book', [\App\Http\Controllers\VoucherController::class, 'cashBook'])->name('vouchers.cash-book');
    Route::get('/vouchers/bank-book', [\App\Http\Controllers\VoucherController::class, 'bankBook'])->name('vouchers.bank-book');
    Route::get('/vouchers/entity-ledger', [\App\Http\Controllers\VoucherController::class, 'entityLedger'])->name('vouchers.entity-ledger');

    // ── Cash Management ───────────────────────────────────────────────────────
    // Route::get('/cash/petty-cash',          [\App\Http\Controllers\CashManagementController::class, 'pettyCashIndex'])->name('cash.petty-cash.index');
    // Route::get('/cash/petty-cash/create',   [\App\Http\Controllers\CashManagementController::class, 'pettyCashCreate'])->name('cash.petty-cash.create');
    // Route::post('/cash/petty-cash',         [\App\Http\Controllers\CashManagementController::class, 'pettyCashStore'])->name('cash.petty-cash.store');
    // Route::get('/cash/denomination',        [\App\Http\Controllers\CashManagementController::class, 'denominationIndex'])->name('cash.denomination.index');
    // Route::get('/cash/denomination/create', [\App\Http\Controllers\CashManagementController::class, 'denominationCreate'])->name('cash.denomination.create');
    // Route::post('/cash/denomination',       [\App\Http\Controllers\CashManagementController::class, 'denominationStore'])->name('cash.denomination.store');
    // Route::get('/cash/reconciliation',      [\App\Http\Controllers\CashManagementController::class, 'reconciliation'])->name('cash.reconciliation');
});

require __DIR__ . '/auth.php';