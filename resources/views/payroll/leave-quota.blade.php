<x-erp-layout title="Leave Quotas">
    <script>
        window.__leaveQuotaRows = @json($rows);
        window.__allLeaveQuotasMap = @json($allQuotasMap);
    </script>
    <div class="space-y-5 p-4 sm:p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;"
        x-data="{
            search: '',
            openAddQuotaModal: false,
            modalForm: {
                financial_period_id: '{{ $selectedPeriodId }}',
                employee_id: '{{ $rows[0]['employee_id'] ?? '' }}',
                allowed_leave: 0,
                taken_leave: 0
            },
            rows: window.__leaveQuotaRows || [],
            allQuotasMap: window.__allLeaveQuotasMap || {},
            openAddQuota() {
                this.modalForm = {
                    financial_period_id: '{{ $selectedPeriodId }}',
                    employee_id: this.rows.length ? this.rows[0].employee_id : '',
                    allowed_leave: 0,
                    taken_leave: 0
                };
                this.onModalSelectionChange();
                this.openAddQuotaModal = true;
            },
            onModalSelectionChange() {
                const key = String(this.modalForm.financial_period_id) + '_' + String(this.modalForm.employee_id);
                if (this.allQuotasMap[key]) {
                    this.modalForm.allowed_leave = this.allQuotasMap[key].allowed_leave;
                    this.modalForm.taken_leave = this.allQuotasMap[key].taken_leave;
                    return;
                }
                const found = this.rows.find(r => String(r.employee_id) === String(this.modalForm.employee_id));
                if (found && String(this.modalForm.financial_period_id) === '{{ $selectedPeriodId }}') {
                    this.modalForm.allowed_leave = found.allowed_leave;
                    this.modalForm.taken_leave = found.taken_leave;
                } else {
                    this.modalForm.allowed_leave = 0;
                    this.modalForm.taken_leave = 0;
                }
            },
            filteredRows() {
                const q = (this.search || '').trim().toLowerCase();
                if (!q) return this.rows;
                return this.rows.filter(r =>
                    (r.name || '').toLowerCase().includes(q) ||
                    (r.employee_code || '').toLowerCase().includes(q)
                );
            },
            formatNum(n) {
                const v = parseFloat(n || 0);
                return Number.isInteger(v) ? String(v) : v.toFixed(1);
            },
            remaining(allowed, taken) {
                const rem = Math.max(0, (parseFloat(allowed || 0) - parseFloat(taken || 0)));
                return this.formatNum(rem);
            }
        }">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-xs border border-slate-200">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Leave Quotas</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">View employee yearly allowed leave and record taken leave.</p>
            </div>
            <div class="flex items-center gap-2.5">
                @if(auth()->user()->hasModulePermission('payroll.leave_quota', 'create') || auth()->user()->hasModulePermission('payroll.leave_quota', 'edit'))
                    <button type="button" @click="openAddQuota()" class="px-4 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm transition cursor-pointer">
                        + Add Leave Quota
                    </button>
                @endif
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold">{{ $errors->first() }}</div>
        @endif

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs grid grid-cols-1 md:grid-cols-2 gap-4 items-end text-xs">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">Select Financial Period:</label>
                <select onchange="window.location.href='{{ route('payroll.leave-quota.index') }}?financial_period_id=' + this.value"
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-800 cursor-pointer">
                    @forelse($periods as $p)
                        <option value="{{ $p->id }}" {{ (int)$selectedPeriodId === (int)$p->id ? 'selected' : '' }}>
                            Fiscal Year: {{ $p->name }} ({{ $p->start_date->format('d M Y') }} - {{ $p->end_date->format('d M Y') }})
                        </option>
                    @empty
                        <option value="">No Financial Periods Configured</option>
                    @endforelse
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">Search Employee:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Search by name or code..."
                           class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30">
                </div>
            </div>
        </div>

        {{-- Table (Read-only display of Allowed & Taken) --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <th class="px-4 py-3.5 erp-table-header w-36">Employee Code</th>
                            <th class="px-4 py-3.5 erp-table-header">Employee Name</th>
                            <th class="px-4 py-3.5 erp-table-header text-center">Allowed</th>
                            <th class="px-4 py-3.5 erp-table-header text-center">Taken</th>
                            <th class="px-4 py-3.5 erp-table-header text-center">Remaining</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <template x-for="row in filteredRows()" :key="row.employee_id">
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold font-mono bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30" x-text="row.employee_code"></span>
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-800" x-text="row.name"></td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-900" x-text="formatNum(row.allowed_leave)"></td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-rose-600" x-text="formatNum(row.taken_leave)"></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[28px] px-2.5 py-0.5 rounded-md text-[11px] font-extrabold font-mono bg-emerald-100 text-emerald-700"
                                          x-text="remaining(row.allowed_leave, row.taken_leave)"></span>
                                </td>
                            </tr>
                        </template>
                        <template x-if="filteredRows().length === 0">
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">No employees found.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Employee Leave Quota Modal --}}
        <div x-show="openAddQuotaModal" x-cloak x-transition.opacity style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div @click.away="openAddQuotaModal = false" class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Add Employee Leave Quota</h3>
                    <button type="button" @click="openAddQuotaModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <form action="{{ route('payroll.leave-quota.save') }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Financial Period *</label>
                        <select name="financial_period_id" x-model="modalForm.financial_period_id" @change="onModalSelectionChange()" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            @forelse($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->start_date->format('d M Y') }} - {{ $p->end_date->format('d M Y') }})</option>
                            @empty
                                <option value="">Default Period</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Employee *</label>
                        <select x-model="modalForm.employee_id" @change="onModalSelectionChange()" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                            <option value="">-- Select Employee --</option>
                            <template x-for="r in rows" :key="r.employee_id">
                                <option :value="r.employee_id" x-text="r.employee_code + ' — ' + r.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Allowed Leave</label>
                            <input type="number" step="0.5" readonly
                                   :name="'quotas[' + modalForm.employee_id + '][allowed_leave]'"
                                   x-model="modalForm.allowed_leave"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-100 font-mono font-bold text-slate-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Taken Leave *</label>
                            <input type="number" step="0.5" min="0" max="365" required
                                   :name="'quotas[' + modalForm.employee_id + '][taken_leave]'"
                                   x-model="modalForm.taken_leave"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-slate-900">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="openAddQuotaModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Quota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-erp-layout>
