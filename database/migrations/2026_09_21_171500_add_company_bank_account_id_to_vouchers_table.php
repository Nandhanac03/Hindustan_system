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
        if (!Schema::hasColumn('vouchers', 'company_bank_account_id')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->foreignId('company_bank_account_id')
                    ->nullable()
                    ->after('system_id')
                    ->constrained('company_bank_accounts')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vouchers', 'company_bank_account_id')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->dropForeign(['company_bank_account_id']);
                $table->dropColumn('company_bank_account_id');
            });
        }
    }
};
