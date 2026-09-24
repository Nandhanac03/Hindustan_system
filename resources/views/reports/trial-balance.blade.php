<x-erp-layout title="Trial Balance Workspace" headerTitle="Accounting & Financial Reports">

<div class="w-full space-y-6" x-data="{
    collapsedGroups: {},
    toggleGroup(code) {
        this.collapsedGroups[code] = !this.collapsedGroups[code];
    },
    isGroupCollapsed(code) {
        return !!this.collapsedGroups[code];
    }
}">

    <!-- ── 1. HEADER & BREADCRUMBS ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span class="text-slate-500 uppercase">ACCOUNTING & FINANCE</span>
                <span>›</span>
                <span class="text-[#a38c29] font-black uppercase tracking-wider">TRIAL BALANCE</span>
            </nav>
            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <div class="p-2 bg-amber-50 rounded-xl text-[#a38c29] border border-amber-200/80 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
                <span>Trial Balance</span>
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                View consolidated trial balance for the selected period and project.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-center">
            <span class="px-3.5 py-2 rounded-xl text-xs font-black bg-amber-50 text-[#8a7522] border border-amber-200/90 shadow-2xs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#a38c29] animate-pulse"></span>
                <span>Audited Ledger Verification</span>
            </span>

            <button type="button" onclick="exportTrialBalanceToExcel()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold flex items-center gap-1.5 shadow-sm transition active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Export to Excel</span>
            </button>

            <button type="button" onclick="window.print()" class="px-4 py-2 bg-white hover:bg-rose-50 border border-rose-200 text-rose-700 hover:text-rose-800 rounded-xl text-xs font-extrabold flex items-center gap-1.5 shadow-2xs transition active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Download PDF</span>
            </button>
        </div>
    </div>

    {{-- PILL STYLE FILTER BAR (EXACT PROFIT & LOSS STYLE) --}}
    <form id="trialBalanceForm" action="{{ route('reports.trial_balance') }}" method="GET" class="bg-white p-3 rounded-2xl border border-slate-200/80 shadow-2xs relative">
        <div class="flex flex-wrap items-center gap-3">
            
            {{-- 1. Project (Default First) --}}
            <div class="flex-1 min-w-[170px] relative">
                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-slate-400 pointer-events-none z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <select name="project_id" onchange="this.form.submit()" class="w-full bg-[#F5F4F0] hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29] focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl pl-9 pr-8 py-2.5 text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs relative z-0">
                        @foreach($allProjects as $proj)
                            <option value="{{ $proj->id }}" {{ (string)$selectedProjectId === (string)$proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                        @endforeach
                        <option value="all" {{ (string)$selectedProjectId === 'all' ? 'selected' : '' }}>All Projects</option>
                    </select>
                </div>
            </div>

            {{-- 2. Period Type --}}
            <div class="flex-1 min-w-[150px] relative">
                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-[#a38c29] pointer-events-none z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <select name="period_type" id="periodTypeSelect" onchange="onPeriodTypeChange(this.value)" class="w-full bg-[#F5F4F0] hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29] focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl pl-9 pr-8 py-2.5 text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs relative z-0">
                        <option value="fy" {{ $periodType === 'fy' ? 'selected' : '' }}>Financial Year</option>
                        <option value="quarter" {{ $periodType === 'quarter' ? 'selected' : '' }}>Quarter</option>
                        <option value="month" {{ $periodType === 'month' ? 'selected' : '' }}>Month</option>
                        <option value="custom" {{ $periodType === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                    </select>
                </div>
            </div>

            {{-- 3. From Date --}}
            <div class="flex-1 min-w-[140px] relative">
                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-slate-400 pointer-events-none z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="date" name="from_date" id="fromDateInput" value="{{ $fromDate }}" onchange="this.form.submit()" class="w-full bg-[#F5F4F0] hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29] focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl pl-9 pr-3 py-2.5 text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs relative z-0">
                </div>
            </div>

            {{-- 4. To Date --}}
            <div class="flex-1 min-w-[140px] relative">
                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-slate-400 pointer-events-none z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="date" name="to_date" id="toDateInput" value="{{ $toDate }}" onchange="this.form.submit()" class="w-full bg-[#F5F4F0] hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29] focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl pl-9 pr-3 py-2.5 text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs relative z-0">
                </div>
            </div>

            {{-- 5. Report Level / View Mode --}}
            <div class="flex-1 min-w-[150px] relative">
                <div class="relative flex items-center">
                    <span class="absolute left-3.5 text-slate-400 pointer-events-none z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </span>
                    <select name="view_mode" onchange="this.form.submit()" class="w-full bg-[#F5F4F0] hover:bg-white focus:bg-white border border-[#a38c29] focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl pl-9 pr-8 py-2.5 text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs relative z-0">
                        <option value="detailed" {{ $viewMode === 'detailed' ? 'selected' : '' }}>Detailed</option>
                        <option value="summary" {{ $viewMode === 'summary' ? 'selected' : '' }}>Summary</option>
                    </select>
                </div>
            </div>

            {{-- 6. Hide Zero Balance Toggle Pill --}}
            <div class="bg-[#F5F4F0] hover:bg-white border border-slate-200 hover:border-[#a38c29] rounded-xl px-3.5 py-2 flex items-center h-[38px] transition shrink-0 shadow-2xs">
                <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                    <input type="checkbox" name="hide_zero" value="1" {{ $hideZero ? 'checked' : '' }} onchange="document.getElementById('trialBalanceForm').submit()" class="w-3.5 h-3.5 rounded text-[#a38c29] border-slate-300 focus:ring-[#a38c29] cursor-pointer">
                    <span class="text-xs font-bold text-slate-700 whitespace-nowrap">Hide Zero</span>
                </label>
            </div>

            {{-- 7. RESET FILTERS BUTTON (EXACT P&L STYLE) --}}
            <a href="{{ route('reports.trial_balance') }}" class="px-5 py-2.5 bg-[#8C7A2E] hover:bg-[#786826] text-white text-xs font-extrabold rounded-xl transition shadow-xs flex items-center gap-2 uppercase tracking-wider shrink-0 cursor-pointer hover:-translate-y-0.5 active:scale-95" title="Reset Filters">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>RESET FILTERS</span>
            </a>

        </div>
    </form>

    <!-- ── 3. AUDIT EQUALITY & BALANCE BANNER ── -->
    @if($isBalanced)
    <div class="rounded-2xl bg-emerald-50/90 border-2 border-emerald-500/80 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/20 text-xl font-bold">
                ✓
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-black text-emerald-950 uppercase tracking-wide flex items-center gap-2">
                    <span>TRIAL BALANCE EQUAL & BALANCED</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-200/80 text-emerald-900">Audited</span>
                </h3>
                <p class="text-xs text-emerald-800 font-medium mt-0.5">
                    Total debits and credits are equal (<strong class="font-mono font-bold">₹ {{ number_format($grandTotalDebit, 2) }}</strong>) for the selected period.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/90 px-4 py-2 rounded-xl border border-emerald-200 shadow-2xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Financial Period</span>
            <span class="text-xs font-mono font-black text-slate-800">{{ $financialYearLabel }}</span>
        </div>
    </div>
    @else
    <div class="rounded-2xl bg-rose-50 border-2 border-rose-500 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/20 text-xl font-bold">
                !
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-black text-rose-950 uppercase tracking-wide">
                    TRIAL BALANCE VARIANCE DETECTED
                </h3>
                <p class="text-xs text-rose-800 font-medium mt-0.5">
                    Total debits (₹ {{ number_format($grandTotalDebit, 2) }}) and credits (₹ {{ number_format($grandTotalCredit, 2) }}) differ by ₹ {{ number_format($diff, 2) }}.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/90 px-4 py-2 rounded-xl border border-rose-200 shadow-2xs">
            <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider block">Variance Amount</span>
            <span class="text-sm font-mono font-black text-rose-700">₹ {{ number_format($diff, 2) }}</span>
        </div>
    </div>
    @endif

    <!-- ── 4. TRIAL BALANCE DATA GRID (BRAND GOLD HEADER THEME) ── -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table id="trialBalanceTable" class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a741f] text-[10.5px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 w-36 text-white font-extrabold tracking-wider">ACCOUNT CODE</th>
                        <th class="px-5 py-3.5 text-white font-extrabold tracking-wider">ACCOUNT NAME / GROUP</th>
                        <th class="px-5 py-3.5 text-right w-44 text-white font-extrabold tracking-wider">OPENING BALANCE (₹)</th>
                        <th class="px-5 py-3.5 text-right w-44 text-white font-extrabold tracking-wider">PERIOD DEBIT (₹)</th>
                        <th class="px-5 py-3.5 text-right w-44 text-white font-extrabold tracking-wider">PERIOD CREDIT (₹)</th>
                        <th class="px-5 py-3.5 text-right w-48 text-white font-extrabold tracking-wider">CLOSING BALANCE (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    
                    @foreach($groupsData as $grpKey => $grp)
                        @php
                            $hasAccounts = !empty($grp['accounts']);
                        @endphp

                        <!-- Group Header Row (Rich Gold/Amber Themed) -->
                        <tr class="bg-amber-50/60 hover:bg-amber-100/60 transition-colors font-bold border-t-2 border-b border-amber-200/70 cursor-pointer select-none"
                            @click="toggleGroup('{{ $grp['code'] }}')">
                            
                            <td class="px-5 py-3 font-mono font-black text-slate-900 text-xs">
                                <div class="flex items-center gap-2">
                                    @if($viewMode === 'detailed' && $hasAccounts)
                                        <div class="w-5 h-5 rounded-md bg-white border border-amber-300 text-[#a38c29] flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-3 h-3 transition-transform duration-200"
                                                 :class="isGroupCollapsed('{{ $grp['code'] }}') ? '-rotate-90' : 'rotate-0'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="px-2.5 py-0.5 bg-[#a38c29] text-white rounded-md text-[11px] font-mono font-black shadow-2xs">
                                        {{ $grp['code'] }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-3 font-black text-slate-900 uppercase tracking-wide text-xs">
                                <div class="flex items-center gap-2">
                                    <span>{{ $grp['name'] }}</span>
                                    @if($hasAccounts)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-white border border-amber-200 text-[#8a7522] font-extrabold lowercase">
                                            {{ count($grp['accounts']) }} accounts
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['opening_balance'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['period_debit'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-slate-900">
                                ₹{{ number_format($grp['period_credit'], 2) }}
                            </td>

                            <td class="px-5 py-3 text-right font-mono font-black text-xs {{ $grp['closing_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">
                                ₹{{ number_format($grp['closing_balance'], 2) }} <span class="font-extrabold">{{ $grp['closing_side'] }}</span>
                            </td>
                        </tr>

                        <!-- Sub-Accounts (Detailed View) -->
                        @if($viewMode === 'detailed' && $hasAccounts)
                            @foreach($grp['accounts'] as $acc)
                            <tr x-show="!isGroupCollapsed('{{ $grp['code'] }}')"
                                class="hover:bg-amber-50/30 transition-colors bg-white">
                                
                                <td class="px-5 py-2.5 font-mono text-slate-500 pl-12 text-[11px] font-bold">
                                    {{ $acc['code'] }}
                                </td>

                                <td class="px-5 py-2.5">
                                    <a href="{{ route('vouchers.ledger.index', ['account_id' => $acc['id']]) }}" 
                                       class="font-semibold text-slate-800 hover:text-[#a38c29] hover:underline transition flex items-center gap-1.5 group"
                                       title="Click to view detailed general ledger">
                                        <span>{{ $acc['name'] }}</span>
                                        <svg class="w-3 h-3 text-slate-300 group-hover:text-[#a38c29] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-bold text-slate-700 text-xs">
                                    {{ $acc['opening_balance'] > 0 ? '₹'.number_format($acc['opening_balance'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-bold text-indigo-700 text-xs">
                                    {{ $acc['period_debit'] > 0 ? '₹'.number_format($acc['period_debit'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-bold text-amber-700 text-xs">
                                    {{ $acc['period_credit'] > 0 ? '₹'.number_format($acc['period_credit'], 2) : '0.00' }}
                                </td>

                                <td class="px-5 py-2.5 text-right font-mono font-black text-xs {{ $acc['closing_side'] === 'Dr' ? 'text-blue-700' : 'text-rose-700' }}">
                                    @if($acc['closing_balance'] > 0)
                                        ₹{{ number_format($acc['closing_balance'], 2) }} <span class="font-extrabold">{{ $acc['closing_side'] }}</span>
                                    @else
                                        0.00
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        @endif

                    @endforeach

                </tbody>
                <tfoot>
                    <tr class="bg-white text-slate-900 font-black text-xs uppercase border-t-2 border-b-2 border-[#a38c29] shadow-2xs">
                        <td class="px-5 py-4 font-black tracking-wider text-slate-900 text-xs" colspan="2">
                            TOTAL
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalOpening, 2) }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalDebit, 2) }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-900 text-xs">
                            ₹{{ number_format($grandTotalCredit, 2) }}
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-black text-slate-400 text-xs">
                            —
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- ── 5. INFORMATIONAL FOOTNOTE (ERP BRAND ACCENT) ── -->
    <!-- <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/80 flex items-center gap-3 text-xs text-slate-700 font-medium shadow-2xs">
        <div class="w-7 h-7 rounded-lg bg-amber-100/80 text-[#8a7522] border border-amber-200 flex items-center justify-center shrink-0 font-black text-sm">
            ℹ
        </div>
         <p>
            <strong class="text-slate-900 font-bold">Audit Note:</strong> Figures are dynamically aggregated based on all posted vouchers across the ERP system for the selected period. Click on any account name to inspect its detailed general ledger statement.
        </p> 
    </div> -->

</div>

<!-- ── SCRIPTS FOR PERIOD PRESETS & EXCEL EXPORT ── -->
<script>
function onPeriodTypeChange(type) {
    const today = new Date();
    const fromInput = document.getElementById('fromDateInput');
    const toInput = document.getElementById('toDateInput');

    if (type === 'fy') {
        fromInput.value = '2025-04-01';
        toInput.value = '2026-03-31';
    } else if (type === 'quarter') {
        const curMonth = today.getMonth();
        const qStartMonth = Math.floor(curMonth / 3) * 3;
        const qStart = new Date(today.getFullYear(), qStartMonth, 1);
        const qEnd = new Date(today.getFullYear(), qStartMonth + 3, 0);
        fromInput.value = formatDateYmd(qStart);
        toInput.value = formatDateYmd(qEnd);
    } else if (type === 'month') {
        const mStart = new Date(today.getFullYear(), today.getMonth(), 1);
        const mEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        fromInput.value = formatDateYmd(mStart);
        toInput.value = formatDateYmd(mEnd);
    }
    document.getElementById('trialBalanceForm').submit();
}

function formatDateYmd(d) {
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function exportTrialBalanceToExcel() {
    let table = document.getElementById('trialBalanceTable');
    if (!table) return;

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Trial Balance Summary Report\n";
    csvContent += "Financial Period: {{ $financialYearLabel }}\n\n";

    let rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        let cols = row.querySelectorAll('th, td');
        let rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/,/g, '').replace(/₹/g, 'Rs. ').replace(/\n/g, ' ').trim();
            rowData.push('"' + text + '"');
        });
        if (rowData.length > 0) {
            csvContent += rowData.join(",") + "\n";
        }
    });

    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "Trial_Balance_{{ date('Ymd_His') }}.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<style>
@media print {
    aside, header, nav, button, form, .no-print { display: none !important; }
    body { background-color: #ffffff !important; color: #000000 !important; font-size: 10pt; }
    .shadow-sm, .shadow-2xs, .shadow-xs { box-shadow: none !important; }
    .border { border-color: #cbd5e1 !important; }
}
</style>

</x-erp-layout>
