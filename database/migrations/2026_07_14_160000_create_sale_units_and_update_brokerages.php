<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sale_units')) {
            Schema::create('sale_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
                $table->foreignId('unit_id')->constrained('hindustan_units')->cascadeOnDelete();
                $table->string('wing')->nullable();
                $table->decimal('rate_per_sqft', 15, 2);
                $table->decimal('area_sqft', 15, 2);
                $table->decimal('base_amount', 15, 2);
                $table->enum('gst_type', ['none', 'inclusive', 'exclusive'])->default('none');
                $table->decimal('gst_percentage', 5, 2)->default(0);
                $table->decimal('gst_amount', 15, 2)->default(0);
                $table->decimal('line_total', 15, 2);
                $table->enum('brokerage_type', ['percentage', 'fixed'])->nullable();
                $table->decimal('brokerage_value', 15, 2)->nullable();
                $table->decimal('brokerage_amount', 15, 2)->default(0);
                $table->timestamps();
            });
        }

        Schema::table('brokerages', function (Blueprint $table) {
            if (! Schema::hasColumn('brokerages', 'sale_unit_id')) {
                $table->foreignId('sale_unit_id')->nullable()->after('sale_id')->constrained('sale_units')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('brokerages', function (Blueprint $table) {
            if (Schema::hasColumn('brokerages', 'sale_unit_id')) {
                $table->dropForeign(['sale_unit_id']);
                $table->dropColumn('sale_unit_id');
            }
        });

        Schema::dropIfExists('sale_units');
    }
};
