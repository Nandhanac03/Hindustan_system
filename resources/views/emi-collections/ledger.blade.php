<x-erp-layout title="Customer Ledger" headerTitle="Customer Running Ledger">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="ledgerApp()">

    {{-- Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-[11px] text-slate-400 font-semibold">
        <div class="flex items-center gap-2">
            <a href="{{ route('sales.index') }}" class="hover:text-primary transition-colors">Sales</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('emi-collections.outstanding') }}" class="hover:text-primary transition-colors">Outstanding</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-700">Ledger — {{ $sale->sale_number }}</span>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Select Customer / Unit:</span>
            <select onchange="window.location.href='/emi-collections/ledger/' + this.value" 
                    class="px-3 py-1.5 bg-white border border-slate-200 focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-700 font-bold focus:outline-none transition-all">
                @foreach($allSales as $s)
                    <option value="{{ $s->id }}" {{ $s->id == $sale->id ? 'selected' : '' }}>
                        {{ $s->customer?->name ?? '—' }} — Unit: {{ $s->unit?->door_no ?? '—' }} ({{ $s->sale_number }})
                    </option>
                @endforeach
            </select>
        </div>
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
                <span class="text-sm font-bold text-primary mt-1 block font-mono">{{ $sale->sale_number }}</span>
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
                <button @click="openEmiModal()" class="text-[10px] font-bold text-primary hover:underline bg-transparent border-0 cursor-pointer p-0">↗ Manage EMI Schedule</button>
                <button @click="openPayModal({{ $closingBalance }}, 'Outstanding Balance')" class="text-[10px] font-bold text-primary hover:underline bg-transparent border-0 cursor-pointer p-0">↗ Add Receipt</button>
                 <a href="{{ route('sales.index') }}" class="text-[10px] font-bold text-primary hover:underline">↗ Sales Register</a>
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
                  <td class="px-5 py-3 align-middle">
    <div class="flex items-center gap-2 flex-wrap">

        {{-- Type Badge --}}
        <span class="inline-flex items-center justify-center px-2 py-1 text-[9px] font-bold uppercase rounded-md border {{ $cfg['badge'] }}">
            {{ strtoupper($row['type']) }}
        </span>

        {{-- Installment Status / Button --}}
        @if(isset($row['status']) && $row['type'] === 'installment')

            @if($row['status'] === 'paid')
                <span class="inline-flex items-center px-2 py-1 text-[9px] font-bold uppercase rounded-md bg-emerald-100 text-emerald-700 border border-emerald-200">
                    Paid
                </span>
            @elseif($row['status'] === 'partial')
                <span class="inline-flex items-center px-2 py-1 text-[9px] font-bold uppercase rounded-md bg-amber-100 text-amber-700 border border-amber-200">
                    Partial
                </span>
                <button
                    @click.stop="openPayModal('', '{{ addslashes($row['description']) }}')"
                    type="button"
                    class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8d7923] text-white text-[9px] font-bold uppercase tracking-wider rounded-md transition-all whitespace-nowrap">
                    Pay Remaining
                </button>
            @elseif($row['status'] === 'overdue')
                <span class="inline-flex items-center px-2 py-1 text-[9px] font-bold uppercase rounded-md bg-rose-100 text-rose-700 border border-rose-200">
                    Overdue
                </span>
                <button
                    @click.stop="openPayModal('', '{{ addslashes($row['description']) }}')"
                    type="button"
                    class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8d7923] text-white text-[9px] font-bold uppercase tracking-wider rounded-md transition-all whitespace-nowrap">
                    Pay Installment
                </button>
            @else
                <button
                    @click.stop="openPayModal('', '{{ addslashes($row['description']) }}')"
                    type="button"
                    class="inline-flex items-center justify-center px-3 py-1 bg-[#a38c29] hover:bg-[#8d7923] text-white text-[9px] font-bold uppercase tracking-wider rounded-md transition-all whitespace-nowrap">
                    Pay Installment
                </button>
            @endif

        @endif

    </div>
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
                  <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Payment for <span x-text="form.label" class="text-primary font-bold"></span></h3>
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
                          <select x-model="form.bank_name"
                                  class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29]">
                              <option value="">-- Optional --</option>
                              @foreach($banks as $bank)
                                  <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                              @endforeach
                          </select>
                      </div>
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
                          <span x-text="submitting ? 'Recording...' : 'Collect Payment'"></span>
                      </button>
                  </div>
              </form>
         </div>
    </div>

    {{-- Manage EMI Schedule Modal --}}
    <div x-show="emiModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
         style="display: none;" x-transition>
         <div @click.away="emiModalOpen = false" 
              class="bg-white rounded-2xl border border-slate-200 shadow-2xl p-6 w-full max-w-2xl space-y-4">
              
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                  <h3 class="text-sm font-bold text-slate-950 uppercase tracking-wide">Manage EMI Schedule</h3>
                  <button @click="emiModalOpen = false" class="text-slate-400 hover:text-slate-650 text-base">✕</button>
              </div>

              <div x-show="emiError" class="p-3 bg-rose-50 border border-rose-150 rounded-xl text-xs font-bold text-rose-800 uppercase tracking-wide" x-text="emiError"></div>

              {{-- Dynamic Summary Stats --}}
              <div class="grid grid-cols-3 gap-3 bg-slate-50 p-4 rounded-xl text-xs">
                  <div>
                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Sale Amount</span>
                      <strong class="text-slate-800 text-sm font-mono">₹<span x-text="totalSaleAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                  </div>
                  <div>
                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Allocated Schedule</span>
                      <strong class="text-slate-800 text-sm font-mono">₹<span x-text="calculateTotalAllocated().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></strong>
                  </div>
                  <div>
                      <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Unallocated Balance</span>
                      <div class="flex items-center gap-1.5 mt-0.5">
                          <strong class="text-sm font-mono" :class="calculateUnallocated() === 0 ? 'text-emerald-600' : 'text-rose-600'">
                              ₹<span x-text="calculateUnallocated().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                          </strong>
                          <template x-if="calculateUnallocated() !== 0">
                              <button type="button" @click="distributeRemaining()" class="px-1.5 py-0.5 bg-primary/10 hover:bg-primary/20 text-primary text-[8px] font-bold uppercase rounded transition-colors">
                                  Auto-Distribute
                              </button>
                          </template>
                      </div>
                  </div>
              </div>

              {{-- Scrollable List of Rows --}}
              <div class="overflow-y-auto max-h-[350px] space-y-2.5 pr-1">
                  <template x-for="(inst, index) in editInstallments" :key="index">
                      <div class="flex items-center gap-3 p-3 rounded-xl border transition-all"
                           :class="inst.status === 'paid' ? 'bg-emerald-50/20 border-emerald-100' : 'bg-white border-slate-200/80'">
                          
                          {{-- Label Input --}}
                          <div class="w-1/4 space-y-1">
                              <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Label</span>
                              <input type="text" x-model="inst.label" :disabled="inst.status === 'paid'"
                                     class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white rounded-lg text-xs font-semibold focus:outline-none transition-all disabled:opacity-50 disabled:bg-slate-100">
                          </div>

                          {{-- Due Date Input --}}
                          <div class="w-1/3 space-y-1">
                              <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Due Date</span>
                              <input type="date" x-model="inst.due_date" :disabled="inst.status === 'paid'"
                                     class="w-full px-2 py-1 bg-slate-50 border border-slate-200 focus:bg-white rounded-lg text-xs font-semibold focus:outline-none transition-all disabled:opacity-50 disabled:bg-slate-100">
                          </div>

                          {{-- Amount Input --}}
                          <div class="w-1/3 space-y-1">
                              <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Amount (₹)</span>
                              <input type="number" step="0.01" x-model.number="inst.amount" :disabled="inst.status === 'paid'"
                                     class="w-full px-2 py-1 bg-slate-50 border border-slate-200 focus:bg-white rounded-lg text-xs font-bold text-slate-800 font-mono focus:outline-none transition-all disabled:opacity-50 disabled:bg-slate-100">
                          </div>

                          {{-- Action / Status --}}
                          <div class="pt-4">
                              <template x-if="inst.status === 'paid'">
                                  <span class="px-2 py-1 rounded text-[8px] font-bold uppercase bg-emerald-100 text-emerald-700">Paid</span>
                              </template>
                              <template x-if="inst.status !== 'paid'">
                                  <button type="button" @click="removeInstallment(index)" class="text-rose-600 hover:text-rose-800 transition-colors" title="Delete Row">
                                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                      </svg>
                                  </button>
                              </template>
                          </div>
                      </div>
                  </template>
              </div>

              {{-- Footer Actions --}}
              <div class="pt-4 flex justify-between items-center border-t border-slate-100">
                  <button type="button" @click="addInstallment()"
                          class="px-4 py-2 bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wide flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                      Add Installment
                  </button>
                  <div class="flex gap-2">
                      <button type="button" @click="emiModalOpen = false" 
                              class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-550 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                          Cancel
                      </button>
                      <button type="button" @click="submitEmiSchedule()" x-bind:disabled="emiSubmitting"
                              class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md flex items-center gap-1.5">
                          <span x-text="emiSubmitting ? 'Saving...' : 'Save Schedule'"></span>
                      </button>
                  </div>
              </div>
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
        },

        // Custom EMI schedule editor state
        originalInstallments: @json($installments),
        editInstallments: [],
        emiModalOpen: false,
        totalSaleAmount: {{ $sale->total_amount }},
        emiSubmitting: false,
        emiError: '',
        openEmiModal() {
            this.emiError = '';
            this.editInstallments = this.originalInstallments.map(inst => ({
                id: inst.id,
                installment_no: inst.installment_no,
                label: inst.label,
                due_date: inst.due_date ? inst.due_date.split('T')[0] : '',
                amount: Number(inst.amount),
                status: inst.status
            }));
            this.emiModalOpen = true;
        },
        addInstallment() {
            const nextNo = this.editInstallments.length > 0 
                ? Math.max(...this.editInstallments.map(i => i.installment_no)) + 1 
                : 1;
            
            let lastDate = new Date();
            if (this.editInstallments.length > 0) {
                const dates = this.editInstallments.map(i => i.due_date).filter(Boolean);
                if (dates.length > 0) {
                    lastDate = new Date(dates[dates.length - 1]);
                    lastDate.setMonth(lastDate.getMonth() + 1);
                }
            }
            
            this.editInstallments.push({
                installment_no: nextNo,
                label: 'EMI ' + nextNo,
                due_date: lastDate.toISOString().split('T')[0],
                amount: 0,
                status: 'pending'
            });
        },
        removeInstallment(index) {
            if (this.editInstallments[index].status === 'paid') return;
            this.editInstallments.splice(index, 1);
            this.editInstallments.forEach((inst, idx) => {
                if (inst.installment_no > 0) {
                    inst.installment_no = idx;
                }
            });
        },
        calculateTotalAllocated() {
            return this.editInstallments.reduce((sum, inst) => sum + Number(inst.amount), 0);
        },
        calculateUnallocated() {
            return Math.round((this.totalSaleAmount - this.calculateTotalAllocated()) * 100) / 100;
        },
        distributeRemaining() {
            const unallocated = this.calculateUnallocated();
            const pendingInsts = this.editInstallments.filter(inst => inst.status === 'pending');
            if (pendingInsts.length === 0) {
                this.emiError = 'No pending installments to distribute balance to.';
                return;
            }
            
            const perInstallment = Math.round((unallocated / pendingInsts.length) * 100) / 100;
            pendingInsts.forEach((inst, idx) => {
                if (idx === pendingInsts.length - 1) {
                    const allocatedSoFar = perInstallment * (pendingInsts.length - 1);
                    inst.amount = Math.round((Number(inst.amount) + (unallocated - allocatedSoFar)) * 100) / 100;
                } else {
                    inst.amount = Math.round((Number(inst.amount) + perInstallment) * 100) / 100;
                }
            });
        },
        async submitEmiSchedule() {
            this.emiError = '';
            const unallocated = this.calculateUnallocated();
            if (Math.abs(unallocated) > 0.01) {
                this.emiError = `Unallocated balance must be 0 (current: ₹${unallocated.toLocaleString('en-IN')}).`;
                return;
            }
            this.emiSubmitting = true;
            try {
                const res = await fetch('{{ route('emi-collections.schedules.bulk-update', $sale->id) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ installments: this.editInstallments, _token: '{{ csrf_token() }}' }),
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.emiModalOpen = false;
                    window.location.reload();
                } else {
                    this.emiError = json.error || json.message || 'An error occurred.';
                }
            } catch(e) {
                this.emiError = 'Request failed: ' + e.message;
            } finally {
                this.emiSubmitting = false;
            }
        }
    };
}
</script>

</x-erp-layout>
