<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['account_code' => '1001', 'account_name' => 'Karnataka Bank', 'account_type' => 'ASSET', 'is_active' => true],
            ['account_code' => '1002', 'account_name' => 'State Bank of India', 'account_type' => 'ASSET', 'is_active' => true],
            ['account_code' => '1003', 'account_name' => 'Customer Receivables', 'account_type' => 'ASSET', 'is_active' => true],
            ['account_code' => '1004', 'account_name' => 'Petty Cash Box', 'account_type' => 'ASSET', 'is_active' => true],
            ['account_code' => '2001', 'account_name' => 'Supplier Payables', 'account_type' => 'LIABILITY', 'is_active' => true],
            ['account_code' => '2002', 'account_name' => 'GST Payable', 'account_type' => 'LIABILITY', 'is_active' => true],
            ['account_code' => '2003', 'account_name' => 'Customer Advance Deposits', 'account_type' => 'LIABILITY', 'is_active' => true],
            ['account_code' => '3001', 'account_name' => 'Sales Revenue', 'account_type' => 'REVENUE', 'is_active' => true],
            ['account_code' => '3002', 'account_name' => 'Other Operational Income', 'account_type' => 'REVENUE', 'is_active' => true],
            ['account_code' => '4001', 'account_name' => 'Site Material Expense', 'account_type' => 'EXPENSE', 'is_active' => true],
            ['account_code' => '4002', 'account_name' => 'Contractor Labor Expense', 'account_type' => 'EXPENSE', 'is_active' => true],
            ['account_code' => '4003', 'account_name' => 'Agent Commission Expense', 'account_type' => 'EXPENSE', 'is_active' => true],
            ['account_code' => '4004', 'account_name' => 'General & Administrative Expense', 'account_type' => 'EXPENSE', 'is_active' => true],
        ];

        foreach ($accounts as $acc) {
            ChartOfAccount::updateOrCreate(['account_code' => $acc['account_code']], $acc);
        }
    }
}
