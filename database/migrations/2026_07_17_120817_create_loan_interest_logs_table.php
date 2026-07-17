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
        Schema::create('loan_interest_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->index();
            $table->decimal('old_interest_rate', 5, 2);
            $table->decimal('new_interest_rate', 5, 2);
            $table->string('interest_period')->default('annual');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_interest_logs');
    }
};
