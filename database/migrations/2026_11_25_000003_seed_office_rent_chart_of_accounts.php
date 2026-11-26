<?php

use App\Models\ChartOfAccount;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $accounts = [
            ['account_code' => '1001', 'account_name' => 'Bank Balances', 'account_type' => 'ASSET'],
            ['account_code' => '2010', 'account_name' => 'Provisions & Outstanding Payables', 'account_type' => 'LIABILITY'],
            ['account_code' => '2023', 'account_name' => 'TDS Payable', 'account_type' => 'LIABILITY'],
            ['account_code' => '4106', 'account_name' => 'Office Rent & Maintenance', 'account_type' => 'EXPENSE'],
        ];

        foreach ($accounts as $acc) {
            ChartOfAccount::firstOrCreate(
                ['account_code' => $acc['account_code']],
                [
                    'account_name' => $acc['account_name'],
                    'account_type' => $acc['account_type'],
                    'is_active' => true,
                ]
            );
        }
    }

    public function down(): void
    {
        ChartOfAccount::whereIn('account_code', ['2010', '2023', '4106'])->delete();
    }
};
