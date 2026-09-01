@extends('layouts.erp')

@section('title', 'Contractor Ledger Statement & Directory')

@section('content')
<div x-data="contractorLedgerView()" class="space-y-6">

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
                <span>Manage Contractor Master</span>
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
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
            <!-- Searchable Contractor Select Dropdown -->
            <div class="relative w-full" x-data="{ open: false, search: '' }" @click.outside="open = false">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select Contractor</label>
                
                <button type="button" @click="open = !open" 
                        class="px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none w-full shadow-2xs flex items-center justify-between gap-2 hover:border-[#a38c29] transition">
                    <span class="truncate" x-text="getSelectedContractorName()"></span>
                    <div class="flex items-center gap-1 shrink-0">
                        <template x-if="selectedLedgerContractorId">
                            <span @click.stop="selectedLedgerContractorId = ''; search = '';" class="p-0.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-200 transition" title="Clear selection">✕</span>
                        </template>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <!-- Searchable Dropdown Menu -->
                <div x-show="open" x-transition.opacity.duration.150ms 
                     class="absolute top-full left-0 mt-1 w-full bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                     style="display: none;">
                    
                    <div class="relative">
                        <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="search" placeholder="Type contractor name to filter..." 
                               class="w-full pl-8 pr-7 py-1.5 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#a38c29] focus:bg-white transition"
                               @keydown.escape="open = false" autofocus>
                        <template x-if="search">
                            <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                        </template>
                    </div>

                    <div class="max-h-56 overflow-y-auto space-y-0.5 text-xs font-semibold">
                        <button type="button" @click="selectedLedgerContractorId = ''; open = false; search = '';" 
                                class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': !selectedLedgerContractorId }">
                            <span>All Contractors</span>
                            <span class="text-[10px] text-slate-400 font-normal" x-text="'(' + (contractorsList ? contractorsList.length : 0) + ')'"></span>
                        </button>
                        
                        <template x-for="cont in getFilteredContractorsList(search)" :key="cont.id">
                            <button type="button" @click="selectedLedgerContractorId = cont.id; open = false; search = '';" 
                                    class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition"
                                    :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': selectedLedgerContractorId == cont.id }">
                                <span class="truncate" x-text="cont.name"></span>
                                <span class="text-[9px] text-slate-400 font-mono" x-text="cont.gstin || cont.type || ''"></span>
                            </button>
                        </template>
                        
                        <div x-show="getFilteredContractorsList(search).length === 0" class="px-3 py-3 text-center text-slate-400 text-xs italic">
                            No contractors found.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Search Particulars / Ref # -->
            <div class="w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Search Particulars / Ref #</label>
                <div class="relative w-full">
                    <input type="text" x-model="ledgerSearchQuery" placeholder="Search bill #, voucher #, project..."
                           class="w-full px-3.5 py-2.5 pr-7 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-[#a38c29] focus:outline-none shadow-2xs">
                    <template x-if="ledgerSearchQuery">
                        <button type="button" @click="ledgerSearchQuery = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold">✕</button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtered Contractor Ledger KPI Summary -->
    <!-- Filtered Contractor Ledger KPI Summary (Upgraded with Icons & Hover Effects) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Net Claims Accrued -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-blue-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">TOTAL NET CLAIMS ACCRUED</span>
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-blue-900 tracking-tight group-hover:text-blue-800 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().netClaimed)"></div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">Verified RA Bill Liability</div>
            </div>
        </div>

        <!-- Card 2: Total Disbursements Released -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-emerald-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">TOTAL DISBURSEMENTS RELEASED</span>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-emerald-800 tracking-tight group-hover:text-emerald-700 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().paid)"></div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">Paid Outflow via Treasury</div>
            </div>
        </div>

        <!-- Card 3: Outstanding Ledger Balance -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-rose-600 border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-700">OUTSTANDING LEDGER BALANCE</span>
                <div class="w-7 h-7 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 transition-all duration-300 group-hover:bg-rose-600 group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5 5 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5 5 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-rose-800 tracking-tight group-hover:text-rose-700 transition-colors" x-text="'₹' + numberFormat(getLedgerTotals().balance)"></div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">Payable Remaining</div>
            </div>
        </div>

        <!-- Card 4: Registered Contractors -->
        <div class="bg-white p-5 rounded-2xl border border-y border-r border-l-[6px] border-l-[#a38c29] border-slate-200/90 shadow-xs flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md cursor-default">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-[#8a7522]">REGISTERED CONTRACTORS</span>
                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-xl font-mono font-black text-[#a38c29] tracking-tight group-hover:text-[#8a7522] transition-colors">{{ count($contractors) }} Payees</div>
                <div class="text-[10px] text-slate-400 font-bold mt-1.5 pt-1.5 border-t border-slate-100">Master Accounts Linked</div>
            </div>
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
                        <th class="px-3 py-3 text-left w-[95px]">GROSS (₹)</th>
                        <th class="px-3 py-3 text-left w-[90px]">CORR. (₹)</th>
                        <th class="px-3 py-3 text-left text-blue-100 w-[110px]">NET ACCRUED (₹)</th>
                        <th class="px-3 py-3 text-left text-emerald-100 w-[110px]">RELEASED (₹)</th>
                        <th class="px-3 py-3 text-left text-rose-100 w-[110px]">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px] font-bold">
                    <template x-for="(entry, index) in filteredLedgerEntries()" :key="index">
                        <tr class="hover:bg-amber-50/20 transition font-bold" :class="entry.type === 'CLAIM' ? 'bg-white' : 'bg-emerald-50/20'">
                            <td class="px-3 py-2.5 font-mono text-slate-800 font-bold" x-text="entry.date_formatted"></td>
                            <td class="px-3 py-2.5 font-black text-slate-900" x-text="entry.contractor_name"></td>
                            <td class="px-3 py-2.5">
                                <div class="text-slate-900 font-black text-[10.5px]" x-text="entry.project_name"></div>
                                <div class="text-[9px] text-slate-600 font-bold" x-show="entry.unit_name" x-text="'Unit: ' + entry.unit_name"></div>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2">
                                    <span x-show="entry.type === 'CLAIM'" class="px-1.5 py-0.2 rounded text-[8.5px] font-black bg-blue-100 text-blue-900 uppercase">VERIFIED CLAIM</span>
                                    <span x-show="entry.type === 'DISBURSEMENT'" class="px-1.5 py-0.2 rounded text-[8.5px] font-black bg-emerald-100 text-emerald-900 uppercase">PAYMENT RELEASE</span>
                                    <span class="text-slate-900 font-bold text-[11px]" x-text="entry.particulars"></span>
                                </div>
                            </td>
                            <td class="px-3 py-2.5 font-mono font-extrabold text-slate-800" x-text="entry.ref_no"></td>
                            <td class="px-3 py-2.5 text-left font-mono font-extrabold text-slate-900" x-text="entry.gross_amount > 0 ? '₹' + numberFormat(entry.gross_amount) : '—'"></td>
                            <td class="px-3 py-2.5 text-left font-mono font-extrabold text-amber-700" x-text="entry.correction_amount > 0 ? '-₹' + numberFormat(entry.correction_amount) : '—'"></td>
                            <td class="px-3 py-2.5 text-left font-mono font-black text-blue-900 bg-blue-50/30" x-text="entry.net_approved > 0 ? '₹' + numberFormat(entry.net_approved) : '—'"></td>
                            <td class="px-3 py-2.5 text-left font-mono font-black text-emerald-800 bg-emerald-50/30" x-text="entry.paid_amount > 0 ? '₹' + numberFormat(entry.paid_amount) : '—'"></td>
                            <td class="px-3 py-2.5 text-left">
                                <template x-if="entry.voucher_id">
                                    <a :href="'/vouchers/' + entry.voucher_id + '/payment-voucher-print'" target="_blank"
                                       class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[9.5px] font-black inline-flex items-center gap-1 shadow-2xs border border-emerald-500/50">
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
        contractorsList: @json($contractors ?? []),

        getSelectedContractorName() {
            if (!this.selectedLedgerContractorId) return 'All Contractors';
            const c = this.contractorsList.find(x => x.id == this.selectedLedgerContractorId);
            return c ? c.name : 'All Contractors';
        },

        getFilteredContractorsList(search = '') {
            if (!search) return this.contractorsList;
            const q = search.toLowerCase().trim();
            return this.contractorsList.filter(c => (c.name || '').toLowerCase().includes(q));
        },

        filteredLedgerEntries() {
            let entries = this.allLedgerEntries;
            if (this.selectedLedgerContractorId) {
                entries = entries.filter(e => e.contractor_id == this.selectedLedgerContractorId);
            }
            if (this.ledgerSearchQuery) {
                const q = this.ledgerSearchQuery.toLowerCase().trim();
                entries = entries.filter(e =>
                    (e.contractor_name && e.contractor_name.toLowerCase().includes(q)) ||
                    (e.project_name && e.project_name.toLowerCase().includes(q)) ||
                    (e.unit_name && e.unit_name.toLowerCase().includes(q)) ||
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
