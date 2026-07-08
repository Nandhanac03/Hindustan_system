<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 5. unit_rate_logs table
        Schema::create('unit_rate_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('hindustan_units')->cascadeOnDelete();
            $table->decimal('rate', 15, 2);
            $table->date('effective_from');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // 6. unit_status_logs table
        Schema::create('unit_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('hindustan_units')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_status_logs');
        Schema::dropIfExists('unit_rate_logs');
    }
};
