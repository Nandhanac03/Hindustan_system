<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleExtraWork;
use App\Models\Project;
use App\Models\CompanyBankAccount;
use Illuminate\Support\Facades\DB;

class CancellationAdditionalWorkController extends Controller
{
    public function index()
    {
        $cancellationCharges = Sale::where('status', 'cancelled')
            ->with([
                'customer',
                'project',
                'broker',
                'unit.unitType',
                'unit.floor',
                'saleUnits.unit.unitType',
                'saleUnits.unit.floor',
                'receipts.bank',
                'receipts.companyBankAccount',
                'statusLogs.performedBy'
            ])
            ->withSum('receipts as total_paid', 'amount')
            ->orderByDesc('cancelled_at')
            ->orderByDesc('updated_at')
            ->get();

        // Fetch refund disbursement vouchers if any to provide comprehensive refund payout details
        $postedVouchers = DB::table('vouchers')
            ->whereIn('type', ['Receipt', 'Payment'])
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->orderBy('date')
            ->orderBy('id')
            ->get(['id', 'voucher_number', 'date', 'reference_no', 'company_bank_account_id', 'created_by', 'created_at']);

        $bankNames = CompanyBankAccount::get(['id', 'bank_name', 'account_number'])->keyBy('id');
        $userNames = DB::table('users')
            ->whereIn('id', $postedVouchers->pluck('created_by')->filter()->unique()->all())
            ->pluck('name', 'id');

        $refundsPaidBySale = [];
        $refundPaymentsBySale = [];
        foreach ($postedVouchers as $v) {
            $refData = json_decode($v->reference_no, true);
            if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                foreach ($refData['allocations'] as $alloc) {
                    if (($alloc['type'] ?? '') === 'refund' && !empty($alloc['target_id'])) {
                        $sId = (int)$alloc['target_id'];
                        $amt = (float)($alloc['amount'] ?? 0);
                        $refundsPaidBySale[$sId] = ($refundsPaidBySale[$sId] ?? 0.0) + $amt;

                        $bankId = $refData['company_bank_account_id'] ?? $v->company_bank_account_id;
                        $bank = $bankId ? $bankNames->get((int)$bankId) : null;
                        $refundPaymentsBySale[$sId][] = [
                            'voucher_id'     => $v->id,
                            'voucher_number' => $v->voucher_number,
                            'date'           => $v->date,
                            'created_at'     => $v->created_at,
                            'amount'         => $amt,
                            'payment_mode'   => $refData['payment_mode'] ?? 'Bank Transfer',
                            'bank_name'      => $bank ? $bank->bank_name . ($bank->account_number ? ' (' . $bank->account_number . ')' : '') : '—',
                            'remarks'        => $refData['remarks'] ?? 'Cancellation refund payout',
                            'processed_by'   => $v->created_by ? ($userNames[$v->created_by] ?? null) : null,
                        ];
                    }
                }
            }
        }

        $cancellationCharges->transform(function ($sale) use ($refundsPaidBySale, $refundPaymentsBySale) {
            $sale->refund_paid = $refundsPaidBySale[$sale->id] ?? 0.00;
            $sale->refund_payments = $refundPaymentsBySale[$sale->id] ?? [];
            return $sale;
        });

        $additionalWorks = SaleExtraWork::with([
            'sale.customer',
            'sale.project',
            'sale.unit.unitType',
            'sale.unit.floor',
            'sale.saleUnits.unit.unitType',
            'sale.saleUnits.unit.floor',
            'sale.broker',
            'sale.receipts'
        ])
        ->orderByDesc('id')
        ->get();

        $projects = Project::orderBy('name')->get();

        return view('cancellation-additional-work.index', compact('cancellationCharges', 'additionalWorks', 'projects'));
    }
}

