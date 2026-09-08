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
        if (!Schema::hasTable('journal_entries')) {
            Schema::create('journal_entries', function (Blueprint $table) {
                $table->id();

                // Journal Voucher reference
                $table->unsignedBigInteger('voucher_id');

                // Chart of Accounts account_code
                $table->string('account_id', 20);

                // Debit / Credit amounts
                $table->decimal('debit_amount', 15, 2)->default(0.00);
                $table->decimal('credit_amount', 15, 2)->default(0.00);

                // Line Narration / Description
                $table->string('line_narration', 255)->nullable();

                $table->timestamps();

                // Foreign key -> journal_vouchers.id
                $table->foreign('voucher_id')
                    ->references('id')
                    ->on('journal_vouchers')
                    ->onDelete('cascade');

                // Foreign key -> chart_of_accounts.account_code
                $table->foreign('account_id')
                    ->references('account_code')
                    ->on('chart_of_accounts')
                    ->onDelete('restrict');

                // Useful index for account-wise reports
                $table->index('account_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
