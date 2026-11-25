<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employee_leave_quotas')) {
            Schema::create('employee_leave_quotas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->nullable();
                $table->index('system_id', 'elq_sys_idx');
                $table->unsignedBigInteger('financial_period_id')->nullable();
                $table->index('financial_period_id', 'elq_fp_idx');
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->decimal('casual_allowed', 6, 1)->default(15);
                $table->decimal('medical_allowed', 6, 1)->default(10);
                $table->decimal('casual_taken', 6, 1)->default(0);
                $table->decimal('medical_taken', 6, 1)->default(0);
                $table->timestamps();
                $table->unique(['financial_period_id', 'employee_id'], 'elq_fp_emp_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_leave_quotas');
    }
};
