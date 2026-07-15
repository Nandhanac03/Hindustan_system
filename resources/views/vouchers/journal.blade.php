<x-erp-layout>
    <x-slot:title>Journal Voucher - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Journal Voucher</x-slot:headerTitle>

    <div class="max-w-6xl mx-auto" x-data="journalVoucher()">
        <form action="{{ route('vouchers.journal.store') }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-soft overflow-hidden">
            @csrf

            <!-- Form Title bar -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-800 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider">CORE ACCOUNTING ENGINE / JOURNAL VOUCHER</h2>
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

                <!-- Row 1: Voucher Date -->
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Voucher Date</label>
                    <input type="date" name="date" required x-model="date"
                           class="w-full md:w-1/4 px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-950 focus:outline-none transition-all cursor-pointer font-sans font-semibold">
                </div>

                <!-- Section 2: Multi-line Data Grid -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Journal Adjustment Entries</span>
                        <button type="button" @click="addLine()"
                                class="px-3 py-1.5 bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/25 hover:bg-[#a38c29]/20 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all">
                            + Add Entry Line
                        </button>
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    <th class="px-4 py-3 w-1/3">Ledger / Account Head</th>
                                    <th class="px-4 py-3 text-right w-1/5">Debit (DR)</th>
                                    <th class="px-4 py-3 text-right w-1/5">Credit (CR)</th>
                                    <th class="px-4 py-3 w-1/4">Line Description / Narration</th>
                                    <th class="px-4 py-3 text-center w-12">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <template x-for="(line, index) in lines" :key="index">
                                    <tr class="hover:bg-slate-50/40">
                                        <!-- Account Dropdown -->
                                        <td class="px-4 py-2">
                                            <select :name="'lines['+index+'][account_id]'" required x-model="line.account_id"
                                                    class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 transition-all cursor-pointer">
                                                <option value="">-- Select Account Head --</option>
                                                @foreach($accounts as $acc)
                                                    <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }}) [{{ $acc->type }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                        <!-- Debit Field -->
                                        <td class="px-4 py-2">
                                            <div class="relative rounded-xl shadow-2xs">
                                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 font-bold text-[10px]">₹</div>
                                                <input type="number" :name="'lines['+index+'][debit]'" min="0" step="0.01" placeholder="0.00"
                                                       x-model.number="line.debit" @input="clearOpposite(line, 'debit')"
                                                       class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-200 rounded-xl text-right font-mono font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 transition-all">
                                            </div>
                                        </td>

                                        <!-- Credit Field -->
                                        <td class="px-4 py-2">
                                            <div class="relative rounded-xl shadow-2xs">
                                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 font-bold text-[10px]">₹</div>
                                                <input type="number" :name="'lines['+index+'][credit]'" min="0" step="0.01" placeholder="0.00"
                                                       x-model.number="line.credit" @input="clearOpposite(line, 'credit')"
                                                       class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-200 rounded-xl text-right font-mono font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 transition-all">
                                            </div>
                                        </td>

                                        <!-- Line Narration -->
                                        <td class="px-4 py-2">
                                            <input type="text" :name="'lines['+index+'][line_narration]'" placeholder="Particular details..."
                                                   x-model="line.line_narration"
                                                   class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 transition-all">
                                        </td>

                                        <!-- Remove Row Button -->
                                        <td class="px-4 py-2 text-center">
                                            <button type="button" @click="removeLine(index)" :disabled="lines.length <= 2"
                                                    class="p-1.5 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-lg transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <!-- TOTALS Row -->
                                <tr class="bg-slate-100/80 border-t border-slate-300 font-extrabold text-slate-900">
                                    <td class="px-4 py-3 text-[10px] uppercase tracking-wider">TOTALS</td>
                                    <td class="px-4 py-3 text-right font-mono" x-text="'₹' + formatCurrency(calcTotalDebit())"></td>
                                    <td class="px-4 py-3 text-right font-mono" x-text="'₹' + formatCurrency(calcTotalCredit())"></td>
                                    <td class="px-4 py-3 text-slate-400 font-bold text-[10px]">-</td>
                                    <td class="px-4 py-3 text-center">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 3: Checker / Balance Indicator Block -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Checker Status badge -->
                        <template x-if="isBalanced()">
                            <div class="px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-[10px] font-extrabold uppercase tracking-widest flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-600 animate-pulse"></span>
                                ● BALANCED
                            </div>
                        </template>
                        <template x-if="!isBalanced()">
                            <div class="px-4 py-2 bg-rose-50 border border-rose-250 rounded-xl text-rose-800 text-[10px] font-extrabold uppercase tracking-widest flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-rose-600 animate-pulse"></span>
                                ● UNBALANCED
                            </div>
                        </template>

                        <!-- Math Summary -->
                        <div class="text-xs text-slate-500 font-semibold">
                            Total Debits: <span class="font-bold text-slate-800 font-mono" x-text="'₹' + formatCurrency(calcTotalDebit())"></span> |
                            Total Credits: <span class="font-bold text-slate-800 font-mono" x-text="'₹' + formatCurrency(calcTotalCredit())"></span>
                        </div>
                    </div>

                    <!-- Difference -->
                    <div class="text-right">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Difference</span>
                        <strong class="text-sm font-mono" :class="isBalanced() ? 'text-slate-650' : 'text-rose-600'"
                                x-text="'₹' + formatCurrency(calcDifference())"></strong>
                    </div>
                </div>

                <!-- Section 4: Global Narration -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Voucher Narration / Remarks</label>
                    <textarea name="narration" rows="2" placeholder="Adjusting Site Expenses for partner accounts..."
                              class="w-full bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-4 focus:ring-[#a38c29]/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Form Footer / Actions -->
            <div class="px-6 py-5 border-t border-slate-150 bg-slate-50 flex items-center justify-end gap-2">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 border border-slate-250 hover:bg-slate-100 text-slate-650 text-[10px] font-extrabold rounded-xl transition uppercase tracking-wider">
                    Cancel
                </a>
                <button type="submit" :disabled="!isBalanced()"
                        class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-extrabold rounded-xl transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wider disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none">
                    Save Voucher
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
