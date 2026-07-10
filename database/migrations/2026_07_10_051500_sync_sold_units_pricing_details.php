<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fetch all active sales
        $sales = DB::table('sales')->where('status', 'active')->get();

        foreach ($sales as $sale) {
            $unit = DB::table('hindustan_units')->where('id', $sale->unit_id)->first();
            if ($unit && $unit->status === 'sold') {
                $difference = (float)$unit->expected_sale_amount - (float)$sale->sale_amount;
                DB::table('hindustan_units')
                    ->where('id', $sale->unit_id)
                    ->update([
                        'sale_rate_per_sqft' => $sale->rate_per_sqft,
                        'sale_amount'        => $sale->sale_amount,
                        'difference'         => $difference,
                        'gst_behavior'       => $sale->gst_type ?? 'none',
                        'gst_amount'         => $sale->gst_amount ?? 0.00,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing doesn't clear updated values as it represents historical correct states.
    }
};
