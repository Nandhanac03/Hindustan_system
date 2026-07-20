<x-erp-layout>
    <x-slot:title>Voucher Approvals - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Voucher Approvals</x-slot:headerTitle>

    <div class="max-w-6xl mx-auto space-y-6">
        @if (session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold rounded-2xl shadow-sm">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm font-bold rounded-2xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-soft overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-800 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[#a38c29] text-[9px] font-bold uppercase tracking-widest block mb-0.5">Manager Review</span>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">Pending High-Value Vouchers</h2>
                </div>
                <div class="px-3 py-1 bg-amber-500/20 border border-amber-500/30 rounded-lg flex items-center gap-2 text-amber-400">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                    <span class="text-[10px] font-bold">{{ count($pendingVouchers) }} Pending</span>
                </div>
            </div>

            <div class="p-0">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-bold text-[10px] text-slate-500 uppercase tracking-widest">Voucher No</th>
                            <th class="px-6 py-4 font-bold text-[10px] text-slate-500 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 font-bold text-[10px] text-slate-500 uppercase tracking-widest">Type</th>
                            <th class="px-6 py-4 font-bold text-[10px] text-slate-500 uppercase tracking-widest text-right">Total Amount</th>
                            <th class="px-6 py-4 font-bold text-[10px] text-slate-500 uppercase tracking-widest text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendingVouchers as $voucher)
                            @php
                                $totalDebit = $voucher->lines->sum('debit');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-[#a38c29]">{{ $voucher->voucher_number }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $voucher->date->format('d M, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider">{{ $voucher->type }}</span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-900 text-right">₹{{ number_format($totalDebit, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('vouchers.approve', $voucher->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider shadow-sm shadow-emerald-500/20 transition-all">
                                            Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="bg-slate-50/30">
                                <td colspan="5" class="px-6 py-3 border-t border-slate-100">
                                    <div class="text-[10px] font-semibold text-slate-500 mb-2">Narration: <span class="text-slate-800 font-normal">{{ $voucher->narration }}</span></div>
                                    <div class="grid grid-cols-2 gap-4 text-[10px]">
                                        <div>
                                            <strong class="text-slate-400 uppercase tracking-widest block mb-1">Debits</strong>
                                            @foreach($voucher->lines->where('debit', '>', 0) as $line)
                                                <div class="flex justify-between font-mono text-slate-700">
                                                    <span>{{ $line->account->name }}</span>
                                                    <span>₹{{ number_format($line->debit, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div>
                                            <strong class="text-slate-400 uppercase tracking-widest block mb-1">Credits</strong>
                                            @foreach($voucher->lines->where('credit', '>', 0) as $line)
                                                <div class="flex justify-between font-mono text-slate-700">
                                                    <span>{{ $line->account->name }}</span>
                                                    <span>₹{{ number_format($line->credit, 2) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500 italic font-medium">No pending high-value vouchers awaiting approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-erp-layout>
