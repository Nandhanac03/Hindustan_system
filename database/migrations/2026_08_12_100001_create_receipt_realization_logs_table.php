<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the audit log table for every status change on a receipt instrument.
     */
    public function up(): void
    {
        Schema::dropIfExists('receipt_realization_logs');
        Schema::create('receipt_realization_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->foreign('receipt_id')->references('id')->on('receipts')->cascadeOnDelete();
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->string('remarks', 500)->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->foreign('changed_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['receipt_id', 'created_at'], 'rec_real_logs_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_realization_logs');
    }
};
