<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalVoucher;
use App\Models\VoucherType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalVoucherController extends Controller
{
    /**
     * Display a listing of journal vouchers for client-side instant filtering.
     */
    public function index(Request $request)
    {
        $vouchers = JournalVoucher::with(['voucherType', 'entries.account'])
            ->orderBy('voucher_date', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($jv) {
                return [
                    'id' => $jv->id,
                    'voucher_no' => $jv->voucher_no,
                    'voucher_date' => $jv->voucher_date,
                    'voucher_date_formatted' => \Carbon\Carbon::parse($jv->voucher_date)->format('d/m/Y'),
                    'voucher_type_id' => $jv->voucher_type_id,
                    'voucher_type_name' => $jv->voucherType ? $jv->voucherType->name : 'Journal Voucher',
                    'reference_no' => $jv->reference_no ?: '-',
                    'narration' => $jv->narration ?: '-',
                    'total_debit' => (float) $jv->total_debit,
                    'total_credit' => (float) $jv->total_credit,
                    'status' => $jv->status ?: 'Posted',
                    'entries' => $jv->entries->map(function ($e) {
                        return [
                            'account_code' => $e->account_id,
                            'account_name' => $e->account ? $e->account->account_name : 'Unknown Account',
                            'debit_amount' => (float) $e->debit_amount,
                            'credit_amount' => (float) $e->credit_amount,
                            'line_narration' => $e->line_narration ?: '-',
                        ];
                    })->values()
                ];
            });

        $voucherTypes = VoucherType::where('is_active', true)->get();

        $accounts = ChartOfAccount::where('is_active', true)
            ->orderBy('account_code', 'asc')
            ->get();

        $nextVoucherNo = $this->generateNextVoucherNo();

        return view('journal-vouchers.index', compact(
            'vouchers',
            'voucherTypes',
            'accounts',
            'nextVoucherNo'
        ));
    }

    /**
     * Store a newly created journal voucher.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_date' => 'required|date',
            'voucher_type_id' => 'nullable|exists:voucher_types,id',
            'reference_no' => 'nullable|string|max:100|unique:journal_vouchers,reference_no',
            'narration' => 'required|string',
            'status' => 'required|in:Posted,Draft',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:chart_of_accounts,account_code',
            'entries.*.debit_amount' => 'nullable|numeric|min:0',
            'entries.*.credit_amount' => 'nullable|numeric|min:0',
            'entries.*.line_narration' => 'nullable|string|max:255',
        ], [
            'reference_no.unique' => 'The Reference No has already been taken by another Journal Voucher.',
            'narration.required' => 'Header narration / remarks is required.',
        ]);

        $totalDebit = 0.0;
        $totalCredit = 0.0;
        $validEntries = [];

        foreach ($request->entries as $entry) {
            $debit = (float)($entry['debit_amount'] ?? 0);
            $credit = (float)($entry['credit_amount'] ?? 0);

            if ($debit > 0 || $credit > 0) {
                $totalDebit += $debit;
                $totalCredit += $credit;
                $validEntries[] = [
                    'account_id' => $entry['account_id'],
                    'debit_amount' => $debit,
                    'credit_amount' => $credit,
                    'line_narration' => $entry['line_narration'] ?? null,
                ];
            }
        }

        if (count($validEntries) < 2) {
            return back()->withInput()->with('error', 'A valid Journal Voucher must contain at least two non-zero entry lines.');
        }

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()->with('error', 'Journal Voucher must be balanced! Total Debit (' . number_format($totalDebit, 2) . ') does not equal Total Credit (' . number_format($totalCredit, 2) . ').');
        }

        DB::transaction(function () use ($request, $validEntries) {
            $voucherNo = $request->voucher_no;
            if (empty($voucherNo) || JournalVoucher::where('voucher_no', $voucherNo)->exists()) {
                $voucherNo = $this->generateNextVoucherNo();
            }

            $journalVoucher = JournalVoucher::create([
                'voucher_no' => $voucherNo,
                'voucher_type_id' => $request->voucher_type_id ?: null,
                'voucher_date' => $request->voucher_date,
                'reference_no' => $request->reference_no,
                'narration' => $request->narration,
                'status' => $request->status,
                'is_active' => true,
            ]);

            foreach ($validEntries as $line) {
                JournalEntry::create([
                    'voucher_id' => $journalVoucher->id,
                    'account_id' => $line['account_id'],
                    'debit_amount' => $line['debit_amount'],
                    'credit_amount' => $line['credit_amount'],
                    'line_narration' => $line['line_narration'],
                ]);
            }
        });

        return redirect()->route('journal-vouchers.index')->with('success', 'Journal Voucher created successfully.');
    }

    /**
     * Display details of a journal voucher for JSON/modal viewing.
     */
    public function show($id)
    {
        $voucher = JournalVoucher::with(['voucherType', 'entries.account'])->findOrFail($id);

        return response()->json([
            'id' => $voucher->id,
            'voucher_no' => $voucher->voucher_no,
            'voucher_date' => $voucher->voucher_date,
            'voucher_type_id' => $voucher->voucher_type_id,
            'voucher_type' => $voucher->voucherType ? $voucher->voucherType->name : 'Journal Voucher',
            'reference_no' => $voucher->reference_no ?: '-',
            'narration' => $voucher->narration ?: '-',
            'status' => $voucher->status ?: 'Posted',
            'total_debit' => number_format($voucher->total_debit, 2),
            'total_credit' => number_format($voucher->total_credit, 2),
            'entries' => $voucher->entries->map(function ($e) {
                return [
                    'account_code' => $e->account_id,
                    'account_name' => $e->account ? $e->account->account_name : 'Unknown Account',
                    'debit_amount' => number_format($e->debit_amount, 2),
                    'credit_amount' => number_format($e->credit_amount, 2),
                    'line_narration' => $e->line_narration ?: '-',
                ];
            }),
        ]);
    }

    /**
     * Update an existing journal voucher.
     */
    public function update(Request $request, $id)
    {
        $journalVoucher = JournalVoucher::findOrFail($id);

        $request->validate([
            'voucher_date' => 'required|date',
            'voucher_type_id' => 'nullable|exists:voucher_types,id',
            'reference_no' => 'nullable|string|max:100|unique:journal_vouchers,reference_no,' . $id,
            'narration' => 'required|string',
            'status' => 'required|in:Posted,Draft',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:chart_of_accounts,account_code',
            'entries.*.debit_amount' => 'nullable|numeric|min:0',
            'entries.*.credit_amount' => 'nullable|numeric|min:0',
            'entries.*.line_narration' => 'nullable|string|max:255',
        ], [
            'reference_no.unique' => 'The Reference No has already been taken by another Journal Voucher.',
            'narration.required' => 'Header narration / remarks is required.',
        ]);

        $totalDebit = 0.0;
        $totalCredit = 0.0;
        $validEntries = [];

        foreach ($request->entries as $entry) {
            $debit = (float)($entry['debit_amount'] ?? 0);
            $credit = (float)($entry['credit_amount'] ?? 0);

            if ($debit > 0 || $credit > 0) {
                $totalDebit += $debit;
                $totalCredit += $credit;
                $validEntries[] = [
                    'account_id' => $entry['account_id'],
                    'debit_amount' => $debit,
                    'credit_amount' => $credit,
                    'line_narration' => $entry['line_narration'] ?? null,
                ];
            }
        }

        if (count($validEntries) < 2) {
            return back()->withInput()->with('error', 'A valid Journal Voucher must contain at least two entry lines.');
        }

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withInput()->with('error', 'Journal Voucher must be balanced! Total Debit (' . number_format($totalDebit, 2) . ') does not equal Total Credit (' . number_format($totalCredit, 2) . ').');
        }

        DB::transaction(function () use ($journalVoucher, $request, $validEntries) {
            $journalVoucher->update([
                'voucher_date' => $request->voucher_date,
                'voucher_type_id' => $request->voucher_type_id ?: null,
                'reference_no' => $request->reference_no,
                'narration' => $request->narration,
                'status' => $request->status,
            ]);

            // Replace line entries
            JournalEntry::where('voucher_id', $journalVoucher->id)->delete();

            foreach ($validEntries as $line) {
                JournalEntry::create([
                    'voucher_id' => $journalVoucher->id,
                    'account_id' => $line['account_id'],
                    'debit_amount' => $line['debit_amount'],
                    'credit_amount' => $line['credit_amount'],
                    'line_narration' => $line['line_narration'],
                ]);
            }
        });

        return redirect()->route('journal-vouchers.index')->with('success', 'Journal Voucher updated successfully.');
    }

    /**
     * Remove the specified journal voucher from storage.
     */
    public function destroy($id)
    {
        $journalVoucher = JournalVoucher::findOrFail($id);

        DB::transaction(function () use ($journalVoucher) {
            JournalEntry::where('voucher_id', $journalVoucher->id)->delete();
            $journalVoucher->delete();
        });

        return redirect()->route('journal-vouchers.index')->with('success', 'Journal Voucher deleted successfully.');
    }

    /**
     * Generate the next incremental Journal Voucher Number.
     */
    private function generateNextVoucherNo(): string
    {
        $year = date('Y');
        $prefix = "JV-{$year}-";

        $lastVoucher = JournalVoucher::where('voucher_no', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNum = 1;

        if ($lastVoucher) {
            $parts = explode('-', $lastVoucher->voucher_no);
            $lastSeq = end($parts);
            if (is_numeric($lastSeq)) {
                $nextNum = ((int) $lastSeq) + 1;
            }
        }

        return $prefix . str_pad((string) $nextNum, 4, '0', STR_PAD_LEFT);
    }
}
