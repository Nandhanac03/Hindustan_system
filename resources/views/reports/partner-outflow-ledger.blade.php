<x-erp-layout title="Partner Statement Ledger & Capital Outflows" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6 font-sans print:p-0 print:m-0" 
     x-data="{
        activeTab: 'summary',
        searchQuery: '',

        setDatePreset(preset) {
            const fromInput = document.getElementById('filter_from_date');
            const toInput = document.getElementById('filter_to_date');
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const todayStr = `${yyyy}-${mm}-${dd}`;

            if (preset === 'all') {
                if (fromInput) fromInput.value = '';
                if (toInput) toInput.value = '';
            } else if (preset === 'this_month') {
                if (fromInput) fromInput.value = `${yyyy}-${mm}-01`;
                if (toInput) toInput.value = todayStr;
            } else if (preset === 'this_fy') {
                const fyStartYear = today.getMonth() >= 3 ? yyyy : yyyy - 1;
                if (fromInput) fromInput.value = `${fyStartYear}-04-01`;
                if (toInput) toInput.value = todayStr;
            }
            document.getElementById('partnerOutflowFilterForm').submit();
        },

        exportExcel() {
            const table = document.getElementById('partnerOutflowExcelTable');
            if (!table) return;
            const html = table.outerHTML;
            const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Partner_Capital_Outflow_Ledger_${new Date().toISOString().slice(0,10)}.xls`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        exportCSV() {
            let csv = [];
            const rows = document.querySelectorAll('#summaryTable tbody tr:not(.empty-row)');
            csv.push(['Partner Entity', 'Associated Project', 'Description Memo', 'Transaction Count', 'Last Date', 'Allocated Outflow (INR)', 'Share %'].join(','));
            
            rows.forEach(row => {
                const partner = row.querySelector('.partner-name')?.innerText.trim() || '';
                const project = row.querySelector('.project-name')?.innerText.trim() || '';
                const memo = (row.querySelector('.description-memo')?.innerText.trim() || '').replace(/,/g, ' ');
                const count = row.querySelector('.trans-count')?.innerText.trim() || '0';
                const lastDate = row.querySelector('.last-date')?.innerText.trim() || '—';
                const amount = (row.querySelector('.allocated-amount')?.innerText.trim() || '').replace(/[₹,]/g, '');
                const pct = (row.querySelector('.share-pct')?.innerText.trim() || '').replace(/%/g, '');
                csv.push([`\"${partner}\"`, `\"${project}\"`, `\"${memo}\"`, count, `\"${lastDate}\"`, amount, pct].join(','));
            });

            const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Partner_Outflow_Summary_${new Date().toISOString().slice(0,10)}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
     }">

    {{-- ── 1. BREADCRUMBS & TOP BANNER BOX ── --}}
    <div class="space-y-2 print:hidden">
        <nav class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
            <a href="/" class="hover:text-slate-600 transition">HOME</a>
            <span>›</span>
            <span>FINANCE & ANALYTICS</span>
            <span>›</span>
            <span class="text-[#a38c29]">PARTNER OUTFLOW LEDGER</span>
        </nav>

        {{-- Banner Card (Matching EMI Collection Trends Box UI with Right-Side Outflow Stat Box) --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/30 shadow-sm text-slate-900 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#a38c29] shadow-2xs">
                        <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black uppercase tracking-wider text-slate-900">PARTNER STATEMENT LEDGER & CAPITAL OUTFLOWS</h3>
                        <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">EXECUTIVE OUTFLOW LEDGER</span>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Track capital allocations, profit shares, and mapping of receipt distributions across project partners.</p>
            </div>

            {{-- Right side Total Allocated Outflow Stat Box --}}
            <div class="relative z-10 bg-[#faf9f5]/90 border border-[#a38c29]/40 rounded-2xl px-5 py-2.5 text-right shadow-2xs shrink-0 min-w-[200px]">
                <div class="text-[9.5px] font-black uppercase tracking-wider text-[#a38c29]">TOTAL ALLOCATED OUTFLOW</div>
                <div class="text-lg sm:text-xl font-black text-slate-900 font-mono mt-0.5">
                    ₹{{ number_format($totalAllocatedOutflow, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. EXECUTIVE KPI METRICS CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- KPI 1: Total Allocated Outflow --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 border-l-[6px] border-l-[#a38c29] shadow-xs flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">TOTAL ALLOCATED OUTFLOW</span>
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tight mt-1 group-hover:text-[#a38c29] transition-colors">
                        ₹{{ number_format($totalAllocatedOutflow, 2) }}
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#a38c29] border border-amber-200/80 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Capital & profit outflows mapped</span>
                <span class="px-2 py-0.5 rounded-md bg-amber-50 text-[#8a7522] font-black text-[10px]">100% Realized</span>
            </div>
        </div>

        {{-- KPI 2: Active Participating Partners --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 border-l-[6px] border-l-emerald-500 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">ACTIVE PARTNERS</span>
                    <div class="text-2xl font-black text-slate-900 tracking-tight mt-1 group-hover:text-emerald-600 transition-colors">
                        {{ $activePartnersCount }} <span class="text-xs font-extrabold text-slate-400 uppercase">Parties</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/80 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>Total Registered: <strong class="text-slate-800">{{ $allPartners->count() }}</strong></span>
                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-black text-[10px]">Active Stake</span>
            </div>
        </div>

        @php
            $partnerListSorted = $outflowList->sortByDesc('amount')->values();
            $partnerA = $partnerListSorted->get(0); // Top partner (e.g. Basheer)
            $partnerB = $partnerListSorted->get(1); // Second partner (e.g. Pavoor)
        @endphp

        {{-- KPI 3: Secondary Partner Outflow Card (e.g. Pavoor) --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 border-l-[6px] border-l-teal-500 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block truncate">
                        {{ $partnerB ? strtoupper($partnerB->partner_name) . ' OUTFLOW' : 'PAVOOR OUTFLOW' }}
                    </span>
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tight mt-1 group-hover:text-teal-600 transition-colors">
                        ₹{{ number_format($partnerB?->amount ?? 0, 2) }}
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 border border-teal-200/80 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>{{ $partnerB ? $partnerB->allocations_count . ' allocations recorded' : 'Realized share' }}</span>
                <span class="px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 font-black text-[10px]">
                    {{ $partnerB ? number_format($partnerB->percentage, 1) . '% Share' : '0%' }}
                </span>
            </div>
        </div>

        {{-- KPI 4: Primary Partner Outflow Card (e.g. Basheer) --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 border-l-[6px] border-l-rose-500 shadow-xs flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block truncate">
                        {{ $partnerA ? strtoupper($partnerA->partner_name) . ' OUTFLOW' : 'BASHEER OUTFLOW' }}
                    </span>
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tight mt-1 group-hover:text-rose-600 transition-colors">
                        ₹{{ number_format($partnerA?->amount ?? 0, 2) }}
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/80 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span>{{ $partnerA ? $partnerA->allocations_count . ' allocations recorded' : 'Primary share' }}</span>
                <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 font-black text-[10px]">
                    {{ $partnerA ? number_format($partnerA->percentage, 1) . '% Share' : '0%' }}
                </span>
            </div>
        </div>

    </div>

    {{-- ── 4. VISUAL INTELLIGENCE CHARTS (2 COLUMNS) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Card: Monthly Capital Outflow Trend --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 mb-4 gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#a38c29] border border-amber-200 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">MONTHLY CAPITAL OUTFLOW TREND</h3>
                        <p class="text-[10px] text-slate-400 font-semibold">Allocations timeline across financial periods</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-wider">
                        Period: {{ $monthlyTrendData->keys()->first() ?? 'Current' }} - {{ $monthlyTrendData->keys()->last() ?? 'Current' }}
                    </span>
                </div>
            </div>

            <div class="w-full h-64 relative flex items-center justify-center">
                <div id="monthlyOutflowChart" class="w-full h-full"></div>
            </div>
        </div>

        {{-- Right Card: Partner Outflow Share --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 mb-4 gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">PARTNER OUTFLOW SHARE</h3>
                        <p class="text-[10px] text-slate-400 font-semibold">Proportional capital allocation distribution</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-extrabold uppercase tracking-wider">
                    {{ $partnerShareData->count() }} Entities
                </span>
            </div>

            <div class="w-full h-56 relative flex items-center justify-center">
                <div id="partnerShareChart" class="w-full h-full"></div>
            </div>

            {{-- Custom Partner Distribution Progress Bars List --}}
            <div class="pt-3 border-t border-slate-100 mt-2 space-y-2">
                @php
                    $palette = ['#a38c29', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#06b6d4'];
                    $colorIdx = 0;
                @endphp
                @foreach($outflowList as $row)
                    @php 
                        $c = $palette[$colorIdx % count($palette)]; 
                        $colorIdx++; 
                    @endphp
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $c }};"></span>
                            <span class="font-bold text-slate-800 truncate">{{ $row->partner_name }}</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-mono font-bold text-slate-900">₹{{ number_format((float)$row->amount, 2) }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-black text-white" style="background-color: {{ $c }};">
                                {{ number_format($row->percentage, 1) }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── 5. DATA TABLE CARD (MATCHING REQUESTED DESIGN) ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white text-[10.5px] font-black uppercase tracking-wider text-left border-b border-[#8a7522]">
                        <th class="px-5 py-4 w-52">PARTNER ENTITY</th>
                        <th class="px-5 py-4">ASSOCIATED PROJECT</th>
                        <th class="px-5 py-4">DESCRIPTION MEMO</th>
                        <th class="px-5 py-4 text-right w-44">ALLOCATED OUTFLOW</th>
                        <th class="px-5 py-4 text-center w-40">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800">
                    @forelse($outflowList as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors font-semibold">
                            <td class="px-5 py-4 font-extrabold text-slate-900 whitespace-nowrap">
                                <span class="inline-flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#a38c29]"></span>
                                    <span>{{ $row->partner_name }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $row->project_name }}
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-600">
                                {{ $row->description }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-black text-rose-600 text-sm whitespace-nowrap">
                                ₹{{ number_format((float)$row->amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if(!empty($row->partner_id))
                                    <a href="{{ url('/reports/partner-statements?partner_id=' . $row->partner_id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#a38c29]/10 hover:bg-[#a38c29] text-[#8a7522] hover:text-white rounded-xl font-extrabold text-[11px] transition-all duration-150 shadow-2xs group border border-[#a38c29]/30">
                                        <svg class="w-3.5 h-3.5 text-[#a38c29] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Partner Statement</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-medium italic">
                                No capital outflow allocations found for the selected project.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    </div>

    {{-- ── 6. HIDDEN EXCEL EXPORT TEMPLATE TABLE ── --}}
    <div class="hidden" style="display: none;">
        <table id="partnerOutflowExcelTable" border="1" style="border-collapse: collapse; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 10pt;">
            <thead>
                <tr height="40" style="height: 30pt;">
                    <th colspan="7" bgcolor="#1e293b" style="background-color: #1e293b; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle;">
                        TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD - PARTNER STATEMENT LEDGER & CAPITAL OUTFLOWS
                    </th>
                </tr>
                <tr height="25" style="height: 20pt;">
                    <th colspan="7" bgcolor="#334155" style="background-color: #334155; color: #f8fafc; font-size: 9pt; text-align: left; padding: 6px;">
                        Report Scope: {{ $selectedProject ? $selectedProject->name : 'Consolidated (All Projects)' }} | Export Date: {{ date('d-m-Y H:i') }} | Total Outflow: INR {{ number_format($totalAllocatedOutflow, 2) }}
                    </th>
                </tr>
                <tr height="30" bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left;">
                    <th style="padding: 8px;">Partner Entity</th>
                    <th style="padding: 8px;">Associated Project</th>
                    <th style="padding: 8px;">Description Memo</th>
                    <th style="padding: 8px; text-align: center;">Allocations Count</th>
                    <th style="padding: 8px; text-align: center;">Last Allocation Date</th>
                    <th style="padding: 8px; text-align: right;">Allocated Outflow (₹)</th>
                    <th style="padding: 8px; text-align: center;">Share (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outflowList as $row)
                    <tr height="25">
                        <td style="padding: 6px; font-weight: bold;">{{ $row->partner_name }}</td>
                        <td style="padding: 6px;">{{ $row->project_name }}</td>
                        <td style="padding: 6px;">{{ $row->description }}</td>
                        <td style="padding: 6px; text-align: center;">{{ $row->allocations_count }}</td>
                        <td style="padding: 6px; text-align: center;">{{ $row->last_date }}</td>
                        <td style="padding: 6px; text-align: right; font-weight: bold;">{{ number_format((float)$row->amount, 2, '.', '') }}</td>
                        <td style="padding: 6px; text-align: center;">{{ number_format($row->percentage, 1) }}%</td>
                    </tr>
                @endforeach
                <tr height="30" bgcolor="#f1f5f9" style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="5" style="padding: 8px; text-align: right;">TOTAL CONSOLIDATED OUTFLOW:</td>
                    <td style="padding: 8px; text-align: right; font-weight: bold; color: #b91c1c;">{{ number_format($totalAllocatedOutflow, 2, '.', '') }}</td>
                    <td style="padding: 8px; text-align: center;">100.0%</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

{{-- ── 7. APEXCHARTS INTEGRATION SCRIPT ── --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Monthly Capital Outflow Trend Bar Chart
        const trendCategories = @json(array_keys($monthlyTrendData->toArray()));
        const trendValues = @json(array_values($monthlyTrendData->toArray()));

        const monthlyOptions = {
            series: [{
                name: 'Capital Outflow',
                data: trendValues.length > 0 ? trendValues : [200000]
            }],
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '38%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end',
                    dataLabels: { position: 'top' }
                }
            },
            colors: ['#a38c29'],
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    if (val >= 10000000) return '₹' + (val / 10000000).toFixed(2) + 'Cr';
                    if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                    if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'K';
                    return '₹' + val;
                },
                offsetY: -20,
                style: { 
                    fontSize: '11px', 
                    colors: ['#8a7522'], 
                    fontWeight: 800,
                    fontFamily: 'inherit'
                }
            },
            xaxis: {
                categories: trendCategories.length > 0 ? trendCategories : ['Current'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { 
                        colors: '#64748b', 
                        fontSize: '11px', 
                        fontWeight: 700 
                    } 
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        if (val >= 10000000) return '₹' + (val / 10000000).toFixed(1) + 'Cr';
                        if (val >= 100000) return '₹' + (val / 100000).toFixed(0) + 'L';
                        if (val >= 1000) return '₹' + (val / 1000).toFixed(0) + 'K';
                        return '₹' + val;
                    },
                    style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };

        const monthlyChart = new ApexCharts(document.querySelector("#monthlyOutflowChart"), monthlyOptions);
        monthlyChart.render();

        // 2. Partner Outflow Share Donut Chart
        const shareLabels = @json(array_keys($partnerShareData->toArray()));
        const shareValues = @json(array_values($partnerShareData->toArray()));
        const totalOutflow = {{ (float)$totalAllocatedOutflow }};
        
        const totalFormatted = (totalOutflow >= 10000000) 
            ? '₹' + (totalOutflow / 10000000).toFixed(2) + 'Cr'
            : ((totalOutflow >= 100000) 
                ? '₹' + (totalOutflow / 100000).toFixed(1) + 'L' 
                : '₹' + totalOutflow.toLocaleString('en-IN'));

        const shareOptions = {
            series: shareValues.length > 0 ? shareValues : [13011202, 9546758],
            chart: {
                type: 'donut',
                height: '100%',
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            labels: shareLabels.length > 0 ? shareLabels : ['Basheer', 'Pavoor'],
            colors: ['#a38c29', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#06b6d4'],
            legend: { show: false },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + '%';
                },
                dropShadow: { enabled: false },
                style: {
                    fontSize: '11px',
                    fontWeight: '800'
                }
            },
            stroke: {
                width: 2,
                colors: ['#ffffff']
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            name: { 
                                show: true, 
                                fontSize: '11px', 
                                color: '#64748b', 
                                fontWeight: 700 
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontWeight: 900,
                                color: '#0f172a',
                                formatter: function () {
                                    return totalFormatted;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Outflow',
                                fontSize: '10.5px',
                                color: '#64748b',
                                fontWeight: 700,
                                formatter: function () {
                                    return totalFormatted;
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return '₹ ' + val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        };

        const shareChart = new ApexCharts(document.querySelector("#partnerShareChart"), shareOptions);
        shareChart.render();
    });
</script>

</x-erp-layout>
