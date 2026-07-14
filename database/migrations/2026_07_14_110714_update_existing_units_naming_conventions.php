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
        $mappings = [
            4 => ['old' => 'Fo', 'new' => 'FO'],
            5 => ['old' => 'Fi', 'new' => 'FI'],
            6 => ['old' => 'Si', 'new' => 'SI'],
            7 => ['old' => 'Se', 'new' => 'SE'],
        ];

        foreach ($mappings as $floorNum => $map) {
            $units = \App\Models\Unit::whereHas('floor', function($q) use ($floorNum) {
                $q->where('floor_number', $floorNum);
            })->get();

            foreach ($units as $unit) {
                $oldDoor = $unit->door_no;
                $pattern = '/^' . preg_quote($map['old'], '/') . '\b/i';
                if (preg_match($pattern, $oldDoor)) {
                    $unit->door_no = preg_replace($pattern, $map['new'], $oldDoor);
                    $unit->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mappings = [
            4 => ['old' => 'FO', 'new' => 'Fo'],
            5 => ['old' => 'FI', 'new' => 'Fi'],
            6 => ['old' => 'SI', 'new' => 'Si'],
            7 => ['old' => 'SE', 'new' => 'Se'],
        ];

        foreach ($mappings as $floorNum => $map) {
            $units = \App\Models\Unit::whereHas('floor', function($q) use ($floorNum) {
                $q->where('floor_number', $floorNum);
            })->get();

            foreach ($units as $unit) {
                $oldDoor = $unit->door_no;
                $pattern = '/^' . preg_quote($map['old'], '/') . '\b/i';
                if (preg_match($pattern, $oldDoor)) {
                    $unit->door_no = preg_replace($pattern, $map['new'], $oldDoor);
                    $unit->save();
                }
            }
        }
    }
};
