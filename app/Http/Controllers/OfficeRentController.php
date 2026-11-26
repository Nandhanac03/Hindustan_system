<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\CompanyBankAccount;
use App\Models\JournalEntry;
use App\Models\JournalVoucher;
use App\Models\OfficeRent;
use App\Models\VoucherType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OfficeRentController extends Controller
{
    private function resolveSystemId(): int
    {
        $user = Auth::user();
        return (int) ($user?->system_id ?: 1);
    }

    private function scopeBySystem($query)
    {
        $user = Auth::user();
        if ($user && !$user->hasMultiSystemAccess() && $user->system_id) {
            $query->where('system_id', $user->system_id);
        }
        return $query;
    }

    private function buildFilteredQuery(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $yearFilter = $request->query('year', 'all');
        $locationFilter = $request->query('location', 'all');

        $query = $this->scopeBySystem(OfficeRent::with(['companyBankAccount', 'journalVoucher', 'paymentVoucher']));

        if ($statusFilter !== 'all' && in_array($statusFilter, ['Paid', 'Unpaid'], true)) {
            $query->where('payment_status', $statusFilter);
        }
        if ($yearFilter !== 'all' && is_numeric($yearFilter)) {
            $query->where('rent_year', (int) $yearFilter);
        }
        if ($locationFilter !== 'all' && $locationFilter !== '') {
            $query->where('office_location', $locationFilter);
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $yearFilter = $request->query('year', 'all');
        $locationFilter = $request->query('location', 'all');

        $rents = $this->buildFilteredQuery($request)
            ->orderByDesc('voucher_date')
            ->orderByDesc('id')
            ->get();

        $totalGross = (float) $rents->sum('gross_rent_amount');
        $totalPaid = (float) $rents->where('payment_status', 'Paid')->sum('net_payable_amount');
        $totalUnpaid = (float) $rents->where('payment_status', 'Unpaid')->sum('net_payable_amount');

        $bankAccounts = CompanyBankAccount::where('status', 'active')
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();

        $locations = $this->scopeBySystem(OfficeRent::query())
            ->distinct()
            ->pluck('office_location')
            ->merge(['Corporate Office', 'Branch Office', 'Site Office'])
            ->unique()
            ->values();

        $years = $this->scopeBySystem(OfficeRent::query())
            ->distinct()
            ->orderByDesc('rent_year')
            ->pluck('rent_year');

        return view('office-rent.index', compact(
            'rents',
            'bankAccounts',
            'locations',
            'years',
            'statusFilter',
            'yearFilter',
            'locationFilter',
            'totalGross',
            'totalPaid',
            'totalUnpaid'
        ));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $rents = $this->buildFilteredQuery($request)
            ->orderByDesc('voucher_date')
            ->orderByDesc('id')
            ->get();

        $fileName = 'office-rent-report-' . date('Y-m-d') . '.xls';

        return response()->streamDownload(function () use ($rents) {
            echo '<html><head><meta charset="UTF-8"><style>'
                . 'table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; }'
                . 'th { background-color: #2a2415; color: #ffffff; font-weight: bold; padding: 8px; border: 1px solid #cbd5e1; text-align: left; }'
                . 'td { padding: 7px 8px; border: 1px solid #cbd5e1; }'
                . '.num { text-align: right; mso-number-format:"\#\,\#\#0\.00"; }'
                . '.tot { background-color: #f8fafc; font-weight: bold; }'
                . '</style></head><body>';
            echo '<table><thead><tr>'
                . '<th>#</th>'
                . '<th>Voucher Date</th>'
                . '<th>Voucher No</th>'
                . '<th>Office Location</th>'
                . '<th>Landlord / Payee</th>'
                . '<th>Rent Period</th>'
                . '<th>Rent Amount (INR)</th>'
                . '<th>Status</th>'
                . '<th>Company Bank</th>'
                . '<th>Payment Date</th>'
                . '<th>Reference No</th>'
                . '<th>Remarks</th>'
                . '</tr></thead><tbody>';

            $sumRent = 0.0;

            foreach ($rents as $idx => $r) {
                $amount = (float) $r->gross_rent_amount;
                $sumRent += $amount;

                echo '<tr>'
                    . '<td>' . ($idx + 1) . '</td>'
                    . '<td>' . e($r->voucher_date ? $r->voucher_date->format('d/m/Y') : '') . '</td>'
                    . '<td>' . e($r->journalVoucher?->voucher_no ?? '') . '</td>'
                    . '<td>' . e($r->office_location) . '</td>'
                    . '<td>' . e($r->landlord_name) . '</td>'
                    . '<td>' . e($r->rent_period_label) . '</td>'
                    . '<td class="num">' . number_format($amount, 2, '.', '') . '</td>'
                    . '<td>' . e($r->payment_status) . '</td>'
                    . '<td>' . e($r->companyBankAccount ? ($r->companyBankAccount->bank_name . ($r->companyBankAccount->account_number ? ' (' . $r->companyBankAccount->account_number . ')' : '')) : '') . '</td>'
                    . '<td>' . e($r->payment_date ? $r->payment_date->format('d/m/Y') : '') . '</td>'
                    . '<td>' . e($r->payment_reference ?? '') . '</td>'
                    . '<td>' . e($r->remarks ?? '') . '</td>'
                    . '</tr>';
            }

            echo '<tr class="tot">'
                . '<td colspan="6" style="text-align:right;">TOTAL</td>'
                . '<td class="num">' . number_format($sumRent, 2, '.', '') . '</td>'
                . '<td colspan="5"></td>'
                . '</tr>';
            echo '</tbody></table></body></html>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'office_location' => 'required|string|max:150',
            'landlord_name' => 'required|string|max:150',
            'rent_month' => 'required|integer|min:1|max:12',
            'rent_year' => 'required|integer|min:2000|max:2100',
            'gross_rent_amount' => 'required|numeric|min:0.01',
            'payment_status' => 'required|in:Paid,Unpaid',
            'company_bank_account_id' => 'required_if:payment_status,Paid|nullable|exists:company_bank_accounts,id',
            'payment_reference' => 'nullable|string|max:100',
            'voucher_date' => 'required|date',
            'remarks' => 'nullable|string|max:255',
        ]);

        $gross = round((float) $data['gross_rent_amount'], 2);
        $tds = 0.00;
        $netPayable = $gross;

        $month = (int) $data['rent_month'];
        $year = (int) $data['rent_year'];
        $periodLabel = Carbon::createFromDate($year, $month, 1)->format('F Y');
        $voucherDate = Carbon::parse($data['voucher_date'])->format('Y-m-d');
        $isPaid = $data['payment_status'] === 'Paid';
        $refNo = !empty($data['payment_reference']) ? trim((string) $data['payment_reference']) : null;

        try {
            DB::transaction(function () use ($data, $gross, $tds, $netPayable, $month, $year, $periodLabel, $voucherDate, $isPaid, $refNo) {
                $bankAccount = null;
                if ($isPaid) {
                    $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail((int) $data['company_bank_account_id']);
                    if ((float) $bankAccount->current_balance < $netPayable) {
                        throw new \RuntimeException(
                            "Insufficient balance in {$bankAccount->bank_name}. Available: ₹" . number_format((float) $bankAccount->current_balance, 2)
                        );
                    }
                    $bankAccount->decrement('current_balance', $netPayable);
                }

                $this->ensureChartOfAccounts();

                $voucherType = VoucherType::firstOrCreate(
                    ['code' => 'OFFICE_RENT'],
                    [
                        'name' => 'Office Rent Entry',
                        'prefix' => 'JV-RENT',
                        'description' => 'Generated on office rent expense booking and payment',
                        'is_active' => true,
                    ]
                );

                $rent = OfficeRent::create([
                    'system_id' => $this->resolveSystemId(),
                    'office_location' => trim($data['office_location']),
                    'landlord_name' => trim($data['landlord_name']),
                    'rent_month' => $month,
                    'rent_year' => $year,
                    'rent_period_label' => $periodLabel,
                    'gross_rent_amount' => $gross,
                    'tds_amount' => $tds,
                    'net_payable_amount' => $netPayable,
                    'payment_status' => $data['payment_status'],
                    'company_bank_account_id' => $bankAccount?->id,
                    'payment_reference' => $refNo,
                    'voucher_date' => $voucherDate,
                    'payment_date' => $isPaid ? $voucherDate : null,
                    'remarks' => $data['remarks'] ?? null,
                    'created_by' => Auth::id(),
                ]);

                $voucherNo = $this->generateUniqueVoucherNo('JV-RENT-' . date('Y', strtotime($voucherDate)) . '-' . str_pad((string) $rent->id, 4, '0', STR_PAD_LEFT));
                $bankLabel = $bankAccount
                    ? trim(($bankAccount->account_name ? $bankAccount->account_name . ' - ' : '') . $bankAccount->bank_name . ($bankAccount->account_number ? ' (A/C ' . $bankAccount->account_number . ')' : ''))
                    : null;

                $narration = "Office Rent ({$rent->office_location}) for {$periodLabel} - Landlord: {$rent->landlord_name}"
                    . ($isPaid && $bankLabel ? " | Paid from {$bankLabel}" : ' | Booked as Unpaid Payable');

                $journalVoucher = JournalVoucher::create([
                    'voucher_no' => $voucherNo,
                    'voucher_type_id' => $voucherType->id,
                    'voucher_date' => $voucherDate,
                    'reference_id' => $rent->id,
                    'narration' => $narration,
                    'is_active' => true,
                ]);

                // 1. Debit: 4106 - Office Rent & Maintenance (Rent Amount)
                JournalEntry::create([
                    'voucher_id' => $journalVoucher->id,
                    'account_id' => '4106',
                    'entity_type' => 'OFFICE_RENT',
                    'entity_id' => $rent->id,
                    'debit_amount' => $gross,
                    'credit_amount' => 0.00,
                    'line_narration' => "Office Rent & Maintenance ({$rent->office_location}) - {$periodLabel} | Landlord: {$rent->landlord_name}",
                ]);

                // 2. Credit: 1001 - Bank Balances (if Paid) OR 2010 - Provisions & Outstanding Payables (if Unpaid)
                if ($netPayable > 0) {
                    if ($isPaid && $bankAccount) {
                        JournalEntry::create([
                            'voucher_id' => $journalVoucher->id,
                            'account_id' => '1001',
                            'entity_type' => 'BANK',
                            'entity_id' => $bankAccount->id,
                            'debit_amount' => 0.00,
                            'credit_amount' => $netPayable,
                            'line_narration' => "Office Rent paid to {$rent->landlord_name} ({$rent->office_location}) from {$bankLabel}" . ($refNo ? " (Ref: {$refNo})" : ''),
                        ]);
                    } else {
                        JournalEntry::create([
                            'voucher_id' => $journalVoucher->id,
                            'account_id' => '2010',
                            'entity_type' => 'OFFICE_RENT',
                            'entity_id' => $rent->id,
                            'debit_amount' => 0.00,
                            'credit_amount' => $netPayable,
                            'line_narration' => "Provisions & Outstanding Payables - Office Rent ({$rent->landlord_name}, {$rent->office_location}) - {$periodLabel}",
                        ]);
                    }
                }

                $rent->update(['journal_voucher_id' => $journalVoucher->id]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['company_bank_account_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('office-rent.index')->with('status', 'Office rent entry recorded and accounting voucher posted.');
    }

    public function pay(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'company_bank_account_id' => 'required|exists:company_bank_accounts,id',
            'payment_date' => 'required|date',
            'payment_reference' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:255',
        ]);

        $rent = $this->scopeBySystem(OfficeRent::query())->findOrFail($id);
        if ($rent->payment_status === 'Paid') {
            return back()->withErrors(['payment' => 'This office rent entry is already paid.']);
        }

        $netPayable = (float) $rent->net_payable_amount;
        if ($netPayable <= 0) {
            return back()->withErrors(['payment' => 'Net payable amount must be greater than zero.']);
        }

        $payDate = Carbon::parse($data['payment_date'])->format('Y-m-d');
        $refNo = !empty($data['payment_reference']) ? trim((string) $data['payment_reference']) : null;

        try {
            DB::transaction(function () use ($rent, $data, $netPayable, $payDate, $refNo) {
                $bankAccount = CompanyBankAccount::lockForUpdate()->findOrFail((int) $data['company_bank_account_id']);
                if ((float) $bankAccount->current_balance < $netPayable) {
                    throw new \RuntimeException(
                        "Insufficient balance in {$bankAccount->bank_name}. Available: ₹" . number_format((float) $bankAccount->current_balance, 2)
                    );
                }
                $bankAccount->decrement('current_balance', $netPayable);

                $this->ensureChartOfAccounts();

                $voucherType = VoucherType::firstOrCreate(
                    ['code' => 'OFFICE_RENT_PAYMENT'],
                    [
                        'name' => 'Office Rent Payment',
                        'prefix' => 'JV-RENTP',
                        'description' => 'Generated on settling unpaid office rent payable',
                        'is_active' => true,
                    ]
                );

                $voucherNo = $this->generateUniqueVoucherNo('JV-RENTP-' . date('Y', strtotime($payDate)) . '-' . str_pad((string) $rent->id, 4, '0', STR_PAD_LEFT));
                $bankLabel = trim(($bankAccount->account_name ? $bankAccount->account_name . ' - ' : '') . $bankAccount->bank_name . ($bankAccount->account_number ? ' (A/C ' . $bankAccount->account_number . ')' : ''));

                $paymentVoucher = JournalVoucher::create([
                    'voucher_no' => $voucherNo,
                    'voucher_type_id' => $voucherType->id,
                    'voucher_date' => $payDate,
                    'reference_id' => $rent->id,
                    'narration' => "Office Rent Payable settled for {$rent->office_location} ({$rent->rent_period_label}) to {$rent->landlord_name} via {$bankLabel}",
                    'is_active' => true,
                ]);

                // Debit: 2010 - Provisions & Outstanding Payables
                JournalEntry::create([
                    'voucher_id' => $paymentVoucher->id,
                    'account_id' => '2010',
                    'entity_type' => 'OFFICE_RENT',
                    'entity_id' => $rent->id,
                    'debit_amount' => $netPayable,
                    'credit_amount' => 0.00,
                    'line_narration' => "Provisions & Outstanding Payables cleared for {$rent->landlord_name} ({$rent->office_location}) - {$rent->rent_period_label}",
                ]);

                // Credit: 1001 - Bank Balances
                JournalEntry::create([
                    'voucher_id' => $paymentVoucher->id,
                    'account_id' => '1001',
                    'entity_type' => 'BANK',
                    'entity_id' => $bankAccount->id,
                    'debit_amount' => 0.00,
                    'credit_amount' => $netPayable,
                    'line_narration' => "Office Rent paid to {$rent->landlord_name} ({$rent->office_location}) from {$bankLabel}" . ($refNo ? " (Ref: {$refNo})" : ''),
                ]);

                $rent->update([
                    'payment_status' => 'Paid',
                    'company_bank_account_id' => $bankAccount->id,
                    'payment_reference' => $refNo ?: $rent->payment_reference,
                    'payment_date' => $payDate,
                    'payment_voucher_id' => $paymentVoucher->id,
                    'remarks' => $data['remarks'] ?? $rent->remarks,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['company_bank_account_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('office-rent.index')->with('status', 'Office rent paid via bank account and accounting voucher posted.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $rent = $this->scopeBySystem(OfficeRent::query())->findOrFail($id);

        DB::transaction(function () use ($rent) {
            if ($rent->payment_status === 'Paid' && $rent->company_bank_account_id && (float) $rent->net_payable_amount > 0) {
                $bank = CompanyBankAccount::lockForUpdate()->find($rent->company_bank_account_id);
                $bank?->increment('current_balance', (float) $rent->net_payable_amount);
            }

            foreach (array_filter([$rent->journal_voucher_id, $rent->payment_voucher_id]) as $voucherId) {
                JournalEntry::where('voucher_id', $voucherId)->delete();
                JournalVoucher::where('id', $voucherId)->delete();
            }

            $rent->delete();
        });

        return redirect()->route('office-rent.index')->with('status', 'Office rent entry deleted and linked accounting entries reversed.');
    }

    private function ensureChartOfAccounts(): void
    {
        $requiredAccounts = [
            '1001' => ['name' => 'Bank Balances', 'type' => 'ASSET'],
            '4106' => ['name' => 'Office Rent & Maintenance', 'type' => 'EXPENSE'],
            '2010' => ['name' => 'Provisions & Outstanding Payables', 'type' => 'LIABILITY'],
        ];
        foreach ($requiredAccounts as $accCode => $accInfo) {
            ChartOfAccount::firstOrCreate(
                ['account_code' => $accCode],
                [
                    'account_name' => $accInfo['name'],
                    'account_type' => $accInfo['type'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function generateUniqueVoucherNo(string $base): string
    {
        $voucherNo = $base;
        $suffix = 1;
        while (JournalVoucher::where('voucher_no', $voucherNo)->exists()) {
            $voucherNo = $base . '-' . $suffix;
            $suffix++;
        }
        return $voucherNo;
    }
}
