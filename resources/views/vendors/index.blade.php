<x-erp-layout title="Vendor Master - HindustanERP" headerTitle="Masters > Vendor Master">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="vendorMasterApp()">

        <!-- Breadcrumb & Top Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-4">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Masters</span>
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

        <!-- Executive KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Card 1: Total Registered Vendors --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-[#a38c29] border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>Total Vendors</span>
                    <div class="w-6 h-6 rounded-md bg-amber-50 text-[#a38c29] border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    {{ $totalVendors }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">Registered Procurement Vendors</div>
            </div>

            {{-- Card 2: Active Vendors --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-blue-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>Active Vendors</span>
                    <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    {{ $activeVendors }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">Ready for Site Expense Tagging</div>
            </div>

            {{-- Card 3: GST Compliance --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-emerald-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>GST Registered</span>
                    <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    {{ $gstinCount }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">
                    {{ $totalVendors > 0 ? round(($gstinCount / $totalVendors) * 100) : 0 }}% Tax Compliant
                </div>
            </div>

            {{-- Card 4: Total Expense Billed --}}
            <div class="text-left p-3.5 rounded-2xl border border-l-[6px] border-l-amber-500 border-y-slate-200/80 border-r-slate-200/80 bg-white transition-all duration-300 space-y-1 hover:-translate-y-1.5 hover:shadow-md cursor-default group">
                <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-widest text-slate-600">
                    <span>Total Expense Billed</span>
                    <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-base font-black font-mono text-slate-900">
                    ₹{{ number_format($totalBilledAmount, 2) }}
                </div>
                <div class="text-[10px] font-medium text-slate-400">Site Expenses Billed</div>
            </div>
        </div>

        {{-- Search & Filter Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all">
            <form method="GET" action="{{ route('vendors.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1 w-full">
                    {{-- General Search --}}
                    <div class="relative group sm:col-span-2">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Search Vendor Name, Code, Phone, GSTIN, PAN..." autocomplete="off"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    </div>

                    {{-- GST Status Filter --}}
                    <div>
                        <select name="gst_status" class="w-full py-2.5 px-3 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 focus:outline-none transition-all shadow-2xs">
                            <option value="">All GST Status</option>
                            <option value="with_gst" {{ request('gst_status') === 'with_gst' ? 'selected' : '' }}>With GSTIN Only</option>
                            <option value="without_gst" {{ request('gst_status') === 'without_gst' ? 'selected' : '' }}>Without GSTIN</option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons: Filter & Reset --}}
                <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#a38c29]/20 transition-all uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('vendors.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2.5 text-xs font-extrabold uppercase tracking-wider transition-all">
                        <svg class="h-3.5 w-3.5 text-slate-600 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Master Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <style>
                #vendors-master-table thead th { border-color: #8a741f !important; }
                #vendors-master-tbody tr:nth-child(even) { background-color: #faf7eb !important; }
                #vendors-master-tbody tr:hover { background-color: #f5eed6 !important; }
            </style>
            <div class="overflow-x-auto custom-scrollbar">
                <table id="vendors-master-table" class="w-full text-xs text-left border-collapse min-w-[1100px]">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a741f] text-[10px] font-black uppercase tracking-wider text-left">
                            <th class="px-5 py-3.5 whitespace-nowrap">SL NO</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">VENDOR CODE</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">VENDOR / FIRM NAME</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">TAX IDENTIFIERS (GST / PAN)</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">CONTACT DETAILS</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">BANKING DETAILS</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center">EXPENSES BILLED</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="vendors-master-tbody" class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($vendors as $index => $vendor)
                            <tr class="transition hover:bg-[#faf7eb]">
                                <td class="px-5 py-4 font-bold text-slate-400">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-amber-50 text-[#7a671b] border border-amber-200/80">
                                        {{ $vendor->vendor_code }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#a38c29] text-white flex items-center justify-center font-black text-xs shrink-0 shadow-xs">
                                            {{ strtoupper(substr($vendor->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 font-extrabold text-xs block uppercase">{{ $vendor->name }}</strong>
                                            @if($vendor->contact_person)
                                                <span class="text-[10px] text-slate-400 font-bold block">{{ $vendor->contact_person }}</span>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-bold block">REGISTERED VENDOR</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-0.5 text-[11px]">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase w-7">GST:</span>
                                            @if($vendor->gstin)
                                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $vendor->gstin }}</span>
                                            @else
                                                <span class="text-slate-400 italic">Unregistered</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase w-7">PAN:</span>
                                            @if($vendor->pan)
                                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $vendor->pan }}</span>
                                            @else
                                                <span class="text-slate-400 italic">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-1 text-xs">
                                        @if($vendor->phone)
                                            <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                                <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span>{{ $vendor->phone }}</span>
                                            </div>
                                        @endif
                                        @if($vendor->email)
                                            <div class="flex items-center gap-1.5 text-slate-500 font-semibold">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span>{{ $vendor->email }}</span>
                                            </div>
                                        @endif
                                        @if(!$vendor->phone && !$vendor->email)
                                            <span class="text-slate-400 italic">No contact specified</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-0.5 text-xs">
                                        @if($vendor->bank_name)
                                            <div class="font-bold text-slate-800">{{ $vendor->bank_name }}</div>
                                            <div class="font-mono text-[10px] text-slate-500">A/c: {{ $vendor->account_number ?: '—' }}</div>
                                        @else
                                            <span class="text-slate-400 italic">No bank specified</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex flex-col items-center justify-center p-2 bg-slate-50 border border-slate-200/80 rounded-xl min-w-[90px]">
                                        <span class="text-[10px] font-black text-slate-900 font-mono">{{ $vendor->site_expenses_count ?? 0 }} Expenses</span>
                                        <span class="text-[9px] font-mono font-bold text-slate-500">₹{{ number_format($vendor->total_billed ?? 0, 2) }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- Edit Modal Button -->
                                        <button type="button" @click="openEditModalFunc({{ json_encode($vendor) }})" 
                                                class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-xs cursor-pointer" 
                                                title="Edit Vendor">
                                            <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" @click="confirmDeleteFunc({{ json_encode($vendor) }})" 
                                                class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 transition inline-flex items-center justify-center shadow-xs cursor-pointer" 
                                                title="Delete / Deactivate Vendor">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">
                                    No registered vendors found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
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
                            <p class="text-[#a38c29] text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29]"></span>
                                TABASCO HINDUSTAN · VENDOR MASTER
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
                      class="flex flex-col flex-1">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-6 pt-3.5 pb-6 space-y-3.5 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Vendor / Business Name <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="name" x-model="form.name" required placeholder="e.g. Apex Hardware & Cement Supplies"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
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
        <!-- 2. DELETE VENDOR POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="showDeleteModal = false" class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-md flex flex-col p-6 space-y-4">
                <div class="flex items-center gap-3 text-rose-600">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Remove Vendor</h3>
                        <p class="text-xs text-slate-500">Confirm vendor deletion</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600">
                    Are you sure you want to remove <strong class="text-slate-900" x-text="deleteVendorItem?.name"></strong>?
                    If this vendor has linked site expense vouchers, their status will be set to Inactive to protect ledger audit records.
                </p>

                <form :action="'{{ url('/vendors') }}/' + (deleteVendorItem?.id || '')" method="POST" class="flex justify-end gap-2 pt-2">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" @click="showDeleteModal = false" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-extrabold rounded-xl uppercase transition cursor-pointer">
                        CANCEL
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold rounded-xl uppercase transition shadow-md shadow-rose-600/20 cursor-pointer">
                        CONFIRM DELETE
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function vendorMasterApp() {
            return {
                showModal: false,
                showDeleteModal: false,
                isEdit: false,
                currentVendor: null,
                deleteVendorItem: null,
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

                openAddModalFunc() {
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

                confirmDeleteFunc(vendor) {
                    this.deleteVendorItem = vendor;
                    this.showDeleteModal = true;
                }
            };
        }
    </script>
</x-erp-layout>
