<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\EmiSchedule;
use App\Models\LoanPrepayment;
use App\Models\LoanInterestLog;
use App\Models\Project;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        if (!$request->has('project_id') && !$request->filled('project_id') && $projects->isNotEmpty()) {
            $request->merge(['project_id' => (string)$projects->first()->id]);
        }
        
        $query = Loan::with(['project', 'ledgerAccount', 'interestAccount', 'prepayments']);
        
        // Filters
        if ($request->filled('loan_account_no')) {
            $query->where('loan_account_no', 'like', '%' . $request->loan_account_no . '%');
        }
        if ($request->filled('lender_name')) {
            $query->where('lender_name', 'like', '%' . $request->lender_name . '%');
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $loans = $query->latest()->paginate(50)->withQueryString();
        
        // Calculate dynamic sums and fetch next pending EMI for the table row listings
        foreach ($loans as $loan) {
            $paidSchedules = EmiSchedule::where('loan_id', $loan->id)->where('status', 'Paid')->get();
            $loan->paid_principal_to_date = $paidSchedules->sum('principal_component');
            $loan->cumulative_interest_paid = $paidSchedules->sum('interest_component');
            
            // Next unpaid EMI for quick pay
            $loan->next_emi = EmiSchedule::where('loan_id', $loan->id)
                ->where('status', '!=', 'Paid')
                ->orderBy('installment_no')
                ->first();
        }
        
        $today = now()->startOfDay();
        $endOfMonth = now()->endOfMonth();

        // Overdue EMIs (due before today, not paid)
        $overdueEmis = EmiSchedule::whereHas('loan', function ($q) {
                $q->where('status', 'Active');
            })
            ->where('status', '!=', 'Paid')
            ->where('due_date', '<', $today)
            ->get();
        $overdueCount = $overdueEmis->count();
        $overdueAmount = $overdueEmis->sum(function ($inst) {
            return max(0, (float)$inst->emi_amount - (float)$inst->amount_paid);
        });

        // Due this month EMIs (due between today and end of month, not paid)
        $dueThisMonthEmis = EmiSchedule::whereHas('loan', function ($q) {
                $q->where('status', 'Active');
            })
            ->where('status', '!=', 'Paid')
            ->whereBetween('due_date', [$today, $endOfMonth])
            ->get();
        $dueThisMonthCount = $dueThisMonthEmis->count();
        $dueThisMonthAmount = $dueThisMonthEmis->sum(function ($inst) {
            return max(0, (float)$inst->emi_amount - (float)$inst->amount_paid);
        });

        $totalPendingCount = $overdueCount + $dueThisMonthCount;
        $totalPendingAmount = $overdueAmount + $dueThisMonthAmount;

        // Global stats for KPI metrics cards
        $activeLoansCount = Loan::where('status', 'Active')->count();
        $totalOutstanding = Loan::sum('outstanding_balance');
        $allPaidSchedules = EmiSchedule::where('status', 'Paid')->get();
        $totalPaidPrincipal = $allPaidSchedules->sum('principal_component');
        $totalPaidInterest = $allPaidSchedules->sum('interest_component');
        
        $accounts = Account::orderBy('name')->get();
        $assetAccounts = Account::where('type', 'Asset')->where('is_active', true)->orderBy('name')->get();
        $banks = \App\Models\Bank::where('status', 'active')->orderBy('bank_name')->get();
        $interestLogs = LoanInterestLog::with('loan')->latest()->get();

        return view('loans.index', compact(
            'loans',
            'projects',
            'accounts',
            'banks',
            'assetAccounts',
            'overdueCount',
            'overdueAmount',
            'dueThisMonthCount',
            'dueThisMonthAmount',
            'totalPendingCount',
            'totalPendingAmount',
            'activeLoansCount',
            'totalOutstanding',
            'totalPaidPrincipal',
            'totalPaidInterest',
            'interestLogs'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'loan_account_no' => ['required', 'string', 'max:50'],
            'lender_name' => ['required', 'string', 'max:255'],
            'principal_amount' => ['required', 'numeric', 'min:1'],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'interest_period' => ['nullable', 'in:annual,monthly'],
            'tenure_months' => ['required', 'integer', 'min:1', 'max:600'],
            'start_date' => ['required', 'date'],
            'schedule_type' => ['required', 'in:reducing_balance,flat'],
            'ledger_account_id' => ['nullable', 'exists:accounts,id'],
            'interest_account_id' => ['nullable', 'exists:accounts,id'],
        ]);

        $loan = null;

        DB::transaction(function () use ($validated, &$loan, $request) {
            $systemId = Auth::user()->system_id ?? 1;

            if (empty($validated['ledger_account_id'])) {
                // Automatically create a ledger liability account for the loan
                $loanAcc = Account::create([
                    'system_id' => $systemId,
                    'name' => 'Loan Account - ' . $validated['lender_name'] . ' (' . $validated['loan_account_no'] . ')',
                    'code' => 'LOAN-' . strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $validated['loan_account_no'])),
                    'type' => 'liability',
                    'is_active' => true,
                ]);
                $validated['ledger_account_id'] = $loanAcc->id;
            }

            if (empty($validated['interest_account_id'])) {
                // Find or create a generic Interest Expense account
                $interestAcc = Account::firstOrCreate(
                    [
                        'system_id' => $systemId,
                        'name' => 'Bank Loan Interest Expense',
                        'type' => 'expense',
                    ],
                    [
                        'code' => 'EXP-LOAN-INT',
                        'is_active' => true,
                    ]
                );
                $validated['interest_account_id'] = $interestAcc->id;
            }

            $principal = (float)$validated['principal_amount'];
            $rate = (float)$validated['interest_rate'];
            $tenure = (int)$validated['tenure_months'];
            $scheduleType = $validated['schedule_type'];
            $startDate = \Carbon\Carbon::parse($validated['start_date']);
            
            $period = $request->input('interest_period', 'annual');
            if ($period === 'monthly') {
                $annualRate = $rate * 12;
                $r = $rate / 100;
            } else {
                $annualRate = $rate;
                $r = $rate / 12 / 100;
            }

            $validated['interest_rate'] = $annualRate;
            $validated['outstanding_balance'] = $principal;
            $validated['system_id'] = $systemId;
            $validated['status'] = 'Active';

            $loan = Loan::create($validated);

            $emi = 0.0;
            if ($scheduleType === 'reducing_balance') {
                if ($r > 0) {
                    $emi = $principal * ($r * pow(1 + $r, $tenure)) / (pow(1 + $r, $tenure) - 1);
                } else {
                    $emi = $principal / $tenure;
                }
            } else {
                $emi = ($principal / $tenure) + ($principal * $r);
            }

            // Generate Repayment Schedule
            $tempPrincipal = $principal;
            for ($i = 1; $i <= $tenure; $i++) {
                $dueDate = $startDate->copy()->addMonths($i);

                if ($scheduleType === 'reducing_balance') {
                    $interestComp = $tempPrincipal * $r;
                    $principalComp = $emi - $interestComp;

                    if ($i === $tenure) {
                        $principalComp = $tempPrincipal;
                        $emi = $principalComp + $interestComp;
                    }
                    $tempPrincipal -= $principalComp;
                } else {
                    // Flat Rate
                    $principalComp = $principal / $tenure;
                    $interestComp = $principal * $r;
                }

                EmiSchedule::create([
                    'system_id' => $loan->system_id,
                    'loan_id' => $loan->id,
                    'installment_no' => $i,
                    'due_date' => $dueDate,
                    'emi_amount' => $emi,
                    'principal_component' => $principalComp,
                    'interest_component' => $interestComp,
                    'amount_paid' => 0.00,
                    'status' => 'Due',
                ]);
            }
        });

        return response()->json(['success' => true, 'loan' => $loan]);
    }

    public function showSchedule(Loan $loan): View
    {
        // Reconcile historical overpayments (where amount_paid > emi_amount from earlier payments)
        DB::transaction(function () use ($loan) {
            $overpaidInstallments = EmiSchedule::where('loan_id', $loan->id)
                ->whereRaw('amount_paid > emi_amount + 0.01')
                ->orderBy('installment_no')
                ->get();

            foreach ($overpaidInstallments as $inst) {
                $emiDue = (float)$inst->emi_amount;
                $excess = (float)$inst->amount_paid - $emiDue;

                $inst->amount_paid = $emiDue;
                $inst->status      = 'Paid';
                $inst->save();

                if ($excess > 0.01) {
                    $nextInstallments = EmiSchedule::where('loan_id', $loan->id)
                        ->where('status', '!=', 'Paid')
                        ->where('installment_no', '>', $inst->installment_no)
                        ->orderBy('installment_no')
                        ->get();

                    foreach ($nextInstallments as $next) {
                        if ($excess <= 0.01) break;

                        $nextEmi      = (float)$next->emi_amount;
                        $nextPrevPaid = (float)$next->amount_paid;
                        $nextBalance  = $nextEmi - $nextPrevPaid;

                        if ($excess >= $nextBalance) {
                            $next->amount_paid = $nextEmi;
                            $next->paid_date   = $inst->paid_date ?? now();
                            $next->status      = 'Paid';
                            $loan->decrement('outstanding_balance', $next->principal_component);
                            $excess -= $nextBalance;
                        } else {
                            $next->amount_paid = $nextPrevPaid + $excess;
                            $next->paid_date   = $inst->paid_date ?? now();
                            $next->status      = 'Due';
                            $excess = 0;
                        }
                        $next->save();
                    }
                }
            }
        });

        $loan->load(['project', 'ledgerAccount', 'interestAccount', 'emiSchedules', 'prepayments']);
        $assetAccounts = \App\Models\Account::where('type', 'Asset')->where('is_active', true)->get();
        return view('loans.schedule', compact('loan', 'assetAccounts'));
    }

    public function payEmi(Request $request, Loan $loan, EmiSchedule $installment): JsonResponse
    {
        $validated = $request->validate([
            'amount'    => ['required', 'numeric', 'min:0.01'],
            'paid_date' => ['required', 'date'],
            'bank_account_id' => ['required', 'exists:accounts,id'],
            'other_charges'   => ['nullable', 'numeric', 'min:0'],
        ]);

        $amount   = (float)$validated['amount'];
        $paidDate = $validated['paid_date'];
        $bankAccountId = $validated['bank_account_id'];
        $otherCharges = (float)($validated['other_charges'] ?? 0);

        if ($installment->loan_id !== $loan->id) {
            return response()->json(['error' => 'Invalid installment for this loan.'], 400);
        }

        $emiDue = round((float)$installment->emi_amount - (float)$installment->amount_paid, 2);
        if (abs($amount - $emiDue) > 0.01) {
            return response()->json([
                'error' => 'Exact full EMI installment payment of ₹' . number_format($emiDue, 2) . ' is required. Paying more or less is not allowed.'
            ], 422);
        }

        DB::transaction(function () use ($loan, $installment, $paidDate, $bankAccountId, $otherCharges) {
            $systemId = Auth::user()->system_id ?? 1;

            $installment->amount_paid = (float)$installment->emi_amount;
            $installment->paid_date   = $paidDate;
            $installment->status      = 'Paid';

            if ($installment->getOriginal('status') !== 'Paid') {
                $loan->decrement('outstanding_balance', $installment->principal_component);
            }
            $installment->save();

            $loan->refresh();
            if ((float)$loan->outstanding_balance <= 0.01) {
                $loan->update(['status' => 'Closed']);
            }

            // Create Payment Voucher
            $voucherNumber = 'PAY-LOAN-' . $loan->id . '-' . time();
            $voucher = \App\Models\Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $voucherNumber,
                'type' => 'Payment',
                'date' => $paidDate,
                'narration' => 'Bank Loan EMI Payment - Inst #' . $installment->installment_no . ' (' . $loan->lender_name . ')',
                'status' => 'Posted',
                'created_by' => Auth::id() ?? 1,
            ]);

            $totalCredit = (float)$installment->emi_amount + $otherCharges;

            // Credit Bank Account
            $bankLine = \App\Models\VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $bankAccountId,
                'debit' => 0.00,
                'credit' => $totalCredit,
                'line_narration' => 'Paid Loan EMI - Inst #' . $installment->installment_no,
            ]);

            \App\Models\LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $bankAccountId,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $bankLine->id,
                'date' => $paidDate,
                'debit' => 0.00,
                'credit' => $totalCredit,
                'running_balance' => 0.00,
            ]);

            // Debit Loan Principal
            if ($loan->ledger_account_id) {
                $principalLine = \App\Models\VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $loan->ledger_account_id,
                    'debit' => (float)$installment->principal_component,
                    'credit' => 0.00,
                    'line_narration' => 'Loan Principal Repayment - Inst #' . $installment->installment_no,
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $loan->ledger_account_id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $principalLine->id,
                    'date' => $paidDate,
                    'debit' => (float)$installment->principal_component,
                    'credit' => 0.00,
                    'running_balance' => 0.00,
                ]);
            }

            // Debit Interest Expense (combine regular interest + other charges if any)
            $totalDebitInterest = $totalCredit - (float)$installment->principal_component;
            if ($loan->interest_account_id && $totalDebitInterest > 0) {
                $interestLine = \App\Models\VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $loan->interest_account_id,
                    'debit' => $totalDebitInterest,
                    'credit' => 0.00,
                    'line_narration' => 'Loan Interest Expense / Other Charges - Inst #' . $installment->installment_no,
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $loan->interest_account_id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $interestLine->id,
                    'date' => $paidDate,
                    'debit' => $totalDebitInterest,
                    'credit' => 0.00,
                    'running_balance' => 0.00,
                ]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function prepay(Request $request, Loan $loan): JsonResponse
    {
        $validated = $request->validate([
            'action_type' => ['required', 'in:prepayment,foreclosure'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'prepayment_charges' => ['nullable', 'numeric', 'min:0'],
            'interest_adjustment' => ['nullable', 'numeric'],
            'bank_account_id' => ['required', 'exists:accounts,id'],
            'prepayment_date' => ['required', 'date'],
            'reschedule_option' => ['nullable', 'required_if:action_type,prepayment', 'in:reduce_emi,reduce_tenure'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ]);

        $actionType = $validated['action_type'];
        $amount = (float)$validated['amount'];
        $charges = (float)($validated['prepayment_charges'] ?? 0);
        $interestAdjustment = (float)($validated['interest_adjustment'] ?? 0);
        $bankAccountId = $validated['bank_account_id'];
        $prepaymentDate = $validated['prepayment_date'];
        $rescheduleOption = $validated['reschedule_option'] ?? 'reduce_emi';

        if ($actionType === 'foreclosure') {
            $amount = (float)$loan->outstanding_balance;
        }

        if ($amount > (float)$loan->outstanding_balance + 0.01) {
            return response()->json(['error' => 'Payoff amount exceeds outstanding balance.'], 422);
        }

        $hasOverdue = $loan->emiSchedules()
            ->where('status', '!=', 'Paid')
            ->where('due_date', '<', now()->startOfDay())
            ->exists();

        if ($hasOverdue && $actionType === 'prepayment') {
            return response()->json(['error' => 'Prepayment cannot be processed while there are overdue installments. Please clear all overdue payments first.'], 422);
        }

        DB::transaction(function () use ($loan, $amount, $charges, $interestAdjustment, $bankAccountId, $prepaymentDate, $rescheduleOption, $actionType) {
            $systemId = Auth::user()->system_id ?? 1;
            $prevOutstanding = (float)$loan->outstanding_balance;
            
            $loan->decrement('outstanding_balance', $amount);
            $newOutstanding = (float)$loan->outstanding_balance;

            // Log the Prepayment/Reschedule/Foreclosure
            LoanPrepayment::create([
                'loan_id' => $loan->id,
                'prepayment_amount' => $amount,
                'prepayment_date' => $prepaymentDate,
                'reschedule_option' => $actionType === 'foreclosure' ? 'foreclosure' : $rescheduleOption,
                'previous_outstanding' => $prevOutstanding,
                'new_outstanding' => $newOutstanding,
            ]);

            // Create Payment Voucher
            $voucherNumber = 'PAY-LOAN-PAYOFF-' . $loan->id . '-' . time();
            $voucherType = $actionType === 'foreclosure' ? 'Foreclosure' : 'Prepayment';
            $voucher = \App\Models\Voucher::create([
                'system_id' => $systemId,
                'voucher_number' => $voucherNumber,
                'type' => 'Payment',
                'date' => $prepaymentDate,
                'narration' => 'Bank Loan ' . $voucherType . ' - ' . $loan->lender_name . ' (' . $loan->loan_account_no . ')',
                'status' => 'Posted',
                'created_by' => Auth::id() ?? 1,
            ]);

            $totalCredit = $amount + $charges + $interestAdjustment;

            // Credit Bank Account
            $bankLine = \App\Models\VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $bankAccountId,
                'debit' => 0.00,
                'credit' => $totalCredit,
                'line_narration' => $voucherType . ' Payment Release',
            ]);

            \App\Models\LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $bankAccountId,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $bankLine->id,
                'date' => $prepaymentDate,
                'debit' => 0.00,
                'credit' => $totalCredit,
                'running_balance' => 0.00,
            ]);

            // Debit Loan Principal Account
            if ($loan->ledger_account_id) {
                $principalLine = \App\Models\VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $loan->ledger_account_id,
                    'debit' => $amount,
                    'credit' => 0.00,
                    'line_narration' => $voucherType . ' Principal Component',
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $loan->ledger_account_id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $principalLine->id,
                    'date' => $prepaymentDate,
                    'debit' => $amount,
                    'credit' => 0.00,
                    'running_balance' => 0.00,
                ]);
            }

            // Debit Interest/Charges Account
            $extraExpense = $charges + $interestAdjustment;
            if ($loan->interest_account_id && abs($extraExpense) > 0.01) {
                $debitVal = $extraExpense > 0 ? $extraExpense : 0;
                $creditVal = $extraExpense < 0 ? abs($extraExpense) : 0;

                $interestLine = \App\Models\VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $loan->interest_account_id,
                    'debit' => $debitVal,
                    'credit' => $creditVal,
                    'line_narration' => $voucherType . ' Charges/Adjustments',
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $loan->interest_account_id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $interestLine->id,
                    'date' => $prepaymentDate,
                    'debit' => $debitVal,
                    'credit' => $creditVal,
                    'running_balance' => 0.00,
                ]);
            }

            if ($actionType === 'foreclosure') {
                // Mark all unpaid installments as Paid (since they are foreclosed)
                $loan->emiSchedules()->where('status', '!=', 'Paid')->update(['status' => 'Paid', 'amount_paid' => DB::raw('emi_amount')]);
                $loan->update(['status' => 'Closed', 'outstanding_balance' => 0]);
                return;
            }

            $unpaidInstallments = $loan->emiSchedules()->where('status', '!=', 'Paid')->get();

            if ($unpaidInstallments->isEmpty()) {
                if ($newOutstanding <= 0.01) {
                    $loan->update(['status' => 'Closed']);
                }
                return;
            }

            $remainingPrincipal = $newOutstanding;
            $rate = (float)$loan->interest_rate;
            $r = $rate / 12 / 100;
            $k = $unpaidInstallments->count();
            $isReducing = $loan->schedule_type === 'reducing_balance';

            if ($rescheduleOption === 'reduce_emi') {
                if ($isReducing) {
                    if ($r > 0) {
                        $newEmi = $remainingPrincipal * ($r * pow(1 + $r, $k)) / (pow(1 + $r, $k) - 1);
                    } else {
                        $newEmi = $remainingPrincipal / $k;
                    }

                    $tempPrincipal = $remainingPrincipal;
                    foreach ($unpaidInstallments as $idx => $inst) {
                        $interestComp = $tempPrincipal * $r;
                        $principalComp = $newEmi - $interestComp;

                        if ($idx === $k - 1) {
                            $principalComp = $tempPrincipal;
                            $newEmi = $principalComp + $interestComp;
                        }

                        $inst->update([
                            'emi_amount' => $newEmi,
                            'principal_component' => $principalComp,
                            'interest_component' => $interestComp,
                        ]);

                        $tempPrincipal -= $principalComp;
                    }
                } else {
                    $newPrincipalComp = $remainingPrincipal / $k;
                    $newInterestComp = $remainingPrincipal * $r;
                    $newEmi = $newPrincipalComp + $newInterestComp;

                    foreach ($unpaidInstallments as $inst) {
                        $inst->update([
                            'emi_amount' => $newEmi,
                            'principal_component' => $newPrincipalComp,
                            'interest_component' => $newInterestComp,
                        ]);
                    }
                }
            } else {
                // Reduce Tenure
                $tempPrincipal = $remainingPrincipal;
                foreach ($unpaidInstallments as $inst) {
                    if ($tempPrincipal <= 0) {
                        $inst->delete();
                        continue;
                    }

                    if ($isReducing) {
                        $interestComp = $tempPrincipal * $r;
                        $constantEmi = (float)$inst->emi_amount;
                        $principalComp = $constantEmi - $interestComp;

                        if ($tempPrincipal <= $principalComp) {
                            $principalComp = $tempPrincipal;
                            $emi = $principalComp + $interestComp;
                            $tempPrincipal = 0;
                        } else {
                            $emi = $constantEmi;
                            $tempPrincipal -= $principalComp;
                        }
                    } else {
                        $principalComp = (float)$inst->principal_component;
                        $interestComp = (float)$inst->interest_component;

                        if ($tempPrincipal <= $principalComp) {
                            $principalComp = $tempPrincipal;
                            $emi = $principalComp + $interestComp;
                            $tempPrincipal = 0;
                        } else {
                            $emi = (float)$inst->emi_amount;
                            $tempPrincipal -= $principalComp;
                        }
                    }

                    $inst->update([
                        'emi_amount' => $emi,
                        'principal_component' => $principalComp,
                        'interest_component' => $interestComp,
                    ]);
                }
            }
        });

        return response()->json(['success' => true]);
    }

    public function reports(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $selectedProjectId = $request->project_id;

        $loansQuery = Loan::with('project');
        if ($selectedProjectId) {
            $loansQuery->where('project_id', $selectedProjectId);
        }
        $loans = $loansQuery->get();

        // Metrics calculations
        $totalOutstanding = 0.0;
        $totalPaidPrincipal = 0.0;
        $totalInterestPaid = 0.0;
        $totalLoansAmount = 0.0;

        foreach ($loans as $loan) {
            $totalLoansAmount += (float)$loan->principal_amount;
            $totalOutstanding += (float)$loan->outstanding_balance;
            
            $paidSchedules = EmiSchedule::where('loan_id', $loan->id)->where('status', 'Paid')->get();
            $totalPaidPrincipal += $paidSchedules->sum('principal_component');
            $totalInterestPaid += $paidSchedules->sum('interest_component');
        }

        // EMIs due today and this month
        $today = \Carbon\Carbon::today()->toDateString();
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();

        $emiDueQuery = EmiSchedule::with('loan.project')->where('status', '!=', 'Paid');
        if ($selectedProjectId) {
            $emiDueQuery->whereHas('loan', function ($q) use ($selectedProjectId) {
                $q->where('project_id', $selectedProjectId);
            });
        }

        $emiDueToday = (clone $emiDueQuery)->where('due_date', $today)->get();
        $emiDueThisMonth = (clone $emiDueQuery)->whereBetween('due_date', [$startOfMonth, $endOfMonth])->get();

        return view('loans.reports', compact(
            'projects',
            'selectedProjectId',
            'loans',
            'totalLoansAmount',
            'totalOutstanding',
            'totalPaidPrincipal',
            'totalInterestPaid',
            'emiDueToday',
            'emiDueThisMonth'
        ));
    }

    public function updateInterest(Request $request, Loan $loan): JsonResponse
    {
        $validated = $request->validate([
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'interest_period' => ['nullable', 'in:annual,monthly'],
        ]);

        $rate = (float)$validated['interest_rate'];
        $period = $request->input('interest_period', 'annual');
        
        if ($period === 'monthly') {
            $annualRate = $rate * 12;
            $r = $rate / 100;
        } else {
            $annualRate = $rate;
            $r = $rate / 12 / 100;
        }

        DB::transaction(function () use ($loan, $annualRate, $r, $period) {
            $oldRate = (float)$loan->interest_rate;

            LoanInterestLog::create([
                'loan_id' => $loan->id,
                'old_interest_rate' => $oldRate,
                'new_interest_rate' => $annualRate,
                'interest_period' => $period,
                'reason' => 'Interest rate updated via Bank Loan Repayment module',
            ]);

            $loan->update(['interest_rate' => $annualRate]);

            $unpaidInstallments = $loan->emiSchedules()->where('status', '!=', 'Paid')->get();
            if ($unpaidInstallments->isEmpty()) {
                return;
            }

            $remainingPrincipal = (float)$loan->outstanding_balance;
            $k = $unpaidInstallments->count();
            $isReducing = $loan->schedule_type === 'reducing_balance';

            if ($isReducing) {
                if ($r > 0) {
                    $newEmi = $remainingPrincipal * ($r * pow(1 + $r, $k)) / (pow(1 + $r, $k) - 1);
                } else {
                    $newEmi = $remainingPrincipal / $k;
                }

                $tempPrincipal = $remainingPrincipal;
                foreach ($unpaidInstallments as $idx => $inst) {
                    $interestComp = $tempPrincipal * $r;
                    $principalComp = $newEmi - $interestComp;

                    if ($tempPrincipal <= $principalComp || $idx === $k - 1) {
                        $principalComp = $tempPrincipal;
                        $emi = $principalComp + $interestComp;
                        $tempPrincipal = 0;
                    } else {
                        $emi = $newEmi;
                        $tempPrincipal -= $principalComp;
                    }

                    $inst->update([
                        'emi_amount' => $emi,
                        'principal_component' => $principalComp,
                        'interest_component' => $interestComp,
                    ]);
                }
            } else {
                // Flat rate
                $principalComp = $remainingPrincipal / $k;
                $interestComp = $remainingPrincipal * $r;
                $emi = $principalComp + $interestComp;

                foreach ($unpaidInstallments as $inst) {
                    $inst->update([
                        'emi_amount' => $emi,
                        'principal_component' => $principalComp,
                        'interest_component' => $interestComp,
                    ]);
                }
            }
        });

        return response()->json(['success' => true]);
    }
}
