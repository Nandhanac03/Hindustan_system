<x-erp-layout title="Loan Repayment Schedule" headerTitle="Loan Repayment Schedule Manager">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="scheduleApp()">
    {{-- Loan summary card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6 p-6">
        <div class="space-y-1.5">
            <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <a href="{{ route('loans.index') }}" class="hover:text-primary transition">Bank Loans</a>
                <span>/</span>
                <span class="text-primary">Repayment Schedule</span>
            </div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase" x-text="'Loan A/C: ' + loan.loan_account_no"></h1>
            <p class="text-xs text-slate-500 font-medium">Lending Bank: <span class="font-bold text-slate-700" x-text="loan.lender_name"></span> | Project: <span class="font-bold text-slate-700">{{ $loan->project->name ?? '—' }}</span></p>
        </div>

        <div class="flex flex-wrap gap-3">
            @if($loan->status === 'Active')
                <button @click="openPrepayModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md uppercase tracking-wide">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Prepayment & Reschedule
                </button>
            @endif
            <a href="{{ route('loans.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200 uppercase tracking-wide">
                &larr; Back Master
            </a>
        </div>
    </div>

    {{-- Loan metrics cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Principal Loan Amount</span>
                <strong class="text-sm font-extrabold text-slate-900 block mt-1 font-mono">₹{{ number_format((float)$loan->principal_amount, 2) }}</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center font-bold">₹</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Outstanding Balance</span>
                <strong class="text-sm font-extrabold text-rose-700 block mt-1 font-mono">₹{{ number_format((float)$loan->outstanding_balance, 2) }}</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Interest Rate</span>
                <strong class="text-sm font-extrabold text-slate-900 block mt-1 font-mono">{{ $loan->interest_rate }}% <span class="text-[10px] text-slate-400 font-medium">P.A</span></strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Tenure / Type</span>
                <strong class="text-sm font-extrabold text-slate-900 block mt-1 uppercase" style="font-size: 11px;">{{ $loan->tenure_months }} Months / {{ str_replace('_', ' ', $loan->schedule_type) }}</strong>
            </div>
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Repayment schedule table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Repayment Installment Ledger</h2>
            <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Paid</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Unpaid</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-center font-bold text-slate-700 uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-3 border">INST NO</th>
                        <th class="px-4 py-3 border">DUE DATE</th>
                        <th class="px-4 py-3 border">EMI AMOUNT</th>
                        <th class="px-4 py-3 border">PRINCIPAL COMPONENT</th>
                        <th class="px-4 py-3 border">INTEREST COMPONENT</th>
                        <th class="px-4 py-3 border">AMOUNT PAID</th>
                        <th class="px-4 py-3 border">PAID DATE</th>
                        <th class="px-4 py-3 border">STATUS</th>
                        <th class="px-4 py-3 border text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-center">
                    @forelse($loan->emiSchedules as $inst)
                        <tr class="hover:bg-slate-50/50 transition-colors text-xs font-semibold text-slate-700 {{ $inst->status === 'Paid' ? 'bg-emerald-50/10' : '' }}">
                            <td class="px-4 py-3.5 border font-bold text-slate-400">{{ $inst->installment_no }}</td>
                            <td class="px-4 py-3.5 border text-slate-650">{{ $inst->due_date ? \Carbon\Carbon::parse($inst->due_date)->format('d M Y') : '—' }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-900 font-bold">₹{{ number_format((float)$inst->emi_amount, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-600">₹{{ number_format((float)$inst->principal_component, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-slate-600">₹{{ number_format((float)$inst->interest_component, 2) }}</td>
                            <td class="px-4 py-3.5 border font-mono text-emerald-800 font-bold">₹{{ number_format((float)$inst->amount_paid, 2) }}</td>
                            <td class="px-4 py-3.5 border text-slate-500">{{ $inst->paid_date ? \Carbon\Carbon::parse($inst->paid_date)->format('d M Y') : '—' }}</td>
                            <td class="px-4 py-3.5 border">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider border {{ $inst->status === 'Paid' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-amber-50 border-amber-100 text-amber-700' }}">
                                    {{ $inst->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 border text-right">
                                @if($inst->status !== 'Paid' && $loan->status === 'Active')
                                    <button @click="openPayModal({{ $inst }})" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition shadow-sm">
                                        Pay Installment
                                    </button>
                                @else
                                    <span class="text-emerald-650 text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Cleared</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-slate-400 italic">No schedules generated.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Prepayment Logs list --}}
    @if($loan->prepayments->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-4">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Prepayment & Rescheduling History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-center">
                            <th class="px-4 py-2 border">DATE</th>
                            <th class="px-4 py-2 border">PREPAYMENT AMOUNT</th>
                            <th class="px-4 py-2 border">PREVIOUS OUTSTANDING</th>
                            <th class="px-4 py-2 border">NEW OUTSTANDING</th>
                            <th class="px-4 py-2 border">RESCHEDULE MODE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-center font-semibold text-slate-700">
                        @foreach($loan->prepayments as $log)
                            <tr>
                                <td class="px-4 py-3 border font-mono text-slate-600">{{ $log->prepayment_date ? \Carbon\Carbon::parse($log->prepayment_date)->format('d M Y') : '—' }}</td>
                                <td class="px-4 py-3 border font-mono text-emerald-800">₹{{ number_format((float)$log->prepayment_amount, 2) }}</td>
                                <td class="px-4 py-3 border font-mono text-slate-550">₹{{ number_format((float)$log->previous_outstanding, 2) }}</td>
                                <td class="px-4 py-3 border font-mono text-rose-700 font-bold">₹{{ number_format((float)$log->new_outstanding, 2) }}</td>
                                <td class="px-4 py-3 border text-slate-600 uppercase text-[10px]">{{ $log->reschedule_option === 'reduce_emi' ? 'Reduce EMI' : 'Reduce Tenure' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Pay EMI Modal --}}
    <div x-show="payModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="payModalOpen = false"></div>
        <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest" x-text="'Record Payment: Installment #' + activeInst.installment_no"></h3>
                <button @click="payModalOpen = false" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <form @submit.prevent="submitPayForm">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">EMI Due (₹)</label>
                        <input type="text" readonly :value="'₹' + Number(activeInst.emi_amount - activeInst.amount_paid).toLocaleString('en-IN', {minimumFractionDigits: 2})" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Payment Amount (₹) *</label>
                        <input type="number" step="0.01" x-model="payForm.amount" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Payment Date *</label>
                        <input type="date" x-model="payForm.paid_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="payModalOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-emerald-650/20">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Prepayment & Reschedule Modal --}}
    <div x-show="prepayModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="prepayModalOpen = false"></div>
        <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Lump Sum Prepayment & Rescheduling</h3>
                <button @click="prepayModalOpen = false" class="text-slate-400 hover:text-slate-650">✕</button>
            </div>
            <form @submit.prevent="submitPrepayForm">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Prepayment Amount (₹) *</label>
                        <input type="number" step="0.01" x-model="prepayForm.amount" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date of Prepayment *</label>
                        <input type="date" x-model="prepayForm.prepayment_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Rescheduling Mode *</label>
                        <select x-model="prepayForm.reschedule_option" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs text-slate-800 focus:outline-none transition-all">
                            <option value="reduce_emi">Reduce EMI amount (keep tenure the same)</option>
                            <option value="reduce_tenure">Reduce Tenure (keep monthly EMI the same)</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="prepayModalOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wide hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold uppercase tracking-wide transition shadow-md shadow-[#a38c29]/20">Apply & Reschedule</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Alert Toast --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>
</div>

<script>
function scheduleApp() {
    return {
        loan: {!! json_encode($loan) !!},
        payModalOpen: false,
        prepayModalOpen: false,
        activeInst: {},
        payForm: {
            amount: '',
            paid_date: new Date().toISOString().split('T')[0]
        },
        prepayForm: {
            amount: '',
            prepayment_date: new Date().toISOString().split('T')[0],
            reschedule_option: 'reduce_emi'
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => { this.toast.open = false; }, 4000);
        },
        openPayModal(inst) {
            this.activeInst = inst;
            this.payForm.amount = Number(inst.emi_amount - inst.amount_paid).toFixed(2);
            this.payForm.paid_date = new Date().toISOString().split('T')[0];
            this.payModalOpen = true;
        },
        openPrepayModal() {
            this.prepayForm.amount = '';
            this.prepayForm.prepayment_date = new Date().toISOString().split('T')[0];
            this.prepayForm.reschedule_option = 'reduce_emi';
            this.prepayModalOpen = true;
        },
        submitPayForm() {
            fetch(`{{ url('loans') }}/${this.loan.id}/pay-emi/${this.activeInst.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.payForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to submit payment.', 'error');
                } else {
                    this.showToast('Payment submitted successfully.');
                    this.payModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },
        submitPrepayForm() {
            fetch(`{{ url('loans') }}/${this.loan.id}/prepay`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.prepayForm)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    this.showToast(data.error || 'Failed to apply prepayment.', 'error');
                } else {
                    this.showToast('Prepayment applied and schedule rescheduled successfully.');
                    this.prepayModalOpen = false;
                    setTimeout(() => { window.location.reload(); }, 1500);
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        }
    }
}
</script>
</x-erp-layout>
