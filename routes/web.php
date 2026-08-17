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

// Helper route to trigger, repair, sync, and diagnose storage link on live server
Route::get('/run-storage-link', function () {
    $target = storage_path('app/public');
    $shortcut = public_path('storage');
    $messages = [];

    // Ensure target storage directory exists
    if (!file_exists($target)) {
        @mkdir($target, 0755, true);
        $messages[] = "Created storage target directory: {$target}";
    }

    $symlinkSuccess = false;

    // Try to remove broken link or empty dir to make room for symlink
    if (is_link($shortcut)) {
        @unlink($shortcut);
        $messages[] = "Removed old symlink.";
    } elseif (is_dir($shortcut)) {
        $items = @scandir($shortcut);
        if ($items === false || count($items) <= 2) {
            @rmdir($shortcut);
            $messages[] = "Removed empty public/storage directory.";
        }
    }

    // 1. Attempt symlink creation
    if (!file_exists($shortcut) && !is_link($shortcut)) {
        if (@symlink($target, $shortcut)) {
            $symlinkSuccess = true;
            $messages[] = "SUCCESS: Created symlink via PHP symlink()!";
        } else {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
                if (is_link($shortcut) || file_exists($shortcut)) {
                    $symlinkSuccess = true;
                    $messages[] = "SUCCESS: Created symlink via Artisan storage:link!";
                }
            } catch (\Exception $e) {
                $messages[] = "Artisan storage:link error: " . $e->getMessage();
            }
        }
    } else {
        $symlinkSuccess = is_link($shortcut);
    }

    // 2. Fallback for Shared Hosting without symlink support: Copy files directly into public/storage
    if (!$symlinkSuccess || is_dir($shortcut) && !is_link($shortcut)) {
        $messages[] = "Symlink disabled or public/storage is directory. Performing direct file sync...";
        if (!file_exists($shortcut)) {
            @mkdir($shortcut, 0755, true);
        }
        
        // Recursive copy function
        $copyDir = function ($src, $dst) use (&$copyDir) {
            $dir = opendir($src);
            @mkdir($dst, 0755, true);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        $copyDir($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        @copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        };

        if (file_exists($target)) {
            $copyDir($target, $shortcut);
            $messages[] = "SUCCESS: Copied all storage files to public/storage!";
        }
    }

    // Count uploaded project images
    $projectFiles = [];
    $checkDir = is_dir($shortcut) ? $shortcut . '/projects' : $target . '/projects';
    if (file_exists($checkDir)) {
        $projectFiles = array_map('basename', glob($checkDir . '/*') ?: []);
    }

    return response()->json([
        'status' => 'completed',
        'target_path' => $target,
        'shortcut_path' => $shortcut,
        'is_symlink' => is_link($shortcut),
        'uploaded_projects_images' => $projectFiles,
        'messages' => $messages,
    ]);
});

// Helper route to clear all compiled view, route, config, and application caches on live server
Route::get('/clear-cache', function () {
    $results = [];
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $results['view_clear'] = 'SUCCESS';
    } catch (\Exception $e) {
        $results['view_clear'] = 'ERROR: ' . $e->getMessage();
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $results['cache_clear'] = 'SUCCESS';
    } catch (\Exception $e) {
        $results['cache_clear'] = 'ERROR: ' . $e->getMessage();
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        $results['config_clear'] = 'SUCCESS';
    } catch (\Exception $e) {
        $results['config_clear'] = 'ERROR: ' . $e->getMessage();
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $results['route_clear'] = 'SUCCESS';
    } catch (\Exception $e) {
        $results['route_clear'] = 'ERROR: ' . $e->getMessage();
    }

    if (function_exists('opcache_reset')) {
        @opcache_reset();
        $results['opcache_reset'] = 'SUCCESS';
    }

    return response()->json([
        'status' => 'Cache cleared successfully!',
        'details' => $results
    ]);
});

