<x-erp-layout>
    <x-slot:title>Sales & Purchase Voucher Entry</x-slot:title>
    <x-slot:headerTitle>Sales & Purchase Voucher</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto" x-data="salesPurchaseVoucher()">
        <form action="{{ route('vouchers.sales-purchase.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200/80 shadow-soft overflow-hidden">
            @csrf

            <!-- Form Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-800 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[#a38c29] text-[9px] font-bold uppercase tracking-widest block mb-0.5">Accounting Engine</span>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">Log Sales Invoice / Purchase Bill</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-slate-350 uppercase">Voucher Type</span>
                    <div class="bg-slate-800 rounded-xl p-0.5 flex border border-slate-700">
                        <button type="button" @click="setType('sales')"
                                :class="form.transaction_type === 'sales' ? 'bg-[#a38c29] text-white' : 'text-slate-400 hover:text-white'"
                                class="px-3.5 py-1.5 rounded-lg text-[9px] font-bold uppercase tracking-wider transition-all">
                            SALES
                        </button>
                        <button type="button" @click="setType('purchase')"
                                :class="form.transaction_type === 'purchase' ? 'bg-[#a38c29] text-white' : 'text-slate-400 hover:text-white'"
                                class="px-3.5 py-1.5 rounded-lg text-[9px] font-bold uppercase tracking-wider transition-all">
                            PURCHASE
                        </button>
                    </div>
                    <input type="hidden" name="transaction_type" :value="form.transaction_type">
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

                <!-- Row 1: Voucher Number & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Voucher Number (Auto-Generated)</label>
                        <input type="text" name="voucher_number" :value="form.voucher_number" readonly required
                               class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-500 focus:outline-none transition-all cursor-not-allowed">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block font-sans">Voucher Date</label>
                        <input type="date" name="date" required x-model="form.date"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 focus:outline-none transition-all cursor-pointer font-sans">
                    </div>
                </div>

                <!-- Row 2: Debit and Credit Accounts -->
                <div class="bg-slate-50 border border-slate-150 rounded-2xl p-5 space-y-4">
                    <p class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-200 pb-1.5" x-text="form.transaction_type === 'sales' ? 'Sales Ledger Details (Debit: Customer, Credit: Sales Income)' : 'Purchase Ledger Details (Debit: Cost/Asset, Credit: Vendor)'"></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Debit Account Dropdown -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Debit Account (Receives Charge)</label>
                            <select name="debit_account_id" required x-model="form.debit_account_id"
                                    class="w-full px-4 py-3 bg-white border border-slate-250 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                                <option value="">-- Select Debit Ledger --</option>
                                <template x-if="form.transaction_type === 'sales'">
                                    <optgroup label="Customer Ledger Accounts">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->ledger_account_id }}">{{ $customer->name }} (Receivable Account)</option>
                                        @endforeach
                                    </optgroup>
                                </template>
                                <optgroup label="General Ledger Accounts">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        
                        <!-- Credit Account Dropdown -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Credit Account (Records Revenue/Liability)</label>
                            <select name="credit_account_id" required x-model="form.credit_account_id"
                                    class="w-full px-4 py-3 bg-white border border-slate-250 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-800 font-semibold cursor-pointer focus:outline-none transition-all">
                                <option value="">-- Select Credit Ledger --</option>
                                <template x-if="form.transaction_type === 'purchase'">
                                    <optgroup label="Vendor Accounts">
                                        @foreach($accounts->filter(fn($acc) => strtolower($acc->type) === 'liability') as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }} (Vendor)</option>
                                        @endforeach
                                    </optgroup>
                                </template>
                                <optgroup label="General Ledger Accounts">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Amount -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Transaction Base Amount (₹)</label>
                    <input type="number" name="amount" required min="0.01" step="0.01" placeholder="Enter base amount..."
                           x-model.number="form.amount"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 font-bold focus:outline-none transition-all">
                </div>

                <!-- Row 4: GST / Tax Breakdown Array -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-stretch">
                    <!-- GST Settings and Toggle -->
                    <div class="bg-slate-50/50 border border-slate-150 rounded-2xl p-4 flex flex-col justify-between space-y-3">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Billing Behavior</span>
                            <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wide block">GST Billing Mode</label>
                        </div>
                        
                        <!-- Toggle Switch -->
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold" :class="!form.is_inclusive ? 'text-[#a38c29]' : 'text-slate-400'">EXCLUSIVE</span>
                            <button type="button" @click="form.is_inclusive = !form.is_inclusive"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary/30"
                                    :class="form.is_inclusive ? 'bg-[#a38c29]' : 'bg-slate-300'">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                                      :class="form.is_inclusive ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                            <span class="text-[10px] font-bold" :class="form.is_inclusive ? 'text-[#a38c29]' : 'text-slate-400'">INCLUSIVE</span>
                            <input type="hidden" name="gst_behavior" :value="form.is_inclusive ? 'inclusive' : 'exclusive'">
                        </div>

                        <!-- GST Slab -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-150">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">GST Slab Rate</label>
                            <select x-model.number="form.gst_rate"
                                    class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700 focus:outline-none cursor-pointer">
                                <option value="5">5% GST (2.5% CGST + 2.5% SGST)</option>
                                <option value="12">12% GST (6.0% CGST + 6.0% SGST)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tax Summary Card (Uneditable) -->
                    <div class="md:col-span-2 bg-[#a38c29]/5 border border-[#a38c29]/20 rounded-2xl p-4 flex flex-col justify-between">
                        <div>
                            <span class="text-[9px] font-bold text-[#a38c29] uppercase tracking-wider block mb-1">Tax Summary (Uneditable)</span>
                            <h3 class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">CGST & SGST Split Summary</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-4 my-2 text-xs font-semibold text-slate-600">
                            <div class="bg-white/80 p-2.5 rounded-xl border border-[#a38c29]/10">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">CGST (<span x-text="(form.gst_rate/2).toFixed(1)"></span>%)</span>
                                <strong class="text-slate-800 text-[11px] font-mono" x-text="'₹' + formatCurrency(calcCGST())"></strong>
                            </div>
                            <div class="bg-white/80 p-2.5 rounded-xl border border-[#a38c29]/10">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block">SGST (<span x-text="(form.gst_rate/2).toFixed(1)"></span>%)</span>
                                <strong class="text-slate-800 text-[11px] font-mono" x-text="'₹' + formatCurrency(calcSGST())"></strong>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-slate-400 border-t border-[#a38c29]/15 pt-2">
                            <span>Base Value: <strong class="text-slate-700 font-mono" x-text="'₹' + formatCurrency(calcBase())"></strong></span>
                            <span>Total GST: <strong class="text-slate-700 font-mono" x-text="'₹' + formatCurrency(calcTotalGST())"></strong></span>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Narration -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Narration / Context</label>
                    <textarea name="narration" rows="3" placeholder="Explain the transaction context (e.g., Sales billing for Unit 302)..."
                              class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="px-6 py-4 border-t border-slate-150 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-left">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block">Computed Total</span>
                    <strong class="text-slate-900 text-lg font-mono" x-text="'₹' + formatCurrency(calcFinalTotal())"></strong>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-600 text-[10px] font-bold rounded-xl transition uppercase tracking-wide">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold rounded-xl transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                        Post Voucher
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function salesPurchaseVoucher() {
            return {
                form: {
                    transaction_type: 'sales',
                    voucher_number: '{{ $voucherNumber }}',
                    date: new Date().toISOString().substring(0,10),
                    debit_account_id: '',
                    credit_account_id: '',
                    amount: 0.0,
                    is_inclusive: true,
                    gst_rate: 5,
                },
                setType(type) {
                    this.form.transaction_type = type;
                    // Dynamically swap prefixes for sales vs purchase if wanted, or just keep original
                    const prefix = type === 'sales' ? 'SL' : 'PR';
                    const numberOnly = this.form.voucher_number.substring(2);
                    this.form.voucher_number = prefix + numberOnly;
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
