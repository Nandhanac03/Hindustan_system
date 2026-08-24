<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\RaBill;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ra_bills', function (Blueprint $table) {
            if (!Schema::hasColumn('ra_bills', 'additional_percentage')) {
                $table->decimal('additional_percentage', 5, 2)->default(0)->after('additional_amount');
            }
        });

        // Populate additional_percentage for all existing records
        try {
            $bills = RaBill::all();
            foreach ($bills as $bill) {
                $gross = (float) $bill->gross_amount;
                $additional = (float) $bill->additional_amount;
                if ($gross > 0 && $additional > 0) {
                    $bill->additional_percentage = round(($additional / $gross) * 100, 2);
                    $bill->save();
                }
            }
        } catch (\Throwable $e) {
            // Ignore if table or model not ready
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ra_bills', function (Blueprint $table) {
            if (Schema::hasColumn('ra_bills', 'additional_percentage')) {
                $table->dropColumn('additional_percentage');
            }
        });
    }
};
