<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (! Schema::hasColumn('sales', 'gst_behavior')) {
                $table->enum('gst_behavior', ['none', 'included', 'excluded'])
                      ->default('none')
                      ->after('gst_applicable');
            }
            if (! Schema::hasColumn('sales', 'base_amount')) {
                $table->decimal('base_amount', 15, 2)->nullable()->after('gst_behavior');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['gst_behavior', 'base_amount']);
        });
    }
};