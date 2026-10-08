<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $allEmployees = Employee::where('system_id', $systemId)->orderBy('id', 'desc')->get();

        $totalCount = $allEmployees->count();
        $activeCount = $allEmployees->where('status', 'active')->count();
        $inactiveCount = $allEmployees->where('status', 'inactive')->count();
        $totalPayroll = (float) $allEmployees->where('status', 'active')->sum('salary');

        $existingDesignations = $allEmployees
            ->pluck('designation')
            ->filter(fn($val) => !empty(trim((string)$val)))
            ->unique()
            ->values();

        $existingDepartments = $allEmployees
            ->pluck('department')
            ->filter(fn($val) => !empty(trim((string)$val)))
            ->unique()
            ->values();

        // Calculate system-generated employee code
        $lastEmp = $allEmployees->first();
        $nextNum = $lastEmp ? ((int) preg_replace('/[^0-9]/', '', (string)$lastEmp->employee_id) + 1) : 1001;
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

    public function store(Request $request)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,NULL,id,system_id,' . $systemId,
            'name' => 'required|string|max:191',
            'designation' => 'required|string|max:191',
            'department' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        Employee::create([
            'system_id' => $systemId,
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'designation' => $request->designation,
            'department' => $request->department,
            'phone' => $request->phone,
            'email' => $request->email,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'status' => 'active',
        ]);

        return redirect()->route('employees.index')->with('status', 'Employee registered successfully.');
    }

    public function update(Request $request, int $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $employee = Employee::where('system_id', $systemId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'designation' => 'required|string|max:191',
            'department' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:191',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $employee->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'department' => $request->department,
            'phone' => $request->phone,
            'email' => $request->email,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'status' => $request->status,
        ]);

        return redirect()->route('employees.index')->with('status', 'Employee details updated successfully.');
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $systemId = $user->system_id;

        $employee = Employee::where('system_id', $systemId)->findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('status', 'Employee removed from database.');
    }
}
