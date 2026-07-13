<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Unit;
use App\Models\Floor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $units = Unit::with('floor')->get();

        foreach ($units as $unit) {
            if (!$unit->floor) {
                continue;
            }

            $prefix = Floor::getDoorPrefix($unit->floor->floor_number);
            $doorNo = $unit->door_no;

            // Strip any existing prefix formatting (space, hyphen, or direct prefix)
            if (str_starts_with($doorNo, $prefix . ' ')) {
                $doorNo = substr($doorNo, strlen($prefix . ' '));
            } elseif (str_starts_with($doorNo, $prefix . '-')) {
                $doorNo = substr($doorNo, strlen($prefix . '-'));
            } elseif (str_starts_with($doorNo, $prefix)) {
                $doorNo = substr($doorNo, strlen($prefix));
            }

            // Re-apply prefix with a space separator
            $unit->door_no = $prefix . ' ' . $doorNo;
            $unit->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional down action
    }
};
