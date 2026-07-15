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
        Schema::dropIfExists('sale_extra_works');
        Schema::create('sale_extra_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->string('gst_type')->default('none'); // 'exclusive', 'inclusive', 'none'
            $table->decimal('gst_percentage', 5, 2)->default(18.00);
            $table->decimal('gst_amount', 15, 2)->default(0.00);
            $table->decimal('line_total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_extra_works');
    }
};
