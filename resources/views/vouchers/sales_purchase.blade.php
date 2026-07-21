<x-erp-layout>
    <x-slot:title>Sales & Purchase Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Sales & Purchase Voucher</x-slot:headerTitle>

    <div class="max-w-4xl mx-auto py-6" x-data="salesPurchaseVoucher()">
        <form action="{{ route('vouchers.sales-purchase.store') }}" method="POST" class="bg-white text-slate-900 rounded-3xl border border-slate-200/90 shadow-xl p-8 space-y-7 transition-all">
            @csrf

            <!-- Form Header Bar with Mode Switcher -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-150 gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="p-3 bg-slate-900 text-white rounded-2xl shadow-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase" x-text="form.transaction_type === 'sales' ? 'Sales Invoice Entry' : 'Purchase Bill Entry'">Sales & Purchase Voucher</h2>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Record customer sales invoices or vendor purchase bills</p>
                    </div>
                </div>

                <div class="bg-slate-100 p-1.5 rounded-2xl flex border border-slate-200 shadow-2xs">
                    <button type="button" @click="setType('sales')"
                            :class="form.transaction_type === 'sales' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer">
                        Sales
                    </button>
                    <button type="button" @click="setType('purchase')"
                            :class="form.transaction_type === 'purchase' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer">
                        Purchase
                    </button>
                </div>
                <input type="hidden" name="transaction_type" :value="form.transaction_type">
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
                    <input type="text" name="voucher_number" :value="form.voucher_number" readonly required
                           class="w-full bg-slate-100/90 border border-slate-200 text-slate-800 rounded-2xl px-4 py-3 text-xs font-mono font-extrabold focus:outline-none cursor-not-allowed shadow-2xs">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Voucher Date</label>
                    <input type="date" name="date" required x-model="form.date"
                           class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs cursor-pointer">
                </div>
            </div>

            <!-- Debit and Credit Accounts Selection -->
            <div class="bg-gradient-to-br from-amber-50/30 via-slate-50 to-amber-50/10 border border-slate-200 rounded-2xl p-5 space-y-4 shadow-2xs">
                <div class="border-b border-slate-200/80 pb-2">
                    <label class="text-[10px] font-black text-slate-700 uppercase tracking-widest block"
                           x-text="form.transaction_type === 'sales' ? 'Sales Ledger Details (Debit: Customer Receivable, Credit: Sales Income)' : 'Purchase Ledger Details (Debit: Expense/Asset, Credit: Vendor Liability)'"></label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Debit Account Dropdown -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Debit Account (Receives Charge)</label>
                        <select name="debit_account_id" required x-model="form.debit_account_id"
                                class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                            <option value="">Select Debit Ledger...</option>
                            <optgroup label="Customer Accounts">
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->ledger_account_id }}">{{ $customer->name }} (Receivable Account)</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="General Ledger Accounts">
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    
                    <!-- Credit Account Dropdown -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Credit Account (Records Revenue/Liability)</label>
                        <select name="credit_account_id" required x-model="form.credit_account_id"
                                class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                            <option value="">Select Credit Ledger...</option>
                            <optgroup label="Vendor & Liability Accounts">
                                @foreach($accounts->filter(fn($acc) => strtolower($acc->type) === 'liability') as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} (Vendor/Liability)</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="General Ledger Accounts">
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Base Amount Grid -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Base Invoice Amount (₹)</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-slate-400 font-black text-xs">₹</span>
                    <input type="number" name="amount" required min="0.01" step="0.01" placeholder="0.00"
                           x-model.number="form.amount"
                           class="w-full bg-white border border-slate-300/80 text-slate-900 font-black rounded-2xl pl-9 pr-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs font-mono">
                </div>
            </div>

            <!-- GST Calculation Mode & Breakdown Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch">
                <!-- GST Settings and Toggle -->
                <div class="bg-slate-50/80 border border-slate-200/90 rounded-2xl p-5 flex flex-col justify-between space-y-4 shadow-2xs">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Billing Tax Mode</span>
                        <label class="text-xs font-black text-slate-800 uppercase tracking-wide block">GST Rate & Inclusion</label>
                    </div>
                    
                    <!-- Toggle Switch -->
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-extrabold" :class="!form.is_inclusive ? 'text-amber-800' : 'text-slate-400'">EXCLUSIVE</span>
                        <button type="button" @click="form.is_inclusive = !form.is_inclusive"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="form.is_inclusive ? 'bg-slate-900' : 'bg-slate-300'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md transition duration-200 ease-in-out"
                                  :class="form.is_inclusive ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="text-[10px] font-extrabold" :class="form.is_inclusive ? 'text-amber-800' : 'text-slate-400'">INCLUSIVE</span>
                        <input type="hidden" name="gst_behavior" :value="form.is_inclusive ? 'inclusive' : 'exclusive'">
                    </div>

                    <!-- GST Slab -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-200">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">GST Slab Rate</label>
                        <select x-model.number="form.gst_rate"
                                class="w-full px-3 py-2 bg-white border border-slate-250 rounded-xl text-xs font-extrabold text-slate-800 focus:outline-none cursor-pointer">
                            <option value="5">5% GST (2.5% CGST + 2.5% SGST)</option>
                            <option value="12">12% GST (6.0% CGST + 6.0% SGST)</option>
                            <option value="18">18% GST (9.0% CGST + 9.0% SGST)</option>
                        </select>
                    </div>
                </div>

                <!-- Tax Summary Card (Uneditable Live Calculations) -->
                <div class="md:col-span-2 bg-gradient-to-br from-amber-50/40 via-slate-50 to-amber-50/20 border border-amber-200/70 rounded-2xl p-5 flex flex-col justify-between shadow-2xs">
                    <div>
                        <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest block mb-0.5">Live Tax Split Calculation</span>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wide">CGST & SGST Accounting Tax Summary</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4 my-3">
                        <div class="bg-white p-3 rounded-xl border border-amber-200/50 shadow-2xs">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CGST (<span x-text="(form.gst_rate/2).toFixed(1)"></span>%)</span>
                            <strong class="text-slate-900 text-sm font-mono font-black" x-text="'₹' + formatCurrency(calcCGST())"></strong>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-amber-200/50 shadow-2xs">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">SGST (<span x-text="(form.gst_rate/2).toFixed(1)"></span>%)</span>
                            <strong class="text-slate-900 text-sm font-mono font-black" x-text="'₹' + formatCurrency(calcSGST())"></strong>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 font-semibold border-t border-amber-200/60 pt-2.5">
                        <span>Base Taxable Value: <strong class="text-slate-900 font-mono font-bold" x-text="'₹' + formatCurrency(calcBase())"></strong></span>
                        <span>Total Tax Amount: <strong class="text-amber-800 font-mono font-bold" x-text="'₹' + formatCurrency(calcTotalGST())"></strong></span>
                    </div>
                </div>
            </div>

            <!-- Narration -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Narration / Context</label>
                <textarea name="narration" rows="2.5" placeholder="Explain the transaction context (e.g., Sales billing for Unit 302)..."
                          x-model="form.narration"
                          class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-semibold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs resize-none"></textarea>
            </div>

            <!-- Form Actions -->
            <div class="pt-2 flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Computed Total Invoice</span>
                    <strong class="text-xl font-mono font-black text-slate-900" x-text="'₹' + formatCurrency(calcFinalTotal())"></strong>
                </div>

                <button type="submit"
                        class="px-8 py-3.5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 hover:from-slate-800 hover:to-slate-800 text-white text-xs font-black rounded-2xl transition-all shadow-md hover:shadow-lg uppercase tracking-wider flex items-center gap-2.5 cursor-pointer">
                    <span x-text="form.transaction_type === 'sales' ? 'Post Sales Invoice' : 'Post Purchase Bill'">Post Voucher</span>
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        function salesPurchaseVoucher() {
            return {
                salesNum: '{{ $voucherNumber }}',
                purchaseNum: '{{ $purchaseVoucherNumber }}',
                form: {
                    transaction_type: 'sales',
                    voucher_number: '{{ $voucherNumber }}',
                    date: new Date().toISOString().substring(0,10),
                    debit_account_id: '',
                    credit_account_id: '',
                    amount: 0.0,
                    is_inclusive: true,
                    gst_rate: 5,
                    narration: '',
                },
                setType(type) {
                    this.form.transaction_type = type;
                    this.form.voucher_number = type === 'sales' ? this.salesNum : this.purchaseNum;
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
