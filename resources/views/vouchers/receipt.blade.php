<x-erp-layout>
    <x-slot:title>Payment Receipt Intake & Real-Time Allocation - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Payment Receipt Intake & Real-Time Allocation</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="receiptVoucher()" x-init="fetchUnits()">
        <form action="{{ route('vouchers.receipt.store') }}" method="POST">
            @csrf

            <!-- Hidden input fields for voucher metadata -->
            <input type="hidden" name="split_active" :value="splitActive ? 1 : 0">
            <input type="hidden" name="date" :value="form.date">
            <input type="hidden" name="gst_behavior" value="inclusive">
            <input type="hidden" name="gst_rate" value="0">

            <!-- Page Title Header and Split Toggle -->
            <div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Payment Receipt Intake & Real-Time Allocation</h2>
                </div>
                <div class="flex items-center gap-4">
                    <button type="button" @click="splitActive = !splitActive"
                            :class="splitActive ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                        <span x-text="splitActive ? 'Split: Active' : '+ Split'"></span>
                    </button>
                    <div class="flex items-center gap-2 border-l border-slate-200 pl-4">
                        <span class="text-[10px] font-bold text-slate-450 uppercase">Voucher :</span>
                        <input type="text" name="voucher_number" value="{{ $voucherNumber }}" readonly
                               class="w-32 bg-slate-150 text-slate-800 border border-slate-200 rounded-lg px-2.5 py-1 text-[11px] font-mono text-center font-bold">
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Two-Pane Layout Grid -->
            <div class="grid grid-cols-1 gap-6 items-start transition-all duration-300" :class="splitActive ? 'lg:grid-cols-2' : 'max-w-4xl mx-auto'">
                
                <!-- Left Pane: Step 1: Inbound Receipt Details -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Step 1: Inbound Receipt Details</h3>
                        <span class="text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 2h6m-6 2h6M3 5h18M3 19h18M3 5v14M21 5v14"/></svg>
                        </span>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Date -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Date</label>
                            <input type="date" name="date" required x-model="form.date"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all">
                        </div>

                        <!-- Select Project -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Select Project</label>
                            <select name="project_id" required x-model="form.project_id" @change="fetchUnits()"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                <option value="">-- Select Project --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ $projects->first()?->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Unit -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Select Unit</label>
                            <select name="unit_id" x-model="form.unit_id"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                <option value="">-- Select Unit --</option>
                                <template x-for="unit in units" :key="unit.id">
                                    <option :value="unit.id" x-text="unit.door_no + ' (' + unit.status.toUpperCase() + ')'" :selected="form.unit_id == unit.id"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Select Customer -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Select Customer</label>
                            <select name="credit_account_id" id="credit_account_id" required x-model="form.credit_account_id" @change="updateNames()"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->ledger_account_id }}" {{ $customers->first()?->ledger_account_id == $customer->ledger_account_id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount Received (Large Bold Text) -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                            <label class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block">Amount Received</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-lg font-bold text-slate-800">₹</span>
                                <input type="number" name="amount" required min="0" step="0.01"
                                       x-model.number="form.amount"
                                       class="w-full pl-9 pr-4 py-3 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-xl text-xl font-extrabold text-slate-850 focus:outline-none transition">
                            </div>
                        </div>

                        <!-- Payment Mode -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Payment Mode</label>
                            <select name="destination_account_id" id="destination_account_id" required x-model="form.destination_account_id" @change="updateNames()"
                                    class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                <option value="">-- Select Cash/Bank Account --</option>
                                @foreach($assetAccounts as $acc)
                                    <option value="{{ $acc->id }}" {{ $assetAccounts->first()?->id == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reference/Narration -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Reference/Narration</label>
                            <textarea name="narration" rows="2" placeholder="Reference details or narration..."
                                      class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-xs text-slate-800 px-4 py-3 focus:outline-none transition resize-none">Advance payment for flat</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Pane: Step 2: Immediate Real-Time Allocation (Tracing Customer Money) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-fit" x-show="splitActive" x-transition>
                    <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Step 2: Immediate Real-Time Allocation (Tracing Customer Money)</h3>
                        <span class="text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                        </span>
                    </div>

                    <div class="p-6 space-y-6">
                        <table class="w-full text-left border-collapse border border-slate-100 rounded-xl overflow-hidden">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3.5 border-r border-slate-150">Partner</th>
                                    <th class="px-4 py-3.5 border-r border-slate-150">Breakdown</th>
                                    <th class="px-4 py-3.5 text-right font-mono" x-text="'₹' + formatCurrency(form.amount)"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-150 text-xs text-slate-800">
                                <tr>
                                    <td class="px-4 py-4 font-bold text-slate-700 border-r border-slate-150 w-1/3">Allocate to Partner Shares</td>
                                    <td class="px-4 py-4 border-r border-slate-150">
                                        <div class="space-y-1">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Select Partner</span>
                                            <select name="partner_id" x-model="form.partner_id"
                                                    class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none">
                                                <option value="">Select Partner</option>
                                                @foreach($partners as $partner)
                                                    <option value="{{ $partner->id }}" {{ $partners->first()?->id == $partner->id ? 'selected' : '' }}>{{ $partner->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="relative flex items-center justify-end">
                                            <span class="absolute left-2 font-bold text-slate-500">₹</span>
                                            <input type="number" name="partner_amount" x-model.number="form.partner_amount" step="0.01" min="0"
                                                   class="w-32 px-2 py-2 pl-6 text-right bg-slate-50 border border-slate-200 rounded-lg font-mono font-bold text-slate-850">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 font-bold text-slate-700 border-r border-slate-150 w-1/3">Clear Pending Supplier Bills</td>
                                    <td class="px-4 py-4 border-r border-slate-150">
                                        <div class="space-y-1">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Select Invoice</span>
                                            <select name="bill_id" x-model="form.bill_id"
                                                    class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none">
                                                <option value="">Select Invoice</option>
                                                @foreach($pendingBills as $bill)
                                                    <option value="{{ $bill->id }}" {{ $pendingBills->first()?->id == $bill->id ? 'selected' : '' }}>{{ $bill->bill_number }} (Bal: ₹{{ number_format($bill->final_amount, 2) }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="relative flex items-center justify-end">
                                            <span class="absolute left-2 font-bold text-slate-500">₹</span>
                                            <input type="number" name="bill_amount" x-model.number="form.bill_amount" step="0.01" min="0"
                                                   class="w-32 px-2 py-2 pl-6 text-right bg-slate-50 border border-slate-200 rounded-lg font-mono font-bold text-slate-850">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 font-bold text-slate-700 border-r border-slate-150 w-1/3">Customer Refund</td>
                                    <td class="px-4 py-4 border-r border-slate-150">
                                        <div class="space-y-1">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Select Cancelled Customer</span>
                                            <select name="refund_sale_id" x-model="form.refund_sale_id"
                                                    class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none">
                                                <option value="">Select Cancelled Customer</option>
                                                @foreach($cancelledSales as $cs)
                                                    <option value="{{ $cs->id }}" {{ $cancelledSales->first()?->id == $cs->id ? 'selected' : '' }}>{{ $cs->customer->name }} - {{ $cs->sale_number }} (Bal: ₹{{ number_format($cs->remaining_refund, 2) }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="relative flex items-center justify-end">
                                            <span class="absolute left-2 font-bold text-slate-500">₹</span>
                                            <input type="number" name="refund_amount" x-model.number="form.refund_amount" step="0.01" min="0"
                                                   class="w-32 px-2 py-2 pl-6 text-right bg-slate-50 border border-slate-200 rounded-lg font-mono font-bold text-slate-850">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="p-4 rounded-xl flex items-center justify-between text-xs font-bold transition-all border"
                             :class="remainingBalance() === 0 && form.amount > 0 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-250'">
                            <div class="flex items-center gap-6">
                                <div>Total Allocated: <span class="font-mono font-extrabold text-slate-900" x-text="'₹' + formatCurrency(totalAllocated())"></span></div>
                                <div>Remaining Balance: <span class="font-mono font-extrabold text-slate-900" x-text="'₹' + formatCurrency(remainingBalance())"></span></div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <template x-if="remainingBalance() === 0 && form.amount > 0">
                                    <div class="flex items-center gap-1 text-emerald-600 font-bold uppercase tracking-wider text-[9px]">
                                        <span>Balanced</span>
                                        <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                </template>
                                <template x-if="remainingBalance() !== 0 || form.amount <= 0">
                                    <div class="flex items-center gap-1 text-amber-600 font-bold uppercase tracking-wider text-[9px] animate-pulse">
                                        <span>Awaiting Allocation</span>
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    :disabled="remainingBalance() !== 0 || form.amount <= 0"
                                    :class="remainingBalance() !== 0 || form.amount <= 0 ? 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-md'"
                                    class="w-full py-3.5 text-center text-xs font-extrabold rounded-xl transition uppercase tracking-wider">
                                Process Receipt & Complete Split
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Standard Ledger Particulars (Visible only when splitActive is false) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6 lg:col-span-2" x-show="!splitActive" x-transition>
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Ledger Particulars (Double-Entry Matrix)</span>
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
                                    <tr>
                                        <td class="px-5 py-3.5 font-bold text-slate-800"><span x-text="customerName || 'Customer Ledger'"></span></td>
                                        <td class="px-5 py-3.5 text-right font-mono text-slate-300">-</td>
                                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-5 py-3.5 text-center text-slate-455 font-bold text-[10px] tracking-wide uppercase">CREDITS PARTY</td>
                                    </tr>
                                    <tr class="bg-primary/5">
                                        <td class="px-5 py-3.5 font-bold text-slate-800"><span x-text="destAccountName || 'Cash / Bank Ledger'"></span></td>
                                        <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900" x-text="'₹' + formatCurrency(form.amount)"></td>
                                        <td class="px-5 py-3.5 text-right font-mono text-slate-300">-</td>
                                        <td class="px-5 py-3.5 text-center text-slate-455 font-bold text-[10px] tracking-wide uppercase">DEBITS BANK/CASH</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="submit" class="px-6 py-2.5 text-xs font-bold rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition uppercase tracking-wider">
                            Save Receipt Voucher
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function receiptVoucher() {
            return {
                splitActive: true,
                units: [],
                form: {
                    project_id: '{{ $projects->first()?->id ?? "" }}',
                    unit_id: '',
                    date: '{{ date('Y-m-d') }}',
                    destination_account_id: '{{ $assetAccounts->first()?->id ?? "" }}',
                    credit_account_id: '{{ $customers->first()?->ledger_account_id ?? "" }}',
                    amount: 10000.00,
                    partner_id: '{{ $partners->first()?->id ?? "" }}',
                    partner_amount: 4000.00,
                    bill_id: '{{ $pendingBills->first()?->id ?? "" }}',
                    bill_amount: 4000.00,
                    refund_sale_id: '{{ $cancelledSales->first()?->id ?? "" }}',
                    refund_amount: 2000.00,
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
                fetchUnits() {
                    if (!this.form.project_id) {
                        this.units = [];
                        this.form.unit_id = '';
                        return;
                    }
                    fetch(`/vouchers/project/${this.form.project_id}/units`)
                        .then(res => res.json())
                        .then(data => {
                            this.units = data;
                            // Preselect unit G 1 or preselect first unit if matches
                            if (this.units.length > 0) {
                                // Attempt to match "G 1" or fallback to first unit
                                const match = this.units.find(u => u.door_no.trim() === 'G 1');
                                this.form.unit_id = match ? match.id : this.units[0].id;
                            } else {
                                this.form.unit_id = '';
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching units:', err);
                            this.units = [];
                        });
                },
                totalAllocated() {
                    const p = parseFloat(this.form.partner_amount) || 0.0;
                    const b = parseFloat(this.form.bill_amount) || 0.0;
                    const r = parseFloat(this.form.refund_amount) || 0.0;
                    return parseFloat((p + b + r).toFixed(2));
                },
                remainingBalance() {
                    const amt = parseFloat(this.form.amount) || 0.0;
                    return parseFloat((amt - this.totalAllocated()).toFixed(2));
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
