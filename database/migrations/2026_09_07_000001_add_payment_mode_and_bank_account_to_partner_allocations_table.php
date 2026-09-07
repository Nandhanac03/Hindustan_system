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
        Schema::table('partner_allocations', function (Blueprint $table) {
            if (!Schema::hasColumn('partner_allocations', 'payment_mode')) {
                $table->string('payment_mode', 100)->nullable()->after('allocated_amount');
            }
            if (!Schema::hasColumn('partner_allocations', 'remarks')) {
                $table->text('remarks')->nullable()->after('payment_mode');
            }
            if (!Schema::hasColumn('partner_allocations', 'company_bank_account_id')) {
                $table->unsignedBigInteger('company_bank_account_id')->nullable()->after('remarks');
                $table->foreign('company_bank_account_id', 'pa_cbank_fk')->references('id')->on('company_bank_accounts')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_allocations', function (Blueprint $table) {
            if (Schema::hasColumn('partner_allocations', 'company_bank_account_id')) {
                $table->dropForeign('pa_cbank_fk');
                $table->dropColumn('company_bank_account_id');
            }
            if (Schema::hasColumn('partner_allocations', 'remarks')) {
                $table->dropColumn('remarks');
            }
            if (Schema::hasColumn('partner_allocations', 'payment_mode')) {
                $table->dropColumn('payment_mode');
            }
        });
    }
};
