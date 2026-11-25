<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('payroll_salaries', 'company_bank_account_id')) {
                $table->unsignedBigInteger('company_bank_account_id')->nullable()->after('payment_mode');
                $table->index('company_bank_account_id', 'ps_bank_acc_idx');
            }
            if (!Schema::hasColumn('payroll_salaries', 'payment_reference')) {
                $table->string('payment_reference', 100)->nullable()->after('company_bank_account_id');
            }
            if (!Schema::hasColumn('payroll_salaries', 'journal_voucher_id')) {
                $table->unsignedBigInteger('journal_voucher_id')->nullable()->after('payment_reference');
                $table->index('journal_voucher_id', 'ps_jv_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payroll_salaries', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_salaries', 'journal_voucher_id')) {
                $table->dropIndex('ps_jv_idx');
                $table->dropColumn('journal_voucher_id');
            }
            if (Schema::hasColumn('payroll_salaries', 'payment_reference')) {
                $table->dropColumn('payment_reference');
            }
            if (Schema::hasColumn('payroll_salaries', 'company_bank_account_id')) {
                $table->dropIndex('ps_bank_acc_idx');
                $table->dropColumn('company_bank_account_id');
            }
        });
    }
};
