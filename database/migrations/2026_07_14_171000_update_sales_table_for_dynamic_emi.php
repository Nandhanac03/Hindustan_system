<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'emi_plan_type')) {
                $table->dropColumn('emi_plan_type');
            }
            if (!Schema::hasColumn('sales', 'emi_type')) {
                $table->enum('emi_type', ['equal', 'milestone'])->nullable()->after('payment_plan');
            }
            if (!Schema::hasColumn('sales', 'emi_installment_count')) {
                $table->integer('emi_installment_count')->nullable()->after('emi_type');
            }
            if (!Schema::hasColumn('sales', 'emi_frequency')) {
                $table->string('emi_frequency')->nullable()->after('emi_installment_count');
            }
            if (!Schema::hasColumn('sales', 'first_installment_date')) {
                $table->date('first_installment_date')->nullable()->after('emi_frequency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'emi_type')) {
                $table->dropColumn('emi_type');
            }
            if (Schema::hasColumn('sales', 'emi_installment_count')) {
                $table->dropColumn('emi_installment_count');
            }
            if (Schema::hasColumn('sales', 'emi_frequency')) {
                $table->dropColumn('emi_frequency');
            }
            if (Schema::hasColumn('sales', 'first_installment_date')) {
                $table->dropColumn('first_installment_date');
            }
            if (!Schema::hasColumn('sales', 'emi_plan_type')) {
                $table->string('emi_plan_type')->nullable()->after('payment_plan');
            }
        });
    }
};
