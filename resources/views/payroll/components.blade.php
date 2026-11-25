<x-erp-layout title="Allowance &amp; Deduction Master">
    <script>
        window.__salaryMasterComponents = @json($components);
    </script>
    <div class="space-y-6 p-4 sm:p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;"
        x-data="{
            typeFilter: '{{ $typeFilter ?? 'all' }}',
            statusFilter: '{{ $statusFilter ?? 'all' }}',
            components: window.__salaryMasterComponents || [],
            filteredComponents() {
                return this.components.filter(c => {
                    const matchType = this.typeFilter === 'all' || c.type === this.typeFilter;
                    const matchStatus = this.statusFilter === 'all' || c.status === this.statusFilter;
                    return matchType && matchStatus;
                });
            },
            openAdd: false,
            openEdit: false,
            openDeleteModal: false,
            deleteAction: '',
            deleteName: '',
            editAction: '',
            openDeleteConfirm(actionUrl, name) {
                this.deleteAction = actionUrl;
                this.deleteName = name || 'this component';
                this.openDeleteModal = true;
            },
            editForm: {
                name: '',
                type: 'allowance',
                description: '',
                status: 'active',
            },
            startEdit(comp) {
                this.editAction = '{{ url('/payroll/components') }}/' + comp.id + '/update';
                this.editForm = {
                    name: comp.name || '',
                    type: comp.type || 'allowance',
                    description: comp.description || '',
                    status: comp.status || 'active',
                };
                this.openEdit = true;
            }
        }">

        {{-- Header --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-lg font-extrabold text-slate-800 tracking-tight">Allowance &amp; Deduction Master</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage standard allowance and deduction components assignable to employee salary profiles.</p>
            </div>
            @if(auth()->user()->hasModulePermission('payroll.components', 'create'))
                <button type="button" @click="openAdd = true" style="background:#a38c29;" class="px-4 py-2.5 rounded-xl text-white text-xs font-bold uppercase tracking-wider shadow-sm hover:opacity-95 transition cursor-pointer shrink-0">
                    + Add Component
                </button>
            @endif
        </div>

        @if(session('status'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Summary Cards (Customer Ledger KPI Card Style) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Card 1: Master Allowances --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
                <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Master Allowances</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm">
                        Earnings
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block">
                        {{ $allowanceCount }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Configured Allowance Heads</p>
                </div>
            </div>

            {{-- Card 2: Master Deductions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-300 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
                <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Master Deductions</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm">
                        Deductions
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block">
                        {{ $deductionCount }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Configured Deduction Heads</p>
                </div>
            </div>

            {{-- Card 3: Active Components --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
                <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] border border-amber-200/60 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Active Components</span>
                    </div>
                    <span class="shrink-0 whitespace-nowrap text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm">
                        Active
                    </span>
                </div>
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                        {{ $activeCount }}
                    </span>
                    <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Available for Employee Salaries</p>
                </div>
            </div>
        </div>

        {{-- Filter Bar (Instant Client-Side Tab Filtering) --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <button type="button" @click="typeFilter = 'all'"
                        :class="typeFilter === 'all' ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-xl font-bold transition cursor-pointer">
                    All Components
                </button>
                <button type="button" @click="typeFilter = 'allowance'"
                        :class="typeFilter === 'allowance' ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-xl font-bold transition cursor-pointer">
                    Allowances
                </button>
                <button type="button" @click="typeFilter = 'deduction'"
                        :class="typeFilter === 'deduction' ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-xl font-bold transition cursor-pointer">
                    Deductions
                </button>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <button type="button" @click="statusFilter = 'all'"
                        :class="statusFilter === 'all' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-xl font-semibold transition cursor-pointer">All Status</button>
                <button type="button" @click="statusFilter = 'active'"
                        :class="statusFilter === 'active' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-xl font-semibold transition cursor-pointer">Active</button>
                <button type="button" @click="statusFilter = 'inactive'"
                        :class="statusFilter === 'inactive' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-xl font-semibold transition cursor-pointer">Inactive</button>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="erp-table-header text-[10px] font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Component Name</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(comp, idx) in filteredComponents()" :key="comp.id">
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3 text-slate-400 font-mono" x-text="idx + 1"></td>
                                <td class="px-4 py-3">
                                    <p class="font-bold text-slate-800" x-text="comp.name"></p>
                                    <template x-if="comp.description">
                                        <p class="text-[11px] text-slate-500" x-text="comp.description"></p>
                                    </template>
                                </td>
                                <td class="px-4 py-3">
                                    <template x-if="comp.type === 'allowance'">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Allowance</span>
                                    </template>
                                    <template x-if="comp.type !== 'allowance'">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Deduction</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                          :class="comp.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                          x-text="comp.status ? (comp.status.charAt(0).toUpperCase() + comp.status.slice(1)) : ''">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        @if(auth()->user()->hasModulePermission('payroll.components', 'edit'))
                                            <button type="button" @click="startEdit(comp)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Component">
                                                <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                        @endif
                                        @if(auth()->user()->hasModulePermission('payroll.components', 'delete'))
                                            <button type="button" @click="openDeleteConfirm('{{ url('/payroll/components') }}/' + comp.id + '/delete', comp.name)" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Component">
                                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="filteredComponents().length === 0">
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">No salary components match the selected filter.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Modal --}}
        <div x-show="openAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div @click.away="openAdd = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Add Allowance / Deduction Component</h3>
                    <button type="button" @click="openAdd = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <form action="{{ route('payroll.components.store') }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Component Name *</label>
                            <input type="text" name="name" required placeholder="e.g. Medical Allowance" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Type *</label>
                            <select name="type" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                <option value="allowance">Allowance</option>
                                <option value="deduction">Deduction</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Status *</label>
                            <select name="status" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                            <input type="text" name="description" placeholder="Brief description of this component" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 text-slate-900">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="openAdd = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Component</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div @click.away="openEdit = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Edit Component</h3>
                    <button type="button" @click="openEdit = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <form :action="editAction" method="POST" class="p-6 space-y-4 text-xs font-sans">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Component Name *</label>
                            <input type="text" name="name" x-model="editForm.name" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Type *</label>
                            <select name="type" x-model="editForm.type" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                <option value="allowance">Allowance</option>
                                <option value="deduction">Deduction</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Status *</label>
                            <select name="status" x-model="editForm.status" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Description</label>
                            <input type="text" name="description" x-model="editForm.description" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 text-slate-900">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Update Component</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openDeleteModal = false">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONFIRMATION</span>
                        <h3 class="font-black text-base uppercase tracking-wider text-white">DELETE COMPONENT</h3>
                    </div>
                    <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <div class="p-6 space-y-4 text-center font-sans">
                    <p class="text-xs font-semibold text-slate-600">Are you sure you want to remove <span class="font-bold text-slate-900" x-text="deleteName"></span>?</p>
                    <form :action="deleteAction" method="POST" class="flex justify-center gap-3 pt-2">
                        @csrf
                        <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-erp-layout>
