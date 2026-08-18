@extends('layouts.erp')

@section('title', 'Contractor Ledger Statement & Directory')

@section('content')
<div x-data="raBillLedger()" class="p-6 space-y-6 bg-slate-50 min-h-screen">

    <!-- ── TOP BREADCRUMB & HEADER BAR ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <a href="/" class="hover:text-slate-600 transition">HOME</a>
                <span>›</span>
                <span>CONTRACTOR OPERATIONS</span>
                <span>›</span>
                <span class="text-[#a38c29] font-bold">CONTRACTOR LEDGER VIEW</span>
            </nav>
            <h1 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Contractor Account Statement Ledger</span>
                <span class="text-xs bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold">Account Statement</span>
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('contractors.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:shadow-md cursor-pointer border border-[#a38c29]/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>⚙️ Manage Contractor Master</span>
            </a>
        </div>
    </div>

    <!-- ── SUCCESS & ERROR ALERTS ── -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-extrabold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700">✕</button>
        </div>
    @endif

    <!-- Toolbar & Filter Header -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col md:flex-row md:items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select Contractor</label>
                <select x-model="selectedLedgerContractorId"
                        class="px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-64 shadow-2xs">
                    <option value="">All Contractors</option>
                    @foreach($contractors as $cont)
                        <option value="{{ $cont->id }}">{{ $cont->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Search Particulars / Ref #</label>
                <input type="text" x-model="ledgerSearchQuery" placeholder="Search bill #, voucher #..."
                       class="px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-64 shadow-2xs">
            </div>
        </div>
    </div>

    <!-- Filtered Contractor Ledger KPI Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-blue-600 shadow-xs">
            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">TOTAL NET CLAIMS ACCRUED</span>
            <div class="text-xl font-mono font-black text-blue-900 mt-1" x-text="'₹' + numberFormat(getLedgerTotals().netClaimed)"></div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">Verified RA Bill Liability</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-emerald-600 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">TOTAL DISBURSEMENTS RELEASED</span>
            <div class="text-xl font-mono font-black text-emerald-800 mt-1" x-text="'₹' + numberFormat(getLedgerTotals().paid)"></div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">Paid Outflow via Treasury</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-rose-600 shadow-xs">
            <span class="text-[10px] font-bold text-rose-700 uppercase tracking-wider block">OUTSTANDING LEDGER BALANCE</span>
            <div class="text-xl font-mono font-black text-rose-800 mt-1" x-text="'₹' + numberFormat(getLedgerTotals().balance)"></div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">Payable Remaining</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs">
            <span class="text-[10px] font-bold text-[#8a7522] uppercase tracking-wider block">REGISTERED CONTRACTORS</span>
            <div class="text-xl font-mono font-black text-[#a38c29] mt-1">{{ count($contractors) }} Payees</div>
            <div class="text-[10px] text-slate-400 font-semibold mt-1">Master Accounts Linked</div>
        </div>
    </div>

    <!-- ── CONTRACTOR RUNNING ACCOUNT LEDGER STATEMENT TABLE ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Contractor Account Statement Ledger</span>
                <span class="text-[11px] bg-[#a38c29]/15 text-[#a38c29] px-2.5 py-0.5 rounded-full font-bold" x-text="filteredLedgerEntries().length + ' Transactions'"></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#a38c29] text-white border-b border-[#8a7522] text-[9.5px] font-black uppercase tracking-wider">
                    <tr>
                        <th class="px-3 py-3 text-left w-[95px]">DATE</th>
                        <th class="px-3 py-3 text-left w-[150px]">CONTRACTOR</th>
                        <th class="px-3 py-3 text-left w-[130px]">PROJECT / UNIT</th>
                        <th class="px-3 py-3 text-left">EVENT PARTICULARS</th>
                        <th class="px-3 py-3 text-left w-[110px]">REF / VOUCHER #</th>
                        <th class="px-3 py-3 text-right w-[95px]">GROSS (₹)</th>
                        <th class="px-3 py-3 text-right w-[90px]">CORR. (₹)</th>
                        <th class="px-3 py-3 text-right text-blue-100 w-[110px]">NET ACCRUED (₹)</th>
                        <th class="px-3 py-3 text-right text-emerald-100 w-[110px]">RELEASED (₹)</th>
                        <th class="px-3 py-3 text-right text-rose-100 w-[110px]">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-semibold">
                    <template x-for="(entry, index) in filteredLedgerEntries()" :key="index">
                        <tr class="hover:bg-slate-50 transition" :class="entry.type === 'CLAIM' ? 'bg-white' : 'bg-emerald-50/20'">
                            <td class="px-3 py-2.5 font-mono text-slate-700" x-text="entry.date_formatted"></td>
                            <td class="px-3 py-2.5 font-bold text-slate-900" x-text="entry.contractor_name"></td>
                            <td class="px-3 py-2.5">
                                <div class="text-slate-900 font-bold text-[10.5px]" x-text="entry.project_name"></div>
                                <div class="text-[9px] text-slate-500" x-show="entry.unit_name" x-text="'Unit: ' + entry.unit_name"></div>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2">
                                    <span x-show="entry.type === 'CLAIM'" class="px-1.5 py-0.2 rounded text-[8.5px] font-black bg-blue-100 text-blue-900 uppercase">VERIFIED CLAIM</span>
                                    <span x-show="entry.type === 'DISBURSEMENT'" class="px-1.5 py-0.2 rounded text-[8.5px] font-black bg-emerald-100 text-emerald-900 uppercase">PAYMENT RELEASE</span>
                                    <span class="text-slate-800 text-[11px]" x-text="entry.particulars"></span>
                                </div>
                            </td>
                            <td class="px-3 py-2.5 font-mono font-bold text-slate-700" x-text="entry.ref_no"></td>
                            <td class="px-3 py-2.5 text-right font-mono text-slate-800" x-text="entry.gross_amount > 0 ? '₹' + numberFormat(entry.gross_amount) : '—'"></td>
                            <td class="px-3 py-2.5 text-right font-mono text-amber-700" x-text="entry.correction_amount > 0 ? '-₹' + numberFormat(entry.correction_amount) : '—'"></td>
                            <td class="px-3 py-2.5 text-right font-mono font-black text-blue-900 bg-blue-50/30" x-text="entry.net_approved > 0 ? '₹' + numberFormat(entry.net_approved) : '—'"></td>
                            <td class="px-3 py-2.5 text-right font-mono font-black text-emerald-800 bg-emerald-50/30" x-text="entry.paid_amount > 0 ? '₹' + numberFormat(entry.paid_amount) : '—'"></td>
                            <td class="px-3 py-2.5 text-right">
                                <template x-if="entry.voucher_id">
                                    <a :href="'/vouchers/' + entry.voucher_id + '/payment-voucher-print'" target="_blank"
                                       class="px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[9.5px] font-bold inline-flex items-center gap-1">
                                        <span>Voucher</span>
                                    </a>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredLedgerEntries().length === 0">
                        <td colspan="10" class="px-4 py-8 text-center text-slate-400 italic">
                            No ledger transactions found matching the filter criteria.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function raBillLedger() {
    return {
        ledgerSearchQuery: '',
        selectedLedgerContractorId: '',
        allLedgerEntries: @json($allLedgerEntries ?? []),

        filteredLedgerEntries() {
            let entries = this.allLedgerEntries;
            if (this.selectedLedgerContractorId) {
                entries = entries.filter(e => e.contractor_id == this.selectedLedgerContractorId);
            }
            if (this.ledgerSearchQuery) {
                const q = this.ledgerSearchQuery.toLowerCase();
                entries = entries.filter(e =>
                    (e.contractor_name && e.contractor_name.toLowerCase().includes(q)) ||
                    (e.particulars && e.particulars.toLowerCase().includes(q)) ||
                    (e.ra_bill_number && e.ra_bill_number.toLowerCase().includes(q)) ||
                    (e.ref_no && e.ref_no.toLowerCase().includes(q))
                );
            }
            return entries;
        },

        getLedgerTotals() {
            const entries = this.filteredLedgerEntries();
            const netClaimed = entries.reduce((acc, e) => acc + (parseFloat(e.net_approved) || 0), 0);
            const paid = entries.reduce((acc, e) => acc + (parseFloat(e.paid_amount) || 0), 0);
            return {
                netClaimed: netClaimed,
                paid: paid,
                balance: netClaimed - paid
            };
        },

        numberFormat(val) {
            return (parseFloat(val) || 0).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    };
}
</script>
@endsection
