<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hindustan_units', function (Blueprint $table) {
            if (!Schema::hasColumn('hindustan_units', 'gst_behavior')) {
                $table->string('gst_behavior', 20)->default('none')->after('difference');
            }
            if (!Schema::hasColumn('hindustan_units', 'gst_amount')) {
                $table->decimal('gst_amount', 15, 2)->default(0.00)->after('gst_behavior');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hindustan_units', function (Blueprint $table) {
            if (Schema::hasColumn('hindustan_units', 'gst_behavior')) {
                $table->dropColumn('gst_behavior');
            }
            if (Schema::hasColumn('hindustan_units', 'gst_amount')) {
                $table->dropColumn('gst_amount');
            }
        });
    }
};
