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
        Schema::create('petty_cash_boxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('box_code')->unique();
            $table->string('box_name')->default('Site Petty Cash Box');
            $table->unsignedBigInteger('incharge_id')->nullable();
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
            
            // Note: Since projects and users tables might not be fully standardized yet in our tests,
            // we won't strictly enforce foreign key constraints to prevent deployment errors, 
            // but we'll set up the logical columns.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_boxes');
    }
};
