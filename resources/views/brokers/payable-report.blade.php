<x-erp-layout title="Brokerage Payable Report" headerTitle="Brokerage Payable Report">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Header & Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('brokers.index') }}" class="text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Brokerage Payable Report</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Broker-wise breakdown of pending commissions. Commissions become payable only after full payment or EMI completion.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('brokers.index') }}" 
               class="inline-flex items-center gap-2 rounded-xl border border-slate-250 bg-white px-4 py-2 text-xs font-bold uppercase tracking-wide text-slate-700 shadow-2xs transition-all hover:bg-slate-50">
                ← Back to Brokerage Dashboard
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-md transition-all hover:bg-slate-800">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Report
            </button>
        </div>
    </div>

    {{-- Feedback Messages --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs font-bold text-emerald-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs font-bold text-rose-800 uppercase tracking-wide flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- KPI Highlights Banner --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-amber-500/10 to-amber-500/5 border border-amber-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-amber-800 uppercase tracking-widest block">Accrued Commission (Locked)</span>
                <span class="text-2xl font-black text-amber-700 font-mono mt-1 block">₹{{ number_format($totalAccrued, 2) }}</span>
                <span class="text-[9px] text-amber-600/80 mt-1 block font-semibold">Pending customer full payment / EMI</span>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
        </div>

        <div class="bg-gradient-to-br from-emerald-500/15 to-emerald-500/5 border border-emerald-300 rounded-2xl p-5 shadow-md flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-widest block">Payable Commission (Unlocked)</span>
                <span class="text-2xl font-black text-emerald-700 font-mono mt-1 block">₹{{ number_format($totalPayable, 2) }}</span>
                <span class="text-[9px] text-emerald-700 mt-1 block font-bold">Ready for immediate disbursement</span>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
        </div>

        <div class="bg-gradient-to-br from-indigo-500/10 to-indigo-500/5 border border-indigo-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-indigo-800 uppercase tracking-widest block">Total Settled & Paid</span>
                <span class="text-2xl font-black text-indigo-700 font-mono mt-1 block">₹{{ number_format($totalPaid, 2) }}</span>
                <span class="text-[9px] text-indigo-600/80 mt-1 block font-semibold">Historical commission disbursements</span>
            </div>
            <span class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
    </div>

    {{-- Broker-wise Pending Commission Summary Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Broker-wise Pending Commission Breakdown</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Summary of pending commissions per broker. Disburse unlocked payable balances directly to ledger accounts.</p>
            </div>
            <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-xl shadow-2xs">Showing {{ count($brokerReports) }} Registered Broker(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Broker Name & Ledger Account</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Default Rate</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Accrued (Locked)</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Payable (Unlocked)</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Pending</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Settled</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Disbursement Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($brokerReports as $report)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $report->broker->name }}</div>
                                <div class="text-[9px] text-slate-400 font-mono mt-0.5 flex items-center gap-1">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-600 font-bold">A/C: {{ $report->broker->linkedAccount->code ?? 'N/A' }}</span>
                                    <span>{{ $report->broker->linkedAccount->name ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-700">
                                {{ number_format($report->broker->default_commission_pct, 2) }}%
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-amber-600">
                                ₹{{ number_format($report->accrued, 2) }}
                                @if($report->accrued > 0)
                                    <span class="text-[9px] text-amber-600/80 block font-sans font-normal">Awaiting EMI completion</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-emerald-600 text-sm">
                                ₹{{ number_format($report->payable, 2) }}
                                @if($report->payable > 0)
                                    <span class="text-[9px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-sans font-bold border border-emerald-200 block mt-1 w-max">Ready to Pay</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono font-black text-slate-900">
                                ₹{{ number_format($report->total_pending, 2) }}
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-slate-500">
                                ₹{{ number_format($report->paid, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($report->payable > 0)
                                    <form action="{{ route('brokers.payout') }}" method="POST" onsubmit="return confirm('Disburse total payable commission of ₹{{ number_format($report->payable, 2) }} to {{ $report->broker->name }}?')">
                                        @csrf
                                        <input type="hidden" name="broker_id" value="{{ $report->broker->id }}">
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all shadow-md uppercase tracking-wide">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            Disburse ₹{{ number_format($report->payable, 0) }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 italic text-[10px]">No payable balance</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-450 italic">No broker records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detailed Transaction-wise Commission Register --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Transaction-wise Commission Register</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Detailed log of all pending broker transactions. Accrued commissions unlock automatically when booking outstanding balance reaches ₹0.</p>
            </div>

            {{-- Filters --}}
            <form method="GET" action="{{ route('brokers.payable-report') }}" class="flex flex-wrap items-center gap-2">
                <select name="broker_id" onchange="this.form.submit()"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none shadow-2xs font-semibold">
                    <option value="">All Brokers</option>
                    @foreach($brokers as $b)
                        <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none shadow-2xs font-semibold">
                    <option value="">All Pending (Accrued & Payable)</option>
                    <option value="Accrued" {{ request('status') === 'Accrued' ? 'selected' : '' }}>Accrued Only (Locked)</option>
                    <option value="Payable" {{ request('status') === 'Payable' ? 'selected' : '' }}>Payable Only (Unlocked)</option>
                    <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>Paid Out</option>
                </select>

                @if(request('broker_id') || request('status'))
                    <a href="{{ route('brokers.payable-report') }}" class="text-[10px] text-slate-400 hover:text-slate-700 font-bold underline">Reset Filters</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1100px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Booking & Date</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Broker & Property</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Customer Name</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Net Sale Value</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Commission Amount</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">EMI / Payment Progress</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Status</th>
                        <th class="px-5 py-3.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($commissionEntries as $entry)
                        @php
                            $deal = $entry->deal;
                            $booking = $deal->booking ?? null;
                            $broker = $deal->broker ?? null;
                            $status = $entry->status;
                            
                            $badgeClass = match($status) {
                                'Payable' => 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow-2xs font-bold',
                                'Paid' => 'bg-indigo-50 text-indigo-700 border-indigo-200 font-bold',
                                default => 'bg-amber-50 text-amber-700 border-amber-200 font-semibold'
                            };

                            $statusLabel = match($status) {
                                'Payable' => 'Payable (Unlocked)',
                                'Paid' => 'Paid Out',
                                default => 'Accrued (Locked)'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-[#a38c29] font-mono">{{ $booking->booking_number ?? 'N/A' }}</div>
                                <div class="text-[9px] text-slate-400 mt-0.5">{{ $entry->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $broker->name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $deal->project->name ?? '' }} — <span class="font-bold text-slate-700 font-mono">Unit {{ $booking->unit->door_no ?? 'N/A' }}</span></div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-800">
                                {{ $booking->customer->name ?? 'Customer' }}
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                ₹{{ number_format($deal->sale_value ?? 0, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-mono font-black text-slate-900 text-sm">₹{{ number_format($entry->amount, 2) }}</div>
                                <div class="text-[9px] text-slate-400 uppercase mt-0.5">@ {{ number_format($deal->commission_pct_override ?? 2.00, 2) }}% rate</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($booking)
                                    @if($booking->outstanding <= 0)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            100% Paid / EMI Complete
                                        </span>
                                    @else
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-[10px]">
                                                <span class="text-slate-500 font-semibold">Pending EMI</span>
                                                <span class="font-mono font-bold text-rose-600">₹{{ number_format($booking->outstanding, 2) }}</span>
                                            </div>
                                            <div class="w-28 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                @php
                                                    $pctPaid = $booking->amount > 0 ? (($booking->amount - $booking->outstanding) / $booking->amount) * 100 : 0;
                                                @endphp
                                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ min(100, max(0, $pctPaid)) }}%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 block">{{ number_format($pctPaid, 0) }}% collected</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic text-[10px]">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="badge-pill border px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase {{ $badgeClass }} inline-block">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($status === 'Payable')
                                    <form action="{{ route('brokers.payout') }}" method="POST" onsubmit="return confirm('Disburse commission of ₹{{ number_format($entry->amount, 2) }} for Booking #{{ $booking->booking_number ?? '' }}?')">
                                        @csrf
                                        <input type="hidden" name="commission_entry_id" value="{{ $entry->id }}">
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[10px] transition uppercase tracking-wide shadow-2xs">
                                            Pay ₹{{ number_format($entry->amount, 0) }}
                                        </button>
                                    </form>
                                @elseif($status === 'Accrued')
                                    <span class="text-[10px] text-slate-400 italic">Locked (Pending EMI)</span>
                                @else
                                    <span class="text-[10px] text-indigo-600 font-bold">Settled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-450 italic">No commission transactions found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commissionEntries->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $commissionEntries->links() }}
            </div>
        @endif
    </div>

</div>

</x-erp-layout>
