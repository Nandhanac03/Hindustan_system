<x-erp-layout title="Employee Master">
<script>
function employeeMasterComponent() {
    return {
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        openViewModal: false,
        search: '',
        selectedDept: '',
        selectedDesig: '',
        selectedStatus: '',
        deptOpen: false,
        desigOpen: false,
        statusOpen: false,
        existingDepartments: @json($existingDepartments),
        existingDesignations: @json($existingDesignations),
        employees: @json($employeesArray),
        
        currentPage: 1,
        perPage: 15,

        viewEmployee: { id: null, employee_id: '', name: '', designation: '', department: '', phone: '', email: '', joining_date: '', salary: '', status: 'active' },
        editEmployee: { id: null, employee_id: '', name: '', designation: '', department: '', phone: '', email: '', joining_date: '', salary: '', status: 'active' },
        deleteEmployee: { id: null, name: '' },

        get filteredEmployees() {
            const s = (this.search || '').toLowerCase().trim();
            const dept = (this.selectedDept || '').trim();
            const desig = (this.selectedDesig || '').trim();
            const status = (this.selectedStatus || '').trim();

            return this.employees.filter(emp => {
                const matchSearch = !s || 
                    (emp.name && emp.name.toLowerCase().includes(s)) ||
                    (emp.employee_id && emp.employee_id.toLowerCase().includes(s)) ||
                    (emp.designation && emp.designation.toLowerCase().includes(s)) ||
                    (emp.department && emp.department.toLowerCase().includes(s)) ||
                    (emp.phone && emp.phone.toLowerCase().includes(s)) ||
                    (emp.email && emp.email.toLowerCase().includes(s));

                const matchDept = !dept || emp.department === dept;
                const matchDesig = !desig || emp.designation === desig;
                const matchStatus = !status || emp.status === status;

                return matchSearch && matchDept && matchDesig && matchStatus;
            });
        },

        get totalFiltered() {
            return this.filteredEmployees.length;
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.totalFiltered / this.perPage));
        },

        get paginatedEmployees() {
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredEmployees.slice(start, start + this.perPage);
        },

        get selectedDeptName() {
            return this.selectedDept || 'All Departments';
        },

        get selectedDesigName() {
            return this.selectedDesig || 'All Designations';
        },

        get selectedStatusName() {
            if (this.selectedStatus === 'active') return 'Active';
            if (this.selectedStatus === 'inactive') return 'Inactive';
            return 'All Statuses';
        },

        resetFilters() {
            this.search = '';
            this.selectedDept = '';
            this.selectedDesig = '';
            this.selectedStatus = '';
            this.deptOpen = false;
            this.desigOpen = false;
            this.statusOpen = false;
            this.currentPage = 1;
        },

        goToPage(p) {
            if (p >= 1 && p <= this.totalPages) {
                this.currentPage = p;
            }
        },

        initView(emp) {
            this.viewEmployee = { ...emp };
            this.openViewModal = true;
        },
        initEdit(emp) {
            const copy = { ...emp };
            ['joining_date', 'date_of_birth', 'confirmation_date', 'probation_end_date'].forEach(f => {
                if (copy[f]) {
                    copy[f] = String(copy[f]).substring(0, 10);
                } else {
                    copy[f] = '';
                }
            });
            if (!copy.first_name && copy.name) {
                const parts = String(copy.name).trim().split(/\s+/);
                copy.first_name = parts.shift() || '';
                if (!copy.last_name) copy.last_name = parts.join(' ');
            }
            if (!copy.work_email && copy.email) copy.work_email = copy.email;
            if (!copy.portal_email && copy.email) copy.portal_email = copy.email;
            copy.salary = copy.salary !== null && copy.salary !== undefined ? parseFloat(copy.salary) : '';
            copy.basic_salary = copy.basic_salary !== null && copy.basic_salary !== undefined ? parseFloat(copy.basic_salary) : '';
            copy.hra = copy.hra !== null && copy.hra !== undefined ? parseFloat(copy.hra) : '';
            copy.allowances = copy.allowances !== null && copy.allowances !== undefined ? parseFloat(copy.allowances) : '';
            copy.deductions = copy.deductions !== null && copy.deductions !== undefined ? parseFloat(copy.deductions) : '';
            this.editEmployee = copy;
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
    };
}
</script>

