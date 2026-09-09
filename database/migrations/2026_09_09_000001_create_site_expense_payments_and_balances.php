<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add balance & payment status tracking to site_expenses table
        Schema::table('site_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('site_expenses', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0.00)->after('net_amount');
            }
            if (!Schema::hasColumn('site_expenses', 'balance_amount')) {
                $table->decimal('balance_amount', 15, 2)->default(0.00)->after('paid_amount');
            }
            if (!Schema::hasColumn('site_expenses', 'payment_status')) {
                $table->string('payment_status', 30)->default('unpaid')->after('status');
            }
        });

        // 2. Initialize existing site expenses as paid if they were already approved & linked to bank
        DB::table('site_expenses')->where('status', 'Approved')->update([
            'paid_amount'    => DB::raw('net_amount'),
            'balance_amount' => 0.00,
            'payment_status' => 'paid',
        ]);

        DB::table('site_expenses')->where('status', '!=', 'Approved')->update([
            'paid_amount'    => 0.00,
            'balance_amount' => DB::raw('net_amount'),
            'payment_status' => 'unpaid',
        ]);

        // 3. Create site_expense_payments table
        if (!Schema::hasTable('site_expense_payments')) {
            Schema::create('site_expense_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->default(1);
                $table->foreignId('site_expense_id')->constrained('site_expenses')->onDelete('cascade');
                $table->date('payment_date');
                $table->decimal('paid_amount', 15, 2);
                $table->string('payment_mode')->default('Bank Transfer');
                $table->enum('payment_source_type', ['bank', 'loan'])->default('bank');
                $table->unsignedBigInteger('company_bank_account_id')->nullable();
                $table->unsignedBigInteger('loan_id')->nullable();
                $table->string('reference_no')->nullable();
                $table->unsignedBigInteger('voucher_id')->nullable();
                $table->string('status')->default('paid');
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index('site_expense_id', 'sep_expense_idx');
                $table->index('company_bank_account_id', 'sep_bank_idx');
                $table->index('loan_id', 'sep_loan_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_expense_payments');

        Schema::table('site_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('site_expenses', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('site_expenses', 'balance_amount')) {
                $table->dropColumn('balance_amount');
            }
            if (Schema::hasColumn('site_expenses', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
};
