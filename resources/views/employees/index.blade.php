<x-erp-layout title="Employee Master">
<div class="space-y-6 p-6" x-data="{
    openAddModal: false,
    openEditModal: false,
    openDeleteModal: false,
    openViewModal: false,
    viewEmployee: { id: null, employee_id: '', name: '', designation: '', department: '', phone: '', email: '', joining_date: '', salary: '', status: 'active' },
    editEmployee: { id: null, employee_id: '', name: '', designation: '', department: '', phone: '', email: '', joining_date: '', salary: '', status: 'active' },
    deleteEmployee: { id: null, name: '' },
    initView(emp) {
        this.viewEmployee = { ...emp };
        this.openViewModal = true;
    },
    initEdit(emp) {
        this.editEmployee = { ...emp };
        if (emp.joining_date) {
            this.editEmployee.joining_date = String(emp.joining_date).substring(0, 10);
        }
        this.editEmployee.salary = emp.salary ? Math.round(parseFloat(emp.salary)) : '';
        this.openEditModal = true;
    },
    initDelete(emp) {
        this.deleteEmployee = { ...emp };
        this.openDeleteModal = true;
    },
    formatDate(dateStr) {
        if (!dateStr) return '—';
        const parts = String(dateStr).substring(0, 10).split('-');
        if (parts.length === 3) {
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }
        return dateStr;
    },
    formatCurrency(val) {
        return Number(parseFloat(val || 0).toFixed(2)).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Employee Master</h1>
                    <p class="text-xs text-slate-500 font-medium">Manage employee profiles, designations, department allocations, and monthly payroll registers</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Employee</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('status') || session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-3 shadow-xs">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('status') ?? session('success') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-xs">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Metrics (Border-left style cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Workforce (Gold) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#a38c29] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#a38c29]/50">
            <p class="text-[11px] font-bold text-[#a38c29] uppercase tracking-wider mb-1">Total Workforce</p>
            <h4 class="text-[22px] font-bold text-[#8a7522] m-0">{{ $totalCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Registered Personnel</p>
        </div>
        <!-- Active Staff (Green) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#10b981] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#10b981]/30">
            <p class="text-[11px] font-bold text-[#10b981] uppercase tracking-wider mb-1">Active Staff</p>
            <h4 class="text-[22px] font-bold text-[#10b981] m-0">{{ $activeCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">On Active Duty</p>
        </div>
        <!-- Inactive Staff (Red) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-[#ef4444] p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-[#ef4444]/30">
            <p class="text-[11px] font-bold text-[#ef4444] uppercase tracking-wider mb-1">Inactive Staff</p>
            <h4 class="text-[22px] font-bold text-[#ef4444] m-0">{{ $inactiveCount }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Separated / On Hold</p>
        </div>
        <!-- Monthly Payroll (Gray/Black) -->
        <div class="bg-white rounded-lg border border-gray-200 border-l-4 border-l-gray-700 p-4 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-gray-300 group">
            <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1 group-hover:text-[#a38c29] transition-colors duration-300">Monthly Payroll</p>
            <h4 class="text-[22px] font-bold text-gray-800 m-0 group-hover:text-[#a38c29] transition-colors duration-300">₹ {{ number_format((float) ($totalPayroll ?? 0), 2) }}</h4>
            <p class="text-[10px] text-gray-500 mt-1">Gross Salary Obligation</p>
        </div>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
        <form method="GET" action="{{ route('employees.index') }}" class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
                {{-- Search Input with Icon --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Code, Name, Phone, Email..." 
                           class="w-full pl-10 @if(request('search')) pr-10 @else pr-4 @endif py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    @if(request('search'))
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <a href="{{ route('employees.index', request()->except('search')) }}"
                           class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Department Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                        </svg>
                    </div>
                    <select name="department" onchange="this.form.submit()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Departments</option>
                        @foreach($existingDepartments as $dept)
                            <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Designation Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <select name="designation" onchange="this.form.submit()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Designations</option>
                        @foreach($existingDesignations as $desig)
                            <option value="{{ $desig }}" {{ request('designation') === $desig ? 'selected' : '' }}>{{ $desig }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Status Filter with Icon --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/>
                        </svg>
                    </div>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            {{-- Reset Filters Button --}}
            <a href="{{ route('employees.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 shrink-0">
                <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset Filters</span>
            </a>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[10px] font-black uppercase tracking-wider text-left">
                        <th class="px-4 py-3.5">ID</th>
                        <th class="px-4 py-3.5">EMPLOYEE CODE</th>
                        <th class="px-4 py-3.5">FULL NAME</th>
                        <th class="px-4 py-3.5">DESIGNATION</th>
                        <th class="px-4 py-3.5">DEPARTMENT</th>
                        <th class="px-4 py-3.5">CONTACT</th>
                        <th class="px-4 py-3.5">JOINING DATE</th>
                        <th class="px-4 py-3.5 text-right">MONTHLY SALARY</th>
                        <th class="px-4 py-3.5 text-center">STATUS</th>
                        <th class="px-4 py-3.5 text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($employees as $emp)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5 font-bold text-slate-400 font-mono">#{{ $emp->id }}</td>
                        <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]">{{ $emp->employee_id }}</td>
                        <td class="px-4 py-3.5 font-bold text-slate-900">{{ $emp->name }}</td>
                        <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $emp->designation }}</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $emp->department ?: 'General' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="font-semibold text-slate-700">{{ $emp->phone ?: '—' }}</div>
                            <div class="text-[10px] text-slate-400 truncate max-w-[140px]">{{ $emp->email ?: '' }}</div>
                        </td>
                        <td class="px-4 py-3.5 font-mono text-slate-600" x-text="formatDate('{{ $emp->joining_date }}')"></td>
                        <td class="px-4 py-3.5 text-right font-mono font-extrabold text-slate-900">₹{{ number_format((float) $emp->salary, 2) }}</td>
                        <td class="px-4 py-3.5 text-center">
                            @if($emp->status === 'active')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right pr-4 whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                {{-- View Trigger --}}
                                <button type="button" @click="initView({{ json_encode($emp) }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                
                                {{-- Edit Trigger --}}
                                <button type="button" @click="initEdit({{ json_encode($emp) }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Employee">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                {{-- Delete Trigger --}}
                                <button type="button" @click="initDelete({{ json_encode($emp) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Employee">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">No employee records configured.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($employees, 'hasPages') && $employees->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{ $employees->links() }}
        </div>
        @endif
    </div>

    <!-- View Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openViewModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">EMPLOYEE MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EMPLOYEE DETAILS</h3>
                </div>
                <button type="button" @click="openViewModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-3.5 text-xs">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">EMPLOYEE CODE</span>
                    <span class="font-bold font-mono text-[#a38c29]" x-text="viewEmployee.employee_id"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">FULL NAME</span>
                    <span class="font-bold text-slate-900" x-text="viewEmployee.name"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">DESIGNATION</span>
                    <span class="font-bold text-slate-800" x-text="viewEmployee.designation"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">DEPARTMENT</span>
                    <span class="font-bold text-slate-800" x-text="viewEmployee.department || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PHONE</span>
                    <span class="font-semibold text-slate-800" x-text="viewEmployee.phone || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">EMAIL</span>
                    <span class="font-semibold text-slate-800" x-text="viewEmployee.email || '—'"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">JOINING DATE</span>
                    <span class="font-bold font-mono text-slate-800" x-text="formatDate(viewEmployee.joining_date)"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">MONTHLY SALARY</span>
                    <span class="font-bold font-mono text-slate-900" x-text="'₹' + formatCurrency(viewEmployee.salary)"></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">STATUS</span>
                    <span class="font-bold" :class="viewEmployee.status === 'active' ? 'text-emerald-600' : 'text-slate-500'" x-text="viewEmployee.status === 'active' ? 'Active' : 'Inactive'"></span>
                </div>
                <div class="flex justify-end pt-3">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="openAddModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openAddModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">EMPLOYEE MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ADD EMPLOYEE</h3>
                </div>
                <button type="button" @click="openAddModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMPLOYEE CODE</label>
                        <input type="text" name="employee_id" value="{{ $nextEmpId }}" readonly class="w-full px-3.5 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl font-bold font-mono text-slate-800 focus:outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">FULL NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Rajesh Kumar" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESIGNATION <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="designation" list="empDesignationOptions" required placeholder="e.g. Civil Engineer" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DEPARTMENT</label>
                        <input type="text" name="department" list="empDepartmentOptions" placeholder="e.g. Construction / Site" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                        <input type="text" name="phone" placeholder="e.g. +91 9876543210" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                        <input type="email" name="email" placeholder="e.g. rajesh@tabasco.com" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">JOINING DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="joining_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">MONTHLY SALARY (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" name="salary" required min="0" step="1" placeholder="e.g. 25000" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">SAVE EMPLOYEE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openEditModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">EMPLOYEE MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EDIT EMPLOYEE</h3>
                </div>
                <button type="button" @click="openEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <form :action="'/employees/' + editEmployee.id" method="POST" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMPLOYEE CODE</label>
                        <input type="text" readonly :value="editEmployee.employee_id" class="w-full px-3.5 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl font-bold font-mono text-slate-700 focus:outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">STATUS <span class="text-rose-500 font-bold">*</span></label>
                        <select name="status" required x-model="editEmployee.status" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none cursor-pointer">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">FULL NAME <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="name" required x-model="editEmployee.name" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DESIGNATION <span class="text-rose-500 font-bold">*</span></label>
                        <input type="text" name="designation" list="empDesignationOptions" required x-model="editEmployee.designation" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">DEPARTMENT</label>
                        <input type="text" name="department" list="empDepartmentOptions" x-model="editEmployee.department" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PHONE NUMBER</label>
                        <input type="text" name="phone" x-model="editEmployee.phone" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">EMAIL ADDRESS</label>
                        <input type="email" name="email" x-model="editEmployee.email" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">JOINING DATE <span class="text-rose-500 font-bold">*</span></label>
                        <input type="date" name="joining_date" required x-model="editEmployee.joining_date" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">MONTHLY SALARY (₹) <span class="text-rose-500 font-bold">*</span></label>
                        <input type="number" name="salary" required min="0" step="1" x-model="editEmployee.salary" placeholder="e.g. 25000" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">UPDATE EMPLOYEE</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all" @click.outside="openDeleteModal = false">
            <div class="bg-rose-950 p-5 text-white flex items-center justify-between border-b border-rose-900">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-rose-900/40 text-rose-200 text-[9px] font-black uppercase tracking-wider rounded border border-rose-800 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE EMPLOYEE</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to remove <span class="font-bold text-slate-900" x-text="deleteEmployee.name"></span>?</p>
                <form :action="'/employees/' + deleteEmployee.id" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Datalists populated ONLY from existing database records --}}
    <datalist id="empDesignationOptions">
        @foreach($existingDesignations as $desig)
            <option value="{{ $desig }}">
        @endforeach
    </datalist>

    <datalist id="empDepartmentOptions">
        @foreach($existingDepartments as $dept)
            <option value="{{ $dept }}">
        @endforeach
    </datalist>
</div>
</x-erp-layout>
