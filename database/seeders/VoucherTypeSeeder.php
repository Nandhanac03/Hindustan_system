<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VoucherType;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeds = [
            [
                'id'          => 1,
                'code'        => 'SALES_BOOKING',
                'name'        => 'Sales Booking Voucher',
                'prefix'      => 'JV-SB',
                'description' => 'Generated on unit booking agreement',
                'is_active'   => true,
            ],
            [
                'id'          => 2,
                'code'        => 'CUSTOMER_RECEIPT',
                'name'        => 'Customer Payment Receipt',
                'prefix'      => 'JV-CR',
                'description' => 'Generated on cheque realization / payment receipt',
                'is_active'   => true,
            ],
            [
                'id'          => 3,
                'code'        => 'CUSTOMER_REFUND',
                'name'        => 'Customer Refund Voucher',
                'prefix'      => 'JV-RF',
                'description' => 'Generated on booking cancellation / refund issue',
                'is_active'   => true,
            ],
            [
                'id'          => 4,
                'code'        => 'SUPPLIER_INVOICE',
                'name'        => 'Supplier Invoice Voucher',
                'prefix'      => 'JV-SI',
                'description' => 'Generated on supplier material bill approval',
                'is_active'   => true,
            ],
            [
                'id'          => 5,
                'code'        => 'SUPPLIER_PAYMENT',
                'name'        => 'Supplier Payment Release',
                'prefix'      => 'JV-SP',
                'description' => 'Generated on supplier cheque / transfer release',
                'is_active'   => true,
            ],
            [
                'id'          => 6,
                'code'        => 'CONTRACTOR_RA_BILL',
                'name'        => 'Contractor RA Bill Voucher',
                'prefix'      => 'JV-RA',
                'description' => 'Generated on site engineer verified RA bill',
                'is_active'   => true,
            ],
            [
                'id'          => 7,
                'code'        => 'AGENT_COMMISSION',
                'name'        => 'Agent Commission Provision',
                'prefix'      => 'JV-AG',
                'description' => 'Generated on tagging broker commission',
                'is_active'   => true,
            ],
            [
                'id'          => 8,
                'code'        => 'AGENT_PAYMENT',
                'name'        => 'Agent Payment Disbursement',
                'prefix'      => 'JV-AP',
                'description' => 'Generated on paying commission to broker',
                'is_active'   => true,
            ],
            [
                'id'          => 9,
                'code'        => 'PETTY_CASH_CONTRA',
                'name'        => 'Petty Cash Contra Transfer',
                'prefix'      => 'JV-PC',
                'description' => 'Generated on bank cash withdrawal to petty cash box',
                'is_active'   => true,
            ],
            [
                'id'          => 10,
                'code'        => 'GST_FILING',
                'name'        => 'GST Filing & Set-Off Voucher',
                'prefix'      => 'JV-TX',
                'description' => 'Generated on monthly GST set-off and tax settlement',
                'is_active'   => true,
            ],
        ];

        foreach ($seeds as $data) {
            VoucherType::updateOrCreate(['id' => $data['id']], $data);
        }
    }
}