<div class="space-y-6 p-6" x-data="employeeMasterComponent()">
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
            @if(auth()->user()->hasModulePermission('employees.manage', 'create'))
            <button @click="openAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Employee</span>
            </button>
            @endif
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

    <!-- Metrics (Customer Ledger KPI Card Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Workforce (Gold) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] border border-amber-200/60 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Workforce</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm">
                    Staff
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    {{ $totalCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Registered Personnel</p>
            </div>
        </div>

        <!-- Active Staff (Green) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Active Staff</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm">
                    Active
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block">
                    {{ $activeCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">On Active Duty</p>
            </div>
        </div>

        <!-- Inactive Staff (Red) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-300 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Inactive Staff</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm">
                    Inactive
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block">
                    {{ $inactiveCount }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Separated / On Hold</p>
            </div>
        </div>
    </div>

    {{-- Global Filter Panel (Zero Page Refresh) --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
                {{-- Search Input with Global Search Style --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Search Code, Name, Phone, Email..." 
                           class="erp-search-input w-full"
                           @keydown.escape="search = ''; currentPage = 1">
                    <template x-if="search">
                        <button type="button" @click="search = ''; currentPage = 1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-rose-500 cursor-pointer" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </template>
                </div>

                {{-- Department Dropdown Filter (Custom Popover) --}}
                <div class="relative" @click.outside="deptOpen = false">
                    <div @click="deptOpen = !deptOpen; desigOpen = false; statusOpen = false"
                         class="erp-dropdown-trigger cursor-pointer"
                         :class="deptOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/>
                            </svg>
                            <span class="truncate font-bold text-slate-800" x-text="selectedDeptName"></span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="deptOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="deptOpen" x-cloak class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectedDept = ''; deptOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="!selectedDept ? 'selected-all' : ''">
                                <span>All Departments</span>
                            </div>
                            <template x-for="dept in existingDepartments" :key="dept">
                                <div @click="selectedDept = dept; deptOpen = false; currentPage = 1"
                                     class="erp-dropdown-option cursor-pointer"
                                     :class="selectedDept === dept ? 'selected' : ''">
                                    <span x-text="dept"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Designation Dropdown Filter (Custom Popover) --}}
                <div class="relative" @click.outside="desigOpen = false">
                    <div @click="desigOpen = !desigOpen; deptOpen = false; statusOpen = false"
                         class="erp-dropdown-trigger cursor-pointer"
                         :class="desigOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="truncate font-bold text-slate-800" x-text="selectedDesigName"></span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="desigOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="desigOpen" x-cloak class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectedDesig = ''; desigOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="!selectedDesig ? 'selected-all' : ''">
                                <span>All Designations</span>
                            </div>
                            <template x-for="desig in existingDesignations" :key="desig">
                                <div @click="selectedDesig = desig; desigOpen = false; currentPage = 1"
                                     class="erp-dropdown-option cursor-pointer"
                                     :class="selectedDesig === desig ? 'selected' : ''">
                                    <span x-text="desig"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Status Dropdown Filter (Custom Popover) --}}
                <div class="relative" @click.outside="statusOpen = false">
                    <div @click="statusOpen = !statusOpen; deptOpen = false; desigOpen = false"
                         class="erp-dropdown-trigger cursor-pointer"
                         :class="statusOpen ? 'active' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/>
                            </svg>
                            <span class="truncate font-bold text-slate-800" x-text="selectedStatusName"></span>
                        </div>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-[#a38c29] shrink-0" :class="statusOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div x-show="statusOpen" x-cloak class="erp-dropdown-popover" style="display: none;">
                        <div class="overflow-y-auto divide-y divide-slate-100 max-h-52">
                            <div @click="selectedStatus = ''; statusOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="!selectedStatus ? 'selected-all' : ''">
                                <span>All Statuses</span>
                            </div>
                            <div @click="selectedStatus = 'active'; statusOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="selectedStatus === 'active' ? 'selected' : ''">
                                <span>Active</span>
                            </div>
                            <div @click="selectedStatus = 'inactive'; statusOpen = false; currentPage = 1"
                                 class="erp-dropdown-option cursor-pointer"
                                 :class="selectedStatus === 'inactive' ? 'selected' : ''">
                                <span>Inactive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reset Filters Button (Zero Page Refresh) --}}
        <button type="button" @click="resetFilters()"
           class="inline-flex items-center justify-center gap-2 rounded-xl theme-btn px-5 h-[38px] text-xs font-extrabold flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer whitespace-nowrap">
            <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Reset Filters</span>
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                    <tr class="erp-table-header border-b border-slate-700 text-left">
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600 w-14 text-center">ID</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">EMPLOYEE CODE</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">FULL NAME</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">DESIGNATION</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">DEPARTMENT</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">CONTACT</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600">JOINING DATE</th>
                        <th class="px-4 py-3.5 erp-table-header border-r border-slate-600 text-center">STATUS</th>
                        <th class="px-4 py-3.5 erp-table-header text-right pr-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <template x-for="emp in paginatedEmployees" :key="emp.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5 font-bold text-slate-400 font-mono text-center" x-text="'#' + emp.id"></td>
                            <td class="px-4 py-3.5 font-bold font-mono text-[#a38c29]">
                                <a :href="'{{ url('/employees') }}/' + emp.id" class="hover:underline" x-text="emp.employee_id"></a>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900">
                                <a :href="'{{ url('/employees') }}/' + emp.id" class="hover:text-[#0f5253] hover:underline transition" x-text="emp.name"></a>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-slate-800" x-text="emp.designation"></td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200" x-text="emp.department || 'General'">
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-700" x-text="emp.phone || '—'"></div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[140px]" x-text="emp.email || ''"></div>
                            </td>
                            <td class="px-4 py-3.5 font-mono text-slate-600" x-text="formatDate(emp.joining_date)"></td>
                            <td class="px-4 py-3.5 text-center">
                                <template x-if="emp.status === 'active'">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                </template>
                                <template x-if="emp.status !== 'active'">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                                </template>
                            </td>
                            <td class="px-4 py-3.5 text-right pr-4 whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    {{-- View Full Profile Trigger --}}
                                    <a :href="'{{ url('/employees') }}/' + emp.id" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Employee Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    
                                    {{-- Edit Trigger --}}
                                    @if(auth()->user()->hasModulePermission('employees.manage', 'edit'))
                                    <button type="button" @click="initEdit(emp)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Employee">
                                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @endif

                                    {{-- Delete Trigger --}}
                                    @if(auth()->user()->hasModulePermission('employees.manage', 'delete'))
                                    <button type="button" @click="initDelete(emp)" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Employee">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="paginatedEmployees.length === 0">
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic">No employee records match the selected filters.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Dynamic Client Pagination Bar --}}
        <div x-show="totalFiltered > 0" class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500 font-medium">
                Showing <span class="font-bold text-slate-800" x-text="((currentPage - 1) * perPage) + 1"></span> to 
                <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, totalFiltered)"></span> of 
                <span class="font-bold text-slate-800" x-text="totalFiltered"></span> employees
            </div>

            <div class="flex items-center gap-1.5" x-show="totalPages > 1">
                <button type="button" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200 text-slate-700 cursor-pointer'"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-bold transition bg-white">
                    Previous
                </button>

                <template x-for="page in totalPages" :key="page">
                    <button type="button" @click="goToPage(page)"
                            :class="currentPage === page ? 'bg-[#a38c29] text-white border-[#a38c29] shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 border-slate-300'"
                            class="w-8 h-8 rounded-lg border text-xs font-bold transition flex items-center justify-center cursor-pointer"
                            x-text="page">
                    </button>
                </template>

                <button type="button" @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-200 text-slate-700 cursor-pointer'"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-bold transition bg-white">
                    Next
                </button>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div x-show="openViewModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openViewModal = false">
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
        <div x-data="{ addTab: 'personal' }" class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openAddModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">EMPLOYEE MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">ADD EMPLOYEE — COMPLETE PROFILE</h3>
                </div>
                <button type="button" @click="openAddModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            {{-- Section Tabs inside Add Modal --}}
            <div class="border-b border-slate-200 px-6 bg-slate-50 flex items-center gap-1 overflow-x-auto">
                <button type="button" @click="addTab = 'personal'" :class="addTab === 'personal' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">1. Personal &amp; Contact</button>
                <button type="button" @click="addTab = 'employment'" :class="addTab === 'employment' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">2. Employment &amp; Job</button>
                <button type="button" @click="addTab = 'address'" :class="addTab === 'address' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">3. Address</button>
                <button type="button" @click="addTab = 'salary'" :class="addTab === 'salary' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">4. Bank Details</button>
            </div>

            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="max-h-[60vh] overflow-y-auto pr-1 space-y-4">

                    {{-- TAB 1: PERSONAL & CONTACT --}}
                    <div x-show="addTab === 'personal'" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">EMPLOYEE CODE <span class="text-rose-500">*</span></label>
                            <input type="text" name="employee_id" value="{{ $nextEmpId }}" required class="w-full px-3 py-2 text-xs bg-slate-100 border border-slate-200 rounded-xl font-bold font-mono text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">FIRST NAME <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" required placeholder="e.g. Priya" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">LAST NAME</label>
                            <input type="text" name="last_name" placeholder="e.g. Menon" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">GENDER</label>
                            <select name="gender" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                                <option value="">Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DATE OF BIRTH</label>
                            <input type="date" name="date_of_birth" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">NATIONALITY</label>
                            <input type="text" name="nationality" placeholder="e.g. Indian" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">BLOOD GROUP</label>
                            <input type="text" name="blood_group" placeholder="e.g. O+" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">MOBILE PHONE</label>
                            <input type="text" name="phone" placeholder="+91 9876543210" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ALTERNATE PHONE</label>
                            <input type="text" name="alternate_phone" placeholder="+91 9876509999" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">WORK EMAIL</label>
                            <input type="email" name="work_email" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PERSONAL EMAIL</label>
                            <input type="email" name="personal_email" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PROFILE PHOTO</label>
                            <input type="file" name="photo" accept="image/*" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl">
                        </div>
                    </div>

                    {{-- TAB 2: EMPLOYMENT & JOB --}}
                    <div x-show="addTab === 'employment'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">POSITION / DESIGNATION <span class="text-rose-500">*</span></label>
                            <input type="text" name="designation" list="empDesignationOptions" required placeholder="e.g. Civil Engineer" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DEPARTMENT</label>
                            <input type="text" name="department" list="empDepartmentOptions" placeholder="e.g. Human Resources" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">BRANCH</label>
                            <input type="text" name="branch" placeholder="e.g. Kochi Development Center" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">JOINING DATE <span class="text-rose-500">*</span></label>
                            <input type="date" name="joining_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">WORK LOCATION</label>
                            <input type="text" name="work_location" placeholder="e.g. Head Office" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">STATUS</label>
                            <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                    </div>

                    {{-- TAB 3: ADDRESS --}}
                    <div x-show="addTab === 'address'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ADDRESS LINE 1</label>
                            <input type="text" name="address_line_1" placeholder="Flat / House / Street" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ADDRESS LINE 2</label>
                            <input type="text" name="address_line_2" placeholder="Area / Landmark" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">COUNTRY</label>
                            <input type="text" name="country" placeholder="e.g. India" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">STATE / PROVINCE / REGION</label>
                            <input type="text" name="state" placeholder="e.g. Kerala" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">CITY</label>
                            <input type="text" name="city" placeholder="e.g. Kochi" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">POSTAL CODE</label>
                            <input type="text" name="postal_code" placeholder="e.g. 560103" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                    </div>

                    {{-- TAB 4: BANK DETAILS --}}
                    <div x-show="addTab === 'salary'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">BANK NAME</label>
                            <input type="text" name="bank_name" placeholder="e.g. HDFC Bank" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ACCOUNT NUMBER</label>
                            <input type="text" name="bank_account_number" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">IFSC CODE</label>
                            <input type="text" name="ifsc_code" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono uppercase text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAN NUMBER</label>
                            <input type="text" name="pan_number" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono uppercase text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">UAN / PF NUMBER</label>
                            <input type="text" name="uan_number" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900">
                        </div>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-slate-100">
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 cursor-pointer">
                        <input type="checkbox" name="open_profile" value="1" checked class="rounded border-slate-300 text-[#a38c29]">
                        <span>Open full Employee Details profile after saving</span>
                    </label>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="openAddModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">SAVE EMPLOYEE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div x-data="{ editTab: 'personal' }" class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openEditModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between relative overflow-hidden border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">EMPLOYEE MASTER</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">EDIT EMPLOYEE — COMPLETE PROFILE</h3>
                </div>
                <button type="button" @click="openEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>

            {{-- Section Tabs inside Edit Modal --}}
            <div class="border-b border-slate-200 px-6 bg-slate-50 flex items-center gap-1 overflow-x-auto">
                <button type="button" @click="editTab = 'personal'" :class="editTab === 'personal' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">1. Personal &amp; Contact</button>
                <button type="button" @click="editTab = 'employment'" :class="editTab === 'employment' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">2. Employment &amp; Job</button>
                <button type="button" @click="editTab = 'address'" :class="editTab === 'address' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">3. Address</button>
                <button type="button" @click="editTab = 'salary'" :class="editTab === 'salary' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 font-bold'" class="px-3 py-2.5 text-[11px] uppercase tracking-wider border-b-2 whitespace-nowrap cursor-pointer">4. Bank Details</button>
            </div>

            <form :action="'{{ url('/employees') }}/' + editEmployee.id + '/update'" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs font-sans">
                @csrf
                <div class="max-h-[60vh] overflow-y-auto pr-1 space-y-4">

                    {{-- TAB 1: PERSONAL & CONTACT --}}
                    <div x-show="editTab === 'personal'" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">EMPLOYEE CODE</label>
                            <input type="text" readonly :value="editEmployee.employee_id" class="w-full px-3 py-2 text-xs bg-slate-100 border border-slate-200 rounded-xl font-bold font-mono text-slate-700 focus:outline-none cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">FIRST NAME <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" required x-model="editEmployee.first_name" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">LAST NAME</label>
                            <input type="text" name="last_name" x-model="editEmployee.last_name" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-[#a38c29] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">GENDER</label>
                            <select name="gender" x-model="editEmployee.gender" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                                <option value="">Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DATE OF BIRTH</label>
                            <input type="date" name="date_of_birth" x-model="editEmployee.date_of_birth" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">NATIONALITY</label>
                            <input type="text" name="nationality" x-model="editEmployee.nationality" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">BLOOD GROUP</label>
                            <input type="text" name="blood_group" x-model="editEmployee.blood_group" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">MOBILE PHONE</label>
                            <input type="text" name="phone" x-model="editEmployee.phone" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ALTERNATE PHONE</label>
                            <input type="text" name="alternate_phone" x-model="editEmployee.alternate_phone" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">WORK EMAIL</label>
                            <input type="email" name="work_email" x-model="editEmployee.work_email" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PERSONAL EMAIL</label>
                            <input type="email" name="personal_email" x-model="editEmployee.personal_email" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">UPDATE PROFILE PHOTO</label>
                            <input type="file" name="photo" accept="image/*" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl">
                        </div>
                    </div>

                    {{-- TAB 2: EMPLOYMENT & JOB --}}
                    <div x-show="editTab === 'employment'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">POSITION / DESIGNATION <span class="text-rose-500">*</span></label>
                            <input type="text" name="designation" list="empDesignationOptions" required x-model="editEmployee.designation" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">DEPARTMENT</label>
                            <input type="text" name="department" list="empDepartmentOptions" x-model="editEmployee.department" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">BRANCH</label>
                            <input type="text" name="branch" x-model="editEmployee.branch" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">JOINING DATE <span class="text-rose-500">*</span></label>
                            <input type="date" name="joining_date" required x-model="editEmployee.joining_date" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">WORK LOCATION</label>
                            <input type="text" name="work_location" x-model="editEmployee.work_location" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">STATUS <span class="text-rose-500">*</span></label>
                            <select name="status" required x-model="editEmployee.status" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 cursor-pointer">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="on_leave">On Leave</option>
                                <option value="terminated">Terminated</option>
                            </select>
                        </div>
                    </div>

                    {{-- TAB 3: ADDRESS --}}
                    <div x-show="editTab === 'address'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ADDRESS LINE 1</label>
                            <input type="text" name="address_line_1" x-model="editEmployee.address_line_1" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ADDRESS LINE 2</label>
                            <input type="text" name="address_line_2" x-model="editEmployee.address_line_2" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">COUNTRY</label>
                            <input type="text" name="country" x-model="editEmployee.country" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">STATE / PROVINCE / REGION</label>
                            <input type="text" name="state" x-model="editEmployee.state" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">CITY</label>
                            <input type="text" name="city" x-model="editEmployee.city" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">POSTAL CODE</label>
                            <input type="text" name="postal_code" x-model="editEmployee.postal_code" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                    </div>

                    {{-- TAB 4: BANK DETAILS --}}
                    <div x-show="editTab === 'salary'" style="display: none;" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <input type="hidden" name="salary" :value="editEmployee.salary ?? 0">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">BANK NAME</label>
                            <input type="text" name="bank_name" x-model="editEmployee.bank_name" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">ACCOUNT NUMBER</label>
                            <input type="text" name="bank_account_number" x-model="editEmployee.bank_account_number" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">IFSC CODE</label>
                            <input type="text" name="ifsc_code" x-model="editEmployee.ifsc_code" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono uppercase text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">PAN NUMBER</label>
                            <input type="text" name="pan_number" x-model="editEmployee.pan_number" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono uppercase text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">UAN / PF NUMBER</label>
                            <input type="text" name="uan_number" x-model="editEmployee.uan_number" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-bold font-mono text-slate-900">
                        </div>
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
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openDeleteModal = false">
            <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                <div>
                    <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONFIRMATION</span>
                    <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE EMPLOYEE</h3>
                </div>
                <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-4 text-center">
                <p class="text-xs font-semibold text-slate-600">Are you sure you want to remove <span class="font-bold text-slate-900" x-text="deleteEmployee.name"></span>?</p>
                <form :action="'{{ url('/employees') }}/' + deleteEmployee.id + '/delete'" method="POST" class="flex justify-center gap-3 pt-2">
                    @csrf
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Datalists populated ONLY from existing database records --}}
    <datalist id="empDesignationOptions">
        <template x-for="desig in existingDesignations" :key="desig">
            <option :value="desig"></option>
        </template>
    </datalist>

    <datalist id="empDepartmentOptions">
        <template x-for="dept in existingDepartments" :key="dept">
            <option :value="dept"></option>
        </template>
    </datalist>
</div>
</x-erp-layout>
