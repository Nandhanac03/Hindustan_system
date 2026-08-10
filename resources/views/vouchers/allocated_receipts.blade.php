<x-erp-layout>
    <x-slot:title>Receipt Allocated to Others</x-slot:title>
    <x-slot:headerTitle>Receipt Allocated to Others</x-slot:headerTitle>

    <div class="max-w-[1600px] mx-auto space-y-6" x-data="allocatedReceiptsPage()" x-init="init()">

        {{-- ── BREADCRUMB ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Finance &amp; Accounting</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Receipt Allocated to Others</span>
            </div>
            <a href="{{ route('vouchers.receipt.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-sm border border-slate-800 self-start sm:self-auto">
                <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14h6M9 10h6M7 3h10a2 2 0 012 2v14l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                </svg>
                Go to Allocation Workspace
            </a>
        </div>

        {{-- ── KPI CARDS ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-[#a38c29] shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#a38c29]">Total Allocated Receipts</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ number_format($totalAllocated) }}</h3>
                    <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">Vouchers Posted &amp; Distributed</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-emerald-500 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Total Allocated Amount</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1">&#8377;{{ number_format($totalAllocatedAmount, 2) }}</h3>
                    <span class="text-[10px] text-emerald-700 font-bold block mt-0.5">Funds Distributed to Partners &amp; Others</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 border-l-4 border-l-blue-600 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700">Showing (Filtered)</span>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-1" x-text="filteredReceipts().length"></h3>
                    <span class="text-[10px] text-blue-700 font-bold block mt-0.5">Matches Current Filters</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                </div>
            </div>
        </div>

        {{-- ── FILTER BAR + TABLE ── --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 bg-[#a38c29] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-white">Allocated Receipt Register</h3>
                    <p class="text-xs text-white/70 font-medium mt-0.5">All receipts that have been split and distributed as allocation vouchers</p>
                </div>
                <span class="px-3 py-1.5 rounded-xl bg-white/20 text-white text-xs font-black uppercase tracking-wider border border-white/30">
                    Fully Allocated
                </span>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" x-model="searchQuery" placeholder="Search receipt #, customer..."
                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 hover:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition-all shadow-xs">
                    </div>

                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <select x-model="filterProject"
                                class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-300 hover:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition-all cursor-pointer shadow-xs">
                            <option value="">All Projects</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->name }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        <select x-model="filterMode"
                                class="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-300 hover:border-[#a38c29] rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#a38c29] transition-all cursor-pointer shadow-xs">
                            <option value="">All Payment Modes</option>
                            <option value="Cheque">Cheque</option>
                            <option value="NEFT/RTGS">NEFT/RTGS</option>
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="DD">DD</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#a38c29] text-white text-[11px] font-black uppercase tracking-widest">
                            <th class="px-5 py-3.5">#</th>
                            <th class="px-5 py-3.5">Receipt Ref</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Customer</th>
                            <th class="px-5 py-3.5">Company Bank Account</th>
                            <th class="px-5 py-3.5">Project / Unit</th>
                            <th class="px-5 py-3.5 text-right">Amount</th>
                            <th class="px-5 py-3.5 text-center">Mode</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <template x-for="(r, idx) in paginatedReceipts()" :key="r.id">
                            <tr class="hover:bg-[#a38c29]/5 transition-all duration-150 border-l-4 border-l-emerald-500">
                                <td class="px-5 py-4 font-black text-slate-400 text-[11px]" x-text="(currentPage - 1) * perPage + idx + 1"></td>
                                <td class="px-5 py-4 font-mono font-bold text-slate-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div>
                                        <span x-text="r.ref"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-500" x-text="r.date"></td>
                                <td class="px-5 py-4 font-bold text-slate-900" x-text="r.customer_name"></td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded bg-[#a38c29]/20 text-[#a38c29] text-[9px] font-black flex items-center justify-center shrink-0"
                                              x-text="(r.company_bank_account_name || 'G').charAt(0)"></span>
                                        <span class="font-bold text-slate-800" x-text="r.company_bank_account_name"></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-bold text-slate-800" x-text="r.project_name"></span>
                                    <span class="text-slate-400 mx-1">&#8226;</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold" x-text="r.unit_name"></span>
                                </td>
                                <td class="px-5 py-4 font-mono font-black text-slate-950 text-right text-sm"
                                    x-text="'&#8377;' + parseFloat(r.amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></td>
                                <td class="px-5 py-4 text-center">
                                    <span :class="
                                        r.payment_mode && r.payment_mode.toLowerCase() === 'cash' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' :
                                        r.payment_mode && r.payment_mode.toLowerCase() === 'cheque' ? 'bg-amber-100 text-amber-800 border border-amber-200' :
                                        'bg-blue-100 text-blue-800 border border-blue-200'
                                    " class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider whitespace-nowrap inline-block"
                                       x-text="r.payment_mode || 'N/A'"></span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                        &#10003; Allocated
                                    </span>
                                </td>
                            </tr>
                        </template>

                        <template x-if="filteredReceipts().length === 0">
                            <tr>
                                <td colspan="9" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-700 font-black text-sm">No Allocated Receipts Found</p>
                                            <p class="text-slate-500 font-medium text-xs mt-1">No receipts match your current filters, or none have been allocated yet.</p>
                                        </div>
                                        <a href="{{ route('vouchers.receipt.create') }}"
                                           class="px-4 py-2 bg-[#a38c29] text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-[#8d7923] transition shadow-sm">
                                            Go to Allocation Workspace
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs text-slate-500 font-semibold">
                    Showing
                    <span class="font-black text-slate-800" x-text="Math.min((currentPage - 1) * perPage + 1, filteredReceipts().length)"></span>
                    to
                    <span class="font-black text-slate-800" x-text="Math.min(currentPage * perPage, filteredReceipts().length)"></span>
                    of
                    <span class="font-black text-slate-800" x-text="filteredReceipts().length"></span> allocated receipts
                </div>
                <div class="flex items-center gap-2">
                    <button @click="currentPage = Math.max(1, currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="px-3 py-1.5 rounded-lg text-xs font-black bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-[#a38c29] transition disabled:opacity-40 disabled:cursor-not-allowed">
                        Prev
                    </button>
                    <span class="text-xs font-black text-slate-700 px-2"
                          x-text="'Page ' + currentPage + ' / ' + Math.max(1, Math.ceil(filteredReceipts().length / perPage))"></span>
                    <button @click="currentPage = Math.min(Math.ceil(filteredReceipts().length / perPage), currentPage + 1)"
                            :disabled="currentPage >= Math.ceil(filteredReceipts().length / perPage)"
                            class="px-3 py-1.5 rounded-lg text-xs font-black bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-[#a38c29] transition disabled:opacity-40 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
    function allocatedReceiptsPage() {
        return {
            allReceipts:   @json($receipts->values()),
            searchQuery:   '',
            filterProject: '',
            filterMode:    '',
            currentPage:   1,
            perPage:       20,

            init() {
                this.$watch('searchQuery',   () => { this.currentPage = 1; });
                this.$watch('filterProject', () => { this.currentPage = 1; });
                this.$watch('filterMode',    () => { this.currentPage = 1; });
            },

            filteredReceipts() {
                return this.allReceipts.filter(r => {
                    const q = this.searchQuery.toLowerCase();
                    const matchesSearch  = !q ||
                        (r.ref           || '').toLowerCase().includes(q) ||
                        (r.customer_name || '').toLowerCase().includes(q);
                    const matchesProject = !this.filterProject || r.project_name === this.filterProject;
                    const matchesMode    = !this.filterMode    || r.payment_mode === this.filterMode;
                    return matchesSearch && matchesProject && matchesMode;
                });
            },

            paginatedReceipts() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredReceipts().slice(start, start + this.perPage);
            }
        };
    }
    </script>
</x-erp-layout>
