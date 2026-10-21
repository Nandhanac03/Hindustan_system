<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payee;
use App\Models\Project;
use App\Models\RaBill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContractorPayoutReportService
{
    /**
     * Get Contractor RA Bill & Payout Register Report data directly from Database
     */
    public function getReportData(array $filters = []): array
    {
        $systemId = Auth::user()->system_id ?? 1;

        // 1. Fetch Real RA Bills from the Contractor Section
        $query = RaBill::with([
            'contractor.linkedAccount',
            'project',
            'unit',
            'payments.companyBankAccount'
        ]);

        if ($systemId) {
            $query->where('system_id', $systemId);
        }

        $allDbBills = $query->orderBy('id', 'desc')->get();

        // 2. Map Database Records to Report Row Format
        $rows = [];
        foreach ($allDbBills as $bill) {
            $c = $bill->contractor;
            $contractorName = $bill->contractor_name ?: ($c?->name ?? 'General Contractor');
            
            // Contractor Company ID or Tax Code
            $companyId = $c?->pan ?: ($c?->gstin ?: ('CON-' . str_pad((string)($bill->contractor_id ?: $bill->id), 3, '0', STR_PAD_LEFT)));
            
            // Project / Phase display
            $projectName = $bill->project?->name ?? 'Site Project';
            $projectPhase = $bill->unit_name ? ($projectName . ' - ' . $bill->unit_name) : $projectName;

            // Numeric amounts
            $gross = (float) $bill->gross_amount;
            $deduction = (float) $bill->correction_amount;
            $addition = (float) $bill->additional_amount;
            $additionPct = (float) $bill->additional_percentage;
            $netPayable = (float) $bill->net_approved_amount;
            $paid = (float) $bill->paid_amount;
            $balance = (float) $bill->balance_amount;

            // Dates
            $subDate = $bill->submit_date 
                ? Carbon::parse($bill->submit_date)->format('d/m/Y') 
                : ($bill->created_at ? $bill->created_at->format('d/m/Y') : '—');
            $subDateRaw = $bill->submit_date 
                ? Carbon::parse($bill->submit_date)->format('Y-m-d') 
                : ($bill->created_at ? $bill->created_at->format('Y-m-d') : '');

            // Payout Status Mapping
            if ($bill->status === 'cleared' || ($paid >= $netPayable && $netPayable > 0)) {
                $status = 'Paid';
            } elseif ($paid > 0) {
                $status = 'Partially Paid';
            } elseif (!empty($bill->verified_date) || $bill->status === 'verified') {
                $status = 'Approved';
            } else {
                $status = 'Pending Approval';
            }

            // Payment information from linked payments
            $lastPayment = $bill->payments->last();
            $paidDate = $lastPayment?->payment_date 
                ? Carbon::parse($lastPayment->payment_date)->format('d/m/Y') 
                : ($paid > 0 ? $subDate : '—');
            $bankRef = $lastPayment?->reference_no 
                ?: ($lastPayment?->voucher_id ? 'Voucher #' . $lastPayment->voucher_id : ($paid > 0 ? 'TRF' . $bill->id : '—'));

            // Payment details collection for bottom detail tab
            $paymentsHistory = [];
            foreach ($bill->payments as $p) {
                $paymentsHistory[] = [
                    'voucher_no'     => $p->voucher_id ? ('BPV-' . $p->voucher_id) : ('PMT-' . $p->id),
                    'payment_date'   => $p->payment_date ? Carbon::parse($p->payment_date)->format('d/m/Y') : '—',
                    'payment_mode'   => $p->payment_mode ?: 'Bank Transfer',
                    'bank_reference' => $p->reference_no ?: '—',
                    'bank_account'   => $p->companyBankAccount?->bank_name ? ($p->companyBankAccount->bank_name . ' (' . $p->companyBankAccount->account_number . ')') : 'Main Corporate A/c',
                    'paid_amount'    => (float) $p->paid_amount,
                    'status'         => ucfirst($p->status ?: 'paid'),
                ];
            }

            $rows[] = [
                'id'                  => $bill->id,
                'contractor_id'       => $bill->contractor_id,
                'contractor_name'     => $contractorName,
                'company_id'          => $companyId,
                'project_id'          => $bill->project_id,
                'project_phase'       => $projectPhase,
                'ra_bill_no'          => $bill->ra_bill_number ?: ('RA-' . str_pad((string)$bill->id, 3, '0', STR_PAD_LEFT)),
                'submission_date'     => $subDate,
                'submission_date_raw' => $subDateRaw,
                'gross_amount'        => $gross,
                'advance_deduction'   => $deduction,
                'additional_amount'   => $addition,
                'additional_pct'      => $additionPct,
                'net_payable'         => $netPayable,
                'paid_amount'         => $paid,
                'balance_amount'      => $balance,
                'payout_status'       => $status,
                'paid_date'           => $paidDate,
                'bank_ref_no'         => $bankRef,
                'contact_person'      => $c?->name ?: $contractorName,
                'contact_phone'       => $c?->phone ?: '—',
                'contact_email'       => $c?->email ?: '—',
                'work_description'    => $bill->remarks ?: 'Contractor RA progress bill claim',
                'contract_value'      => $gross,
                'approved_by'         => $bill->engineer_name ?: (!empty($bill->verified_date) ? 'Site Engineer' : '—'),
                'approved_date'       => $bill->verified_date ? Carbon::parse($bill->verified_date)->format('d/m/Y') : '—',
                'remarks'             => $bill->remarks ?: 'Verified site measurement sign-off.',
                'payments'            => $paymentsHistory,
            ];
        }

        // 3. Apply Filters
        // Filter by Project
        $selectedProject = $filters['project'] ?? null;
        if ($selectedProject && $selectedProject !== 'All' && $selectedProject !== 'All Projects') {
            $rows = array_values(array_filter($rows, function ($r) use ($selectedProject) {
                return stripos($r['project_phase'], $selectedProject) !== false;
            }));
        }

        // Filter by Contractor
        $selectedContractor = $filters['contractor'] ?? null;
        if ($selectedContractor && $selectedContractor !== 'All' && $selectedContractor !== 'All Contractors') {
            $rows = array_values(array_filter($rows, function ($r) use ($selectedContractor) {
                return stripos($r['contractor_name'], $selectedContractor) !== false ||
                       stripos($r['company_id'], $selectedContractor) !== false;
            }));
        }

        // Filter by Date Range (From Date - To Date)
        $fromDate = $filters['from_date'] ?? null;
        $toDate   = $filters['to_date'] ?? null;
        if ($fromDate) {
            $fromTs = strtotime(str_replace('/', '-', $fromDate));
            if ($fromTs) {
                $rows = array_values(array_filter($rows, function ($r) use ($fromTs) {
                    $itemTs = strtotime($r['submission_date_raw'] ?? '');
                    return $itemTs ? ($itemTs >= $fromTs) : true;
                }));
            }
        }
        if ($toDate) {
            $toTs = strtotime(str_replace('/', '-', $toDate));
            if ($toTs) {
                $rows = array_values(array_filter($rows, function ($r) use ($toTs) {
                    $itemTs = strtotime($r['submission_date_raw'] ?? '');
                    return $itemTs ? ($itemTs <= $toTs) : true;
                }));
            }
        }

        // Filter by Status
        $selectedStatus = $filters['status'] ?? null;
        if ($selectedStatus && $selectedStatus !== 'All') {
            $rows = array_values(array_filter($rows, function ($r) use ($selectedStatus) {
                return strcasecmp($r['payout_status'], $selectedStatus) === 0;
            }));
        }

        // 4. Compute Dynamic Financial KPIs directly from Database
        $totalBills = count($rows);
        $totalGross = (float) array_sum(array_column($rows, 'gross_amount'));
        $totalDeduc = (float) array_sum(array_column($rows, 'advance_deduction'));
        $totalAdd   = (float) array_sum(array_column($rows, 'additional_amount'));
        $totalNet   = (float) array_sum(array_column($rows, 'net_payable'));
        $totalPaid  = (float) array_sum(array_column($rows, 'paid_amount'));
        $totalBal   = (float) array_sum(array_column($rows, 'balance_amount'));
        $paidPct    = $totalNet > 0 ? round(($totalPaid / $totalNet) * 100, 1) : 0.0;

        $kpis = [
            'total_bills'         => $totalBills,
            'gross_amount'        => $totalGross,
            'total_deductions'    => $totalDeduc,
            'total_additions'     => $totalAdd,
            'net_payable'         => $totalNet,
            'paid_amount'         => $totalPaid,
            'balance_amount'      => $totalBal,
            'paid_pct'            => $paidPct,
        ];

        // 5. Lookups from actual Database records
        $allProjects = Project::where('is_active', true)->orderBy('name')->pluck('name')->toArray();
        if (empty($allProjects)) {
            $allProjects = Project::orderBy('name')->pluck('name')->toArray();
        }

        $allContractors = Payee::whereIn('type', ['Contractor', 'Supplier'])
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'email', 'pan', 'gstin'])
            ->map(function ($c) {
                return [
                    'id'    => $c->pan ?: ($c->gstin ?: ('CON-' . str_pad((string)$c->id, 3, '0', STR_PAD_LEFT))),
                    'name'  => $c->name,
                    'phone' => $c->phone,
                    'email' => $c->email,
                ];
            })
            ->toArray();

        // Fallback for contractor names present on bills
        $existingContractorNames = array_column($allContractors, 'name');
        foreach ($allDbBills as $b) {
            $cName = $b->contractor_name ?: ($b->contractor?->name);
            if ($cName && !in_array($cName, $existingContractorNames)) {
                $allContractors[] = [
                    'id'    => 'CON-' . str_pad((string)($b->contractor_id ?: $b->id), 3, '0', STR_PAD_LEFT),
                    'name'  => $cName,
                    'phone' => '',
                    'email' => '',
                ];
                $existingContractorNames[] = $cName;
            }
        }

        // Default selected bill is the first bill from the active database
        $selectedBill = !empty($rows) ? $rows[0] : null;

        return [
            'kpis'               => $kpis,
            'rows'               => $rows,
            'allProjects'        => $allProjects,
            'allContractors'     => $allContractors,
            'selectedBill'       => $selectedBill,
            'selectedProject'    => $selectedProject ?? 'All Projects',
            'selectedContractor' => $selectedContractor ?? 'All Contractors',
            'fromDate'           => $fromDate ?? '',
            'toDate'             => $toDate ?? '',
            'selectedStatus'     => $selectedStatus ?? 'All',
        ];
    }
}
