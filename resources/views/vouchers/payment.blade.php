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

                <!-- Auto-Population / Source Selection -->
                <div class="p-5 bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 rounded-2xl shadow-sm mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div>
                                <h3 class="text-xs font-extrabold text-indigo-900 uppercase tracking-widest">Auto-Populate Voucher</h3>
                                <p class="text-[10px] text-indigo-600/80 font-medium mt-0.5">Link a pending bill, loan EMI, or brokerage to instantly fill out the voucher details.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <select x-model="sourceType" @change="sourceId = ''"
                                        class="flex-1 px-3 py-2 bg-white border border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-300 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                                    <option value="">-- Select Source Type --</option>
                                    <option value="bill">Vendor Bill</option>
                                    <option value="loan">Bank Loan EMI</option>
                                    <option value="brokerage">Broker Commission</option>
                                </select>

                                <select x-model="sourceId" x-show="sourceType" @change="fetchSourceData()"
                                        class="flex-1 px-3 py-2 bg-white border border-indigo-200 focus:bg-white focus:ring-2 focus:ring-indigo-300 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition-all">
                                    <option value="">-- Select Pending Item --</option>
                                    <optgroup label="Pending Bills" x-show="sourceType === 'bill'">
                                        @foreach($pendingBills as $bill)
                                            <option value="{{ $bill->id }}">{{ $bill->bill_number }} - {{ $bill->supplier_name }} (Bal: ₹{{ number_format($bill->final_amount ?? 0, 2) }})</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Active Loans" x-show="sourceType === 'loan'">
                                        @foreach($pendingLoans as $loan)
                                            <option value="{{ $loan->id }}">{{ $loan->lender_name }} - {{ $loan->loan_account_no }} (EMI: ₹{{ number_format($loan->base_emi, 2) }})</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Pending Brokerages" x-show="sourceType === 'brokerage'">
                                        @foreach($pendingBrokerages as $brokerage)
                                            <option value="{{ $brokerage->id }}">Brokerage #{{ $brokerage->id }} - {{ $brokerage->broker->name ?? 'Unknown' }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                
                                <div x-show="isFetching" class="flex items-center justify-center text-indigo-500">
                                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Picker -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Date</label>
                    <input type="date" name="date" required x-model="form.date"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-950 focus:outline-none transition-all cursor-pointer font-sans font-semibold">
                </div>

                <!-- Row 1: Particulars Ledger (Debit) -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Particulars Ledger (Debit)</label>
                    <select name="debit_account_id" required x-model="form.debit_account_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all cursor-pointer">
                        <option value="">-- Select Particulars Ledger --</option>
                        @foreach($debitAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ ucfirst($acc->type) }}]</option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 2: Contractor / Supplier / Partner Selection -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Contractor / Supplier / Partner (Payee)</label>
                    <select name="payee_id" x-model="form.payee_id"
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
                    <textarea id="ck_payment_narration" name="narration" rows="2" placeholder="Enter transaction narration..."
                              class="ck-editor-field w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition-all resize-none"></textarea>
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
                sourceType: '',
                sourceId: '',
                isFetching: false,
                form: {
                    date: new Date().toISOString().substring(0,10),
                    debit_account_id: '',
                    credit_account_id: '',
                    payee_id: '',
                    amount: 0.0,
                    gst_rate: 0,
                    tds_rate: 0,
                    narration: '',
                },
                
                async fetchSourceData() {
                    if (!this.sourceType || !this.sourceId) return;
                    
                    this.isFetching = true;
                    try {
                        const response = await fetch(`/vouchers/source-details?source_type=${this.sourceType}&source_id=${this.sourceId}`);
                        if (!response.ok) throw new Error('Failed to fetch data');
                        
                        const data = await response.json();
                        
                        if (data.amount) this.form.amount = data.amount;
                        if (data.narration) this.form.narration = data.narration;
                        if (data.debit_account_id) this.form.debit_account_id = data.debit_account_id;
                        if (data.credit_account_id) this.form.credit_account_id = data.credit_account_id;
                        if (data.payee_id) this.form.payee_id = data.payee_id;
                        
                        // Update CKEditor if it exists
                        if (window.editor) {
                            window.editor.setData(this.form.narration);
                        } else {
                            document.getElementById('ck_payment_narration').value = this.form.narration;
                        }
                    } catch (error) {
                        console.error("Error fetching source data:", error);
                        alert("Could not auto-populate data. Please enter manually.");
                    } finally {
                        this.isFetching = false;
                    }
                },
                
                calcFinalTotal() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    const gst = amt * (this.form.gst_rate / 100);
                    const tds = amt * (this.form.tds_rate / 100);
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
