<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payee;
use App\Models\Project;
use App\Models\RaBill;
use App\Models\SiteExpense;
use App\Models\System;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorAgingReportService
{
    /**
     * Get Real Vendor & Sundry Creditor Aging Report data from Database
     */
    public function getReportData(array $filters = []): array
    {
        $systemId = Auth::user()->system_id ?? 1;
        $system = System::find($systemId);
        $currencyCode = $system?->currency_code ?: 'INR';
        $currency = $currencyCode === 'INR' ? '₹' : $currencyCode;

        // 1. Projects Lookup from DB
        $allProjects = Project::where('is_active', true)->orderBy('name')->pluck('name')->toArray();
        if (empty($allProjects)) {
            $allProjects = Project::orderBy('name')->pluck('name')->toArray();
        }
        if (empty($allProjects)) {
            $allProjects = ['Tabasco Hindustan Infra Developers Pvt. Ltd'];
        }
        $defaultProjectName = $allProjects[0];

        // 2. As On Date
        $asOnDateInput = $filters['as_on_date'] ?? Carbon::now()->format('d/m/Y');
        try {
            $asOnDate = Carbon::createFromFormat('d/m/Y', $asOnDateInput)->endOfDay();
        } catch (\Throwable $e) {
            try {
                $asOnDate = Carbon::parse($asOnDateInput)->endOfDay();
            } catch (\Throwable $e2) {
                $asOnDate = Carbon::now()->endOfDay();
            }
        }

        // 3. Build Real Dataset from Database
        $allVendors = $this->buildRealDataset($asOnDate, $defaultProjectName, $systemId);

        // 4. Filter by Project (if specified)
        $selectedProject = $filters['project'] ?? 'All Projects';
        $rows = $allVendors;
        if ($selectedProject && $selectedProject !== 'All Projects' && $selectedProject !== 'All') {
            $rows = array_values(array_filter($rows, function ($v) use ($selectedProject) {
                return stripos($v['project'], $selectedProject) !== false;
            }));
        }

        // 5. Filter by Vendor (if specified)
        $selectedVendor = $filters['vendor'] ?? 'All Vendors';
        if ($selectedVendor && $selectedVendor !== 'All Vendors' && $selectedVendor !== 'All') {
            $rows = array_values(array_filter($rows, function ($v) use ($selectedVendor) {
                return stripos($v['name'], $selectedVendor) !== false || stripos($v['company_id'], $selectedVendor) !== false;
            }));
        }

        // 6. Filter by Search Query (if passed in request)
        $search = $filters['search'] ?? '';
        if ($search) {
            $searchLower = strtolower(trim($search));
            $rows = array_values(array_filter($rows, function ($v) use ($searchLower) {
                return str_contains(strtolower($v['name']), $searchLower) ||
                       str_contains(strtolower($v['company_id']), $searchLower) ||
                       str_contains(strtolower($v['last_payment_ref']), $searchLower) ||
                       str_contains(strtolower($v['project']), $searchLower);
            }));
        }

        // 7. Dynamic Financial KPIs based on actual database records
        $totalVendors = count($rows);
        $totalOutstanding = (float) array_sum(array_column($rows, 'total_outstanding'));
        $b0_30 = (float) array_sum(array_column($rows, 'bucket_0_30'));
        $b31_60 = (float) array_sum(array_column($rows, 'bucket_31_60'));
        $b61_90 = (float) array_sum(array_column($rows, 'bucket_61_90'));
        $b90_plus = (float) array_sum(array_column($rows, 'bucket_90_plus'));

        $pct0_30 = $totalOutstanding > 0 ? round(($b0_30 / $totalOutstanding) * 100, 1) : 0.0;
        $pct31_60 = $totalOutstanding > 0 ? round(($b31_60 / $totalOutstanding) * 100, 1) : 0.0;
        $pct61_90 = $totalOutstanding > 0 ? round(($b61_90 / $totalOutstanding) * 100, 1) : 0.0;
        $pct90_plus = $totalOutstanding > 0 ? round(($b90_plus / $totalOutstanding) * 100, 1) : 0.0;

        $kpis = [
            'total_vendors'     => $totalVendors,
            'total_outstanding' => $totalOutstanding,
            'b0_30'             => $b0_30,
            'b0_30_pct'         => $pct0_30,
            'b31_60'            => $b31_60,
            'b31_60_pct'        => $pct31_60,
            'b61_90'            => $b61_90,
            'b61_90_pct'        => $pct61_90,
            'b90_plus'          => $b90_plus,
            'b90_plus_pct'      => $pct90_plus,
        ];

        // 8. Top 5 Overdue Vendors from real records (90+ Days delinquent, or highest total outstanding)
        $overdueVendors = array_filter($rows, fn($v) => ($v['bucket_90_plus'] ?? 0) > 0);
        if (empty($overdueVendors)) {
            $overdueVendors = array_filter($rows, fn($v) => ($v['total_outstanding'] ?? 0) > 0);
        }
        usort($overdueVendors, function ($a, $b) {
            if (($b['bucket_90_plus'] ?? 0) != ($a['bucket_90_plus'] ?? 0)) {
                return ($b['bucket_90_plus'] ?? 0) <=> ($a['bucket_90_plus'] ?? 0);
            }
            return ($b['total_outstanding'] ?? 0) <=> ($a['total_outstanding'] ?? 0);
        });

        $top5Overdue = array_slice(array_map(function ($item) {
            return [
                'name' => $item['name'],
                'amount' => (float) (($item['bucket_90_plus'] ?? 0) > 0 ? $item['bucket_90_plus'] : $item['total_outstanding'])
            ];
        }, $overdueVendors), 0, 5);

        // 9. Vendor list for the theme dropdown filter
        $vendorList = array_map(fn($v) => [
            'id' => $v['company_id'],
            'name' => $v['name']
        ], $allVendors);

        return [
            'kpis'            => $kpis,
            'rows'            => $rows,
            'allVendors'      => $allVendors,
            'vendorList'      => $vendorList,
            'allProjects'     => $allProjects,
            'top5Overdue'     => $top5Overdue,
            'asOnDate'        => $asOnDate->format('d/m/Y'),
            'selectedProject' => $selectedProject,
            'selectedVendor'  => $selectedVendor,
            'currency'        => $currency,
            'currencyCode'    => $currencyCode,
        ];
    }

    /**
     * Build the real dataset querying vendors, payees, purchase bills, RA bills, and site expenses
     */
    private function buildRealDataset(Carbon $asOnDate, string $defaultProjectName, int $systemId): array
    {
        $entities = [];

        // 1. Vendors table
        $dbVendors = Vendor::all();
        foreach ($dbVendors as $v) {
            $code = $v->vendor_code ?: ('VND-' . str_pad((string)$v->id, 3, '0', STR_PAD_LEFT));
            $entities['vendor_' . $v->id] = [
                'type' => 'Vendor',
                'raw_id' => $v->id,
                'vendor_id' => $v->id,
                'key' => 'vendor_' . $v->id,
                'name' => $v->name,
                'company_id' => $code,
                'phone' => $v->phone ?: '',
                'email' => $v->email ?: '',
                'pan' => $v->pan ?: '',
                'gstin' => $v->gstin ?: '',
                'linked_account_id' => $v->linked_account_id,
                'invoices' => [],
                'payments' => []
            ];
        }

        // 2. Payees table (Suppliers, Contractors, Vendors)
        $dbPayees = Payee::whereIn('type', ['Supplier', 'Contractor', 'Vendor'])->get();
        foreach ($dbPayees as $p) {
            // Check if name matches an existing Vendor entity
            $matchedVendorKey = null;
            foreach ($entities as $k => $e) {
                if (strcasecmp(trim($e['name']), trim($p->name)) === 0) {
                    $matchedVendorKey = $k;
                    break;
                }
            }

            if ($matchedVendorKey) {
                $entities[$matchedVendorKey]['payee_id'] = $p->id;
                if (empty($entities[$matchedVendorKey]['linked_account_id'])) {
                    $entities[$matchedVendorKey]['linked_account_id'] = $p->linked_account_id;
                }
                if (empty($entities[$matchedVendorKey]['pan'])) {
                    $entities[$matchedVendorKey]['pan'] = $p->pan ?: '';
                }
                if (empty($entities[$matchedVendorKey]['gstin'])) {
                    $entities[$matchedVendorKey]['gstin'] = $p->gstin ?: '';
                }
            } else {
                $key = 'payee_' . $p->id;
                $code = $p->pan ?: ($p->gstin ?: ('SUP-' . str_pad((string)$p->id, 4, '0', STR_PAD_LEFT)));
                $entities[$key] = [
                    'type' => $p->type ?: 'Supplier',
                    'raw_id' => $p->id,
                    'payee_id' => $p->id,
                    'key' => $key,
                    'name' => $p->name,
                    'company_id' => $code,
                    'phone' => $p->phone ?: '',
                    'email' => $p->email ?: '',
                    'pan' => $p->pan ?: '',
                    'gstin' => $p->gstin ?: '',
                    'linked_account_id' => $p->linked_account_id,
                    'invoices' => [],
                    'payments' => []
                ];
            }
        }

        // 3. Purchase Bills from bills table
        $bills = DB::table('bills')
            ->leftJoin('projects', 'bills.project_id', '=', 'projects.id')
            ->select('bills.*', 'projects.name as project_name')
            ->get();

        foreach ($bills as $b) {
            $targetKey = null;
            if ($b->payee_id) {
                if (isset($entities['payee_' . $b->payee_id])) {
                    $targetKey = 'payee_' . $b->payee_id;
                } else {
                    foreach ($entities as $k => $e) {
                        if (($e['payee_id'] ?? null) == $b->payee_id) {
                            $targetKey = $k;
                            break;
                        }
                    }
                }
            }

            if (!$targetKey) {
                $payeeRec = Payee::find($b->payee_id);
                $pName = $payeeRec?->name ?: ('Supplier #' . $b->payee_id);
                $targetKey = 'payee_' . $b->payee_id;
                $entities[$targetKey] = [
                    'type' => $payeeRec?->type ?: 'Supplier',
                    'raw_id' => $b->payee_id,
                    'payee_id' => $b->payee_id,
                    'key' => $targetKey,
                    'name' => $pName,
                    'company_id' => $payeeRec?->pan ?: ($payeeRec?->gstin ?: ('SUP-' . str_pad((string)$b->payee_id, 4, '0', STR_PAD_LEFT))),
                    'phone' => $payeeRec?->phone ?: '',
                    'email' => $payeeRec?->email ?: '',
                    'pan' => $payeeRec?->pan ?: '',
                    'gstin' => $payeeRec?->gstin ?: '',
                    'linked_account_id' => $payeeRec?->linked_account_id,
                    'invoices' => [],
                    'payments' => []
                ];
            }

            // Payments for this bill
            $billPayments = DB::table('bill_payments')->where('bill_id', $b->id)->get();
            $paidAmount = (float) $billPayments->sum('amount');
            $billTotal = (float) ($b->final_amount ?: $b->bill_amount ?: 0);
            $outstanding = max(0, $billTotal - $paidAmount);

            foreach ($billPayments as $bp) {
                $entities[$targetKey]['payments'][] = [
                    'date' => $bp->date,
                    'amount' => (float)$bp->amount,
                    'ref' => $bp->voucher_id ? ('Voucher #' . $bp->voucher_id) : ('BP-' . $bp->id)
                ];
            }

            $invDate = $b->created_at ? Carbon::parse($b->created_at) : Carbon::now();
            $termsDays = 30;
            if (!empty($b->payment_terms) && preg_match('/(\d+)/', $b->payment_terms, $m)) {
                $termsDays = (int) $m[1];
            }
            $dueDate = (clone $invDate)->addDays($termsDays);

            $daysOutstanding = 0;
            if ($asOnDate->greaterThan($dueDate)) {
                $daysOutstanding = (int) $dueDate->diffInDays($asOnDate);
            }

            if ($daysOutstanding <= 30) {
                $bucket = '0 - 30 Days';
                $bucketCode = '0_30';
            } elseif ($daysOutstanding <= 60) {
                $bucket = '31 - 60 Days';
                $bucketCode = '31_60';
            } elseif ($daysOutstanding <= 90) {
                $bucket = '61 - 90 Days';
                $bucketCode = '61_90';
            } else {
                $bucket = '90+ Days';
                $bucketCode = '90_plus';
            }

            $entities[$targetKey]['invoices'][] = [
                'source' => 'bill',
                'source_id' => $b->id,
                'invoice_no' => $b->bill_number ?: ('BILL-' . str_pad((string)$b->id, 4, '0', STR_PAD_LEFT)),
                'project_name' => $b->project_name ?: $defaultProjectName,
                'invoice_date' => $invDate->format('d/m/Y'),
                'invoice_date_raw' => $invDate->format('Y-m-d'),
                'due_date' => $dueDate->format('d/m/Y'),
                'due_date_raw' => $dueDate->format('Y-m-d'),
                'invoice_amount' => $billTotal,
                'paid_amount' => $paidAmount,
                'outstanding_amount' => $outstanding,
                'days_outstanding' => $daysOutstanding,
                'bucket' => $bucket,
                'bucket_code' => $bucketCode,
                'status' => $outstanding <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partially Paid' : 'Unpaid'),
                'expense_head' => $b->expense_head ?: ($b->bill_type ?: 'Material Supply')
            ];
        }

        // 4. Contractor RA Progress Bills
        $raBills = RaBill::with(['payments', 'project'])->get();
        foreach ($raBills as $rb) {
            $targetKey = null;
            if ($rb->contractor_id) {
                if (isset($entities['payee_' . $rb->contractor_id])) {
                    $targetKey = 'payee_' . $rb->contractor_id;
                } else {
                    foreach ($entities as $k => $e) {
                        if (($e['payee_id'] ?? null) == $rb->contractor_id) {
                            $targetKey = $k;
                            break;
                        }
                    }
                }
            }
            if (!$targetKey && !empty($rb->contractor_name)) {
                foreach ($entities as $k => $e) {
                    if (strcasecmp(trim($e['name']), trim($rb->contractor_name)) === 0) {
                        $targetKey = $k;
                        break;
                    }
                }
            }

            if (!$targetKey) {
                $cName = $rb->contractor_name ?: ('Contractor #' . ($rb->contractor_id ?: $rb->id));
                $targetKey = 'payee_' . ($rb->contractor_id ?: ('c_' . $rb->id));
                $entities[$targetKey] = [
                    'type' => 'Contractor',
                    'raw_id' => $rb->contractor_id ?: $rb->id,
                    'payee_id' => $rb->contractor_id,
                    'key' => $targetKey,
                    'name' => $cName,
                    'company_id' => 'CON-' . str_pad((string)($rb->contractor_id ?: $rb->id), 3, '0', STR_PAD_LEFT),
                    'phone' => '',
                    'email' => '',
                    'pan' => '',
                    'gstin' => '',
                    'linked_account_id' => null,
                    'invoices' => [],
                    'payments' => []
                ];
            }

            foreach ($rb->payments as $rp) {
                $entities[$targetKey]['payments'][] = [
                    'date' => $rp->payment_date,
                    'amount' => (float)$rp->paid_amount,
                    'ref' => $rp->reference_no ?: ($rp->voucher_id ? 'Voucher #' . $rp->voucher_id : 'RA-PMT-' . $rp->id)
                ];
            }

            $netAmount = (float)($rb->net_approved_amount > 0 ? $rb->net_approved_amount : $rb->gross_amount);
            $paidAmount = (float)$rb->paid_amount;
            $balance = (float)($rb->balance_amount > 0 ? $rb->balance_amount : max(0, $netAmount - $paidAmount));

            $subDate = $rb->submit_date ? Carbon::parse($rb->submit_date) : ($rb->created_at ? Carbon::parse($rb->created_at) : Carbon::now());
            $dueDate = $rb->due_date ? Carbon::parse($rb->due_date) : (clone $subDate)->addDays(30);

            $daysOutstanding = 0;
            if ($asOnDate->greaterThan($dueDate)) {
                $daysOutstanding = (int) $dueDate->diffInDays($asOnDate);
            }

            if ($daysOutstanding <= 30) {
                $bucket = '0 - 30 Days';
                $bucketCode = '0_30';
            } elseif ($daysOutstanding <= 60) {
                $bucket = '31 - 60 Days';
                $bucketCode = '31_60';
            } elseif ($daysOutstanding <= 90) {
                $bucket = '61 - 90 Days';
                $bucketCode = '61_90';
            } else {
                $bucket = '90+ Days';
                $bucketCode = '90_plus';
            }

            $projName = $rb->project?->name ?: $defaultProjectName;
            if ($rb->unit_name) {
                $projName .= ' (' . $rb->unit_name . ')';
            }

            $entities[$targetKey]['invoices'][] = [
                'source' => 'ra_bill',
                'source_id' => $rb->id,
                'invoice_no' => $rb->ra_bill_number ?: ('RA-' . str_pad((string)$rb->id, 3, '0', STR_PAD_LEFT)),
                'project_name' => $projName,
                'invoice_date' => $subDate->format('d/m/Y'),
                'invoice_date_raw' => $subDate->format('Y-m-d'),
                'due_date' => $dueDate->format('d/m/Y'),
                'due_date_raw' => $dueDate->format('Y-m-d'),
                'invoice_amount' => $netAmount,
                'paid_amount' => $paidAmount,
                'outstanding_amount' => $balance,
                'days_outstanding' => $daysOutstanding,
                'bucket' => $bucket,
                'bucket_code' => $bucketCode,
                'status' => $balance <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partially Paid' : 'Unpaid'),
                'expense_head' => 'Contractor Progress Bill'
            ];
        }

        // 5. Site Expenses
        $siteExpenses = SiteExpense::with('project')->get();
        foreach ($siteExpenses as $se) {
            $targetKey = null;
            if ($se->vendor_id && isset($entities['vendor_' . $se->vendor_id])) {
                $targetKey = 'vendor_' . $se->vendor_id;
            } elseif ($se->payee_id && isset($entities['payee_' . $se->payee_id])) {
                $targetKey = 'payee_' . $se->payee_id;
            } elseif (!empty($se->casual_payee_name)) {
                foreach ($entities as $k => $e) {
                    if (stripos($e['name'], trim($se->casual_payee_name)) !== false || stripos(trim($se->casual_payee_name), $e['name']) !== false) {
                        $targetKey = $k;
                        break;
                    }
                }
            }

            if (!$targetKey) {
                $cName = $se->casual_payee_name ?: 'Site Expense Payee';
                $targetKey = 'expense_payee_' . $se->id;
                $entities[$targetKey] = [
                    'type' => 'Vendor',
                    'raw_id' => $se->id,
                    'key' => $targetKey,
                    'name' => $cName,
                    'company_id' => 'EXP-' . str_pad((string)$se->id, 3, '0', STR_PAD_LEFT),
                    'phone' => '',
                    'email' => '',
                    'pan' => '',
                    'gstin' => '',
                    'linked_account_id' => null,
                    'invoices' => [],
                    'payments' => []
                ];
            }

            $netAmount = (float)($se->net_amount > 0 ? $se->net_amount : $se->gross_amount);
            $paidAmount = (float)$se->paid_amount;
            $balance = (float)($se->balance_amount > 0 ? $se->balance_amount : max(0, $netAmount - $paidAmount));

            $vDate = $se->voucher_date ? Carbon::parse($se->voucher_date) : ($se->created_at ? Carbon::parse($se->created_at) : Carbon::now());
            $dueDate = (clone $vDate)->addDays(30);

            $daysOutstanding = 0;
            if ($asOnDate->greaterThan($dueDate)) {
                $daysOutstanding = (int) $dueDate->diffInDays($asOnDate);
            }

            if ($daysOutstanding <= 30) {
                $bucket = '0 - 30 Days';
                $bucketCode = '0_30';
            } elseif ($daysOutstanding <= 60) {
                $bucket = '31 - 60 Days';
                $bucketCode = '31_60';
            } elseif ($daysOutstanding <= 90) {
                $bucket = '61 - 90 Days';
                $bucketCode = '61_90';
            } else {
                $bucket = '90+ Days';
                $bucketCode = '90_plus';
            }

            $entities[$targetKey]['invoices'][] = [
                'source' => 'site_expense',
                'source_id' => $se->id,
                'invoice_no' => $se->voucher_number ?: ('EXP-' . str_pad((string)$se->id, 4, '0', STR_PAD_LEFT)),
                'project_name' => $se->project?->name ?: $defaultProjectName,
                'invoice_date' => $vDate->format('d/m/Y'),
                'invoice_date_raw' => $vDate->format('Y-m-d'),
                'due_date' => $dueDate->format('d/m/Y'),
                'due_date_raw' => $dueDate->format('Y-m-d'),
                'invoice_amount' => $netAmount,
                'paid_amount' => $paidAmount,
                'outstanding_amount' => $balance,
                'days_outstanding' => $daysOutstanding,
                'bucket' => $bucket,
                'bucket_code' => $bucketCode,
                'status' => $balance <= 0 ? 'Paid' : ($paidAmount > 0 ? 'Partially Paid' : 'Unpaid'),
                'expense_head' => $se->expense_category_name ?: 'Site Operating Expense'
            ];
        }

        // 6. Linked Ledger Payments from Accounts
        foreach ($entities as $k => &$e) {
            if (!empty($e['linked_account_id'])) {
                $accId = $e['linked_account_id'];
                $debitLines = DB::table('voucher_lines')
                    ->join('vouchers', 'voucher_lines.voucher_id', '=', 'vouchers.id')
                    ->where('voucher_lines.account_id', $accId)
                    ->where('voucher_lines.debit', '>', 0)
                    ->select('voucher_lines.debit', 'vouchers.date as voucher_date', 'vouchers.voucher_number', 'vouchers.id as v_id')
                    ->get();
                foreach ($debitLines as $dl) {
                    $e['payments'][] = [
                        'date' => $dl->voucher_date,
                        'amount' => (float)$dl->debit,
                        'ref' => $dl->voucher_number ?: ('Voucher #' . $dl->v_id)
                    ];
                }
            }
        }
        unset($e);

        // 7. Aggregate Entity Rows
        $allVendors = [];
        $idCounter = 1;

        foreach ($entities as $k => $e) {
            // Sort invoices descending by invoice date
            usort($e['invoices'], function ($a, $b) {
                return strcmp($b['invoice_date_raw'] ?? '', $a['invoice_date_raw'] ?? '');
            });

            $totOut = 0.0;
            $b0_30 = 0.0;
            $b31_60 = 0.0;
            $b61_90 = 0.0;
            $b90_plus = 0.0;

            foreach ($e['invoices'] as $inv) {
                $bal = (float)$inv['outstanding_amount'];
                $totOut += $bal;
                if ($inv['bucket_code'] === '0_30') {
                    $b0_30 += $bal;
                } elseif ($inv['bucket_code'] === '31_60') {
                    $b31_60 += $bal;
                } elseif ($inv['bucket_code'] === '61_90') {
                    $b61_90 += $bal;
                } elseif ($inv['bucket_code'] === '90_plus') {
                    $b90_plus += $bal;
                }
            }

            // Determine primary project for display
            $projectsList = array_unique(array_filter(array_column($e['invoices'], 'project_name')));
            $primaryProject = !empty($projectsList) ? implode(', ', $projectsList) : $defaultProjectName;

            // Determine latest payment date and reference
            $lastPaymentDate = '—';
            $lastPaymentRef = '—';
            if (!empty($e['payments'])) {
                usort($e['payments'], function ($a, $b) {
                    return strcmp($b['date'] ?? '', $a['date'] ?? '');
                });
                $lp = $e['payments'][0];
                $lastPaymentDate = !empty($lp['date']) ? Carbon::parse($lp['date'])->format('d/m/Y') : '—';
                $lastPaymentRef = $lp['ref'] ?? '—';
            }

            $allVendors[] = [
                'id' => $idCounter++,
                'key' => $e['key'],
                'name' => $e['name'],
                'type' => $e['type'],
                'company_id' => $e['company_id'],
                'project' => $primaryProject,
                'total_outstanding' => $totOut,
                'bucket_0_30' => $b0_30,
                'bucket_31_60' => $b31_60,
                'bucket_61_90' => $b61_90,
                'bucket_90_plus' => $b90_plus,
                'last_payment_date' => $lastPaymentDate,
                'last_payment_ref' => $lastPaymentRef,
                'invoices' => $e['invoices']
            ];
        }

        // Sort allVendors: highest total outstanding first, then alphabetical by name
        usort($allVendors, function ($a, $b) {
            if ($b['total_outstanding'] != $a['total_outstanding']) {
                return $b['total_outstanding'] <=> $a['total_outstanding'];
            }
            return strcmp($a['name'], $b['name']);
        });

        // Re-index row IDs sequentially
        foreach ($allVendors as $idx => &$v) {
            $v['id'] = $idx + 1;
        }
        unset($v);

        return $allVendors;
    }
}
