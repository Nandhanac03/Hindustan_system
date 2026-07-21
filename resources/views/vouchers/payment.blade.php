<x-erp-layout>
    <x-slot:title>Payment Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Payment Voucher</x-slot:headerTitle>

    <div class="max-w-3xl mx-auto py-6" x-data="paymentVoucher()">
        <form action="{{ route('vouchers.payment.store') }}" method="POST" class="bg-white text-slate-900 rounded-3xl border border-slate-200/90 shadow-xl p-8 space-y-7 transition-all">
            @csrf

            <!-- Form Header Bar -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-150">
                <div class="flex items-center gap-3.5">
                    <div class="p-3 bg-slate-900 text-white rounded-2xl shadow-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Payment Voucher</h2>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Disburse vendor, contractor, or partner payments securely</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-amber-500/10 text-amber-800 text-[10px] font-black rounded-full uppercase border border-amber-500/20 shadow-2xs">
                    Executive Suite
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

            <!-- Project Selection -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Project</label>
                <select name="project_id" x-model="form.project_id"
                        class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                    <option value="">Select Target Project...</option>
                    @foreach($projects ?? [] as $p)
                        <option value="{{ $p->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Payee Type & Payee Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Payee Type</label>
                    <select x-model="payeeType" @change="filterPayees()"
                            class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                        <option value="Contractor">Contractor</option>
                        <option value="Supplier">Supplier</option>
                        <option value="Broker">Broker</option>
                        <option value="Partner">Partner</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Payee Name</label>
                    <select name="payee_id" x-model="form.payee_id" required
                            class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                        <option value="">Select Payee Entity...</option>
                        @foreach($payees as $payee)
                            <option value="{{ $payee->id }}">{{ $payee->name }} ({{ ucfirst($payee->type) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Linked RA bill / PO (Auto-Populate Panel) -->
            <div class="bg-gradient-to-br from-amber-50/40 via-slate-50 to-amber-50/20 border border-amber-200/60 rounded-2xl p-5 space-y-2.5 shadow-2xs">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-black text-amber-900 uppercase tracking-widest flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Linked RA Bill / PO Reference
                    </label>
                    <span class="text-[9px] font-bold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-md uppercase">Auto-Populate</span>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <select x-model="sourceType" @change="sourceId = ''"
                            class="sm:w-1/3 bg-white border border-amber-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer shadow-2xs">
                        <option value="">Select Document Type...</option>
                        <option value="bill">RA Bill / Vendor Bill</option>
                        <option value="loan">Bank Loan EMI</option>
                        <option value="brokerage">Brokerage Payout</option>
                    </select>

                    <select x-model="sourceId" @change="fetchSourceData()"
                            class="sm:w-2/3 bg-white border border-amber-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer shadow-2xs">
                        <option value="">Select Linked Certified Document...</option>
                        <optgroup label="Pending Bills / RA Bills" x-show="sourceType === 'bill'">
                            @foreach($pendingBills as $bill)
                                <option value="{{ $bill->id }}">{{ $bill->bill_number }} – {{ $bill->supplier_name }} (Certified: ₹{{ number_format($bill->final_amount ?? 0, 0) }})</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Active Loans" x-show="sourceType === 'loan'">
                            @foreach($pendingLoans as $loan)
                                <option value="{{ $loan->id }}">{{ $loan->lender_name }} - {{ $loan->loan_account_no }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Brokerage Items" x-show="sourceType === 'brokerage'">
                            @foreach($pendingBrokerages as $brokerage)
                                <option value="{{ $brokerage->id }}">Brokerage #{{ $brokerage->id }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            <!-- Hidden Ledger Account Mapping -->
            <input type="hidden" name="debit_account_id" :value="form.debit_account_id || '{{ $debitAccounts->first()?->id }}'">

            <!-- Payment Mode & Amount Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Payment Mode / Source Bank</label>
                    <select name="credit_account_id" required x-model="form.credit_account_id"
                            class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white cursor-pointer focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs">
                        <option value="">Select Mode / Source Account...</option>
                        @foreach($creditAccounts as $acc)
                            <option value="{{ $acc->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Amount (₹)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-slate-400 font-black text-xs">₹</span>
                        <input type="number" name="amount" required min="0.01" step="0.01" placeholder="0.00"
                               x-model.number="form.amount"
                               class="w-full bg-white border border-slate-300/80 text-slate-900 font-black rounded-2xl pl-9 pr-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs font-mono">
                    </div>
                </div>
            </div>

            <!-- Narration -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Narration / Payment Note</label>
                <textarea name="narration" rows="2.5" placeholder="e.g. RA bill 14 payment – slab casting, Tower B, floor 9"
                          x-model="form.narration"
                          class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-semibold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs resize-none"></textarea>
            </div>

            <!-- Approval Required Panel -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white p-5 sm:p-6 rounded-2xl shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest block mb-1">Approval Hierarchy Check</span>
                    <div class="text-xs font-extrabold flex items-center gap-2">
                        <template x-if="form.amount > 200000">
                            <span class="flex items-center gap-2 text-amber-300">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                                </span>
                                Level 2 · Project manager sign-off required (above ₹2,00,000)
                            </span>
                        </template>
                        <template x-if="form.amount <= 200000">
                            <span class="flex items-center gap-2 text-emerald-400">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                                </span>
                                Level 1 · Standard Accountant Verification (under ₹2,00,000)
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Form Actions (Single Executive Action Button, No Draft Button) -->
            <div class="pt-2 flex items-center justify-end">
                <button type="submit" name="action_type" value="submit_approval"
                        class="px-8 py-3.5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 hover:from-slate-800 hover:to-slate-800 text-white text-xs font-black rounded-2xl transition-all shadow-md hover:shadow-lg uppercase tracking-wider flex items-center gap-2.5 cursor-pointer">
                    <span>Submit For Approval</span>
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        function paymentVoucher() {
            return {
                payeeType: 'Contractor',
                sourceType: '',
                sourceId: '',
                form: {
                    date: new Date().toISOString().substring(0,10),
                    project_id: '{{ isset($projects) && count($projects) > 0 ? $projects->first()->id : "" }}',
                    payee_id: '{{ $payees->first()?->id }}',
                    debit_account_id: '{{ $debitAccounts->first()?->id }}',
                    credit_account_id: '{{ $creditAccounts->first()?->id }}',
                    amount: 1850000,
                    narration: 'RA bill 14 payment – slab casting, Tower B, floor 9',
                },
                
                async fetchSourceData() {
                    if (!this.sourceType || !this.sourceId) return;
                    
                    try {
                        const response = await fetch(`/vouchers/source-details?source_type=${this.sourceType}&source_id=${this.sourceId}`);
                        if (!response.ok) throw new Error('Failed to fetch data');
                        
                        const data = await response.json();
                        
                        if (data.amount) this.form.amount = data.amount;
                        if (data.narration) this.form.narration = data.narration;
                        if (data.debit_account_id) this.form.debit_account_id = data.debit_account_id;
                        if (data.credit_account_id) this.form.credit_account_id = data.credit_account_id;
                        if (data.payee_id) this.form.payee_id = data.payee_id;
                    } catch (error) {
                        console.error("Error fetching source details:", error);
                    }
                }
            }
        }
    </script>
</x-erp-layout>
