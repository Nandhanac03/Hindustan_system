<x-erp-layout title="Receipts Entry" headerTitle="Collection Receipts Register">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="receiptsApp()">

    {{-- Summary KPIs --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Collected</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">
                    ₹{{ number_format($recentReceipts->sum('amount'), 0) }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Cheque Collections</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">
                    ₹{{ number_format($recentReceipts->where('payment_mode','Cheque')->sum('amount'), 0) }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Online / UPI</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">
                    ₹{{ number_format($recentReceipts->where('payment_mode','Online')->sum('amount'), 0) }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Cash Collected</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">
                    ₹{{ number_format($recentReceipts->where('payment_mode','Cash')->sum('amount'), 0) }}
                </span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Main Workspace --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Add Receipt Form (links to Sale) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5 h-fit">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Receipt Collection</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Collections must be linked to an active Sale. Select the Sale first.</p>
            </div>

            <form @submit.prevent="submitReceipt()" class="space-y-4">

                {{-- Select Active Sale --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Active Sale *</label>
                    <select x-model="form.sale_id" required @change="updateSaleDetail()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">-- Select Sale --</option>
                        @foreach($sales as $sale)
                        <option value="{{ $sale->id }}"
                                data-remaining="{{ $sale->remaining_balance }}"
                                data-total="{{ $sale->total_amount }}"
                                data-unit="{{ $sale->unit?->door_no ?? '—' }}"
                                data-project="{{ $sale->project?->name ?? '—' }}"
                                data-customer="{{ $sale->customer?->name ?? '—' }}"
                                data-salenum="{{ $sale->sale_number }}">
                            {{ $sale->customer?->name ?? '—' }} — {{ $sale->sale_number }} ({{ $sale->project?->name ?? '—' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sale Info Card --}}
                <div x-show="selectedSale" class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-[11px] font-semibold text-slate-600" x-transition>
                    <div class="flex justify-between">
                        <span>Project / Unit:</span>
                        <strong class="text-slate-900" x-text="(selectedSale?.project ?? '') + ' / Unit ' + (selectedSale?.unit ?? '')"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Sale Total:</span>
                        <strong class="text-slate-700 font-mono" x-text="'₹' + Number(selectedSale?.total ?? 0).toLocaleString('en-IN')"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Remaining Balance:</span>
                        <strong class="text-rose-600 font-mono" x-text="'₹' + Number(selectedSale?.remaining ?? 0).toLocaleString('en-IN')"></strong>
                    </div>
                </div>

                {{-- Amount & Date --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount (₹) *</label>
                        <input type="number" x-model.number="form.amount" required min="0.01" step="0.01"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs font-bold focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Receipt Date</label>
                        <input type="date" x-model="form.receipt_date"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                </div>

                {{-- Intake Mode Field (4 modes) --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode *</label>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach(['Cash','Cheque','Bank Transfer','Online'] as $mode)
                        <button type="button" @click="form.payment_mode = '{{ $mode }}'"
                                :class="form.payment_mode === '{{ $mode }}' ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-primary/40'"
                                class="px-3 py-2 border rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all">
                            {{ $mode }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Reference & Bank --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Ref / Cheque No.</label>
                        <input type="text" x-model="form.reference_no" placeholder="Optional"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Bank Name</label>
                        <select x-model="form.bank_name"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="">-- Optional --</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Remarks</label>
                    <textarea x-model="form.remarks" rows="2"
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs focus:outline-none transition-all resize-none"></textarea>
                </div>

                {{-- Error / Success --}}
                <div x-show="error" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[11px] text-rose-700 font-semibold" x-text="error" x-transition></div>
                <div x-show="success" class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-[11px] text-emerald-700 font-semibold" x-text="success" x-transition></div>

                {{-- Submit --}}
                <button type="submit" :disabled="submitting"
                        class="w-full py-3 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider flex items-center justify-center gap-2">
                    <svg x-show="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="submitting ? 'Recording...' : 'Collect Receipt'"></span>
                </button>

                {{-- Link to Sales module --}}
                <p class="text-[10px] text-slate-400 text-center">
                    To register a new sale, go to
                    <a href="{{ route('sales.index') }}" class="text-primary font-bold hover:underline">Sales Module &rarr;</a>
                </p>
            </form>
        </div>

        {{-- Right: Recent Receipts --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Recent Receipts</h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Latest receipt collections — linked to Sale records.</p>
                </div>
                <a href="{{ route('emi-collections.cash-book') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-wide">View Full Cash Book →</a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-left">
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Sale No.</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Customer</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Project / Unit</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Amount</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Mode</th>
                            <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentReceipts as $receipt)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3 font-bold text-primary font-mono text-[10px]">{{ $receipt->sale?->sale_number ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-slate-800">{{ $receipt->customer?->name ?? '—' }}</div>
                                <div class="text-[9px] text-slate-400">{{ $receipt->customer?->phone ?? '' }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-slate-700">{{ $receipt->sale?->project?->name ?? '—' }}</div>
                                <div class="text-[9px] text-slate-400">Unit: {{ $receipt->sale?->unit?->door_no ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-3 text-right font-bold text-slate-900 font-mono">₹{{ number_format($receipt->amount, 2) }}</td>
                            <td class="px-5 py-3">
                                @php $modeColors = ['Cash'=>'bg-emerald-50 text-emerald-700','Cheque'=>'bg-amber-50 text-amber-700','Bank Transfer'=>'bg-blue-50 text-blue-700','Online'=>'bg-primary-50 text-primary-700']; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $modeColors[$receipt->payment_mode] ?? 'bg-slate-100 text-slate-600' }}">{{ $receipt->payment_mode }}</span>
                                @if($receipt->partner)
                                <div class="text-[9px] font-bold text-primary-700 mt-1">Via: {{ $receipt->partner->name }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-[10px]">{{ $receipt->receipt_date?->format('d M Y') ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">No receipts found. Use the form on the left to record the first collection.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function receiptsApp() {
    return {
        form: {
            sale_id:      '',
            amount:       '',
            receipt_date: new Date().toISOString().split('T')[0],
            payment_mode: 'Cash',
            reference_no: '',
            bank_name:    '',
            remarks:      '',
        },
        selectedSale: null,
        submitting: false,
        error: '',
        success: '',
        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const saleIdParam = urlParams.get('sale_id');
            if (saleIdParam) {
                this.form.sale_id = saleIdParam;
                this.updateSaleDetail();
            }
        },
        updateSaleDetail() {
            if (!this.form.sale_id) {
                this.selectedSale = null;
                this.form.amount = '';
                return;
            }
            const sel = document.querySelector(`select[x-model="form.sale_id"] option[value="${this.form.sale_id}"]`);
            if (sel && sel.dataset) {
                this.selectedSale = {
                    remaining: sel.dataset.remaining || '0',
                    total:     sel.dataset.total || '0',
                    unit:      sel.dataset.unit || '—',
                    project:   sel.dataset.project || '—',
                    customer:  sel.dataset.customer || '—',
                    salenum:   sel.dataset.salenum || '',
                };
                this.form.amount = '';
            } else {
                this.selectedSale = null;
                this.form.amount = '';
            }
        },

        async submitReceipt() {
            this.error = '';
            this.success = '';
            if (!this.form.sale_id || !this.form.amount || !this.form.payment_mode) {
                this.error = 'Please select a Sale, enter amount, and choose payment mode.';
                return;
            }
            this.submitting = true;
            try {
                const res = await fetch('{{ route('emi-collections.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ...this.form, _token: '{{ csrf_token() }}' }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.success = 'Receipt recorded successfully!';
                    this.form.sale_id = '';
                    this.form.amount  = '';
                    this.selectedSale = null;
                    setTimeout(() => window.location.reload(), 1400);
                } else {
                    this.error = json.error || json.message || 'An error occurred.';
                }
            } catch(e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.submitting = false;
            }
        }
    }
}
</script>

</x-erp-layout>
