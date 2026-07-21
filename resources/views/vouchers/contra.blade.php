<x-erp-layout>
    <x-slot:title>Contra Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Contra Voucher</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto py-6" x-data="contraVoucher()">
        <form action="{{ route('vouchers.contra.store') }}" method="POST" class="bg-white text-slate-900 rounded-3xl border border-slate-200/90 shadow-xl p-8 space-y-7 transition-all">
            @csrf

            <!-- Form Header Bar -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-150">
                <div class="flex items-center gap-3.5">
                    <div class="p-3 bg-slate-900 text-white rounded-2xl shadow-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Contra Voucher</h2>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Record cash deposits, withdrawals, and bank-to-bank transfers</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-amber-500/10 text-amber-800 text-[10px] font-black rounded-full uppercase border border-amber-500/20 shadow-2xs">
                    Internal Transfer
                </span>
            </div>

            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Voucher No. & Date Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Voucher No.</label>
                    <input type="text" name="voucher_number" value="{{ $voucherNumber }}" readonly
                           class="w-full bg-slate-100/90 border border-slate-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-mono font-extrabold focus:outline-none cursor-not-allowed shadow-2xs">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Date</label>
                    <input type="date" name="date" required x-model="form.date"
                           class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs cursor-pointer">
                </div>
            </div>

            <!-- Account Selection (Debit and Credit) -->
            <div class="bg-gradient-to-br from-amber-50/30 via-slate-50 to-amber-50/10 border border-slate-200 rounded-2xl p-5 space-y-4 shadow-2xs">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                    <label class="text-[10px] font-black text-slate-700 uppercase tracking-widest flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Liquid Cash / Bank Accounts Selection
                    </label>
                    <span class="text-[9px] font-bold text-amber-700 bg-amber-100/80 px-2.5 py-0.5 rounded-md uppercase">Asset Ledgers</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Debit Account (Destination / Receiver)</label>
                        <select name="destination_account_id" id="destination_account_id" required x-model="form.destination_account_id" @change="updateNames()"
                                class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                            <option value="">Select Destination Account...</option>
                            @foreach($assetAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Credit Account (Source / Giver)</label>
                        <select name="credit_account_id" id="credit_account_id" required x-model="form.credit_account_id" @change="updateNames()"
                                class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                            <option value="">Select Source Account...</option>
                            @foreach($assetAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Amount -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Transfer Amount (₹)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-slate-400 font-black text-xs">₹</span>
                    <input type="number" name="amount" required min="0.01" step="0.01" placeholder="0.00"
                           x-model.number="form.amount"
                           class="w-full bg-white border border-slate-300/80 text-slate-900 font-black rounded-2xl pl-9 pr-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs font-mono">
                </div>
            </div>

            <!-- Ledger Particulars Table (Double-Entry Matrix) -->
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Double-Entry Particulars Preview</span>
                <div class="border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100/80 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                <th class="px-5 py-3.5">Ledger Head</th>
                                <th class="px-5 py-3.5 text-right">Debit (DR)</th>
                                <th class="px-5 py-3.5 text-right">Credit (CR)</th>
                                <th class="px-5 py-3.5 text-center">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-800 bg-white">
                            <!-- Source Row -->
                            <tr>
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    <span x-text="creditAccountName || 'Source Account'"></span>
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono text-slate-300">-</td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                <td class="px-5 py-3.5 text-center font-bold text-[10px] tracking-wide uppercase text-amber-700 bg-amber-50/50">Credit Source</td>
                            </tr>
                            <!-- Destination Row -->
                            <tr class="bg-emerald-50/30">
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    <span x-text="destAccountName || 'Destination Account'"></span>
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                <td class="px-5 py-3.5 text-right font-mono text-slate-300">-</td>
                                <td class="px-5 py-3.5 text-center font-bold text-[10px] tracking-wide uppercase text-emerald-700 bg-emerald-50/50">Debit Destination</td>
                            </tr>
                            <!-- TOTALS Row -->
                            <tr class="bg-slate-900 text-white font-black">
                                <td class="px-5 py-3 text-[10px] uppercase tracking-wider">TOTALS MATCH</td>
                                <td class="px-5 py-3 text-right font-mono" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                <td class="px-5 py-3 text-right font-mono" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                <td class="px-5 py-3 text-center text-amber-400 font-bold text-[10px] uppercase">BALANCED</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Narration -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Narration / Payment Note</label>
                <textarea name="narration" rows="2.5" placeholder="e.g. Cash deposited to Bank of India..."
                          class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-semibold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs resize-none"></textarea>
            </div>

            <!-- Form Actions -->
            <div class="pt-2 flex items-center justify-end">
                <button type="submit"
                        class="px-8 py-3.5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 hover:from-slate-800 hover:to-slate-800 text-white text-xs font-black rounded-2xl transition-all shadow-md hover:shadow-lg uppercase tracking-wider flex items-center gap-2.5 cursor-pointer">
                    <span>Save Contra Voucher</span>
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        function contraVoucher() {
            return {
                form: {
                    date: new Date().toISOString().substring(0,10),
                    destination_account_id: '',
                    credit_account_id: '',
                    amount: 0.0,
                },
                creditAccountName: '',
                destAccountName: '',
                updateNames() {
                    this.$nextTick(() => {
                        const creditEl = document.getElementById('credit_account_id');
                        this.creditAccountName = creditEl ? creditEl.options[creditEl.selectedIndex]?.text : '';
                        
                        const destEl = document.getElementById('destination_account_id');
                        this.destAccountName = destEl ? destEl.options[destEl.selectedIndex]?.text : '';
                    });
                },
                formatCurrency(val) {
                    return Number(val.toFixed(2)).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }
    </script>
</x-erp-layout>
