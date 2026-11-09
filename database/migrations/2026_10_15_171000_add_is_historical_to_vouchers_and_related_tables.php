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
        // 1. vouchers table
        if (Schema::hasTable('vouchers') && !Schema::hasColumn('vouchers', 'is_historical')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }

        // 2. journal_vouchers table
        if (Schema::hasTable('journal_vouchers') && !Schema::hasColumn('journal_vouchers', 'is_historical')) {
            Schema::table('journal_vouchers', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }

        // 3. receipts table
        if (Schema::hasTable('receipts') && !Schema::hasColumn('receipts', 'is_historical')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('remarks');
            });
        }

        // 4. payments table
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'is_historical')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }

        // 5. petty_cash_transactions table
        if (Schema::hasTable('petty_cash_transactions') && !Schema::hasColumn('petty_cash_transactions', 'is_historical')) {
            Schema::table('petty_cash_transactions', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vouchers') && Schema::hasColumn('vouchers', 'is_historical')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }

        if (Schema::hasTable('journal_vouchers') && Schema::hasColumn('journal_vouchers', 'is_historical')) {
            Schema::table('journal_vouchers', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }

        if (Schema::hasTable('receipts') && Schema::hasColumn('receipts', 'is_historical')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }

        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'is_historical')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }

        if (Schema::hasTable('petty_cash_transactions') && Schema::hasColumn('petty_cash_transactions', 'is_historical')) {
            Schema::table('petty_cash_transactions', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }
    }
};
