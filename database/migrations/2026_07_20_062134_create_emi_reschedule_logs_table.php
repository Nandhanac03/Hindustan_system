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
        Schema::create('emi_reschedule_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('action_type'); // prepayment_reduce_tenure, reschedule_extend_tenure, etc.
            $table->text('reason')->nullable();
            $table->json('old_schedule_snapshot');
            $table->json('new_schedule_snapshot');
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emi_reschedule_logs');
    }
};
