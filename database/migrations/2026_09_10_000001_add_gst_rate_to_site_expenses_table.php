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
        if (!Schema::hasColumn('site_expenses', 'gst_rate')) {
            Schema::table('site_expenses', function (Blueprint $table) {
                $table->decimal('gst_rate', 5, 2)->default(0.00)->after('gross_amount');
            });
        }

        // Backfill existing site expenses with true calculated GST rate and GST components
        $expenses = DB::table('site_expenses')->get();
        foreach ($expenses as $exp) {
            $gross = (float) $exp->gross_amount;
            $net = (float) $exp->net_amount;
            if ($gross > 0 && $net >= $gross) {
                $taxDiff = round($net - $gross, 2);
                $rate = round(($taxDiff / $gross) * 100, 2);
                $halfTax = round($taxDiff / 2, 2);

                DB::table('site_expenses')
                    ->where('id', $exp->id)
                    ->update([
                        'gst_rate' => $rate,
                        'total_gst_amount' => $taxDiff,
                        'cgst_amount' => $halfTax,
                        'sgst_amount' => $halfTax,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('site_expenses', 'gst_rate')) {
            Schema::table('site_expenses', function (Blueprint $table) {
                $table->dropColumn('gst_rate');
            });
        }
    }
};
