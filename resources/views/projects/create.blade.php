<x-erp-layout>
    <x-slot:title>Create Project</x-slot:title>
    <x-slot:headerTitle>Reals Estate Project / Create</x-slot:headerTitle>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Register New Project</h2>
                <p class="text-xs text-slate-500 mt-0.5">Define geographical parameters, total structures, and dates.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="text-xs text-primary font-bold hover:text-indigo-650 transition uppercase tracking-wider">&larr; Back to List</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- System selection (Owner only) -->
                @if(auth()->user()->hasMultiSystemAccess())
                    <div class="space-y-1.5">
                        <label for="system_id" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Operating Entity / System</label>
                        <select id="system_id" 
                                name="system_id" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="">Select Region...</option>
                            @foreach($systems as $sys)
                                <option value="{{ $sys->id }}" {{ old('system_id') == $sys->id ? 'selected' : '' }}>
                                    {{ $sys->name }} ({{ $sys->country }})
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('system_id'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('system_id') }}</p>
                        @endif
                    </div>
                @else
                    <div class="space-y-1.5 bg-slate-50 border border-slate-200/60 p-4 rounded-xl text-xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Operating Entity</span>
                        <strong class="text-slate-700">{{ $systems->first()->name ?? 'System Default' }}</strong>
                    </div>
                @endif

                <!-- Name & Total Floors -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="name" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Name</label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="e.g. Hindustan Emerald Heights"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
                        @if($errors->has('name'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="total_floors" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Total Floors</label>
                        <input id="total_floors" 
                               type="number" 
                               name="total_floors" 
                               value="{{ old('total_floors', 1) }}" 
                               required 
                               min="1"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        @if($errors->has('total_floors'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('total_floors') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Location, City, State/Emirate, Country -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="location" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Address / Location</label>
                        <input id="location" 
                               type="text" 
                               name="location" 
                               value="{{ old('location') }}" 
                               required 
                               placeholder="Sector 62"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="city" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">City</label>
                        <input id="city" 
                               type="text" 
                               name="city" 
                               value="{{ old('city') }}" 
                               required 
                               placeholder="Noida / Dubai"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>
                </div>

              
                    <div class="space-y-1.5">
                        <label for="state_or_emirate" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">State / Emirate</label>
                        <input id="state_or_emirate" 
                               type="text" 
                               name="state_or_emirate" 
                               value="{{ old('state_or_emirate') }}" 
                               required 
                               placeholder="Uttar Pradesh / Dubai"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="country" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Country</label>
                        <input id="country" 
                               type="text" 
                               name="country" 
                               value="{{ old('country') }}" 
                               required 
                               placeholder="India / UAE"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                  
             

                <!-- Dates & Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="start_date" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Start Date</label>
                        <input id="start_date" 
                               type="date" 
                               name="start_date" 
                               value="{{ old('start_date') }}" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="expected_completion_date" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Expected Completion</label>
                        <input id="expected_completion_date" 
                               type="date" 
                               name="expected_completion_date" 
                               value="{{ old('expected_completion_date') }}" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="status" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Status</label>
                        <select id="status" 
                                name="status" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="planning" {{ old('status', 'planning') === 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label for="description" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Description / Notes</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Brief description of project specifications..."
                              class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition">{{ old('description') }}</textarea>
                </div>

                <!-- Project Cover Image -->
                <div class="space-y-1.5">
                    <label for="image" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Cover Image</label>
                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <input id="image" 
                               type="file" 
                               name="image" 
                               accept="image/*"
                               class="text-xs text-slate-650 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                        <span class="text-[10px] text-slate-400 font-medium">Max size: 2MB (JPG, PNG, WEBP)</span>
                    </div>
                    @if($errors->has('image'))
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('image') }}</p>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/95 text-white rounded-xl text-xs font-bold transition shadow-md shadow-primary/10 tracking-wide uppercase">
                        Save Project Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-erp-layout>
