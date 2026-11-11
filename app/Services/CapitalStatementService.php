<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Account;
use App\Models\Brokerage;
use App\Models\EmiSchedule;
use App\Models\PartnerAllocation;
use App\Models\PartnerContribution;
use App\Models\PartnerShare;
use App\Models\Payee;
use App\Models\PettyCashTransaction;
use App\Models\Project;
use App\Models\RaBill;
use App\Models\Receipt;
use App\Models\SiteExpense;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CapitalStatementService
{
    public function getStatementData(array $filters = []): array
    {
        $systemId = Auth::user()->system_id ?? 1;
        $system = System::find($systemId);
        $currencyCode = $system?->currency_code ?: 'INR';
        $currency = $currencyCode === 'INR' ? '₹' : $currencyCode;

        // 1. Fetch real Partners & Shares
        $partners = Payee::whereRaw("LOWER(type) = 'partner'")->orderBy('id')->get();
        $allShares = PartnerShare::all();
        $partnerAccountIds = $partners->pluck('linked_account_id')->filter()->toArray();
        $partnerAccountMap = $partners->pluck('id', 'linked_account_id')->filter()->toArray();
        $accountsById = Account::whereIn('id', $partnerAccountIds)->get()->keyBy('id');

        $partnersData = [];
        foreach ($partners as $idx => $p) {
            $pShare = $allShares->where('partner_id', $p->id)->first();
            $sharePct = $pShare ? (float)$pShare->share_pct : ($p->id == 1 ? 57.5 : 42.5);
            $acc = $p->linked_account_id ? ($accountsById[$p->linked_account_id] ?? null) : null;

            $partnersData[] = [
                'id'           => $p->id,
                'partner_code' => 'P-' . str_pad((string)($idx + 1), 3, '0', STR_PAD_LEFT),
                'account_code' => $acc?->code ?: ('PRT-ACC-' . str_pad((string)$p->id, 2, '0', STR_PAD_LEFT)),
                'name'         => $p->name,
                'share_pct'    => $sharePct,
                'pan'          => $p->pan ?: '—',
                'gstin'        => $p->gstin ?: '—',
                'phone'        => $p->phone ?: '—',
                'email'        => $p->email ?: '—',
                'address'      => $p->address ?: '—',
                'role'         => $p->id == 1 ? 'Lead Developer' : 'JV Partner / Land Owner',
            ];
        }

        $partnerPayeeIds = $partners->pluck('id')->toArray();

        // 2. Fetch Real Capital Infusions (PartnerContribution)
        $ledgerEvents = [];

        $contribVouchers = DB::table('voucher_lines')
            ->join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
            ->whereIn('voucher_lines.account_id', $partnerAccountIds)
            ->where('voucher_lines.credit', '>', 0)
            ->select('voucher_lines.*', 'vouchers.voucher_number', 'vouchers.date as v_date', 'vouchers.narration')
            ->get();

        $contribs = PartnerContribution::with(['paymentMode'])->orderBy('contribution_date')->get();
        $usedVoucherIds = [];

        foreach ($contribs as $c) {
            $cDate = $c->contribution_date ? Carbon::parse($c->contribution_date) : Carbon::parse($c->created_at);
            $dateRaw = $cDate->format('Y-m-d');

            $matchedV = $contribVouchers->first(function ($cv) use ($c, $dateRaw, &$usedVoucherIds) {
                if (in_array($cv->voucher_id, $usedVoucherIds, true)) return false;
                return (float)$cv->credit == (float)$c->amount && $cv->v_date === $dateRaw;
            });
            $vNo = 'PRTC-' . str_pad((string)$c->id, 4, '0', STR_PAD_LEFT);
            if ($matchedV) {
                $vNo = $matchedV->voucher_number;
                $usedVoucherIds[] = $matchedV->voucher_id;
            }

            $modeName = $c->paymentMode?->name ?: 'Bank Transfer';

            $ledgerEvents[] = [
                'partner_id'  => (int)$c->partner_id,
                'date_raw'    => $dateRaw,
                'date'        => $cDate->format('d/m/Y'),
                'voucher_no'  => $vNo,
                'particulars' => 'Capital Infusion - ' . $modeName,
                'reference'   => strip_tags((string)($c->reference_no ?: ($c->remarks ?: '—'))),
                'entry_type'  => 'Infusion',
                'credit'      => (float)$c->amount,
                'debit'       => 0.0,
            ];
        }

        foreach ($contribVouchers as $cv) {
            if (in_array($cv->voucher_id, $usedVoucherIds, true)) continue;
            $pId = $partnerAccountMap[$cv->account_id] ?? null;
            if (!$pId) continue;
            $d = Carbon::parse($cv->v_date);
            $ledgerEvents[] = [
                'partner_id'  => (int)$pId,
                'date_raw'    => $d->format('Y-m-d'),
                'date'        => $d->format('d/m/Y'),
                'voucher_no'  => $cv->voucher_number,
                'particulars' => strip_tags((string)($cv->line_narration ?: ($cv->narration ?: 'Capital Infusion'))),
                'reference'   => 'Voucher Credit',
                'entry_type'  => 'Infusion',
                'credit'      => (float)$cv->credit,
                'debit'       => 0.0,
            ];
        }

        // 3. Fetch Real Drawings & Payouts Released (PartnerAllocation, bill_payments, voucher_lines)
        $allocations = PartnerAllocation::with(['voucher', 'payment'])->orderBy('date')->get();
        $allocVoucherIds = $allocations->pluck('voucher_id')->filter()->toArray();

        foreach ($allocations as $alloc) {
            $d = $alloc->date ? Carbon::parse($alloc->date) : Carbon::parse($alloc->created_at);
            $vNo = $alloc->voucher?->voucher_number ?: ('PY-PTR-' . $d->format('Ymd') . '-' . str_pad((string)$alloc->id, 4, '0', STR_PAD_LEFT));
            $mode = $alloc->payment_mode ?: 'Bank Transfer';
            $isReceiptSplit = str_starts_with($vNo, 'RC-');
            $desc = $isReceiptSplit
                ? 'Partner Share Allocation Drawing'
                : 'Partner Drawing / Payout - ' . $mode;

            $ledgerEvents[] = [
                'partner_id'  => (int)$alloc->partner_id,
                'date_raw'    => $d->format('Y-m-d'),
                'date'        => $d->format('d/m/Y'),
                'voucher_no'  => $vNo,
                'particulars' => $desc,
                'reference'   => strip_tags((string)($alloc->remarks ?: ($isReceiptSplit ? 'Receipt Share Split' : $mode))),
                'entry_type'  => 'Drawing',
                'credit'      => 0.0,
                'debit'       => (float)$alloc->allocated_amount,
            ];
        }

        $billPayouts = DB::table('bill_payments')->whereIn('payee_id', $partnerPayeeIds)->orderBy('date')->get();
        foreach ($billPayouts as $bp) {
            $d = $bp->date ? Carbon::parse($bp->date) : Carbon::now();
            $ledgerEvents[] = [
                'partner_id'  => (int)$bp->payee_id,
                'date_raw'    => $d->format('Y-m-d'),
                'date'        => $d->format('d/m/Y'),
                'voucher_no'  => 'BILL-PAY-' . str_pad((string)$bp->id, 3, '0', STR_PAD_LEFT),
                'particulars' => 'Partner Payout / Drawing',
                'reference'   => 'Bill Settlement',
                'entry_type'  => 'Drawing',
                'credit'      => 0.0,
                'debit'       => (float)$bp->amount,
            ];
        }

        $voucherPayoutsQuery = DB::table('voucher_lines')
            ->join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
            ->whereIn('voucher_lines.account_id', $partnerAccountIds)
            ->where('vouchers.type', 'Payment')
            ->where('voucher_lines.debit', '>', 0)
            ->where('vouchers.voucher_number', 'NOT LIKE', 'PY-REF-%')
            ->where('vouchers.narration', 'NOT LIKE', '%Customer Refund%')
            ->where('vouchers.narration', 'NOT LIKE', '%cancellation%');
        if (!empty($allocVoucherIds)) {
            $voucherPayoutsQuery->whereNotIn('vouchers.id', $allocVoucherIds);
        }
        $voucherPayouts = $voucherPayoutsQuery->select(
            'voucher_lines.*',
            'vouchers.voucher_number',
            'vouchers.date as v_date',
            'vouchers.narration'
        )->get();

        foreach ($voucherPayouts as $vp) {
            $pId = $partnerAccountMap[$vp->account_id] ?? null;
            if (!$pId) continue;
            $d = $vp->v_date ? Carbon::parse($vp->v_date) : Carbon::now();
            $cleanRef = trim(strip_tags((string)($vp->narration ?: '')));
            $ledgerEvents[] = [
                'partner_id'  => (int)$pId,
                'date_raw'    => $d->format('Y-m-d'),
                'date'        => $d->format('d/m/Y'),
                'voucher_no'  => $vp->voucher_number,
                'particulars' => strip_tags((string)($vp->line_narration ?: 'Partner Drawing / Payout Voucher')),
                'reference'   => $cleanRef !== '' ? $cleanRef : 'Voucher Debit',
                'entry_type'  => 'Drawing',
                'credit'      => 0.0,
                'debit'       => (float)$vp->debit,
            ];
        }

        usort($ledgerEvents, fn($a, $b) => strcmp($a['date_raw'], $b['date_raw']));

        // 4. Real Profit Pool Events (Matches Project Margin / Dashboard Realized Net Operating Profit)
        //    Realized Customer Collections (whereNull partner_id) - Refunds - RA Bills - Site Expenses - Supplier Bills - Brokerages - Petty Cash - Loan Interest
        $profitEvents = [];

        $realizedReceipts = Receipt::where('realization_status', 'realized')
            ->whereNull('partner_id')
            ->get(['id', 'receipt_date', 'amount', 'created_at']);
        foreach ($realizedReceipts as $rec) {
            $d = $rec->receipt_date ? Carbon::parse($rec->receipt_date) : Carbon::parse($rec->created_at);
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => (float)$rec->amount,
            ];
        }

        // Deduct customer cancellation refunds already paid out (Posted vouchers with refund allocations)
        $refundVouchers = DB::table('vouchers')
            ->whereIn('type', ['Receipt', 'Payment'])
            ->where('status', 'Posted')
            ->whereNotNull('reference_no')
            ->get(['date', 'reference_no', 'created_at']);
        foreach ($refundVouchers as $rv) {
            $refData = json_decode($rv->reference_no, true);
            if (!empty($refData['allocations']) && is_array($refData['allocations'])) {
                foreach ($refData['allocations'] as $alloc) {
                    if (($alloc['type'] ?? '') === 'refund' && (float)($alloc['amount'] ?? 0) > 0) {
                        $d = $rv->date ? Carbon::parse($rv->date) : Carbon::parse($rv->created_at);
                        $profitEvents[] = [
                            'date_raw' => $d->format('Y-m-d'),
                            'amount'   => -1 * (float)$alloc['amount'],
                        ];
                    }
                }
            }
        }

        $raBills = RaBill::get(['id', 'submit_date', 'net_approved_amount', 'created_at']);
        foreach ($raBills as $ra) {
            $d = $ra->submit_date ? Carbon::parse($ra->submit_date) : Carbon::parse($ra->created_at);
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => -1 * (float)$ra->net_approved_amount,
            ];
        }

        $siteExpenses = SiteExpense::whereIn('status', ['Approved', 'approved', 'Paid', 'paid'])
            ->get(['id', 'voucher_date', 'net_amount', 'created_at']);
        foreach ($siteExpenses as $se) {
            $d = $se->voucher_date ? Carbon::parse($se->voucher_date) : Carbon::parse($se->created_at);
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => -1 * (float)$se->net_amount,
            ];
        }

        $supplierBills = DB::table('bills')->get(['id', 'final_amount', 'created_at']);
        foreach ($supplierBills as $b) {
            $d = $b->created_at ? Carbon::parse($b->created_at) : Carbon::now();
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => -1 * (float)$b->final_amount,
            ];
        }

        $brokerages = Brokerage::get(['id', 'commission_amount', 'created_at']);
        foreach ($brokerages as $br) {
            $d = Carbon::parse($br->created_at);
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => -1 * (float)$br->commission_amount,
            ];
        }

        $pettyCashOut = PettyCashTransaction::where('cash_out', '>', 0)->get(['id', 'transaction_date', 'cash_out', 'created_at']);
        foreach ($pettyCashOut as $pc) {
            $d = $pc->transaction_date ? Carbon::parse($pc->transaction_date) : Carbon::parse($pc->created_at);
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => -1 * (float)$pc->cash_out,
            ];
        }

        $paidEmis = EmiSchedule::where('status', 'Paid')->where('interest_component', '>', 0)->get(['due_date', 'interest_component', 'paid_date']);
        foreach ($paidEmis as $pe) {
            $d = Carbon::parse($pe->paid_date ?: $pe->due_date);
            $profitEvents[] = [
                'date_raw' => $d->format('Y-m-d'),
                'amount'   => -1 * (float)$pe->interest_component,
            ];
        }

        $projects = Project::orderBy('name')->get(['id', 'name']);
        $primaryProjectName = $projects->first()?->name ?: 'Tabasco Hindustan Infra Developers Pvt. Ltd';

        return [
            'currency'     => $currency,
            'currencyCode' => $currencyCode,
            'projectName'  => $primaryProjectName,
            'partnersData' => $partnersData,
            'ledgerEvents' => $ledgerEvents,
            'profitEvents' => $profitEvents,
            'projects'     => $projects,
            'asOnDate'     => Carbon::now()->format('d/m/Y'),
        ];
    }
}
