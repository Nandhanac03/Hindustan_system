<x-erp-layout title="Contractor Master - HindustanERP" headerTitle="Masters > Contractor Master">

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="contractorDirectoryApp()">

        <!-- Breadcrumb & Top Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
            <div class="text-xs font-bold text-slate-400 tracking-wide uppercase flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Home</a>
                <span class="text-slate-300">›</span>
                <span>Masters</span>
                <span class="text-slate-300">›</span>
                <span class="text-[#a38c29] font-black">Contractor Master</span>
            </div>

            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <a href="{{ route('expenses.ra-bills.ledger') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-black uppercase tracking-wider transition border border-slate-250 shadow-xs">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Contractor Ledgers</span>
                </a>
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

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Executive KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Registered Contractors --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Total Contractors</span>
                    <h3 class="text-2xl font-mono font-black text-slate-900 tracking-tight mt-1">
                        {{ $totalContractors ?? count($suppliers) }}
                    </h3>
                    <span class="text-[10px] text-[#a38c29] font-bold mt-0.5 block">
                        Registered Master Payees
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center border border-[#a38c29]/20 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            {{-- Card 2: Liability Ledger Accounts Linked --}}
            <div class="bg-white rounded-2xl p-5 border border-[#a38c29]/40 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest block">Ledger Integration</span>
                    <h3 class="text-2xl font-mono font-black text-slate-900 tracking-tight mt-1">
                        {{ $totalContractors ?? count($suppliers) }}
                    </h3>
                    <span class="text-[10px] text-[#a38c29] font-bold mt-0.5 block">
                        SUP-ACC Payables Linked
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center border border-[#a38c29]/20 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            {{-- Card 3: GSTIN Compliance --}}
            <div class="bg-white rounded-2xl p-5 border border-[#a38c29]/40 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest block">GST Compliance</span>
                    <h3 class="text-2xl font-mono font-black text-slate-900 tracking-tight mt-1">
                        {{ $gstinCount ?? 0 }}
                    </h3>
                    <span class="text-[10px] text-[#a38c29] font-bold mt-0.5 block">
                        {{ ($totalContractors ?? count($suppliers)) > 0 ? round((($gstinCount ?? 0) / ($totalContractors ?? count($suppliers))) * 100) : 0 }}% GST Registered
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center border border-[#a38c29]/20 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>

            {{-- Card 4: RA Billing Total Work --}}
            <div class="bg-white rounded-2xl p-5 border border-[#a38c29]/40 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-[#a38c29] uppercase tracking-widest block">Total Work Billed</span>
                    <h3 class="text-2xl font-mono font-black text-slate-900 tracking-tight mt-1">
                        ₹{{ number_format($totalBillsAmount ?? 0, 2) }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center border border-[#a38c29]/20 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition-all">
            <form method="GET" action="{{ route('contractors.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                {{-- Pro Search Input --}}
                <div class="relative sm:col-span-1 group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contractor name, GST, phone..."
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                </div>

                {{-- Tax Status Filter --}}
                <div class="relative">
                    <select name="gst_status" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                        <option value="">All Tax Statuses</option>
                        <option value="with_gst" {{ request('gst_status') === 'with_gst' ? 'selected' : '' }}>GST Registered Only</option>
                        <option value="without_gst" {{ request('gst_status') === 'without_gst' ? 'selected' : '' }}>Unregistered / Non-GST</option>
                    </select>
                </div>

                {{-- Sort Filter --}}
                <div class="relative">
                    <select name="sort_by" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs">
                        <option value="name_asc" {{ request('sort_by') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort_by') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="newest" {{ request('sort_by') === 'newest' ? 'selected' : '' }}>Newest Registered</option>
                    </select>
                </div>
            </form>

            <div class="flex items-center gap-2 flex-shrink-0">
                @if(request()->hasAny(['search', 'gst_status', 'sort_by']))
                    <a href="{{ route('contractors.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a741f] hover:from-[#8a741f] hover:to-[#736017] px-4 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 uppercase tracking-wider group active:scale-95">
                        <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset</span>
                    </a>
                @endif
                <button type="button" @click="openAddModalFunc()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#a38c29] hover:bg-[#8a741f] px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-[#a38c29]/20 transition-all duration-200 flex-shrink-0 uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Contractor</span>
                </button>
            </div>
        </div>

        <!-- Master Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">

            <!-- Master Table -->
            <style>
                #contractors-master-table thead th { border-color: #8a741f !important; }
                #contractors-master-tbody tr:nth-child(even) { background-color: #faf7eb !important; }
                #contractors-master-tbody tr:hover { background-color: #f5eed6 !important; }
            </style>
            <div class="overflow-x-auto custom-scrollbar">
                <table id="contractors-master-table" class="w-full text-xs text-left border-collapse min-w-[1100px]">
                    <thead>
                        <tr class="bg-[#a38c29] text-white border-b border-[#8a741f] text-[10px] font-black uppercase tracking-wider text-left">
                            <th class="px-5 py-3.5 whitespace-nowrap">SL NO</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">LEDGER CODE</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">CONTRACTOR / FIRM NAME</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">TAX IDENTIFIERS (GST / PAN)</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">CONTACT DETAILS</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">OFFICE ADDRESS</th>
                            <th class="px-5 py-3.5 text-center whitespace-nowrap">RA BILLS & BILLED</th>
                            <th class="px-5 py-3.5 text-right whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="contractors-master-tbody" class="divide-y divide-slate-100 text-slate-800 font-medium">
                        @forelse($suppliers as $index => $sup)
                            <tr class="transition-colors">
                                <td class="px-5 py-4 font-mono font-bold text-slate-400 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-mono text-[10px] text-[#a38c29] font-black bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-md uppercase whitespace-nowrap inline-block">
                                        {{ $sup->linkedAccount?->code ?? 'SUP-ACC-xxxx' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#a38c29] to-[#8a741f] text-white flex items-center justify-center text-xs font-black shadow-xs flex-shrink-0">
                                            {{ strtoupper(substr($sup->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900 text-xs uppercase tracking-wide">{{ $sup->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 mt-0.5">CONTRACTOR PAYEE</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest w-8">GST:</span>
                                            @if($sup->gstin)
                                                <span class="font-mono font-bold text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">{{ $sup->gstin }}</span>
                                            @else
                                                <span class="text-slate-400 italic text-[10px]">Unregistered</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest w-8">PAN:</span>
                                            @if($sup->pan)
                                                <span class="font-mono font-bold text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">{{ $sup->pan }}</span>
                                            @else
                                                <span class="text-slate-400 italic text-[10px]">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="space-y-0.5">
                                        @if($sup->phone)
                                            <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span>{{ $sup->phone }}</span>
                                            </div>
                                        @endif
                                        @if($sup->email)
                                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 font-semibold">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span>{{ $sup->email }}</span>
                                            </div>
                                        @endif
                                        @if(!$sup->phone && !$sup->email)
                                            <span class="text-slate-400 italic text-[10px]">No contact details</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 max-w-xs truncate text-slate-600 font-semibold" title="{{ $sup->address }}">
                                    {{ $sup->address ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex flex-col items-center justify-center p-2 bg-slate-50 border border-slate-200/80 rounded-xl min-w-[90px]">
                                        <span class="text-[10px] font-black text-slate-900 font-mono">{{ $sup->ra_bills_count ?? 0 }} RA Bills</span>
                                        <span class="text-[9px] font-mono font-bold text-slate-500">₹{{ number_format($sup->total_billed ?? 0, 2) }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <!-- View Details Modal Button -->
                                        <button type="button" @click="openViewModalFunc({{ json_encode($sup) }})" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a741f] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="View Contractor Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Edit Modal Button -->
                                        <button type="button" @click="openEditModalFunc({{ json_encode($sup) }})" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="Edit Contractor">
                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete Confirmation Modal Button -->
                                        <button type="button" @click="openDeleteModalFunc({{ json_encode($sup) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 transition inline-flex items-center justify-center shadow-sm cursor-pointer" title="Delete Contractor">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">
                                    No registered contractors found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 1. ADD NEW CONTRACTOR POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="openAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openAddModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">CONTRACTOR MASTER</p>
                            <h2 class="text-lg font-extrabold text-white">Add New Contractor</h2>
                        </div>
                        <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('contractors.store') }}" method="POST" @submit="submitAdd($event)" class="flex flex-col flex-1">
                    @csrf
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Contractor / Firm Name <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="name" x-model="name" required placeholder="e.g. BuildRight Constructions Pvt Ltd"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.name"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="errors.name"></p></template>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="phone" placeholder="e.g. 9876543210"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" x-model="email" placeholder="e.g. contact@builder.com"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">GSTIN Number</label>
                                <input type="text" name="gstin" x-model="gstin" placeholder="33AABCB1234C1Z5" minlength="15" maxlength="15"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                                <template x-if="errors.gstin"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="errors.gstin"></p></template>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PAN Number</label>
                                <input type="text" name="pan" x-model="pan" placeholder="AABCB1234C" maxlength="10"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition uppercase">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Office Address</label>
                            <textarea name="address" x-model="address" rows="2" placeholder="Street, City, Postal Code..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none"></textarea>
                        </div>


                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openAddModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a741f] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">SAVE CONTRACTOR</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. EDIT CONTRACTOR POPUP MODAL -->
        <!-- ========================================== -->
        <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openEditModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-lg flex flex-col">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#c5a059]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#c5a059] text-[10px] font-semibold uppercase tracking-widest mb-1">CONTRACTOR MASTER</p>
                            <h2 class="text-lg font-extrabold text-white">Edit Contractor Details</h2>
                        </div>
                        <button type="button" @click="openEditModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form :action="'{{ url('/contractors') }}/' + editForm.id" method="POST" class="flex flex-col flex-1">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-white">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Contractor / Firm Name <span class="text-rose-500 font-bold">*</span></label>
                            <input type="text" name="name" x-model="editForm.name" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#c5a059]/40 focus:border-[#c5a059] outline-none transition">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" x-model="editForm.phone" placeholder="e.g. 9876543210"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#c5a059]/40 focus:border-[#c5a059] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" x-model="editForm.email" placeholder="e.g. contact@builder.com"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#c5a059]/40 focus:border-[#c5a059] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">GSTIN Number</label>
                                <input type="text" name="gstin" x-model="editForm.gstin" placeholder="33AABCB1234C1Z5" minlength="15" maxlength="15"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#c5a059]/40 focus:border-[#c5a059] outline-none transition uppercase">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">PAN Number</label>
                                <input type="text" name="pan" x-model="editForm.pan" placeholder="AABCB1234C" maxlength="10"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#c5a059]/40 focus:border-[#c5a059] outline-none transition uppercase">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Office Address</label>
                            <textarea name="address" x-model="editForm.address" rows="2" placeholder="Street, City, Postal Code..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#c5a059]/40 focus:border-[#c5a059] outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3 bg-slate-50">
                        <button type="button" @click="openEditModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-[#c5a059] hover:bg-[#b38e42] text-white text-xs font-bold rounded-xl uppercase transition shadow-sm cursor-pointer">UPDATE CONTRACTOR</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 3. VIEW CONTRACTOR PROFILE MODAL -->
        <!-- ========================================== -->
        <div x-show="openViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openViewModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md flex flex-col">
                {{-- Dark Header --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#c5a059]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-[#c5a059] text-[10px] font-semibold uppercase tracking-widest mb-1">CONTRACTOR MASTER</p>
                            <h2 class="text-lg font-extrabold text-white" x-text="viewContractor.name"></h2>
                        </div>
                        <button type="button" @click="openViewModal = false" class="text-slate-400 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-3.5 text-xs bg-white">
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">LEDGER CODE</span>
                        <span class="font-mono font-bold text-[#a38c29]" x-text="viewContractor.linked_account ? viewContractor.linked_account.code : 'SUP-ACC-xxxx'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">CONTRACTOR NAME</span>
                        <span class="font-bold text-slate-900" x-text="viewContractor.name"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">GSTIN</span>
                        <span class="font-mono font-bold text-slate-900" x-text="viewContractor.gstin || 'Unregistered'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PAN</span>
                        <span class="font-mono font-bold text-slate-900" x-text="viewContractor.pan || 'N/A'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">PHONE</span>
                        <span class="font-bold text-slate-900" x-text="viewContractor.phone || 'N/A'"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2.5">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">EMAIL</span>
                        <span class="font-bold text-slate-900" x-text="viewContractor.email || 'N/A'"></span>
                    </div>
                    <div class="flex justify-between pb-1">
                        <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">ADDRESS</span>
                        <span class="font-semibold text-slate-800 text-right max-w-[200px]" x-text="viewContractor.address || 'N/A'"></span>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                    <div class="flex items-center gap-2">
                        <a :href="'{{ route('expenses.ra-bills.ledger') }}?contractor_id=' + viewContractor.id" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase rounded-xl transition inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Open Ledger</span>
                        </a>
                        <button type="button" @click="openViewModal = false" class="px-5 py-2.5 bg-[#a38c29] hover:bg-[#8a741f] text-white text-xs font-bold uppercase rounded-xl transition shadow-sm cursor-pointer">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 4. DELETE CONTRACTOR CONFIRMATION MODAL -->
        <!-- ========================================== -->
        <div x-show="openDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" style="display: none;" x-transition.opacity>
            <div @click.away="openDeleteModal = false" class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md flex flex-col p-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider mb-1">Delete Contractor?</h3>
                <p class="text-xs text-slate-500 font-semibold mb-6">
                    Are you sure you want to delete <span class="font-bold text-slate-800" x-text="contractorToDelete.name"></span>? This will deactivate the contractor payee profile.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl uppercase transition cursor-pointer">CANCEL</button>
                    <form :action="'{{ url('/contractors') }}/' + contractorToDelete.id" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl uppercase transition shadow-md cursor-pointer">YES, DELETE</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        function contractorDirectoryApp() {
            return {
                openAddModal: false,
                openEditModal: false,
                openViewModal: false,
                openDeleteModal: false,
                name: '',
                phone: '',
                email: '',
                gstin: '',
                pan: '',
                address: '',
                errors: {},
                editForm: {
                    id: '',
                    name: '',
                    phone: '',
                    email: '',
                    gstin: '',
                    pan: '',
                    address: ''
                },
                viewContractor: {},
                contractorToDelete: {},

                openAddModalFunc() {
                    this.name = '';
                    this.phone = '';
                    this.email = '';
                    this.gstin = '';
                    this.pan = '';
                    this.address = '';
                    this.errors = {};
                    this.openAddModal = true;
                },

                openEditModalFunc(sup) {
                    this.editForm = {
                        id: sup.id,
                        name: sup.name || '',
                        phone: sup.phone || '',
                        email: sup.email || '',
                        gstin: sup.gstin || '',
                        pan: sup.pan || '',
                        address: sup.address || ''
                    };
                    this.openEditModal = true;
                },

                openViewModalFunc(sup) {
                    this.viewContractor = sup;
                    this.openViewModal = true;
                },

                openDeleteModalFunc(sup) {
                    this.contractorToDelete = sup;
                    this.openDeleteModal = true;
                },

                submitAdd(e) {
                    this.errors = {};
                    if (!this.name || !this.name.trim()) {
                        e.preventDefault();
                        this.errors.name = 'The contractor name field is required.';
                        return false;
                    }
                    if (this.gstin && this.gstin.trim().length !== 15) {
                        e.preventDefault();
                        this.errors.gstin = 'GSTIN must be exactly 15 alphanumeric characters.';
                        return false;
                    }
                    return true;
                }
            };
        }
    </script>
</x-erp-layout>
