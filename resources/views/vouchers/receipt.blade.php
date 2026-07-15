<x-erp-layout>
    <x-slot:title>Receipt Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Receipt Voucher</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto" x-data="receiptVoucher()">
        <form action="{{ route('vouchers.receipt.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-soft overflow-hidden">
            @csrf

            <!-- Form Title bar -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-800 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">CORE ACCOUNTING ENGINE / RECEIPT VOUCHER</h2>
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

                <!-- Section 1: Header Row Meta Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Date</label>
                        <input type="date" name="date" required x-model="form.date"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-950 focus:outline-none transition-all cursor-pointer font-sans font-semibold">
                    </div>

                    <!-- Project Dropdown -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Project</label>
                        <select name="project_id" required x-model="form.project_id"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="">-- Select Project --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Customer Dropdown -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Customer</label>
                        <select name="credit_account_id" id="credit_account_id" required x-model="form.credit_account_id" @change="updateNames()"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="">-- Select Customer --</option>
                         
                            @foreach($customers as $customer)
                                <option value="{{ $customer->ledger_account_id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Mode -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Payment Mode</label>
                        <select name="payment_mode" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cash/Bank Account Selection -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Cash / Bank Account</label>
                        <select name="destination_account_id" id="destination_account_id" required x-model="form.destination_account_id" @change="updateNames()"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="">-- Select Cash/Bank Account --</option>
                            @foreach($assetAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Receipt Type Selection -->
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Receipt Type</label>
                        <select name="receipt_type" required
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                            <option value="Advance">Advance Payment</option>
                            <option value="Progress Payment">Progress Billing Payment</option>
                            <option value="Final Settlement">Final Settlement</option>
                        </select>
                    </div>
                </div>

                <!-- Base Value Block: Base Billing Amount (₹) -->
                <div class="space-y-1.5 pt-2 border-t border-slate-100">
                    <label class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest block">Base Billing Amount (₹)</label>
                    <input type="number" name="amount" required min="0" step="0.01" placeholder="0"
                           x-model.number="form.amount"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-sm text-slate-900 font-extrabold focus:outline-none transition-all">
                </div>

                <!-- Section 2: Ledger Particulars Table (Dynamic Grid) -->
                <div class="space-y-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Ledger Particulars (Double-Entry Matrix)</span>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-5 py-3.5">Ledger Head</th>
                                    <th class="px-5 py-3.5 text-right">Debit (DR)</th>
                                    <th class="px-5 py-3.5 text-right">Credit (CR)</th>
                                    <th class="px-5 py-3.5 text-center">Tax / Splitting</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs text-slate-800">
                                <!-- Customer Receivable Row -->
                                <tr>
                                    <td class="px-5 py-3.5 font-bold text-slate-800">
                                        <span x-text="customerName || 'Customer Ledger'"></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-350">-</td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(calcBase())"></td>
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-bold text-[10px] tracking-wide uppercase">CREDITS PARTY</td>
                                </tr>
                                <!-- CGST Split Row -->
                                <tr class="bg-slate-50/40">
                                    <td class="px-5 py-3.5 font-bold text-slate-600">Output CGST Account</td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-350">-</td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(calcCGST())"></td>
                                    <td class="px-5 py-3.5 text-center text-[#a38c29] font-bold text-[10px]" x-text="'+' + (form.gst_rate / 2).toFixed(1) + '% Split'"></td>
                                </tr>
                                <!-- SGST Split Row -->
                                <tr class="bg-slate-50/40">
                                    <td class="px-5 py-3.5 font-bold text-slate-600">Output SGST Account</td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-350">-</td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(calcSGST())"></td>
                                    <td class="px-5 py-3.5 text-center text-[#a38c29] font-bold text-[10px]" x-text="'+' + (form.gst_rate / 2).toFixed(1) + '% Split'"></td>
                                </tr>
                                <!-- Destination Account Debit Row -->
                                <tr class="bg-[#a38c29]/5">
                                    <td class="px-5 py-3.5 font-bold text-slate-800">
                                        <span x-text="destAccountName || 'Cash / Bank Ledger'"></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(calcFinalTotal())"></td>
                                    <td class="px-5 py-3.5 text-right font-mono text-slate-350">-</td>
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-bold text-[10px] tracking-wide uppercase">DEBITS BANK/CASH</td>
                                </tr>
                                <!-- TOTALS Row -->
                                <tr class="bg-slate-100/80 border-t border-slate-300 font-extrabold text-slate-900">
                                    <td class="px-5 py-3 text-[10px] uppercase tracking-wider">TOTALS</td>
                                    <td class="px-5 py-3 text-right font-mono" x-text="'₹' + formatCurrency(calcFinalTotal())"></td>
                                    <td class="px-5 py-3 text-right font-mono" x-text="'₹' + formatCurrency(calcBase() + calcCGST() + calcSGST())"></td>
                                    <td class="px-5 py-3 text-center text-slate-400 font-bold text-[10px]">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 3: GST Inclusive vs Exclusive toggle and Slab Selector -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center bg-slate-50 border border-slate-150 rounded-2xl p-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">GST Mode</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="radio" name="gst_mode_radio" value="inclusive" x-model="form.gst_mode" @change="form.is_inclusive = true"
                                       class="text-[#a38c29] focus:ring-[#a38c29]/30 h-4.5 w-4.5 border-slate-300">
                                GST Inclusive
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="radio" name="gst_mode_radio" value="exclusive" x-model="form.gst_mode" @change="form.is_inclusive = false"
                                       class="text-[#a38c29] focus:ring-[#a38c29]/30 h-4.5 w-4.5 border-slate-300">
                                GST Exclusive
                            </label>
                            <input type="hidden" name="gst_behavior" :value="form.is_inclusive ? 'inclusive' : 'exclusive'">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">GST Slab Rate</label>
                        <select x-model.number="form.gst_rate"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-750 focus:outline-none cursor-pointer">
                            <option value="5">5% GST (2.5% CGST + 2.5% SGST)</option>
                            <option value="12">12% GST (6.0% CGST + 6.0% SGST)</option>
                        </select>
                    </div>
                </div>

                <!-- Section 4: Narration -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Narration</label>
                    <textarea id="ck_receipt_narration" name="narration" rows="2" placeholder="Advance payment received for Flat A-302..."
                              class="ck-editor-field w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="px-6 py-5 border-t border-slate-150 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-left">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block">Computed Total Transaction Value</span>
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
        function receiptVoucher() {
            return {
                form: {
                    project_id: '{{ $projects->first()?->id ?? "" }}',
                    date: new Date().toISOString().substring(0,10),
                    destination_account_id: '',
                    credit_account_id: '',
                    amount: 0.0,
                    gst_mode: 'inclusive',
                    is_inclusive: true,
                    gst_rate: 5,
                },
                customerName: '',
                destAccountName: '',
                updateNames() {
                    this.$nextTick(() => {
                        const custEl = document.getElementById('credit_account_id');
                        this.customerName = custEl ? custEl.options[custEl.selectedIndex]?.text : '';
                        
                        const destEl = document.getElementById('destination_account_id');
                        this.destAccountName = destEl ? destEl.options[destEl.selectedIndex]?.text : '';
                    });
                },
                calcBase() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    if (this.form.is_inclusive) {
                        const baseRaw = amt / (1 + (this.form.gst_rate / 100));
                        const cgst = parseFloat(((amt - baseRaw) / 2).toFixed(2));
                        const sgst = parseFloat(((amt - baseRaw) / 2).toFixed(2));
                        return amt - (cgst + sgst);
                    }
                    return amt;
                },
                calcTotalGST() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    if (this.form.is_inclusive) {
                        return amt - this.calcBase();
                    }
                    return amt * (this.form.gst_rate / 100);
                },
                calcCGST() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    if (this.form.is_inclusive) {
                        const baseRaw = amt / (1 + (this.form.gst_rate / 100));
                        return parseFloat(((amt - baseRaw) / 2).toFixed(2));
                    }
                    return parseFloat((this.calcTotalGST() / 2).toFixed(2));
                },
                calcSGST() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    if (this.form.is_inclusive) {
                        const baseRaw = amt / (1 + (this.form.gst_rate / 100));
                        return parseFloat(((amt - baseRaw) / 2).toFixed(2));
                    }
                    return parseFloat((this.calcTotalGST() / 2).toFixed(2));
                },
                calcFinalTotal() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    if (this.form.is_inclusive) {
                        return amt;
                    }
                    return amt + this.calcTotalGST();
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
