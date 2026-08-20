<x-erp-layout title="Cancellation Charges & Additional Work" headerTitle="Cancellation Charges & Additional Work">
    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{ activeTab: 'cancellation', search: '', status: '' }">
        
        {{-- Header & Breadcrumb (Treasury Style) --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Cancellation Charges & Additional Work</h1>
                <p class="text-xs text-slate-500 mt-1">Home / Sales & Property / Cancellation & Additional Work</p>
            </div>
            <div>
                {{-- Action buttons if any --}}
            </div>
        </div>

        {{-- Summary Cards (Treasury Style) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card 1: Total Cancellation Charges --}}
            <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.2)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Cancellation Charges</span>
                    </div>
                    <!-- <span class="px-2 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">Collected</span> -->
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-[#a38c29]">₹{{ number_format($cancellationCharges->sum('cancellation_fee'), 2) }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total Accumulated</p>
                </div>
            </div>
            
            {{-- Card 2: Total Additional Work --}}
            <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Additional Work</span>
                    </div>
                    <!-- <span class="px-2 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">Completed</span> -->
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-emerald-600">₹{{ number_format($additionalWorks->sum('amount'), 2) }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total Accumulated</p>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-1 flex mb-4">
            <button @click="activeTab = 'cancellation'" 
                    class="px-6 py-3 text-[11px] font-black uppercase tracking-wider transition-all rounded-lg"
                    :class="activeTab === 'cancellation' ? 'bg-[#a38c29] text-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'">
                Cancellation Charges
            </button>
            <button @click="activeTab = 'additional'" 
                    class="px-6 py-3 text-[11px] font-black uppercase tracking-wider transition-all rounded-lg"
                    :class="activeTab === 'additional' ? 'bg-[#a38c29] text-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'">
                Additional Work
            </button>
        </div>

        {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                {{-- Search Input --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Customer/Unit/Sale No..." 
                           x-model="search"
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    
                    {{-- Clear Button --}}
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <button type="button" x-show="search" @click="search = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select x-model="status"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            {{-- Reset Filters Button --}}
            <button type="button" @click="search = ''; status = '';"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-6 py-2.5 text-xs font-extrabold text-slate-700 shadow-sm shadow-slate-200/50 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-slate-500 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
        </div>

        {{-- Main Content Area --}}
        
        {{-- Cancellation Charges Table --}}
        <div x-show="activeTab === 'cancellation'" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Cancellation Charges Directory
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all cancellation charges for cancelled sales.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">#</th>
                            <th class="px-5 py-3">Sale / Booking No.</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Cancellation Date</th>
                            <th class="px-5 py-3 text-right">Cancellation Fee (₹)</th>
                            <th class="px-5 py-3">Reason</th>
                            <th class="px-5 py-3 text-center w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cancellationCharges as $index => $charge)
                            @php
                                $chargeUnitDisplay = 'N/A';
                                if ($charge->saleUnits && $charge->saleUnits->count() > 0) {
                                    $chargeUnitDisplay = $charge->saleUnits->map(function($su) {
                                        return $su->unit ? $su->unit->formatted_name : '';
                                    })->filter()->implode(', ');
                                } elseif ($charge->unit) {
                                    $chargeUnitDisplay = $charge->unit->formatted_name;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition group"
                                x-show="(search === '' || 
                                    '{{ addslashes($charge->sale_number ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($charge->customer->name ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($chargeUnitDisplay) }}'.toLowerCase().includes(search.toLowerCase())
                                ) && (status === '' || '{{ addslashes($charge->status ?? '') }}'.toLowerCase() === status)">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $charge->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $chargeUnitDisplay }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->updated_at ? $charge->updated_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="px-5 py-3 text-right text-xs font-black text-[#a38c29]">
                                    ₹{{ number_format((float)($charge->cancellation_fee ?? 0), 2) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->cancellation_reason ?? 'Customer Request' }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($charge->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-rose-50 text-rose-700 border border-rose-100">{{ $charge->status }}</span>
                                    @elseif($charge->status === 'active')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $charge->status }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">{{ $charge->status ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">No cancellation charges found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Controls --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900">{{ count($cancellationCharges) > 0 ? 1 : 0 }}</span> TO 
                    <span class="text-slate-900">{{ count($cancellationCharges) }}</span> OF 
                    <span class="text-slate-900">{{ count($cancellationCharges) }}</span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">PREV</button>
                    <span class="inline-flex items-center gap-1">
                        <button type="button" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#a38c29] text-white border border-[#a38c29] shadow-2xs">1</button>
                    </span>
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">NEXT</button>
                </div>
            </div>
        </div>

        {{-- Additional Work Table --}}
        <div x-show="activeTab === 'additional'" style="display:none;" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Additional Work Directory
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all additional work associated with sales.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">#</th>
                            <th class="px-5 py-3">Sale / Booking No.</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Unit No.</th>
                            <th class="px-5 py-3">Work Description</th>
                            <th class="px-5 py-3 text-right">Amount (₹)</th>
                            <th class="px-5 py-3">Work Date</th>
                            <th class="px-5 py-3 text-center w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($additionalWorks as $index => $work)
                            @php
                                $workUnitDisplay = 'N/A';
                                if ($work->sale && $work->sale->saleUnits && $work->sale->saleUnits->count() > 0) {
                                    $workUnitDisplay = $work->sale->saleUnits->map(function($su) {
                                        return $su->unit ? $su->unit->formatted_name : '';
                                    })->filter()->implode(', ');
                                } elseif ($work->sale && $work->sale->unit) {
                                    $workUnitDisplay = $work->sale->unit->formatted_name;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition group"
                                x-show="(search === '' || 
                                    '{{ addslashes($work->sale->sale_number ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($work->sale->customer->name ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($workUnitDisplay) }}'.toLowerCase().includes(search.toLowerCase())
                                ) && (status === '' || '{{ addslashes($work->sale->status ?? '') }}'.toLowerCase() === status)">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $work->sale->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->sale->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $workUnitDisplay }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->description }}</td>
                                <td class="px-5 py-3 text-right text-xs font-black text-[#a38c29]">
                                    ₹{{ number_format((float)($work->amount ?? 0), 2) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->created_at ? $work->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($work->sale && $work->sale->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-rose-50 text-rose-700 border border-rose-100">{{ $work->sale->status }}</span>
                                    @elseif($work->sale && $work->sale->status === 'active')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $work->sale->status }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">{{ $work->sale->status ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">No additional work found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Controls --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900">{{ count($additionalWorks) > 0 ? 1 : 0 }}</span> TO 
                    <span class="text-slate-900">{{ count($additionalWorks) }}</span> OF 
                    <span class="text-slate-900">{{ count($additionalWorks) }}</span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">PREV</button>
                    <span class="inline-flex items-center gap-1">
                        <button type="button" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#a38c29] text-white border border-[#a38c29] shadow-2xs">1</button>
                    </span>
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">NEXT</button>
                </div>
            </div>
        </div>

    </div>
</x-erp-layout>
