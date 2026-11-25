<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'system_id',
        'user_id',
        'employee_id',
        'name',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'nationality',
        'blood_group',
        'designation',
        'salary_structure',
        'department',
        'branch',
        'reports_to',
        'employment_type',
        'shift',
        'phone',
        'alternate_phone',
        'email',
        'personal_email',
        'work_email',
        'portal_email',
        'address_line_1',
        'address_line_2',
        'country',
        'state',
        'city',
        'postal_code',
        'joining_date',
        'confirmation_date',
        'probation_end_date',
        'notice_period',
        'work_location',
        'photo_path',
        'salary',
        'basic_salary',
        'hra',
        'allowances',
        'deductions',
        'bank_name',
        'bank_account_number',
        'ifsc_code',
        'pan_number',
        'uan_number',
        'bank_country',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'date_of_birth' => 'date',
        'confirmation_date' => 'date',
        'probation_end_date' => 'date',
        'salary' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
    ];

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(EmployeeExperience::class)->orderByDesc('from_date');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class)->orderByDesc('attendance_date');
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(EmployeeOvertime::class)->orderByDesc('overtime_date');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class)->orderByDesc('from_date');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(EmployeePayroll::class)->orderByDesc('id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class)->orderByDesc('id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(EmployeeAsset::class)->orderByDesc('id');
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmployeeEmergencyContact::class)->orderByDesc('is_primary');
    }

    public function salaryComponents(): HasMany
    {
        return $this->hasMany(EmployeeSalaryComponent::class)->orderBy('id');
    }

    public function salaryRevisions(): HasMany
    {
        return $this->hasMany(EmployeeSalaryRevision::class)->orderByDesc('effective_from')->orderByDesc('id');
    }
}
