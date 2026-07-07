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
        Schema::create('hindustan_units', function (Blueprint $table) {
            $table->id();

            // Relationships (from image: FLOOR, TYPE)
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('floor_id')->constrained('floors')->cascadeOnDelete();
            $table->foreignId('unit_type_id')->constrained('unit_types')->cascadeOnDelete();

            // DOOR NO
            $table->string('door_no');

            // BUILT UP AREA (In Sq Ft)
            $table->decimal('built_up_area', 10, 2)->nullable();

            // CARPET AREA (In Sq Ft)
            $table->decimal('carpet_area', 10, 2)->nullable();

            // ₹ EXPECTED / SQ.FT
            $table->decimal('expected_rate_per_sqft', 12, 2)->nullable();

            // ₹ EXPECTED SALE
            $table->decimal('expected_sale_amount', 14, 2)->nullable();

            // ₹ SALE PER SQ.FT
            $table->decimal('sale_rate_per_sqft', 12, 2)->nullable();

            // ₹ SALE AMOUNT
            $table->decimal('sale_amount', 14, 2)->nullable();

            // DIFFERECE
            $table->decimal('difference', 14, 2)->nullable();

            // STATUS
            $table->enum('status', ['available', 'booked', 'sold', 'blocked', 'hold'])->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hindustan_units');
    }
};
