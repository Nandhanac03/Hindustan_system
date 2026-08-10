<x-erp-layout title="Real-time Sales Register & Report" headerTitle="Business Reports Center">

<style>
    .table-responsive-container::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .table-responsive-container::-webkit-scrollbar-thumb {
        background-color: #cbd5e1; /* slate-300 */
        border-radius: 9999px;
    }
    .table-responsive-container::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8; /* slate-400 */
    }
</style>

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6">
            {{-- Top Header & Action Banner --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-slate-50 p-6 rounded-2xl border border-[#a38c29]/30 shadow-sm text-slate-900 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-[#a38c29]/15 rounded-xl border border-[#a38c29]/30 text-[#a38c29] shadow-2xs">
                            <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-slate-900">Real-time Sales Register & Report</h3>
                            <span class="text-[10px] font-bold text-[#a38c29] uppercase tracking-widest bg-[#a38c29]/15 px-2.5 py-0.5 rounded border border-[#a38c29]/30">Executive Performance Ledger</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 mt-2 font-medium max-w-3xl">Comprehensive audit trail of property bookings, project distributions, total sale agreements, and active sales revenue tracking.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5 shrink-0 relative z-10">
                    <button @click="printReport()" 
                            class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-extrabold rounded-xl transition-all shadow-2xs hover:shadow flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Report
                    </button>
                    <button @click="exportCurrentTable()" 
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export Excel
                    </button>
                </div>
            </div>

            {{-- Customer Selection Filter Bar --}}
            <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-5 shadow-2xs">
                <form id="salesReportFilterForm" method="GET" action="{{ route('reports.sales') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    @if(request('project_id'))
                        <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="hidden" name="customer_id" :value="selectedCustomerId">
                    
                    <div class="flex-1 w-full relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Filter by Individual Customer
                        </label>
                        
                        <div class="relative flex-1">
                            <button type="button" 
                                    @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                                    :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                                    class="w-full h-10 px-3.5 py-2 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs">
                                <template x-if="getSelectedCustomer()">
                                    <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#8a7522] font-bold text-xs flex items-center justify-center shrink-0" x-text="getSelectedCustomer().name.charAt(0).toUpperCase()"></div>
                                        <span class="font-extrabold text-slate-900 truncate" x-text="getSelectedCustomer().name"></span>
                                        <span class="text-xs font-bold text-slate-400 font-mono shrink-0" x-show="getSelectedCustomer().phone" x-text="'(' + getSelectedCustomer().phone + ')'"></span>
                                    </div>
                                </template>
                                <template x-if="!getSelectedCustomer()">
                                    <span class="text-slate-500 font-bold">— All Customers —</span>
                                </template>
                                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                    <template x-if="getSelectedCustomer()">
                                        <span @click.stop="selectCustomer(null); search = '';" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition" title="Clear selected customer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </span>
                                    </template>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-50"
                                 style="display: none;">
                                
                                <div class="p-2.5 bg-slate-50/80 border-b border-slate-100 sticky top-0 z-10 backdrop-blur-xs">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text"
                                               x-model="search"
                                               x-ref="customerSearchInput"
                                               placeholder="Type name or phone number..."
                                               @keydown.escape="open = false"
                                               class="w-full pl-8 pr-7 py-2 bg-white border border-slate-200 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/10 rounded-xl text-xs focus:outline-none transition-all placeholder:text-slate-400 font-medium">
                                        <template x-if="search">
                                            <button type="button" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">✕</button>
                                        </template>
                                    </div>
                                </div>

                                <button type="button" @click="selectCustomer(null); open = false; search = ''"
                                        class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>— All Customers —</span>
                                </button>

                                <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                                    <template x-for="customer in getFilteredCustomersList(search)" :key="customer.id">
                                        <button type="button"
                                                @click="selectCustomer(customer); open = false; search = ''"
                                                :class="selectedCustomerId == customer.id ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                                class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div :class="selectedCustomerId == customer.id ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                                     class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors"
                                                     x-text="(customer.name || '?').charAt(0).toUpperCase()"></div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-xs truncate leading-snug" :class="selectedCustomerId == customer.id ? 'text-[#8a7522]' : 'text-slate-800'" x-text="customer.name"></p>
                                                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 font-mono mt-0.5" x-show="customer.phone">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-2.5 h-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                            <span x-text="customer.phone"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Sales Report Data Table Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-amber-50/40 border-b border-slate-200/90 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                            Chronological Sales Log & Financial Auditing Ledger
                        </h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 font-medium">Chronological listing of active property sale agreements and total amounts.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $activeProjectName = 'All Projects (Default)';
                            if(request('project_id')) {
                                $activeProject = \App\Models\Project::find(request('project_id'));
                                if($activeProject) {
                                    $activeProjectName = $activeProject->name;
                                }
                            }
                            $activeCustomerName = null;
                            if(request('customer_id')) {
                                $activeCustomer = \App\Models\Customer::find(request('customer_id'));
                                if($activeCustomer) {
                                    $activeCustomerName = $activeCustomer->name;
                                }
                            }
                        @endphp
                        <span class="px-4 py-1.5 bg-slate-800 text-white border border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active Project: {{ $activeProjectName }}
                        </span>
                        @if($activeCustomerName)
                        <span class="px-4 py-1.5 bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                            Customer: {{ $activeCustomerName }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="w-full overflow-x-auto table-responsive-container">
                    <table id="reportsTable" class="w-full text-[11px] text-left border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-3 py-3 text-white font-extrabold whitespace-nowrap min-w-[100px]">Sale No</th>
                                <th class="px-3 py-3 text-white font-extrabold min-w-[200px]">Project / Unit</th>
                                <th class="px-3 py-3 text-white font-extrabold min-w-[90px]">Floor</th>
                                <th class="px-3 py-3 text-white font-extrabold min-w-[130px]">Customer</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[110px]">Expected (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[110px]">Sale Amt (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[115px]">Diff (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[130px]">Additional Work (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[100px]">GST (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-right whitespace-nowrap min-w-[110px]">Total (₹)</th>
                                <th class="px-3 py-3 text-white font-extrabold text-center whitespace-nowrap min-w-[115px]">Agreement Date</th>
                                <th class="px-3 py-3 text-white font-extrabold text-center whitespace-nowrap min-w-[125px]">Registration Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 bg-white">
                            @forelse($salesList as $sale)
                            @php
                                $expectedAmount = 0.00;
                                if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                                    foreach ($sale->saleUnits as $su) {
                                        $expectedAmount += (float)($su->unit?->expected_sale_amount ?? 0.00);
                                    }
                                } else {
                                    $expectedAmount = (float)($sale->unit?->expected_sale_amount ?? 0.00);
                                }
                                $saleAmount = (float)($sale->sale_amount ?? 0.00);
                                $difference = $saleAmount - $expectedAmount;
                                $extraWorkAmount = (float)($sale->extraWorks->sum('amount') ?? 0.00);

                                $floorsList = '';
                                if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                                    $floorNames = $sale->saleUnits->map(fn($su) => $su->unit?->floor?->name)->filter()->unique();
                                    if ($floorNames->isNotEmpty()) {
                                        $floorsList = implode(', ', $floorNames->toArray());
                                    }
                                } elseif ($sale->unit && $sale->unit->floor) {
                                    $floorsList = $sale->unit->floor->name;
                                }
                            @endphp
                            <tr class="hover:bg-amber-50/30 transition-colors duration-150 font-medium text-[11px]">
                                <td class="px-3 py-3.5 font-bold text-emerald-700 whitespace-nowrap min-w-[100px]">{{ $sale->sale_number }}</td>
                                <td class="px-3 py-3.5 font-sans min-w-[200px]">
                                    <div class="font-bold text-slate-800 leading-tight">{{ $sale->project?->name }}</div>
                                    <div class="text-[9px] text-slate-400 mt-0.5 leading-normal">
                                        Unit: 
                                        @if($sale->saleUnits && $sale->saleUnits->isNotEmpty())
                                            @foreach($sale->saleUnits as $su)
                                                @if($su->unit)
                                                    @php
                                                        $door = trim(explode(',', $su->unit->door_no)[0]);
                                                        $type = strtolower($su->unit->unitType?->name ?? '');
                                                        if ($type === 'flat') $type = 'Apartment';
                                                        elseif (strpos($type, 'parking') !== false) $type = 'Parking';
                                                        else $type = ucfirst($type);

                                                        $floor = trim($su->unit->floor?->name ?? '');
                                                        if (preg_match('/^(floor|fl)\b/i', $floor)) {
                                                            $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $floor);
                                                        } elseif ($floor && is_numeric($floor)) {
                                                            $floor = 'Floor ' . $floor;
                                                        } elseif ($floor) {
                                                            $floor = ucfirst($floor);
                                                        }
                                                    @endphp
                                                    {{ $door }}{{ $type ? "($type)" : "" }}{{ $floor ? " - $floor" : "" }}@if(!$loop->last), @endif
                                                @endif
                                            @endforeach
                                        @elseif($sale->unit)
                                            @php
                                                $door = trim(explode(',', $sale->unit->door_no)[0]);
                                                $type = strtolower($sale->unit->unitType?->name ?? '');
                                                if ($type === 'flat') $type = 'Apartment';
                                                elseif (strpos($type, 'parking') !== false) $type = 'Parking';
                                                else $type = ucfirst($type);

                                                $floor = trim($sale->unit->floor?->name ?? '');
                                                if (preg_match('/^(floor|fl)\b/i', $floor)) {
                                                    $floor = preg_replace('/^(floor|fl)\b/i', 'Floor', $floor);
                                                } elseif ($floor && is_numeric($floor)) {
                                                    $floor = 'Floor ' . $floor;
                                                } elseif ($floor) {
                                                    $floor = ucfirst($floor);
                                                }
                                            @endphp
                                            {{ $door }}{{ $type ? "($type)" : "" }}{{ $floor ? " - $floor" : "" }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 font-sans text-slate-650 min-w-[90px]">{{ $floorsList ?: '—' }}</td>
                                <td class="px-3 py-3.5 font-sans text-slate-800 leading-tight min-w-[130px]">{{ $sale->customer?->name ?? 'N/A' }}</td>
                                <td class="px-3 py-3.5 text-right font-mono font-bold text-slate-650 whitespace-nowrap min-w-[110px]">₹{{ number_format($expectedAmount, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap min-w-[110px]">₹{{ number_format($saleAmount, 2) }}</td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold whitespace-nowrap min-w-[115px]">
                                    @if($difference > 0)
                                        <span class="text-emerald-600">+₹{{ number_format($difference, 2) }}</span>
                                    @elseif($difference < 0)
                                        <span class="text-rose-600">-₹{{ number_format(abs($difference), 2) }}</span>
                                    @else
                                        <span class="text-slate-400">₹0.00</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-bold text-[#a38c29] whitespace-nowrap min-w-[130px] font-bold">
                                    @if($extraWorkAmount > 0)
                                        ₹{{ number_format($extraWorkAmount, 2) }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono text-slate-700 whitespace-nowrap min-w-[100px]">
                                    @if($sale->gst_amount > 0)
                                        ₹{{ number_format($sale->gst_amount, 2) }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap min-w-[110px]">₹{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-3 py-3.5 text-center font-sans whitespace-nowrap min-w-[115px]">{{ $sale->agreement_date?->format('d M Y') ?? $sale->sale_date?->format('d M Y') ?? '—' }}</td>
                                <td class="px-3 py-3.5 text-center font-sans whitespace-nowrap min-w-[125px]">{{ $sale->registration_date?->format('d M Y') ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="px-3 py-12 text-center text-slate-400 italic">No sales logs matching filter criteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between rounded-b-2xl">
                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                        Showing <span class="text-slate-900 font-black">{{ $salesList->firstItem() ?? 0 }}</span> to 
                        <span class="text-slate-900 font-black">{{ $salesList->lastItem() ?? 0 }}</span> of 
                        <span class="text-slate-900 font-black">{{ $salesList->total() }}</span> Sales Records
                    </div>
                    <div class="flex items-center gap-1.5 print:hidden">
                        {{ $salesList->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden" style="display: none;">
    <table id="salesExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
        <colgroup>
            {{-- Section 1 --}}
            <col width="60" style="width: 45pt;" />    {{-- SL NO --}}
            <col width="220" style="width: 165pt;" />  {{-- CUSTOMER NAME --}}
            <col width="130" style="width: 98pt;" />   {{-- BOOKING DATE --}}
            <col width="90" style="width: 68pt;" />    {{-- FLOOR --}}
            <col width="110" style="width: 83pt;" />   {{-- UNIT TYPE --}}
            <col width="140" style="width: 105pt;" />  {{-- AGREEMENT DATE --}}
            <col width="110" style="width: 83pt;" />   {{-- AREA (SQFT) --}}
            {{-- Section 2 --}}
            <col width="150" style="width: 113pt;" />  {{-- EXPECTED RATE / SQFT --}}
            <col width="140" style="width: 105pt;" />  {{-- ACTUAL RATE / SQFT --}}
            <col width="150" style="width: 113pt;" />  {{-- BASE TOTAL AMOUNT --}}
            <col width="190" style="width: 143pt;" />  {{-- RATE VARIANCE --}}
            {{-- Section 3 --}}
            <col width="80" style="width: 60pt;" />    {{-- GST % --}}
            <col width="130" style="width: 98pt;" />   {{-- GST AMOUNT --}}
            <col width="140" style="width: 105pt;" />  {{-- PARKING CHARGES --}}
            <col width="140" style="width: 105pt;" />  {{-- ADDITIONAL WORK --}}
            <col width="170" style="width: 128pt;" />  {{-- GRAND TOTAL DEAL PRICE --}}
            {{-- Section 4 --}}
            <col width="160" style="width: 120pt;" />  {{-- TOTAL CHEQUE VALUE --}}
            <col width="150" style="width: 113pt;" />  {{-- CHEQUE RECEIVED --}}
            <col width="150" style="width: 113pt;" />  {{-- CHEQUE RECEIPT DATE --}}
            {{-- Section 5 --}}
            <col width="160" style="width: 120pt;" />  {{-- CHEQUE BALANCE DUE --}}
            <col width="110" style="width: 83pt;" />   {{-- INSTALMENT --}}
            <col width="160" style="width: 120pt;" />  {{-- CHEQUE COLLECTION % --}}
        </colgroup>
        <thead>
            {{-- Title Header Row (Exact A3 print layout friendly combined 1 row title) --}}
            <tr height="45" style="height: 45pt;">
                <th colspan="22" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: REAL ESTATE SALES BOOKING MASTER (WITH AUDIT DATES & DUAL-TRACK SPLIT)
                </th>
            </tr>
            {{-- Super Section Headers Row with correct requested colors --}}
            <tr height="30" style="height: 30pt;">
                <th colspan="7" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. UNIT & CUSTOMER INFORMATION</th>
                <th colspan="4" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. PRICING & RATE VARIANCE</th>
                <th colspan="5" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. TAXES & ADD-ONS</th>
                <th colspan="3" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">4. CHEQUE VALUE</th>
                <!-- <th colspan="3" bgcolor="#15803d" style="background-color: #15803d; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">5. BALANCE & COLLECTION</th> -->
            </tr>
            {{-- Main Column Headers --}}
            <tr height="40" style="height: 40pt;">
                {{-- Section 1 --}}
                <th width="60" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 45pt;">SL NO</th>
                <th width="220" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 165pt;">CUSTOMER NAME</th>
                <th width="130" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 98pt;">BOOKING DATE</th>
                <th width="90" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 68pt;">FLOOR</th>
                <th width="110" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">UNIT TYPE</th>
                <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">AGREEMENT DATE</th>
                <th width="110" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">AREA (SQFT)</th>
                {{-- Section 2 --}}
                <th width="150" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">EXPECTED RATE / SQFT</th>
                <th width="140" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">ACTUAL RATE / SQFT</th>
                <th width="150" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">BASE TOTAL AMOUNT</th>
                <th width="190" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 143pt;">RATE VARIANCE (DISCOUNT / LOSS)</th>
                {{-- Section 3 --}}
                <th width="80" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 60pt;">GST %</th>
                <th width="130" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 98pt;">GST AMOUNT</th>
                <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">PARKING CHARGES</th>
                <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">ADDITIONAL WORK</th>
                <th width="170" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 128pt;">TOTAL AMOUNT INCLUDING GST /PARKING/ADDITIONAL</th>
                {{-- Section 4 --}}
                <!-- <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">TOTAL CHEQUE VALUE</th> -->
                <th width="150" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">CHEQUE RECEIVED</th>
                <th width="150" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">CHEQUE RECEIPT DATE</th>
                <th width="110" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">INSTALMENT</th>
                <!-- <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE COLLECTION %</th> -->
                {{-- Section 5 --}}
                <!-- <th width="160" bgcolor="#17365D" x:autofilter="all" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE BALANCE DUE</th> -->

            </tr>
        </thead>
        <tbody>
            @php
                $totals = [
                    'area' => 0,
                    'base_total' => 0,
                    'variance' => 0,
                    'gst_amount' => 0,
                    'parking' => 0,
                    'additional' => 0,
                    'grand_total' => 0,
                    'cheque_value' => 0,
                    'cheque_received' => 0,
                    'cheque_due' => 0
                ];
            @endphp
            @foreach($salesList as $sale)
                @php
                    $mainUnit = $sale->saleUnits->filter(fn($su) => !str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking'))->first() ?? $sale->saleUnits->first();

                    // Collect non-parking units for floor / unit display
                    $nonParkingUnits = $sale->saleUnits->filter(
                        fn($su) => !str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking')
                    );

                    // Helper: format a floor name string to ordinal (e.g. "Floor 11" → "11TH")
                    $formatFloor = function(string $floorName): string {
                        $clean = preg_replace('/[^0-9]/', '', $floorName);
                        if ($clean !== '') {
                            $n = (int)$clean;
                            $suffix = in_array($n % 100, [11, 12, 13]) ? 'TH'
                                : (['TH', 'ST', 'ND', 'RD'][$n % 10] ?? 'TH');
                            return $clean . $suffix;
                        }
                        return strtoupper(trim($floorName));
                    };

                    // Comma-separated floor display for all units (including parking)
                    $floorParts = $sale->saleUnits
                        ->map(fn($su) => $su->unit?->floor?->name ?? '')
                        ->filter()
                        ->map($formatFloor)
                        ->unique()
                        ->values()
                        ->toArray();
                    if (empty($floorParts)) {
                        $fb = $sale->unit?->floor?->name ?? '';
                        $floorParts = $fb ? [$formatFloor($fb)] : [];
                    }
                    $floorDisplay = implode(', ', $floorParts);

                    // Comma-separated door numbers for all units — parking units get "(Parking)" label
                    $doorParts = $sale->saleUnits
                        ->map(function($su) {
                            $door = trim(explode(',', $su->unit?->door_no ?? '')[0]);
                            if (!$door) return null;
                            $isParking = str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking');
                            return $isParking ? $door . '(Parking)' : $door;
                        })
                        ->filter()
                        ->unique()
                        ->values()
                        ->toArray();
                    if (empty($doorParts)) {
                        $fallbackDoor = $sale->unit?->door_no ?? '';
                        $doorParts = $fallbackDoor ? [trim(explode(',', $fallbackDoor)[0])] : [];
                    }
                    $unitTypeDisplay = implode(', ', $doorParts);

                    // Area (sum of non-parking units)
                    $areaSqft = (float)($nonParkingUnits->sum('area_sqft') ?: $sale->saleUnits->sum('area_sqft'));

                    // Pricing values
                    $expectedRate = (float)($mainUnit?->unit?->expected_rate_per_sqft ?? 0.00);
                    $actualRate = (float)($mainUnit?->rate_per_sqft ?? 0.00);
                    $baseTotal = (float)$sale->saleUnits->filter(fn($su) => !str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking'))->sum('base_amount');
                    $variance = ($expectedRate - $actualRate) * $areaSqft;
                    
                    // Taxes & Charges
                    $gstPercentage = (float)($mainUnit?->gst_percentage ?? 0.00);
                    $gstAmount = (float)$sale->saleUnits->sum('gst_amount');
                    $parkingCharges = (float)$sale->saleUnits->filter(fn($su) => str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking'))->sum('base_amount');
                    $additionalWork = (float)$sale->extraWorks->sum('amount');
                    $grandTotalDeal = $baseTotal + $parkingCharges + $gstAmount + $additionalWork;
                    
                    // Track A
                    $totalChequeValue = (float)$sale->total_amount;
                    $chequeReceived = (float)$sale->receipts->sum('amount');
                    $chequeBalanceDue = (float)$sale->remaining_balance;
                    
                    // Receipt details
                    $latestReceipt = $sale->receipts->sortByDesc('receipt_date')->first();
                    $receiptDate = $latestReceipt?->receipt_date?->format('Y-m-d') ?? '';
                    $bookingDate = $sale->sale_date?->format('Y-m-d') ?? '';
                    $agreementDate = $sale->agreement_date?->format('Y-m-d') ?? $sale->sale_date?->format('Y-m-d') ?? '';
                    $installmentsCount = $sale->emi_installment_count ?? '';
                    $collectionPct = $totalChequeValue > 0 ? ($chequeReceived / $totalChequeValue) * 100 : 0.00;
 
                    // Increment totals
                    $totals['area'] += $areaSqft;
                    $totals['base_total'] += $baseTotal;
                    $totals['variance'] += $variance;
                    $totals['gst_amount'] += $gstAmount;
                    $totals['parking'] += $parkingCharges;
                    $totals['additional'] += $additionalWork;
                    $totals['grand_total'] += $grandTotalDeal;
                    $totals['cheque_value'] += $totalChequeValue;
                    $totals['cheque_received'] += $chequeReceived;
                    $totals['cheque_due'] += $chequeBalanceDue;
 
                    // Row Zebra striping
                    $rowBg = $loop->iteration % 2 === 0 ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
 
                    // Conditional highlights for Rate Variance (Discount/Loss = Red, Premium = Green)
                    $varianceStyle = '';
                    if ($variance > 0) {
                        $varianceStyle = 'background-color: #fee2e2; color: #991b1b;';
                    } elseif ($variance < 0) {
                        $varianceStyle = 'background-color: #dcfce7; color: #166534;';
                    }
                    
                    // Conditional highlights for Outstanding Balance (Due > 0 = Red)
                    $balanceStyle = $chequeBalanceDue > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                    
                    // Conditional highlights for Collection % (100% = Green, >= 50% = Yellow, < 50% = Red)
                    $pctStyle = '';
                    if ($collectionPct >= 100) {
                        $pctStyle = 'background-color: #dcfce7; color: #166534; font-weight: bold;';
                    } elseif ($collectionPct >= 50) {
                        $pctStyle = 'background-color: #fef9c3; color: #854d0e; font-weight: bold;';
                    } else {
                        $pctStyle = 'background-color: #fee2e2; color: #991b1b; font-weight: bold;';
                    }
                @endphp
                <tr height="25" style="height: 25pt; text-align: center; vertical-align: middle; {{ $rowBg }}">
                    {{-- Section 1 --}}
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $loop->iteration }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: left; padding-left: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($sale->customer?->name ?? '') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $bookingDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $floorDisplay }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $unitTypeDisplay }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $agreementDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $areaSqft }}</td>
                    
                    {{-- Section 2 --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $expectedRate > 0 ? $expectedRate : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $actualRate > 0 ? $actualRate : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $baseTotal > 0 ? $baseTotal : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; {{ $varianceStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $variance != 0 ? abs($variance) : '0' }}</td>
                    
                    {{-- Section 3 --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $gstPercentage > 0 ? ($gstPercentage / 100) : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $gstAmount > 0 ? $gstAmount : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $parkingCharges > 0 ? $parkingCharges : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $additionalWork > 0 ? $additionalWork : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #f1f5f9; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $grandTotalDeal }}</td>
                    
                    {{-- Section 4 --}}
                    <!-- <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #dcfce7; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totalChequeValue }}</td> -->
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $chequeReceived }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $receiptDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $installmentsCount }}</td>
                    <!-- <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; {{ $pctStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $collectionPct / 100 }}</td> -->

                    
                    {{-- Section 5 --}}
                    <!-- <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $balanceStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $chequeBalanceDue }}</td> -->
                </tr>
            @endforeach
 
              <tr height="30" style="height: 30pt; font-weight: bold; color: #ffffff;">
                {{-- Section 1 Totals --}}
                <td colspan="6" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['area'] }}</td>
                {{-- Section 2 Totals --}}
                <td colspan="2" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['base_total'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['variance'] }}</td>
                {{-- Section 3 Totals --}}
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['gst_amount'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['parking'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['additional'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['grand_total'] }}</td>
                {{-- Section 4 Totals --}}
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['cheque_received'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                {{-- Section 5 Totals --}}
                <!-- <td bgcolor="#17365D" style="background-color: #17365D; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif;"></td> -->
                <!-- <td bgcolor="#17365D" style="background-color: #17365D; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td> -->
                <!-- @php
                    $overallCollectionPct = $totals['cheque_value'] > 0 ? ($totals['cheque_received'] / $totals['cheque_value'] * 100) : 0.00;
                @endphp
                <td bgcolor="#17365D" style="background-color: #17365D; text-align: center; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $overallCollectionPct / 100 }}</td> -->
            </tr>
        </tbody>
    </table>
</div>

@include('reports.partials.script')

</x-erp-layout>
