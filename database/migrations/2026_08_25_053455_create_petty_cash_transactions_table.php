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
        Schema::create('petty_cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petty_cash_box_id');
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->date('transaction_date');
            $table->string('voucher_number')->nullable();
            $table->string('transaction_type')->nullable(); // Contra, Site Expense, etc.
            $table->string('reference_no')->nullable();
            $table->text('narration')->nullable();
            $table->decimal('cash_in', 15, 2)->default(0);
            $table->decimal('cash_out', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0); // Running balance after this transaction
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status')->default('Posted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_transactions');
    }
};
