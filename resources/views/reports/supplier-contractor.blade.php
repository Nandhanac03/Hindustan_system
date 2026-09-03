<x-erp-layout title="Contractor Statement" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    {{-- Under Construction Notice --}}
    <div class="rounded-2xl bg-gradient-to-r from-red-500/15 via-rose-500/10 to-red-500/15 border-2 border-red-500 p-5 md:p-6 shadow-sm relative overflow-hidden backdrop-blur-sm">
        <div class="flex items-start md:items-center gap-4">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30 text-2xl md:text-3xl">
                🚧
            </div>
            <div class="flex-1 space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">Under Development</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-red-700">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        Work In Progress
                    </span>
                </div>
                <h2 class="text-lg md:text-2xl font-black text-red-950 tracking-tight leading-snug">
                    We're working on this module. It is not yet ready for use and will be released shortly.
                </h2>
                <p class="text-xs md:text-sm font-medium text-red-800">
                    This module is currently being finalized. Please check back soon for full availability.
                </p>
            </div>
        </div>
    </div>

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-3">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Contractor Dues & Statement Payables</h3>
            @include('reports.partials.header-badges')
        </div>

        <div id="supplierPayablesChart" class="w-full h-44 bg-slate-50 border border-slate-150 rounded-2xl p-4"></div>

        {{-- Filter, Print & Export Bar directly above Table --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs relative z-50">
            <form id="supplierContractorFilterForm" method="GET" action="{{ route('reports.supplier_contractor') }}" class="flex flex-wrap items-center gap-3 w-full">
                <div class="flex-1 min-w-[200px]">
                    <select name="contractor_id" onchange="document.getElementById('supplierContractorFilterForm').submit()" class="w-full h-[42px] px-3 py-1.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white focus:border-[#a38c29] focus:outline-none shadow-2xs">
                        <option value="">-- All Contractors --</option>
                        @foreach($suppliers as $contractor)
                            <option value="{{ $contractor->id }}" {{ request('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                {{ $contractor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[180px]">
                    <select name="status" onchange="document.getElementById('supplierContractorFilterForm').submit()" class="w-full h-[42px] px-3 py-1.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-700 bg-white focus:border-[#a38c29] focus:outline-none shadow-2xs">
                        <option value="">-- All Statuses --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="cleared" {{ request('status') == 'cleared' ? 'selected' : '' }}>Cleared / Paid</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search RA Bill # or Contractor..." class="w-full h-[42px] px-3 py-1.5 border border-slate-300 rounded-xl text-xs font-medium text-slate-700 bg-white focus:border-[#a38c29] focus:outline-none shadow-2xs">
                </div>

                <button type="submit" class="h-[42px] px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-xl transition cursor-pointer shadow-2xs">
                    Filter
                </button>

                @if(request('contractor_id') || request('status') || request('search'))
                    <a href="{{ route('reports.supplier_contractor') }}" class="h-[42px] px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition flex items-center shadow-2xs">
                        Reset
                    </a>
                @endif

                <div class="flex items-center gap-2 ml-auto">
                    <button type="button" @click="printReport()" class="h-[42px] px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print
                    </button>
                    <button type="button" @click="exportCurrentTable()" class="h-[42px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Payables
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-xl">
            <table id="reportsTable" class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                        <th class="px-5 py-3.5 text-white font-extrabold">Contractor Name</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">RA Bill # / Ref</th>
                        <th class="px-5 py-3.5 text-white font-extrabold">Project / Work Location</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Net Approved Dues</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Paid Amount</th>
                        <th class="px-5 py-3.5 text-right text-white font-extrabold">Balance Due</th>
                        <th class="px-5 py-3.5 text-center text-white font-extrabold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-650 font-mono">
                    @forelse($supplierContractorEntries as $row)
                    <tr class="hover:bg-slate-50/60 font-semibold">
                        <td class="px-5 py-3 font-sans font-bold text-slate-900">
                            {{ $row->contractor_name ?: ($row->contractor?->name ?? 'General Contractor') }}
                        </td>
                        <td class="px-5 py-3 text-indigo-750 font-bold">
                            <div>{{ $row->ra_bill_number }}</div>
                            @if($row->verified_date)
                                <div class="text-[10px] text-slate-400 font-normal">Verified: {{ \Carbon\Carbon::parse($row->verified_date)->format('d-M-Y') }}</div>
                            @elseif($row->submit_date)
                                <div class="text-[10px] text-slate-400 font-normal">Submitted: {{ \Carbon\Carbon::parse($row->submit_date)->format('d-M-Y') }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-sans text-slate-600">
                            <div class="font-bold text-slate-800">{{ $row->project?->name ?? 'General Project' }}</div>
                            @if($row->unit_name || $row->unit?->door_no)
                                <div class="text-[10px] text-slate-400 font-medium">Unit: {{ $row->unit_name ?: $row->unit?->door_no }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right text-slate-900 font-bold">₹{{ number_format((float)$row->net_approved_amount, 2) }}</td>
                        <td class="px-5 py-3 text-right text-emerald-700 font-bold">₹{{ number_format((float)$row->paid_amount, 2) }}</td>
                        <td class="px-5 py-3 text-right text-rose-600 font-bold">₹{{ number_format((float)$row->balance_amount, 2) }}</td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $sc = [
                                    'cleared' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                    'partially_paid' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                    'pending' => 'bg-orange-50 text-orange-700 border border-orange-200',
                                    'submitted' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                ];
                                $st = str_replace('_', ' ', $row->status);
                            @endphp
                            <span class="px-2.5 py-0.5 rounded text-[9px] font-black uppercase inline-block {{ $sc[$row->status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ $st }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">No contractor statements or RA bills found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $supplierContractorEntries->appends(request()->query())->links() }}</div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
