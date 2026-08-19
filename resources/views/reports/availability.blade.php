<x-erp-layout title="Property Availability Matrix" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6 relative"
             @mousemove="mouseX = $event.clientX; mouseY = $event.clientY"
             x-data="{ 
            currentSubTab: 'summary', 
            hoveredUnit: null, 
            hoveredEl: null, 
            unitModalOpen: false, 
            modalLoading: false, 
            selectedUnitDetails: null,
            panelOpen: false,
            unit: null,
            loading: false,
            activeTab: 'details',
            mouseX: 0,
            mouseY: 0,
            viewUnitDetails(unitId) {
                this.loading = true;
                this.panelOpen = true;
                this.activeTab = 'details';
                fetch(`{{ url('units') }}/${unitId}/json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Unauthorized');
                        return res.json();
                    })
                    .then(data => {
                        this.unit = data.unit;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                        this.panelOpen = false;
                    });
            }
        }">
            {{-- Header Title and Status Badges --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Property Availability Matrix</h3>
                @include('reports.partials.header-badges')
            </div>
            
            {{-- Controls Bar: Sub-tab Navigation (Left) & Filter Bar (Right) --}}
            <div class="flex flex-row items-center justify-end gap-4 relative z-50">
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl shrink-0">
                    <button type="button" @click="currentSubTab = 'summary'"
                            :class="currentSubTab === 'summary' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Summary
                    </button>
                    <button type="button" @click="currentSubTab = 'matrix'"
                            :class="currentSubTab === 'matrix' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Floor Matrix
                    </button>
                    <button type="button" @click="currentSubTab = 'shop'"
                            :class="currentSubTab === 'shop' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Shop
                    </button>
                    <button type="button" @click="currentSubTab = 'apartment'"
                            :class="currentSubTab === 'apartment' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Apartment
                    </button>
                    <button type="button" @click="currentSubTab = 'parking'"
                            :class="currentSubTab === 'parking' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Parking
                    </button>
                    @if(isset($others) && $others->isNotEmpty())
                    <button type="button" @click="currentSubTab = 'other'"
                            :class="currentSubTab === 'other' ? 'bg-white text-primary shadow-sm font-extrabold' : 'text-slate-550 hover:text-slate-700 font-bold'"
                            class="px-3.5 py-1.5 rounded-lg text-[10px] uppercase tracking-wider transition-all">
                        Other
                    </button>
                    @endif
                </div>


            </div>

            {{-- SUMMARY SUB-TAB --}}
            <div x-show="currentSubTab === 'summary'" class="space-y-6" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 lg:col-span-1">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Availability Distribution</h4>
                        <div id="availabilityDistributionChart" class="w-full h-52"></div>
                    </div>
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 lg:col-span-2">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Unit Type Distribution</h4>
                        <div id="unitTypeDistributionChart" class="w-full h-52"></div>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-sm">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 text-white font-extrabold">Type</th>
                                <th class="px-5 py-3.5 text-center text-white font-extrabold">Nos</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Built Up Area (In Sq Ft)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Carpet Area (In Sq Ft)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @php
                                $totalNos = 0;
                                $totalBuilt = 0;
                                $totalCarpet = 0;
                            @endphp
                            @foreach($groupedSummary as $row)
                                @php
                                    $totalNos += $row->nos;
                                    $totalBuilt += $row->built_up_area;
                                    $totalCarpet += $row->carpet_area;
                                @endphp
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-3.5 font-sans font-bold text-slate-900">{{ $row->type }}</td>
                                    <td class="px-5 py-3.5 text-center text-slate-650">{{ $row->nos }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        {{ $row->built_up_area > 0 ? number_format($row->built_up_area, 2) : '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        {{ $row->carpet_area > 0 ? number_format($row->carpet_area, 2) : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-amber-50/80 font-bold text-slate-900">
                                <td class="px-5 py-4 font-sans uppercase">Total</td>
                                <td class="px-5 py-4 text-center">{{ $totalNos }}</td>
                                <td class="px-5 py-4 text-right">{{ number_format($totalBuilt, 2) }}</td>
                                <td class="px-5 py-4 text-right">{{ number_format($totalCarpet, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SHOP SUB-TAB --}}
            <div x-show="currentSubTab === 'shop'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-sm">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 w-16 text-center text-white font-extrabold">No</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Floor</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Type</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Door No</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3.5 text-center text-white font-extrabold">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($shops as $index => $row)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No shops matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- APARTMENT SUB-TAB --}}
            <div x-show="currentSubTab === 'apartment'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-sm">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 w-16 text-center text-white font-extrabold">No</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Floor</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Type</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Door No</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3.5 text-center text-white font-extrabold">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($apartments as $index => $row)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No apartments matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PARKING SUB-TAB --}}
            <div x-show="currentSubTab === 'parking'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-sm">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 w-16 text-center text-white font-extrabold">No</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Floor</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Type</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Parking No</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Sold/Booked To</th>
                                <th class="px-5 py-3.5 text-center text-white font-extrabold">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($parkings as $index => $row)
                                <tr class="hover:bg-amber-50/30 transition-colors">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-800">
                                        @if($row->sale)
                                            <div class="font-bold text-[11px]">{{ $row->sale->customer?->name }}</div>
                                            <div class="text-[9px] text-slate-400 font-mono">Sale: {{ $row->sale->sale_number }}</div>
                                        @else
                                            <span class="text-slate-350 italic font-normal text-[10px]">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-slate-400 italic">No parking bays matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- OTHER SUB-TAB --}}
            @if(isset($others) && $others->isNotEmpty())
            <div x-show="currentSubTab === 'other'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-sm">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#a38c29] via-[#b89635] to-[#a38c29] text-white border-b-2 border-[#8a7522] text-[10px] font-black uppercase tracking-widest shadow-xs">
                                <th class="px-5 py-3.5 w-16 text-center text-white font-extrabold">No</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Floor</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Type</th>
                                <th class="px-5 py-3.5 text-white font-extrabold">Door No</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3.5 text-right text-white font-extrabold">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3.5 text-center text-white font-extrabold">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($others as $index => $row)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-5 py-3.5 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500">{{ $row->floor?->name ?? '—' }}</td>
                                    <td class="px-5 py-3.5 font-sans text-slate-900 font-bold">{{ $row->unitType?->name }}</td>
                                    <td class="px-5 py-3.5 font-bold text-indigo-700">{{ $row->door_no }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->built_up_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-right">{{ number_format($row->carpet_area, 2) }}</td>
                                    <td class="px-5 py-3.5 text-center font-sans">
                                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">Available</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No other properties matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- FLOOR MATRIX SUB-TAB --}}
            <div x-show="currentSubTab === 'matrix'" class="space-y-6 relative" x-transition style="display: none;">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/15 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Floor Matrix – Unit Availability</h3>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3 sm:gap-5 text-[9px] font-black uppercase tracking-wider text-slate-655">
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-emerald-500 shadow-xs border border-emerald-600"></span> Available</span>
                        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-[#0b1e36] shadow-xs border border-slate-800"></span> Parking</span>
                    </div>
                </div>

                @if(empty($matrixColumns) && empty($parkingRows))
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">No unit data available for this project</p>
                        <p class="text-[10px] text-slate-400 mt-1">Select a different project or add units to see the matrix grid.</p>
                    </div>
                @else
                    @php
                        $maxFloorUnits = 0;
                        foreach ($floorMatrix as $row) {
                            $count = collect($row['columns'])->filter()->count();
                            if ($count > $maxFloorUnits) $maxFloorUnits = $count;
                        }
                        $maxParkingUnits = empty($parkingRows) ? 0 : collect($parkingRows)->max(fn($p) => $p['units']->count());
                        $totalGridCols = max($maxFloorUnits, $maxParkingUnits);
                    @endphp

                    <div class="overflow-x-auto relative rounded-2xl border border-slate-200 shadow-sm bg-white">
                        <table class="border-collapse w-full text-xs text-left" style="min-width: max-content;">
                            <thead>
                                <tr class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-widest border-b border-[#a38c29]/30">
                                    <th class="p-3.5 sticky left-0 bg-[#a38c29] z-10 min-w-[130px] border-r border-[#a38c29]/30 text-white font-extrabold whitespace-nowrap">
                                        Floor / Unit
                                    </th>
                                    @for($i = 1; $i <= $totalGridCols; $i++)
                                        <th class="p-3.5 text-center min-w-[90px] text-white font-extrabold">
                                            <span class="block text-white/90">{{ $i }}</span>
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @php
                                    $reversedFloors = array_reverse($floorMatrix);
                                    $combinedFloors = [];
                                    
                                    $validParking = [];
                                    foreach($parkingRows as $pRow) {
                                        if ($pRow['units']->count() == 0) continue;
                                        
                                        $dName = $pRow['floor']->name;
                                        
                                        $validParking[] = [
                                            'display_name' => $dName,
                                            'floor_number' => $pRow['floor']->floor_number ?? 0,
                                            'is_parking_row' => true,
                                            'columns' => collect($pRow['units'])->sortBy('door_no', SORT_NATURAL | SORT_FLAG_CASE)->values()->all()
                                        ];
                                    }
                                    
                                    // Sort parking rows in ascending order
                                    usort($validParking, function($a, $b) {
                                        return $a['floor_number'] <=> $b['floor_number'];
                                    });
                                    
                                    foreach($reversedFloors as $row) {
                                        $row['is_parking_row'] = false;
                                        $combinedFloors[] = $row;
                                        if (strtolower(trim($row['display_name'])) === 'floor 3') {
                                            foreach($validParking as $vp) {
                                                $combinedFloors[] = $vp;
                                            }
                                        }
                                    }
                                    
                                    $hasAddedParking = false;
                                    foreach($combinedFloors as $cf) {
                                        if(isset($cf['is_parking_row']) && $cf['is_parking_row']) {
                                            $hasAddedParking = true;
                                        }
                                    }
                                    if (!$hasAddedParking) {
                                        $combinedFloors = array_merge($combinedFloors, $validParking);
                                    }
                                @endphp

                                @foreach ($combinedFloors as $row)
                                    @php
                                        $isParkingRow = $row['is_parking_row'] ?? false;
                                        $validUnits = collect($row['columns'])->filter()->values();
                                        
                                        $isParkingFloor = $isParkingRow;
                                        if (!$isParkingFloor) {
                                            $firstUnit = $validUnits->first();
                                            if ($firstUnit) {
                                                $isParkingFloor = ($firstUnit->unitType && stripos($firstUnit->unitType->name, 'park') !== false) 
                                                                  || stripos($firstUnit->door_no, 'P') === 0;
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-[#a38c29]/5 transition-colors duration-150 group">
                                        <td class="p-3.5 sticky left-0 bg-white group-hover:bg-slate-50 backdrop-blur-md z-10 border-l-2 border-l-[#a38c29] border-r border-slate-150 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] transition-colors duration-150">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-xl bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center shrink-0 border border-[#a38c29]/20">
                                                    @if($isParkingFloor)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16a2 2 0 11-4 0 2 2 0 014 0zm12 0a2 2 0 11-4 0 2 2 0 014 0zM4 16h-.5A1.5 1.5 0 012 14.5v-2c0-.5.2-1 .5-1.3l2-3.4A3 3 0 017 6.5h10a3 3 0 012.5 1.3l2 3.4c.3.3.5.8.5 1.3v2a1.5 1.5 0 01-1.5 1.5H20m-4-10v3m-8-3v3m12 1.5H4" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="block text-[10px] font-black text-slate-800 uppercase tracking-wide">{{ $row['display_name'] }}</span>
                                                    <span class="block text-[9px] text-slate-400 font-bold mt-0.5">{{ collect($row['columns'])->filter()->count() }} {{ $isParkingRow ? 'Slot(s)' : 'Unit(s)' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        @for ($i = 0; $i < $totalGridCols; $i++)
                                            <td class="p-2.5">
                                                @if ($i < $validUnits->count())
                                                    @php $unit = $validUnits[$i]; @endphp
                                                    
                                                    @if ($isParkingRow)
                                                        @php 
                                                            $isOccupied = in_array(strtolower($unit->status), ['sold', 'blocked']); 
                                                        @endphp
                                                        @if (!$isOccupied)
                                                            <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: 'Car Parking Space', status: 'Available', price: '₹{{ number_format($unit->expected_sale_amount ?? 300000) }}', type: 'parking' }; hoveredEl = $el"
                                                                 @mouseleave="hoveredUnit = null"
                                                                 @click="viewUnitDetails({{ $unit->id }})"
                                                                 class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl border border-transparent transition-all hover:-translate-y-0.5 hover:shadow-md cursor-pointer bg-[#0b1e36] text-white shadow-xs hover:bg-[#152a47] duration-150">
                                                                <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-xs">{{ $unit->door_no }}</span>
                                                                <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-xs">Parking</span>
                                                            </div>
                                                        @else
                                                            <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100 rounded-xl bg-slate-50/30"></div>
                                                        @endif
                                                    @else
                                                        @php
                                                            $status = strtolower($unit->status);
                                                            $isAvailable = ($status === 'available');
                                                        @endphp
                                                        @if ($isAvailable)
                                                            @php
                                                                $isParkingUnit = ($unit->unitType && (strtolower($unit->unitType->name) === 'parking' || strtolower($unit->unitType->category) === 'parking'));
                                                            @endphp
                                                            <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: '{{ $unit->built_up_area ? $unit->built_up_area.' sq.ft' : 'N/A' }}', status: 'Available', price: '₹{{ number_format($unit->expected_sale_amount ?? 0) }}', type: '{{ $isParkingUnit ? 'parking' : 'unit' }}' }; hoveredEl = $el"
                                                                 @mouseleave="hoveredUnit = null"
                                                                 @click="viewUnitDetails({{ $unit->id }})"
                                                                 class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl border border-transparent transition-all hover:-translate-y-0.5 hover:shadow-md cursor-pointer {{ $isParkingUnit ? 'bg-[#0b1e36] hover:bg-[#152a47]' : 'bg-emerald-500 hover:border-emerald-400' }} text-white shadow-xs duration-150">

                                                                <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-xs">
                                                                    {{ $unit->door_no }}
                                                                </span>
                                                                <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-xs">
                                                                    {{ $unit->unitType?->name ? (strtolower($unit->unitType->name) === 'flat' ? 'Apartment' : ucfirst($unit->unitType->name)) : 'N/A' }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100 rounded-xl bg-slate-50/30"></div>
                                                        @endif
                                                    @endif
                                                @else
                                                    <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100 rounded-xl bg-slate-50/30"></div>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            

<!-- Hover Tooltip -->
                <div class="fixed z-[150] bg-white border border-[#EAE3CD] rounded-2xl shadow-2xl p-4 w-[260px] pointer-events-none space-y-2"
                     :style="`display: ${hoveredUnit ? 'block' : 'none'}; left: ${Math.max(10, Math.min(mouseX - 130, window.innerWidth - 270))}px; top: ${mouseY - 15}px; transform: translateY(-100%);`">

                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <div>
                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider block" x-text="hoveredUnit?.floor"></span>
                            <span class="text-xs font-black text-slate-800 uppercase tracking-wider" x-text="hoveredUnit?.door_no"></span>
                        </div>
                        <span class="text-[8px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm"
                              :class="{'bg-rose-600': hoveredUnit?.status === 'Sold', 'bg-blue-500': hoveredUnit?.status === 'Booked', 'bg-amber-500': hoveredUnit?.status === 'Blocked', 'bg-emerald-500': hoveredUnit?.status === 'Available', 'bg-slate-700': hoveredUnit?.status === 'Reserved'}"
                              x-text="hoveredUnit?.status"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[9px] font-semibold text-slate-500 uppercase tracking-wider">
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Built Up Area</span>
                            <span class="text-slate-800 font-extrabold" x-text="hoveredUnit?.area"></span>
                        </div>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold">Expected Sale</span>
                            <span class="text-[#a38c29] font-extrabold font-mono" x-text="hoveredUnit?.price"></span>
                        </div>
                        <div class="col-span-2" x-show="hoveredUnit?.customer">
                            <span class="block text-[8px] text-slate-400 font-bold">Sold To</span>
                            <span class="text-emerald-700 font-extrabold uppercase tracking-widest" x-text="hoveredUnit?.customer"></span>
                        </div>
                    </div>
                </div>

                

<!-- Dynamic Drawer Slide-Over Panel -->
            <div x-show="panelOpen" 
                 class="fixed inset-0 z-[120] overflow-hidden" 
                 style="display: none;"
                 @keydown.window.escape="panelOpen = false">
                <div class="absolute inset-0 overflow-hidden">
                    <!-- Overlay Backdrop -->
                    <div x-show="panelOpen" 
                         x-transition:enter="ease-in-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in-out duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="panelOpen = false" 
                         class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="panelOpen" 
                             x-transition:enter="transform transition ease-in-out duration-350"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-350"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             class="pointer-events-auto w-screen max-w-md">
                            
                            <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl border-l border-slate-200">
                                
                                <!-- Header Info -->
                                <div class="bg-slate-950 p-6 text-white border-b border-slate-900">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[9px] font-bold text-indigo-300 bg-indigo-950 px-2 py-0.5 rounded uppercase" 
                                                  x-text="unit && unit.unit_type ? unit.unit_type.name : ''"></span>
                                            <h2 class="text-sm font-bold tracking-tight uppercase" x-text="unit ? 'Unit ' + unit.door_no : ''"></h2>
                                        </div>
                                        <button @click="panelOpen = false" class="text-slate-400 hover:text-white rounded-lg transition-colors p-1.5">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between text-xs text-slate-400 font-medium">
                                        <span>Floor: <strong class="text-white" x-text="unit && unit.floor ? unit.floor.name : ''"></strong></span>
                                        <span>Status: 
                                            <span class="ml-1 uppercase text-[9px] font-bold px-2 py-0.5 rounded"
                                                  :class="{
                                                      'bg-emerald-950 text-emerald-400 border border-emerald-800': unit && unit.status === 'available',
                                                      'bg-amber-950 text-amber-400 border border-amber-800': unit && unit.status === 'blocked',
                                                      'bg-blue-950 text-blue-400 border border-blue-800': unit && unit.status === 'booked',
                                                      'bg-rose-950 text-rose-400 border border-rose-800': unit && unit.status === 'sold',
                                                      'bg-slate-900 text-slate-400 border border-slate-800': unit && (unit.status === 'on_hold' || unit.status === 'parking')
                                                  }"
                                                  x-text="unit ? unit.status : ''"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Drawer Navigation Tabs -->
                                <div class="flex border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-center">
                                    <button @click="activeTab = 'details'" 
                                            :class="activeTab === 'details' ? 'border-[#a38c29] text-[#a38c29] border-b-2' : 'text-slate-500 hover:text-slate-800'"
                                            class="flex-1 py-3 transition">Details</button>
                                    <button @click="activeTab = 'rates'" 
                                            :class="activeTab === 'rates' ? 'border-[#a38c29] text-[#a38c29] border-b-2' : 'text-slate-500 hover:text-slate-800'"
                                            class="flex-1 py-3 transition">Rate Logs</button>
                                    <button @click="activeTab = 'status'" 
                                            :class="activeTab === 'status' ? 'border-[#a38c29] text-[#a38c29] border-b-2' : 'text-slate-500 hover:text-slate-800'"
                                            class="flex-1 py-3 transition">Status Logs</button>
                                </div>

                                <!-- Content Body -->
                                <div class="flex-1 p-6 overflow-y-auto space-y-6">
                                    
                                    <!-- Loading Spinner -->
                                    <div x-show="loading" class="flex flex-col items-center justify-center py-12 space-y-2">
                                        <div class="animate-spin rounded-full h-8 w-8 border-2 border-[#a38c29] border-t-transparent"></div>
                                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Loading details...</span>
                                    </div>

                                    <div x-show="!loading && unit">
                                        
                                        <!-- TAB 1: DETAILS -->
                                        <div x-show="activeTab === 'details'" class="space-y-6">
                                            <!-- Specs Matrix Card -->
                                            <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4 space-y-3 text-xs">
                                                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Specifications</h4>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <span class="text-slate-450 font-medium block">BUA Area</span>
                                                        <strong class="text-slate-850" x-text="unit && unit.built_up_area ? unit.built_up_area + ' Sq Ft' : 'N/A'"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-455 font-medium block">Carpet Area</span>
                                                        <strong class="text-slate-850" x-text="unit && unit.carpet_area ? unit.carpet_area + ' Sq Ft' : 'N/A'"></strong>
                                                    </div>
                                                    <div class="col-span-2 border-t border-slate-200/60 pt-2 grid grid-cols-2 gap-4">
                                                        <div>
                                                            <span class="text-slate-455 font-medium block">Expected Rate</span>
                                                            <strong class="text-slate-850" x-text="unit ? '₹' + Number(unit.expected_rate_per_sqft || 0).toLocaleString('en-US') : ''"></strong>
                                                        </div>
                                                        <div>
                                                            <span class="text-slate-455 font-medium block">Expected Sale</span>
                                                            <strong class="text-emerald-700" x-text="unit ? '₹' + Number(unit.expected_sale_amount || 0).toLocaleString('en-US') : ''"></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sale Details (Shown if unit is sold) -->
                                            <div x-show="unit?.status === 'sold'" class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 space-y-3 text-xs">
                                                <div class="flex items-center justify-between border-b border-emerald-100 pb-1.5">
                                                    <h4 class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-widest">Sale Information</h4>
                                                    <span x-show="unit?.sale" class="text-[9px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded" x-text="unit?.sale ? unit.sale.sale_number : ''"></span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3.5">
                                                    <div class="col-span-2">
                                                        <span class="text-emerald-650 block font-medium">Customer / Sold To</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit?.sale && unit.sale.customer ? unit.sale.customer.name : '-'"></strong>
                                                    </div>
                                                    <div x-show="unit?.sale && unit.sale.sale_date">
                                                        <span class="text-emerald-650 block font-medium">Sale Date</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit?.sale ? new Date(unit.sale.sale_date).toLocaleDateString() : 'N/A'"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Sale Area (BUA)</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit?.built_up_area ? unit.built_up_area + ' Sq Ft' : 'N/A'"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Expected Rate / Sq Ft</span>
                                                        <strong class="text-slate-850 font-extrabold" x-text="unit ? '₹' + Number(unit.expected_rate_per_sqft || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Sale Rate / Sq Ft</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit ? '₹' + Number(unit.sale_rate_per_sqft || (unit.sale ? unit.sale.rate_per_sqft : 0)).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Expected Price</span>
                                                        <strong class="text-slate-850 font-extrabold" x-text="unit ? '₹' + Number(unit.expected_sale_amount || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Actual Sale Price</span>
                                                        <strong class="text-emerald-800 font-extrabold" x-text="unit ? '₹' + Number(unit.sale_amount || (unit.sale ? unit.sale.sale_amount : 0)).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div class="col-span-2 bg-rose-50 border border-rose-100 rounded-lg p-2.5">
                                                        <span class="text-rose-600 block font-bold text-[9px] uppercase tracking-wider">Shortfall / Difference</span>
                                                        <strong class="text-rose-750 font-extrabold text-sm" x-text="unit ? '₹' + Number(unit.difference || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div x-show="unit?.sale">
                                                        <span class="text-emerald-650 block font-medium">Total Amount (Tax Inc.)</span>
                                                        <strong class="text-emerald-850 font-extrabold" x-text="unit?.sale ? '₹' + Number(unit.sale.total_amount || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div x-show="unit?.sale">
                                                        <span class="text-emerald-650 block font-medium">Remaining Bal.</span>
                                                        <strong class="text-rose-750 font-extrabold" x-text="unit?.sale ? '₹' + Number(unit.sale.remaining_balance || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Booking Details (Shown if unit is booked and has linked booking information) -->
                                            <div x-show="unit?.status === 'booked' && unit?.booking" class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 space-y-3 text-xs">
                                                <div class="flex items-center justify-between border-b border-blue-100 pb-1.5">
                                                    <h4 class="text-[10px] font-extrabold text-blue-800 uppercase tracking-widest">Booking Information</h4>
                                                    <span class="text-[9px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded" x-text="unit?.booking ? unit.booking.booking_number : ''"></span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3.5">
                                                    <div class="col-span-2">
                                                        <span class="text-blue-650 block font-medium">Customer</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit?.booking && unit.booking.customer ? unit.booking.customer.name : '-'"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-blue-650 block font-medium">Booking Date</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit?.booking && unit.booking.agreement_date ? new Date(unit.booking.agreement_date).toLocaleDateString() : 'N/A'"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-blue-650 block font-medium">Booking Amount</span>
                                                        <strong class="text-blue-850 font-extrabold" x-text="unit?.booking ? '₹' + Number(unit.booking.amount || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAB 2: RATE HISTORY -->
                                        <div x-show="activeTab === 'rates'" class="space-y-4">
                                            <h4 class="text-[10px] font-bold text-slate-450 uppercase tracking-widest mb-2">Pricing History Logs (Append-Only)</h4>
                                            <div class="relative pl-6 border-l border-slate-200 space-y-4">
                                                <template x-for="log in unit?.rate_logs" :key="log.id">
                                                    <div class="relative">
                                                        <!-- Icon indicator -->
                                                        <span class="absolute -left-[30px] top-0.5 bg-indigo-100 text-indigo-700 border-2 border-white rounded-full w-4 h-4 flex items-center justify-center">
                                                            <span class="w-1.5 h-1.5 bg-[#a38c29] rounded-full"></span>
                                                        </span>
                                                        <div class="text-xs">
                                                            <div class="flex items-center justify-between font-semibold text-slate-900">
                                                                <span x-text="'₹' + Number(log.rate).toLocaleString('en-US')"></span>
                                                                <span class="text-[10px] text-slate-400 font-medium" x-text="log.effective_from ? new Date(log.effective_from).toLocaleDateString() : 'N/A'"></span>
                                                            </div>
                                                            <p class="text-slate-500 font-medium mt-1" x-text="log.reason ?? 'No reason provided'"></p>
                                                            <div class="text-[10px] text-slate-400 mt-0.5">
                                                                Updated by: <span class="font-bold text-slate-650" x-text="log.user ? log.user.name : 'System'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- TAB 3: STATUS HISTORY -->
                                        <div x-show="activeTab === 'status'" class="space-y-4">
                                            <h4 class="text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Status Change Logs (Append-Only)</h4>
                                            <div class="relative pl-6 border-l border-slate-200 space-y-4">
                                                <template x-for="log in unit?.status_logs" :key="log.id">
                                                    <div class="relative">
                                                        <!-- Icon indicator -->
                                                        <span class="absolute -left-[30px] top-0.5 bg-slate-100 border-2 border-white rounded-full w-4 h-4 flex items-center justify-center">
                                                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span>
                                                        </span>
                                                        <div class="text-xs">
                                                            <div class="flex items-center gap-2 font-bold uppercase text-[9px]">
                                                                <span class="text-slate-450" x-text="log.from_status ? log.from_status : 'NEW'"></span>
                                                                <span class="text-slate-400">&rarr;</span>
                                                                <span class="text-slate-800" x-text="log.to_status"></span>
                                                            </div>
                                                            <p class="text-slate-500 font-medium mt-1" x-text="log.reason ?? 'No reason provided'"></p>
                                                            <div class="flex justify-between items-center text-[10px] text-slate-400 mt-1">
                                                                <span x-text="log.user ? 'Changed by: ' + log.user.name : 'Changed by: System'"></span>
                                                                <span x-text="new Date(log.created_at).toLocaleDateString()"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

</div>
</div>
</div>

@include('reports.partials.script')

</x-erp-layout>


