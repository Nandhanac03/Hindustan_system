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
        
        // All pending EMIs across active loans due on/before end of current month
        $pendingEmis = EmiSchedule::whereHas('loan', function ($q) {
                $q->where('status', 'Active');
            })
            ->where('status', '!=', 'Paid')
            ->where('due_date', '<=', now()->endOfMonth())
            ->get();
        $pendingEmisCount = $pendingEmis->count();
        $pendingEmisAmount = $pendingEmis->sum(function ($inst) {
            return max(0, (float)$inst->emi_amount - (float)$inst->amount_paid);
        });

        // Global stats for KPI metrics cards
        $activeLoansCount = Loan::where('status', 'Active')->count();
        $totalOutstanding = Loan::sum('outstanding_balance');
        $allPaidSchedules = EmiSchedule::where('status', 'Paid')->get();
        $totalPaidPrincipal = $allPaidSchedules->sum('principal_component');
        $totalPaidInterest = $allPaidSchedules->sum('interest_component');
        
        $accounts = Account::orderBy('name')->get();
        $banks = \App\Models\Bank::where('status', 'active')->orderBy('bank_name')->get();
        $interestLogs = LoanInterestLog::with('loan')->latest()->get();

        return view('loans.index', compact('loans', 'projects', 'accounts', 'banks', 'pendingEmisCount', 'pendingEmisAmount', 'activeLoansCount', 'totalOutstanding', 'totalPaidPrincipal', 'totalPaidInterest', 'interestLogs'));
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
        ]);

        $amount   = (float)$validated['amount'];
        $paidDate = $validated['paid_date'];
        $bankAccountId = $validated['bank_account_id'];

        if ($installment->loan_id !== $loan->id) {
            return response()->json(['error' => 'Invalid installment for this loan.'], 400);
        }

        $emiDue = round((float)$installment->emi_amount - (float)$installment->amount_paid, 2);
        if (abs($amount - $emiDue) > 0.01) {
            return response()->json([
                'error' => 'Bank regulations require exact full EMI installment payment of ₹' . number_format($emiDue, 2) . '. Paying more or less amount is not allowed.'
            ], 422);
        }

        DB::transaction(function () use ($loan, $installment, $paidDate, $bankAccountId) {
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
                'narration' => 'Bank Loan EMI Payment - Inst #' . $installment->installment_no,
                'status' => 'Posted',
                'created_by' => Auth::id() ?? 1,
            ]);

            // Credit Bank Account
            $bankLine = \App\Models\VoucherLine::create([
                'voucher_id' => $voucher->id,
                'account_id' => $bankAccountId,
                'debit' => 0.00,
                'credit' => (float)$installment->emi_amount,
                'line_narration' => 'Paid Loan EMI',
            ]);

            \App\Models\LedgerEntry::create([
                'system_id' => $systemId,
                'account_id' => $bankAccountId,
                'voucher_id' => $voucher->id,
                'voucher_line_id' => $bankLine->id,
                'date' => $paidDate,
                'debit' => 0.00,
                'credit' => (float)$installment->emi_amount,
                'running_balance' => 0.00,
            ]);

            // Debit Loan Principal
            if ($loan->ledger_account_id) {
                $principalLine = \App\Models\VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $loan->ledger_account_id,
                    'debit' => (float)$installment->principal_component,
                    'credit' => 0.00,
                    'line_narration' => 'Loan Principal Repayment',
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

            // Debit Interest Expense
            if ($loan->interest_account_id && (float)$installment->interest_component > 0) {
                $interestLine = \App\Models\VoucherLine::create([
                    'voucher_id' => $voucher->id,
                    'account_id' => $loan->interest_account_id,
                    'debit' => (float)$installment->interest_component,
                    'credit' => 0.00,
                    'line_narration' => 'Loan Interest Expense',
                ]);

                \App\Models\LedgerEntry::create([
                    'system_id' => $systemId,
                    'account_id' => $loan->interest_account_id,
                    'voucher_id' => $voucher->id,
                    'voucher_line_id' => $interestLine->id,
                    'date' => $paidDate,
                    'debit' => (float)$installment->interest_component,
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
            'amount' => ['required', 'numeric', 'min:1'],
            'prepayment_date' => ['required', 'date'],
            'reschedule_option' => ['required', 'in:reduce_emi,reduce_tenure'],
        ]);

        $amount = (float)$validated['amount'];
        $rescheduleOption = $validated['reschedule_option'];

        if ($amount > $loan->outstanding_balance) {
            return response()->json(['error' => 'Prepayment amount exceeds outstanding balance.'], 422);
        }

        // In standard financial logic, a borrower cannot make a prepayment towards the future principal 
        // if they have past overdue installments. They must clear arrears first.
        $hasOverdue = $loan->emiSchedules()
            ->where('status', '!=', 'Paid')
            ->where('due_date', '<', now()->startOfDay())
            ->exists();

        if ($hasOverdue) {
            return response()->json(['error' => 'Prepayment cannot be processed while there are overdue installments. Please clear all overdue payments first.'], 422);
        }

        DB::transaction(function () use ($loan, $amount, $rescheduleOption, $validated) {
            $prevOutstanding = (float)$loan->outstanding_balance;
            $loan->decrement('outstanding_balance', $amount);
            $newOutstanding = (float)$loan->outstanding_balance;

            // Log the Prepayment/Reschedule
            LoanPrepayment::create([
                'loan_id' => $loan->id,
                'prepayment_amount' => $amount,
                'prepayment_date' => $validated['prepayment_date'],
                'reschedule_option' => $rescheduleOption,
                'previous_outstanding' => $prevOutstanding,
                'new_outstanding' => $newOutstanding,
            ]);

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
                    // Flat Rate
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
                        // Flat rate
                        $principalComp = (float)$inst->principal_component;
                        $interestComp = (float)$inst->interest_component;
                        $emi = $principalComp + $interestComp;

                        if ($tempPrincipal <= $principalComp) {
                            $principalComp = $tempPrincipal;
                            $emi = $principalComp + $interestComp;
                            $tempPrincipal = 0;
                        } else {
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

            if ($newOutstanding <= 0.01) {
                $loan->update(['status' => 'Closed']);
            }
        });

        return response()->json(['success' => true]);
    }

    public function reports(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        
        if (!$request->has('project_id')) {
            $selectedProjectId = $projects->first()->id ?? null;
        } else {
            $selectedProjectId = $request->project_id;
        }

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
