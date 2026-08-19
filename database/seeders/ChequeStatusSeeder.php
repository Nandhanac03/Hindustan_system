<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ChequeStatus;
use Illuminate\Database\Seeder;

class ChequeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds for Cheque Statuses.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'PENDING',        'color_code' => 'amber-500',   'is_active' => true],
            ['name' => 'REALIZED',       'color_code' => 'emerald-500', 'is_active' => true],
            ['name' => 'BOUNCED',        'color_code' => 'rose-500',    'is_active' => true],
            ['name' => 'CANCELLED',      'color_code' => 'slate-500',   'is_active' => true],
            ['name' => 'DEPOSITED',      'color_code' => 'blue',        'is_active' => true],
            ['name' => 'CHEQUE IN HAND', 'color_code' => 'orange',      'is_active' => true],
            ['name' => 'IN CLEARING',    'color_code' => 'purple',      'is_active' => true],
        ];

        foreach ($statuses as $st) {
            ChequeStatus::updateOrCreate(
                ['name' => $st['name']],
                [
                    'color_code' => $st['color_code'],
                    'is_active'  => $st['is_active'],
                ]
            );
        }
    }
}
