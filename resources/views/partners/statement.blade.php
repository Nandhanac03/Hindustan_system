<x-erp-layout title="Partner Ledger" headerTitle="Partner Current Account Statement">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Top Navigation & Title --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('partners.index') }}" class="p-1.5 border border-slate-200 hover:bg-slate-50 rounded-lg text-slate-500 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">{{ $partner->name }} - Current Account</h1>
                <p class="text-xs text-slate-500 mt-1">Detailed ledger statement showing credits from project collections and debits from payouts made.</p>
            </div>
        </div>

        {{-- Filter by Project --}}
        <div>
            <form method="GET" action="{{ route('partners.statement', $partner->id) }}" class="flex items-center gap-2">
                <select name="project_id" onchange="this.form.submit()"
                        class="px-3 py-2 bg-white border border-slate-250 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                    <option value="">All Projects Combined</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                @if($projectId)
                    <a href="{{ route('partners.statement', $partner->id) }}" class="px-3 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl text-xs font-bold transition uppercase tracking-wide text-center">Clear</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Statement Summary Cards --}}
    @php
        $totalCredits = $ledger->sum('credit');
        $totalDebits = $ledger->sum('debit');
        $netBalance = $totalCredits - $totalDebits;
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        {{-- Total Credits Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Allocated Collections Share</span>
                <span class="text-lg font-bold text-emerald-700 block">₹{{ number_format($totalCredits, 2) }}</span>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
        </div>

        {{-- Total Payouts Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Total Paid-out (Debits)</span>
                <span class="text-lg font-bold text-rose-700 block">₹{{ number_format($totalDebits, 2) }}</span>
            </div>
            <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Running Balance Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Net Capital Balance</span>
                <span class="text-lg font-bold {{ $netBalance >= 0 ? 'text-emerald-800' : 'text-rose-800' }} block">
                    ₹{{ number_format($netBalance, 2) }}
                </span>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl text-amber-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Ledger Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Account Transaction Ledger</h2>
            <span class="text-[10px] bg-slate-100 border text-slate-500 font-mono px-2 py-0.5 rounded uppercase">Ledger Account: {{ $partner->linkedAccount->code ?? 'N/A' }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Date</th>
                        <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Type</th>
                        <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Description</th>
                        <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Credit (+)</th>
                        <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Debit (-)</th>
                        <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Running Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($ledger as $row)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-5 py-4 text-slate-500 font-mono">
                                {{ $row['date']->format('d M Y h:i A') }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $badge = $row['type'] === 'Collection Share'
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100'
                                        : 'bg-rose-50 text-rose-700 border-rose-100';
                                @endphp
                                <span class="badge-pill border px-2 py-0.5 rounded-lg text-[9px] uppercase font-bold {{ $badge }}">
                                    {{ $row['type'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-700">
                                {{ $row['description'] }}
                            </td>
                            <td class="px-5 py-4 text-right text-emerald-700 font-bold">
                                {{ $row['credit'] > 0 ? '₹' . number_format($row['credit'], 2) : '-' }}
                            </td>
                            <td class="px-5 py-4 text-right text-rose-700 font-bold">
                                {{ $row['debit'] > 0 ? '₹' . number_format($row['debit'], 2) : '-' }}
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-slate-800">
                                ₹{{ number_format($row['balance'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-450 italic">No ledger transactions found for this selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-erp-layout>
