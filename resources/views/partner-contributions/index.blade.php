<x-erp-layout title="Partner Contribution" headerTitle="Partner Contribution">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="partnerContributionsApp()">
        
        {{-- Flash Notifications --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-black">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black">✕</button>
            </div>
        @endif

        {{-- Top Page Header Card --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center shrink-0 border border-[#a38c29]/20 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                        <span>Finance</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-[#a38c29] font-bold">Partner Contribution</span>
                    </div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Partner Contribution</h1>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Record partner contribution made through company bank account</p>
                </div>
            </div>

            <div>
                <button type="button" @click="openAddContributionModal()"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#a38c29] via-[#947e24] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md border border-[#a38c29]/40 cursor-pointer">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Contribution</span>
                </button>
            </div>
        </div>

        {{-- Instant Interactive Filter Toolbar (Units Screen Style with Icons) --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                {{-- Project Filter --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Project</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0h-3.5M9 7h1m5 0h1M9 11h1m5 0h1M9 15h1m5 0h1M9 19h1m5 0h1"/></svg>
                        </div>
                        <select x-model="filters.project_id"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all appearance-none">
                            <option value="">All Projects</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Partner Filter --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Partner</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select x-model="filters.partner_id"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all appearance-none">
                            <option value="">Select Partner</option>
                            @foreach($partners as $part)
                                <option value="{{ $part->id }}">{{ $part->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- From Date Filter --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">From Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.from_date"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                    </div>
                </div>

                {{-- To Date Filter --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">To Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" x-model="filters.to_date"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all">
                    </div>
                </div>

                {{-- Reset Filters Button --}}
                <div>
                    <button type="button" @click="resetFilters()"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-5 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95 cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Filters</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Contributions Table Card (Units Screen Header Theme & Striped Rows) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <style>
                #contributions-table thead th {
                    border-color: #8a7522 !important;
                    background-color: #a38c29 !important;
                    color: #ffffff !important;
                }
                #contributions-tbody tr:nth-child(even) {
                    background-color: #F6F3E9 !important;
                }
                #contributions-tbody tr:hover {
                    background-color: #ebe5d0 !important;
                }
            </style>
            
            <div class="overflow-x-auto">
                <table id="contributions-table" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] font-extrabold uppercase text-[10px] tracking-wider text-center">
                            <th class="py-3 px-4 border border-[#8a7522]">DATE</th>
                            <th class="py-3 px-4 border border-[#8a7522]">PARTNER</th>
                            <th class="py-3 px-4 border border-[#8a7522]">PROJECT</th>
                            <th class="py-3 px-4 border border-[#8a7522]">AMOUNT</th>
                            <th class="py-3 px-4 border border-[#8a7522]">BANK ACCOUNT</th>
                            <th class="py-3 px-4 border border-[#8a7522]">MODE</th>
                            <th class="py-3 px-4 border border-[#8a7522]">REFERENCE NO.</th>
                            <th class="py-3 px-4 border border-[#8a7522]">REMARKS</th>
                            <th class="py-3 px-4 border border-[#8a7522]">STATUS</th>
                            <th class="py-3 px-4 border border-[#8a7522]">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="contributions-tbody" class="divide-y divide-slate-100 text-slate-700 font-medium">
                        <template x-for="item in paginatedContributions" :key="item.id">
                            <tr class="transition-colors border-b border-slate-200/60">
                                <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-800 text-center" x-text="item.formatted_date"></td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900" x-text="item.partner_name"></td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-700" x-text="item.project_name"></td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-black text-slate-900 font-mono" x-text="'₹ ' + item.formatted_amount"></td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600 font-medium" x-text="item.bank_name_formatted"></td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600 font-semibold" x-text="item.payment_mode"></td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-slate-500" x-text="item.reference_no"></td>
                                <td class="py-3.5 px-4 max-w-xs truncate text-slate-500" :title="item.remarks" x-text="item.remarks"></td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider" x-text="item.status"></span>
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" 
                                            @click="selectedContribution = item; openViewModal = true;"
                                            class="p-1.5 text-slate-400 hover:text-[#a38c29] hover:bg-[#a38c29]/10 rounded-lg transition" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <template x-if="filteredContributions.length === 0">
                            <tr>
                                <td colspan="10" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="font-bold text-slate-600 text-xs uppercase tracking-wider">No partner contributions match selected filters</p>
                                        <p class="text-xs text-slate-400">Try adjusting your filter selection or click Reset Filters.</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-semibold">
                <div class="text-[11px] font-black uppercase text-slate-500 tracking-wider">
                    SHOWING <span x-text="filteredContributions.length > 0 ? ((currentPage - 1) * perPage + 1) : 0"></span> TO <span x-text="Math.min(currentPage * perPage, filteredContributions.length)"></span> OF <span x-text="filteredContributions.length"></span> CONTRIBUTIONS
                </div>

                {{-- Interactive Pagination Controls (Always Visible) --}}
                <div class="flex items-center gap-1.5">
                    <button type="button" 
                            @click="prevPage()" 
                            :disabled="currentPage === 1" 
                            :class="currentPage === 1 ? 'opacity-60 cursor-not-allowed text-slate-400 bg-white' : 'hover:bg-slate-200 hover:text-slate-800 cursor-pointer text-slate-700 bg-white'"
                            class="px-3 py-1 border border-slate-200 rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-2xs">
                        PREV
                    </button>

                    <template x-for="p in totalPages" :key="p">
                        <button type="button" 
                                @click="goToPage(p)" 
                                :class="currentPage === p ? 'bg-[#a38c29] text-white shadow-2xs font-black' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 font-bold'"
                                class="px-3 py-1 rounded-lg text-xs transition cursor-pointer" 
                                x-text="p">
                        </button>
                    </template>

                    <button type="button" 
                            @click="nextPage()" 
                            :disabled="currentPage === totalPages" 
                            :class="currentPage === totalPages ? 'opacity-60 cursor-not-allowed text-slate-400 bg-white' : 'hover:bg-slate-200 hover:text-slate-800 cursor-pointer text-slate-700 bg-white'"
                            class="px-3 py-1 border border-slate-200 rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-2xs">
                        NEXT
                    </button>
                </div>
            </div>
        </div>

        {{-- ── ADD PARTNER CONTRIBUTION MODAL ── --}}
        <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.outside="openAddModal = false" class="bg-white rounded-3xl shadow-2xl border-0 w-full max-w-3xl overflow-hidden transform transition-all">
                
                {{-- Modal Header Bar (#232018 Dark Charcoal + Gold Badge Style) --}}
                <div class="bg-[#232018] px-6 py-5 border-0 flex items-center justify-between shrink-0">
                    <div>
                        <span class="text-[#a38c29] text-[10px] font-extrabold uppercase tracking-widest block mb-1">FINANCE & CAPITAL</span>
                        <h2 class="text-base sm:text-lg font-extrabold text-white leading-tight">Add Partner Contribution</h2>
                    </div>
                    <button type="button" @click="openAddModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-sm cursor-pointer">✕</button>
                </div>

                {{-- Form Body --}}
                <form method="POST" action="{{ route('partner-contributions.store') }}" class="p-5 space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        {{-- Left Column: Basic Details (4 Fields) --}}
                        <div class="space-y-3.5">
                            <h3 class="text-xs font-black text-[#a38c29] uppercase tracking-wider border-b border-slate-100 pb-1.5">Basic Details</h3>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Partner <span class="text-rose-500">*</span></label>
                                <select name="partner_id" x-model="modalForm.partner_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition">
                                    <option value="" disabled>Select Partner</option>
                                    @foreach($partners as $part)
                                        <option value="{{ $part->id }}">{{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Project <span class="text-rose-500">*</span></label>
                                <select name="project_id" x-model="modalForm.project_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition">
                                    <option value="" disabled>Select Project</option>
                                    @foreach($projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Contribution Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="contribution_date" x-model="modalForm.contribution_date" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Amount <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-black text-xs">₹</span>
                                    <input type="number" step="0.01" name="amount" x-model="modalForm.amount" placeholder="5,00,000" required class="w-full pl-7 bg-slate-50 border border-slate-200 text-slate-900 text-xs rounded-xl px-3 py-2 font-mono font-black focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition">
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Payment Details (4 Fields) --}}
                        <div class="space-y-3.5">
                            <h3 class="text-xs font-black text-[#a38c29] uppercase tracking-wider border-b border-slate-100 pb-1.5">Payment Details</h3>

                            {{-- Company Bank Account (Searchable Select Box) --}}
                            <div class="relative" @click.outside="modalBankDropdownOpen = false">
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Company Bank Account <span class="text-rose-500">*</span>
                                </label>
                                
                                {{-- Hidden Input for Form Submission --}}
                                <input type="hidden" name="company_bank_account_id" :value="modalForm.company_bank_account_id" required>

                                {{-- Dropdown Trigger Button --}}
                                <div @click="modalBankDropdownOpen = !modalBankDropdownOpen; if(modalBankDropdownOpen) { modalBankSearch = ''; $nextTick(() => $refs.modalBankSearchInput?.focus()); }"
                                     class="w-full h-9 px-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 hover:border-[#a38c29]/60 rounded-xl text-xs font-bold text-slate-800 cursor-pointer flex items-center justify-between transition shadow-2xs">
                                    <template x-if="selectedBankObj">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="px-2 py-0.5 bg-[#a38c29]/10 text-[#8a7522] rounded font-bold text-[10px]" x-text="selectedBankObj.bank_name"></span>
                                            <span class="font-bold text-slate-800 truncate" x-text="selectedBankObj.account_name || selectedBankObj.bank_name"></span>
                                            <span class="text-slate-500 text-[10px] font-mono shrink-0" x-text="'(A/C: ' + (selectedBankObj.account_number || '—') + ')'"></span>
                                        </div>
                                    </template>
                                    <template x-if="!selectedBankObj">
                                        <span class="text-slate-400 font-medium">Select Company Bank Account...</span>
                                    </template>
                                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0" :class="modalBankDropdownOpen ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>

                                {{-- Dropdown Popover List --}}
                                <div x-show="modalBankDropdownOpen" 
                                     x-transition
                                     class="absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden max-h-56 flex flex-col"
                                     style="display: none;">
                                    
                                    {{-- Search Input inside Popover --}}
                                    <div class="p-2 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
                                        <div class="relative">
                                            <input type="text" 
                                                   x-model="modalBankSearch" 
                                                   x-ref="modalBankSearchInput"
                                                   placeholder="Search bank name, account no, branch..." 
                                                   class="w-full pl-7 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium focus:outline-none focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29]">
                                            <svg class="w-3 h-3 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 0 0114 0z"/></svg>
                                        </div>
                                    </div>

                                    {{-- Results List --}}
                                    <div class="overflow-y-auto divide-y divide-slate-100 max-h-48">
                                        <template x-for="acc in filteredModalBankAccounts" :key="acc.id">
                                            <div @click="selectModalBank(acc.id)"
                                                 class="px-3 py-2 hover:bg-[#a38c29]/5 cursor-pointer flex items-center justify-between text-xs transition-colors"
                                                 :class="modalForm.company_bank_account_id == acc.id ? 'bg-[#a38c29]/10 font-bold' : ''">
                                                <div class="flex flex-col min-w-0 pr-2">
                                                    <div class="flex items-center gap-1.5 truncate">
                                                        <span class="font-bold text-slate-900" x-text="acc.bank_name"></span>
                                                        <span class="text-slate-500 font-medium truncate" x-text="'— ' + (acc.account_name || 'Account')"></span>
                                                    </div>
                                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5 truncate" x-text="'A/C: ' + (acc.account_number || '—') + (acc.branch_name ? ' • ' + acc.branch_name : '')"></div>
                                                </div>
                                                <div class="text-right font-mono shrink-0">
                                                    <div class="text-[8px] text-slate-400 uppercase font-sans font-bold tracking-wider">Current Balance</div>
                                                    <div class="font-bold text-slate-800 text-[11px]" x-text="formatCurrency(acc.balance)"></div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="filteredModalBankAccounts.length === 0">
                                            <div class="p-3 text-center text-xs text-slate-400 italic">No matching company bank accounts found.</div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Payment Mode <span class="text-rose-500">*</span></label>
                                <select name="payment_mode_id" x-model="modalForm.payment_mode_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition">
                                    <option value="" disabled>Select Payment Mode</option>
                                    @foreach($paymentModes as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Reference No.</label>
                                <input type="text" name="reference_no" x-model="modalForm.reference_no" placeholder="e.g. TRX-001256 or CHQ-000789" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-2 font-mono focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Remarks</label>
                                <textarea name="remarks" x-model="modalForm.remarks" rows="1" placeholder="Partner contribution towards project development" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs font-semibold rounded-xl px-3 py-1.5 focus:bg-white focus:ring-2 focus:ring-[#a38c29] focus:border-[#a38c29] transition resize-none"></textarea>
                            </div>
                        </div>

                    </div>

                    {{-- Live Bank Balance Summary Card (Matching Cheque Realization & Process Clearance UI) --}}
                    <div class="p-3.5 bg-slate-50/90 rounded-2xl border border-slate-200/90 space-y-2 text-xs font-semibold shadow-2xs" x-show="selectedBankObj">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-bold">Selected Bank Account Balance (<span x-text="selectedBankObj ? (selectedBankObj.bank_name + (selectedBankObj.account_number ? ' ' + selectedBankObj.account_number : '')) : '—'"></span>)</span>
                            <span class="font-mono font-bold text-indigo-600" x-text="formatCurrency(selectedBankBalance)">₹ 0.00</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-bold">Contribution Amount</span>
                            <span class="font-mono font-bold text-emerald-600" x-text="'+ ' + formatCurrency(parsedModalAmount)">+ ₹ 0.00</span>
                        </div>

                        <div class="pt-2 border-t border-slate-200/90 flex items-center justify-between text-xs font-black">
                            <span class="uppercase tracking-wider text-slate-900">BANK BALANCE AFTER CONTRIBUTION</span>
                            <span class="font-mono text-slate-950 text-sm font-black" x-text="formatCurrency(bankBalanceAfterContribution)">₹ 0.00</span>
                        </div>
                    </div>

                    {{-- Accounting Banner Note --}}
                    <div class="p-3 rounded-xl bg-[#FAF0D7]/60 border border-[#a38c29]/30 flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-full bg-[#a38c29] text-white flex items-center justify-center shrink-0 text-[11px] font-bold mt-0.5">ℹ</div>
                        <p class="text-[11.5px] text-[#8a7522] leading-relaxed font-semibold">
                            This contribution will be recorded as a debit to the selected company bank account and a credit to the partner's capital / contribution account.
                        </p>
                    </div>

                    {{-- Modal Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-2.5 border-t border-slate-100">
                        <button type="button" @click="openAddModal = false" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider shadow-md transition cursor-pointer">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── VIEW DETAIL MODAL ── --}}
        <div x-show="openViewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.outside="openViewModal = false" class="bg-white rounded-3xl shadow-2xl border-0 w-full max-w-lg overflow-hidden transform transition-all">
                {{-- Modal Header Bar (#232018 Dark Charcoal + Gold Badge Style) --}}
                <div class="bg-[#232018] px-6 py-5 border-0 flex items-center justify-between shrink-0">
                    <div>
                        <span class="text-[#a38c29] text-[10px] font-extrabold uppercase tracking-widest block mb-1">PARTNER CONTRIBUTION STATEMENT</span>
                        <h2 class="text-base sm:text-lg font-extrabold text-white leading-tight">Contribution Details</h2>
                    </div>
                    <button type="button" @click="openViewModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-sm cursor-pointer">✕</button>
                </div>

                <div class="p-6 space-y-4 text-xs" x-if="selectedContribution">
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase text-[10px]">Date</span>
                            <span class="font-bold text-slate-800 text-sm" x-text="selectedContribution?.formatted_date"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block uppercase text-[10px]">Amount</span>
                            <span class="font-mono font-black text-[#a38c29] text-base" x-text="'₹ ' + selectedContribution?.formatted_amount"></span>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Partner</span>
                            <span class="font-bold text-slate-800" x-text="selectedContribution?.partner_name"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Project</span>
                            <span class="font-semibold text-slate-800" x-text="selectedContribution?.project_name"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Bank Account</span>
                            <span class="font-semibold text-slate-800" x-text="selectedContribution?.bank_name_formatted"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Payment Mode</span>
                            <span class="font-semibold text-slate-800" x-text="selectedContribution?.payment_mode"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Reference No.</span>
                            <span class="font-mono font-bold text-slate-800" x-text="selectedContribution?.reference_no"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500 font-semibold">Status</span>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold uppercase text-[10px]" x-text="selectedContribution?.status"></span>
                        </div>
                        <div class="py-1">
                            <span class="text-slate-500 font-semibold block mb-1">Remarks</span>
                            <p class="text-slate-700 italic bg-slate-50 p-2.5 rounded-xl border border-slate-100 font-medium" x-text="selectedContribution?.remarks"></p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="button" @click="openViewModal = false" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                        Close
                    </button>
                </div>
        </div>
    </div>

    <script>
        function partnerContributionsApp() {
            const firstProjId = @js($projects->first()?->id ?? '');

            return {
                openAddModal: false,
                openViewModal: false,
                selectedContribution: null,
                projectsList: @js($projects),
                partnersList: @js($partners),
                paymentModesList: @js($paymentModes),
                bankAccountsList: [
                    @foreach($companyBankAccounts as $bank)
                    {
                        id: '{{ $bank->id }}',
                        bank_name: @json($bank->bank_name),
                        account_name: @json($bank->account_name ?? $bank->bank_name),
                        account_number: @json($bank->account_number ?? ''),
                        branch_name: @json($bank->branch_name ?? ''),
                        account_type: @json($bank->account_type ?? 'Current Account'),
                        balance: {{ (float)($bank->current_balance ?? $bank->opening_balance ?? 0) }}
                    },
                    @endforeach
                ],
                modalBankDropdownOpen: false,
                modalBankSearch: '',
                filters: {
                    project_id: firstProjId,
                    partner_id: '',
                    from_date: '',
                    to_date: '',
                    search: ''
                },
                modalForm: {
                    partner_id: '',
                    project_id: firstProjId,
                    contribution_date: '{{ date('Y-m-d') }}',
                    amount: '',
                    company_bank_account_id: '',
                    payment_mode_id: @js($paymentModes->firstWhere('code', 'BANK_TRANSFER')?->id ?? $paymentModes->first(fn($m) => str_contains(strtolower($m->name), 'bank transfer'))?->id ?? $paymentModes->first()?->id ?? ''),
                    reference_no: '',
                    remarks: ''
                },
                allContributions: @js($allContributions),
                currentPage: 1,
                perPage: 10,

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredContributions.length / this.perPage));
                },

                get paginatedContributions() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredContributions.slice(start, start + this.perPage);
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                goToPage(p) {
                    if (p >= 1 && p <= this.totalPages) {
                        this.currentPage = p;
                    }
                },

                get filteredModalBankAccounts() {
                    if (!this.modalBankSearch || !this.modalBankSearch.trim()) {
                        return this.bankAccountsList;
                    }
                    const q = this.modalBankSearch.toLowerCase().trim();
                    return this.bankAccountsList.filter(b => 
                        (b.bank_name && b.bank_name.toLowerCase().includes(q)) ||
                        (b.account_name && b.account_name.toLowerCase().includes(q)) ||
                        (b.account_number && String(b.account_number).toLowerCase().includes(q)) ||
                        (b.branch_name && b.branch_name.toLowerCase().includes(q)) ||
                        (b.account_type && b.account_type.toLowerCase().includes(q))
                    );
                },

                get selectedBankObj() {
                    return this.bankAccountsList.find(b => String(b.id) === String(this.modalForm.company_bank_account_id)) || null;
                },

                get selectedBankBalance() {
                    return this.selectedBankObj ? (parseFloat(this.selectedBankObj.balance) || 0) : 0;
                },

                get parsedModalAmount() {
                    return parseFloat(this.modalForm.amount) || 0;
                },

                get bankBalanceAfterContribution() {
                    return this.selectedBankBalance + this.parsedModalAmount;
                },

                selectModalBank(bankId) {
                    this.modalForm.company_bank_account_id = bankId;
                    this.modalBankDropdownOpen = false;
                    this.modalBankSearch = '';
                },

                formatCurrency(value) {
                    return '₹ ' + new Intl.NumberFormat('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(value || 0);
                },

                openAddContributionModal() {
                    const defaultProj = this.filters.project_id || (this.projectsList.length > 0 ? this.projectsList[0].id : '');
                    const defaultPart = this.filters.partner_id || (this.partnersList.length > 0 ? this.partnersList[0].id : '');
                    const defaultBank = this.bankAccountsList.length > 0 ? this.bankAccountsList[0].id : '';
                    
                    const bankTransferMode = this.paymentModesList.find(m => 
                        (m.code && m.code === 'BANK_TRANSFER') || 
                        (m.name && m.name.toLowerCase().includes('bank transfer'))
                    );
                    const defaultModeId = bankTransferMode ? bankTransferMode.id : (this.paymentModesList.length > 0 ? this.paymentModesList[0].id : '');

                    this.modalForm = {
                        partner_id: defaultPart,
                        project_id: defaultProj,
                        contribution_date: new Date().toISOString().split('T')[0],
                        amount: '',
                        company_bank_account_id: defaultBank,
                        payment_mode_id: defaultModeId,
                        reference_no: '',
                        remarks: ''
                    };
                    this.modalBankDropdownOpen = false;
                    this.modalBankSearch = '';

                    this.openAddModal = true;
                },

                get filteredContributions() {
                    return this.allContributions.filter(item => {
                        if (this.filters.project_id && String(item.project_id) !== String(this.filters.project_id)) {
                            return false;
                        }
                        if (this.filters.partner_id && String(item.partner_id) !== String(this.filters.partner_id)) {
                            return false;
                        }
                        if (this.filters.from_date && item.contribution_date < this.filters.from_date) {
                            return false;
                        }
                        if (this.filters.to_date && item.contribution_date > this.filters.to_date) {
                            return false;
                        }
                        if (this.filters.search) {
                            const q = this.filters.search.toLowerCase();
                            const ref = (item.reference_no || '').toLowerCase();
                            const rem = (item.remarks || '').toLowerCase();
                            const part = (item.partner_name || '').toLowerCase();
                            const proj = (item.project_name || '').toLowerCase();
                            const mode = (item.payment_mode || '').toLowerCase();
                            if (!ref.includes(q) && !rem.includes(q) && !part.includes(q) && !proj.includes(q) && !mode.includes(q)) {
                                return false;
                            }
                        }
                        return true;
                    });
                },

                resetFilters() {
                    this.currentPage = 1;
                    this.filters.project_id = this.projectsList.length > 0 ? this.projectsList[0].id : '';
                    this.filters.partner_id = '';
                    this.filters.from_date = '';
                    this.filters.to_date = '';
                    this.filters.search = '';
                }
            };
        }
    </script>

</x-erp-layout>
