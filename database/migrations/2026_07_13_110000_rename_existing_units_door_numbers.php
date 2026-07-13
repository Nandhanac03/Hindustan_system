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
        // Update all existing units to match the new naming convention
        $units = Unit::with('floor')->get();

        foreach ($units as $unit) {
            if (!$unit->floor) {
                continue;
            }

            $prefix = Floor::getDoorPrefix($unit->floor->floor_number);
            $delim = $unit->floor->floor_number < 0 ? '-' : '';
            $targetPrefix = $prefix . $delim;

            // If it already starts with the target prefix, skip it
            if (str_starts_with($unit->door_no, $targetPrefix)) {
                continue;
            }

            $doorNo = $unit->door_no;

            // Strip legacy "U-{floor_number}{unit_number}" format
            if (str_starts_with($doorNo, 'U-')) {
                $temp = substr($doorNo, 2);
                $floorNumStr = (string)$unit->floor->floor_number;
                if (str_starts_with($temp, $floorNumStr)) {
                    $temp = substr($temp, strlen($floorNumStr));
                }
                if (is_numeric($temp)) {
                    $doorNo = (string)(int)$temp;
                } else {
                    $doorNo = $temp;
                }
            }

            // Re-apply target prefix
            $unit->door_no = $targetPrefix . $doorNo;
            $unit->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional down action: cannot easily reverse custom string stripping, but we can leave it as is
    }
};
