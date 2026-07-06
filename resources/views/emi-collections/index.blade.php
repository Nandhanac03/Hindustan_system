<x-erp-layout title="EMI Collections" headerTitle="EMI Collections Directory">

<div class="max-w-[1400px] mx-auto space-y-6" x-data="emiApp()">

    {{-- Top Stats Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total EMI Collections Received</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-2 block">
                    ₹{{ number_format($totalReceived, 2) }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Pending Receipts</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-2 block">
                    {{ $pendingPaymentsCount }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Ledger Status</span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg px-2.5 py-1 mt-2 inline-block">
                    Healthy & Balanced
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Main Two-Column view --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left side: Collection History (2/3 width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">EMI Collection History</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time listing of customer installments and receipts.</p>
                </div>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-left">
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Receipt No.</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Customer</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Project</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Amount</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Mode</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-indigo-650">{{ $payment->receipt_number }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $payment->customer->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $payment->customer->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $payment->project->name }}</div>
                                    <span class="text-[9px] bg-slate-100 border px-1.5 py-0.5 rounded text-slate-500 font-mono">{{ $payment->project->code }}</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900">₹{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $payment->payment_mode }}</td>
                                <td class="px-6 py-4">
                                    <span class="badge-pill {{ $payment->status === 'completed' ? 'badge-completed' : 'badge-pending' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">No EMI Collection history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        {{-- Right side: Active Bookings for quick receipt mapping (1/3 width) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Map New Receipt</h2>
                <p class="text-xs text-slate-400 mt-0.5">Select a recent active booking to register an incoming payment installment.</p>
            </div>

            <div class="space-y-4">
                @forelse($recentBookings as $booking)
                    <div class="p-3.5 bg-slate-50 border border-slate-150 rounded-xl space-y-2 hover:border-indigo-200 hover:shadow-sm transition-all">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-100 font-mono">{{ $booking->booking_number }}</span>
                                <h3 class="text-xs font-bold text-slate-900 mt-2">{{ $booking->customer->name }}</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $booking->project->name }} · {{ $booking->unit->unit_number }}</p>
                            </div>
                            <span class="text-[11px] font-extrabold text-slate-900">₹{{ number_format($booking->amount / 100000, 1) }}L</span>
                        </div>
                        <button @click="openCollectModal({{ json_encode($booking) }})" class="w-full mt-2 py-1.5 bg-white border border-slate-200 hover:bg-indigo-50 hover:border-indigo-300 text-indigo-700 text-[10px] font-bold rounded-lg transition uppercase tracking-wide">
                            Collect Installment
                        </button>
                    </div>
                @empty
                    <p class="text-xs text-slate-450 italic text-center py-4">No recent active bookings found.</p>
                @endforelse
            </div>
        </div>

    </div>


    {{-- Toast Notification --}}
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

    {{-- Collect Installment Modal --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition>
        <div @click.away="closeCollectModal()" class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Collect Installment</h3>
                    <p class="text-[10px] text-slate-450 mt-0.5">Register a payment against booking record.</p>
                </div>
                <button @click="closeCollectModal()" class="text-slate-450 hover:text-slate-700">✕</button>
            </div>
            
            <form @submit.prevent="submitCollection()" class="p-6 space-y-4">
                {{-- Info Box --}}
                <div class="p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl space-y-1 text-[11px] font-semibold text-slate-650">
                    <div>Customer: <strong class="text-slate-800" x-text="form.customer_name"></strong></div>
                    <div>Unit: <strong class="text-slate-800" x-text="form.unit_number"></strong></div>
                    <div>Outstanding: <strong class="text-indigo-750">₹<span x-text="Number(form.outstanding).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></strong></div>
                </div>

                {{-- Amount Field --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount to Collect (₹)</label>
                    <input type="number" step="0.01" x-model="form.amount" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl text-xs placeholder-slate-400 focus:outline-none transition-all">
                    <template x-if="errors.amount">
                        <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="errors.amount[0]"></span>
                    </template>
                </div>

                {{-- Payment Mode --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode</label>
                    <select x-model="form.payment_mode" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="UPI">UPI</option>
                    </select>
                    <template x-if="errors.payment_mode">
                        <span class="text-[10px] text-rose-500 font-bold block mt-1" x-text="errors.payment_mode[0]"></span>
                    </template>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" @click="closeCollectModal()" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-550 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md shadow-indigo-600/10">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function emiApp() {
    return {
        modal: {
            open: false
        },
        form: {
            booking_id: '',
            amount: '',
            payment_mode: 'Cash',
            customer_name: '',
            unit_number: '',
            outstanding: 0
        },
        toast: {
            open: false,
            message: '',
            type: 'success'
        },
        errors: {},

        openCollectModal(booking) {
            this.errors = {};
            this.form.booking_id = booking.id;
            this.form.amount = booking.outstanding;
            this.form.payment_mode = 'Cash';
            this.form.customer_name = booking.customer.name;
            this.form.unit_number = booking.unit.unit_number;
            this.form.outstanding = booking.outstanding;
            this.modal.open = true;
        },

        closeCollectModal() {
            this.modal.open = false;
        },

        submitCollection() {
            this.errors = {};
            
            fetch('{{ route('emi-collections.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    booking_id: this.form.booking_id,
                    amount: this.form.amount,
                    payment_mode: this.form.payment_mode
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.showToast(data.message, 'success');
                    this.closeCollectModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else if (data.errors) {
                    this.errors = data.errors;
                } else if (data.error) {
                    this.showToast(data.error, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Something went wrong. Please try again.', 'error');
            });
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => {
                this.toast.open = false;
            }, 3000);
        }
    };
}
</script>

</x-erp-layout>
