<x-erp-layout title="Cancellation Charges & Additional Work" headerTitle="Cancellation Charges & Additional Work">
    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{ activeTab: 'cancellation' }">
        
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
                            <th class="px-5 py-3">Unit No.</th>
                            <th class="px-5 py-3">Cancellation Date</th>
                            <th class="px-5 py-3 text-right">Cancellation Fee (₹)</th>
                            <th class="px-5 py-3">Reason</th>
                            <th class="px-5 py-3 text-center w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cancellationCharges as $index => $charge)
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $charge->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->unit->unit_number ?? 'N/A' }}</td>
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
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $work->sale->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->sale->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->sale->unit->unit_number ?? 'N/A' }}</td>
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
