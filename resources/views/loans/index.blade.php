<x-erp-layout title="Bank Loan Master" headerTitle="Bank Loan Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="loanApp()">
    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Bank Loan Repayments</h1>
            <p class="text-xs text-slate-500 mt-1">Manage project-level funding, loan master registers, track repayments, and rescheduling events.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('loans.reports') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-md uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                Loan Dashboard & Reports
            </a>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Loan
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('loans.index') }}" class="flex flex-wrap items-end gap-4 text-xs font-semibold">
            <div class="w-full sm:w-60 space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Lending Bank</label>
                <input type="text" name="lender_name" value="{{ request('lender_name') }}" placeholder="Search bank..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl focus:outline-none transition-all">
            </div>

            <div class="w-full sm:w-60 space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Associated Project</label>
                <select name="project_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl focus:outline-none transition-all">
                    <option value="">All Projects...</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-44 space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Loan Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white rounded-xl focus:outline-none transition-all">
                    <option value="">All Statuses...</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-bold uppercase tracking-wider hover:bg-slate-800 transition">Filter</button>
                @if(request()->anyFilled(['lender_name', 'project_id', 'status']))
                    <a href="{{ route('loans.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold uppercase tracking-wider transition">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Alert Toast --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

    {{-- Loans List Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">SL NO</th>
                        <th class="px-4 py-3 border">LOAN ACCOUNT NO</th>
                        <th class="px-4 py-3 border">LENDING BANK</th>
                        <th class="px-4 py-3 border">ASSOCIATED PROJECT</th>
                        <th class="px-4 py-3 border">TOTAL SANCTIONED PRINCIPAL</th>
                        <th class="px-4 py-3 border">INTEREST RATE (%)</th>
                        <th class="px-4 py-3 border">LOAN TENURE (M)</th>
                        <th class="px-4 py-3 border">SCHEDULED MONTHLY EMI</th>
                        <th class="px-4 py-3 border">OUTSTANDING PRINCIPAL BALANCE</th>
                        <th class="px-4 py-3 border">PAID PRINCIPAL TO DATE</th>
                        <th class="px-4 py-3 border">CUMULATIVE INTEREST COST PAID</th>
                        <th class="px-4 py-3 border">STATUS</th>
                        <th class="px-4 py-3 border">PREPAYMENT LOGS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center font-semibold text-slate-700">
                    @forelse($loans as $idx => $loan)
                        <tr class="hover:bg-slate-50/50 transition-colors text-xs">
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $loans->firstItem() + $idx }}</td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold font-mono">{{ $loan->loan_account_no ?? '—' }}</td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold">{{ $loan->lender_name }}</td>
                            <td class="px-4 py-3.5 border text-slate-600">{{ $loan->project->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-800 font-bold">₹{{ number_format((float)$loan->principal_amount, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-650">{{ $loan->interest_rate }}%</td>
                            <td class="px-4 py-3.5 border text-slate-600">{{ $loan->tenure_months }} Mos</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-800">₹{{ number_format((float)$loan->base_emi, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-rose-700 font-extrabold">₹{{ number_format((float)$loan->outstanding_balance, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-emerald-800 font-bold">₹{{ number_format((float)$loan->paid_principal_to_date, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-[#a38c29] font-bold">₹{{ number_format((float)$loan->cumulative_interest_paid, 2) }}</td>
                            <td class="px-4 py-3.5 border">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border {{ $loan->status === 'Active' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-100 border-slate-200 text-slate-500' }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border">
                                @if($loan->prepayments->isNotEmpty())
                                    <button @click="showLogs({{ json_encode($loan->prepayments) }}, '{{ $loan->loan_account_no }}')" class="text-indigo-650 hover:underline text-[10px] font-bold uppercase tracking-wider">
                                        View ({{ $loan->prepayments->count() }})
                                    </button>
                                @else
                                    <span class="text-slate-400 font-medium italic text-[10px]">None</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                <a href="{{ route('loans.schedule', $loan->id) }}" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] transition inline-flex items-center justify-center shadow-sm" title="Repayment Schedule">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="ml-1.5 text-[10px] font-bold uppercase tracking-wider">Schedule</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="px-6 py-10 text-center text-slate-400 italic">No loan records found. Please configure a bank loan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loans->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $loans->links() }}
            </div>
        @endif
    </div>

    {{-- Prepayment Logs Modal --}}
    <div x-show="logsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="logsModalOpen = false"></div>
        <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest" x-text="'Prepayment Logs: ' + activeAccountNo"></h3>
                <button @click="logsModalOpen = false" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-55 border-b border-slate-100 text-center font-bold text-slate-650 uppercase tracking-wider text-[10px]">
                            <th class="px-4 py-2 border">DATE</th>
                            <th class="px-4 py-2 border">PREPAYMENT AMOUNT</th>
                            <th class="px-4 py-2 border">PREVIOUS OUTSTANDING</th>
                            <th class="px-4 py-2 border">NEW OUTSTANDING</th>
                            <th class="px-4 py-2 border">MODE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-center font-semibold text-slate-700">
                        <template x-for="log in activeLogs" :key="log.id">
                            <tr>
                                <td class="px-4 py-3 border font-mono text-slate-600" x-text="new Date(log.prepayment_date).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'})"></td>
                                <td class="px-4 py-3 border font-mono text-emerald-800" x-text="'₹' + Number(log.prepayment_amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                <td class="px-4 py-3 border font-mono text-slate-550" x-text="'₹' + Number(log.previous_outstanding).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                <td class="px-4 py-3 border font-mono text-rose-700" x-text="'₹' + Number(log.new_outstanding).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                <td class="px-4 py-3 border text-slate-600 uppercase text-[10px]" x-text="log.reschedule_option === 'reduce_emi' ? 'Reduce EMI' : 'Reduce Tenure'"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
                <button type="button" @click="logsModalOpen = false" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-800 transition">Close</button>
            </div>
        </div>
    </div>

    {{-- Create Loan Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Create New Project Loan Account</h3>
                <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <form @submit.prevent="submitAddForm">
                <div class="p-6 grid grid-cols-2 gap-4 max-h-[70vh] overflow-y-auto">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Associated Project *</label>
                        <select x-model="addForm.project_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="">Select Project...</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Loan Account No *</label>
                        <input type="text" x-model="addForm.loan_account_no" required placeholder="e.g. LN-897937402" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lending Bank *</label>
                        <input type="text" x-model="addForm.lender_name" required placeholder="e.g. State Bank of India" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Total Sanctioned Principal (₹) *</label>
                        <input type="number" step="0.01" x-model="addForm.principal_amount" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Interest Rate (Annual %) *</label>
                        <input type="number" step="0.01" x-model="addForm.interest_rate" required placeholder="e.g. 7.50" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Loan Tenure (Months) *</label>
                        <input type="number" x-model="addForm.tenure_months" required placeholder="e.g. 120" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Repayment Start Date *</label>
                        <input type="date" x-model="addForm.start_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Schedule Type *</label>
                        <select x-model="addForm.schedule_type" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="reducing_balance">Reducing Balance</option>
                            <option value="flat">Flat Rate</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Ledger Account (Liability) *</label>
                        <select x-model="addForm.ledger_account_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="">Select Ledger Account...</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ strtoupper($acc->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Interest Expense Account *</label>
                        <select x-model="addForm.interest_account_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="">Select Interest Account...</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ strtoupper($acc->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-[#a38c29]/20">Create Loan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loanApp() {
    return {
        addModalOpen: false,
        logsModalOpen: false,
        activeLogs: [],
        activeAccountNo: '',
        addForm: {
            project_id: '',
            loan_account_no: '',
            lender_name: '',
            principal_amount: '',
            interest_rate: '',
            tenure_months: '',
            start_date: '',
            schedule_type: 'reducing_balance',
            ledger_account_id: '',
            interest_account_id: ''
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },
        openAddModal() {
            this.addForm = {
                project_id: '{{ request('project_id') ?? ($projects->first()->id ?? '') }}',
                loan_account_no: '',
                lender_name: '',
                principal_amount: '',
                interest_rate: '',
                tenure_months: '',
                start_date: '',
                schedule_type: 'reducing_balance',
                ledger_account_id: '',
                interest_account_id: ''
            };
            this.addModalOpen = true;
        },
        showLogs(logs, accountNo) {
            this.activeLogs = logs;
            this.activeAccountNo = accountNo;
            this.logsModalOpen = true;
        },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => { this.toast.open = false; }, 4000);
        },
        submitAddForm() {
            fetch('{{ route('loans.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.addForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.message || 'Validation error. Please verify input data.', 'error');
                } else {
                    this.showToast('Project loan and repayment schedule created successfully.');
                    this.addModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network or server error occurred.', 'error');
            });
        }
    }
}
</script>
</x-erp-layout>
