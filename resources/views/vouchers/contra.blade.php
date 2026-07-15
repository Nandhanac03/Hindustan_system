<x-erp-layout>
    <x-slot:title>Contra Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Contra Voucher</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto" x-data="contraVoucher()">
        <form action="{{ route('vouchers.contra.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-soft overflow-hidden">
            @csrf

            <!-- Form Title bar -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-800 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">CORE ACCOUNTING ENGINE / CONTRA VOUCHER</h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-slate-350 uppercase">VOUCHER :</span>
                    <input type="text" name="voucher_number" value="{{ $voucherNumber }}" readonly
                           class="w-32 bg-slate-800 text-white border border-slate-700/50 rounded-lg px-2.5 py-1 text-[11px] font-mono text-center focus:outline-none select-all font-bold">
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-6 space-y-6">
                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-250 text-rose-800 text-xs font-bold rounded-2xl shadow-2xs">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Row 1: Voucher Date -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Date</label>
                    <input type="date" name="date" required x-model="form.date"
                           class="w-full md:w-1/4 px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-950 focus:outline-none transition-all cursor-pointer font-sans font-semibold">
                </div>

                <!-- Row 2: Account Selection (Debit and Credit) -->
                <div class="bg-slate-50 border border-slate-150 rounded-2xl p-5 space-y-4">
                    <p class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-200 pb-1.5">CASH / BANK ACCOUNTS DETAILS (RESTRICTED TO LIQUID ASSETS)</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Debit Account (Destination / Receiver)</label>
                            <select name="destination_account_id" id="destination_account_id" required x-model="form.destination_account_id" @change="updateNames()"
                                    class="w-full px-4 py-3 bg-white border border-slate-250 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                                <option value="">-- Select Destination cash/bank --</option>
                                @foreach($assetAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Credit Account (Source / Giver)</label>
                            <select name="credit_account_id" id="credit_account_id" required x-model="form.credit_account_id" @change="updateNames()"
                                    class="w-full px-4 py-3 bg-white border border-slate-250 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                                <option value="">-- Select Source cash/bank --</option>
                                @foreach($assetAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Amount -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest block">Transfer Amount (₹)</label>
                    <input type="number" name="amount" required min="0.01" step="0.01" placeholder="Enter transfer value..."
                           x-model.number="form.amount"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-sm text-slate-900 font-extrabold focus:outline-none transition-all">
                </div>

                <!-- Section 3: Ledger Particulars Table (Dynamic Grid) -->
                <div class="space-y-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Ledger Particulars (Double-Entry Matrix)</span>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-5 py-3.5">Ledger Head</th>
                                    <th class="px-5 py-3.5 text-right">Debit (DR)</th>
                                    <th class="px-5 py-3.5 text-right">Credit (CR)</th>
                                    <th class="px-5 py-3.5 text-center">Type</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                                <!-- Source Row -->
                                <tr>
                                    <td class="px-5 py-3.5 font-bold text-slate-800">
                                        <span x-text="creditAccountName || 'Source Account'"></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-350">-</td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-bold text-[10px] tracking-wide uppercase">CREDITS SOURCE</td>
                                </tr>
                                <!-- Destination Row -->
                                <tr class="bg-[#a38c29]/5">
                                    <td class="px-5 py-3.5 font-bold text-slate-800">
                                        <span x-text="destAccountName || 'Destination Account'"></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-350">-</td>
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-bold text-[10px] tracking-wide uppercase">DEBITS DESTINATION</td>
                                </tr>
                                <!-- TOTALS Row -->
                                <tr class="bg-slate-100/80 border-t border-slate-300 font-extrabold text-slate-900">
                                    <td class="px-5 py-3 text-[10px] uppercase tracking-wider">TOTALS</td>
                                    <td class="px-5 py-3 text-right font-mono" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                    <td class="px-5 py-3 text-right font-mono" x-text="'₹' + formatCurrency(form.amount || 0.0)"></td>
                                    <td class="px-5 py-3 text-center text-slate-400 font-bold text-[10px]">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Row 4: Narration -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Narration</label>
                    <textarea name="narration" rows="2" placeholder="Cash deposited to Karnataka Bank..."
                              class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="px-6 py-5 border-t border-slate-150 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-left">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block">Computed Total</span>
                    <strong class="text-slate-950 text-xl font-mono" x-text="'₹' + formatCurrency(form.amount || 0.0)"></strong>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 border border-slate-250 hover:bg-slate-100 text-slate-650 text-[10px] font-extrabold rounded-xl transition uppercase tracking-wider">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-extrabold rounded-xl transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wider">
                        Save
                    </button>
                </div>
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
