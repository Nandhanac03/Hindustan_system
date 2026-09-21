<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('site_expense_categories')) {
            Schema::create('site_expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('category_code', 50)->nullable();
                $table->string('category_name', 255);
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_expense_categories');
    }
};
