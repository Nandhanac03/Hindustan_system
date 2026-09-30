<x-erp-layout title="Vendor Master - HindustanERP" headerTitle="Masters > Vendor Master">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="vendorMasterApp()">

        <!-- Breadcrumb & Top Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Site Expense Management</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Vendor Master</span>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <a href="{{ route('site-expenses.index') }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2.5 text-xs font-bold text-slate-700 transition flex-shrink-0 uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Site Expenses</span>
                </a>
                <button type="button" @click="openAddModalFunc()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Vendor</span>
                </button>
            </div>
        </div>

        <!-- Flash & Error Notifications -->
        @if(session('status') || session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('status') ?? session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-800 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold uppercase tracking-wide flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:opacity-75 font-black text-sm">✕</button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Executive KPI Summary Cards (Exact Match to Picture 2 Style & Matched Ash Color) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Vendors --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-[#a38c29] p-5 flex flex-col justify-between relative overflow-hidden group hover:border-[#a38c29]/50 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.15)] cursor-default">
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 shrink-0 rounded-full bg-amber-50 flex items-center justify-center text-[#a38c29] border border-amber-200/60 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL VENDORS</span>
                    </div>
                    <span class="text-[9px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider">All</span>
                </div>
                
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-slate-900 font-mono tracking-tight block group-hover:text-slate-800 transition-colors duration-300" x-text="filteredVendors().length"></span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Registered Procurement Vendors</p>
                </div>
            </div>

            {{-- Card 2: Active Vendors --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-blue-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-blue-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.15)] cursor-default">
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/60 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">ACTIVE VENDORS</span>
                    </div>
                    <span class="text-[9px] text-blue-700 font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200 uppercase tracking-wider">Active</span>
                </div>
                
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-blue-600 font-mono tracking-tight block group-hover:text-blue-700 transition-colors duration-300" x-text="getActiveVendorsCount()"></span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Ready for Site Expense Tagging</p>
                </div>
            </div>

            {{-- Card 3: GST Registered --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)] cursor-default">
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 shrink-0 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">GST REGISTERED</span>
                    </div>
                    <span class="text-[9px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 uppercase tracking-wider">Verified</span>
                </div>
                
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-emerald-600 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300" x-text="getGstVendorsCount()"></span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium" x-text="getGstPercentage() + '% Tax Compliant'"></p>
                </div>
            </div>

            {{-- Card 4: Total Expense Billed --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-rose-500 p-5 flex flex-col justify-between relative overflow-hidden group hover:border-rose-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.15)] cursor-default">
                <div class="flex items-center justify-between mb-3 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 border border-rose-100/60 transition-all duration-300 group-hover:bg-rose-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">TOTAL BILLED</span>
                    </div>
                    <span class="text-[9px] text-rose-700 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 uppercase tracking-wider">Amount</span>
                </div>
                
                <div class="relative z-10 mt-1">
                    <span class="text-2xl font-black text-rose-600 font-mono tracking-tight block group-hover:text-rose-700 transition-colors duration-300" x-text="'₹' + numberFormat(getTotalBilledAmount())"></span>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Site Expenses Billed</p>
                </div>
            </div>
        </div>

        {{-- Search & Filter Panel (1 Row, Instant Live Filtering without Page Reload, Picture 2 Matched Reset Button) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 flex-1 w-full">
                    
                    {{-- 1. Searchable Vendor Select Dropdown (4 cols) --}}
                    <div class="relative sm:col-span-4" @click.outside="openVendorDropdown = false">
                        <button type="button" @click="openVendorDropdown = !openVendorDropdown; if(openVendorDropdown) { $nextTick(() => $refs.vendorSearchInput?.focus()); }" 
                                class="w-full h-[42px] px-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 shadow-2xs flex items-center justify-between gap-2 transition cursor-pointer text-left">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="truncate" :class="selectedVendorId ? 'text-slate-900 font-extrabold' : 'text-slate-500 font-medium'" x-text="getSelectedVendorLabel()"></span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <template x-if="selectedVendorId">
                                    <span @click.stop="selectedVendorId = ''; vendorDropdownSearch = '';" 
                                          class="w-4 h-4 rounded-full bg-slate-200 hover:bg-rose-100 hover:text-rose-600 text-slate-500 flex items-center justify-center text-[10px] font-black transition cursor-pointer" 
                                          title="Clear vendor selection">✕</span>
                                </template>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openVendorDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <!-- Searchable Dropdown Menu -->
                        <div x-show="openVendorDropdown" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute top-full left-0 mt-1.5 w-full min-w-[320px] bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-2" 
                             style="display: none;">
                            
                            <div class="relative">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input x-ref="vendorSearchInput" 
                                       type="text" 
                                       x-model="vendorDropdownSearch" 
                                       placeholder="Type vendor name, code, contact to search..." 
                                       class="w-full pl-8 pr-7 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#a38c29] focus:bg-white transition"
                                       @keydown.escape="openVendorDropdown = false">
                                <template x-if="vendorDropdownSearch">
                                    <button type="button" @click="vendorDropdownSearch = ''; $refs.vendorSearchInput.focus();" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold cursor-pointer">✕</button>
                                </template>
                            </div>

                            <div class="max-h-60 overflow-y-auto space-y-1 text-xs font-semibold custom-scrollbar divide-y divide-slate-50">
                                {{-- All Vendors Option --}}
                                <button type="button" 
                                        @click="selectedVendorId = ''; openVendorDropdown = false; vendorDropdownSearch = '';" 
                                        class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between transition cursor-pointer"
                                        :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': !selectedVendorId }">
                                    <span>All Vendors</span>
                                    <span class="text-[10px] text-slate-400 font-mono" x-text="'(' + (allVendors ? allVendors.length : 0) + ')'"></span>
                                </button>
                                
                                {{-- Filtered Vendor List Items --}}
                                <template x-for="v in getFilteredVendorsList(vendorDropdownSearch)" :key="v.id">
                                    <button type="button" 
                                            @click="selectedVendorId = v.id; openVendorDropdown = false; vendorDropdownSearch = '';" 
                                            class="w-full px-3 py-2 text-left rounded-xl hover:bg-slate-100 flex items-center justify-between gap-2 transition cursor-pointer group"
                                            :class="{ 'bg-[#a38c29]/10 text-[#8a7522] font-black': selectedVendorId == v.id }">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <template x-if="v.vendor_code">
                                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[10px] font-mono font-bold text-slate-600 shrink-0" x-text="v.vendor_code"></span>
                                                </template>
                                                <span class="truncate font-bold text-slate-800 group-hover:text-slate-900" :class="{ '!text-[#8a7522] !font-black': selectedVendorId == v.id }" x-text="v.name"></span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px] text-slate-400 font-normal mt-0.5">
                                                <span x-show="v.contact_person" x-text="v.contact_person"></span>
                                                <span x-show="v.phone" x-text="v.phone"></span>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0 flex flex-col items-end">
                                            <template x-if="v.gstin">
                                                <span class="text-[9px] font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/60" x-text="v.gstin"></span>
                                            </template>
                                            <template x-if="selectedVendorId == v.id">
                                                <svg class="w-4 h-4 text-[#a38c29] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                        </div>
                                    </button>
                                </template>
                                
                                <div x-show="getFilteredVendorsList(vendorDropdownSearch).length === 0" class="px-3 py-4 text-center text-slate-400 text-xs italic">
                                    No vendors found matching "<span class="font-bold text-slate-600" x-text="vendorDropdownSearch"></span>"
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. General Keyword Search (5 cols) --}}
                    <div class="relative sm:col-span-5">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" placeholder="Search Vendor Name, Code, Phone, GSTIN, PAN..."
                               class="w-full h-[42px] pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                        <template x-if="searchQuery">
                            <button type="button" @click="searchQuery = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs font-bold cursor-pointer">✕</button>
                        </template>
                    </div>

                    {{-- 3. GST Status Filter (3 cols) --}}
                    <div class="relative sm:col-span-3">
                        <select x-model="gstStatus"
                                class="w-full h-[42px] py-2.5 px-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs cursor-pointer">
                            <option value="">All GST Status</option>
                            <option value="with_gst">With GSTIN Only</option>
                            <option value="without_gst">Without GSTIN</option>
                        </select>
                    </div>
                </div>

                {{-- Single Unified Reset Filters Button (Picture 2 Signature Gold Gradient) --}}
                <button type="button" @click="resetFilters()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611c] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer h-[42px] w-full lg:w-auto">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>

        <!-- Master Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <style>
                #vendors-master-table thead th { border-color: #8a741f !important; }
                #vendors-master-tbody tr:nth-child(even) { background-color: #faf7eb !important; }
                #vendors-master-tbody tr:hover { background-color: #f5eed6 !important; }
            </style>

            {{-- Table Header Box (Exact Picture 2 Corporate Standard) --}}
            <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#a38c29] border border-[#a38c29]/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Registered Vendors Directory</h2>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center gap-1.5 flex-wrap">
                            <span>Showing <strong class="text-slate-800 font-bold" x-text="filteredVendors().length"></strong> vendors</span>
                            <span x-show="selectedVendorId">for <strong class="text-[#8a7522] font-bold" x-text="getSelectedVendorName()"></strong></span>
                            <span x-show="!selectedVendorId">across <strong class="text-slate-700 font-bold">all registered vendors</strong></span>
                            <span x-show="gstStatus === 'with_gst'">with <strong class="text-[#8a7522] font-bold">verified GSTIN</strong></span>
                            <span x-show="gstStatus === 'without_gst'">without <strong class="text-slate-700 font-bold">GST registration</strong></span>
                            <span x-show="searchQuery">matching "<span class="font-bold text-slate-800" x-text="searchQuery"></span>"</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                    <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Filtered Count:</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-slate-50 border border-slate-200 text-slate-800 shadow-2xs"
                          x-text="filteredVendors().length + ' Entries'">
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table id="vendors-master-table" class="w-full text-xs text-left border-collapse min-w-[1100px]">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a741f] text-[10px] font-black uppercase tracking-wider text-left">
                            <th class="px-5 py-3.5 whitespace-nowrap w-14 text-center">SL NO</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">VENDOR CODE</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">VENDOR / FIRM NAME</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">TAX IDENTIFIERS (GST / PAN)</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">CONTACT DETAILS</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">BANKING DETAILS</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center">EXPENSES BILLED</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center w-24">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="vendors-master-tbody" class="divide-y divide-slate-100 font-medium text-slate-700">
                        <template x-for="(vendor, index) in filteredVendors()" :key="vendor.id">
                            <tr class="transition hover:bg-[#faf7eb]">
                                <td class="px-5 py-4 font-bold text-slate-400 text-center" x-text="index + 1"></td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-amber-50 text-[#7a671b] border border-amber-200/80" x-text="vendor.vendor_code">
                                    </span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center font-black text-xs shrink-0 shadow-xs"
                                             x-text="(vendor.name || '').substring(0, 2).toUpperCase()">
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 font-extrabold text-xs block uppercase" x-text="vendor.name"></strong>
                                            <span class="text-[10px] text-slate-400 font-bold block" x-text="vendor.contact_person || 'REGISTERED VENDOR'"></span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-0.5 text-[11px]">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase w-7">GST:</span>
                                            <template x-if="vendor.gstin">
                                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200" x-text="vendor.gstin"></span>
                                            </template>
                                            <template x-if="!vendor.gstin">
                                                <span class="text-slate-400 italic">Unregistered</span>
                                            </template>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase w-7">PAN:</span>
                                            <template x-if="vendor.pan">
                                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200" x-text="vendor.pan"></span>
                                            </template>
                                            <template x-if="!vendor.pan">
                                                <span class="text-slate-400 italic">N/A</span>
                                            </template>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-1 text-xs">
                                        <div class="flex items-center gap-1.5 font-bold text-slate-800" x-show="vendor.phone">
                                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span x-text="vendor.phone"></span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-slate-500 font-semibold" x-show="vendor.email">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            <span x-text="vendor.email"></span>
                                        </div>
                                        <span class="text-slate-400 italic" x-show="!vendor.phone && !vendor.email">No contact specified</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-0.5 text-xs">
                                        <div class="font-bold text-slate-800" x-show="vendor.bank_name" x-text="vendor.bank_name"></div>
                                        <div class="font-mono text-[10px] text-slate-500" x-show="vendor.bank_name" x-text="'A/c: ' + (vendor.account_number || '—')"></div>
                                        <span class="text-slate-400 italic" x-show="!vendor.bank_name">No bank specified</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex flex-col items-center justify-center p-2 bg-slate-50 border border-slate-200/80 rounded-xl min-w-[90px]">
                                        <span class="text-[10px] font-black text-slate-900 font-mono" x-text="(vendor.site_expenses_count || 0) + ' Expenses'"></span>
                                        <span class="text-[9px] font-mono font-bold text-slate-500" x-text="'₹' + numberFormat(vendor.total_billed || 0)"></span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        <!-- View Modal Button (Icon Only) -->
                                        <button type="button" @click="openViewModalFunc(vendor)"
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm cursor-pointer group active:scale-95" 
                                                title="View Vendor Details">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Edit Modal Button (Icon Only) -->
                                        <button type="button" @click="openEditModalFunc(vendor)" 
                                                class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm cursor-pointer group active:scale-95" 
                                                title="Edit Vendor">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredVendors().length === 0">
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">
                                No registered vendors found matching the filter criteria.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 1. ADD / EDIT VENDOR POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="showModal = false" class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col">
                
                {{-- Executive Dark Gradient Header Matching Contractor Master --}}
                <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1">
                                TABASCO HINDUSTAN VENDOR MASTER
                            </p>
                            <h2 class="text-base font-extrabold text-white uppercase tracking-wider" x-text="isEdit ? 'Edit Vendor Details' : 'Add New Vendor'"></h2>
                        </div>
                        <button type="button" @click="showModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Form Body --}}
                <form :action="isEdit ? ('{{ url('/vendors') }}/' + currentVendor.id) : '{{ route('vendors.store') }}'" 
                      method="POST" 
                      novalidate
                      @submit="if(!validateVendor()) { $event.preventDefault(); }"
                      class="flex flex-col flex-1">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-6 pt-3.5 pb-6 space-y-3.5 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Vendor / Business Name <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="name" x-model="form.name" required placeholder="e.g. Apex Hardware & Cement Supplies"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border rounded-xl text-xs font-bold text-slate-900 outline-none transition"
                                   :class="(hasAttemptedSubmit && !form.name) ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-200 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29]'">
                            <p x-show="hasAttemptedSubmit && !form.name" class="mt-1 text-[10px] font-bold text-rose-600">The vendor name is required.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Contact Person</label>
                                <input type="text" name="contact_person" x-model="form.contact_person" placeholder="e.g. Ramesh Kumar"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="form.phone" placeholder="e.g. 9876543210"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" x-model="form.email" placeholder="e.g. vendor@domain.com"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                                <select name="is_active" x-model="form.is_active" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    <option :value="1">Active</option>
                                    <option :value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">GSTIN Number</label>
                                <input type="text" name="gstin" x-model="form.gstin" placeholder="33AABCB1234C1Z5" minlength="15" maxlength="15"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PAN Number</label>
                                <input type="text" name="pan" x-model="form.pan" placeholder="AABCB1234C" maxlength="10"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                                <input type="text" name="bank_name" x-model="form.bank_name" placeholder="e.g. State Bank of India"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Number</label>
                                <input type="text" name="account_number" x-model="form.account_number" placeholder="e.g. 10023456789"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">IFSC Code</label>
                                <input type="text" name="ifsc_code" x-model="form.ifsc_code" placeholder="e.g. SBIN0001234"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch Name</label>
                                <input type="text" name="branch" x-model="form.branch" placeholder="e.g. MG Road Branch"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Office Address</label>
                            <textarea name="address" x-model="form.address" rows="2" placeholder="Street, City, Postal Code..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a741f] text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                            <span x-text="isEdit ? 'UPDATE VENDOR' : 'SAVE VENDOR'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. VIEW VENDOR DETAIL POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="showViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="showViewModal = false"
                 class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                {{-- Header --}}
                <div class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-[#2c281b] px-6 py-5 flex-shrink-0 border-b border-amber-500/20">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1">VENDOR PROFILE</p>
                            <h2 class="text-base font-extrabold text-white uppercase tracking-wider" x-text="viewVendor?.name || 'Vendor Details'"></h2>
                            <p class="text-slate-400 text-[10px] font-mono mt-0.5" x-text="viewVendor?.vendor_code || ''"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <template x-if="viewVendor?.is_active">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider">Active</span>
                            </template>
                            <template x-if="!viewVendor?.is_active">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 uppercase tracking-wider">Inactive</span>
                            </template>
                            <button type="button" @click="showViewModal = false" class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 pt-5 pb-6 space-y-4 max-h-[70vh] overflow-y-auto bg-white text-xs">

                    {{-- Contact Info --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Contact Information</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Contact Person</p>
                                <p class="font-semibold text-slate-800" x-text="viewVendor?.contact_person || '—'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Phone</p>
                                <p class="font-semibold text-slate-800 font-mono" x-text="viewVendor?.phone || '—'"></p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Email</p>
                                <p class="font-semibold text-slate-800" x-text="viewVendor?.email || '—'"></p>
                            </div>
                            <div class="col-span-2" x-show="viewVendor?.address">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Address</p>
                                <p class="font-semibold text-slate-800" x-text="viewVendor?.address"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Tax Info --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Tax Identifiers</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">GSTIN</p>
                                <template x-if="viewVendor?.gstin">
                                    <p class="font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 inline-block" x-text="viewVendor?.gstin"></p>
                                </template>
                                <template x-if="!viewVendor?.gstin">
                                    <p class="text-slate-400 italic">Unregistered</p>
                                </template>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">PAN</p>
                                <template x-if="viewVendor?.pan">
                                    <p class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 inline-block" x-text="viewVendor?.pan"></p>
                                </template>
                                <template x-if="!viewVendor?.pan">
                                    <p class="text-slate-400 italic">N/A</p>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Banking Info --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <p class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Banking Details</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Bank Name</p>
                                <p class="font-semibold text-slate-800" x-text="viewVendor?.bank_name || '—'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Account Number</p>
                                <p class="font-mono font-bold text-slate-800" x-text="viewVendor?.account_number || '—'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">IFSC Code</p>
                                <p class="font-mono font-bold text-slate-800 uppercase" x-text="viewVendor?.ifsc_code || '—'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-0.5">Branch</p>
                                <p class="font-semibold text-slate-800" x-text="viewVendor?.branch || '—'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Billing Summary --}}
                    <div class="p-4 rounded-2xl bg-gradient-to-br from-[#2c281b] to-[#1f1c13] border border-[#a38c29]/30 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-extrabold text-[#d4af37] uppercase tracking-wider mb-1">Total Expenses Billed</p>
                            <p class="text-xl font-black text-white font-mono" x-text="'₹ ' + numberFormat(viewVendor?.total_billed || 0)"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-extrabold text-[#d4af37] uppercase tracking-wider mb-1">Expense Count</p>
                            <p class="text-xl font-black text-white font-mono" x-text="(viewVendor?.site_expenses_count || 0) + ' Bills'"></p>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                    <button type="button" @click="openEditModalFunc(viewVendor); showViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a741f] text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md cursor-pointer flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Vendor</span>
                    </button>
                    <button type="button" @click="showViewModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">CLOSE</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function vendorMasterApp() {
            return {
                showModal: false,
                showViewModal: false,
                viewVendor: null,
                isEdit: false,
                currentVendor: null,
                selectedVendorId: '',
                openVendorDropdown: false,
                vendorDropdownSearch: '',
                searchQuery: '',
                gstStatus: '',
                allVendors: @json($vendors ?? []),
                isLoading: false,

                form: {
                    name: '',
                    contact_person: '',
                    phone: '',
                    email: '',
                    gstin: '',
                    pan: '',
                    address: '',
                    bank_name: '',
                    account_number: '',
                    ifsc_code: '',
                    branch: '',
                    is_active: 1
                },
                
                hasAttemptedSubmit: false,

                validateVendor() {
                    this.hasAttemptedSubmit = true;
                    if (!this.form.name) return false;
                    return true;
                },

                getSelectedVendorName() {
                    if (!this.selectedVendorId) return 'All Vendors';
                    const v = this.allVendors.find(x => x.id == this.selectedVendorId);
                    return v ? v.name : 'All Vendors';
                },

                getSelectedVendorLabel() {
                    if (!this.selectedVendorId) return 'Search & Select Vendor...';
                    const v = this.allVendors.find(x => x.id == this.selectedVendorId);
                    if (!v) return 'Search & Select Vendor...';
                    return (v.vendor_code ? v.vendor_code + ' ' : '') + v.name;
                },

                getFilteredVendorsList(search = '') {
                    if (!this.allVendors || !Array.isArray(this.allVendors)) return [];
                    if (!search || search.trim() === '') return this.allVendors;
                    const q = search.toLowerCase().trim();
                    return this.allVendors.filter(v =>
                        (v.name && v.name.toLowerCase().includes(q)) ||
                        (v.vendor_code && v.vendor_code.toLowerCase().includes(q)) ||
                        (v.contact_person && v.contact_person.toLowerCase().includes(q)) ||
                        (v.phone && v.phone.toLowerCase().includes(q)) ||
                        (v.gstin && v.gstin.toLowerCase().includes(q))
                    );
                },

                filteredVendors() {
                    let list = this.allVendors;
                    if (this.selectedVendorId) {
                        list = list.filter(v => v.id == this.selectedVendorId);
                    }
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase().trim();
                        list = list.filter(v =>
                            (v.name && v.name.toLowerCase().includes(q)) ||
                            (v.vendor_code && v.vendor_code.toLowerCase().includes(q)) ||
                            (v.contact_person && v.contact_person.toLowerCase().includes(q)) ||
                            (v.phone && v.phone.toLowerCase().includes(q)) ||
                            (v.gstin && v.gstin.toLowerCase().includes(q)) ||
                            (v.pan && v.pan.toLowerCase().includes(q)) ||
                            (v.email && v.email.toLowerCase().includes(q)) ||
                            (v.bank_name && v.bank_name.toLowerCase().includes(q))
                        );
                    }
                    if (this.gstStatus === 'with_gst') {
                        list = list.filter(v => v.gstin && v.gstin.trim() !== '');
                    } else if (this.gstStatus === 'without_gst') {
                        list = list.filter(v => !v.gstin || v.gstin.trim() === '');
                    }
                    return list;
                },

                resetFilters() {
                    this.selectedVendorId = '';
                    this.searchQuery = '';
                    this.gstStatus = '';
                    this.vendorDropdownSearch = '';
                    this.openVendorDropdown = false;
                },

                getActiveVendorsCount() {
                    return this.filteredVendors().filter(v => v.is_active).length;
                },

                getGstVendorsCount() {
                    return this.filteredVendors().filter(v => v.gstin && v.gstin.trim() !== '').length;
                },

                getGstPercentage() {
                    const total = this.filteredVendors().length;
                    if (total === 0) return 0;
                    return Math.round((this.getGstVendorsCount() / total) * 100);
                },

                getTotalBilledAmount() {
                    return this.filteredVendors().reduce((acc, v) => acc + (parseFloat(v.total_billed) || 0), 0);
                },

                numberFormat(val) {
                    return (Math.abs(parseFloat(val)) || 0).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                async fetchVendorsAjax() {
                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams();
                        if (this.searchQuery) params.append('search', this.searchQuery);
                        if (this.gstStatus) params.append('gst_status', this.gstStatus);
                        const res = await fetch(`{{ route('vendors.index') }}?` + params.toString(), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (data && data.vendors) {
                            this.allVendors = data.vendors;
                        }
                    } catch (e) {
                        console.error('AJAX fetch error:', e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                openAddModalFunc() {
                    this.hasAttemptedSubmit = false;
                    this.isEdit = false;
                    this.currentVendor = null;
                    this.form = {
                        name: '',
                        contact_person: '',
                        phone: '',
                        email: '',
                        gstin: '',
                        pan: '',
                        address: '',
                        bank_name: '',
                        account_number: '',
                        ifsc_code: '',
                        branch: '',
                        is_active: 1
                    };
                    this.showModal = true;
                },

                openEditModalFunc(vendor) {
                    this.hasAttemptedSubmit = false;
                    this.isEdit = true;
                    this.currentVendor = vendor;
                    this.form = {
                        name: vendor.name || '',
                        contact_person: vendor.contact_person || '',
                        phone: vendor.phone || '',
                        email: vendor.email || '',
                        gstin: vendor.gstin || '',
                        pan: vendor.pan || '',
                        address: vendor.address || '',
                        bank_name: vendor.bank_name || '',
                        account_number: vendor.account_number || '',
                        ifsc_code: vendor.ifsc_code || '',
                        branch: vendor.branch || '',
                        is_active: vendor.is_active ? 1 : 0
                    };
                    this.showModal = true;
                },

                openViewModalFunc(vendor) {
                    this.viewVendor = vendor;
                    this.showViewModal = true;
                }
            };
        }
    </script>
</x-erp-layout>
