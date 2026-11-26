<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAsset;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeDocument;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeExperience;
use App\Models\EmployeeLeave;
use App\Models\EmployeeOvertime;
use App\Models\EmployeePayroll;
use App\Models\EmployeeSalaryComponent;
use App\Models\EmployeeSalaryRevision;
use App\Models\SalaryComponent;
use App\Models\System;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    private function scopedQuery()
    {
        $user = Auth::user();
        $query = Employee::query();
        if (!$user->hasMultiSystemAccess() && $user->system_id) {
            $query->where('system_id', $user->system_id);
        }
        return $query;
    }

    private function resolveSystemId(?int $requestedSystemId = null): int
    {
        if ($requestedSystemId) {
            return $requestedSystemId;
        }
        $user = Auth::user();
        if ($user && $user->system_id) {
            return (int) $user->system_id;
        }
        return (int) (System::where('is_active', true)->value('id') ?? 1);
    }

    public function index(Request $request): View
    {
        $allEmployees = $this->scopedQuery()->with(['system', 'salaryComponents'])->orderBy('id', 'desc')->get();

        $totalCount = $allEmployees->count();
        $activeCount = $allEmployees->where('status', 'active')->count();
        $inactiveCount = $allEmployees->where('status', '!=', 'active')->count();
        $totalPayroll = (float) $allEmployees->where('status', 'active')->sum(function ($emp) {
            $compAllow = (float) $emp->salaryComponents->where('type', 'allowance')->sum('calculated_amount');
            if ($compAllow > 0) {
                return $compAllow;
            }
            $allow = (float) ($emp->allowances ?? 0);
            return $allow > 0 ? $allow : (float) ($emp->salary ?? 0);
        });

        $existingDesignations = $allEmployees
            ->pluck('designation')
            ->filter(fn($val) => !empty(trim((string) $val)))
            ->unique()
            ->values();

        $existingDepartments = $allEmployees
            ->pluck('department')
            ->filter(fn($val) => !empty(trim((string) $val)))
            ->unique()
            ->values();

        $lastEmp = $allEmployees->first();
        $nextNum = $lastEmp ? ((int) preg_replace('/[^0-9]/', '', (string) $lastEmp->employee_id) + 1) : 1001;
        if ($nextNum < 1001) {
            $nextNum = 1001;
        }
        $nextEmpId = 'EMP-' . $nextNum;

        return view('employees.index', [
            'employees' => $allEmployees,
            'employeesArray' => $allEmployees->values()->toArray(),
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'totalPayroll' => $totalPayroll,
            'nextEmpId' => $nextEmpId,
            'existingDesignations' => $existingDesignations,
            'existingDepartments' => $existingDepartments,
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $employee = $this->scopedQuery()
            ->with([
                'system',
                'user.roles',
                'experiences',
                'attendances',
                'overtimes',
                'leaves',
                'payrolls',
                'documents',
                'assets',
                'emergencyContacts',
                'salaryComponents',
                'salaryRevisions',
            ])
            ->findOrFail($id);

        $orderedEmployees = $this->scopedQuery()->orderBy('id', 'asc')->get(['id', 'name', 'employee_id', 'photo_path']);
        $currentIndex = $orderedEmployees->search(fn($e) => $e->id === $employee->id);
        $prevEmployee = ($currentIndex !== false && $currentIndex > 0) ? $orderedEmployees[$currentIndex - 1] : null;
        $nextEmployee = ($currentIndex !== false && $currentIndex < $orderedEmployees->count() - 1) ? $orderedEmployees[$currentIndex + 1] : null;

        $erpUsers = User::withoutGlobalScopes()
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'Super Admin'))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'username']);

        $masterComponents = SalaryComponent::where('status', 'active')
            ->orderBy('name')
            ->get();

        $activeTab = $request->query('tab', 'personal');

        $salaryLogs = \App\Models\PayrollSalary::with(['salaryHead', 'companyBankAccount', 'journalVoucher'])
            ->where('employee_id', $employee->id)
            ->orderByDesc('id')
            ->get();

        $financialPeriods = \App\Models\PayrollFinancialPeriod::where('system_id', $employee->system_id)
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $leaveQuotas = \App\Models\EmployeeLeaveQuota::with('financialPeriod')
            ->where('employee_id', $employee->id)
            ->orderByDesc('id')
            ->get();

        return view('employees.show', [
            'employee' => $employee,
            'prevEmployee' => $prevEmployee,
            'nextEmployee' => $nextEmployee,
            'erpUsers' => $erpUsers,
            'masterAllowances' => $masterComponents->where('type', 'allowance')->values(),
            'masterDeductions' => $masterComponents->where('type', 'deduction')->values(),
            'salaryLogs' => $salaryLogs,
            'financialPeriods' => $financialPeriods,
            'leaveQuotas' => $leaveQuotas,
            'activeTab' => $activeTab,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $systemId = $this->resolveSystemId($request->filled('system_id') ? (int) $request->system_id : null);

        $request->validate([
            'employee_id' => 'required|string|max:100|unique:employees,employee_id,NULL,id,system_id,' . $systemId,
            'name' => 'nullable|string|max:191',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:20',
            'designation' => 'required|string|max:191',
            'department' => 'nullable|string|max:191',
            'branch' => 'nullable|string|max:150',
            'reports_to' => 'nullable|string|max:150',
            'employment_type' => 'nullable|string|max:100',
            'shift' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'personal_email' => 'nullable|email|max:191',
            'work_email' => 'nullable|email|max:191',
            'portal_email' => 'nullable|email|max:191',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:30',
            'joining_date' => 'required|date',
            'confirmation_date' => 'nullable|date',
            'probation_end_date' => 'nullable|date',
            'notice_period' => 'nullable|string|max:50',
            'work_location' => 'nullable|string|max:150',
            'salary' => 'nullable|numeric|min:0',
            'basic_salary' => 'nullable|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:150',
            'bank_account_number' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'uan_number' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive,on_leave,terminated',
            'photo' => 'nullable|image|max:3072',
        ]);

        $firstName = trim((string) $request->input('first_name', ''));
        $lastName = trim((string) $request->input('last_name', ''));
        $fullName = trim((string) $request->input('name', ''));

        if ($fullName === '' && $firstName !== '') {
            $fullName = trim($firstName . ' ' . $lastName);
        } elseif ($fullName !== '' && $firstName === '') {
            $parts = preg_split('/\s+/', $fullName, 2);
            $firstName = $parts[0] ?? $fullName;
            $lastName = $lastName !== '' ? $lastName : ($parts[1] ?? '');
        }

        if ($fullName === '') {
            return back()->withErrors(['first_name' => 'Please enter employee name.'])->withInput();
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
        }

        $primaryEmail = $request->input('work_email') ?: $request->input('email');

        $employee = Employee::create([
            'system_id' => $systemId,
            'employee_id' => trim((string) $request->employee_id),
            'name' => $fullName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'nationality' => $request->nationality,
            'blood_group' => $request->blood_group,
            'designation' => $request->designation,
            'department' => $request->department,
            'branch' => $request->input('branch') ?: 'Head Office',
            'reports_to' => $request->reports_to,
            'employment_type' => $request->input('employment_type') ?: 'Full-Time Permanent',
            'shift' => $request->input('shift') ?: 'General Day Shift',
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'email' => $primaryEmail,
            'work_email' => $primaryEmail,
            'personal_email' => $request->personal_email,
            'portal_email' => $request->input('portal_email') ?: $primaryEmail,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'joining_date' => $request->joining_date,
            'confirmation_date' => $request->confirmation_date,
            'probation_end_date' => $request->probation_end_date,
            'notice_period' => $request->notice_period,
            'work_location' => $request->work_location,
            'photo_path' => $photoPath,
            'salary' => $request->filled('salary') ? $request->salary : 0,
            'basic_salary' => $request->filled('basic_salary') ? $request->basic_salary : ($request->filled('salary') ? $request->salary : 0),
            'hra' => $request->input('hra', 0) ?? 0,
            'allowances' => $request->input('allowances', 0) ?? 0,
            'deductions' => $request->input('deductions', 0) ?? 0,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'ifsc_code' => $request->ifsc_code,
            'pan_number' => $request->pan_number,
            'uan_number' => $request->uan_number,
            'status' => $request->input('status') ?: 'active',
        ]);

        if ($request->boolean('open_profile')) {
            return redirect()->route('employees.show', $employee->id)->with('status', 'Employee registered successfully.');
        }

        return redirect()->route('employees.index')->with('status', 'Employee registered successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $section = $request->input('section', 'all');
        $tab = $request->input('redirect_tab', 'personal');

        if ($section === 'personal') {
            $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'gender' => 'nullable|string|max:30',
                'date_of_birth' => 'nullable|date',
                'nationality' => 'nullable|string|max:100',
                'blood_group' => 'nullable|string|max:20',
                'personal_email' => 'nullable|email|max:191',
                'work_email' => 'nullable|email|max:191',
                'portal_email' => 'nullable|email|max:191',
                'phone' => 'nullable|string|max:50',
                'alternate_phone' => 'nullable|string|max:50',
                'address_line_1' => 'nullable|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:30',
            ]);

            $fullName = trim($request->first_name . ' ' . ($request->last_name ?? ''));
            $employee->update([
                'name' => $fullName,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'nationality' => $request->nationality,
                'blood_group' => $request->blood_group,
                'personal_email' => $request->personal_email,
                'work_email' => $request->work_email,
                'portal_email' => $request->portal_email,
                'email' => $request->work_email ?: $employee->email,
                'phone' => $request->phone,
                'alternate_phone' => $request->alternate_phone,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
            ]);

            return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'personal'])
                ->with('status', 'Personal details updated successfully.');
        }

        if ($section === 'employment') {
            $request->validate([
                'designation' => 'required|string|max:191',
                'department' => 'nullable|string|max:191',
                'branch' => 'nullable|string|max:150',
                'reports_to' => 'nullable|string|max:150',
                'employment_type' => 'nullable|string|max:100',
                'shift' => 'nullable|string|max:100',
                'joining_date' => 'required|date',
                'confirmation_date' => 'nullable|date',
                'probation_end_date' => 'nullable|date',
                'notice_period' => 'nullable|string|max:50',
                'work_location' => 'nullable|string|max:150',
                'status' => 'required|in:active,inactive,on_leave,terminated',
            ]);

            $employee->update($request->only([
                'designation',
                'department',
                'branch',
                'reports_to',
                'employment_type',
                'shift',
                'joining_date',
                'confirmation_date',
                'probation_end_date',
                'notice_period',
                'work_location',
                'status',
            ]));

            return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => $tab ?: 'employment'])
                ->with('status', 'Employment details updated successfully.');
        }

        if ($section === 'salary') {
            $request->validate([
                'salary' => 'required|numeric|min:0',
                'basic_salary' => 'nullable|numeric|min:0',
                'hra' => 'nullable|numeric|min:0',
                'allowances' => 'nullable|numeric|min:0',
                'deductions' => 'nullable|numeric|min:0',
                'bank_name' => 'nullable|string|max:150',
                'bank_account_number' => 'nullable|string|max:100',
                'ifsc_code' => 'nullable|string|max:50',
                'pan_number' => 'nullable|string|max:50',
                'uan_number' => 'nullable|string|max:50',
            ]);

            $employee->update([
                'salary' => $request->salary,
                'basic_salary' => $request->input('basic_salary', 0),
                'hra' => $request->input('hra', 0),
                'allowances' => $request->input('allowances', 0),
                'deductions' => $request->input('deductions', 0),
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'ifsc_code' => $request->ifsc_code,
                'pan_number' => $request->pan_number,
                'uan_number' => $request->uan_number,
            ]);

            return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
                ->with('status', 'Salary & banking details updated successfully.');
        }

        // Standard directory modal update
        $request->validate([
            'name' => 'nullable|string|max:191',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:20',
            'designation' => 'required|string|max:191',
            'department' => 'nullable|string|max:191',
            'branch' => 'nullable|string|max:150',
            'reports_to' => 'nullable|string|max:150',
            'employment_type' => 'nullable|string|max:100',
            'shift' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'personal_email' => 'nullable|email|max:191',
            'work_email' => 'nullable|email|max:191',
            'portal_email' => 'nullable|email|max:191',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:30',
            'joining_date' => 'required|date',
            'confirmation_date' => 'nullable|date',
            'probation_end_date' => 'nullable|date',
            'notice_period' => 'nullable|string|max:50',
            'work_location' => 'nullable|string|max:150',
            'salary' => 'nullable|numeric|min:0',
            'basic_salary' => 'nullable|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:150',
            'bank_account_number' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'uan_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'photo' => 'nullable|image|max:3072',
        ]);

        $firstName = trim((string) $request->input('first_name', ''));
        $lastName = trim((string) $request->input('last_name', ''));
        $fullName = trim((string) $request->input('name', ''));

        if ($fullName === '' && $firstName !== '') {
            $fullName = trim($firstName . ' ' . $lastName);
        } elseif ($fullName !== '' && $firstName === '') {
            $parts = preg_split('/\s+/', $fullName, 2);
            $firstName = $parts[0] ?? $fullName;
            $lastName = $lastName !== '' ? $lastName : ($parts[1] ?? '');
        }
        if ($fullName === '') {
            $fullName = $employee->name;
        }

        $photoPath = $employee->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
        }

        $primaryEmail = $request->input('work_email') ?: ($request->input('email') ?: $employee->email);

        $employee->update([
            'name' => $fullName,
            'first_name' => $firstName ?: $employee->first_name,
            'last_name' => $lastName,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'nationality' => $request->nationality,
            'blood_group' => $request->blood_group,
            'designation' => $request->designation,
            'department' => $request->department,
            'branch' => $request->input('branch') ?: $employee->branch,
            'reports_to' => $request->reports_to,
            'employment_type' => $request->input('employment_type') ?: $employee->employment_type,
            'shift' => $request->input('shift') ?: $employee->shift,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'email' => $primaryEmail,
            'work_email' => $primaryEmail,
            'personal_email' => $request->personal_email,
            'portal_email' => $request->input('portal_email') ?: $primaryEmail,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'joining_date' => $request->joining_date,
            'confirmation_date' => $request->confirmation_date,
            'probation_end_date' => $request->probation_end_date,
            'notice_period' => $request->notice_period,
            'work_location' => $request->work_location,
            'photo_path' => $photoPath,
            'salary' => $request->salary,
            'basic_salary' => $request->filled('basic_salary') ? $request->basic_salary : $employee->basic_salary,
            'hra' => $request->input('hra', 0),
            'allowances' => $request->input('allowances', 0),
            'deductions' => $request->input('deductions', 0),
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'ifsc_code' => $request->ifsc_code,
            'pan_number' => $request->pan_number,
            'uan_number' => $request->uan_number,
            'status' => $request->status,
        ]);

        if ($request->input('from_show')) {
            return redirect()->route('employees.show', $employee->id)->with('status', 'Employee details updated successfully.');
        }

        return redirect()->route('employees.index')->with('status', 'Employee details updated successfully.');
    }

    public function updateStatus(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,on_leave,terminated',
        ]);

        $employee->update(['status' => $validated['status']]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'status' => $employee->status, 'message' => 'Status updated.']);
        }

        return redirect()->route('employees.show', $employee->id)->with('status', 'Employee status updated.');
    }

    public function updatePhoto(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $request->validate([
            'photo' => 'required|image|max:3072',
        ]);

        if ($employee->photo_path && Storage::disk('public')->exists($employee->photo_path)) {
            Storage::disk('public')->delete($employee->photo_path);
        }

        $path = $request->file('photo')->store('employees/photos', 'public');
        $employee->update(['photo_path' => $path]);

        return redirect()->route('employees.show', $employee->id)->with('status', 'Profile photo updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('status', 'Employee removed from database.');
    }

    // --- Child Tab Endpoints ---

    public function storeExperience(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'company_name' => 'required|string|max:191',
            'job_title' => 'required|string|max:191',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);
        $employee->experiences()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'personal'])
            ->with('status', 'Previous experience added.');
    }

    public function destroyExperience(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeExperience::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'personal'])
            ->with('status', 'Experience record removed.');
    }

    public function storeAttendance(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|string|max:20',
            'check_out' => 'nullable|string|max:20',
            'status' => 'required|string|max:30',
            'notes' => 'nullable|string|max:255',
        ]);
        $employee->attendances()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'attendance'])
            ->with('status', 'Attendance recorded.');
    }

    public function destroyAttendance(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeAttendance::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'attendance'])
            ->with('status', 'Attendance record deleted.');
    }

    public function storeOvertime(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'overtime_date' => 'required|date',
            'hours' => 'required|numeric|min:0.5',
            'rate' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:255',
            'status' => 'required|string|max:30',
        ]);
        $rate = (float) ($data['rate'] ?? 0);
        $hours = (float) $data['hours'];
        if (empty($data['amount']) && $rate > 0) {
            $data['amount'] = $hours * $rate;
        }
        $employee->overtimes()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'overtime'])
            ->with('status', 'Overtime entry added.');
    }

    public function destroyOvertime(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeOvertime::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'overtime'])
            ->with('status', 'Overtime entry removed.');
    }

    public function storeLeave(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'leave_type' => 'required|string|max:60',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string|max:255',
            'status' => 'required|string|max:30',
        ]);
        $employee->leaves()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'leave'])
            ->with('status', 'Leave record added.');
    }

    public function destroyLeave(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeLeave::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'leave'])
            ->with('status', 'Leave record removed.');
    }

    public function storeLeaveQuota(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'financial_period_id' => 'required|exists:payroll_financial_periods,id',
            'allowed_leave' => 'required|numeric|min:0|max:366',
        ]);

        $existing = \App\Models\EmployeeLeaveQuota::where('financial_period_id', (int) $data['financial_period_id'])
            ->where('employee_id', $employee->id)
            ->first();

        \App\Models\EmployeeLeaveQuota::updateOrCreate(
            [
                'financial_period_id' => (int) $data['financial_period_id'],
                'employee_id' => $employee->id,
            ],
            [
                'system_id' => $employee->system_id,
                'allowed_leave' => (float) $data['allowed_leave'],
                'taken_leave' => $existing ? (float) $existing->taken_leave : 0,
            ]
        );

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'leave'])
            ->with('status', 'Financial year leave quota saved.');
    }

    public function destroyLeaveQuota(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        \App\Models\EmployeeLeaveQuota::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'leave'])
            ->with('status', 'Financial year leave quota removed.');
    }

    public function storePayroll(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'payroll_month' => 'required|string|max:30',
            'basic_salary' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required|string|max:30',
        ]);

        $basic = (float) ($data['basic_salary'] ?? 0);
        $allow = (float) ($data['allowances'] ?? 0);
        $ot = (float) ($data['overtime_pay'] ?? 0);
        $ded = (float) ($data['deductions'] ?? 0);
        $data['basic_salary'] = $basic;
        $data['allowances'] = $allow;
        $data['overtime_pay'] = $ot;
        $data['deductions'] = $ded;
        $data['net_salary'] = max(0, $basic + $allow + $ot - $ded);

        $employee->payrolls()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'payroll'])
            ->with('status', 'Payroll entry generated.');
    }

    public function destroyPayroll(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeePayroll::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'payroll'])
            ->with('status', 'Payroll entry removed.');
    }

    public function storeDocument(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:191',
            'document_type' => 'nullable|string|max:80',
            'file' => 'required|file|max:5120',
            'notes' => 'nullable|string|max:255',
        ]);

        $path = $request->file('file')->store('employees/documents', 'public');
        $employee->documents()->create([
            'title' => $request->title,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'notes' => $request->notes,
        ]);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'documents'])
            ->with('status', 'Document uploaded.');
    }

    public function destroyDocument(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $doc = EmployeeDocument::where('employee_id', $employee->id)->where('id', $itemId)->firstOrFail();
        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }
        $doc->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'documents'])
            ->with('status', 'Document deleted.');
    }

    public function storeAsset(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'asset_name' => 'required|string|max:191',
            'asset_code' => 'nullable|string|max:80',
            'category' => 'nullable|string|max:80',
            'assigned_date' => 'nullable|date',
            'status' => 'required|string|max:30',
            'notes' => 'nullable|string|max:255',
        ]);
        $employee->assets()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'assets'])
            ->with('status', 'Asset assigned.');
    }

    public function destroyAsset(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeAsset::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'assets'])
            ->with('status', 'Asset record removed.');
    }

    public function storeEmergencyContact(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'relationship' => 'required|string|max:80',
            'phone' => 'required|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);
        $data['is_primary'] = $request->boolean('is_primary');
        if ($data['is_primary']) {
            $employee->emergencyContacts()->update(['is_primary' => false]);
        }
        $employee->emergencyContacts()->create($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'emergency'])
            ->with('status', 'Emergency contact added.');
    }

    public function destroyEmergencyContact(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeEmergencyContact::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'emergency'])
            ->with('status', 'Emergency contact removed.');
    }

    public function linkUser(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $userId = $request->filled('user_id') ? (int) $request->input('user_id') : null;
        $employee->update(['user_id' => $userId]);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'permissions'])
            ->with('status', 'ERP user account link updated.');
    }

    private function syncEmployeeSalaryTotals(Employee $employee): void
    {
        $employee->load('salaryComponents');
        $base = (float) ($employee->basic_salary > 0 ? $employee->basic_salary : $employee->salary);
        $totalAllowances = 0.0;
        $totalDeductions = 0.0;

        foreach ($employee->salaryComponents as $comp) {
            $calc = $comp->calculation_type === 'Percentage'
                ? round(($base * (float) $comp->configured_value) / 100, 2)
                : (float) $comp->configured_value;

            if ((float) $comp->calculated_amount !== $calc) {
                $comp->update(['calculated_amount' => $calc]);
            }

            if ($comp->type === 'allowance') {
                $totalAllowances += $calc;
            } else {
                $totalDeductions += $calc;
            }
        }

        $totalSalary = round(max(0, $totalAllowances - $totalDeductions), 2);

        $employee->update([
            'allowances' => round($totalAllowances, 2),
            'deductions' => round($totalDeductions, 2),
            'salary' => $totalSalary,
        ]);

        // Keep any unpaid (Pending / Processed) payroll salary records in sync when components change
        $pendingSalaries = \App\Models\PayrollSalary::with('salaryHead')
            ->where('employee_id', $employee->id)
            ->where('status', '!=', 'Paid')
            ->get();

        foreach ($pendingSalaries as $ps) {
            $gross = round((float) $ps->basic_salary + (float) $ps->hra + $totalAllowances + (float) $ps->overtime_pay, 2);
            $net = round(max(0, $gross - $totalDeductions), 2);
            $ps->update([
                'allowances' => round($totalAllowances, 2),
                'deductions' => round($totalDeductions, 2),
                'gross_salary' => $gross,
                'net_salary' => $net,
            ]);
            $ps->salaryHead?->recalculateTotals();
        }
    }

    public function updateSalaryStructure(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'salary_structure' => 'nullable|string|max:150',
            'basic_salary' => 'nullable|numeric|min:0',
        ]);

        $updates = [
            'salary_structure' => $data['salary_structure'] ?: 'Standard Pay Structure',
        ];
        if (array_key_exists('basic_salary', $data) && $data['basic_salary'] !== null) {
            $updates['basic_salary'] = (float) $data['basic_salary'];
        }

        $employee->update($updates);

        $this->syncEmployeeSalaryTotals($employee);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', 'Salary structure updated.');
    }

    public function updateBankDetails(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'bank_name' => 'nullable|string|max:150',
            'bank_account_number' => 'nullable|string|max:80',
            'ifsc_code' => 'nullable|string|max:40',
            'pan_number' => 'nullable|string|max:40',
            'uan_number' => 'nullable|string|max:40',
            'bank_country' => 'nullable|string|max:100',
        ]);

        $employee->update($data);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', 'Bank details updated.');
    }

    public function storeSalaryComponent(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $data = $request->validate([
            'type' => 'required|in:allowance,deduction',
            'salary_component_id' => 'nullable|exists:salary_components,id',
            'name' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:255',
            'calculation_type' => 'required|in:Fixed Amount,Percentage',
            'configured_value' => 'required|numeric|min:0',
            'source' => 'nullable|string|max:50',
        ]);

        $master = !empty($data['salary_component_id'])
            ? SalaryComponent::find((int) $data['salary_component_id'])
            : null;

        $name = trim((string) ($data['name'] ?? ($master?->name ?? '')));
        if ($name === '') {
            return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
                ->withErrors(['name' => 'Component name is required.']);
        }

        // Reuse existing master component by name + type if already present, otherwise create it
        if (!$master) {
            $master = SalaryComponent::where('type', $data['type'])
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->first();
        }

        if (!$master) {
            $master = SalaryComponent::create([
                'system_id' => $employee->system_id,
                'name' => $name,
                'type' => $data['type'],
                'description' => $data['description'] ?? null,
                'status' => 'active',
            ]);
        }

        $description = $data['description'] ?? ($master->description ?? null);
        $source = ($data['source'] ?? null) ?: 'Master';

        $base = (float) ($employee->basic_salary > 0 ? $employee->basic_salary : $employee->salary);
        $configured = (float) $data['configured_value'];
        $calculated = $data['calculation_type'] === 'Percentage'
            ? round(($base * $configured) / 100, 2)
            : $configured;

        $employee->salaryComponents()->create([
            'salary_component_id' => $master->id,
            'name' => $master->name,
            'type' => $data['type'],
            'description' => $description,
            'source' => $source,
            'calculation_type' => $data['calculation_type'],
            'configured_value' => $configured,
            'calculated_amount' => $calculated,
        ]);

        $this->syncEmployeeSalaryTotals($employee);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', ucfirst($data['type']) . ' component added.');
    }

    public function updateSalaryComponent(Request $request, int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $component = EmployeeSalaryComponent::where('employee_id', $employee->id)->findOrFail($itemId);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
            'calculation_type' => 'required|in:Fixed Amount,Percentage',
            'configured_value' => 'required|numeric|min:0',
        ]);

        $base = (float) ($employee->basic_salary > 0 ? $employee->basic_salary : $employee->salary);
        $configured = (float) $data['configured_value'];
        $calculated = $data['calculation_type'] === 'Percentage'
            ? round(($base * $configured) / 100, 2)
            : $configured;

        $component->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? $component->description,
            'calculation_type' => $data['calculation_type'],
            'configured_value' => $configured,
            'calculated_amount' => $calculated,
        ]);

        $this->syncEmployeeSalaryTotals($employee);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', 'Salary component updated.');
    }

    public function destroySalaryComponent(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeSalaryComponent::where('employee_id', $employee->id)->where('id', $itemId)->delete();
        $this->syncEmployeeSalaryTotals($employee);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', 'Salary component removed.');
    }

    public function storeSalaryRevision(Request $request, int $id): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        $employee->load('salaryComponents');

        $data = $request->validate([
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'structure_name' => 'nullable|string|max:150',
            'revision_label' => 'nullable|string|max:80',
            'base_salary' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'is_current' => 'nullable|boolean',
        ]);

        $base = (float) $data['base_salary'];
        $totalAllowances = 0.0;
        $totalDeductions = 0.0;
        $breakdown = [];

        foreach ($employee->salaryComponents as $comp) {
            $calc = $comp->calculation_type === 'Percentage'
                ? round(($base * (float) $comp->configured_value) / 100, 2)
                : (float) $comp->configured_value;

            if ($comp->type === 'allowance') {
                $totalAllowances += $calc;
            } else {
                $totalDeductions += $calc;
            }

            $breakdown[] = [
                'name' => $comp->name,
                'type' => $comp->type,
                'calculation_type' => $comp->calculation_type,
                'configured_value' => (float) $comp->configured_value,
                'amount' => $calc,
            ];
        }

        $gross = round($base + $totalAllowances, 2);
        $net = round(max(0, $gross - $totalDeductions), 2);
        $isCurrent = $request->boolean('is_current', true);

        if ($isCurrent) {
            $employee->salaryRevisions()->update(['is_current' => false]);
            $employee->update([
                'salary_structure' => $data['structure_name'] ?: ($employee->salary_structure ?: 'Standard Pay Structure'),
                'basic_salary' => $base,
            ]);
            $this->syncEmployeeSalaryTotals($employee);
        }

        $employee->salaryRevisions()->create([
            'effective_from' => $data['effective_from'],
            'effective_to' => $data['effective_to'] ?? null,
            'structure_name' => $data['structure_name'] ?: ($employee->salary_structure ?: 'Standard Pay Structure'),
            'revision_label' => $data['revision_label'] ?: 'Salary Revision',
            'base_salary' => $base,
            'gross_salary' => $gross,
            'total_allowances' => round($totalAllowances, 2),
            'total_deductions' => round($totalDeductions, 2),
            'net_salary' => $net,
            'is_current' => $isCurrent,
            'notes' => $data['notes'] ?? null,
            'breakdown' => $breakdown,
        ]);

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', 'Salary revision recorded.');
    }

    public function destroySalaryRevision(int $id, int $itemId): RedirectResponse
    {
        $employee = $this->scopedQuery()->findOrFail($id);
        EmployeeSalaryRevision::where('employee_id', $employee->id)->where('id', $itemId)->delete();

        return redirect()->route('employees.show', ['employee' => $employee->id, 'tab' => 'salary'])
            ->with('status', 'Salary revision removed.');
    }
}
