<x-erp-layout>
    <x-slot:title>Employees Master - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Employees > Employees Master</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="employeeDirectory()">
        <!-- Section Header -->
        <div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Employee & Worker Directory</h1>
                <p class="text-xs text-slate-450 mt-1">Manage staff records, designations, departments, contact details, and monthly payroll registers for Tabasco company.</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold" x-text="'Active Staff: ' + activeCount"></span>
                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold" x-text="'Monthly Payroll: ₹' + formatCurrency(totalPayroll)"></span>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold rounded-2xl shadow-2xs">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-250 text-rose-800 text-xs font-bold rounded-2xl shadow-2xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Left Card: Register New Employee Form -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-white border-b border-slate-100">
                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Register New Employee</h3>
                </div>
                <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <!-- Employee ID -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Employee ID <span class="text-slate-400 font-normal lowercase">(Auto Generated)</span></label>
                        <input type="text" name="employee_id" value="{{ $nextEmpId }}" readonly
                               class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono font-bold focus:outline-none">
                    </div>

                    <!-- Name -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Rajesh Kumar"
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                    </div>

                    <!-- Designation -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Designation <span class="text-rose-500">*</span></label>
                        <select name="designation" required
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                            <option value="">Select Designation</option>
                            <option value="Site Supervisor">Site Supervisor</option>
                            <option value="Project Manager">Project Manager</option>
                            <option value="Accountant">Accountant</option>
                            <option value="Civil Engineer">Civil Engineer</option>
                            <option value="Store Keeper">Store Keeper</option>
                            <option value="Security Guard">Security Guard</option>
                            <option value="Labor Lead">Labor Lead</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Department</label>
                        <select name="department"
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                            <option value="Construction">Construction / Site</option>
                            <option value="Finance & Accounts">Finance & Accounts</option>
                            <option value="Purchase & Stores">Purchase & Stores</option>
                            <option value="Administration">Administration</option>
                        </select>
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Phone Number</label>
                        <input type="text" name="phone" placeholder="e.g. +91 9876543210"
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Email Address</label>
                        <input type="email" name="email" placeholder="e.g. rajesh@tabasco.com"
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                    </div>

                    <!-- Joining Date -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Joining Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="joining_date" required value="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                    </div>

                    <!-- Monthly Salary -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Monthly Salary (₹) <span class="text-rose-500">*</span></label>
                        <input type="number" name="salary" required min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition shadow-sm uppercase tracking-wider">
                            Register Employee
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Card: Employee Directory List (Span 2) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Registered Workers Directory</h3>
                    
                    <!-- Search Input -->
                    <input type="text" x-model="searchQuery" placeholder="Search by name, ID or role..."
                           class="w-64 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:bg-white focus:border-blue-500 transition">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-55 bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                <th class="px-5 py-4">ID / Worker Name</th>
                                <th class="px-5 py-4">Role / Department</th>
                                <th class="px-5 py-4">Contact Details</th>
                                <th class="px-5 py-4 text-right">Joining Date</th>
                                <th class="px-5 py-4 text-right">Salary (₹)</th>
                                <th class="px-5 py-4 text-center">Status</th>
                                <th class="px-5 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            <template x-for="emp in filteredEmployees()" :key="emp.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-4">
                                        <div class="font-mono font-bold text-blue-600 text-[10px]" x-text="emp.employee_id"></div>
                                        <div class="font-bold text-slate-800 text-xs mt-0.5" x-text="emp.name"></div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-750" x-text="emp.designation"></div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5" x-text="emp.department || 'N/A'"></div>
                                    </td>
                                    <td class="px-5 py-4 text-[10px]">
                                        <div class="font-semibold text-slate-600" x-text="emp.phone || 'No Phone'"></div>
                                        <div class="text-slate-400" x-text="emp.email || 'No Email'"></div>
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-slate-500" x-text="formatDate(emp.joining_date)"></td>
                                    <td class="px-5 py-4 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(emp.salary)"></td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-lg text-[9px] font-extrabold uppercase border"
                                              :class="emp.status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-550 border-slate-200'"
                                              x-text="emp.status"></span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2.5">
                                            <!-- Edit Trigger -->
                                            <button type="button" @click="editEmployee(emp)" class="text-slate-400 hover:text-blue-600 transition focus:outline-none">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            
                                            <!-- Delete -->
                                            <form :action="'/employees/' + emp.id" method="POST" onsubmit="return confirm('Are you sure you want to remove this employee?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition focus:outline-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredEmployees().length === 0">
                                <td colspan="7" class="px-6 py-12 text-center text-slate-450 font-bold">
                                    No employee records match the search query.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Popup Modal -->
        <div class="fixed inset-0 z-50 overflow-y-auto" x-show="showEditModal" style="display: none;" x-transition>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-2xs" @click="showEditModal = false"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden max-w-lg w-full relative z-10">
                    <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Edit Employee Details</h3>
                        <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form :action="'/employees/' + editForm.id" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Employee ID (Readonly) -->
                            <div class="col-span-2 space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Employee ID</label>
                                <input type="text" readonly :value="editForm.employee_id"
                                       class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 font-mono font-bold focus:outline-none">
                            </div>

                            <!-- Name -->
                            <div class="col-span-2 space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required x-model="editForm.name"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Designation -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Designation <span class="text-rose-500">*</span></label>
                                <select name="designation" required x-model="editForm.designation"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                    <option value="Site Supervisor">Site Supervisor</option>
                                    <option value="Project Manager">Project Manager</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="Civil Engineer">Civil Engineer</option>
                                    <option value="Store Keeper">Store Keeper</option>
                                    <option value="Security Guard">Security Guard</option>
                                    <option value="Labor Lead">Labor Lead</option>
                                </select>
                            </div>

                            <!-- Department -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Department</label>
                                <select name="department" x-model="editForm.department"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                    <option value="Construction">Construction / Site</option>
                                    <option value="Finance & Accounts">Finance & Accounts</option>
                                    <option value="Purchase & Stores">Purchase & Stores</option>
                                    <option value="Administration">Administration</option>
                                </select>
                            </div>

                            <!-- Phone -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Phone Number</label>
                                <input type="text" name="phone" x-model="editForm.phone"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Email Address</label>
                                <input type="email" name="email" x-model="editForm.email"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Joining Date -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Joining Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="joining_date" required x-model="editForm.joining_date"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Monthly Salary -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Monthly Salary (₹) <span class="text-rose-500">*</span></label>
                                <input type="number" name="salary" required min="0" step="0.01" x-model.number="editForm.salary"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Status -->
                            <div class="col-span-2 space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Employment Status <span class="text-rose-500">*</span></label>
                                <select name="status" required x-model="editForm.status"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="showEditModal = false"
                                    class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition shadow-sm uppercase tracking-wider">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function employeeDirectory() {
            return {
                employees: @json($employees),
                searchQuery: '',
                showEditModal: false,
                activeCount: {{ $employees->where('status', 'active')->count() }},
                totalPayroll: {{ $employees->where('status', 'active')->sum('salary') }},
                editForm: {
                    id: '',
                    employee_id: '',
                    name: '',
                    designation: '',
                    department: '',
                    phone: '',
                    email: '',
                    joining_date: '',
                    salary: 0.00,
                    status: 'active'
                },
                filteredEmployees() {
                    if (!this.searchQuery) return this.employees;
                    const q = this.searchQuery.toLowerCase();
                    return this.employees.filter(emp => {
                        return emp.name.toLowerCase().includes(q) ||
                               emp.employee_id.toLowerCase().includes(q) ||
                               emp.designation.toLowerCase().includes(q) ||
                               (emp.department && emp.department.toLowerCase().includes(q));
                    });
                },
                editEmployee(emp) {
                    this.editForm.id = emp.id;
                    this.editForm.employee_id = emp.employee_id;
                    this.editForm.name = emp.name;
                    this.editForm.designation = emp.designation;
                    this.editForm.department = emp.department || '';
                    this.editForm.phone = emp.phone || '';
                    this.editForm.email = emp.email || '';
                    
                    // Format date for html input yyyy-mm-dd
                    const dt = new Date(emp.joining_date);
                    this.editForm.joining_date = emp.joining_date.substring(0, 10);
                    
                    this.editForm.salary = parseFloat(emp.salary) || 0.00;
                    this.editForm.status = emp.status;
                    this.showEditModal = true;
                },
                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const parts = dateStr.substring(0, 10).split('-');
                    if (parts.length === 3) {
                        return parts[2] + '/' + parts[1] + '/' + parts[0];
                    }
                    return dateStr;
                },
                formatCurrency(val) {
                    return Number(parseFloat(val).toFixed(2)).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }
    </script>
</x-erp-layout>
