<x-erp-layout title="Customer Ledger" headerTitle="Customer Running Ledger">

<div class="max-w-[1800px] mx-auto space-y-3" x-data="ledgerApp()">

    {{-- Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-[11px] text-slate-400 font-semibold">
        <div class="flex items-center gap-2">
            <a href="{{ route('sales.index') }}" class="hover:text-[#a38c29] transition-colors">Sales</a>
            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('emi-collections.outstanding') }}" class="hover:text-[#a38c29] transition-colors">Outstanding</a>
            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700 font-bold">Ledger — {{ $sale->sale_number }}</span>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Select Customer / Unit:</span>
            <select onchange="window.location.href='{{ url('emi-collections/ledger') }}/' + this.value" 
                    class="px-3.5 py-1.5 bg-white border border-slate-200/90 focus:ring-4 focus:ring-[#a38c29]/15 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all shadow-xs cursor-pointer">
                @foreach($allSales as $s)
                    <option value="{{ $s->id }}" {{ $s->id == $sale->id ? 'selected' : '' }}>
                        {{ $s->customer?->name ?? '—' }} — Unit: {{ $s->unit?->door_no ?? '—' }} ({{ $s->sale_number }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Hero Sale Summary Banner --}}
    <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 text-slate-900">
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-[#a38c29]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 items-center">
            {{-- Customer Info --}}
            <div class="flex items-center gap-3 lg:col-span-1">
                <div class="w-10 h-10 rounded-xl bg-[#a38c29]/10 border border-[#a38c29]/20 text-[#a38c29] flex items-center justify-center font-extrabold text-sm shrink-0 shadow-xs">
                    {{ strtoupper(substr($sale->customer?->name ?? 'C', 0, 2)) }}
                </div>
                <div>
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Customer</span>
                    <h3 class="text-sm font-extrabold text-slate-900 mt-0.5 tracking-tight">{{ $sale->customer?->name ?? '—' }}</h3>
                    <span class="text-[10px] text-slate-500 font-semibold inline-flex items-center gap-1 mt-0.5">
                        <svg class="w-3 h-3 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $sale->customer?->phone ?? 'N/A' }}
                    </span>
                </div>
            </div>

            {{-- Sale Number & Project --}}
            <div class="lg:col-span-1">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Sale Number / Project</span>
                <span class="text-xs font-extrabold text-[#a38c29] font-mono mt-0.5 block tracking-wide">{{ $sale->sale_number }}</span>
                <span class="text-[10px] text-slate-600 font-semibold mt-0.5 block truncate" title="{{ $sale->project?->name ?? '—' }} — Unit: {{ $sale->unit?->door_no ?? '—' }}">
                    {{ $sale->project?->name ?? '—' }} — <span class="text-slate-900 font-bold">Unit: {{ $sale->unit?->door_no ?? '—' }}</span>
                </span>
            </div>

            {{-- Sale Amount --}}
            <div class="lg:col-span-1">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Contract Value (incl. GST)</span>
                <span class="text-sm font-black text-slate-900 font-mono mt-0.5 block tracking-tight">₹{{ number_format($sale->total_amount, 2) }}</span>
                <span class="text-[10px] text-slate-500 font-semibold mt-0.5 block">Agreement: {{ $sale->agreement_date?->format('d M Y') ?? '—' }}</span>
            </div>

            {{-- Built Up Area --}}
            <div class="lg:col-span-1">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Built Up Area (Sq Ft)</span>
                <span class="text-sm font-black text-slate-900 font-mono mt-0.5 block tracking-tight">
                    @php
                        $unitBuiltUp = $sale->unit?->built_up_area ?? 0;
                    @endphp
                    {{ $unitBuiltUp > 0 ? number_format($unitBuiltUp, 2) . ' sq.ft' : '—' }}
                </span>
                <span class="text-[10px] text-slate-500 font-semibold mt-0.5 block">Unit: {{ $sale->unit?->door_no ?? '—' }}</span>
            </div>

            {{-- Sale Rate/Sqft --}}
            <div class="lg:col-span-1">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Sale Rate / Sq Ft</span>
                @php
                    $saleRateSqft = (float)($sale->rate_per_sqft ?? $sale->unit?->sale_rate_per_sqft ?? 0);
                    $effectiveRateDisplay = $loggedRate ?: $saleRateSqft;
                @endphp
                <span class="text-sm font-black text-[#a38c29] font-mono mt-0.5 block tracking-tight" x-text="'₹' + Number(adjustedRateSqft || {{ (float)$effectiveRateDisplay }}).toLocaleString('en-IN', {minimumFractionDigits: 2})">
                    ₹{{ number_format($effectiveRateDisplay, 2) }}
                </span>
                <span class="text-[10px] text-slate-500 font-semibold mt-0.5 block">
                    @if($loggedRate && $loggedRate != $saleRateSqft)
                        Restructured Rate <span class="text-slate-400 font-normal">(Base: ₹{{ number_format($saleRateSqft, 0) }})</span>
                    @else
                        Per Sq.Ft (Sale Rate)
                    @endif
                </span>
            </div>

            {{-- Remaining Balance Status --}}
            <div class="lg:col-span-1 lg:text-right">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest block">Remaining Balance</span>
                <div class="mt-0.5 inline-flex items-center gap-2">
                    <span class="text-base font-black font-mono {{ $sale->status === 'exchanged' ? 'text-blue-600' : (in_array($sale->status, ['cancelled', 'returned', 'resale']) ? 'text-rose-600' : ($closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600')) }}">
                        ₹{{ number_format(abs($closingBalance), 2) }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[8.5px] font-extrabold uppercase tracking-wider border 
                        {{ $sale->status === 'exchanged' 
                            ? 'bg-blue-50 text-blue-700 border-blue-200' 
                            : (in_array($sale->status, ['cancelled', 'returned', 'resale']) 
                                ? 'bg-rose-50 text-rose-700 border-rose-200' 
                                : ($closingBalance > 0 ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200')) }}">
                        {{ in_array($sale->status, ['exchanged', 'cancelled', 'returned', 'resale']) ? ucfirst($sale->status) : ($closingBalance > 0 ? 'Outstanding' : 'Fully Paid') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Executive Metric Cards (4 Cards - Design Matching Customer Ledger Report) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Sale Value --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M13 12h.01M13 16h.01M17 12h.01M17 16h.01"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Sale Value</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-[#8a7522] font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:bg-amber-100/60">
                    Sales Value
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">
                    ₹{{ number_format($sale->total_amount, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Agreed sale price + GST</p>
            </div>
        </div>

        {{-- Card 2: Total Instalment Dues --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Instalment Dues</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-rose-300 group-hover:bg-rose-100/60">
                    Scheduled
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">
                    ₹{{ number_format($totalDebits, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Scheduled installments</p>
            </div>
        </div>

        {{-- Card 3: Total Receipts Paid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Total Receipts Paid</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:bg-emerald-100/60">
                    Realized
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">
                    ₹{{ number_format($totalCredits, 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">Payments received</p>
            </div>
        </div>

        {{-- Card 4: Net Outstanding --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] 
            {{ $sale->status === 'exchanged' 
                ? 'border-l-blue-500 hover:border-blue-200 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.15)] bg-blue-50/20' 
                : (in_array($sale->status, ['cancelled', 'returned', 'resale']) 
                    ? 'border-l-rose-500 hover:border-rose-200 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] bg-rose-50/20' 
                    : ($closingBalance > 0 ? 'border-l-rose-500 hover:border-rose-200 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] bg-rose-50/20' : 'border-l-emerald-500 hover:border-emerald-200 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] bg-emerald-50/20')) }} 
            p-5 flex flex-col justify-between relative overflow-hidden group transition-all duration-300 hover:-translate-y-1.5 cursor-default h-full">
            <div class="flex items-center justify-between gap-2 mb-4 relative z-10 min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-8 h-8 shrink-0 rounded-full 
                        {{ $sale->status === 'exchanged' ? 'bg-blue-50 text-blue-600 border-blue-100/60' : ($closingBalance > 0 ? 'bg-rose-50 text-rose-600 border-rose-100/60' : 'bg-emerald-50 text-emerald-600 border-emerald-100/60') }} 
                        flex items-center justify-center border transition-all duration-300 
                        {{ $closingBalance > 0 ? 'group-hover:bg-rose-500' : 'group-hover:bg-emerald-500' }} group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider truncate">Net Outstanding</span>
                </div>
                <span class="shrink-0 whitespace-nowrap text-[9px] font-bold px-2 py-0.5 rounded-md border uppercase tracking-wider shadow-sm transition-all duration-300 
                    {{ $sale->status === 'exchanged' 
                        ? 'text-blue-700 bg-blue-50 border-blue-200 group-hover:border-blue-300 group-hover:bg-blue-100/60' 
                        : ($closingBalance > 0 ? 'text-rose-700 bg-rose-50 border-rose-200 group-hover:border-rose-300 group-hover:bg-rose-100/60' : 'text-emerald-700 bg-emerald-50 border-emerald-200 group-hover:border-emerald-300 group-hover:bg-emerald-100/60') }}">
                    {{ in_array($sale->status, ['exchanged', 'cancelled', 'returned', 'resale']) ? ucfirst($sale->status) : ($closingBalance > 0 ? 'Pending' : 'Settled') }}
                </span>
            </div>
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black font-mono tracking-tight block transition-colors duration-300 
                    {{ $sale->status === 'exchanged' ? 'text-blue-600' : ($closingBalance > 0 ? 'text-rose-600 group-hover:text-rose-700' : 'text-emerald-600 group-hover:text-emerald-700') }}">
                    ₹{{ number_format(abs($closingBalance), 2) }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1.5 font-medium">
                    {{ in_array($sale->status, ['exchanged', 'cancelled', 'returned', 'resale']) ? ucfirst($sale->status) . ' (Archived)' : ($closingBalance > 0 ? 'Balance Due' : 'Fully Settled') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Running Ledger Table Container --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-10 bg-[#a38c29] rounded-full shrink-0"></div>
                <div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        Running Ledger Statement
                        <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 text-[9px] font-extrabold uppercase tracking-normal">Live Log</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Chronological history of installment dues and receipt credits with running balance.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                @if($sale->status !== 'exchanged')
                <button type="button" @click="openPayModal({{ $closingBalance }}, 'Outstanding Balance')" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white border border-[#a38c29] text-[10px] font-black uppercase tracking-wider rounded-xl transition-all shadow-sm hover:shadow inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Receipt
                </button>
                <button type="button" @click="openEmiModal()" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-[#a38c29] border border-[#a38c29]/40 hover:border-[#a38c29] text-[10px] font-black uppercase tracking-wider rounded-xl transition-all shadow-xs inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Restructure / Edit EMI
                </button>
                @endif
                <a href="{{ route('sales.index') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/90 hover:border-slate-300 text-[10px] font-extrabold uppercase tracking-wider rounded-xl transition-all shadow-xs inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Sales Register
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="erp-table-header">
                    <tr class="text-white font-extrabold text-[9.5px] uppercase tracking-widest border-b border-[#3e3a35]">
                        <th class="px-4 py-2.5 text-white">Date</th>
                        <th class="px-4 py-2.5 text-white">Description</th>
                        <th class="px-4 py-2.5 text-right text-white">Debit (Due)</th>
                        <th class="px-4 py-2.5 text-right text-white">Credit (Paid)</th>
                        <th class="px-4 py-2.5 text-right text-white">Running Balance</th>
                        <th class="px-4 py-2.5 text-center text-white">Type & Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white font-semibold text-slate-700">
                    @php
                        $firstUnpaidIndex = null;
                        $firstUnpaidLabel = null;
                        foreach($ledger as $idx => $r) {
                            $rem = round(($r['debit'] ?? 0) - ($r['credit'] ?? 0), 2);
                            if ($rem > 0.01 && in_array($r['status'] ?? '', ['pending', 'partial', 'overdue'])) {
                                $firstUnpaidIndex = $idx;
                                $firstUnpaidLabel = $r['description'] ?? 'earlier installment';
                                break;
                            }
                        }
                    @endphp
                    @forelse($ledger as $row)
                    @php
                        $status = $row['status'] ?? '';
                        $rowStyle = match($status) {
                            'paid'    => 'bg-emerald-50/60 hover:bg-emerald-100/70',
                            'partial' => 'bg-amber-50/50 hover:bg-amber-100/60',
                            'overdue' => 'bg-rose-50/50 hover:bg-rose-100/60',
                            default   => 'bg-slate-50/50 hover:bg-slate-100/60',
                        };

                        $cfg = [
                            'row'   => $rowStyle,
                            'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                        ];

                        $isOutOfOrder = ($firstUnpaidIndex !== null && $loop->index > $firstUnpaidIndex);
                    @endphp
                    <tr class="transition-colors border-b border-slate-100/80 {{ $cfg['row'] }}">
                        <td class="px-4 py-2.5 text-slate-500 text-[10px] font-mono whitespace-nowrap">{{ $row['date'] }}</td>
                        <td class="px-4 py-2.5">
                            <div class="font-extrabold text-slate-900 text-xs">{{ $row['description'] }}</div>
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-xs {{ $row['status'] === 'paid' ? 'text-emerald-600 font-extrabold' : 'text-rose-600 font-extrabold' }}">
                            ₹{{ number_format($row['debit'], 2) }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-xs {{ $row['credit'] > 0 ? 'text-emerald-600 font-extrabold' : 'text-slate-350' }}">
                            {{ $row['credit'] > 0 ? '₹' . number_format($row['credit'], 2) : '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-xs font-black">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg border {{ $row['status'] === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($row['running_balance'] > 0 ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100') }}">
                                ₹{{ number_format(abs($row['running_balance']), 2) }}
                                <span class="text-[8px] font-extrabold uppercase">{{ $row['running_balance'] > 0 ? 'DR' : 'CR' }}</span>
                            </span>
                        </td>
                        <td class="px-4 py-2.5 align-middle text-center">
                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                {{-- Type Badge --}}
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md border {{ $cfg['badge'] }}">
                                    INSTALLMENT
                                </span>

                                {{-- Installment Status / Button --}}
                                @if(in_array($row['status'], ['exchanged', 'cancelled', 'returned', 'resale']))
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md 
                                        {{ $row['status'] === 'exchanged' ? 'bg-blue-50 text-blue-700 border-blue-200' : ($row['status'] === 'cancelled' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                        {{ ucfirst($row['status']) }}
                                    </span>
                                @elseif($row['status'] === 'paid' || round($row['debit'] ?? 0, 2) <= 0.01)
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        Paid
                                    </span>
                                    @if(!empty($row['receipts']))
                                        @foreach($row['receipts'] as $rcpt)
                                            <button type="button" @click.stop="openReceiptModal('{{ $rcpt['id'] }}')" title="View Receipt (₹{{ number_format($rcpt['amount'], 2) }})"
                                                    class="inline-flex items-center justify-center p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg transition-all shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        @endforeach
                                    @endif
                                @elseif($row['status'] === 'partial')
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-amber-100 text-amber-700 border border-amber-200">
                                        Partial
                                    </span>
                                    @if(!empty($row['receipts']))
                                        @foreach($row['receipts'] as $rcpt)
                                            <button type="button" @click.stop="openReceiptModal('{{ $rcpt['id'] }}')" title="View Receipt (₹{{ number_format($rcpt['amount'], 2) }})"
                                                    class="inline-flex items-center justify-center p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg transition-all shadow-xs">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        @endforeach
                                    @endif
                                    @if($sale->status !== 'exchanged')
                                    <button
                                        @click.stop="openPayModal('{{ $row['debit'] - $row['credit'] }}', '{{ addslashes($row['description']) }}', {{ $isOutOfOrder ? 'true' : 'false' }}, '{{ addslashes($firstUnpaidLabel ?? '') }}')"
                                        type="button"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[9px] font-extrabold uppercase tracking-wider rounded-lg shadow-sm transition-all whitespace-nowrap cursor-pointer">
                                        Pay Remaining
                                    </button>
                                    @endif
                                @elseif($row['status'] === 'overdue')
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-rose-100 text-rose-700 border border-rose-200">
                                        Overdue
                                    </span>
                                    @if($sale->status !== 'exchanged')
                                    <button
                                        @click.stop="openPayModal('{{ $row['debit'] - $row['credit'] }}', '{{ addslashes($row['description']) }}', {{ $isOutOfOrder ? 'true' : 'false' }}, '{{ addslashes($firstUnpaidLabel ?? '') }}')"
                                        type="button"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[9px] font-extrabold uppercase tracking-wider rounded-lg shadow-sm transition-all whitespace-nowrap cursor-pointer">
                                        Pay Installment
                                    </button>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                        Pending
                                    </span>
                                    @if($sale->status !== 'exchanged')
                                    <button
                                        @click.stop="openPayModal('{{ $row['debit'] - $row['credit'] }}', '{{ addslashes($row['description']) }}', {{ $isOutOfOrder ? 'true' : 'false' }}, '{{ addslashes($firstUnpaidLabel ?? '') }}')"
                                        type="button"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-[9px] font-extrabold uppercase tracking-wider rounded-lg shadow-sm transition-all whitespace-nowrap cursor-pointer">
                                        Pay Installment
                                    </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No transactions yet for this sale. Use "Add Receipt" to record the first payment.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-white text-slate-900 font-extrabold text-xs border-t-2 border-[#a38c29]">
                        <td colspan="3" class="px-5 py-4 text-[10px] text-slate-800 uppercase tracking-widest">Closing Totals</td>
                        <td class="px-5 py-4 text-right font-mono text-emerald-600">₹{{ number_format($totalCredits, 2) }}</td>
                        <td class="px-5 py-4 text-right font-mono {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            ₹{{ number_format(abs($closingBalance), 2) }} <span class="text-[9px] font-extrabold uppercase text-slate-500">{{ $closingBalance > 0 ? 'DR' : 'CR' }}</span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if(!empty($archiveSnapshot))
    {{-- Collapsible Archived Receipts & EMI Schedule details --}}
    <div x-data="{ openTables: false }" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
        <button type="button" @click="openTables = !openTables"
                class="text-xs font-black {{ in_array($sale->status, ['cancelled', 'returned', 'resale']) ? 'text-rose-700 hover:text-rose-900' : 'text-blue-700 hover:text-blue-900' }} uppercase tracking-wider flex items-center gap-1.5 cursor-pointer">
            <span x-text="openTables ? 'Hide Detailed Receipts & EMI Schedule History' : '📜 Expand Full Receipts & EMI Schedule Audit Log'"></span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="openTables ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div x-show="openTables" x-transition.opacity class="mt-4 space-y-4 font-sans text-xs">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Archived Receipts Table --}}
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Archived Receipts History</span>
                    <div class="border border-slate-200/80 rounded-2xl bg-slate-50/50 divide-y divide-slate-200/60 overflow-hidden shadow-xs">
                        @if(!empty($archiveSnapshot['receipts']))
                            @foreach($archiveSnapshot['receipts'] as $r)
                                @if(empty($r['partner_id']))
                                <div class="px-4 py-2.5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-slate-800 font-mono">{{ $r['receipt_number'] ?? 'Receipt #' . $r['id'] }}</span>
                                        <span class="text-slate-400 font-mono font-semibold">{{ isset($r['receipt_date']) ? \Carbon\Carbon::parse($r['receipt_date'])->format('d M Y') : '—' }}</span>
                                        <span class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-700 text-[8px] font-bold uppercase">{{ $r['payment_mode'] ?? '—' }}</span>
                                    </div>
                                    <span class="font-bold text-emerald-700 font-mono">₹{{ number_format($r['amount'], 2) }}</span>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="px-4 py-4 text-slate-400 italic text-center">No receipts recorded prior to exchange</div>
                        @endif
                    </div>
                </div>

                {{-- Archived Installments Schedule Table --}}
                <div class="space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Archived EMI Installments Schedule</span>
                    <div class="border border-slate-200/80 rounded-2xl bg-slate-50/50 divide-y divide-slate-200/60 overflow-hidden shadow-xs">
                        @if(!empty($archiveSnapshot['installments']))
                            @foreach($archiveSnapshot['installments'] as $inst)
                                <div class="px-4 py-2.5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-700 text-[8px] font-bold flex items-center justify-center font-mono">{{ $inst['installment_no'] }}</span>
                                        <span class="font-bold text-slate-800">{{ $inst['label'] }}</span>
                                        <span class="text-slate-400 font-mono font-semibold">{{ isset($inst['due_date']) ? \Carbon\Carbon::parse($inst['due_date'])->format('d M Y') : '—' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        @php
                                            $paidAmt = $inst['paid_amount'] ?? 0;
                                            if (($inst['status'] ?? '') === 'paid' && (!$paidAmt || (float)$paidAmt <= 0)) {
                                                $paidAmt = $inst['amount'] ?? 0;
                                            }
                                        @endphp
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase border
                                            @if(($inst['status'] ?? '') === 'paid')
                                                bg-emerald-50 text-emerald-755 border-emerald-100
                                            @elseif(($inst['status'] ?? '') === 'partial')
                                                bg-amber-50 text-amber-755 border-amber-100
                                            @else
                                                bg-slate-100 text-slate-700 border-slate-200
                                            @endif
                                        ">
                                            {{ $inst['status'] ?? 'pending' }}
                                        </span>
                                        <span class="font-bold text-slate-800 font-mono">₹{{ number_format($inst['amount'], 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="px-4 py-4 text-slate-400 italic text-center">No EMI schedule configured prior to exchange</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Back Links --}}
    <div class="flex gap-4 text-xs">
        <a href="{{ route('emi-collections.outstanding') }}" class="font-bold text-slate-500 hover:text-primary transition-colors">&larr; Outstanding Summary</a>
        <a href="{{ route('sales.index') }}" class="font-bold text-slate-500 hover:text-primary transition-colors">&larr; Sales Register</a>
    </div>

    {{-- Direct Pay Installment Modal --}}
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
         style="display: none;" x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="modalOpen = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Installment Payment</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Payment for <span x-text="form.label"></span></h2>
                      </div>
                      <button type="button" @click="modalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                  </div>
              </div>

              <div x-show="error" class="p-4 mx-6 mt-4 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800 leading-relaxed shadow-xs" x-text="error"></div>

              <form @submit.prevent="submitPayment()" novalidate>
                  <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                      <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount to Collect (₹) <span class="text-rose-500">*</span></label>
                              <input type="number" step="0.01" x-model.number="form.amount"
                                     @input="if(errors.amount) delete errors.amount;"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-sm"
                                     :class="errors.amount ? 'border-rose-500 bg-rose-50/20' : ''">
                              <template x-if="errors.amount">
                                  <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.amount) ? errors.amount[0] : errors.amount"></span>
                              </template>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Receipt Date <span class="text-rose-500">*</span></label>
                              <input type="date" x-model="form.receipt_date"
                                     @input="if(errors.receipt_date) delete errors.receipt_date;"
                                     class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all shadow-sm"
                                     :class="errors.receipt_date ? 'border-rose-500 bg-rose-50/20' : ''">
                              <template x-if="errors.receipt_date">
                                  <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.receipt_date) ? errors.receipt_date[0] : errors.receipt_date"></span>
                              </template>
                          </div>

                          <div class="space-y-2">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode <span class="text-rose-500">*</span></label>
                              <select x-model="form.payment_mode"
                                      @change="if(errors.payment_mode) delete errors.payment_mode;"
                                      class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm"
                                      :class="errors.payment_mode ? 'border-rose-500 bg-rose-50/20' : ''">
                                  <option value="">-- Select Payment Mode --</option>
                                  @foreach($paymentModes as $pm)
                                      <option value="{{ $pm->name }}">{{ $pm->name }}</option>
                                  @endforeach
                              </select>
                              <template x-if="errors.payment_mode">
                                  <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.payment_mode) ? errors.payment_mode[0] : errors.payment_mode"></span>
                              </template>
                          </div>
                      </div>

                      <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                          <div class="grid grid-cols-2 gap-4">
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Ref / Cheque No. <span class="text-rose-500">*</span></label>
                                  <input type="text" x-model="form.reference_no" placeholder="Enter Ref / Cheque No."
                                         @input="if(errors.reference_no) delete errors.reference_no;"
                                         class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all shadow-sm"
                                         :class="errors.reference_no ? 'border-rose-500 bg-rose-50/20' : ''">
                                  <template x-if="errors.reference_no">
                                      <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="Array.isArray(errors.reference_no) ? errors.reference_no[0] : errors.reference_no"></span>
                                  </template>
                              </div>
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Bank Name</label>
                                  <select x-model="form.bank_id"
                                          class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-sm">
                                      <option value="">-- Optional --</option>
                                      @foreach($banks as $bank)
                                      <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                          <div class="space-y-1.5">
                              <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Remarks</label>
                              <textarea x-model="form.remarks" rows="2"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-800 focus:outline-none transition-all resize-none shadow-sm"></textarea>
                          </div>
                      </div>
                  </div>

                  <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                      <button type="button" @click="modalOpen = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                          Cancel
                      </button>
                      <button type="submit" x-bind:disabled="submitting"
                              class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition-all uppercase tracking-wider shadow-md flex items-center justify-center gap-2">
                          <span x-text="submitting ? 'Recording...' : 'Collect Payment'"></span>
                      </button>
                  </div>
              </form>
         </div>
    </div>

    {{-- Manage EMI Schedule Modal --}}
    <div x-show="emiModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 min-h-screen text-left"
         style="display: none;" x-transition.opacity>
         <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all flex flex-col max-h-[90vh] my-auto" @click.away="emiModalOpen = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-3.5 border-b border-[#a38c29]/10 shrink-0 rounded-t-2xl">
                  <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <div class="flex flex-wrap items-center gap-2 mb-1">
                              <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Schedule Editor</span>
                          </div>
                          <h2 class="text-base sm:text-lg font-extrabold text-white tracking-tight uppercase mt-0.5">Custom EMI Schedule Breakdown</h2>
                      </div>
                      <button type="button" @click="emiModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                  </div>
              </div>

              <div x-show="emiError" class="p-3 mx-6 mt-3 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide shrink-0" x-text="emiError"></div>

              <div class="px-6 pt-3 pb-0 overflow-x-auto scrollbar-none shrink-0 bg-white">
                  {{-- Tabs --}}
                  <div class="flex items-center gap-1 sm:gap-2 border-b border-slate-200 whitespace-nowrap min-w-max">
                      <button type="button" @click="emiTab = 'restructure'" 
                              :class="emiTab === 'restructure' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 font-bold'"
                              class="pb-2.5 px-3 text-xs border-b-2 uppercase tracking-wider transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                          <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                          <span>Restructure Balance</span>
                      </button>
                      <button type="button" @click="emiTab = 'manual'" 
                              :class="emiTab === 'manual' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 font-bold'"
                              class="pb-2.5 px-3 text-xs border-b-2 uppercase tracking-wider transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                          <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                          <span>Edit Rows Manually</span>
                      </button>
                      <button type="button" @click="emiTab = 'history'" 
                              :class="emiTab === 'history' ? 'border-[#a38c29] text-[#a38c29] font-extrabold' : 'border-transparent text-slate-500 hover:text-slate-700 font-bold'"
                              class="pb-2.5 px-3 text-xs border-b-2 uppercase tracking-wider transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                          <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                          <span>History</span>
                          <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded-full text-[9px] font-black"
                                :class="emiTab === 'history' ? 'bg-[#a38c29]/20 text-[#a38c29]' : 'bg-slate-200 text-slate-600'"
                                x-text="restructureLogs.length"></span>
                      </button>
                  </div>
              </div>

              {{-- Restructure Generator Card --}}
              <div x-show="emiTab === 'restructure'" class="p-4 space-y-3 bg-amber-50/40 border-b border-amber-200/60 font-sans text-xs shrink-0">
                  <div class="flex items-center justify-between">
                      <div>
                          <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Restructure Available Balance</h3>
                          <!-- <p class="text-[11px] text-slate-500 mt-0.5">Recalculate remaining unpaid balance from Area / Rate / Discount without modifying unit or sale base rates.</p> -->
                      </div>
                      <div class="text-right">
                          <span class="text-[9px] font-extrabold text-slate-400 uppercase">Remaining Balance</span>
                          <div class="text-sm font-black font-mono text-rose-600">₹<span x-text="existingRemainingBalance().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></div>
                      </div>
                  </div>

                  {{-- Adjustments Grid: Built Up Area, Current Rate, New Rate, Discount Amount --}}
                  <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-0.5">
                      {{-- 1. Built Up Area (Sq Ft) text box --}}
                      <div class="bg-white/95 border border-amber-200/80 rounded-xl p-2.5 space-y-1 shadow-xs">
                          <label class="block text-[9px] font-black text-slate-700 uppercase tracking-wider">
                              Built Up Area (Sq Ft)
                          </label>
                          <input type="number" step="0.01" min="0.01" x-model.number="adjustmentForm.built_up_area"
                                 class="w-full px-2 py-1 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-lg text-xs font-bold text-slate-800 focus:outline-none transition-all">
                          <span class="text-[9px] text-slate-400 font-semibold block">Unit Area</span>
                      </div>

                      {{-- 2. Current Rate / Sq Ft (Current/Original) --}}
                      <div class="bg-white/95 border border-amber-200/80 rounded-xl p-2.5 space-y-1 shadow-xs">
                          <label class="block text-[9px] font-black text-slate-700 uppercase tracking-wider">
                              Current Rate / Sqft (₹)
                          </label>
                          <input type="number" step="0.01" readonly :value="adjustmentForm.current_rate"
                                 class="w-full px-2 py-1 bg-slate-100 border border-slate-200 text-slate-700 font-bold rounded-lg text-xs cursor-not-allowed">
                          <template x-if="loggedRate && loggedRate !== baseSaleRateSqft">
                              <div class="text-[9px] leading-tight break-words pt-0.5">
                                  <span class="text-[#a38c29] font-bold block">Logged: ₹<span x-text="Number(loggedRate).toLocaleString('en-IN')"></span></span>
                                  <span class="text-slate-400 font-semibold block">(Base Rate: ₹{{ number_format($saleRateSqft, 0) }})</span>
                              </div>
                          </template>
                          <template x-if="!loggedRate || loggedRate === baseSaleRateSqft">
                              <span class="text-[9px] text-slate-400 font-semibold block">Base Sale Rate</span>
                          </template>
                      </div>

                      {{-- 3. New Sale Rate / Sq Ft text box --}}
                      <div class="bg-white/95 border border-amber-200/80 rounded-xl p-2.5 space-y-1 shadow-xs">
                          <label class="block text-[9px] font-black text-[#a38c29] uppercase tracking-wider">
                              New Rate / Sqft (₹)
                          </label>
                          <input type="number" step="0.01" min="0.01" id="new_sqft_rate_input" name="new_sqft_rate"
                                 x-model.number="adjustmentForm.new_rate"
                                 placeholder="Enter new rate / sqft"
                                 class="w-full px-2 py-1 bg-white border border-amber-300 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] rounded-lg text-xs font-bold text-slate-800 focus:outline-none transition-all">
                          <span class="text-[9px] text-amber-700 font-semibold block">Applies to Balance</span>
                      </div>

                      {{-- 4. Discount Amount (₹) text box --}}
                      <div class="bg-white/95 border border-amber-200/80 rounded-xl p-2.5 space-y-1 shadow-xs">
                          <label class="block text-[9px] font-black text-emerald-700 uppercase tracking-wider">
                              Discount Amount (₹)
                          </label>
                          <input type="number" step="0.01" min="0" x-model.number="adjustmentForm.discount_amount"
                                 placeholder="e.g. 50000"
                                 class="w-full px-2 py-1 bg-white border border-emerald-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 rounded-lg text-xs font-bold text-slate-800 focus:outline-none transition-all">
                          <span class="text-[9px] text-emerald-600 font-semibold block">Deducts from Balance</span>
                      </div>
                  </div>

                  {{-- Live Preview Alert / Status --}}
                  <div class="p-2.5 bg-white/90 border border-amber-200/80 rounded-xl flex flex-wrap items-center justify-between gap-2 text-xs shadow-xs">
                      <div class="flex items-center gap-2 flex-wrap">
                          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                          <span class="text-slate-600 font-medium">Customer Paid: <strong class="text-emerald-700 font-mono">₹<span x-text="calculateTotalPaid().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong></span>
                          <template x-if="parseFloat(adjustmentForm.discount_amount) > 0">
                              <span class="inline-flex items-center gap-1">
                                  <span class="text-slate-300">|</span>
                                  <span class="text-slate-600 font-medium">Discount: <strong class="text-emerald-600 font-mono">-₹<span x-text="Number(adjustmentForm.discount_amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong></span>
                              </span>
                          </template>
                          <template x-if="parseFloat(adjustmentForm.new_rate) > 0">
                              <span class="inline-flex items-center gap-1">
                                  <span class="text-slate-300">|</span>
                                  <span class="text-slate-600 font-medium">Discount Rate: <strong class="text-[#a38c29] font-mono">₹<span x-text="Number(adjustmentForm.new_rate).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>/sqft</strong></span>
                              </span>
                          </template>
                          <span class="text-slate-300">|</span>
                          <span class="text-slate-600 font-medium">Effective Balance: <strong class="text-rose-600 font-mono">₹<span x-text="unpaidBalanceToRestructure().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong></span>
                      </div>
                      <!-- <div class="text-[10px] text-slate-500 italic">
                          * Changes recalculate the schedule balance and record in logs without mutating base inventory unit rates.
                      </div> -->
                  </div>

                  {{-- EMI Schedule Generator Inputs --}}
                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-0.5 border-t border-amber-200/50">
                      <div>
                          <label class="block text-[9px] font-extrabold text-slate-500 uppercase tracking-wider mb-0.5">Number of New EMIs</label>
                          <input type="number" min="1" max="120" placeholder="e.g. 3, 6, 12" x-model.number="restructureCount"
                                 class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] focus:outline-none">
                      </div>
                      <div>
                          <label class="block text-[9px] font-extrabold text-slate-500 uppercase tracking-wider mb-0.5">First Due Date</label>
                          <input type="date" x-model="restructureStartDate"
                                 class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29] focus:outline-none">
                      </div>
                      <div class="flex items-end">
                          <button type="button" @click="applyRestructure()" 
                                  class="w-full px-3 py-1.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-black uppercase tracking-wider rounded-lg transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                              Recalculate & Generate
                          </button>
                      </div>
                  </div>
              </div>

              {{-- Restructure History Container --}}
              <div x-show="emiTab === 'history'" class="p-5 space-y-3 overflow-y-auto font-sans text-xs bg-slate-50/50 flex-1 min-h-0">
                  <template x-if="restructureLogs.length === 0">
                      <div class="text-center py-8 text-slate-400 bg-white rounded-xl border border-dashed border-slate-200">
                          <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                          <div class="font-bold text-slate-600">No Restructure Logs Found</div>
                          <p class="text-[10px] mt-0.5">Every time this customer's schedule is restructured, a full copy of the previous installments is saved here.</p>
                      </div>
                  </template>
                  <template x-for="(log, lIdx) in restructureLogs" :key="log.id || lIdx">
                      <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
                          <div class="px-3.5 py-2.5 bg-slate-100/70 border-b border-slate-200 flex items-center justify-between">
                              <div>
                                  <span class="text-[10px] font-black uppercase text-slate-700" x-text="log.reason || 'EMI Restructure'"></span>
                                  <div class="text-[9px] text-slate-400 mt-0.5">
                                      By <strong class="text-slate-600" x-text="log.performed_by?.name || 'Admin'"></strong> • <span x-text="formatDate(log.created_at)"></span>
                                  </div>
                              </div>
                              <button type="button" @click="restoreFromLog(log)" 
                                      class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-[#a38c29] border border-[#a38c29]/30 rounded-lg text-[9px] font-extrabold uppercase tracking-wider transition-colors flex items-center gap-1 cursor-pointer">
                                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                  Load this Version
                              </button>
                          </div>
                          <div class="p-3">
                              <table class="w-full text-[10px]">
                                  <thead>
                                      <tr class="text-slate-400 border-b border-slate-100 uppercase text-[8px] font-bold">
                                          <th class="text-left pb-1.5">No / Label</th>
                                          <th class="text-left pb-1.5">Due Date</th>
                                          <th class="text-right pb-1.5">Amount</th>
                                          <th class="text-right pb-1.5">Paid</th>
                                          <th class="text-center pb-1.5">Status</th>
                                      </tr>
                                  </thead>
                                  <tbody class="divide-y divide-slate-50">
                                      <template x-for="inst in (log.snapshot_data?.installments || [])" :key="inst.id || inst.installment_no">
                                          <tr>
                                              <td class="py-1 font-semibold text-slate-700" x-text="inst.label || ('EMI ' + inst.installment_no)"></td>
                                              <td class="py-1 text-slate-500 font-mono" x-text="inst.due_date || '—'"></td>
                                              <td class="py-1 text-right font-mono font-bold text-slate-800" x-text="'₹' + Number(inst.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                              <td class="py-1 text-right font-mono text-emerald-600 font-semibold" x-text="inst.paid_amount > 0 ? ('₹' + Number(inst.paid_amount).toLocaleString('en-IN', {minimumFractionDigits: 2})) : '—'"></td>
                                              <td class="py-1 text-center">
                                                  <span class="px-1.5 py-0.5 text-[8px] uppercase font-bold rounded"
                                                        :class="inst.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600'"
                                                        x-text="inst.status"></span>
                                              </td>
                                          </tr>
                                      </template>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </template>
              </div>

              <div x-show="emiTab !== 'history'" class="p-6 space-y-4 overflow-y-auto font-sans text-xs bg-slate-50/50 flex-1 min-h-0">
                  {{-- Dynamic Summary Stats --}}
                   <div class="grid grid-cols-3 gap-3 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-3.5 rounded-xl text-xs text-white border border-[#a38c29]/20">
                       <div>
                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Sale Amount</span>
                          <strong class="text-white text-xs sm:text-sm font-mono mt-0.5 block">₹<span x-text="effectiveTotalAmount().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                          <template x-if="effectiveTotalAmount() !== baseSaleTotalAmount">
                              <span class="text-[8px] text-slate-400 font-medium block mt-0.5 truncate" x-text="'(Base: ₹' + baseSaleTotalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}) + ')'"></span>
                          </template>
                      </div>
                        <div>
                          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Remaining Balance</span>
                          <strong class="text-rose-400 text-xs sm:text-sm font-mono mt-0.5 block">₹<span x-text="unpaidBalanceToRestructure().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                          <template x-if="parseFloat(adjustmentForm.discount_amount) > 0">
                              <span class="text-[8px] text-emerald-400 font-semibold block mt-0.5 truncate" x-text="'(-₹' + Number(adjustmentForm.discount_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) + ' Disc)'"></span>
                          </template>
                        </div>
                        <div>
                            <template x-if="hasActiveAdjustment()">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block" x-text="adjustmentCardLabel()"></span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <strong class="text-xs sm:text-sm font-mono text-emerald-400" x-text="adjustmentCardValue()"></strong>
                                    </div>
                                    <span class="text-[8px] text-emerald-400/90 font-semibold block mt-0.5" x-text="adjustmentCardSub()"></span>
                                </div>
                            </template>
                            <template x-if="!hasActiveAdjustment()">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Unallocated Balance</span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <strong class="text-xs sm:text-sm font-mono" :class="Math.abs(calculateUnallocated()) <= 0.01 ? 'text-emerald-400' : 'text-rose-400'">
                                            ₹<span x-text="calculateUnallocated().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                        </strong>
                                        <template x-if="Math.abs(calculateUnallocated()) > 0.01">
                                            <button type="button" @click="distributeRemaining()" class="px-1.5 py-0.5 bg-[#a38c29]/20 hover:bg-[#a38c29]/40 text-[#d9bf3b] text-[8px] font-bold uppercase rounded transition-colors border border-[#a38c29]/30 cursor-pointer" title="Auto-distribute difference across pending installments">
                                                Auto-Distribute
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                  </div>

                  {{-- Scrollable List of Rows --}}
                  <div class="space-y-2.5 pr-1 max-h-[300px] overflow-y-auto">
                      <template x-for="(inst, index) in editInstallments" :key="index">
                          <div class="flex items-center gap-3 p-3 rounded-xl border transition-all bg-white border-slate-200/85 shadow-xs"
                               :class="inst.status === 'paid' ? 'bg-emerald-50/20 border-emerald-100 shadow-none' : ''">
                              
                              {{-- Label Input --}}
                              <div class="w-1/4 space-y-1">
                                  <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Label</span>
                                  <input type="text" x-model="inst.label" :disabled="inst.status === 'paid'"
                                         class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                              </div>

                              {{-- Due Date Input --}}
                              <div class="w-1/4 space-y-1">
                                  <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Due Date</span>
                                  <input type="date" x-model="inst.due_date" :disabled="inst.status === 'paid'"
                                         class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                              </div>

                              {{-- Amount Input --}}
                              <div class="w-1/3 space-y-1">
                                  <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Amount (₹)</span>
                                  <input type="number" step="0.01" min="0" x-model.number="inst.amount" :disabled="inst.status === 'paid'"
                                         class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] focus:outline-none transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                              </div>

                              {{-- Status & Remove --}}
                              <div class="w-1/6 flex items-center justify-end gap-1.5 pt-2.5">
                                  <template x-if="inst.status === 'paid'">
                                      <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold uppercase rounded">Paid</span>
                                  </template>
                                  <template x-if="inst.status !== 'paid'">
                                      <button type="button" @click="removeInstallment(index)" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Row">
                                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                          </svg>
                                      </button>
                                  </template>
                              </div>
                          </div>
                      </template>
                  </div>
              </div>

              {{-- Footer Actions --}}
              <div class="px-6 py-4 border-t border-slate-200 flex justify-between items-center bg-slate-50 shrink-0">
                  <button type="button" @click="addInstallment()"
                          class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider flex items-center gap-1.5 border border-slate-200">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                      Add Installment
                  </button>
                  <div class="flex gap-2">
                      <button type="button" @click="emiModalOpen = false" 
                              class="px-3.5 py-1.5 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                          Cancel
                      </button>
                      <button type="button" @click="submitEmiSchedule()" x-bind:disabled="emiSubmitting"
                              class="px-4 py-1.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md flex items-center gap-1.5">
                          <span x-text="emiSubmitting ? 'Saving...' : 'Save Schedule'"></span>
                      </button>
                  </div>
              </div>
         </div>
    </div>

    {{-- View Receipt Modal --}}
    <div x-show="receiptModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop transition-opacity text-left"
         style="display: none;" x-transition.opacity>
         <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="receiptModalOpen = false">
              {{-- Header --}}
              <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                  <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                  <div class="relative z-10 flex items-center justify-between gap-4">
                      <div>
                          <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Receipt Log</span>
                          <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Receipt Details</h2>
                      </div>
                      <button type="button" @click="receiptModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                  </div>
              </div>

              <template x-if="viewReceiptData">
                  <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                      <div class="grid grid-cols-2 gap-4 bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Receipt Date</span>
                              <strong class="text-slate-800 text-xs font-bold block mt-1" x-text="formatDate(viewReceiptData.receipt_date)"></strong>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Amount Paid</span>
                              <strong class="text-emerald-700 text-sm font-mono font-extrabold block mt-1">₹<span x-text="Number(viewReceiptData.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                          </div>
                      </div>

                      <div class="grid grid-cols-2 gap-4 bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm">
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Payment Mode</span>
                              <span class="px-2.5 py-0.5 rounded text-[10px] font-bold font-mono uppercase inline-block mt-1 bg-slate-100 text-slate-600 border border-slate-200" x-text="viewReceiptData.payment_mode"></span>
                          </div>
                          <div>
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Reference / Transaction ID</span>
                              <span class="text-slate-800 text-xs font-mono font-bold mt-1 block" x-text="viewReceiptData.reference_no || 'N/A'"></span>
                          </div>
                      </div>

                      <template x-if="viewReceiptData.bank_name">
                          <div class="p-5 rounded-xl border border-slate-200/80 bg-white shadow-sm flex justify-between items-center">
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Bank Name</span>
                              <strong class="text-slate-800 text-xs font-bold" x-text="viewReceiptData.bank_name"></strong>
                          </div>
                      </template>
                      
                      <template x-if="viewReceiptData.remarks">
                          <div class="p-5 rounded-xl border border-slate-200/80 bg-[#a38c29]/5 shadow-sm">
                              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Remarks</span>
                              <p class="text-slate-600 text-xs bg-amber-50/50 p-3 rounded-lg border border-amber-100/50 italic font-medium" x-text="viewReceiptData.remarks"></p>
                          </div>
                      </template>
                  </div>
              </template>

              <template x-if="!viewReceiptData">
                  <div class="p-6 text-center text-xs text-slate-500 italic">
                      No receipt details found for this transaction.
                  </div>
              </template>
              
              <div class="px-6 py-4 border-t border-slate-200 flex justify-end bg-slate-50">
                  <button type="button" @click="receiptModalOpen = false" 
                          class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                      Close
                  </button>
              </div>
         </div>
    </div>

    {{-- Warning Popup Modal (Matching System Style) --}}
    <div x-show="warningModal.open" 
         class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-xs text-left" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="w-full max-w-md bg-white rounded-2xl overflow-hidden shadow-2xl flex flex-col animate-fade-in-up" 
             @click.away="warningModal.open = false">
            <div class="bg-[#1c1716] px-6 py-4 text-white flex items-center justify-between">
                <div class="space-y-1">
                    <span class="px-2.5 py-0.5 rounded-md bg-rose-500/20 text-rose-400 text-[10px] font-extrabold uppercase tracking-widest inline-block">WARNING</span>
                </div>
                <button type="button" @click="warningModal.open = false" 
                        class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs cursor-pointer">✕</button>
            </div>
            
            <div class="p-6 space-y-5 text-slate-700 text-xs pb-6">
                <p class="font-bold text-slate-900 text-sm">
                    Cannot process this installment payment.
                </p>
                
                <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-2xl space-y-2 text-amber-900 shadow-2xs">
                    <p class="font-extrabold uppercase tracking-wider text-[11px] text-amber-800 flex items-center gap-1.5">
                        <span>⚠️ PAYMENT BLOCKED</span>
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 text-[12px] text-slate-700 font-medium leading-relaxed mt-2">
                        <li class="font-semibold text-rose-800" x-text="warningModal.message"></li>
                        <li>Please clear installments sequentially to maintain accurate ledger balances.</li>
                    </ul>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button" @click="warningModal.open = false" 
                            class="px-5 py-2.5 bg-[#1c1716] hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider cursor-pointer">
                        Understood
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal (Matching System Style) --}}
    <div x-show="successModal.open" 
         class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs text-left" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden pointer-events-auto transform transition-all"
             style="animation: successModalIn 0.35s cubic-bezier(0.34,1.56,0.64,1) both;"
             @click.away="closeSuccessModal()">
            
            {{-- Gold shimmer top stripe --}}
            <div class="h-1 w-full bg-gradient-to-r from-[#a38c29] via-[#d9bf3b] to-[#a38c29]"></div>

            {{-- Dark header --}}
            <div class="relative overflow-hidden px-6 py-5 bg-slate-900" style="background-color: #0f172a;">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        {{-- Animated Checkmark Icon --}}
                        <div class="w-12 h-12 rounded-xl bg-[#a38c29]/20 border border-[#a38c29]/40 flex items-center justify-center shadow-lg shadow-[#a38c29]/20 ring-1 ring-[#d9bf3b]/20 shrink-0"
                             style="animation: iconPop 0.5s cubic-bezier(0.34,1.56,0.64,1) 0.2s both;">
                            <svg class="w-6 h-6 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"
                                      style="stroke-dasharray:30; stroke-dashoffset:30; animation: drawCheck 0.5s ease-out 0.4s forwards;"/>
                            </svg>
                        </div>

                        <div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-[#a38c29]/25 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>PAYMENT RECORDED</span>
                            </span>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Payment Successful!</h2>
                            <p class="text-[10px] text-slate-400 mt-0.5 font-medium">Installment receipt recorded & verified.</p>
                        </div>
                    </div>

                    {{-- Close Button --}}
                    <button type="button" @click="closeSuccessModal()" title="Close"
                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#a38c29]/30 text-white hover:text-[#d9bf3b] flex items-center justify-center transition-all focus:outline-none shrink-0 border border-white/10 hover:border-[#a38c29]/40 text-sm font-bold cursor-pointer">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 bg-gradient-to-b from-slate-50/80 to-white space-y-3">
                {{-- Message Card --}}
                <div class="flex items-center gap-3 bg-white border border-[#a38c29]/25 rounded-xl px-4 py-3.5 shadow-sm ring-1 ring-[#a38c29]/10">
                    <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[9px] font-bold text-[#a38c29]/70 uppercase tracking-widest">Installment Settled</p>
                        <p class="text-xs font-extrabold text-slate-900 mt-0.5" x-text="successModal.message"></p>
                    </div>
                </div>

                {{-- Auto-close progress bar --}}
                <div class="flex items-center gap-2.5">
                    <div class="flex-1 h-1 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#a38c29] to-[#d9bf3b] rounded-full transition-all duration-75 ease-linear"
                             :style="'width: ' + successModal.progressWidth + '%;'"></div>
                    </div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider whitespace-nowrap">Auto-refresh</span>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-white flex items-center justify-end">
                <button type="button" @click="closeSuccessModal()"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#7a6920] text-white text-xs font-bold shadow-md shadow-[#a38c29]/25 uppercase tracking-wider transition-all ring-1 ring-[#a38c29]/30 flex items-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Got it</span>
                </button>
            </div>
        </div>
    </div>

@php
    $ledgerRateLogs = \App\Models\SaleStatusLog::where('sale_id', $sale->id)
        ->where('event_type', 'rate_adjustment')
        ->latest()->limit(5)->get()
        ->map(function($l) {
            return [
                'date'     => \Carbon\Carbon::parse($l->created_at)->format('d M Y'),
                'old_rate' => $l->snapshot_data['old_rate'] ?? 0,
                'new_rate' => $l->snapshot_data['new_rate'] ?? 0,
            ];
        })->values();

    $ledgerDiscountLogs = \App\Models\SaleStatusLog::where('sale_id', $sale->id)
        ->where('event_type', 'discount_applied')
        ->latest()->limit(5)->get()
        ->map(function($l) {
            return [
                'date'   => \Carbon\Carbon::parse($l->created_at)->format('d M Y'),
                'amount' => $l->snapshot_data['discount_amount'] ?? 0,
            ];
        })->values();
@endphp

<script>
function ledgerApp() {
    return {
        warningModal: {
            open: false,
            message: ''
        },
        successModal: {
            open: false,
            message: '',
            progressWidth: 100,
            _timer: null,
            _progressInterval: null
        },
        openSuccessModal(message) {
            this.modalOpen = false;
            this.successModal.message = message;
            this.successModal.progressWidth = 100;
            this.successModal.open = true;

            const duration = 3500;
            const startTime = Date.now();
            if (this.successModal._progressInterval) clearInterval(this.successModal._progressInterval);
            this.successModal._progressInterval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const remaining = Math.max(0, 100 - (elapsed / duration) * 100);
                this.successModal.progressWidth = remaining;
                if (remaining <= 0) {
                    clearInterval(this.successModal._progressInterval);
                }
            }, 50);

            if (this.successModal._timer) clearTimeout(this.successModal._timer);
            this.successModal._timer = setTimeout(() => {
                this.closeSuccessModal();
            }, duration);
        },
        closeSuccessModal() {
            if (this.successModal._timer) clearTimeout(this.successModal._timer);
            if (this.successModal._progressInterval) clearInterval(this.successModal._progressInterval);
            this.successModal.open = false;
            window.location.reload();
        },
        modalOpen: false,
        submitting: false,
        error: '',
        errors: {},
        form: {
            sale_id: '{{ $sale->id }}',
            amount: 0,
            receipt_date: new Date().toISOString().split('T')[0],
            payment_mode: '{{ $paymentModes->first()?->name ?? '' }}',
            reference_no: '',
            bank_id: '',
            partner_id: '',
            remarks: '',
            label: ''
        },

        // Rate & Discount Adjustment State (Calculates directly on Available Balance without mutating base unit rates)
        baseSaleTotalAmount: {{ (float)$sale->total_amount }},
        builtUpAreaSqft: {{ (float)($sale->unit?->built_up_area ?? 0) }},
        baseSaleRateSqft: {{ (float)($sale->rate_per_sqft ?? $sale->unit?->sale_rate_per_sqft ?? 0) }},
        loggedRate: {{ $loggedRate ? (float)$loggedRate : 'null' }},
        currentSaleRateSqft: {{ (float)($loggedRate ?: ($sale->rate_per_sqft ?? $sale->unit?->sale_rate_per_sqft ?? 0)) }},
        adjustedRateSqft: {{ (float)($loggedRate ?: ($sale->rate_per_sqft ?? $sale->unit?->sale_rate_per_sqft ?? 0)) }},
        currentFinalDiscount: {{ (float)($sale->final_discount ?? 0) }},
        gstPercentage: {{ (float)($sale->gst_percentage ?? 0) }},
        adjustmentForm: {
            built_up_area: {{ (float)($sale->unit?->built_up_area ?? 0) }},
            current_rate: {{ (float)($loggedRate ?: ($sale->rate_per_sqft ?? $sale->unit?->sale_rate_per_sqft ?? 0)) }},
            new_rate: '',
            discount_amount: ''
        },
        rateLogs: @json($ledgerRateLogs ?? []),
        discountLogs: @json($ledgerDiscountLogs ?? []),

        calculateTotalPaid() {
            return this.editInstallments
                .filter(inst => inst.status === 'paid')
                .reduce((sum, inst) => sum + Number(inst.amount), 0);
        },

        _r2(n) { return Math.round(n * 100) / 100; },

        // Returns the effective adjusted total amount (for unallocated balance and balance check)
        effectiveTotalAmount() {
            const newRate = parseFloat(this.adjustmentForm.new_rate);
            const area   = parseFloat(this.adjustmentForm.built_up_area) || this.builtUpAreaSqft;
            const initialScheduleTotal = (this.originalInstallments && this.originalInstallments.length > 0)
                ? this._r2(this.originalInstallments.reduce((sum, inst) => sum + Number(inst.amount), 0))
                : this.baseSaleTotalAmount;

            let total = initialScheduleTotal;
            if (newRate && newRate > 0 && area > 0) {
                const base = this._r2(area * newRate);
                const gst  = this._r2(base * (this.gstPercentage / 100));
                total = this._r2(base + gst);
            }
            const discount = parseFloat(this.adjustmentForm.discount_amount);
            if (discount && discount > 0) total = Math.max(0, this._r2(total - discount));
            return this._r2(total);
        },

        existingRemainingBalance() {
            const paidSum = this.calculateTotalPaid();
            const initialScheduleTotal = (this.originalInstallments && this.originalInstallments.length > 0)
                ? this._r2(this.originalInstallments.reduce((sum, inst) => sum + Number(inst.amount), 0))
                : this.baseSaleTotalAmount;
            return Math.max(0, this._r2(initialScheduleTotal - paidSum));
        },

        unpaidBalanceToRestructure() {
            const paidSum = this.calculateTotalPaid();
            return Math.max(0, this._r2(this.effectiveTotalAmount() - paidSum));
        },

        hasActiveAdjustment() {
            if (this.emiTab !== 'restructure') return false;
            const hasDiscount = parseFloat(this.adjustmentForm.discount_amount) > 0;
            const hasRate = parseFloat(this.adjustmentForm.new_rate) > 0;
            return (hasDiscount || hasRate);
        },

        adjustmentCardLabel() {
            const hasDiscount = parseFloat(this.adjustmentForm.discount_amount) > 0;
            const hasRate = parseFloat(this.adjustmentForm.new_rate) > 0;
            if (hasDiscount && hasRate) return 'Discount & Rate Adj';
            if (hasDiscount) return 'Discount';
            return 'Rate Adjustment';
        },

        adjustmentCardValue() {
            const hasDiscount = parseFloat(this.adjustmentForm.discount_amount) > 0;
            const hasRate = parseFloat(this.adjustmentForm.new_rate) > 0;
            if (hasDiscount) {
                return '₹' + Number(this.adjustmentForm.discount_amount).toLocaleString('en-IN', {minimumFractionDigits: 2});
            }
            if (hasRate) {
                const initialScheduleTotal = (this.originalInstallments && this.originalInstallments.length > 0)
                    ? this._r2(this.originalInstallments.reduce((sum, inst) => sum + Number(inst.amount), 0))
                    : this.baseSaleTotalAmount;
                const diff = this._r2(initialScheduleTotal - this.effectiveTotalAmount());
                return (diff >= 0 ? '₹' : '-₹') + Math.abs(diff).toLocaleString('en-IN', {minimumFractionDigits: 2});
            }
            return '₹0.00';
        },

        adjustmentCardSub() {
            const hasDiscount = parseFloat(this.adjustmentForm.discount_amount) > 0;
            if (hasDiscount) return 'Deducted from balance';
            return 'Applied to balance';
        },
        openPayModal(amount, label, isOutOfOrder = false, firstUnpaidLabel = '') {
            this.error = '';
            this.errors = {};
            this.form.amount = amount;
            this.form.label = label;
            this.form.isOutOfOrder = isOutOfOrder;
            this.form.firstUnpaidLabel = firstUnpaidLabel;
            this.form.receipt_date = new Date().toISOString().split('T')[0];
            this.form.payment_mode = '{{ $paymentModes->first()?->name ?? '' }}';
            this.form.reference_no = '';
            this.form.bank_id = '';
            this.form.partner_id = '';
            this.form.remarks = '';
            this.modalOpen = true;
        },
        async submitPayment() {
            this.error = '';
            this.errors = {};

            if (this.form.isOutOfOrder) {
                const msg = "Installments must be paid in sequential order. Please settle earlier pending installment '" + this.form.firstUnpaidLabel + "' first before paying '" + this.form.label + "'.";
                this.error = msg;
                this.warningModal.message = msg;
                this.modalOpen = false;
                this.warningModal.open = true;
                return;
            }

            let hasError = false;

            if (this.form.amount === '' || this.form.amount === null || this.form.amount === undefined || parseFloat(this.form.amount) <= 0 || isNaN(parseFloat(this.form.amount))) {
                this.errors.amount = ['please enter amount'];
                hasError = true;
            }
            if (!this.form.receipt_date) {
                this.errors.receipt_date = ['please select receipt date'];
                hasError = true;
            }
            if (!this.form.payment_mode) {
                this.errors.payment_mode = ['please select payment mode'];
                hasError = true;
            }
            if (!this.form.reference_no || !this.form.reference_no.toString().trim()) {
                this.errors.reference_no = ['please enter ref / cheque no'];
                hasError = true;
            }

            if (hasError) {
                return;
            }

            this.submitting = true;
            try {
                const res = await fetch('{{ route('emi-collections.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ...this.form, _token: '{{ csrf_token() }}' }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    const paidLabel = this.form.label || 'Installment';
                    const paidAmt = Number(this.form.amount).toLocaleString('en-IN', {minimumFractionDigits: 2});
                    this.openSuccessModal(`Successfully collected ₹${paidAmt} for ${paidLabel}.`);
                } else if (json.errors) {
                    this.errors = json.errors;
                } else {
                    const errMsg = json.error || json.message || 'An error occurred.';
                    this.error = errMsg;
                    if (errMsg.includes('sequential order') || errMsg.includes('earlier pending installment')) {
                        this.modalOpen = false;
                        this.warningModal.message = errMsg;
                        this.warningModal.open = true;
                    }
                }
            } catch(e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.submitting = false;
            }
        },

        // Custom EMI schedule editor state
        originalInstallments: @json($installments),
        restructureLogs: @json($restructureLogs ?? []),
        editInstallments: [],
        emiModalOpen: false,
        emiTab: 'manual',
        restructureCount: '',
        restructureStartDate: '',
        totalSaleAmount: {{ $sale->total_amount }},
        emiSubmitting: false,
        emiError: '',
        
        // View Receipt Modal State
        receiptsData: @json($sale->receipts),
        receiptModalOpen: false,
        viewReceiptId: null,
        get viewReceiptData() {
            if (!this.viewReceiptId) return null;
            return this.receiptsData.find(r => r.id == this.viewReceiptId) || null;
        },
        formatDate(dateStr) {
            if (!dateStr) return '—';
            try {
                const isoStr = String(dateStr).replace(' ', 'T');
                const d = new Date(isoStr);
                if (isNaN(d.getTime())) return String(dateStr);
                return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            } catch(e) {
                return String(dateStr);
            }
        },
        openReceiptModal(id) {
            console.log("Opening receipt modal for ID:", id);
            this.viewReceiptId = id;
            if ((!this.viewReceiptData || !id) && this.receiptsData && this.receiptsData.length > 0) {
                this.viewReceiptId = this.receiptsData[this.receiptsData.length - 1].id;
            }
            this.receiptModalOpen = true;
        },

        openEmiModal() {
            this.emiError = '';
            this.emiTab = 'restructure';
            this.restructureCount = '';
            this.restructureStartDate = '';
            // Reset adjustment inputs to defaults each time modal opens
            this.adjustmentForm.built_up_area = this.builtUpAreaSqft;
            this.adjustmentForm.current_rate  = this.currentSaleRateSqft;
            this.adjustmentForm.new_rate       = '';
            this.adjustmentForm.discount_amount = '';
            this.editInstallments = this.originalInstallments.map(inst => ({
                id: inst.id,
                installment_no: inst.installment_no,
                label: inst.label,
                due_date: inst.due_date ? inst.due_date.split('T')[0] : '',
                amount: Number(inst.amount),
                status: inst.status
            }));

            // Pre-fill next due date from first unpaid installment if available
            const firstUnpaid = this.originalInstallments.find(inst => inst.status !== 'paid');
            if (firstUnpaid && firstUnpaid.due_date) {
                this.restructureStartDate = firstUnpaid.due_date.split('T')[0];
            }

            this.emiModalOpen = true;
        },
        applyRestructure() {
            this.emiError = '';
            const count = parseInt(this.restructureCount);
            if (isNaN(count) || count < 1) {
                this.emiError = 'Please enter a valid number of EMIs (minimum 1).';
                return;
            }
            if (!this.restructureStartDate) {
                this.emiError = 'Please choose a starting due date for restructured EMIs.';
                return;
            }

            const remainingBalance = this.unpaidBalanceToRestructure();
            if (remainingBalance <= 0) {
                this.emiError = 'No remaining unpaid balance to restructure.';
                return;
            }

            // Keep all paid installments
            const preservedPaid = this.editInstallments.filter(inst => inst.status === 'paid');
            
            // Generate restructured EMIs
            const baseAmount = Math.floor((remainingBalance / count) * 100) / 100;
            const remainder = Math.round((remainingBalance - (baseAmount * count)) * 100) / 100;

            const newInstallments = [];
            const startDateParts = this.restructureStartDate.split('-');
            const year = parseInt(startDateParts[0]);
            const month = parseInt(startDateParts[1]) - 1;
            const day = parseInt(startDateParts[2]);

            // Find starting index for labels
            let startIndex = 1;
            if (preservedPaid.length > 0) {
                const maxNo = Math.max(...preservedPaid.map(i => i.installment_no));
                startIndex = maxNo + 1;
            }

            for (let i = 0; i < count; i++) {
                // Calculate next month correctly without timezone shifts
                let dYear = year + Math.floor((month + i) / 12);
                let dMonth = (month + i) % 12;
                // Clamp day to max days in month (e.g. Feb 28/29, Apr 30)
                let maxDays = new Date(dYear, dMonth + 1, 0).getDate();
                let dDay = Math.min(day, maxDays);
                
                const mm = String(dMonth + 1).padStart(2, '0');
                const dd = String(dDay).padStart(2, '0');
                const isoDue = `${dYear}-${mm}-${dd}`;
                
                // Add the cents remainder to the last installment
                const amt = (i === count - 1) 
                    ? Math.round((baseAmount + remainder) * 100) / 100 
                    : baseAmount;

                newInstallments.push({
                    id: null,
                    installment_no: startIndex + i,
                    label: 'EMI ' + (startIndex + i),
                    due_date: isoDue,
                    amount: amt,
                    status: 'pending'
                });
            }

            this.editInstallments = [...preservedPaid, ...newInstallments];
            this.emiTab = 'manual';
        },
        restoreFromLog(log) {
            if (!log || !log.snapshot_data || !log.snapshot_data.installments) {
                this.emiError = 'No installments data found in this log.';
                return;
            }
            this.emiError = '';
            this.editInstallments = log.snapshot_data.installments.map(inst => ({
                id: inst.id,
                installment_no: inst.installment_no,
                label: inst.label,
                due_date: inst.due_date ? inst.due_date.split('T')[0] : '',
                amount: Number(inst.amount),
                status: inst.status
            }));
            if (log.snapshot_data && log.snapshot_data.new_rate) {
                const r = Number(log.snapshot_data.new_rate);
                this.currentSaleRateSqft = r;
                this.adjustedRateSqft = r;
                this.adjustmentForm.current_rate = r;
                this.loggedRate = r;
            }
            this.emiTab = 'manual';
        },
        addInstallment() {
            const nextNo = this.editInstallments.length > 0 
                ? Math.max(...this.editInstallments.map(i => i.installment_no)) + 1 
                : 1;
            
            let lastDate = new Date();
            if (this.editInstallments.length > 0) {
                const dates = this.editInstallments.map(i => i.due_date).filter(Boolean);
                if (dates.length > 0) {
                    lastDate = new Date(dates[dates.length - 1]);
                    lastDate.setMonth(lastDate.getMonth() + 1);
                }
            }
            
            this.editInstallments.push({
                installment_no: nextNo,
                label: 'EMI ' + nextNo,
                due_date: lastDate.toISOString().split('T')[0],
                amount: 0,
                status: 'pending'
            });
        },
        removeInstallment(index) {
            if (this.editInstallments[index].status === 'paid') return;
            this.editInstallments.splice(index, 1);
            this.editInstallments.forEach((inst, idx) => {
                if (inst.installment_no > 0) {
                    inst.installment_no = idx;
                }
            });
        },
        calculateTotalAllocated() {
            return this._r2(this.editInstallments.reduce((sum, inst) => sum + Number(inst.amount), 0));
        },
        calculateUnallocated() {
            // Use the effective (rate/discount-adjusted) total, not the original totalSaleAmount
            return this._r2(this.effectiveTotalAmount() - this.calculateTotalAllocated());
        },
        distributeRemaining() {
            const unallocated = this.calculateUnallocated();
            const pendingInsts = this.editInstallments.filter(inst => inst.status !== 'paid');
            if (pendingInsts.length === 0) {
                this.emiError = 'No pending installments to distribute balance to.';
                return;
            }
            
            const perInstallment = Math.round((unallocated / pendingInsts.length) * 100) / 100;
            pendingInsts.forEach((inst, idx) => {
                if (idx === pendingInsts.length - 1) {
                    const allocatedSoFar = perInstallment * (pendingInsts.length - 1);
                    inst.amount = Math.max(0, Math.round((Number(inst.amount) + (unallocated - allocatedSoFar)) * 100) / 100);
                } else {
                    inst.amount = Math.max(0, Math.round((Number(inst.amount) + perInstallment) * 100) / 100);
                }
            });
        },
        async submitEmiSchedule() {
            this.emiError = '';
            const unallocated = this.calculateUnallocated();
            if (Math.abs(unallocated) > 0.01) {
                this.emiError = `Unallocated balance must be 0 (current: ₹${unallocated.toLocaleString('en-IN', {minimumFractionDigits: 2})}). Click 'Recalculate & Generate' or 'Auto-Distribute' to balance.`;
                return;
            }
            this.emiSubmitting = true;
            try {
                // Build adjustment metadata (only send non-empty values)
                const adjustmentPayload = {};
                const newRate = parseFloat(this.adjustmentForm.new_rate);
                const area    = parseFloat(this.adjustmentForm.built_up_area);
                const discount = parseFloat(this.adjustmentForm.discount_amount);
                if (newRate && newRate > 0)  adjustmentPayload.new_rate = newRate;
                if (area   && area   > 0)    adjustmentPayload.built_up_area = area;
                if (discount && discount > 0) adjustmentPayload.discount_amount = discount;
                adjustmentPayload.old_rate = this.currentSaleRateSqft;
                adjustmentPayload.effective_total = this.effectiveTotalAmount();

                const res = await fetch('{{ route('emi-collections.schedules.bulk-update', $sale->id) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        installments : this.editInstallments,
                        adjustment   : adjustmentPayload,
                        _token       : '{{ csrf_token() }}'
                    }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.emiModalOpen = false;
                    window.location.reload();
                } else {
                    this.emiError = json.error || json.message || 'An error occurred.';
                }
            } catch(e) {
                this.emiError = 'Request failed: ' + e.message;
            } finally {
                this.emiSubmitting = false;
            }
        },

        amountInWords(amount) {
            if (!amount || isNaN(amount) || parseFloat(amount) <= 0) return '';
            const num = Math.floor(parseFloat(amount));
            const paise = Math.round((parseFloat(amount) - num) * 100);

            const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                           'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
            const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

            function convert(n) {
                if (n < 20) return units[n];
                if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + units[n % 10] : '');
                if (n < 1000) return units[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + convert(n % 100) : '');
                if (n < 100000) return convert(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + convert(n % 1000) : '');
                if (n < 10000000) return convert(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + convert(n % 100000) : '');
                return convert(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + convert(n % 10000000) : '');
            }

            let words = convert(num);
            if (!words) return '';
            let result = 'IN WORDS: ' + words.toUpperCase() + ' RUPEES';
            if (paise > 0) {
                result += ' AND ' + convert(paise).toUpperCase() + ' PAISE';
            }
            result += ' ONLY';
            return result;
        }
    };
}
</script>

</x-erp-layout>
