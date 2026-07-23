<x-erp-layout>
    <x-slot:title>Project Master</x-slot:title>
    <x-slot:headerTitle>Real Estate Projects</x-slot:headerTitle>

    <div x-data="{ 
        editModalOpen: {{ $errors->any() && old('_method') === 'PUT' ? 'true' : 'false' }}, 
        createModalOpen: {{ request()->query('open_create') || ($errors->any() && old('_method') !== 'PUT') ? 'true' : 'false' }},
        deleteModalOpen: false,
        deleteTarget: { id: null, name: '', url: '' },
        editProject: {
            id: '{{ old('id') }}',
            name: '{{ old('name') }}',
            location: '{{ old('location') }}',
            city: '{{ old('city') }}',
            state_or_emirate: '{{ old('state_or_emirate') }}',
            country: '{{ old('country') }}',
            total_floors: '{{ old('total_floors') }}',
            start_date: '{{ old('start_date') }}',
            expected_completion_date: '{{ old('expected_completion_date') }}',
            status: '{{ old('status', 'planning') }}',
            description: '{{ old('description') }}',
            image_url: '{{ old('image_url') }}'
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
            this.$nextTick(() => {
                if (window['ckEditor_ck_edit_project_description']) {
                    window['ckEditor_ck_edit_project_description'].setData(project.description || '');
                }
            });
        },
        openDeleteModal(id, name, url) {
            this.deleteTarget = { id, name, url };
            this.deleteModalOpen = true;
        },
        submitDelete() {
            this.$refs.deleteForm.submit();
        }
    }" class="space-y-6">
        
        <!-- Action Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Entity Projects Directory</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage property project portfolios, structures, and base unit configurations.</p>
            </div>
            
            @can('projects.manage')
            <button @click="createModalOpen = true" class="btn-ripple flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary/95 transition-colors shadow-md shadow-primary/10 tracking-wide uppercase">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Project
            </button>
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
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                No units generated yet.
                            </div>
                        @endif
                    </div>

                    <!-- Footer Controls -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="inline-flex items-center justify-end gap-1.5 ml-auto">
                            @can('projects.manage')


                                <button @click="openEditModal({{ json_encode($proj) }})" title="Edit Project Details" class="p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary hover:text-primary-700 transition inline-flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>

                                <button type="button"
                                    title="Delete Project"
                                    @click="openDeleteModal({{ $proj->id }}, '{{ addslashes($proj->name) }}', '{{ route('projects.destroy', $proj->id) }}')"
                                    class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-sm border border-rose-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
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
        <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="editModalOpen = false">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Project Portfolio</span>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Edit Project Specifications</h2>
                        </div>
                        <button type="button" @click="editModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                    </div>
                </div>

                <form method="POST" :action="'{{ route('projects.update', ['project' => 'PROJECT_ID']) }}'.replace('PROJECT_ID', editProject.id)" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                            <!-- Name & Total Floors -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2 space-y-1.5">
                                    <label for="edit_name" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Project Name</label>
                                    <input id="edit_name" 
                                           type="text" 
                                           name="name" 
                                           x-model="editProject.name"
                                           required 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="edit_total_floors" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Total Floors</label>
                                    <input id="edit_total_floors" 
                                           type="number" 
                                           name="total_floors" 
                                           x-model="editProject.total_floors"
                                           required 
                                           min="1"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>
                            </div>

                            <!-- Location & City -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="edit_location" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Address / Location</label>
                                    <input id="edit_location" 
                                           type="text" 
                                           name="location" 
                                           x-model="editProject.location"
                                           required 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="edit_city" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">City</label>
                                    <input id="edit_city" 
                                           type="text" 
                                           name="city" 
                                           x-model="editProject.city"
                                           required 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>
                            </div>

                            <!-- State & Country -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="edit_state_or_emirate" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">State / Emirate</label>
                                    <input id="edit_state_or_emirate" 
                                           type="text" 
                                           name="state_or_emirate" 
                                           x-model="editProject.state_or_emirate"
                                           required 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="edit_country" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Country</label>
                                    <input id="edit_country" 
                                           type="text" 
                                           name="country" 
                                           x-model="editProject.country"
                                           required 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>
                            </div>

                            <!-- Dates & Status -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label for="edit_start_date" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Start Date</label>
                                    <input id="edit_start_date" 
                                           type="date" 
                                           name="start_date" 
                                           x-model="editProject.start_date"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm cursor-pointer" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="edit_expected_completion_date" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Expected Completion</label>
                                    <input id="edit_expected_completion_date" 
                                           type="date" 
                                           name="expected_completion_date" 
                                           x-model="editProject.expected_completion_date"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm cursor-pointer" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="edit_status" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Project Status</label>
                                    <select id="edit_status" 
                                            name="status" 
                                            x-model="editProject.status"
                                            required 
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all shadow-sm font-semibold">
                                        <option value="planning">Planning</option>
                                        <option value="ongoing">Ongoing</option>
                                        <option value="completed">Completed</option>
                                        <option value="on_hold">On Hold</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-1.5">
                                <label for="ck_edit_project_description" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Description / Specifications</label>
                                <textarea id="ck_edit_project_description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Enter detailed project specifications, structural details, amenities & notes..."
                                          class="ck-editor-field w-full bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 px-4 py-2 focus:outline-none transition resize-none shadow-sm font-semibold"></textarea>
                            </div>

                            <!-- Image Upload -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Project Cover Image</label>
                                <div class="flex items-center gap-4 bg-slate-50 border border-slate-250 rounded-xl p-4 shadow-sm">
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
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md uppercase transition tracking-wider">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>        <!-- New Project Modal -->
        <div x-show="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
            <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="createModalOpen = false">
                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between gap-4">
                        <div>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Project Portfolio</span>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Register New Project</h2>
                        </div>
                        <button type="button" @click="createModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                    </div>
                </div>

                <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                        <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                            <!-- Name & Total Floors -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2 space-y-1.5">
                                    <label for="create_name" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Project Name</label>
                                    <input id="create_name" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           placeholder="e.g. Hindustan Emerald Heights"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="create_total_floors" class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Total Floors</label>
                                    <input id="create_total_floors" 
                                           type="number" 
                                           name="total_floors" 
                                           value="{{ old('total_floors', 1) }}" 
                                           required 
                                           min="1"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>
                            </div>

                            <!-- Location & City -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="create_location" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Address / Location</label>
                                    <input id="create_location" 
                                           type="text" 
                                           name="location" 
                                           value="{{ old('location') }}" 
                                           required 
                                           placeholder="Sector 62"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="create_city" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">City</label>
                                    <input id="create_city" 
                                           type="text" 
                                           name="city" 
                                           value="{{ old('city') }}" 
                                           required 
                                           placeholder="Noida / Dubai"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>
                            </div>

                            <!-- State & Country -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="create_state_or_emirate" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">State / Emirate</label>
                                    <input id="create_state_or_emirate" 
                                           type="text" 
                                           name="state_or_emirate" 
                                           value="{{ old('state_or_emirate') }}" 
                                           required 
                                           placeholder="Uttar Pradesh / Dubai"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="create_country" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Country</label>
                                    <input id="create_country" 
                                           type="text" 
                                           name="country" 
                                           value="{{ old('country') }}" 
                                           required 
                                           placeholder="India / UAE"
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm" />
                                </div>
                            </div>

                            <!-- Dates & Status -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label for="create_start_date" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Start Date</label>
                                    <input id="create_start_date" 
                                           type="date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm cursor-pointer" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="create_expected_completion_date" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Expected Completion</label>
                                    <input id="create_expected_completion_date" 
                                           type="date" 
                                           name="expected_completion_date" 
                                           value="{{ old('expected_completion_date') }}" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm cursor-pointer" />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="create_status" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Status</label>
                                    <select id="create_status" 
                                            name="status" 
                                            required 
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all shadow-sm font-semibold">
                                        <option value="planning" {{ old('status', 'planning') === 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-1.5">
                                <label for="ck_project_create_description" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Description / Notes</label>
                                <textarea id="ck_project_create_description"
                                          name="description"
                                          rows="4"
                                          placeholder="Brief description of project specifications..."
                                          class="ck-editor-field w-full bg-slate-50 border border-slate-255 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 px-4 py-2 focus:outline-none transition resize-none shadow-sm font-semibold">{{ old('description') }}</textarea>
                            </div>

                            <!-- Image Upload -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Cover Image</label>
                                <div class="flex items-center gap-4 bg-slate-50 border border-slate-250 rounded-xl p-4 shadow-sm">
                                    <div class="flex-1 space-y-1">
                                        <input id="create_image" 
                                               type="file" 
                                               name="image" 
                                               accept="image/*"
                                               class="text-xs text-slate-650 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                                        <p class="text-[9px] text-slate-400">Upload a project cover image. Max size: 2MB.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md uppercase transition tracking-wider">
                            Create Project
                        </button>
                    </div>
                </form>
            </div>
        </div>        {{-- ═══════════ DELETE PROJECT CONFIRMATION MODAL ═══════════ --}}
        <div x-show="deleteModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 modal-backdrop" style="display:none;" x-cloak x-transition.opacity>
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="deleteModalOpen = false">

                {{-- Header --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/20">
                    <div class="absolute -top-12 -right-12 w-36 h-36 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute top-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-[#a38c29]/60 to-transparent"></div>
                    <div class="relative z-10 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-[#a38c29]/25 border border-[#a38c29]/40 flex items-center justify-center shadow-lg shadow-[#a38c29]/20 shrink-0 ring-1 ring-[#d9bf3b]/20">
                                <svg class="w-5 h-5 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-[#a38c29]/25 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    Irreversible Action
                                </span>
                                <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Delete Project?</h2>
                                <p class="text-[10px] text-slate-400 mt-0.5 font-medium">This will permanently remove all project data.</p>
                            </div>
                        </div>
                        <button type="button" @click="deleteModalOpen = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-[#a38c29]/30 text-white hover:text-[#d9bf3b] flex items-center justify-center transition focus:outline-none shrink-0 text-xs mt-0.5 border border-white/10 hover:border-[#a38c29]/40">✕</button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-4 bg-gradient-to-b from-slate-50/80 to-white">

                    {{-- Project Name Card --}}
                    <div class="flex items-center gap-3 bg-white border border-[#a38c29]/25 rounded-xl px-4 py-3 shadow-sm shadow-[#a38c29]/5 ring-1 ring-[#a38c29]/10">
                        <div class="w-9 h-9 rounded-lg bg-[#a38c29]/10 border border-[#a38c29]/20 flex items-center justify-center text-[#a38c29] shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-bold text-[#a38c29]/70 uppercase tracking-widest">Project to be Deleted</p>
                            <p class="text-xs font-extrabold text-slate-900 truncate mt-0.5" x-text="deleteTarget.name"></p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-[#a38c29]/10 text-[#8a7522] text-[9px] font-bold uppercase tracking-wider border border-[#a38c29]/20 shrink-0">Target</span>
                    </div>

                    {{-- Warning card — fully themed in gold/amber --}}
                    <div class="rounded-xl border border-[#a38c29]/30 overflow-hidden" style="background: linear-gradient(135deg, rgba(163,140,41,0.06) 0%, rgba(163,140,41,0.03) 100%);">
                        <div class="px-4 py-2.5 border-b border-[#a38c29]/20 flex items-center gap-2" style="background: rgba(163,140,41,0.08);">
                            <svg class="w-3.5 h-3.5 text-[#a38c29] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-[10px] font-extrabold text-[#7a6920] uppercase tracking-wider">Warning — This action cannot be undone</span>
                        </div>
                        <div class="px-4 py-3">
                            <ul class="text-[10px] text-[#8a7522] font-semibold space-y-1.5">
                                <li class="flex items-start gap-2">
                                    <span class="mt-0.5 w-3.5 h-3.5 rounded-full bg-[#a38c29]/15 border border-[#a38c29]/25 flex items-center justify-center shrink-0">
                                        <svg class="w-2 h-2 text-[#a38c29]" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    </span>
                                    All floors and units will be permanently removed
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-0.5 w-3.5 h-3.5 rounded-full bg-[#a38c29]/15 border border-[#a38c29]/25 flex items-center justify-center shrink-0">
                                        <svg class="w-2 h-2 text-[#a38c29]" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    </span>
                                    All rate logs and unit history will be deleted
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-0.5 w-3.5 h-3.5 rounded-full bg-[#a38c29]/15 border border-[#a38c29]/25 flex items-center justify-center shrink-0">
                                        <svg class="w-2 h-2 text-[#a38c29]" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    </span>
                                    Active sales linked to units may be affected
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2.5 bg-white">
                    <form x-ref="deleteForm" :action="deleteTarget.url" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="button" @click="submitDelete()"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#7a6920] text-white text-xs font-bold shadow-md shadow-[#a38c29]/30 uppercase transition tracking-wider flex items-center gap-2 ring-1 ring-[#a38c29]/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Yes, Delete Project
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-erp-layout>
