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
        Schema::create('ra_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_id')->default(1);
            $table->string('ra_bill_number')->unique();
            $table->unsignedBigInteger('contractor_id')->nullable();
            $table->string('contractor_name')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->date('submit_date');
            $table->decimal('gross_amount', 15, 2);
            $table->date('verified_date')->nullable();
            $table->string('engineer_name')->nullable();
            $table->decimal('correction_amount', 15, 2)->default(0.00);
            $table->decimal('net_approved_amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->decimal('balance_amount', 15, 2);
            $table->string('status')->default('pending'); // pending, verified, partially_paid, cleared, cancelled
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('system_id');
            $table->index('contractor_id');
            $table->index('project_id');
            $table->index('status');
        });

        Schema::create('ra_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_id')->default(1);
            $table->foreignId('ra_bill_id')->constrained('ra_bills')->onDelete('cascade');
            $table->date('payment_date');
            $table->decimal('paid_amount', 15, 2);
            $table->string('payment_mode')->default('NEFT');
            $table->unsignedBigInteger('company_bank_account_id')->nullable();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->string('status')->default('paid');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('system_id');
            $table->index('ra_bill_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ra_bill_payments');
        Schema::dropIfExists('ra_bills');
    }
};
