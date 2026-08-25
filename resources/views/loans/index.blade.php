<x-erp-layout title="EMI & Interest Payment Release" headerTitle="EMI & Interest Payment Release Directory">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="loanApp()">
    {{-- Pending EMI Alert --}}
    @if(isset($totalPendingCount) && $totalPendingCount > 0)
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-250 text-amber-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-amber-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>
                    @if(isset($overdueCount) && $overdueCount > 0)
                        Attention: <strong class="text-amber-900">{{ $overdueCount }}</strong> EMI payments are overdue and <strong class="text-amber-900">{{ $dueThisMonthCount }}</strong> are due this month — Total <strong class="text-amber-900">₹{{ number_format($totalPendingAmount, 2) }}</strong>.
                    @else
                        Attention: <strong class="text-amber-900">{{ $totalPendingCount }}</strong> EMI payments are pending this month, totaling <strong class="text-amber-900">₹{{ number_format($totalPendingAmount, 2) }}</strong>.
                    @endif
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
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">EMI & Interest Payment Release</h1>
            <p class="text-xs text-slate-500 mt-1">Record monthly EMI payments, principal/interest splits and payoff adjustments.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openInterestLogsModal()" class="inline-flex items-center gap-2 px-3.5 py-2 border border-slate-250 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition shadow-2xs uppercase tracking-wide cursor-pointer bg-white">
                <svg class="w-3.5 h-3.5 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Interest Edit Log
            </button>
            <button @click="openAddModal()" class="inline-flex items-center gap-2 px-3.5 py-2 border border-slate-250 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition shadow-2xs uppercase tracking-wide cursor-pointer bg-white">
                <svg class="w-3.5 h-3.5 text-slate-550" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Loan Account
            </button>
        </div>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Overdue Amount --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Overdue Amount</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:text-rose-700 group-hover:bg-rose-50/50">Urgent</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-rose-700 font-mono tracking-tight block group-hover:text-rose-600 transition-colors duration-300">₹{{ number_format((float)($overdueAmount ?? 0), 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">{{ $overdueCount ?? 0 }} Overdue Installment(s)</p>
            </div>
        </div>

        {{-- Card 2: Active Loans --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-slate-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-slate-300 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-slate-50 flex items-center justify-center text-slate-600 border border-slate-200/60 transition-all duration-300 group-hover:bg-slate-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Active Loans</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-slate-400 group-hover:text-slate-800 group-hover:bg-slate-100">Live</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-2xl xl:text-3xl font-black text-slate-800 font-sans tracking-tight block group-hover:text-slate-900 transition-colors duration-300">{{ $activeLoansCount ?? 0 }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Total active accounts.</p>
            </div>
        </div>

        {{-- Card 3: Total Principal Paid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Principal Paid</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:text-emerald-700 group-hover:bg-emerald-50/50">Cleared</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">₹{{ number_format((float)($totalPaidPrincipal ?? 0), 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Principal repaid.</p>
            </div>
        </div>

        {{-- Card 4: Paid Interest Cost --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)]">
            <div class="flex flex-wrap xl:flex-nowrap items-start xl:items-center justify-between gap-2 mb-4 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider leading-tight">Interest Paid</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2 py-0.5 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:text-amber-700 group-hover:bg-amber-50/50">Expense</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-xl xl:text-2xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">₹{{ number_format((float)($totalPaidInterest ?? 0), 2) }}</span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Interest paid so far.</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 py-4 px-4 shadow-xs mb-4 mt-4">
        <form method="GET" action="{{ route('loans.index') }}" class="flex flex-wrap items-center gap-2.5 text-xs font-semibold w-full">
            
            {{-- Search Account / Loan No --}}
            <div class="relative flex-grow min-w-[200px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="loan_account_no" value="{{ request('loan_account_no') }}" placeholder="Search Account / Loan No..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none text-xs font-semibold text-slate-800 transition-all">
            </div>

            {{-- Lending Bank Dropdown --}}
            <div class="relative flex-grow min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <select name="lender_name" onchange="this.form.submit()" class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none text-xs font-semibold text-slate-800 cursor-pointer transition-all appearance-none">
                    <option value="">All Lending Banks</option>
                    @foreach($banks as $b)
                        <option value="{{ $b->bank_name }}" {{ request('lender_name') === $b->bank_name ? 'selected' : '' }}>{{ $b->bank_name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Associated Project Select --}}
            <div class="relative flex-grow min-w-[180px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <select name="project_id" onchange="this.form.submit()" class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none text-xs font-semibold text-slate-800 cursor-pointer transition-all appearance-none">
                    <option value="">All Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Loan Status Select --}}
            <div class="relative flex-grow min-w-[150px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <select name="status" onchange="this.form.submit()" class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-xl outline-none text-xs font-semibold text-slate-800 cursor-pointer transition-all appearance-none">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Hidden Submit for Enter Key --}}
            <button type="submit" style="display: none;"></button>

            {{-- Buttons --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('loans.index') }}" class="px-6 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-[10px] font-extrabold uppercase tracking-widest transition-all inline-flex items-center gap-2 cursor-pointer shadow-xs shrink-0 select-none">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    RESET FILTERS
                </a>
            </div>
        </form>
    </div>

    {{-- Loans List Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #loans-table thead th {
                border-color: #8a7522 !important;
            }
            #loans-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #loans-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-x-auto">
            <table id="loans-table" class="w-full text-xs text-left border-collapse">
                              <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">SL NO</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-left">LOAN ACCOUNT / PROJECT</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">LENDING BANK</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">OUTSTANDING</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">CURRENT EMI</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">PRINCIPAL</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">INTEREST</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">NEXT DUE DATE</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-center">PAYMENT STATUS</th>
                        <th class="px-4 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="loans-tbody" class="divide-y divide-[#EAE3CD] text-center font-semibold text-slate-700 bg-white">
                    @forelse($loans as $idx => $loan)
                        @php
                            $paymentStatus = 'PAID';
                            $statusColor = 'bg-emerald-50 border-emerald-100 text-emerald-700';

                            if ($loan->status === 'Closed') {
                                $paymentStatus = 'CLOSED';
                                $statusColor = 'bg-slate-100 border-slate-200 text-slate-500';
                            } elseif ($loan->next_emi) {
                                $dueDate = \Carbon\Carbon::parse($loan->next_emi->due_date);
                                if ($dueDate->lt(now()->startOfDay())) {
                                    $paymentStatus = 'OVERDUE';
                                    $statusColor = 'bg-rose-50 border-rose-100 text-rose-700 animate-pulse';
                                } else {
                                    $paymentStatus = 'DUE';
                                    $statusColor = 'bg-amber-50 border-amber-100 text-amber-700';
                                }
                            }
                        @endphp
                        <tr class="transition-colors text-xs">
                            <td class="px-4 py-3.5 border font-bold text-slate-400 text-center">{{ $loans->firstItem() + $idx }}</td>
                            <td class="px-4 py-3.5 border text-left">
                                <div class="font-bold text-slate-900 font-mono">{{ $loan->loan_account_no ?? '—' }}</div>
                                <div class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $loan->project->name ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3.5 border text-slate-900 font-bold text-center">
                                <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-slate-105 border border-slate-200 text-[10px] uppercase tracking-wider shadow-sm">
                                    {{ $loan->lender_name }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono text-rose-700 font-extrabold">
                                ₹{{ number_format((float)$loan->outstanding_balance, 2) }}
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono text-slate-900 font-bold">
                                @if($loan->next_emi && $loan->status === 'Active')
                                    ₹{{ number_format((float)$loan->next_emi->emi_amount - (float)$loan->next_emi->amount_paid, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono text-slate-600">
                                @if($loan->next_emi && $loan->status === 'Active')
                                    ₹{{ number_format((float)$loan->next_emi->principal_component, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-right font-mono text-slate-600">
                                @if($loan->next_emi && $loan->status === 'Active')
                                    ₹{{ number_format((float)$loan->next_emi->interest_component, 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-center text-slate-650">
                                @if($loan->next_emi && $loan->status === 'Active')
                                    {{ \Carbon\Carbon::parse($loan->next_emi->due_date)->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3.5 border text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md border text-[10px] font-extrabold uppercase tracking-wider shadow-xs {{ $statusColor }}">
                                    {{ $paymentStatus }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border text-right pr-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if($loan->next_emi && $loan->status === 'Active')
                                        <button @click="openPayModal({ id: {{ $loan->next_emi->id }}, emi_amount: {{ $loan->next_emi->emi_amount }}, amount_paid: {{ $loan->next_emi->amount_paid }} }, { id: {{ $loan->id }}, loan_account_no: '{{ addslashes($loan->loan_account_no) }}', lender_name: '{{ addslashes($loan->lender_name) }}', project: { name: '{{ addslashes($loan->project->name ?? 'N/A') }}' } })"
                                                class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all shadow-xs active:scale-95 cursor-pointer">
                                            Pay EMI
                                        </button>
                                    @endif

                                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                        <button @click="open = !open" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all inline-flex items-center gap-1 cursor-pointer">
                                            More
                                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div x-show="open" class="absolute right-0 mt-1 w-44 bg-white border border-slate-200 rounded-xl shadow-lg z-50 py-1 text-left font-semibold text-slate-700 text-[10px] uppercase tracking-wide" style="display: none;" x-transition>
                                            <a href="{{ route('loans.schedule', $loan->id) }}" class="block px-4 py-2.5 hover:bg-slate-50 transition-colors">
                                                View Ledger
                                            </a>
                                            @if($loan->status === 'Active')
                                                <button @click="openPayoffModal({ id: {{ $loan->id }}, loan_account_no: '{{ addslashes($loan->loan_account_no) }}', lender_name: '{{ addslashes($loan->lender_name) }}', outstanding_balance: {{ $loan->outstanding_balance }} }, 'prepayment')" class="w-full text-left block px-4 py-2.5 hover:bg-slate-50 transition-colors cursor-pointer font-semibold uppercase tracking-wide">
                                                    Principal Payoff
                                                </button>
                                                <button @click="openPayoffModal({ id: {{ $loan->id }}, loan_account_no: '{{ addslashes($loan->loan_account_no) }}', lender_name: '{{ addslashes($loan->lender_name) }}', outstanding_balance: {{ $loan->outstanding_balance }} }, 'foreclosure')" class="w-full text-left block px-4 py-2.5 hover:bg-slate-50 transition-colors cursor-pointer font-semibold uppercase tracking-wide">
                                                    Foreclosure
                                                </button>
                                                <button @click="openEditInterestModal({ id: {{ $loan->id }}, loan_account_no: '{{ addslashes($loan->loan_account_no) }}', lender_name: '{{ addslashes($loan->lender_name) }}', interest_rate: {{ $loan->interest_rate }} })" class="w-full text-left block px-4 py-2.5 hover:bg-slate-50 transition-colors cursor-pointer font-semibold uppercase tracking-wide">
                                                    Adjust Interest
                                                </button>
                                            @endif
                                            <button @click="openInterestLogsModal('{{ $loan->loan_account_no }}')" class="w-full text-left block px-4 py-2.5 hover:bg-slate-50 transition-colors cursor-pointer font-semibold uppercase tracking-wide">
                                                Interest Edit Log
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-slate-400 italic">No loan records found. Please configure a bank loan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Controls --}}
        @if($loans instanceof \Illuminate\Pagination\AbstractPaginator && $loans->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    Showing <span class="text-slate-900">{{ $loans->firstItem() }}</span> to 
                    <span class="text-slate-900">{{ $loans->lastItem() }}</span> of 
                    <span class="text-slate-900">{{ number_format($loans->total()) }}</span> Loans
                </div>
                <div class="flex items-center gap-1.5">
                    {{-- Previous Page Link --}}
                    @if ($loans->onFirstPage())
                        <span class="px-2.5 py-1 bg-white border border-slate-100 text-slate-300 rounded-lg text-[10px] font-bold uppercase tracking-wider cursor-not-allowed bg-slate-50/50">
                            Prev
                        </span>
                    @else
                        <a href="{{ $loans->previousPageUrl() }}" 
                           class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 hover:bg-slate-50 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors">
                            Prev
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $currentPage = $loans->currentPage();
                        $lastPage = $loans->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $loans->url(1) }}" 
                           class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 hover:bg-slate-50 rounded-lg text-[10px] font-bold transition-colors">
                            1
                        </a>
                        @if ($start > 2)
                            <span class="px-2 py-1 text-[10px] text-slate-400 font-bold">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $currentPage)
                            <span class="px-2.5 py-1 bg-primary text-white border border-primary rounded-lg text-[10px] font-bold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $loans->url($page) }}" 
                               class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 hover:bg-slate-50 rounded-lg text-[10px] font-bold transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="px-2 py-1 text-[10px] text-slate-400 font-bold">...</span>
                        @endif
                        <a href="{{ $loans->url($lastPage) }}" 
                           class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 hover:bg-slate-50 rounded-lg text-[10px] font-bold transition-colors">
                            {{ $lastPage }}
                        </a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($loans->hasMorePages())
                        <a href="{{ $loans->nextPageUrl() }}" 
                           class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 hover:bg-slate-50 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors">
                            Next
                        </a>
                    @else
                        <span class="px-2.5 py-1 bg-white border border-slate-100 text-slate-300 rounded-lg text-[10px] font-bold uppercase tracking-wider cursor-not-allowed bg-slate-50/50">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- Payoff / Foreclosure Modal --}}
    <div x-show="payoffModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="payoffModalOpen = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 border-b border-primary-500/10 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest" x-text="payoffForm.action_type === 'foreclosure' ? 'Process Full Foreclosure' : 'Principal Payoff Adjustment'"></h3>
                    <button @click="payoffModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form @submit.prevent="submitPayoffForm">
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    
                    {{-- 1. Loan Overview --}}
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">1. Account Overview</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs font-semibold text-slate-700">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Loan A/C No</span>
                                <span class="font-mono text-slate-900 font-extrabold" x-text="activePayoffLoan ? activePayoffLoan.loan_account_no : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Lending Bank</span>
                                <span class="text-slate-900 font-bold" x-text="activePayoffLoan ? activePayoffLoan.lender_name : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Outstanding Principal</span>
                                <span class="text-rose-700 font-extrabold font-mono" x-text="activePayoffLoan ? '₹' + Number(activePayoffLoan.outstanding_balance).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '—'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Payoff Splits --}}
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2.5">2. Payoff Calculations</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Action Type *</label>
                                <select x-model="payoffForm.action_type" required @change="if (payoffForm.action_type === 'foreclosure') { payoffForm.amount = activePayoffLoan ? parseFloat(activePayoffLoan.outstanding_balance).toFixed(2) : ''; }" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                    <option value="prepayment">Prepayment (Reduce Principal)</option>
                                    <option value="foreclosure">Full Foreclosure (Payoff Account)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Principal Amount to Pay (₹) *</label>
                                <input type="number" step="0.01" min="0.01" x-model="payoffForm.amount" :readonly="payoffForm.action_type === 'foreclosure'" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-850 focus:outline-none transition-all" :class="payoffForm.action_type === 'foreclosure' ? 'bg-slate-100 cursor-not-allowed text-slate-650' : ''">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Prepayment / Foreclosure Charges (₹)</label>
                                <input type="number" step="0.01" min="0" x-model="payoffForm.prepayment_charges" placeholder="0.00" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-850 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Interest Adjustment / Rebate (₹)</label>
                                <input type="number" step="0.01" x-model="payoffForm.interest_adjustment" placeholder="0.00 (debit positive, credit negative)" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-855 focus:outline-none transition-all">
                            </div>
                            <template x-if="payoffForm.action_type === 'prepayment'">
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Rescheduling Option *</label>
                                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-700">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" value="reduce_emi" x-model="payoffForm.reschedule_option" class="text-[#a38c29] focus:ring-[#a38c29]/20">
                                            Reduce monthly installment (EMI), keep tenure same
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" value="reduce_tenure" x-model="payoffForm.reschedule_option" class="text-[#a38c29] focus:ring-[#a38c29]/20">
                                            Reduce outstanding tenure (months), keep EMI same
                                        </label>
                                    </div>
                                </div>
                            </template>
                            <div class="col-span-1 sm:col-span-2 bg-indigo-50/50 p-3.5 rounded-xl border border-indigo-100 flex items-center justify-between text-xs font-black">
                                <span class="text-indigo-950 uppercase tracking-wider text-[10px]">Net Payout Amount</span>
                                <span class="text-indigo-950 text-sm font-mono" x-text="'₹' + Number(Number(payoffForm.amount || 0) + Number(payoffForm.prepayment_charges || 0) + Number(payoffForm.interest_adjustment || 0)).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Voucher Settings --}}
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2.5">3. Payout & Reference Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Bank / Cash Account <span class="text-rose-500">*</span></label>
                                <select x-model="payoffForm.bank_account_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                    <option value="">Select Account...</option>
                                    @foreach($assetAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Payment Date *</label>
                                <input type="date" x-model="payoffForm.prepayment_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-850 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Transaction / Cheque / UTR No. <span class="text-rose-500">*</span></label>
                                <input type="text" x-model="payoffForm.reference_no" required placeholder="e.g. UTR1087349137" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-855 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-550 uppercase tracking-wider mb-1.5">Remarks / Reason</label>
                                <input type="text" x-model="payoffForm.remarks" placeholder="Optional notes..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-semibold text-slate-855 focus:outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                    <button type="button" @click="payoffModalOpen = false" class="px-4 py-2 border border-slate-250 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-rose-600/20 active:scale-95 cursor-pointer" x-text="payoffForm.action_type === 'foreclosure' ? 'Foreclose Loan' : 'Post Prepayment'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Prepayment Logs Modal --}}
    <div x-show="logsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="logsModalOpen = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 border-b border-primary-500/10 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest" x-text="'Prepayment Logs: ' + activeAccountNo"></h3>
                    <button @click="logsModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
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

    {{-- Interest Edit Logs Modal --}}
    <div x-show="interestLogsModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="interestLogsModalOpen = false"></div>
        <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up flex flex-col max-h-[85vh]">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 border-b border-primary-500/10 rounded-t-2xl shrink-0">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="activeInterestLogAccount ? 'Interest Rate Modification Log: ' + activeInterestLogAccount : 'Interest Rate Modification Log'"></span>
                    </h3>
                    <button @click="interestLogsModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto grow">
                @if($interestLogs->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">No interest rate modifications recorded yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 border-r">Date / Time</th>
                                    <th class="px-4 py-3 border-r">Loan A/C</th>
                                    <th class="px-4 py-3 border-r">Old Rate</th>
                                    <th class="px-4 py-3 border-r">New Rate</th>
                                    <th class="px-4 py-3 border-r">Period</th>
                                    <th class="px-4 py-3">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs text-slate-700 font-medium">
                                @foreach($interestLogs as $log)
                                    <tr class="hover:bg-slate-50/60 transition-colors" x-show="!activeInterestLogAccount || activeInterestLogAccount === '{{ $log->loan ? $log->loan->loan_account_no : '' }}'">
                                        <td class="px-4 py-3 border-r text-slate-500 font-mono text-[11px] whitespace-nowrap">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                        <td class="px-4 py-3 border-r font-bold text-slate-900">{{ $log->loan ? $log->loan->loan_account_no : '—' }}</td>
                                        <td class="px-4 py-3 border-r font-mono text-rose-600 font-bold">{{ $log->old_interest_rate }}%</td>
                                        <td class="px-4 py-3 border-r font-mono text-emerald-700 font-bold">{{ $log->new_interest_rate }}%</td>
                                        <td class="px-4 py-3 border-r text-slate-600 capitalize">{{ $log->interest_period }}</td>
                                        <td class="px-4 py-3 text-slate-500 text-[11px]">{{ $log->reason ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end shrink-0">
                <button type="button" @click="interestLogsModalOpen = false" class="px-4 py-2 bg-slate-200 text-slate-700 hover:bg-slate-300 rounded-xl text-xs font-bold uppercase tracking-wide transition">Close</button>
            </div>
        </div>
    </div>

    {{-- Edit Interest Rate Modal --}}
    <div x-show="editInterestModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editInterestModalOpen = false"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 border-b border-primary-500/10 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest" x-text="editLoan ? 'Edit Interest Rate: A/C ' + editLoan.loan_account_no : 'Edit Interest Rate'"></h3>
                    <button @click="editInterestModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
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
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-[#a38c29]/20">Update Interest Rate</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Pay EMI Modal --}}
    <div x-show="payModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="payModalOpen = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 border-b border-primary-500/10 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest" x-text="activeInst ? 'Payment Release Voucher: Installment #' + activeInst.installment_no : 'Payment Release Voucher'"></h3>
                    <button @click="payModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form @submit.prevent="submitPayForm">
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    
                    {{-- 1. Payment Details Grid --}}
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">1. Loan & EMI Information</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs font-semibold text-slate-700">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Loan A/C No</span>
                                <span class="font-mono text-slate-900 font-extrabold" x-text="activeLoan ? activeLoan.loan_account_no : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Lending Bank</span>
                                <span class="text-slate-900 font-bold" x-text="activeLoan ? activeLoan.lender_name : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Project</span>
                                <span class="text-slate-900" x-text="activeLoan && activeLoan.project ? activeLoan.project.name : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">EMI Installment</span>
                                <span class="text-slate-950 font-bold" x-text="activeInst ? '#' + activeInst.installment_no : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Due Date</span>
                                <span class="text-rose-700 font-bold font-mono" x-text="activeInst ? new Date(activeInst.due_date).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'}) : '—'"></span>
                            </div>
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">EMI Amount</span>
                                <span class="text-indigo-850 font-extrabold font-mono" x-text="activeInst ? '₹' + Number(activeInst.emi_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '—'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Accounting Split Table --}}
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2.5">2. Accounting Split</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                            <table class="w-full text-left text-xs font-semibold">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-[9px] font-black text-slate-500 uppercase tracking-wider">
                                        <th class="px-4 py-2 border-r">Component Name</th>
                                        <th class="px-4 py-2 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    <tr>
                                        <td class="px-4 py-2.5 border-r">Principal Payoff</td>
                                        <td class="px-4 py-2.5 text-right font-mono text-slate-900" x-text="activeInst ? '₹' + Number(activeInst.principal_component).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '—'"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 border-r">Interest Expense</td>
                                        <td class="px-4 py-2.5 text-right font-mono text-slate-900" x-text="activeInst ? '₹' + Number(activeInst.interest_component).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '—'"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2.5 border-r flex items-center justify-between">
                                            <span>Other Charges / Bank Penalty</span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">(Edit if applicable)</span>
                                        </td>
                                        <td class="px-3 py-1.5 text-right font-mono text-slate-900">
                                            <input type="number" step="0.01" min="0" x-model.number="payForm.other_charges" class="w-32 px-2.5 py-1 text-right bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]/20 rounded-lg text-xs font-extrabold outline-none">
                                        </td>
                                    </tr>
                                    <tr class="bg-indigo-50/50 font-black">
                                        <td class="px-4 py-2.5 border-r text-indigo-950 uppercase tracking-wider text-[10px]">Total Payment Release</td>
                                        <td class="px-4 py-2.5 text-right font-mono text-indigo-950 text-sm" x-text="activeInst ? '₹' + Number(Number(payForm.amount) + Number(payForm.other_charges || 0)).toLocaleString('en-IN', {minimumFractionDigits: 2}) : '—'"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 3. Payment Information --}}
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2.5">3. Payment & Reference Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-555 uppercase tracking-wider mb-1.5">Bank / Cash Account <span class="text-rose-500">*</span></label>
                                <select x-model="payForm.bank_account_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 font-extrabold focus:outline-none transition-all cursor-pointer">
                                    <option value="">Select Account...</option>
                                    @foreach($assetAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-555 uppercase tracking-wider mb-1.5">Payment Date *</label>
                                <input type="date" x-model="payForm.paid_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-850 focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-555 uppercase tracking-wider mb-1.5">Payment Mode *</label>
                                <select x-model="payForm.payment_mode" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 focus:outline-none transition-all cursor-pointer">
                                    <option value="Bank Transfer">Bank Transfer / NEFT / RTGS / IMPS</option>
                                    <option value="Cheque">Cheque Payout</option>
                                    <option value="Direct Debit">Direct Bank Debit (ECS)</option>
                                    <option value="Cash">Cash Payout</option>
                                    <option value="Online">Online Gateway Payment</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-555 uppercase tracking-wider mb-1.5">Transaction / Cheque / UTR No. <span class="text-rose-500">*</span></label>
                                <input type="text" x-model="payForm.reference_no" required placeholder="e.g. UTR1087349137" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-855 focus:outline-none transition-all">
                            </div>
                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-555 uppercase tracking-wider mb-1.5">Remarks / Internal Payout Notes</label>
                                <textarea x-model="payForm.remarks" placeholder="Optional notes..." rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-semibold text-slate-855 focus:outline-none transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                    <button type="button" @click="payModalOpen = false" class="px-4 py-2 border border-slate-250 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-emerald-600/20 active:scale-95 cursor-pointer">
                        Release Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create Loan Modal --}}
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-4 border-b border-primary-500/10 rounded-t-2xl">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest">Create New Project Loan Account</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitAddForm($event)" novalidate>
                <div class="p-6 grid grid-cols-2 gap-4 max-h-[70vh] overflow-y-auto">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Associated Project <span class="text-rose-500">*</span></label>
                        <select x-model="addForm.project_id" required
                                :class="errors.project_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="">Select Project...</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <template x-if="errors.project_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.project_id) ? errors.project_id[0] : errors.project_id"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Loan Account No <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="addForm.loan_account_no" required placeholder="e.g. LN-897937402"
                               :class="errors.loan_account_no ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        <template x-if="errors.loan_account_no"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.loan_account_no) ? errors.loan_account_no[0] : errors.loan_account_no"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Lending Bank <span class="text-rose-500">*</span></label>
                        <select x-model="addForm.lender_name" required
                                :class="errors.lender_name ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all cursor-pointer">
                            <option value="">Select Lending Bank...</option>
                            @foreach($banks as $b)
                                <option value="{{ $b->bank_name }}">{{ $b->bank_name }}</option>
                            @endforeach
                        </select>
                        <template x-if="errors.lender_name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.lender_name) ? errors.lender_name[0] : errors.lender_name"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Total Sanctioned Principal (₹) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" x-model="addForm.principal_amount" required
                               :class="errors.principal_amount ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        <template x-if="errors.principal_amount"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.principal_amount) ? errors.principal_amount[0] : errors.principal_amount"></p></template>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Interest Rate <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="addForm.interest_rate" required placeholder="e.g. 7.50"
                                   :class="errors.interest_rate ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                   class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <template x-if="errors.interest_rate"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.interest_rate) ? errors.interest_rate[0] : errors.interest_rate"></p></template>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Interest Period <span class="text-rose-500">*</span></label>
                            <select x-model="addForm.interest_period" required
                                    :class="errors.interest_period ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                    class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all cursor-pointer">
                                <option value="annual">Per Annum (Yearly)</option>
                                <option value="monthly">Per Month (Monthly)</option>
                            </select>
                            <template x-if="errors.interest_period"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.interest_period) ? errors.interest_period[0] : errors.interest_period"></p></template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Loan Tenure (Months) <span class="text-rose-500">*</span></label>
                        <input type="number" x-model="addForm.tenure_months" required placeholder="e.g. 120"
                               :class="errors.tenure_months ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        <template x-if="errors.tenure_months"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.tenure_months) ? errors.tenure_months[0] : errors.tenure_months"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Repayment Start Date <span class="text-rose-500">*</span></label>
                        <input type="date" x-model="addForm.start_date" required
                               :class="errors.start_date ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                               class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                        <template x-if="errors.start_date"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.start_date) ? errors.start_date[0] : errors.start_date"></p></template>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Schedule Type <span class="text-rose-500">*</span></label>
                        <select x-model="addForm.schedule_type" required
                                :class="errors.schedule_type ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 bg-slate-50'"
                                class="w-full px-3 py-2 border focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="reducing_balance">Reducing Balance</option>
                            <option value="flat">Flat Rate</option>
                        </select>
                        <template x-if="errors.schedule_type"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.schedule_type) ? errors.schedule_type[0] : errors.schedule_type"></p></template>
                    </div>



                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex justify-between">
                            <span>Interest Expense Account</span>
                            <span class="text-[9px] text-slate-400 font-normal normal-case tracking-normal">(Optional)</span>
                        </label>
                        <select x-model="addForm.interest_account_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all cursor-pointer">
                            <option value="">Leave empty to auto-create generic interest account...</option>
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
</div>

<script>
function loanApp() {
    return {
        errors: {},
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
        payModalOpen: false,
        activeInst: null,
        activeLoan: null,
        payForm: {
            amount: '',
            paid_date: '',
            bank_account_id: '',
            payment_mode: 'Bank Transfer',
            reference_no: '',
            remarks: '',
            other_charges: 0
        },
        payoffModalOpen: false,
        activePayoffLoan: null,
        payoffForm: {
            action_type: 'prepayment',
            amount: '',
            prepayment_charges: '',
            interest_adjustment: '',
            bank_account_id: '',
            prepayment_date: '',
            reschedule_option: 'reduce_emi',
            reference_no: '',
            remarks: ''
        },
        openPayModal(installment, loan) {
            this.activeInst = installment;
            this.activeLoan = loan;
            this.payForm = {
                amount: (parseFloat(installment.emi_amount) - parseFloat(installment.amount_paid)).toFixed(2),
                paid_date: new Date().toISOString().split('T')[0],
                bank_account_id: '',
                payment_mode: 'Bank Transfer',
                reference_no: '',
                remarks: '',
                other_charges: 0
            };
            this.payModalOpen = true;
        },
        submitPayForm() {
            if (!this.activeLoan || !this.activeInst) return;
            const url = `{{ url('/loans') }}/${this.activeLoan.id}/pay-emi/${this.activeInst.id}`;
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
                    this.showToast(data.error || 'Failed to submit payment.', 'error');
                } else {
                    this.showToast('EMI Repayment Voucher posted successfully.');
                    this.payModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },
        openPayoffModal(loan, type = 'prepayment') {
            this.activePayoffLoan = loan;
            this.payoffForm = {
                action_type: type,
                amount: type === 'foreclosure' ? parseFloat(loan.outstanding_balance).toFixed(2) : '',
                prepayment_charges: '',
                interest_adjustment: '',
                bank_account_id: '',
                prepayment_date: new Date().toISOString().split('T')[0],
                reschedule_option: 'reduce_emi',
                reference_no: '',
                remarks: ''
            };
            this.payoffModalOpen = true;
        },
        submitPayoffForm() {
            if (!this.activePayoffLoan) return;
            const url = `{{ url('/loans') }}/${this.activePayoffLoan.id}/prepay`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.payoffForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to submit payoff transaction.', 'error');
                } else {
                    this.showToast(this.payoffForm.action_type === 'foreclosure' ? 'Loan accounts fully foreclosed and closed.' : 'Principal prepayment processed successfully.');
                    this.payoffModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },
        openAddModal() {
            this.errors = {};
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
        interestLogsModalOpen: false,
        activeInterestLogAccount: null,
        openInterestLogsModal(accountNo = null) {
            this.activeInterestLogAccount = accountNo;
            this.interestLogsModalOpen = true;
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
            const url = `{{ url('/loans') }}/${this.editLoan.id}/update-interest`;
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
        submitAddForm(e) {
            if (e) e.preventDefault();
            let clientErrors = {};
            if (!this.addForm.project_id) {
                clientErrors.project_id = ['The project field is required.'];
            }
            if (!this.addForm.loan_account_no || !String(this.addForm.loan_account_no).trim()) {
                clientErrors.loan_account_no = ['The loan account number field is required.'];
            }
            if (!this.addForm.lender_name || !String(this.addForm.lender_name).trim()) {
                clientErrors.lender_name = ['The lending bank field is required.'];
            }
            if (!this.addForm.principal_amount) {
                clientErrors.principal_amount = ['The principal amount field is required.'];
            }
            if (!this.addForm.interest_rate) {
                clientErrors.interest_rate = ['The interest rate field is required.'];
            }
            if (!this.addForm.interest_period) {
                clientErrors.interest_period = ['The interest period field is required.'];
            }
            if (!this.addForm.tenure_months) {
                clientErrors.tenure_months = ['The tenure field is required.'];
            }
            if (!this.addForm.start_date) {
                clientErrors.start_date = ['The start date field is required.'];
            }
            if (!this.addForm.schedule_type) {
                clientErrors.schedule_type = ['The schedule type field is required.'];
            }
            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }
            this.errors = {};

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
                    if (data.errors) {
                        this.errors = data.errors;
                    }
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
