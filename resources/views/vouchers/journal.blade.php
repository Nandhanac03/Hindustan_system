<x-erp-layout>
    <x-slot:title>Journal Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Journal Voucher</x-slot:headerTitle>

    <div class="max-w-6xl mx-auto py-6" x-data="journalVoucher()">
        <form action="{{ route('vouchers.journal.store') }}" method="POST" class="bg-white text-slate-900 rounded-3xl border border-slate-200/90 shadow-xl p-8 space-y-7 transition-all">
            @csrf

            <!-- Form Header Bar -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-150">
                <div class="flex items-center gap-3.5">
                    <div class="p-3 bg-slate-900 text-white rounded-2xl shadow-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Journal Voucher</h2>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Record multi-line ledger adjustments, provisions, and non-cash postings</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-amber-500/10 text-amber-800 text-[10px] font-black rounded-full uppercase border border-amber-500/20 shadow-2xs">
                    Journal Adjustment
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
                    <input type="date" name="date" required x-model="date"
                           class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-extrabold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs cursor-pointer">
                </div>
            </div>

            <!-- Multi-line Entry Grid Header -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Journal Adjustment Lines</label>
                    <button type="button" @click="addLine()"
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Entry Line</span>
                    </button>
                </div>

                <div class="border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100/80 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                <th class="px-4 py-3.5 w-1/3">Ledger Head</th>
                                <th class="px-4 py-3.5 text-right w-1/5">Debit (DR)</th>
                                <th class="px-4 py-3.5 text-right w-1/5">Credit (CR)</th>
                                <th class="px-4 py-3.5 w-1/4">Line Description / Narration</th>
                                <th class="px-4 py-3.5 text-center w-12">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs bg-white">
                            <template x-for="(line, index) in lines" :key="index">
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <!-- Account Dropdown -->
                                    <td class="px-4 py-2.5">
                                        <select :name="'lines['+index+'][account_id]'" required x-model="line.account_id"
                                                class="w-full px-3 py-2 bg-white border border-slate-250 text-slate-900 font-extrabold rounded-xl text-xs focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 cursor-pointer transition">
                                            <option value="">Select Account Head...</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ $acc->type }}]</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    
                                    <!-- Debit Field -->
                                    <td class="px-4 py-2.5">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-black text-[10px]">₹</div>
                                            <input type="number" :name="'lines['+index+'][debit]'" min="0" step="0.01" placeholder="0.00"
                                                   x-model.number="line.debit" @input="clearOpposite(line, 'debit')"
                                                   class="w-full pl-7 pr-3 py-2 bg-white border border-slate-250 text-slate-900 font-mono font-extrabold rounded-xl text-right text-xs focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition">
                                        </div>
                                    </td>

                                    <!-- Credit Field -->
                                    <td class="px-4 py-2.5">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-black text-[10px]">₹</div>
                                            <input type="number" :name="'lines['+index+'][credit]'" min="0" step="0.01" placeholder="0.00"
                                                   x-model.number="line.credit" @input="clearOpposite(line, 'credit')"
                                                   class="w-full pl-7 pr-3 py-2 bg-white border border-slate-250 text-slate-900 font-mono font-extrabold rounded-xl text-right text-xs focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition">
                                        </div>
                                    </td>

                                    <!-- Line Narration -->
                                    <td class="px-4 py-2.5">
                                        <input type="text" :name="'lines['+index+'][line_narration]'" placeholder="Line detail..."
                                               x-model="line.line_narration"
                                               class="w-full px-3 py-2 bg-white border border-slate-250 text-slate-800 font-medium rounded-xl text-xs focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition">
                                    </td>

                                    <!-- Remove Row Button -->
                                    <td class="px-4 py-2.5 text-center">
                                        <button type="button" @click="removeLine(index)" x-show="lines.length > 2"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg transition-colors cursor-pointer" title="Remove row">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        
                        <!-- Table Footer / Live Totals -->
                        <tfoot>
                            <tr class="bg-slate-900 text-white font-black">
                                <td class="px-4 py-3.5 text-xs uppercase tracking-wider">TOTALS</td>
                                <td class="px-4 py-3.5 text-right font-mono text-xs" x-text="'₹' + formatCurrency(calcTotalDebit())"></td>
                                <td class="px-4 py-3.5 text-right font-mono text-xs" x-text="'₹' + formatCurrency(calcTotalCredit())"></td>
                                <td colspan="2" class="px-4 py-3.5 text-center">
                                    <template x-if="isBalanced()">
                                        <span class="inline-flex items-center gap-1.5 text-emerald-400 text-xs font-bold uppercase tracking-wider">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            BALANCED (DR = CR)
                                        </span>
                                    </template>
                                    <template x-if="!isBalanced()">
                                        <span class="inline-flex items-center gap-1.5 text-rose-400 text-xs font-bold uppercase tracking-wider">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            UNBALANCED (Diff: ₹<span x-text="formatCurrency(calcDifference())"></span>)
                                        </span>
                                    </template>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Header Narration -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Header Narration / Notes</label>
                <textarea name="narration" rows="2.5" placeholder="Overall journal entry narration..."
                          class="w-full bg-white border border-slate-300/80 text-slate-900 rounded-2xl px-4 py-3 text-xs font-semibold focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition shadow-2xs resize-none"></textarea>
            </div>

            <!-- Form Actions -->
            <div class="pt-2 flex items-center justify-end">
                <button type="submit" :disabled="!isBalanced()"
                        :class="(!isBalanced()) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:shadow-lg'"
                        class="px-8 py-3.5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white text-xs font-black rounded-2xl transition-all shadow-md uppercase tracking-wider flex items-center gap-2.5">
                    <span>Post Journal Voucher</span>
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        function journalVoucher() {
            return {
                date: new Date().toISOString().substring(0,10),
                lines: [
                    { account_id: '', debit: 0.0, credit: 0.0, line_narration: '' },
                    { account_id: '', debit: 0.0, credit: 0.0, line_narration: '' }
                ],
                addLine() {
                    this.lines.push({ account_id: '', debit: 0.0, credit: 0.0, line_narration: '' });
                },
                removeLine(index) {
                    if (this.lines.length > 2) {
                        this.lines.splice(index, 1);
                    }
                },
                clearOpposite(line, field) {
                    if (field === 'debit' && line.debit > 0) {
                        line.credit = 0.0;
                    } else if (field === 'credit' && line.credit > 0) {
                        line.debit = 0.0;
                    }
                },
                calcTotalDebit() {
                    return this.lines.reduce((sum, line) => sum + (parseFloat(line.debit) || 0.0), 0.0);
                },
                calcTotalCredit() {
                    return this.lines.reduce((sum, line) => sum + (parseFloat(line.credit) || 0.0), 0.0);
                },
                calcDifference() {
                    return Math.abs(this.calcTotalDebit() - this.calcTotalCredit());
                },
                isBalanced() {
                    const deb = this.calcTotalDebit();
                    const cred = this.calcTotalCredit();
                    return deb > 0 && Math.abs(deb - cred) < 0.001;
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
