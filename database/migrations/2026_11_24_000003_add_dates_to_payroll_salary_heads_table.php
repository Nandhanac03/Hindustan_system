<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payroll_salary_heads')) {
            Schema::table('payroll_salary_heads', function (Blueprint $table) {
                if (!Schema::hasColumn('payroll_salary_heads', 'from_date')) {
                    $table->date('from_date')->nullable()->after('financial_period_id');
                }
                if (!Schema::hasColumn('payroll_salary_heads', 'to_date')) {
                    $table->date('to_date')->nullable()->after('from_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payroll_salary_heads')) {
            Schema::table('payroll_salary_heads', function (Blueprint $table) {
                if (Schema::hasColumn('payroll_salary_heads', 'to_date')) {
                    $table->dropColumn('to_date');
                }
                if (Schema::hasColumn('payroll_salary_heads', 'from_date')) {
                    $table->dropColumn('from_date');
                }
            });
        }
    }
};
