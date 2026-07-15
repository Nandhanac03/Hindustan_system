<x-erp-layout>
    <x-slot:title>Payment Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Payment Voucher</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto" x-data="paymentVoucher()">
        <form action="{{ route('vouchers.payment.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-soft overflow-hidden">
            @csrf

            <!-- Form Title bar -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-800 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[#a38c29] text-[9px] font-bold uppercase tracking-widest block mb-0.5">Core Accounting Engine</span>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">Payment Voucher</h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-slate-350 uppercase">Voucher :</span>
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

                <!-- Date Picker -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Date</label>
                    <input type="date" name="date" required x-model="form.date"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-950 focus:outline-none transition-all cursor-pointer font-sans font-semibold">
                </div>

                <!-- Row 1: Expense Ledger -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Expense Ledger (Debit)</label>
                    <select name="debit_account_id" required x-model="form.debit_account_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                        <option value="">-- Select Expense Ledger --</option>
                        @foreach($expenseAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 2: Contractor / Supplier / Partner Selection -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Contractor / Supplier / Partner (Payee)</label>
                    <select name="payee_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                        <option value="">-- Select Contractor/Supplier/Partner --</option>
                        @foreach($payees as $payee)
                            <option value="{{ $payee->id }}">{{ $payee->name }} ({{ ucfirst($payee->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 3: Cash / Bank (Source of Funds) -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Cash / Bank Account (Credit Source)</label>
                    <select name="credit_account_id" required x-model="form.credit_account_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                        <option value="">-- Select Cash/Bank Source --</option>
                        @foreach($creditAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 4: Amount -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest block">Amount (₹)</label>
                    <input type="number" name="amount" required min="0.01" step="0.01" placeholder="Enter amount to pay..."
                           x-model.number="form.amount"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-sm text-slate-900 font-extrabold focus:outline-none transition-all">
                </div>

                <!-- Row 5: GST and TDS Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">GST Mode / Rate</label>
                        <select name="gst_rate" x-model.number="form.gst_rate"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="0">No GST (0%)</option>
                            <option value="5">GST 5% (2.5% CGST + 2.5% SGST)</option>
                            <option value="12">GST 12% (6.0% CGST + 6.0% SGST)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">TDS Mode / Deduction</label>
                        <select name="tds_rate" x-model.number="form.tds_rate"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="0">No TDS (0%)</option>
                            <option value="1">TDS @ 1% (Individual/HUF)</option>
                            <option value="2">TDS @ 2% (Companies/Contractors)</option>
                            <option value="10">TDS @ 10% (Rent/Professional Fees)</option>
                        </select>
                    </div>
                </div>

                <!-- Section 4: Narration -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Narration</label>
                    <textarea name="narration" rows="2" placeholder="Enter transaction narration..."
                              class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="px-6 py-5 border-t border-slate-150 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-left">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block">Computed Total Paid</span>
                    <strong class="text-slate-950 text-xl font-mono" x-text="'₹' + formatCurrency(calcFinalTotal())"></strong>
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
        function paymentVoucher() {
            return {
                form: {
                    date: new Date().toISOString().substring(0,10),
                    debit_account_id: '',
                    credit_account_id: '',
                    amount: 0.0,
                    gst_rate: 0,
                    tds_rate: 0,
                },
                calcFinalTotal() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    const gst = amt * (this.form.gst_rate / 100);
                    const tds = amt * (this.form.tds_rate / 100);
                    // Final paid amount is Base + GST - TDS deduction
                    return amt + gst - tds;
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
