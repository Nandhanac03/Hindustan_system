<x-erp-layout title="Commission Ledger" headerTitle="Commission Ledger">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Top Header & Title --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v8m-6 0h6"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Commission Ledger</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Real-time visibility into every property deal, commission amount, and payment completion status.</p>
        </div>
    </div>

    {{-- Key Metrics KPI Cards (4 Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Commission --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-pointer">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] border border-[#a38c29]/20 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL COMMISSION</span>
                </div>
                <span class="text-[9px] text-[#8a7522] font-bold bg-[#a38c29]/10 px-2 py-0.5 rounded-md border border-[#a38c29]/20 uppercase tracking-wider">All Deals</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-[#a38c29] transition-colors duration-300">₹{{ number_format($totalCommission ?? ($totalAccrued + $totalPayable + $totalPaid), 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total broker commission allocated to date</p>
            </div>
        </div>

        {{-- Card 2: Pending Clearance (Locked) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)] cursor-pointer">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">PENDING CLEARANCE</span>
                </div>
                <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider">Locked</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-amber-600 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">₹{{ number_format($totalAccrued, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Unlocks when customer completes payment/EMI</p>
            </div>
        </div>

        {{-- Card 3: Ready for Payout (Unlocked) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-pointer">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">READY FOR PAYOUT</span>
                </div>
                <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider">Approved</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">₹{{ number_format($totalPayable, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Customer payment cleared; approved for release</p>
            </div>
        </div>

        {{-- Card 4: Total Paid Out --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-pointer">
            <div class="flex items-center justify-between mb-3 relative z-10">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL PAID OUT</span>
                </div>
                <span class="text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider">Disbursed</span>
            </div>
            
            <div class="relative z-10 mt-1">
                <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300">₹{{ number_format($totalPaid, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Total commission payouts released to date</p>
            </div>
        </div>
    </div>

    {{-- Transaction-wise Commission Visibility Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Transaction-wise Commission Visibility</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Real-time visibility into every property deal, commission amount, and payment completion status.</p>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('brokers.commission-ledger') }}" class="flex items-center gap-2">
                <select name="broker_id" onchange="this.form.submit()"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none shadow-2xs font-semibold">
                    <option value="">All Brokers</option>
                    @foreach($brokers as $b)
                        <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
                @if(request('broker_id'))
                    <a href="{{ route('brokers.commission-ledger') }}" class="text-[10px] text-slate-400 hover:text-slate-700 font-bold underline">Clear</a>
                @endif
            </form>
        </div>

        <style>
            .broker-table thead th { border-color: #8a7522 !important; }
            .broker-tbody tr:nth-child(even) { background-color: #F6F3E9 !important; }
            .broker-tbody tr:hover { background-color: #ebe5d0 !important; }
        </style>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1100px] broker-table border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border">Booking & Date</th>
                        <th class="px-3 py-3 border">Property / Project</th>
                        <th class="px-3 py-3 border">Broker / Agent</th>
                        <th class="px-3 py-3 border">Net Sale Value</th>
                        <th class="px-3 py-3 border">Sale EMI Progress</th>
                        <th class="px-3 py-3 border">Commission</th>
                        <th class="px-3 py-3 border">Commission Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 broker-tbody">
                    @forelse($deals as $brokerage)
                        @php
                            $sale = $brokerage->sale;
                            if (!$sale) continue;

                            $paidAmt    = (float)($brokerage->paid_amount ?? 0);
                            $commAmt    = (float)($brokerage->commission_amount ?? 0);
                            $commAmount = $commAmt;
                            $remaining  = max(0, $commAmt - $paidAmt);

                            $rawStatus = $brokerage->status ?? 'pending';
                            $effectiveStatus = match(true) {
                                $paidAmt >= $commAmt - 0.01 && $commAmt > 0 => 'paid',
                                $paidAmt > 0 => 'partial',
                                default => $rawStatus
                            };

                            $badgeClass = match($effectiveStatus) {
                                'paid'     => 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow-2xs font-bold',
                                'partial'  => 'bg-[#a38c29]/10 text-[#8a7522] border-[#a38c29]/30 shadow-2xs font-bold',
                                'payable'  => 'bg-teal-50 text-teal-700 border-teal-200 shadow-2xs font-bold',
                                default    => 'bg-amber-50 text-amber-700 border-amber-200 font-semibold'
                            };

                            $statusLabel = match($effectiveStatus) {
                                'payable'  => 'Ready for Payout',
                                'partial'  => 'Partially Paid',
                                'paid'     => 'Paid Out',
                                default    => 'Pending Clearance'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-3 py-4 border text-center">
                                <div class="font-bold text-[#a38c29] font-mono">{{ $sale->sale_number ?? 'N/A' }}</div>
                                <div class="text-[9px] text-slate-500 mt-0.5">{{ $sale->sale_date ? $sale->sale_date->format('d M Y') : 'N/A' }}</div>
                                <div class="text-[10px] font-semibold text-slate-800 mt-0.5">{{ $sale->customer->name ?? 'Customer' }}</div>
                            </td>
                            <td class="px-3 py-4 border text-center">
                                <div class="font-bold text-slate-900">{{ $sale->project->name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-600 mt-0.5">Unit: <span class="font-bold text-slate-800 font-mono">{{ $sale->unit->door_no ?? 'N/A' }}</span></div>
                            </td>
                            <td class="px-3 py-4 border text-center">
                                <div class="font-bold text-slate-800">{{ $brokerage->broker->name ?? 'Direct' }}</div>
                                <div class="text-[9px] text-slate-500 font-mono mt-0.5">Rate: {{ number_format($brokerage->commission_percent ?? $brokerage->broker->default_commission_pct ?? 0, 2) }}%</div>
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-bold text-slate-900">
                                ₹{{ number_format($sale->total_amount ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 border text-center">
                                @if($sale)
                                    @if($sale->remaining_balance <= 0)
                                        <span class="inline-flex items-center justify-center gap-1 text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            100% Paid / EMI Complete
                                        </span>
                                    @else
                                        <div class="space-y-1 mx-auto max-w-[120px]">
                                            <div class="flex justify-between text-[10px]">
                                                <span class="text-slate-600 font-semibold">Pending Bal.</span>
                                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($sale->remaining_balance, 2) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden border border-slate-300">
                                                @php
                                                    $pctPaid = $sale->total_amount > 0 ? (($sale->total_amount - $sale->remaining_balance) / $sale->total_amount) * 100 : 0;
                                                @endphp
                                                <div class="bg-[#a38c29] h-full rounded-full" style="width: {{ min(100, max(0, $pctPaid)) }}%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-500 block text-center">{{ number_format($pctPaid, 0) }}% collected</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-500 italic text-[10px]">N/A</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 border text-center">
                                <div class="font-mono font-black text-slate-900 text-sm">₹{{ number_format($commAmount, 2) }}</div>
                                @if($brokerage->commission_percent)
                                <div class="text-[9px] text-slate-500 uppercase mt-0.5">@ {{ number_format($brokerage->commission_percent, 2) }}% of sale</div>
                                @endif
                            </td>
                            <td class="px-3 py-4 border text-center align-middle">
                                <div class="flex flex-col items-center justify-center gap-2 w-[140px] mx-auto">
                                    <span class="w-full border px-2 py-1.5 rounded-xl font-bold text-[9px] uppercase {{ $badgeClass }} shadow-sm tracking-wide text-center">
                                        {{ $statusLabel }}
                                    </span>
                                    
                                    @if($effectiveStatus === 'pending')
                                        <span class="text-[9px] text-slate-400 italic text-center w-full">Pending customer payment</span>
                                    @elseif($effectiveStatus === 'payable')
                                        <span class="text-[9px] text-teal-700 font-bold text-center w-full">Ready for disbursement</span>
                                    @elseif($effectiveStatus === 'partial')
                                        <div class="w-full space-y-1">
                                            <div class="flex justify-between text-[9px]">
                                                <span class="text-[#8a7522] font-bold">Paid: ₹{{ number_format($paidAmt, 0) }}</span>
                                                <span class="text-rose-600 font-bold">Bal: ₹{{ number_format($remaining, 0) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                @php $pctPaidComm = $commAmt > 0 ? ($paidAmt / $commAmt) * 100 : 0; @endphp
                                                <div class="bg-[#a38c29] h-full rounded-full" style="width: {{ min(100, $pctPaidComm) }}%;"></div>
                                            </div>
                                            <span class="text-[9px] text-[#8a7522] font-bold block text-center">{{ number_format($pctPaidComm, 0) }}% paid</span>
                                        </div>
                                    @elseif($effectiveStatus === 'paid')
                                        <span class="text-[9px] text-emerald-700 font-bold text-center w-full">✓ Fully settled ₹{{ number_format($commAmt, 0) }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 border text-center text-slate-500 italic">No broker sales or transactions recorded yet. When sales are registered with a broker, commission entries will appear here automatically.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deals->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $deals->links() }}
            </div>
        @endif
    </div>

</div>

</x-erp-layout>

