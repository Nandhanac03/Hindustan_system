<x-erp-layout>
    <x-slot:title>Bulk Unit Setup</x-slot:title>
    <x-slot:headerTitle>Real Estate Projects / Setup</x-slot:headerTitle>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Bulk Generate Floors &amp; Units</h2>
                <p class="text-xs text-slate-500 mt-0.5">Project: <strong class="text-slate-700">{{ $project->name }}</strong></p>
            </div>
            <a href="{{ route('projects.show', $project->id) }}" class="text-xs text-primary font-bold hover:text-indigo-650 transition uppercase tracking-wider">&larr; Back to Grid</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('projects.bulk-generate.store', $project->id) }}" class="space-y-5">
                @csrf

                <!-- Floor range (Start/End) and Units per floor -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="start_floor" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Start Floor (Number)</label>
                        <input id="start_floor" 
                               type="number" 
                               name="start_floor" 
                               value="{{ old('start_floor', 1) }}" 
                               required 
                               placeholder="e.g. -1 for basement"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        @if($errors->has('start_floor'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('start_floor') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="end_floor" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">End Floor (Number)</label>
                        <input id="end_floor" 
                               type="number" 
                               name="end_floor" 
                               value="{{ old('end_floor', $project->total_floors) }}" 
                               required 
                               placeholder="e.g. 5"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        @if($errors->has('end_floor'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('end_floor') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="units_per_floor" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Units Per Floor</label>
                        <input id="units_per_floor" 
                               type="number" 
                               name="units_per_floor" 
                               value="{{ old('units_per_floor', 4) }}" 
                               required 
                               min="1"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        @if($errors->has('units_per_floor'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('units_per_floor') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Unit prefix & Unit Type drop-down -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="unit_prefix" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Unit Number Prefix</label>
                        <input id="unit_prefix" 
                               type="text" 
                               name="unit_prefix" 
                               value="{{ old('unit_prefix', 'A-') }}" 
                               placeholder="e.g. A- or B-"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="unit_type_id" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Unit Type</label>
                        <select id="unit_type_id" 
                                name="unit_type_id" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="">Select Unit Type...</option>
                            @foreach($unitTypes as $type)
                                <option value="{{ $type->id }}" {{ old('unit_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->category }})
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('unit_type_id'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('unit_type_id') }}</p>
                        @endif
                    </div>
                </div>

                <!-- BUA Area, Carpet Area, Unit -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="bua_area" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">BUA Area</label>
                        <input id="bua_area" 
                               type="number" 
                               step="0.01" 
                               name="bua_area" 
                               value="{{ old('bua_area', 1200) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="carpet_area" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Carpet Area (Optional)</label>
                        <input id="carpet_area" 
                               type="number" 
                               step="0.01" 
                               name="carpet_area" 
                               value="{{ old('carpet_area', 1050) }}" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="area_unit" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Area Unit</label>
                        <select id="area_unit" 
                                name="area_unit" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="sqft">Sq. Ft (sqft)</option>
                            <option value="sqm">Sq. Meters (sqm)</option>
                        </select>
                    </div>
                </div>

                <!-- Facing and Base Rate -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="facing" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Facing / Orientation</label>
                        <input id="facing" 
                               type="text" 
                               name="facing" 
                               value="{{ old('facing', 'East') }}" 
                               placeholder="e.g. North-East"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="base_rate" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Base Rate (Per unit area)</label>
                        <input id="base_rate" 
                               type="number" 
                               step="0.01" 
                               name="base_rate" 
                               value="{{ old('base_rate', 4500) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        @if($errors->has('base_rate'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('base_rate') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-600/10 tracking-wide uppercase">
                        Generate Unit Matrix
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-erp-layout>
