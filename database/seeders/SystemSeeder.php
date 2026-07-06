<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed systems
        $india = System::firstOrCreate(
            ['code' => 'IN'],
            [
                'name' => 'India System',
                'country' => 'India',
                'currency_code' => 'INR',
                'gst_enabled' => true,
                'vat_enabled' => false,
                'timezone' => 'Asia/Kolkata',
                'is_active' => true,
            ]
        );

        $uae = System::firstOrCreate(
            ['code' => 'AE'],
            [
                'name' => 'UAE System',
                'country' => 'UAE',
                'currency_code' => 'AED',
                'gst_enabled' => false,
                'vat_enabled' => true,
                'timezone' => 'Asia/Dubai',
                'is_active' => true,
            ]
        );

        // 2. Create the Owner user in India system
        $ownerUser = User::updateOrCreate(
            ['email' => 'owner@hindustan.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('password'),
                'system_id' => $india->id,
                'phone' => '+91 99999 99999',
                'employee_code' => 'EMP-001',
                'status' => 'active',
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );

        // Assign the Owner role
        $ownerUser->assignRole('Owner');
    }
}
