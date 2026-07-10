<x-erp-layout title="Customer Ledger" headerTitle="Customer Running Ledger">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="ledgerApp()">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-semibold">
        <a href="{{ route('sales.index') }}" class="hover:text-primary transition-colors">Sales</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('emi-collections.outstanding') }}" class="hover:text-primary transition-colors">Outstanding</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-700">Ledger — {{ $sale->sale_number }}</span>
    </div>

    {{-- Sale Summary Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Customer</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block">{{ $sale->customer?->name ?? '—' }}</span>
                <span class="text-[10px] text-slate-400">{{ $sale->customer?->phone ?? '' }}</span>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Number / Project</span>
                <span class="text-sm font-bold text-indigo-600 mt-1 block font-mono">{{ $sale->sale_number }}</span>
                <span class="text-[10px] text-slate-500">{{ $sale->project?->name ?? '—' }} — Unit: {{ $sale->unit?->door_no ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Amount (incl. GST)</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block font-mono">₹{{ number_format($sale->total_amount, 2) }}</span>
                <span class="text-[10px] text-slate-400">Agreement: {{ $sale->agreement_date?->format('d M Y') ?? '—' }}</span>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Remaining Balance</span>
                <span class="text-sm font-extrabold {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1 block font-mono">
                    ₹{{ number_format(abs($closingBalance), 2) }}
                    <span class="text-[9px] font-semibold">{{ $closingBalance > 0 ? '(Outstanding)' : '(Fully Paid)' }}</span>
                </span>
            </div>
        </div>
    </div>

    {{-- Ledger KPIs --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Sale Value</span>
            <span class="text-xl font-extrabold text-slate-900 mt-1 block font-mono">₹{{ number_format($sale->total_amount, 2) }}</span>
            <span class="text-[9px] text-slate-400">Agreed sale price + GST</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Instalment Dues</span>
            <span class="text-xl font-extrabold text-rose-600 mt-1 block font-mono">₹{{ number_format($totalDebits, 2) }}</span>
            <span class="text-[9px] text-slate-400">Scheduled installments</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Receipts</span>
            <span class="text-xl font-extrabold text-emerald-600 mt-1 block font-mono">₹{{ number_format($totalCredits, 2) }}</span>
            <span class="text-[9px] text-slate-400">Payments received</span>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Net Outstanding</span>
            <span class="text-xl font-extrabold {{ $closingBalance > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1 block font-mono">
                ₹{{ number_format(abs($closingBalance), 2) }}
            </span>
            <span class="text-[9px] text-slate-400">{{ $closingBalance > 0 ? 'Balance Due' : 'Settled' }}</span>
        </div>
    </div>

    {{-- Running Ledger Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Running Ledger Statement</h2>
                <p class="text-xs text-slate-400 mt-0.5">Chronological history of installment dues and receipt credits with running balance.</p>
            </div>
            <div class="flex gap-3">
                <button @click="openPayModal({{ $closingBalance }}, 'Outstanding Balance')" class="text-[10px] font-bold text-primary hover:underline bg-transparent border-0 cursor-pointer p-0">↗ Add Receipt</button>
                <a href="{{ route('sales.index') }}" class="text-[10px] font-bold text-indigo-600 hover:underline">↗ Sales Register</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left">
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Date</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Description</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Debit (Due)</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Credit (Paid)</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest text-right">Running Balance</th>
                        <th class="px-5 py-3 font-bold text-slate-500 text-[9px] uppercase tracking-widest">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ledger as $row)
                    @php
                        $typeColors = [
                            'installment' => ['row' => ($row['status'] ?? '') === 'paid' ? 'bg-emerald-50/20' : (($row['status'] ?? '') === 'overdue' ? 'bg-rose-50/30' : ''), 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                            'receipt'     => ['row' => 'bg-emerald-50/20', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                        ];
                        $cfg = $typeColors[$row['type']] ?? ['row' => '', 'badge' => 'bg-slate-100 text-slate-600 border-slate-200'];
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors {{ $cfg['row'] }}">
                        <td class="px-5 py-3 text-slate-500 text-[10px] font-mono">{{ $row['date'] }}</td>
                        <td class="px-5 py-3">
                            <div class="font-semibold text-slate-800">{{ $row['description'] }}</div>
                            @if(isset($row['status']) && $row['type'] === 'installment')
                                <div class="mt-0.5">
                                    @if($row['status'] === 'paid')
                                        <span class="text-[9px] font-bold text-emerald-650 uppercase">Paid</span>
                                    @else
                                        <button @click.stop="openPayModal({{ $row['debit'] }}, '{{ addslashes($row['description']) }}')"
                                                type="button"
                                                class="px-2.5 py-1 bg-[#a38c29] hover:bg-[#8d7923] text-white font-extrabold rounded-lg text-[9px] uppercase tracking-wider transition-all shadow-md">
                                            Pay Installment
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right font-mono {{ $row['debit'] > 0 ? 'text-rose-600 font-bold' : 'text-slate-300' }}">
                            {{ $row['debit'] > 0 ? '₹' . number_format($row['debit'], 2) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-mono {{ $row['credit'] > 0 ? 'text-emerald-600 font-bold' : 'text-slate-300' }}">
                            {{ $row['credit'] > 0 ? '₹' . number_format($row['credit'], 2) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-bold font-mono {{ $row['running_balance'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            ₹{{ number_format(abs($row['running_balance']), 2) }}
                            <span class="text-[8px] font-semibold">{{ $row['running_balance'] > 0 ? 'DR' : 'CR' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-[9px] font-bold border px-1.5 py-0.5 rounded {{ $cfg['badge'] }}">
                                {{ strtoupper($row['type']) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No transactions yet for this sale. Use "Add Receipt" to record the first payment.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t-2 border-slate-200 font-bold">
                        <td colspan="2" class="px-5 py-3 text-[10px] text-slate-600 uppercase">Closing Totals</td>
                        <td class="px-5 py-3 text-right font-mono text-rose-700">₹{{ number_format($totalDebits, 2) }}</td>
                        <td class="px-5 py-3 text-right font-mono text-emerald-700">₹{{ number_format($totalCredits, 2) }}</td>
                        <td class="px-5 py-3 text-right font-mono {{ $closingBalance > 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                            ₹{{ number_format(abs($closingBalance), 2) }} {{ $closingBalance > 0 ? 'DR' : 'CR' }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Back Links --}}
    <div class="flex gap-4 text-xs">
        <a href="{{ route('emi-collections.outstanding') }}" class="font-bold text-slate-500 hover:text-primary transition-colors">&larr; Outstanding Summary</a>
        <a href="{{ route('sales.index') }}" class="font-bold text-slate-500 hover:text-primary transition-colors">&larr; Sales Register</a>
    </div>
    {{-- Direct Pay Installment Modal --}}
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
         style="display: none;" x-transition>
         <div @click.away="modalOpen = false" 
              class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-md space-y-4">
              
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                  <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Record Payment for <span x-text="form.label" class="text-primary font-bold"></span></h3>
                  <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
              </div>

              <div x-show="error" class="p-3 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide" x-text="error"></div>

              <form @submit.prevent="submitPayment()" class="space-y-4">
                  <div class="space-y-1.5">
                      <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount to Collect (₹) *</label>
                      <input type="number" step="0.01" required x-model.number="form.amount"
                             class="w-full px-3 py-2.5 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                      <div class="space-y-1.5">
                          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Receipt Date *</label>
                          <input type="date" required x-model="form.receipt_date"
                                 class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]">
                      </div>

                      <div class="space-y-1.5">
                          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode *</label>
                          <select x-model="form.payment_mode" required
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]">
                              <option value="Cash">Cash</option>
                              <option value="Cheque">Cheque</option>
                              <option value="Bank Transfer">Bank Transfer</option>
                              <option value="Online">Online</option>
                              <option value="UPI">UPI</option>
                          </select>
                      </div>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                      <div class="space-y-1.5">
                          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Reference / Chq No.</label>
                          <input type="text" x-model="form.reference_no" placeholder="e.g. TXN-12345"
                                 class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]">
                      </div>

                      <div class="space-y-1.5">
                          <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Bank Name</label>
                          <input type="text" x-model="form.bank_name" placeholder="e.g. HDFC Bank"
                                 class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]">
                      </div>
                  </div>

                  <div class="space-y-1.5">
                      <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Received By Partner (Optional)</label>
                      <select x-model="form.partner_id"
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]">
                          <option value="">-- Direct to Company --</option>
                          @foreach(\App\Models\Payee::where('type', 'Partner')->orderBy('name')->get() as $partner)
                              <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="space-y-1.5">
                      <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Remarks / Notes</label>
                      <textarea x-model="form.remarks" rows="2" placeholder="Internal notes..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] resize-none"></textarea>
                  </div>

                  <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                      <button type="button" @click="modalOpen = false" 
                              class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-550 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                          Cancel
                      </button>
                      <button type="submit" x-bind:disabled="submitting"
                              class="px-4 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md flex items-center gap-1.5">
                          <span x-text="submitting ? 'Recording...' : 'Record Payment'"></span>
                      </button>
                  </div>
              </form>
         </div>
    </div>

</div>

<script>
function ledgerApp() {
    return {
        modalOpen: false,
        submitting: false,
        error: '',
        form: {
            sale_id: '{{ $sale->id }}',
            amount: 0,
            receipt_date: new Date().toISOString().split('T')[0],
            payment_mode: 'Cash',
            reference_no: '',
            bank_name: '',
            partner_id: '',
            remarks: '',
            label: ''
        },
        openPayModal(amount, label) {
            this.error = '';
            this.form.amount = amount;
            this.form.label = label;
            this.form.receipt_date = new Date().toISOString().split('T')[0];
            this.form.payment_mode = 'Cash';
            this.form.reference_no = '';
            this.form.bank_name = '';
            this.form.partner_id = '';
            this.form.remarks = '';
            this.modalOpen = true;
        },
        async submitPayment() {
            this.error = '';
            this.submitting = true;
            try {
                const res = await fetch('{{ route('emi-collections.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ ...this.form, _token: '{{ csrf_token() }}' }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.modalOpen = false;
                    window.location.reload();
                } else {
                    this.error = json.error || json.message || 'An error occurred.';
                }
            } catch(e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>

</x-erp-layout>
