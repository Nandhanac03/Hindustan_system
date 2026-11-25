<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollSalary extends Model
{
    protected $fillable = [
        'system_id',
        'salary_head_id',
        'employee_id',
        'payroll_month',
        'working_days',
        'present_days',
        'leave_days',
        'overtime_hours',
        'basic_salary',
        'hra',
        'allowances',
        'overtime_pay',
        'gross_salary',
        'deductions',
        'net_salary',
        'payment_date',
        'payment_mode',
        'company_bank_account_id',
        'payment_reference',
        'journal_voucher_id',
        'status',
        'remarks',
    ];

    protected $casts = [
        'working_days' => 'decimal:1',
        'present_days' => 'decimal:1',
        'leave_days' => 'decimal:1',
        'overtime_hours' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'allowances' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function salaryHead(): BelongsTo
    {
        return $this->belongsTo(PayrollSalaryHead::class, 'salary_head_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function companyBankAccount(): BelongsTo
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function journalVoucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'journal_voucher_id');
    }
}
