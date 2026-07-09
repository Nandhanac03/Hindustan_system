<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('customer_installments');

        Schema::create('customer_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->integer('installment_no');
            $table->string('label', 100);
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->string('status', 20)->default('pending'); // pending, paid, overdue, partial
            $table->string('schedule_type', 20)->default('fixed_emi'); // fixed_emi, milestone
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_installments');
    }
};
