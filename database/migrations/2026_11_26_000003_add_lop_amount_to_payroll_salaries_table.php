<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('payroll_salaries', 'lop_amount')) {
                $table->decimal('lop_amount', 12, 2)->default(0)->after('deductions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payroll_salaries', function (Blueprint $table) {
            if (Schema::hasColumn('payroll_salaries', 'lop_amount')) {
                $table->dropColumn('lop_amount');
            }
        });
    }
};
