<x-erp-layout>
    <x-slot:title>Edit Project</x-slot:title>
    <x-slot:headerTitle>Real Estate Projects / Edit</x-slot:headerTitle>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Edit Project Specifications</h2>
                <p class="text-xs text-slate-500 mt-0.5">Modify structure size, status, and geographical coordinates.</p>
            </div>
            <a href="{{ route('projects.show', $project->id) }}" class="text-xs text-primary font-bold hover:text-indigo-650 transition uppercase tracking-wider">&larr; View Grid</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('projects.update', $project->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Name & Total Floors -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="name" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Name</label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name', $project->name) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        @if($errors->has('name'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="total_floors" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Total Floors</label>
                        <input id="total_floors" 
                               type="number" 
                               name="total_floors" 
                               value="{{ old('total_floors', $project->total_floors) }}" 
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
                               value="{{ old('location', $project->location) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="city" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">City</label>
                        <input id="city" 
                               type="text" 
                               name="city" 
                               value="{{ old('city', $project->city) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="state_or_emirate" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">State / Emirate</label>
                        <input id="state_or_emirate" 
                               type="text" 
                               name="state_or_emirate" 
                               value="{{ old('state_or_emirate', $project->state_or_emirate) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="country" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Country</label>
                        <input id="country" 
                               type="text" 
                               name="country" 
                               value="{{ old('country', $project->country) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="rera_number" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">RERA Number</label>
                        <input id="rera_number" 
                               type="text" 
                               name="rera_number" 
                               value="{{ old('rera_number', $project->rera_number) }}" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                    </div>
                </div>

                <!-- Dates & Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="start_date" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Start Date</label>
                        <input id="start_date" 
                               type="date" 
                               name="start_date" 
                               value="{{ old('start_date', $project->start_date?->toDateString()) }}" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="expected_completion_date" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Expected Completion</label>
                        <input id="expected_completion_date" 
                               type="date" 
                               name="expected_completion_date" 
                               value="{{ old('expected_completion_date', $project->expected_completion_date?->toDateString()) }}" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="status" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Status</label>
                        <select id="status" 
                                name="status" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="planning" {{ old('status', $project->status) === 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="ongoing" {{ old('status', $project->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label for="description" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Description / Notes</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition">{{ old('description', $project->description) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/95 text-white rounded-xl text-xs font-bold transition shadow-md shadow-primary/10 tracking-wide uppercase">
                        Update Project Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-erp-layout>
