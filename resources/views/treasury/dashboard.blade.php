<x-erp-layout title="Bank & Treasury Management" headerTitle="Bank & Treasury Management">

<div class="max-w-[1800px] mx-auto space-y-6" 
     x-data="{ 
         bankTransactions: @js($recentTransactions),
         expandedBanks: {},
         customerSearch: {},

         isBankExpanded(id) {
             return !!this.expandedBanks[id];
         },

         toggleBank(id) {
             this.expandedBanks = {
                 ...this.expandedBanks,
                 [id]: !this.expandedBanks[id]
             };
         },

         getBankTransactions(bankId) {
             let list = this.bankTransactions[bankId] || [];
             const q = (this.customerSearch[bankId] || '').trim().toLowerCase();

             if (q) {
                 list = list.filter(t => 
                     (t.customer_name && t.customer_name.toLowerCase().includes(q)) ||
                     (t.narration && t.narration.toLowerCase().includes(q)) ||
                     (t.voucher_no && t.voucher_no.toLowerCase().includes(q)) ||
                     (t.remarks && t.remarks.toLowerCase().includes(q)) ||
                     (t.payment_mode && t.payment_mode.toLowerCase().includes(q)) ||
                     (t.bank_ref_no && t.bank_ref_no.toLowerCase().includes(q)) ||
                     (t.cheque_no && t.cheque_no.toLowerCase().includes(q))
                 );
             }
             return list;
         }
     }">

    {{-- Top Navigation & Action Header --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#a38c29]/20 to-[#a38c29]/5 text-[#8a7522] flex items-center justify-center text-xl shrink-0 shadow-2xs border border-[#a38c29]/30">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-black text-slate-900 tracking-tight">Bank Accounts & Treasury Management</h1>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">Real-Time Balances</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Track company bank balances, monitor cleared and pending cheques, and inspect transaction statements for each account.</p>
            </div>
        </div>

        {{-- Quick Navigation Actions --}}
        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Return to Cheque Realization Console --}}
            <a href="{{ route('cheque-realization.queue') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-black rounded-xl shadow-xs hover:shadow transition-all group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Cheque Realization Console</span>
                @if($totalPendingCount > 0)
                    <span class="px-2 py-0.5 rounded-full bg-white/25 text-white text-[10px] font-black">{{ $totalPendingCount }} Pending</span>
                @endif
            </a>

            {{-- Bank Statement Reports Button --}}
            <a href="{{ route('reports.bank_reports') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] text-white text-xs font-black rounded-xl shadow-xs hover:shadow-md transition-all duration-200 group border border-[#8a7522]/60">
                <div class="w-5 h-5 rounded-lg bg-white/20 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span>Bank Statement</span>
                <svg class="w-3.5 h-3.5 opacity-70 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Flash Success Alert Banner --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50/90 border-2 border-emerald-300 rounded-2xl text-emerald-950 shadow-sm flex items-center justify-between gap-3 animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-900">Realization Successful</h4>
                    <p class="text-xs font-bold text-emerald-800 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
            <a href="{{ route('cheque-realization.queue') }}" class="px-3.5 py-1.5 bg-white text-emerald-800 border border-emerald-300 hover:bg-emerald-100 rounded-xl text-xs font-black transition shrink-0">
                Process Next Cheque →
            </a>
        </div>
    @endif

    {{-- ── TOP EXECUTIVE KPI SUMMARY CARDS GRID ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Total Bank Balance --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Bank Balance</span>
                </div>
                <span class="text-[9px] text-slate-600 font-bold bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-[#a38c29]/50 group-hover:text-[#a38c29] group-hover:bg-[#a38c29]/5">
                    {{ $bankAccounts->count() }} Accounts
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    ₹{{ number_format($totalBalance, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Across all registered company accounts</p>
            </div>
        </div>

        {{-- Card 2: Available Balance --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Available Balance</span>
                </div>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                    Active
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                    ₹{{ number_format($availableBalance, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Real-time liquid assets ready for use</p>
            </div>
        </div>

        {{-- Card 3: Pending Cheques --}}
        <a href="{{ route('cheque-realization.queue') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-pointer block">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Pending Cheques</span>
                </div>
                <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                    {{ $totalPendingCount ?? 0 }} Pending
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">
                    ₹{{ number_format($totalPendingAmount ?? 0, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Awaiting deposit & bank realization</p>
            </div>
        </a>

        {{-- Card 4: Total Realized Collections --}}
        <a href="{{ route('cheque-realization.realized') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-indigo-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(99,102,241,0.15)] cursor-pointer block">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/60 transition-all duration-300 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Total Realized</span>
                </div>
                <span class="text-[9px] text-indigo-700 font-bold bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-indigo-300 group-hover:bg-indigo-100/60">
                    {{ $totalRealizedCount ?? 0 }} Cleared
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-indigo-600 font-mono tracking-tight block group-hover:text-indigo-700 transition-colors duration-300">
                    ₹{{ number_format($totalRealizedAmount ?? 0, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Today: +₹{{ number_format($todayRealizedAmount ?? 0, 2) }} ({{ $todayRealizedCount }} receipts)</p>
            </div>
        </a>

    </div>

    {{-- Bank Accounts Directory Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">Company Bank Accounts Directory</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Click on the eye icon to expand and inspect transaction statements for each bank account.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 text-center w-12">#</th>
                        <th class="px-5 py-3.5">Bank / Account Details</th>
                        <th class="px-5 py-3.5">Account Number</th>
                        <th class="px-5 py-3.5">IFSC & Branch</th>
                        <th class="px-5 py-3.5 text-center">Realized Instruments</th>
                        <th class="px-5 py-3.5 text-center">Pending Cheques</th>
                        <th class="px-5 py-3.5 text-right font-extrabold">Current Balance (₹)</th>
                        <th class="px-5 py-3.5 text-center w-24">Statement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bankAccounts as $index => $account)
                    <tr class="hover:bg-amber-50/40 transition-colors cursor-pointer"
                        :class="isBankExpanded({{ $account->id }}) ? 'bg-amber-50/50 font-semibold ring-1 ring-inset ring-[#a38c29]/30' : ''"
                        @click="toggleBank({{ $account->id }})">
                        <td class="px-5 py-3.5 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-black text-slate-700 text-xs">
                                    {{ substr($account->bank_name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-900 flex items-center gap-1.5">
                                        <span>{{ $account->bank_name }}</span>
                                        @if($account->is_default)
                                            <span class="px-1.5 py-0.2 rounded text-[8px] font-black uppercase bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30">Primary</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium">{{ $account->account_name ?: 'Company Account' }} ({{ $account->account_type ?? 'Current' }})</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-slate-700 font-bold tracking-wider">
                            {{ $account->account_number ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-mono text-slate-800 font-semibold text-[11px]">{{ $account->ifsc_code ?: '—' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $account->branch_name ?: 'Main Branch' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-mono font-bold text-[11px]">
                                {{ $account->realized_count }} (₹{{ number_format($account->realized_sum, 2) }})
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($account->pending_count > 0)
                                <a href="{{ route('cheque-realization.queue', ['bank_account_id' => $account->id]) }}" 
                                   @click.stop
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 font-mono font-bold text-[11px] hover:bg-amber-100 transition">
                                    <span>⏳ {{ $account->pending_count }}</span>
                                    <span class="text-amber-600 font-normal">({{ number_format($account->pending_sum, 2) }})</span>
                                </a>
                            @else
                                <span class="text-slate-400 font-mono text-[11px]">0</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right font-mono font-black text-sm text-[#8a7522]">
                            ₹{{ number_format($account->current_balance, 2) }}
                        </td>
                        <td class="px-5 py-3.5 text-center" @click.stop>
                            <button type="button" 
                                    @click="toggleBank({{ $account->id }})"
                                    :class="isBankExpanded({{ $account->id }}) 
                                        ? 'bg-[#a38c29] text-white shadow-sm ring-2 ring-[#a38c29]/40 border border-[#8a7522] scale-105' 
                                        : 'bg-[#a38c29]/15 text-[#8a7522] hover:bg-[#a38c29] hover:text-white border border-[#a38c29]/30 hover:border-[#8a7522] shadow-2xs'"
                                    class="inline-flex items-center justify-center p-2 rounded-xl transition-all duration-200 group"
                                    :title="isBankExpanded({{ $account->id }}) ? 'Collapse transaction statements' : 'View transaction statements'">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Expanded Bank Transaction Statement Accordion Row --}}
                    <tr x-show="isBankExpanded({{ $account->id }})" x-cloak class="border-b-2 border-[#a38c29]/40 bg-[#a38c29]/5">
                        <td colspan="8" class="p-3 sm:p-5">
                            <div class="bg-white rounded-2xl border-2 border-[#a38c29]/35 shadow-lg shadow-[#a38c29]/5 overflow-hidden">
                                
                                {{-- Statement Header Bar (Clean Light Card Header) --}}
                                <div class="px-5 py-3.5 bg-slate-50/90 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-[#a38c29]/15 border border-[#a38c29]/30 text-[#8a7522] flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                            {{ substr($account->bank_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-sm font-black tracking-tight text-slate-900 uppercase">{{ $account->bank_name }} — Transaction Statement</h4>
                                                @if($account->is_default)
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30">Primary</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-500 font-mono flex flex-wrap items-center gap-x-4 gap-y-0.5 mt-0.5">
                                                <span>A/C No: <strong class="text-slate-800 font-bold">{{ $account->account_number ?: '—' }}</strong></span>
                                                <span>IFSC: <strong class="text-slate-800 font-bold">{{ $account->ifsc_code ?: '—' }}</strong></span>
                                                <span>Branch: <strong class="text-slate-800 font-bold">{{ $account->branch_name ?: 'Main Branch' }}</strong></span>
                                                <span>Type: <strong class="text-slate-800 font-bold">{{ $account->account_type ?? 'Current' }}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-white px-3.5 py-1.5 rounded-xl border border-slate-200 text-right shadow-2xs">
                                            <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Ledger Balance</span>
                                            <span class="text-sm font-black text-emerald-600 font-mono">₹{{ number_format($account->current_balance, 2) }}</span>
                                        </div>
                                        <button type="button" 
                                                @click="toggleBank({{ $account->id }})"
                                                class="w-8 h-8 rounded-xl bg-white hover:bg-slate-100 text-slate-400 hover:text-slate-700 border border-slate-200 flex items-center justify-center transition text-sm font-bold shadow-2xs"
                                                title="Close Statement">
                                            ✕
                                        </button>
                                    </div>
                                </div>

                                {{-- Statement Filter / Search by Customer (No Page Reload) --}}
                                <div class="px-5 py-2.5 bg-white border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-2 text-[11px]">
                                        <span class="font-black text-slate-800 uppercase tracking-wider text-[10px]">Statement Audit Trail</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-bold text-[10px]" x-text="getBankTransactions({{ $account->id }}).length + ' Entries'"></span>
                                        @if($account->realized_count > 0)
                                            <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold text-[10px]">
                                                Total Realized Inflow: ₹{{ number_format($account->realized_sum, 2) }}
                                            </span>
                                        @endif
                                        <span x-show="customerSearch[{{ $account->id }}]" 
                                              class="px-2 py-0.5 rounded-md bg-amber-50 text-[#8a7522] border border-amber-200 font-bold text-[10px]">
                                            Filtered Statement
                                        </span>
                                    </div>

                                    {{-- Statement Search Control (Client-Side / No Reload) --}}
                                    <div class="flex items-center gap-2">
                                        {{-- Search Input --}}
                                        <div class="relative w-full sm:w-80">
                                            <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <input type="text"
                                                   x-model="customerSearch[{{ $account->id }}]"
                                                   placeholder="Search by loan, customer, narration, voucher..."
                                                   class="w-full pl-8 pr-7 py-1.5 bg-slate-50 border border-slate-200 focus:border-[#a38c29] focus:bg-white focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition placeholder:text-slate-400 font-medium">
                                            <button type="button" 
                                                    x-show="customerSearch[{{ $account->id }}]" 
                                                    @click="customerSearch[{{ $account->id }}] = ''" 
                                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold"
                                                    title="Clear search">✕</button>
                                        </div>

                                        {{-- Clear / Reset Button --}}
                                        <button type="button"
                                                x-show="customerSearch[{{ $account->id }}]"
                                                @click="customerSearch[{{ $account->id }}] = ''"
                                                class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition shadow-2xs"
                                                title="Reset Filter">
                                            ✕ Reset
                                        </button>
                                    </div>
                                </div>

                                {{-- Statement Table --}}
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs text-left">
                                        <thead>
                                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white text-[10px] font-black uppercase tracking-widest border-b-2 border-[#8a7522] shadow-xs">
                                                <th class="px-4 py-3.5 text-center w-12 text-white">#</th>
                                                <th class="px-4 py-3.5 w-28 text-white">Date</th>
                                                <th class="px-4 py-3.5 w-44 text-white">Voucher / Ref No.</th>
                                                <th class="px-4 py-3.5 text-white">Particulars / Customer</th>
                                                <th class="px-4 py-3.5 w-56 text-white">Payment Mode & Instrument</th>
                                                <th class="px-4 py-3.5 text-center w-24 text-white">Type</th>
                                                <th class="px-4 py-3.5 text-right font-black w-36 text-white">Credit (₹)</th>
                                                <th class="px-4 py-3.5 text-right font-black w-36 text-white">Debit (₹)</th>
                                                <th class="px-4 py-3.5 text-right font-black w-40 text-white">Account Balance (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <template x-for="(txn, txnIdx) in getBankTransactions({{ $account->id }})" :key="txn.id">
                                                <tr class="hover:bg-amber-50/40 transition-colors">
                                                    <td class="px-4 py-3.5 text-center text-slate-400 font-bold font-mono" x-text="txnIdx + 1"></td>
                                                    <td class="px-4 py-3.5 font-mono text-slate-600 font-semibold text-[11px] whitespace-nowrap" x-text="txn.date"></td>
                                                    <td class="px-4 py-3.5">
                                                        <span class="font-bold text-indigo-700 font-mono text-xs block leading-tight" x-text="txn.voucher_no"></span>
                                                        <div class="text-[10px] text-[#8a7522] font-mono font-medium mt-1 inline-flex items-center gap-1" x-show="txn.bank_ref_no && txn.bank_ref_no !== '—'">
                                                            <span class="text-slate-400">Ref:</span>
                                                            <span class="bg-amber-50 px-1.5 py-0.2 rounded border border-amber-200" x-text="txn.bank_ref_no"></span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3.5">
                                                        <div class="font-bold text-slate-900 leading-snug" x-text="txn.customer_name"></div>
                                                        <div class="text-[11px] text-slate-500 font-medium mt-0.5 truncate max-w-sm" x-show="txn.remarks" x-text="txn.remarks"></div>
                                                    </td>
                                                    <td class="px-4 py-3.5">
                                                        <div class="flex items-center flex-wrap gap-1.5">
                                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs" x-text="txn.payment_mode"></span>
                                                            <span class="font-mono text-slate-700 font-bold text-[11px] bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 shadow-2xs" x-show="txn.cheque_no && txn.cheque_no !== '—'" x-text="'#' + txn.cheque_no"></span>
                                                        </div>
                                                        <div class="text-[10px] text-slate-400 font-medium mt-1" x-show="txn.drawee_bank && txn.drawee_bank !== '—'" x-text="'Drawee: ' + txn.drawee_bank"></div>
                                                    </td>
                                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                                        <span :class="txn.type === 'Debit' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border shadow-2xs">
                                                            <span x-text="txn.type === 'Debit' ? '▼ Debit' : '▲ Credit'"></span>
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3.5 text-right font-mono font-bold text-emerald-700 text-xs whitespace-nowrap">
                                                        <span x-show="txn.type !== 'Debit'" x-text="'₹' + (Number(txn.amount) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                        <span x-show="txn.type === 'Debit'" class="text-slate-300 font-normal">—</span>
                                                    </td>
                                                    <td class="px-4 py-3.5 text-right font-mono font-bold text-rose-600 text-xs whitespace-nowrap">
                                                        <span x-show="txn.type === 'Debit'" x-text="'₹' + (Number(txn.amount) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                        <span x-show="txn.type !== 'Debit'" class="text-slate-300 font-normal">—</span>
                                                    </td>
                                                    <td class="px-4 py-3.5 text-right font-mono font-black text-slate-900 text-xs whitespace-nowrap"
                                                        x-text="'₹' + (Number(txn.balance) || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr x-show="getBankTransactions({{ $account->id }}).length === 0">
                                                <td colspan="9" class="px-5 py-10 text-center text-slate-400 italic font-medium bg-slate-50/50">
                                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        <span>No transaction statement records found matching the search criteria.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-slate-400 italic font-medium">No company bank accounts configured in the system.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-erp-layout>
