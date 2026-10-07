<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_expense_payments') && !Schema::hasColumn('site_expense_payments', 'is_historical')) {
            Schema::table('site_expense_payments', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_expense_payments', 'is_historical')) {
            Schema::table('site_expense_payments', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }
    }
};
