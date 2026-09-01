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

        $query = Employee::where('system_id', $systemId);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('employee_id', 'like', "%{$s}%")
                  ->orWhere('designation', 'like', "%{$s}%")
                  ->orWhere('department', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalCount = Employee::where('system_id', $systemId)->count();
        $activeCount = Employee::where('system_id', $systemId)->where('status', 'active')->count();
        $inactiveCount = Employee::where('system_id', $systemId)->where('status', 'inactive')->count();
        $totalPayroll = (float) Employee::where('system_id', $systemId)->where('status', 'active')->sum('salary');

        $employees = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $existingDesignations = Employee::where('system_id', $systemId)
            ->whereNotNull('designation')
            ->where('designation', '!=', '')
            ->distinct()
            ->pluck('designation')
            ->values();

        $existingDepartments = Employee::where('system_id', $systemId)
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->pluck('department')
            ->values();

        // Calculate system-generated employee code
        $lastEmp = Employee::where('system_id', $systemId)->orderBy('id', 'desc')->first();
        $nextNum = $lastEmp ? ((int) preg_replace('/[^0-9]/', '', $lastEmp->employee_id) + 1) : 1001;
        $nextEmpId = 'EMP-' . $nextNum;

        return view('employees.index', compact(
            'employees',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'totalPayroll',
            'nextEmpId',
            'existingDesignations',
            'existingDepartments'
        ));
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
