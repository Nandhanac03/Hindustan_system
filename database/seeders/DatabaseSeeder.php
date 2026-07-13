<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System;
use App\Models\User;
use App\Models\Project;
use App\Models\Floor;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitRateLog;
use App\Models\UnitStatusLog;
use App\Models\Customer;
use App\Models\SalesExecutive;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ApprovalRequest;
use App\Models\ApprovalRule;
use App\Models\ActivityLog;
use App\Models\Account;
use App\Models\Payee;
use App\Models\PartnerShare;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Spatie Roles and Permissions
        $this->call(RolePermissionSeeder::class);

        // 2. Systems and India Owner
        $this->call(SystemSeeder::class);

        // 2.5. Create Unit Types
        $unitTypes = [
            ['name' => 'Flat', 'category' => 'residential'],
            ['name' => 'Shop', 'category' => 'commercial'],
            ['name' => 'Office', 'category' => 'commercial'],
            ['name' => 'Villa', 'category' => 'residential'],
            ['name' => 'Parking', 'category' => 'parking'],
        ];
        foreach ($unitTypes as $ut) {
            UnitType::firstOrCreate(['name' => $ut['name']], ['category' => $ut['category'], 'is_active' => true]);
        }

        // Get system IDs
        $india = System::where('code', 'IN')->first();
        $uae = System::where('code', 'AE')->first();

        // 3. Create other users with specific roles
        // Accountant (India)
        $accountantIn = User::updateOrCreate(
            ['email' => 'accountant.in@hindustan.com'],
            [
                'name' => 'Rajesh Accountant (IN)',
                'password' => Hash::make('password'),
                'system_id' => $india->id,
                'phone' => '+91 98765 00001',
                'employee_code' => 'EMP-IN-ACC01',
                'status' => 'active',
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );
        $accountantIn->assignRole('Accountant');

        // Accountant (UAE)
        $accountantUae = User::updateOrCreate(
            ['email' => 'accountant.ae@hindustan.com'],
            [
                'name' => 'Omar Accountant (UAE)',
                'password' => Hash::make('password'),
                'system_id' => $uae->id,
                'phone' => '+971 50 123 4567',
                'employee_code' => 'EMP-AE-ACC01',
                'status' => 'active',
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );
        $accountantUae->assignRole('Accountant');

        // Sales Representative (India)
        $salesIn = User::updateOrCreate(
            ['email' => 'sales.in@hindustan.com'],
            [
                'name' => 'Vikram Sales (IN)',
                'password' => Hash::make('password'),
                'system_id' => $india->id,
                'phone' => '+91 98765 00002',
                'employee_code' => 'EMP-IN-SAL01',
                'status' => 'active',
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );
        $salesIn->assignRole('Sales');

        // Site Manager (India)
        $siteIn = User::updateOrCreate(
            ['email' => 'site.in@hindustan.com'],
            [
                'name' => 'Amit Site (IN)',
                'password' => Hash::make('password'),
                'system_id' => $india->id,
                'phone' => '+91 98765 00003',
                'employee_code' => 'EMP-IN-SIT01',
                'status' => 'active',
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );
        $siteIn->assignRole('Site');

        // 4. Create Projects
        $projectsData = [
            [
                'system_id' => $india->id,
                'name' => 'Hindustan Emerald Valley',
                'code' => 'HEV-01',
                'location' => 'Sector 62',
                'city' => 'Noida',
                'state_or_emirate' => 'Uttar Pradesh',
                'country' => 'India',
                'total_floors' => 5,
                'status' => 'ongoing',
            ],
            [
                'system_id' => $india->id,
                'name' => 'Hindustan Sapphire Heights',
                'code' => 'HSH-01',
                'location' => 'Sector 150',
                'city' => 'Noida',
                'state_or_emirate' => 'Uttar Pradesh',
                'country' => 'India',
                'total_floors' => 5,
                'status' => 'completed',
            ],
            [
                'system_id' => $uae->id,
                'name' => 'Hindustan Grand Plaza',
                'code' => 'HGP-01',
                'location' => 'Dubai Marina',
                'city' => 'Dubai',
                'state_or_emirate' => 'Dubai',
                'country' => 'UAE',
                'total_floors' => 5,
                'status' => 'ongoing',
            ],
        ];

        $projects = [];
        foreach ($projectsData as $data) {
            $projects[] = Project::create($data);
        }

        // 4.5. Create Floors for each project
        foreach ($projects as $proj) {
            for ($f = 1; $f <= $proj->total_floors; $f++) {
                Floor::create([
                    'project_id' => $proj->id,
                    'floor_number' => $f,
                    'name' => "Floor " . $f,
                ]);
            }
        }

        // 5. Create Customers
        $customersData = [
            ['name' => 'Vijay Malhotra', 'email' => 'vijay@gmail.com', 'phone' => '+91 98765 43212', 'avatar_url' => 'VM'],
            ['name' => 'Neha Kapoor', 'email' => 'neha@gmail.com', 'phone' => '+91 98765 43213', 'avatar_url' => 'NK'],
            ['name' => 'Rajesh Gupta', 'email' => 'rajesh@gmail.com', 'phone' => '+91 98765 43214', 'avatar_url' => 'RG'],
        ];

        $customers = [];
        foreach ($customersData as $data) {
            $customers[] = Customer::create($data);
        }

        // 6. Create Units
        $ownerIn = User::where('email', 'owner@hindustan.com')->first();
        $flatType = UnitType::where('name', 'Flat')->first();
        foreach ($projects as $proj) {
            $floorsList = $proj->floors;
            foreach ($floorsList as $floor) {
                for ($i = 1; $i <= 4; $i++) {
                    $status = ($i === 1) ? 'sold' : (($i === 2) ? 'booked' : 'available');
                    $expectedRate = 4500.00;
                    $bua = 1200.00;
                    $expectedSale = $bua * $expectedRate;

                    $saleRate = null;
                    $saleAmount = null;
                    $difference = null;

                    if ($status === 'booked' || $status === 'sold') {
                        $saleRate = 4500.00;
                        $saleAmount = $bua * $saleRate;
                        $difference = $expectedSale - $saleAmount;
                    }

                    $unit = Unit::create([
                        'project_id' => $proj->id,
                        'floor_id' => $floor->id,
                        'unit_type_id' => $flatType->id,
                        'door_no' => Floor::getDoorPrefix($floor->floor_number) . ' ' . $i,
                        'built_up_area' => $bua,
                        'carpet_area' => 1000.00,
                        'expected_rate_per_sqft' => $expectedRate,
                        'expected_sale_amount' => $expectedSale,
                        'sale_rate_per_sqft' => $saleRate,
                        'sale_amount' => $saleAmount,
                        'difference' => $difference,
                        'status' => $status,
                    ]);

                    // Append initial rate log
                    UnitRateLog::create([
                        'unit_id' => $unit->id,
                        'rate' => 4500.00,
                        'effective_from' => now()->toDateString(),
                        'changed_by' => $ownerIn->id,
                        'reason' => 'Initial seeding',
                    ]);

                    // Append initial status log
                    UnitStatusLog::create([
                        'unit_id' => $unit->id,
                        'from_status' => null,
                        'to_status' => $status,
                        'changed_by' => $ownerIn->id,
                        'reason' => 'Initial seeding',
                    ]);
                }
            }
        }

        // 7. Create Sales Executives
        $executivesData = [
            ['name' => 'Vikram Sharma', 'email' => 'vikram@hindustan.com', 'avatar_url' => 'VS'],
            ['name' => 'Priya Nair', 'email' => 'priya@hindustan.com', 'avatar_url' => 'PN'],
        ];

        $executives = [];
        foreach ($executivesData as $data) {
            $executives[] = SalesExecutive::create($data);
        }

        // 8. Create Bookings & Payments
        $booking = Booking::create([
            'booking_number' => 'BK-0001',
            'customer_id' => $customers[0]->id,
            'project_id' => $projects[0]->id,
            'unit_id' => Unit::first()->id,
            'sales_executive_id' => $executives[0]->id,
            'amount' => 5000000.00,
            'status' => 'approved',
        ]);

        Payment::create([
            'receipt_number' => 'REC-00001',
            'customer_id' => $customers[0]->id,
            'project_id' => $projects[0]->id,
            'booking_id' => $booking->id,
            'amount' => 2000000.00,
            'payment_mode' => 'Bank Transfer',
            'status' => 'completed',
            'payment_date' => now(),
        ]);

        // 9. Create Approval Rules
        ApprovalRule::create([
            'module' => 'discount',
            'min_role' => 'Owner',
            'threshold_amount' => 100000.00,
            'is_active' => true,
        ]);

        // Seed some brokers and partners for India system
        $brokerAcc = Account::create([
            'system_id' => $india->id,
            'code' => 'BRK-ACC-01',
            'name' => 'Broker Commissions Payable',
            'type' => 'liability',
            'is_active' => true,
        ]);

        \App\Models\Broker::create([
            'system_id' => $india->id,
            'name' => 'Apex Realty Brokers',
            'default_commission_pct' => 2.50,
            'linked_account_id' => $brokerAcc->id,
        ]);

        \App\Models\Broker::create([
            'system_id' => $india->id,
            'name' => 'Metro Homes Agents',
            'default_commission_pct' => 1.75,
            'linked_account_id' => $brokerAcc->id,
        ]);

        // Seed some partners
        $partner1Acc = Account::create([
            'system_id' => $india->id,
            'code' => 'PRT-ACC-01',
            'name' => 'Basheer Capital',
            'type' => 'liability',
            'is_active' => true,
        ]);
        $partner1 = Payee::create([
            'system_id' => $india->id,
            'type' => 'Partner',
            'name' => 'Basheer',
            'linked_account_id' => $partner1Acc->id,
        ]);

        $partner2Acc = Account::create([
            'system_id' => $india->id,
            'code' => 'PRT-ACC-02',
            'name' => 'Pavoor Capital',
            'type' => 'liability',
            'is_active' => true,
        ]);
        $partner2 = Payee::create([
            'system_id' => $india->id,
            'type' => 'Partner',
            'name' => 'Pavoor',
            'linked_account_id' => $partner2Acc->id,
        ]);

        // Define shares for projects
        PartnerShare::create([
            'system_id' => $india->id,
            'project_id' => $projects[0]->id,
            'partner_id' => $partner1->id,
            'share_pct' => 57.50,
        ]);
        PartnerShare::create([
            'system_id' => $india->id,
            'project_id' => $projects[0]->id,
            'partner_id' => $partner2->id,
            'share_pct' => 42.50,
        ]);

        // 9.5 Seed default banks
        \App\Models\Bank::firstOrCreate(['ifsc_code' => 'HDFC0000123'], ['bank_name' => 'HDFC Bank', 'status' => 'active']);
        \App\Models\Bank::firstOrCreate(['ifsc_code' => 'SBIN0000456'], ['bank_name' => 'State Bank of India', 'status' => 'active']);
        \App\Models\Bank::firstOrCreate(['ifsc_code' => 'FDRL0000789'], ['bank_name' => 'Federal Bank', 'status' => 'inactive']);

        // 10. Record default activity log entries using the new record helper
        ActivityLog::record('System Booted', 'System initialized and default seed data populated.');
    }
}
