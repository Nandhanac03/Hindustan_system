<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('chart_of_accounts', 'opening_balance')) {
                $table->decimal('opening_balance', 15, 2)->default(0.00)->after('account_type');
            }
            if (!Schema::hasColumn('chart_of_accounts', 'opening_balance_type')) {
                $table->string('opening_balance_type', 10)->default('DR')->after('opening_balance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('chart_of_accounts', 'opening_balance_type')) {
                $table->dropColumn('opening_balance_type');
            }
            if (Schema::hasColumn('chart_of_accounts', 'opening_balance')) {
                $table->dropColumn('opening_balance');
            }
        });
    }
};
