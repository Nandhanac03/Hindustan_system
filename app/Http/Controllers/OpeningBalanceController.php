<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\AccountingSetting;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OpeningBalanceController extends Controller
{
    /**
     * Display the Opening Balance Master Screen
     */
    public function index(Request $request): View
    {
        $accounts = ChartOfAccount::with('project')->orderBy('account_code')->get();

        // Account 3090: Opening Balance Equity
        $equityAccount = ChartOfAccount::where('account_code', '3090')->first();
        if (!$equityAccount) {
            // $equityAccount = ChartOfAccount::create([
            //     'account_code' => '3090',
            //     'account_name' => 'Opening Balance Equity',
            //     'account_type' => 'LIABILITY',
            //     'opening_balance' => 0.00,
            //     'opening_balance_type' => 'CR',
            //     'is_active' => true,
            // ]);
            $accounts = ChartOfAccount::orderBy('account_code')->get();
        }

        // Sum excluding Account 3090 (which acts as the variance absorbing buffer)
        $operationalAccounts = $accounts->reject(fn($a) => $a->account_code === '3090');

        $totalDebits = (float) $operationalAccounts
            ->where('opening_balance_type', 'DR')
            ->sum('opening_balance');

        $totalCredits = (float) $operationalAccounts
            ->where('opening_balance_type', 'CR')
            ->sum('opening_balance');

        $variance = abs($totalDebits - $totalCredits);
        $varianceSide = $totalDebits > $totalCredits ? 'CR' : ($totalCredits > $totalDebits ? 'DR' : 'BALANCED');

        $configuredCount = $operationalAccounts->where('opening_balance', '>', 0)->count();
        $totalAccountsCount = $operationalAccounts->count();

        $isLocked = AccountingSetting::isOpeningBalanceLocked();
        $lockDetails = AccountingSetting::getLockDetails();

        // Summary counts by account type
        $assetCount = $operationalAccounts->where('account_type', 'ASSET')->count();
        $liabilityCount = $operationalAccounts->where('account_type', 'LIABILITY')->count();
        $revenueCount = $operationalAccounts->where('account_type', 'REVENUE')->count();
        $expenseCount = $operationalAccounts->where('account_type', 'EXPENSE')->count();

        // Projects for optional assignment
        $projects = Project::where('is_active', true)->orderBy('name')->get();
        if ($projects->isEmpty()) {
            $projects = Project::orderBy('name')->get();
        }

        $currency = 'AED';
        $financialYear = '2025 - 2026';
        $financialYearDates = '01/Apr/2025 - 31/Mar/2026';
        $openingBalanceDate = '2025-04-01';
        $defaultDescription = 'Opening balances as per audited financial statements.';
        $accounts = $operationalAccounts;

        // Fetch company bank accounts for the sub-account breakdown under 1001
        $companyBankAccounts = \App\Models\CompanyBankAccount::orderBy('bank_name')->get();

        return view('opening-balances.index', compact(
            'accounts',
            'companyBankAccounts',
            'equityAccount',
            
            'totalDebits',
            'totalCredits',
            'variance',
            'varianceSide',
            'configuredCount',
            'totalAccountsCount',
            'isLocked',
            'lockDetails',
            'assetCount',
            'liabilityCount',
            'revenueCount',
            'expenseCount',
            'projects',
            'currency',
            'financialYear',
            'financialYearDates',
            'openingBalanceDate',
            'defaultDescription'
        ));
    }

    /**
     * Batch Save Opening Balances (Save as Draft or Post Opening Balances)
     */
    public function save(Request $request): RedirectResponse
    {
        if (AccountingSetting::isOpeningBalanceLocked() && $request->input('action') !== 'unlock') {
            return redirect()->route('opening-balances.index')->with('error', '⚠️ Opening balances are locked. Please use a Prior Period Adjustment Journal Voucher (JV) to make corrections or request an Admin Unlock.');
        }

        $balances = $request->input('balances', []);
        if (!is_array($balances)) {
            return redirect()->route('opening-balances.index')->with('error', 'Invalid balance data submitted.');
        }

        $bankBalances = $request->input('bank_balances', []);

        DB::transaction(function () use ($balances, $bankBalances) {
            // 1. Update individual Company Bank Accounts if submitted
            if (is_array($bankBalances) && !empty($bankBalances)) {
                $totalBankOpening = 0.00;
                foreach ($bankBalances as $bankId => $bankData) {
                    $bankAcc = \App\Models\CompanyBankAccount::find($bankId);
                    if ($bankAcc) {
                        $bankAmt = max(0.00, (float)($bankData['amount'] ?? 0));
                        $diff = $bankAmt - (float)$bankAcc->opening_balance;
                        $newCurrent = max(0.00, (float)$bankAcc->current_balance + $diff);

                        $bankAcc->update([
                            'opening_balance' => $bankAmt,
                            'current_balance' => $newCurrent,
                        ]);

                        $totalBankOpening += $bankAmt;
                    }
                }

                // If account 1001 is present, keep it in sync with the sum of bank accounts
                $acc1001 = ChartOfAccount::where('account_code', '1001')->first();
                if ($acc1001 && isset($balances[$acc1001->id])) {
                    $balances[$acc1001->id]['amount'] = $totalBankOpening;
                }
            }

            foreach ($balances as $id => $data) {
                $account = ChartOfAccount::find($id);
                if (!$account || $account->account_code === '3090') {
                    continue;
                }

                $amount = max(0.00, (float) ($data['amount'] ?? 0));
                $rawType = strtoupper(trim((string)($data['type'] ?? 'DR')));
                $type = in_array($rawType, ['DR', 'CR', 'DEBIT', 'CREDIT'])
                    ? (in_array($rawType, ['DR', 'DEBIT']) ? 'DR' : 'CR')
                    : ($account->account_type === 'ASSET' || $account->account_type === 'EXPENSE' ? 'DR' : 'CR');

                $projectId = !empty($data['project_id']) && $data['project_id'] !== '-' && $data['project_id'] !== 'all' ? (int)$data['project_id'] : null;
                $remarks = isset($data['remarks']) ? trim((string)$data['remarks']) : null;

                $account->update([
                    'opening_balance' => $amount,
                    'opening_balance_type' => $type,
                    'project_id' => $projectId,
                    'remarks' => $remarks,
                ]);
            }

            // Auto-reconcile Account 3090 - Opening Balance Equity
            $this->reconcileOpeningBalanceEquity();
        });

        if ($request->input('action') === 'post') {
            AccountingSetting::lockOpeningBalances(Auth::id());
            return redirect()->route('opening-balances.index')->with('success', '✅ Opening balances have been posted & locked successfully.');
        }

        return redirect()->route('opening-balances.index')->with('success', '✅ Opening balances saved successfully as draft.');
    }

    /**
     * Freeze and Lock Opening Balances
     */
    public function lock(Request $request): RedirectResponse
    {
        DB::transaction(function () {
            $this->reconcileOpeningBalanceEquity();
            AccountingSetting::lockOpeningBalances(Auth::id());
        });

        return redirect()->route('opening-balances.index')->with('success', '🔒 Opening balances have been frozen & locked successfully. Direct edits are now disabled to preserve financial report integrity.');
    }

    /**
     * Save / Update a Single Account Head with Opening Balance from Form/Modal
     */
    public function saveAccount(Request $request): RedirectResponse
    {
        $id = $request->input('account_id');
        $account = $id ? ChartOfAccount::find($id) : null;

        $validated = $request->validate([
            'account_code' => 'required|string|max:20|unique:chart_of_accounts,account_code,' . ($account ? $account->id : 'NULL'),
            'account_name' => 'required|string|max:100',
            'account_type' => 'required|in:ASSET,LIABILITY,REVENUE,EXPENSE',
            'opening_balance' => 'nullable|numeric|min:0',
            'opening_balance_type' => 'nullable|string',
            'project_id' => 'nullable',
            'remarks' => 'nullable|string|max:255',
        ]);

        $rawType = strtoupper(trim((string)($validated['opening_balance_type'] ?? 'DR')));
        $type = (str_contains($rawType, 'CR') || str_contains($rawType, 'CREDIT')) ? 'CR' : 'DR';

        $projectId = (!empty($validated['project_id']) && $validated['project_id'] !== '-' && $validated['project_id'] !== 'all')
            ? (int)$validated['project_id']
            : null;

        $isLocked = AccountingSetting::isOpeningBalanceLocked();
        $openingBalance = $isLocked ? ($account ? (float)$account->opening_balance : 0.00) : max(0, (float)($validated['opening_balance'] ?? 0));
        $openingBalanceType = $isLocked ? ($account ? $account->opening_balance_type : $type) : $type;

        $data = [
            'account_code' => $validated['account_code'],
            'account_name' => $validated['account_name'],
            'account_type' => $validated['account_type'],
            'opening_balance' => $openingBalance,
            'opening_balance_type' => $openingBalanceType,
            'project_id' => $projectId,
            'remarks' => $validated['remarks'] ?? null,
            'is_active' => true,
        ];

        if ($account) {
            $account->update($data);
            $msg = "Account '{$account->account_name}' updated successfully.";
        } else {
            ChartOfAccount::create($data);
            $msg = "Account '{$validated['account_name']}' created successfully.";
        }

        $this->reconcileOpeningBalanceEquity();

        return redirect()->route('opening-balances.index')->with('success', '✅ ' . $msg);
    }

    /**
     * Delete an Account Head
     */
    public function deleteAccount(ChartOfAccount $chartOfAccount): RedirectResponse
    {
        if ($chartOfAccount->account_code === '3090') {
            return redirect()->route('opening-balances.index')->with('error', 'Account 3090 cannot be deleted.');
        }

        $name = $chartOfAccount->account_name;
        $chartOfAccount->delete();
        $this->reconcileOpeningBalanceEquity();

        return redirect()->route('opening-balances.index')->with('success', "✅ Account '{$name}' deleted successfully.");
    }

    /**
     * Administrative Unlock Override
     */
    public function unlock(Request $request): RedirectResponse
    {
        AccountingSetting::unlockOpeningBalances();

        return redirect()->route('opening-balances.index')->with('status', '⚠️ Opening balances have been unlocked by administrator. Remember to freeze & lock once adjustments are complete.');
    }

    /**
     * Download CSV Template for Opening Balances
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="opening_balances_template.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Account Code', 'Account Name', 'Account Type', 'Opening Balance (AED)', 'Balance Type (Debit/Credit)', 'Remarks']);

            $accounts = ChartOfAccount::where('account_code', '!=', '3090')->orderBy('account_code')->get();
            foreach ($accounts as $acc) {
                fputcsv($handle, [
                    $acc->account_code,
                    $acc->account_name,
                    ucfirst(strtolower($acc->account_type)),
                    $acc->opening_balance > 0 ? $acc->opening_balance : '0.00',
                    $acc->opening_balance_type === 'CR' ? 'Credit' : 'Debit',
                    $acc->remarks ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Import Opening Balances from CSV
     */
    public function import(Request $request): RedirectResponse
    {
        if (AccountingSetting::isOpeningBalanceLocked()) {
            return redirect()->route('opening-balances.index')->with('error', '⚠️ Opening balances are locked. Please unlock first.');
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle);
            $importedCount = 0;

            DB::transaction(function () use ($handle, &$importedCount) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($data) < 4) continue;
                    $code = trim($data[0]);
                    if ($code === '3090' || empty($code)) continue;

                    $amount = isset($data[3]) ? (float) preg_replace('/[^0-9.]/', '', $data[3]) : 0.0;
                    $typeStr = isset($data[4]) ? strtoupper(trim($data[4])) : 'DEBIT';
                    $type = (str_contains($typeStr, 'CR') || str_contains($typeStr, 'CREDIT')) ? 'CR' : 'DR';
                    $remarks = isset($data[5]) ? trim($data[5]) : null;

                    $account = ChartOfAccount::where('account_code', $code)->first();
                    if ($account) {
                        $account->update([
                            'opening_balance' => $amount,
                            'opening_balance_type' => $type,
                            'remarks' => $remarks ?: $account->remarks,
                        ]);
                        $importedCount++;
                    }
                }
                $this->reconcileOpeningBalanceEquity();
            });

            fclose($handle);
            return redirect()->route('opening-balances.index')->with('success', "✅ Successfully imported balances for {$importedCount} accounts.");
        }

        return redirect()->route('opening-balances.index')->with('error', 'Failed to read the uploaded CSV file.');
    }

    /**
     * Reconcile Account 3090 - Opening Balance Equity
     */
    protected function reconcileOpeningBalanceEquity(): void
    {
        $operationalAccounts = ChartOfAccount::where('account_code', '!=', '3090')->get();

        $drSum = (float) $operationalAccounts->where('opening_balance_type', 'DR')->sum('opening_balance');
        $crSum = (float) $operationalAccounts->where('opening_balance_type', 'CR')->sum('opening_balance');

        $difference = $drSum - $crSum;

        $equity = ChartOfAccount::firstOrCreate(
            ['account_code' => '3090'],
            [
                'account_name' => 'Opening Balance Equity',
                'account_type' => 'LIABILITY',
                'is_active' => true,
            ]
        );

        if ($difference > 0) {
            // Debits exceed Credits: 3090 absorbs the credit variance
            $equity->update([
                'opening_balance' => round(abs($difference), 2),
                'opening_balance_type' => 'CR',
            ]);
        } elseif ($difference < 0) {
            // Credits exceed Debits: 3090 absorbs the debit variance
            $equity->update([
                'opening_balance' => round(abs($difference), 2),
                'opening_balance_type' => 'DR',
            ]);
        } else {
            // Exactly balanced!
            $equity->update([
                'opening_balance' => 0.00,
                'opening_balance_type' => 'CR',
            ]);
        }
    }
}
