<x-erp-layout title="Bank Loan Master" headerTitle="Bank Loan Master Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="loanApp()">
    {{-- Pending EMI Alert --}}
    @if(isset($pendingEmisCount) && $pendingEmisCount > 0)
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-250 text-amber-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-amber-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>
                    Attention: You have <strong class="text-amber-900">{{ $pendingEmisCount }}</strong> pending/overdue Loan EMI repayments due this month totaling <strong class="text-amber-900">₹{{ number_format($pendingEmisAmount, 2) }}</strong>.
                </span>
            </div>
            <span class="px-3 py-1.5 bg-amber-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-wide">
                See Due EMIs Below
            </span>
        </div>
    @endif

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

    {{-- KPI Metrics Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Active Loan Accounts</span>
                <strong class="text-sm font-extrabold text-slate-900 block mt-1 font-sans">{{ $activeLoansCount ?? 0 }} Accounts</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Net Outstanding Balance</span>
                <strong class="text-sm font-extrabold text-rose-700 block mt-1 font-mono">₹{{ number_format((float)($totalOutstanding ?? 0), 2) }}</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Repaid Principal</span>
                <strong class="text-sm font-extrabold text-emerald-800 block mt-1 font-mono">₹{{ number_format((float)($totalPaidPrincipal ?? 0), 2) }}</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Paid Interest Cost</span>
                <strong class="text-sm font-extrabold text-[#a38c29] block mt-1 font-mono">₹{{ number_format((float)($totalPaidInterest ?? 0), 2) }}</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
    </div>

    {{-- Loans List Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">SL NO</th>
                        <th class="px-4 py-3 border">LOAN ACCOUNT / PROJECT</th>
                        <th class="px-4 py-3 border">LENDING BANK</th>
                        <th class="px-4 py-3 border">LOAN TERMS (PRINCIPAL & RATE)</th>
                        <th class="px-4 py-3 border">OUTSTANDING & REPAYMENTS</th>
                        <th class="px-4 py-3 border">NEXT DUE EMI</th>
                        <th class="px-4 py-3 border">STATUS & LOGS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center font-semibold text-slate-700">
                    @forelse($loans as $idx => $loan)
                        <tr class="hover:bg-slate-50/50 transition-colors text-xs">
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $loans->firstItem() + $idx }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="font-bold text-slate-900 font-mono">{{ $loan->loan_account_no ?? '—' }}</div>
                                <div class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $loan->project->name ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold">{{ $loan->lender_name }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="font-mono text-slate-800 font-bold">₹{{ number_format((float)$loan->principal_amount, 2) }}</div>
                                <div class="text-[10px] text-slate-500 font-medium mt-0.5">
                                    Interest: <span class="font-bold text-slate-700">{{ $loan->interest_rate }}% P.A.</span> | 
                                    Tenure: <span class="font-bold text-slate-700">{{ $loan->tenure_months }} Months</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="font-mono text-rose-700 font-extrabold">Bal: ₹{{ number_format((float)$loan->outstanding_balance, 2) }}</div>
                                <div class="text-[10px] text-slate-500 font-medium mt-0.5">
                                    Paid P: <span class="font-bold text-emerald-800">₹{{ number_format((float)$loan->paid_principal_to_date, 2) }}</span> | 
                                    Paid I: <span class="font-bold text-[#a38c29]">₹{{ number_format((float)$loan->cumulative_interest_paid, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border">
                                @if($loan->next_emi && $loan->status === 'Active')
                                    <div class="font-mono text-slate-900 font-bold">₹{{ number_format((float)$loan->next_emi->emi_amount, 2) }}</div>
                                    <div class="text-[10px] text-amber-700 font-bold mt-0.5">
                                        Inst #{{ $loan->next_emi->installment_no }} (Due: {{ \Carbon\Carbon::parse($loan->next_emi->due_date)->format('d M Y') }})
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium italic text-[10px]">No due EMIs</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border {{ $loan->status === 'Active' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-slate-100 border-slate-200 text-slate-500' }}">
                                        {{ $loan->status }}
                                    </span>
                                    @if($loan->prepayments->isNotEmpty())
                                        <button @click="showLogs({{ json_encode($loan->prepayments) }}, '{{ $loan->loan_account_no }}')" class="text-indigo-650 hover:underline text-[9px] font-bold uppercase tracking-wider">
                                            Prepayments ({{ $loan->prepayments->count() }})
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($loan->next_emi && $loan->status === 'Active')
                                        <button @click="openPayModal({{ json_encode($loan) }}, {{ json_encode($loan->next_emi) }})" 
                                                class="px-2.5 py-1.5 bg-primary hover:bg-primary-700 text-white rounded-xl text-[10px] font-bold uppercase tracking-wide transition shadow-sm flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Quick Pay
                                        </button>
                                    @endif
                                    <a href="{{ route('loans.schedule', $loan->id) }}" class="p-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition inline-flex items-center justify-center shadow-sm" title="Repayment Schedule">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="ml-1 text-[10px] font-bold uppercase tracking-wider">Ledger</span>
                                    </a>
                                    @if($loan->status === 'Active')
                                        <button @click="openEditInterestModal({{ json_encode($loan) }})" 
                                                class="p-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition inline-flex items-center justify-center shadow-sm" title="Edit Interest Rate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            <span class="ml-1 text-[10px] font-bold uppercase tracking-wider">Edit Rate</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">No loan records found. Please configure a bank loan.</td>
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

    {{-- Quick Pay EMI Modal --}}
    <div x-show="payModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="payModalOpen = false"></div>
        <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest" x-text="activeLoan ? 'Record Payment: A/C ' + activeLoan.loan_account_no : 'Record Payment'"></h3>
                <button @click="payModalOpen = false" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <form @submit.prevent="submitPayForm">
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Lending Bank</span>
                        <strong class="text-xs text-slate-800 font-extrabold" x-text="activeLoan.lender_name"></strong>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Installment Due</span>
                        <strong class="text-xs text-amber-750 font-extrabold" x-text="activeInst ? 'Installment #' + activeInst.installment_no + ' (Due: ' + new Date(activeInst.due_date).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'}) + ')' : ''"></strong>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 font-sans">EMI Pending Balance (₹)</label>
                        <input type="text" readonly :value="activeInst ? '₹' + Number(activeInst.emi_amount - activeInst.amount_paid).toLocaleString('en-IN', {minimumFractionDigits: 2}) : ''" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 font-sans">Payment Amount (₹) *</label>
                        <input type="number" step="0.01" x-model="payForm.amount" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 font-sans">Payment Date *</label>
                        <input type="date" x-model="payForm.paid_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="payModalOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-emerald-650/20">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Interest Rate Modal --}}
    <div x-show="editInterestModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editInterestModalOpen = false"></div>
        <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest" x-text="editLoan ? 'Edit Interest Rate: A/C ' + editLoan.loan_account_no : 'Edit Interest Rate'"></h3>
                <button @click="editInterestModalOpen = false" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <form @submit.prevent="submitEditInterestForm">
                <div class="p-6 space-y-4">
                    <div class="bg-indigo-50 border border-indigo-150 rounded-xl p-3.5 text-xs text-indigo-850">
                        <strong class="font-bold">Important Notice:</strong> Modifying the interest rate will automatically recalculate the interest and principal components for all remaining unpaid installments of this loan.
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Lending Bank</span>
                        <strong class="text-xs text-slate-800 font-extrabold" x-text="editLoan.lender_name"></strong>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Current Stored Rate</span>
                        <strong class="text-xs text-slate-800 font-extrabold" x-text="editLoan.interest_rate + '% P.A. (Equivalent)'"></strong>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 font-sans">New Interest Rate *</label>
                            <input type="number" step="0.01" x-model="editInterestForm.interest_rate" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 font-sans">Interest Period *</label>
                            <select x-model="editInterestForm.interest_period" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="annual">Per Annum (Yearly)</option>
                                <option value="monthly">Per Month (Monthly)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="editInterestModalOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-indigo-650/20">Update Interest Rate</button>
                </div>
            </form>
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
                        <select x-model="addForm.lender_name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all cursor-pointer">
                            <option value="">Select Lending Bank...</option>
                            @foreach($banks as $b)
                                <option value="{{ $b->bank_name }}">{{ $b->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Total Sanctioned Principal (₹) *</label>
                        <input type="number" step="0.01" x-model="addForm.principal_amount" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Interest Rate *</label>
                            <input type="number" step="0.01" x-model="addForm.interest_rate" required placeholder="e.g. 7.50" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Interest Period *</label>
                            <select x-model="addForm.interest_period" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="annual">Per Annum (Yearly)</option>
                                <option value="monthly">Per Month (Monthly)</option>
                            </select>
                        </div>
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
            interest_period: 'annual',
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
                interest_period: 'annual',
                tenure_months: '',
                start_date: '',
                schedule_type: 'reducing_balance',
                ledger_account_id: '',
                interest_account_id: ''
            };
            this.addModalOpen = true;
        },
        payModalOpen: false,
        activeLoan: {},
        activeInst: {},
        payForm: {
            amount: '',
            paid_date: ''
        },
        openPayModal(loan, inst) {
            this.activeLoan = loan;
            this.activeInst = inst;
            this.payForm.amount = Number(inst.emi_amount - inst.amount_paid).toFixed(2);
            this.payForm.paid_date = new Date().toISOString().slice(0, 10);
            this.payModalOpen = true;
        },
        submitPayForm() {
            if (!this.activeLoan || !this.activeInst) return;
            const url = `/loans/${this.activeLoan.id}/pay-emi/${this.activeInst.id}`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.payForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || data.message || 'Error recording payment.', 'error');
                } else {
                    this.showToast('EMI payment logged successfully.');
                    this.payModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },
        editInterestModalOpen: false,
        editLoan: {},
        editInterestForm: {
            interest_rate: '',
            interest_period: 'annual'
        },
        openEditInterestModal(loan) {
            this.editLoan = loan;
            this.editInterestForm.interest_rate = loan.interest_rate;
            this.editInterestForm.interest_period = 'annual';
            this.editInterestModalOpen = true;
        },
        submitEditInterestForm() {
            if (!this.editLoan) return;
            const url = `/loans/${this.editLoan.id}/update-interest`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.editInterestForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.message || 'Error updating interest rate.', 'error');
                } else {
                    this.showToast('Interest rate updated and unpaid schedules re-amortized successfully.');
                    this.editInterestModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
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
