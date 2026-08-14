<x-erp-layout title="Payment Voucher" headerTitle="Payment Voucher">

    <div class="max-w-[1000px] mx-auto space-y-6 bg-white p-8 rounded-xl shadow-sm border border-slate-200">
        
        {{-- Header & Breadcrumb --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Payment Voucher</h1>
                <p class="text-xs text-slate-500 mt-1">Home / Payments / Payment Voucher</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-semibold rounded-lg shadow-sm transition">
                    Print
                </button>
                <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Export
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                {{-- Left Column --}}
                <div class="space-y-4">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payment No.</label>
                        <input type="text" value="{{ $paymentData['payment_no'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs font-bold text-slate-900" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payment Date</label>
                        <input type="date" value="{{ $paymentData['payment_date'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payee</label>
                        <input type="text" value="{{ $paymentData['payee'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs font-bold text-slate-900" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Amount (₹)</label>
                        <input type="text" value="{{ number_format($paymentData['amount'], 2) }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs font-black text-slate-900" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Amount in Words</label>
                        <input type="text" value="Rupees {{ \NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format($paymentData['amount']) }} Only" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700 capitalize" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Payment Mode</label>
                        <input type="text" value="{{ $paymentData['payment_mode'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Remarks</label>
                        <input type="text" value="{{ $paymentData['remarks'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-4">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Pay From Account</label>
                        <input type="text" value="{{ $paymentData['bank_name'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs font-bold text-slate-900" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Account No.</label>
                        <input type="text" value="{{ substr($paymentData['account_number'], -4) }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs font-medium text-slate-900" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Reference No.</label>
                        <input type="text" value="{{ $paymentData['reference_no'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">UTR No.</label>
                        <input type="text" value="{{ $paymentData['utr_no'] }}" class="w-2/3 px-3 py-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-700" readonly>
                    </div>

                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-medium text-slate-600">Status</label>
                        <div class="w-2/3">
                            <span class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded text-[11px] font-bold tracking-wide">
                                {{ $paymentData['status'] }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Accounting Details --}}
        <div class="mt-8 bg-slate-50 rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 bg-white">
                <h3 class="font-bold text-slate-800">Accounting Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 w-12">#</th>
                            <th class="px-6 py-4">Ledger Account</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Debit (₹)</th>
                            <th class="px-6 py-4 text-right">Credit (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <tr>
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $paymentData['payee'] }}</td>
                            <td class="px-6 py-4">{{ $paymentData['remarks'] ?? 'Payment' }}</td>
                            <td class="px-6 py-4 text-right font-black text-slate-900">{{ number_format($paymentData['amount'], 2) }}</td>
                            <td class="px-6 py-4 text-right font-medium">-</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">2</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $paymentData['bank_name'] }} - {{ substr($paymentData['account_number'], -4) }}</td>
                            <td class="px-6 py-4">{{ $paymentData['payment_mode'] }} Payment</td>
                            <td class="px-6 py-4 text-right font-medium">-</td>
                            <td class="px-6 py-4 text-right font-black text-slate-900">{{ number_format($paymentData['amount'], 2) }}</td>
                        </tr>
                        <tr class="bg-slate-50 border-t-2 border-slate-200">
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-800 uppercase text-xs tracking-wider">Total</td>
                            <td class="px-6 py-4 text-right font-black text-slate-900 text-base">{{ number_format($paymentData['amount'], 2) }}</td>
                            <td class="px-6 py-4 text-right font-black text-slate-900 text-base">{{ number_format($paymentData['amount'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-erp-layout>
