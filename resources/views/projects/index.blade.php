<x-erp-layout>
    <x-slot:title>Project Master</x-slot:title>
    <x-slot:headerTitle>Real Estate Projects</x-slot:headerTitle>

    <div x-data="{ 
        editModalOpen: false, 
        editProject: {
            id: '',
            name: '',
            location: '',
            city: '',
            state_or_emirate: '',
            country: '',
            rera_number: '',
            total_floors: '',
            start_date: '',
            expected_completion_date: '',
            status: 'planning',
            description: '',
            image_url: ''
        },
        openEditModal(project) {
            this.editProject = { ...project };
            if (this.editProject.start_date) {
                this.editProject.start_date = this.editProject.start_date.substring(0, 10);
            }
            if (this.editProject.expected_completion_date) {
                this.editProject.expected_completion_date = this.editProject.expected_completion_date.substring(0, 10);
            }
            this.editModalOpen = true;
        }
    }" class="space-y-6">
        
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Entity Projects Directory</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage property project portfolios, structures, and base unit configurations.</p>
            </div>
            
            @can('projects.manage')
            <a href="{{ route('projects.create') }}" class="btn-ripple flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary/95 transition-colors shadow-md shadow-primary/10 tracking-wide uppercase">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Project
            </a>
            @endcan
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-xl shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Project Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $proj)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:border-slate-350 hover:shadow-md transition duration-300">
                    <!-- Project Cover Image -->
                    <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                        @if($proj->image_url)
                            <img src="{{ asset('storage/' . $proj->image_url) }}" alt="{{ $proj->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-primary-900 via-primary-850 to-indigo-950 flex flex-col items-center justify-center p-4 relative overflow-hidden">
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-white/5 blur-xl"></div>
                                <div class="absolute -left-10 -bottom-10 w-32 h-32 rounded-full bg-primary-500/10 blur-xl"></div>
                                <svg class="w-12 h-12 text-primary-300/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-[9px] font-bold text-primary-300/40 tracking-widest uppercase">HINDUSTAN ERP</span>
                            </div>
                        @endif

                        <!-- Status Badge Overlay -->
                        @php
                            $statusColors = [
                                'planning' => 'bg-slate-900/80 text-slate-200 border-slate-700/50 backdrop-blur-md',
                                'ongoing' => 'bg-indigo-650 text-white border-indigo-500/30 backdrop-blur-md',
                                'completed' => 'bg-emerald-650 text-white border-emerald-500/30 backdrop-blur-md',
                                'on_hold' => 'bg-amber-600 text-white border-amber-500/30 backdrop-blur-md'
                            ];
                            $colorClass = $statusColors[$proj->status] ?? $statusColors['planning'];
                        @endphp
                        <span class="absolute top-4 right-4 border font-bold uppercase {{ $colorClass }} text-[9px] tracking-wider px-2.5 py-1 rounded-full shadow-sm">
                            {{ str_replace('_', ' ', $proj->status) }}
                        </span>

                        <!-- Project Code Badge -->
                        <span class="absolute bottom-4 left-4 bg-black/70 text-primary-300 border border-primary-500/30 font-bold uppercase text-[9px] tracking-wider px-2 py-0.5 rounded backdrop-blur-sm">
                            {{ $proj->code }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-snug">
                                <a href="{{ route('projects.show', $proj->id) }}" class="hover:text-primary transition-colors">
                                    {{ $proj->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-primary/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $proj->location }}, {{ $proj->city }}, {{ $proj->state_or_emirate }}</span>
                            </p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-3 py-2 border-y border-slate-100 text-[11px]">
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Floors</span>
                                <span class="font-bold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1M9 11h1m4-4h1m-1 4h1"/>
                                    </svg>
                                    {{ $proj->total_floors }} Floors
                                </span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Est. Completion</span>
                                <span class="font-bold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $proj->expected_completion_date?->format('M Y') ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress of Units Available vs Total -->
                        @if($proj->units_count > 0)
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <span>Units Available</span>
                                    <span class="text-slate-800">{{ $proj->available_units_count }} / {{ $proj->units_count }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    @php
                                        $percent = ($proj->available_units_count / $proj->units_count) * 100;
                                    @endphp
                                    <div class="bg-primary h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider italic flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-505" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                No units generated yet.
                            </div>
                        @endif
                    </div>

                    <!-- Footer Controls -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                        
                        <div class="inline-flex items-center justify-end gap-1.5">
                            @can('projects.manage')

                                <a href="{{ route('projects.edit', $proj->id) }}" title="Edit Project Details" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>

                                <button @click="openEditModal({{ json_encode($proj) }})" title="Edit Project Details" class="p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary hover:text-primary-700 transition inline-flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                            @endcan
                            <a href="{{ route('projects.show', $proj->id) }}" title="View Project Unit Grid" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 transition inline-flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2v-2z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-slate-200 p-12 rounded-2xl text-center space-y-4">
                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">No Projects Found</h3>
                        <p class="text-xs text-slate-500 mt-1">Get started by creating a new property development project portfolio.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="bg-white border border-slate-200 p-4 rounded-2xl">
                {{ $projects->links() }}
            </div>
        @endif

        <!-- Edit Project Modal -->
        <div x-show="editModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.away="editModalOpen = false" 
                 class="bg-white rounded-3xl border border-slate-200 shadow-xl max-w-2xl w-full overflow-hidden flex flex-col transform transition-all max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 uppercase tracking-wide">Edit Project Specifications</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Modify structure size, status, and details.</p>
                    </div>
                    <button @click="editModalOpen = false" type="button" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (Form) -->
                <form method="POST" :action="'{{ route('projects.update', ['project' => 'PROJECT_ID']) }}'.replace('PROJECT_ID', editProject.id)" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Name & Total Floors -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 space-y-1.5">
                            <label for="edit_name" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Project Name</label>
                            <input id="edit_name" 
                                   type="text" 
                                   name="name" 
                                   x-model="editProject.name"
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        </div>

                        <div class="space-y-1.5">
                            <label for="edit_total_floors" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Floors</label>
                            <input id="edit_total_floors" 
                                   type="number" 
                                   name="total_floors" 
                                   x-model="editProject.total_floors"
                                   required 
                                   min="1"
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        </div>
                    </div>

                    <!-- Location & City -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="edit_location" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Address / Location</label>
                            <input id="edit_location" 
                                   type="text" 
                                   name="location" 
                                   x-model="editProject.location"
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        </div>

                        <div class="space-y-1.5">
                            <label for="edit_city" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">City</label>
                            <input id="edit_city" 
                                   type="text" 
                                   name="city" 
                                   x-model="editProject.city"
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        </div>
                    </div>

                    <!-- State, Country, RERA -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="edit_state_or_emirate" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">State / Emirate</label>
                            <input id="edit_state_or_emirate" 
                                   type="text" 
                                   name="state_or_emirate" 
                                   x-model="editProject.state_or_emirate"
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        </div>

                        <div class="space-y-1.5">
                            <label for="edit_country" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Country</label>
                            <input id="edit_country" 
                                   type="text" 
                                   name="country" 
                                   x-model="editProject.country"
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition" />
                        </div>

                       
                    </div>

                    <!-- Dates & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label for="edit_start_date" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Start Date</label>
                            <input id="edit_start_date" 
                                   type="date" 
                                   name="start_date" 
                                   x-model="editProject.start_date"
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer" />
                        </div>

                        <div class="space-y-1.5">
                            <label for="edit_expected_completion_date" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Expected Completion</label>
                            <input id="edit_expected_completion_date" 
                                   type="date" 
                                   name="expected_completion_date" 
                                   x-model="editProject.expected_completion_date"
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer" />
                        </div>

                        <div class="space-y-1.5">
                            <label for="edit_status" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Project Status</label>
                            <select id="edit_status" 
                                    name="status" 
                                    x-model="editProject.status"
                                    required 
                                    class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                                <option value="planning">Planning</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5">
                        <label for="edit_description" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Description / Notes</label>
                        <textarea id="edit_description" 
                                  name="description" 
                                  x-model="editProject.description"
                                  rows="3" 
                                  class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition"></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Project Cover Image</label>
                        <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <template x-if="editProject.image_url">
                                <img :src="'{{ asset('storage') }}/' + editProject.image_url" alt="Current Image" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shadow-sm" />
                            </template>
                            <div class="flex-1 space-y-1">
                                <input id="edit_image" 
                                       type="file" 
                                       name="image" 
                                       accept="image/*"
                                       class="text-xs text-slate-650 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                                <p class="text-[9px] text-slate-400">Upload a new image to replace the current one. Max size: 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                        <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/95 text-white rounded-xl text-xs font-bold transition shadow-md shadow-primary/10 tracking-wide uppercase">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-erp-layout>
