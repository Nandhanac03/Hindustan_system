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
        Schema::create('receipt_stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id')->nullable();
            $table->unsignedBigInteger('company_bank_account_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->date('receipt_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_mode')->nullable();
            $table->string('reference_no')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_stores');
    }
};