// Fallback route to serve storage files directly if public/storage symlink is bypassed or inaccessible
Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    if (!file_exists($file)) {
        $file = base_path('storage/app/public/' . $path);
    }
    if (!file_exists($file)) {
        abort(404, 'Storage file not found');
    }
    $mime = mime_content_type($file) ?: 'image/jpeg';
    return response()->file($file, ['Content-Type' => $mime]);
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
    Route::match(['patch', 'post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::match(['delete', 'post'], '/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
        Route::match(['put', 'post'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Project Settings
    Route::get('project/{project}/bulk-generate', [ProjectController::class, 'bulkGenerateShow'])->name('project.bulk-generate');
    Route::post('project/{project}/bulk-generate', [ProjectController::class, 'bulkGenerateStore'])->name('project.bulk-generate.store');
    Route::resource('projects', ProjectController::class);

    // Units Module
    Route::get('units/{unit}/json', [UnitController::class, 'showJson'])->name('units.json');
    Route::post('units/{unit}/rate', [UnitController::class, 'updateRate'])->name('units.update-rate');
    Route::post('units/{unit}/status', [UnitController::class, 'updateStatus'])->name('units.update-status');

    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::match(['put', 'post'], '/units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::match(['put', 'post'], '/units/{unit}/update', [UnitController::class, 'update'])->name('units.update.post');
    Route::match(['get', 'post', 'delete'], '/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
    Route::match(['get', 'post', 'delete'], '/units/{unit}/delete', [UnitController::class, 'destroy'])->name('units.destroy.post');
    Route::match(['get', 'post', 'delete'], '/units/{unit}/remove', [UnitController::class, 'destroy'])->name('units.destroy.remove');
    Route::post('/units/bulk', [UnitController::class, 'bulkStore'])->name('units.bulk-store');

    // EMI & Collections Module (linked to Sales → Receipts workflow)
    Route::get('/emi-collections',                      [\App\Http\Controllers\EmiCollectionController::class, 'index'])->name('emi-collections.index');
    Route::get('/emi-collections/schedules',            [\App\Http\Controllers\EmiCollectionController::class, 'schedules'])->name('emi-collections.schedules');
    Route::post('/emi-collections/schedules/generate',  [\App\Http\Controllers\EmiCollectionController::class, 'generateSchedule'])->name('emi-collections.schedules.generate');
    Route::match(['get', 'post', 'delete'], '/emi-collections/schedules/{sale}',  [\App\Http\Controllers\EmiCollectionController::class, 'deleteSchedule'])->name('emi-collections.schedules.delete');
    Route::post('/emi-collections/schedules/{sale}/bulk-update', [\App\Http\Controllers\EmiCollectionController::class, 'bulkUpdateSchedule'])->name('emi-collections.schedules.bulk-update');
    Route::match(['put', 'post'], '/emi-collections/installments/{installment}', [\App\Http\Controllers\EmiCollectionController::class, 'updateInstallment'])->name('emi-collections.installments.update');
    Route::get('/emi-collections/receipts',             [\App\Http\Controllers\EmiCollectionController::class, 'receipts'])->name('emi-collections.receipts');
    Route::post('/emi-collections/receipts',            [\App\Http\Controllers\EmiCollectionController::class, 'store'])->name('emi-collections.store');
    Route::get('/emi-collections/outstanding',          [\App\Http\Controllers\EmiCollectionController::class, 'outstanding'])->name('emi-collections.outstanding');
    Route::get('/emi-collections/cash-book',            [\App\Http\Controllers\EmiCollectionController::class, 'cashBook'])->name('emi-collections.cash-book');
    Route::get('/emi-collections/ledger/{sale}',        [\App\Http\Controllers\EmiCollectionController::class, 'customerLedger'])->name('emi-collections.ledger');
    Route::get('/emi-collections/loans',                [\App\Http\Controllers\EmiCollectionController::class, 'loans'])->name('emi-collections.loans');
    Route::post('/emi-collections/loans',               [\App\Http\Controllers\EmiCollectionController::class, 'storeLoan'])->name('emi-collections.loans.store');
    Route::get('/emi-collections/loans/{loan}/amortization', [\App\Http\Controllers\EmiCollectionController::class, 'loanAmortization'])->name('emi-collections.loans.amortization');
    // Reports Module (Separate Pages & Routes)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/gst', [\App\Http\Controllers\ReportController::class, 'gst'])->name('gst_report');
        Route::get('/activity-statements', [\App\Http\Controllers\ReportController::class, 'activityStatements'])->name('activity_statements');
        Route::get('/availability', [\App\Http\Controllers\ReportController::class, 'availability'])->name('availability');
        Route::get('/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('sales');
        Route::get('/emi-collections', [\App\Http\Controllers\ReportController::class, 'emiCollections'])->name('emi_collections');
        Route::get('/customer-ledger', [\App\Http\Controllers\ReportController::class, 'customerLedger'])->name('customer_ledger');
        Route::get('/cash-book', [\App\Http\Controllers\ReportController::class, 'cashBook'])->name('cash_book');
        Route::get('/bank-reports', [\App\Http\Controllers\ReportController::class, 'bankReports'])->name('bank_reports');
        Route::get('/partner-statements', [\App\Http\Controllers\ReportController::class, 'partnerStatements'])->name('partner_statements');
        Route::get('/supplier-contractor', [\App\Http\Controllers\ReportController::class, 'supplierContractor'])->name('supplier_contractor');
        Route::get('/sales-return', [\App\Http\Controllers\ReportController::class, 'salesReturn'])->name('sales_return');
        Route::get('/exchange', [\App\Http\Controllers\ReportController::class, 'exchange'])->name('exchange_report');
        Route::get('/petty-cash', [\App\Http\Controllers\ReportController::class, 'pettyCash'])->name('petty_cash');
        Route::get('/loan-schedules', [\App\Http\Controllers\ReportController::class, 'loanSchedules'])->name('loan_schedules');
        Route::get('/trial-balance', [\App\Http\Controllers\ReportController::class, 'trialBalance'])->name('trial_balance');
        Route::get('/profit-loss', [\App\Http\Controllers\ReportController::class, 'profitLoss'])->name('profit_loss');
        Route::get('/balance-sheet', [\App\Http\Controllers\ReportController::class, 'balanceSheet'])->name('balance_sheet');
        Route::get('/audit-trail', [\App\Http\Controllers\ReportController::class, 'auditTrail'])->name('audit_trail');
        Route::get('/approvals', [\App\Http\Controllers\ReportController::class, 'approvals'])->name('approvals');
    });

    // Sales Module
    Route::get('/sales', [\App\Http\Controllers\SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales', [\App\Http\Controllers\SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/available-units/{project}', [SalesController::class, 'availableUnits'])->name('sales.available-units');
    Route::get('/sales/{id}', [\App\Http\Controllers\SalesController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/json', [SalesController::class, 'show'])->name('sales.show.json');
    Route::match(['put', 'post'], '/sales/{id}', [\App\Http\Controllers\SalesController::class, 'update'])->name('sales.update');
    Route::match(['put', 'post'], '/sales/{id}/update', [\App\Http\Controllers\SalesController::class, 'update'])->name('sales.update.post');
    Route::post('/sales/{id}/receipt', [\App\Http\Controllers\SalesController::class, 'addReceipt'])->name('sales.add-receipt');
    Route::post('/sales/{id}/status', [\App\Http\Controllers\SalesController::class, 'changeStatus'])->name('sales.change-status');
    Route::post('/sales/{sale}/receipts', [SalesController::class, 'addReceipt'])->name('sales.receipts.store');

    // Sales Register & Bookings
    Route::resource('bookings', \App\Http\Controllers\BookingController::class)->only(['index', 'create', 'store']);
    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/resale', [\App\Http\Controllers\BookingController::class, 'resale'])->name('bookings.resale');

    // Bank Master
    Route::get('/bank', [\App\Http\Controllers\BankController::class, 'index'])->name('bank.index');
    Route::post('/bank', [\App\Http\Controllers\BankController::class, 'store'])->name('bank.store');
    Route::match(['put', 'post'], '/bank/{bank}', [\App\Http\Controllers\BankController::class, 'update'])->name('bank.update');
    Route::match(['put', 'post'], '/bank/{bank}/update', [\App\Http\Controllers\BankController::class, 'update'])->name('bank.update.post');
    Route::match(['get', 'post', 'delete'], '/bank/{bank}', [\App\Http\Controllers\BankController::class, 'destroy'])->name('bank.destroy');
    Route::match(['get', 'post', 'delete'], '/bank/{bank}/delete', [\App\Http\Controllers\BankController::class, 'destroy'])->name('bank.destroy.post');

    // Dedicated Company Bank Account Master
    Route::get('/company-bank-accounts', [\App\Http\Controllers\CompanyBankAccountController::class, 'index'])->name('company-bank-accounts.index');
    Route::post('/company-bank-accounts', [\App\Http\Controllers\CompanyBankAccountController::class, 'store'])->name('company-bank-accounts.store');
    Route::match(['put', 'post'], '/company-bank-accounts/{companyBankAccount}', [\App\Http\Controllers\CompanyBankAccountController::class, 'update'])->name('company-bank-accounts.update');
    Route::match(['put', 'post'], '/company-bank-accounts/{companyBankAccount}/update', [\App\Http\Controllers\CompanyBankAccountController::class, 'update'])->name('company-bank-accounts.update.post');
    Route::match(['get', 'post', 'delete'], '/company-bank-accounts/{companyBankAccount}', [\App\Http\Controllers\CompanyBankAccountController::class, 'destroy'])->name('company-bank-accounts.destroy');
    Route::match(['get', 'post', 'delete'], '/company-bank-accounts/{companyBankAccount}/delete', [\App\Http\Controllers\CompanyBankAccountController::class, 'destroy'])->name('company-bank-accounts.destroy.post');

    // Chart of Accounts Master Module
    Route::get('/chart-of-accounts', [\App\Http\Controllers\ChartOfAccountController::class, 'index'])->name('chart-of-accounts.index');
    Route::post('/chart-of-accounts', [\App\Http\Controllers\ChartOfAccountController::class, 'store'])->name('chart-of-accounts.store');
    Route::match(['put', 'post'], '/chart-of-accounts/{chartOfAccount}', [\App\Http\Controllers\ChartOfAccountController::class, 'update'])->name('chart-of-accounts.update');
    Route::match(['get', 'post', 'delete'], '/chart-of-accounts/{chartOfAccount}', [\App\Http\Controllers\ChartOfAccountController::class, 'destroy'])->name('chart-of-accounts.destroy');
    Route::post('/chart-of-accounts/{chartOfAccount}/toggle-status', [\App\Http\Controllers\ChartOfAccountController::class, 'toggleStatus'])->name('chart-of-accounts.toggle-status');

    // Voucher Type Master Module
    Route::get('/voucher-types', [\App\Http\Controllers\VoucherTypeController::class, 'index'])->name('voucher-types.index');
    Route::post('/voucher-types', [\App\Http\Controllers\VoucherTypeController::class, 'store'])->name('voucher-types.store');
    Route::match(['put', 'post'], '/voucher-types/{voucherType}', [\App\Http\Controllers\VoucherTypeController::class, 'update'])->name('voucher-types.update');
    Route::match(['get', 'post', 'delete'], '/voucher-types/{voucherType}', [\App\Http\Controllers\VoucherTypeController::class, 'destroy'])->name('voucher-types.destroy');
    Route::post('/voucher-types/{voucherType}/toggle-status', [\App\Http\Controllers\VoucherTypeController::class, 'toggleStatus'])->name('voucher-types.toggle-status');

    // Engineer Master Module
    Route::get('/engineers', [\App\Http\Controllers\EngineerController::class, 'index'])->name('engineers.index');
    Route::post('/engineers', [\App\Http\Controllers\EngineerController::class, 'store'])->name('engineers.store');
    Route::match(['put', 'post'], '/engineers/{engineer}', [\App\Http\Controllers\EngineerController::class, 'update'])->name('engineers.update');
    Route::match(['get', 'post', 'delete'], '/engineers/{engineer}', [\App\Http\Controllers\EngineerController::class, 'destroy'])->name('engineers.destroy');
    Route::post('/engineers/{engineer}/toggle-status', [\App\Http\Controllers\EngineerController::class, 'toggleStatus'])->name('engineers.toggle-status');


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
    Route::match(['get', 'post', 'delete'], '/partners/{partner}', [\App\Http\Controllers\PartnerController::class, 'destroy'])->name('partners.destroy');

    // Brokerage & Commission Management
    Route::get('/brokers', [\App\Http\Controllers\BrokerController::class, 'index'])->name('brokers.index');
    Route::post('/brokers', [\App\Http\Controllers\BrokerController::class, 'store'])->name('brokers.store');
    Route::get('/brokers/payable-report', [\App\Http\Controllers\BrokerController::class, 'payableReport'])->name('brokers.payable-report');
    Route::post('/brokers/payout', [\App\Http\Controllers\BrokerController::class, 'recordPayout'])->name('brokers.payout');
    Route::match(['put', 'post'], '/brokers/{broker}', [\App\Http\Controllers\BrokerController::class, 'update'])->name('brokers.update');
    Route::match(['get', 'post', 'delete'], '/brokers/{broker}', [\App\Http\Controllers\BrokerController::class, 'destroy'])->name('brokers.destroy');


    // Customers
    Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::match(['put', 'post'], 'customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::match(['put', 'post'], 'customers/{customer}/update', [CustomerController::class, 'update'])->name('customers.update.post');
    Route::match(['get', 'post', 'delete'], 'customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::match(['get', 'post', 'delete'], 'customers/{customer}/delete', [CustomerController::class, 'destroy'])->name('customers.destroy.post');

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

    // Contractor Progress Billing (RA Bills) Module
    Route::get('/expenses/ra-bills', [\App\Http\Controllers\RaBillController::class, 'index'])->name('expenses.ra-bills.index');
    Route::post('/expenses/ra-bills', [\App\Http\Controllers\RaBillController::class, 'store'])->name('expenses.ra-bills.store');
    Route::match(['put', 'post'], '/expenses/ra-bills/{id}/verify', [\App\Http\Controllers\RaBillController::class, 'verify'])->name('expenses.ra-bills.verify');
    Route::match(['put', 'post'], '/expenses/ra-bills/{id}/disburse', [\App\Http\Controllers\RaBillController::class, 'disburse'])->name('expenses.ra-bills.disburse');
    Route::delete('/expenses/ra-bills/{id}', [\App\Http\Controllers\RaBillController::class, 'destroy'])->name('expenses.ra-bills.destroy');

    // Suppliers Master Module
    Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [\App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
    Route::match(['put', 'post'], '/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update');
    Route::match(['get', 'post', 'delete'], '/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Employee Master Module
    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::match(['put', 'post'], '/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
    Route::match(['get', 'post', 'delete'], '/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Vouchers & Core Accounting Engine
    Route::get('/vouchers/approvals', [\App\Http\Controllers\VoucherController::class, 'approvalsIndex'])->name('vouchers.approvals');
    Route::post('/vouchers/{id}/approve', [\App\Http\Controllers\VoucherController::class, 'approveVoucher'])->name('vouchers.approve');
    Route::get('/vouchers/source-details', [\App\Http\Controllers\VoucherController::class, 'fetchSourceDetails'])->name('vouchers.source.details');
    Route::get('/vouchers/project/{projectId}/units', [\App\Http\Controllers\VoucherController::class, 'getProjectUnits'])->name('vouchers.project.units');
    Route::get('/vouchers/receipt', [\App\Http\Controllers\VoucherController::class, 'createReceipt'])->name('vouchers.receipt.create');
    Route::post('/vouchers/receipt', [\App\Http\Controllers\VoucherController::class, 'storeReceipt'])->name('vouchers.receipt.store');
    Route::get('/vouchers/receipt/{id}/posted', [\App\Http\Controllers\VoucherController::class, 'receiptPosted'])->name('vouchers.receipt.posted');
    Route::get('/receipts/allocated-to-others', [\App\Http\Controllers\VoucherController::class, 'allocatedReceipts'])->name('receipts.allocated-to-others');
    Route::get('/receipts/allocate-to-others', [\App\Http\Controllers\VoucherController::class, 'allocateToOthersWorkspace'])->name('receipts.allocate-to-others');
    Route::post('/receipts/allocate-to-others', [\App\Http\Controllers\VoucherController::class, 'storeAllocateToOthers'])->name('receipts.allocate-to-others.store');
    Route::get('/receipts/allocate-to-others/{id}/posted', [\App\Http\Controllers\VoucherController::class, 'allocateToOthersPosted'])->name('receipts.allocate-to-others.posted');
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
    // Payment Mode Master Module
    Route::get('/payment-modes', [\App\Http\Controllers\PaymentModeController::class, 'index'])->name('payment-modes.index');
    Route::post('/payment-modes', [\App\Http\Controllers\PaymentModeController::class, 'store'])->name('payment-modes.store');
    Route::post('/payment-modes/{id}/update', [\App\Http\Controllers\PaymentModeController::class, 'update'])->name('payment-modes.update');
    Route::post('/payment-modes/{id}/delete', [\App\Http\Controllers\PaymentModeController::class, 'destroy'])->name('payment-modes.destroy');
    Route::post('/payment-modes/{id}/toggle-status', [\App\Http\Controllers\PaymentModeController::class, 'toggleStatus'])->name('payment-modes.toggle-status');
    // Cheque Status Master Module
    Route::resource('cheque-statuses', \App\Http\Controllers\ChequeStatusController::class)->except(['create', 'show', 'edit']);

    // Receipt Management Module
    Route::get('/receipt-management', [\App\Http\Controllers\ReceiptManagementController::class, 'index'])->name('receipt-management.index');
    Route::post('/receipt-management', [\App\Http\Controllers\ReceiptManagementController::class, 'store'])->name('receipt-management.store');
    Route::post('/receipt-management/bulk-assign-bank', [\App\Http\Controllers\ReceiptManagementController::class, 'assignBankBulk'])->name('receipt-management.assign-bank-bulk');
    Route::post('/receipt-management/{id}/assign-bank', [\App\Http\Controllers\ReceiptManagementController::class, 'assignBank'])->name('receipt-management.assign-bank');
    Route::delete('/receipt-management/{id}', [\App\Http\Controllers\ReceiptManagementController::class, 'destroy'])->name('receipt-management.destroy');

    // Cheque Realization Workflow (Steps 5.2 – 5.4)
    Route::get('/receipt-management/realization-queue', [\App\Http\Controllers\ReceiptManagementController::class, 'realizationQueue'])->name('receipt-management.realization-queue');
    Route::post('/receipt-management/{id}/realize', [\App\Http\Controllers\ReceiptManagementController::class, 'realize'])->name('receipt-management.realize');
    Route::post('/receipt-management/{id}/bounced', [\App\Http\Controllers\ReceiptManagementController::class, 'markBounced'])->name('receipt-management.bounced');
    Route::post('/receipt-management/{id}/reinitialize', [\App\Http\Controllers\ReceiptManagementController::class, 'reinitialize'])->name('receipt-management.reinitialize');
    Route::post('/receipt-management/{id}/advance-status', [\App\Http\Controllers\ReceiptManagementController::class, 'advanceStatus'])->name('receipt-management.advance-status');

    // Treasury Disbursement & Payment Voucher (Steps 5.5 – 5.6)
    Route::get('/vouchers/treasury-payment', [\App\Http\Controllers\VoucherController::class, 'treasuryPaymentIndex'])->name('vouchers.treasury-payment.index');
    Route::post('/vouchers/treasury-payment', [\App\Http\Controllers\VoucherController::class, 'treasuryPayment'])->name('vouchers.treasury-payment');
    Route::get('/vouchers/{id}/payment-voucher-print', [\App\Http\Controllers\VoucherController::class, 'printPaymentVoucher'])->name('vouchers.payment-voucher-print');
});

require __DIR__ . '/auth.php';