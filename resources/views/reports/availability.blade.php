<x-erp-layout title="Property Availability Matrix" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="reportsApp()">

    @include('reports.partials.nav')

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-6">
        <div class="space-y-6" x-data="{ 
            currentSubTab: 'summary', 
            hoveredUnit: null, 
            hoveredEl: null, 
            unitModalOpen: false, 
            modalLoading: false, 
            selectedUnitDetails: null,
            viewUnitDetails(unitId) {
                this.modalLoading = true;
                this.unitModalOpen = true;
                fetch(`{{ url('units') }}/${unitId}/json`)
                    .then(res => res.json())
                    .then(data => {
                        this.selectedUnitDetails = data.unit;
                        this.modalLoading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.modalLoading = false;
                        this.unitModalOpen = false;
                    });
            }
        }">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest">Property Availability Matrix</h3>
                
                {{-- Sub-tab Navigation --}}
                <div class="flex flex-wrap gap-1 bg-slate-100 p-1 rounded-xl">
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

                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3 text-center">Nos</th>
                                <th class="px-5 py-3 text-right">Built Up Area (In Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (In Sq Ft)</th>
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
                                <tr class="hover:bg-slate-50/60">
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
                            <tr class="bg-slate-50/80 font-bold text-slate-900">
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
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($shops as $index => $row)
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
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No shops matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- APARTMENT SUB-TAB --}}
            <div x-show="currentSubTab === 'apartment'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($apartments as $index => $row)
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
                                    <td colspan="7" class="px-5 py-16 text-center text-slate-400 italic">No apartments matching filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PARKING SUB-TAB --}}
            <div x-show="currentSubTab === 'parking'" class="space-y-4" x-transition style="display: none;">
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Parking No</th>
                                <th class="px-5 py-3">Sold/Booked To</th>
                                <th class="px-5 py-3 text-center">Availability</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-700 font-mono">
                            @forelse($parkings as $index => $row)
                                <tr class="hover:bg-slate-50/60">
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
                <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50/60 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider text-[9px]">
                                <th class="px-5 py-3 w-16 text-center">No</th>
                                <th class="px-5 py-3">Floor</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Door No</th>
                                <th class="px-5 py-3 text-right">Built Up Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-right">Carpet Area (Sq Ft)</th>
                                <th class="px-5 py-3 text-center">Availability</th>
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
                                        if ($pRow['display_name'] === 'P3' || $pRow['units']->count() == 0) continue;
                                        
                                        $dName = $pRow['display_name'];
                                        if ($dName === 'P1') $dName = 'Floor 4';
                                        if ($dName === 'P2') $dName = 'Floor 5';
                                        
                                        $validParking[] = [
                                            'display_name' => $dName,
                                            'is_parking_row' => true,
                                            'columns' => collect($pRow['units'])->sortBy('door_no', SORT_NATURAL | SORT_FLAG_CASE)->values()->all()
                                        ];
                                    }
                                    
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

            <!-- Unit Details Readonly Modal -->
            <div x-show="unitModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity" x-transition style="display: none;">
                <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100" @click.away="unitModalOpen = false">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#a38c29] to-[#b89635] text-white flex items-center justify-between">
                        <div>
                            <span class="block text-[8px] font-bold uppercase tracking-wider text-white/80" x-text="selectedUnitDetails?.floor?.name || 'Unit Detail'"></span>
                            <h4 class="text-sm font-black uppercase tracking-wider text-white" x-text="selectedUnitDetails ? 'Unit ' + selectedUnitDetails.door_no : 'Loading...' "></h4>
                        </div>
                        <button type="button" @click="unitModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition text-xs">✕</button>
                    </div>

                    <div class="p-6 space-y-4 text-xs">
                        <div x-show="modalLoading" class="flex flex-col items-center justify-center py-12 space-y-3">
                            <div class="w-8 h-8 rounded-full border-4 border-amber-500 border-t-transparent animate-spin"></div>
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Fetching Details...</span>
                        </div>

                        <div x-show="!modalLoading && selectedUnitDetails" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Project</span>
                                    <span class="text-slate-800 font-bold" x-text="selectedUnitDetails?.project?.name || 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Unit Type</span>
                                    <span class="text-slate-800 font-bold" x-text="selectedUnitDetails?.unit_type?.name || 'N/A'"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pb-3 border-b border-slate-100">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Built Up Area</span>
                                    <span class="text-slate-800 font-mono font-bold" x-text="selectedUnitDetails?.built_up_area ? Number(selectedUnitDetails.built_up_area).toLocaleString() + ' Sq.ft' : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Carpet Area</span>
                                    <span class="text-slate-800 font-mono font-bold" x-text="selectedUnitDetails?.carpet_area ? Number(selectedUnitDetails.carpet_area).toLocaleString() + ' Sq.ft' : 'N/A'"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Expected Sale Value</span>
                                    <span class="text-[#a38c29] font-mono font-bold" x-text="selectedUnitDetails?.expected_sale_amount ? '₹' + Number(selectedUnitDetails.expected_sale_amount).toLocaleString('en-IN', {minimumFractionDigits: 2}) : 'N/A'"></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Status</span>
                                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-[9px] font-black uppercase text-white shadow-xs"
                                          :class="{'bg-rose-600': selectedUnitDetails?.status === 'sold', 'bg-blue-500': selectedUnitDetails?.status === 'booked', 'bg-amber-500': selectedUnitDetails?.status === 'blocked', 'bg-emerald-500': selectedUnitDetails?.status === 'available', 'bg-slate-700': selectedUnitDetails?.status === 'reserved'}"
                                          x-text="selectedUnitDetails?.status"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                        <button type="button" @click="unitModalOpen = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('reports.partials.script')

</x-erp-layout>
