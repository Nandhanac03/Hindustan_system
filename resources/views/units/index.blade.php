<x-erp-layout title="Units Directory" headerTitle="Units Directory">



<div class="max-w-[1800px] mx-auto space-y-6" x-data="unitsApp()">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">
                    Project:
                </h1>
                @if(isset($projects) && $projects->count() > 1)
                    <form method="GET" action="{{ route('units.index') }}" class="inline">
                        <select name="project_id" onchange="this.form.submit()" class="px-3 py-1 text-xs font-extrabold uppercase tracking-wide rounded-xl border border-slate-300 bg-white text-primary-700 focus:ring-2 focus:ring-[#a38c29]/50 shadow-sm cursor-pointer">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ $project->id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} {{ $p->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <span class="text-primary-700 text-lg font-extrabold uppercase">{{ $project->name }}</span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage single unit listings, pricing matrices, and floor allocations for <span class="font-bold text-slate-700">{{ $project->name }}</span>.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <template x-if="permissions.manage">
                <div class="flex items-center gap-2">

                    <button @click="openAddModal()" class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Unit
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Notification Toast --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2"
         :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-250 text-emerald-800' : 'bg-rose-50 border-rose-250 text-rose-800'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

    {{-- Project Overview Banner Card --}}
   @php
    $projectImage = $project->image_url
        ? asset('storage/' . $project->image_url)
        : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80';
@endphp
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row gap-6 p-3">
        {{-- Project Image --}}
        <div class="w-full md:w-[300px] h-[240px] rounded-xl overflow-hidden relative flex-shrink-0 bg-slate-100 border border-slate-150">
            <img src="{{ $projectImage }}" alt="{{ $project->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
            <!-- <div class="absolute bottom-3 left-3">
                <span class="text-[9px] font-bold px-2 py-0.5 bg-primary text-white rounded-md uppercase font-mono tracking-wider">{{ $project->code }}</span>
            </div> -->
        </div>

        {{-- Project Information --}}
        <div class="flex-1 flex flex-col justify-between py-1">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight leading-snug">{{ $project->name }}</h2>
                        <p class="text-xs text-slate-450 font-semibold flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-slate-450" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $project->location }}, {{ $project->city }}, {{ $project->state_or_emirate }}, {{ $project->country }}
                        </p>
                    </div>

                    @php
                        $statusColors = [
                            'planning' => 'bg-slate-50 text-slate-700 border-slate-200',
                            'ongoing' => 'bg-primary-50 text-primary-800 border-primary-200',
                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-150',
                            'on_hold' => 'bg-amber-50 text-amber-700 border-amber-150'
                        ];
                        $colorClass = $statusColors[$project->status] ?? $statusColors['planning'];
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="badge border font-extrabold uppercase {{ $colorClass }} text-[10px] px-2.5 py-1 rounded-lg">
                            {{ str_replace('_', ' ', $project->status) }}
                        </span>
                        @can('projects.manage')
    <button
        @click="editProjectModal = true"
        class="px-2.5 py-1 bg-white border border-[#09876B] hover:bg-[#09876B]/10 text-[#09876B] hover:text-[#076852] font-bold rounded-lg transition text-[10px] uppercase tracking-wide flex items-center gap-1"
    >
        <svg class="w-3.5 h-3.5 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Project
    </button>
@endcan
                    </div>
                </div>

                @if($project->description)
                    <div class="mt-3 text-[11px] leading-relaxed text-slate-500 bg-slate-50/50 p-2.5 rounded-lg border border-slate-100">
                        {!! $project->description !!}
                    </div>
                @endif
            </div>
            {{-- Summary of Statistics / RERA --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 border-t border-slate-100 pt-4 mt-4 text-xs font-semibold text-slate-500">
               
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Total Floors</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->total_floors }} Floors</strong>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Start Date</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}</strong>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Target Completion</span>
                    <strong class="text-slate-800 font-bold text-[11px]">{{ $project->expected_completion_date ? \Carbon\Carbon::parse($project->expected_completion_date)->format('d M Y') : 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Selection Filter Bar --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-sm mb-4">
        <form id="unitsCustomerFilterForm" method="GET" action="{{ route('units.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
            @if(request('project_id'))
                <input type="hidden" name="project_id" value="{{ request('project_id') }}">
            @endif
            @if(request('project'))
                <input type="hidden" name="project" value="{{ request('project') }}">
            @endif
            <template x-for="id in localSelectedIds">
                <input type="hidden" name="customer_id[]" :value="id">
            </template>
            
            <div class="flex-1 w-full relative" x-data="{ open: false, search: '' }" @click.outside="open = false">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Filter Units and Excel Report by Customer
                </label>
                
                <div class="relative flex-1">
                    <button type="button" 
                            @click="open = !open; if (open) { $nextTick(() => $refs.customerSearchInput?.focus()); }"
                            :class="open ? 'border-[#a38c29] ring-4 ring-[#a38c29]/10 bg-white shadow-sm' : 'border-slate-300 bg-white hover:bg-slate-50 hover:border-slate-400'"
                            class="w-full min-h-[42px] px-2.5 py-1.5 border rounded-xl text-xs flex items-center justify-between transition-all cursor-pointer text-left shadow-2xs text-slate-700">
                        
                        <template x-if="selectedCustomers.length > 0">
                            <div class="flex flex-wrap items-center gap-1.5 overflow-hidden min-w-0 flex-1">
                                <template x-for="c in selectedCustomers" :key="c.id">
                                    <span class="inline-flex items-center gap-1 pl-2 pr-1 py-1 rounded-lg bg-[#a38c29]/10 text-[#8a7522] border border-[#a38c29]/20 text-[10px] font-bold">
                                        <span x-text="c.name" class="whitespace-nowrap max-w-[150px] truncate"></span>
                                        <button type="button" @click.stop="toggleCustomer(c.id)" class="text-[#8a7522]/70 hover:text-rose-600 hover:bg-rose-50 rounded p-0.5 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                        </template>

                        <template x-if="selectedCustomers.length === 0">
                            <div class="flex items-center gap-1.5 text-slate-500 font-bold px-1">
                                <span>— Filter by Customers —</span>
                            </div>
                        </template>

                        <div class="flex items-center gap-1.5 shrink-0 ml-2">
                            <template x-if="selectedCustomers.length > 0">
                                <span @click.stop="localSelectedIds = []; search = ''; $nextTick(() => document.getElementById('unitsCustomerFilterForm').submit());" class="p-1 text-slate-400 hover:text-rose-600 rounded-full hover:bg-slate-100 transition animate-fade-in" title="Clear selection">
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
                         class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200/90 shadow-2xl rounded-2xl overflow-hidden max-h-80 flex flex-col z-[100]"
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

                        <button type="button" @click="localSelectedIds = []; open = false; search = ''; $nextTick(() => document.getElementById('unitsCustomerFilterForm').submit());"
                                class="w-full px-3.5 py-2 text-left text-xs font-bold text-slate-500 hover:bg-amber-50/50 hover:text-[#8a7522] border-b border-slate-100 flex items-center gap-2 transition cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>— Clear Selection (All Customers) —</span>
                        </button>

                        <div class="overflow-y-auto flex-1 p-1.5 space-y-1">
                            <template x-for="customer in getFilteredCustomersList(search)" :key="customer.id">
                                <button type="button"
                                        @click="toggleCustomer(customer.id); search = ''"
                                        :class="localSelectedIds.includes(customer.id.toString()) ? 'bg-[#a38c29]/10 border-[#a38c29]/20 text-[#8a7522] shadow-xs' : 'hover:bg-slate-50 border-transparent text-slate-700'"
                                        class="w-full p-2 text-left text-xs rounded-xl border transition-all duration-150 flex items-center justify-between gap-2 group cursor-pointer font-medium">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div :class="localSelectedIds.includes(customer.id.toString()) ? 'bg-[#a38c29] text-white' : 'bg-slate-100 text-slate-600 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'"
                                             class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0 transition-colors">
                                             <template x-if="localSelectedIds.includes(customer.id.toString())">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                             </template>
                                             <template x-if="!localSelectedIds.includes(customer.id.toString())">
                                                <span x-text="(customer.name || '?').charAt(0).toUpperCase()"></span>
                                             </template>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-xs truncate leading-snug" :class="localSelectedIds.includes(customer.id.toString()) ? 'text-[#8a7522]' : 'text-slate-800'" x-text="customer.name"></p>
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
            
            <button onclick="exportCurrentTable()" type="button"
                    class="h-[42px] px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow hover:shadow-md flex items-center gap-2 uppercase tracking-wider cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Sale Report 
            </button>
        </form>
    </div>

    {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 transition-all">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 flex-1">
            {{-- Pro Light Search Input --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Search Door No... (e.g. D1, A-101)" 
                       x-model="filters.search" @input.debounce.300ms="fetchUnits()"
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                
                {{-- Clear Button --}}
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                    <button type="button" x-show="filters.search" @click="filters.search = ''; fetchUnits()"
                            class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Floor Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8h1m-1-4h1m-5 4h1m-1-4h1m8 8v-4m0 4h-4m4-4h-4"/></svg>
                </div>
                <select x-model="filters.floor_id" @change="fetchUnits()"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Floors</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Unit Type Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <select x-model="filters.unit_type_id" @change="fetchUnits()"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Types</option>
                    @foreach($unitTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }} ({{ ucfirst($type->category) }})</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                </div>
                <select x-model="filters.status" @change="fetchUnits()"
                        class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                    <option value="">All Statuses</option>
                    <!-- <option value="recently_added">Recently Added</option> -->
                    <option value="available">Available</option>
                    <option value="blocked">Blocked</option>
                    <!-- <option value="booked">Booked</option> -->
                    <option value="sold">Sold</option>
                    <!-- <option value="on_hold">On Hold</option> -->
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        {{-- Reset Filters Button --}}
        <button @click="resetFilters()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
            <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Reset Filters</span>
        </button>
    </div>

    {{-- Units Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        <style>
            #units-table thead th {
                border-color: #8a7522 !important;
            }
            #units-tbody tr:nth-child(even) {
                background-color: #F6F3E9 !important;
            }
            #units-tbody tr:hover {
                background-color: #ebe5d0 !important;
            }
        </style>
        <div class="overflow-auto max-h-[100vh]">
            <table id="units-table" class="w-full text-xs text-left">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">FLOOR</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm whitespace-nowrap">FLOOR NO.</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">TYPE</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">DOOR NO</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">BUILT UP AREA (In Sq Ft)</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">CARPET AREA (In Sq Ft)</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">₹ EXPECTED / SQ.FT</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">₹ EXPECTED SALE</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">₹ SALE PER SQ.FT</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">₹ SALE AMOUNT</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">DIFFERENCE</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm">STATUS</th>
                        <th class="px-3 py-3 border sticky top-0 bg-[#a38c29] shadow-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="units-tbody" x-effect="renderUnitsTable()">
                </tbody>
            </table>
        </div>
        </div>

        <!-- FLOOR MATRIX GRID -->
        <div class="bg-gradient-to-br from-white to-slate-50/80 border border-slate-200/80 rounded-3xl p-6 shadow-md shadow-slate-200/30 space-y-6 relative"
             @mousemove="mouseX = $event.clientX; mouseY = $event.clientY"
             x-data="{
                 panelOpen: false,
                 unit: null,
                 allowedTransitions: [],
                 loading: false,
                 activeTab: 'details',
                 hoveredUnit: null,
                 hoveredEl: null,
                 mouseX: 0,
                 mouseY: 0,
                 fetchUnit(unitId) {
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
                             this.allowedTransitions = data.allowed_transitions;
                             this.loading = false;
                         })
                         .catch(err => {
                             console.error(err);
                             this.loading = false;
                             this.panelOpen = false;
                             alert('Error loading unit details or permission denied.');
                         });
                 }
             }">
            
            <!-- Header with Title and Legend -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#a38c29]/15 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#a38c29]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Floor Matrix – Unit Availability</h3>
                </div>
                
                <!-- Status Legends -->
                <div class="flex flex-wrap items-center gap-3 sm:gap-5 text-[9px] font-black uppercase tracking-wider text-slate-600">
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-emerald-500 shadow-sm border border-emerald-600"></span> Available</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-amber-500 shadow-sm border border-amber-600"></span> Blocked</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-rose-600 shadow-sm border border-rose-700"></span> Sold</span>
                    <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded-md bg-[#0B1E36] shadow-sm border border-slate-800"></span> Parking</span>
                </div>
            </div>

            @php
                // Count units per column (door_no) across all floor rows
                $colCounts = [];
                foreach ($matrixColumns as $doorNo) {
                    $colCounts[$doorNo] = 0;
                }
                foreach ($floorMatrix as $row) {
                    foreach ($row['columns'] as $doorNo => $unit) {
                        if ($unit !== null) {
                            $colCounts[$doorNo] = ($colCounts[$doorNo] ?? 0) + 1;
                        }
                    }
                }

                // Summary aggregates
                $totalUnitsCount = 0;
                $availableCount = 0;
                $blockedCount = 0;
                $soldCount = 0;
                $parkingCount = 0;
                foreach ($floorMatrix as $row) {
                    foreach ($row['columns'] as $u) {
                        if ($u) {
                            $totalUnitsCount++;
                            $st = strtolower($u->status);
                            if ($st === 'sold') $soldCount++;
                            elseif ($st === 'blocked') $blockedCount++;
                            elseif ($st === 'available') $availableCount++;
                        }
                    }
                }
                
                if (!empty($parkingRows)) {
                    foreach ($parkingRows as $pRow) {
                        if ($pRow['display_name'] === 'P3' || $pRow['units']->count() == 0) continue;
                        $parkingCount += $pRow['units']->count();
                    }
                }
            @endphp

            @if(empty($matrixColumns) && empty($parkingRows))
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">No unit data available</p>
                    <p class="text-[10px] text-slate-400 mt-1">Add floors and units to see the property matrix.</p>
                </div>
            @else
                <!-- Calculate Max Columns needed -->
                @php
                    $maxFloorUnits = 0;
                    foreach ($floorMatrix as $row) {
                        $count = collect($row['columns'])->filter()->count();
                        if ($count > $maxFloorUnits) $maxFloorUnits = $count;
                    }
                    $maxParkingUnits = empty($parkingRows) ? 0 : collect($parkingRows)->max(fn($p) => $p['units']->count());
                    $totalGridCols = max($maxFloorUnits, $maxParkingUnits);
                @endphp

                <!-- Table Matrix Container -->
                <div class="overflow-x-auto relative rounded-2xl border border-slate-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] bg-white">
                    <table class="border-collapse w-full" style="min-width: max-content;">
                        <thead>
                            <tr class="border-b border-[#a38c29]/30 bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-widest shadow-[0_1px_2px_0_rgba(0,0,0,0.02)]">
                                <!-- Floor label column -->
                                <th class="p-3.5 text-left sticky left-0 bg-[#a38c29] backdrop-blur-md z-10 min-w-[130px] border-r border-[#a38c29]/30 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                                    Floor / Unit
                                </th>
                                <!-- Dynamic Unit Column Headers -->
                                @for($i = 1; $i <= $totalGridCols; $i++)
                                    <th class="p-3.5 text-center min-w-[90px]">
                                        <span class="block text-white/90">{{ $i }}</span>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white/40">
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

                            <!-- Combined Floor Rows -->
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
                                <tr class="hover:bg-[#a38c29]/5 transition-colors duration-200 group">
                                    <!-- Floor Label -->
                                    <td class="p-3.5 sticky left-0 bg-white/95 group-hover:bg-[#FAF8F2] backdrop-blur-md z-10 border-l-2 border-l-[#a38c29] border-r-2 border-r-[#a38c29] border-b-2 border-b-[#a38c29]/20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] transition-colors duration-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center shrink-0 border border-[#a38c29]/20">
                                                @if($isParkingFloor)
                                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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

                                    <!-- Unit Cells -->
                                    @for ($i = 0; $i < $totalGridCols; $i++)
                                        <td class="p-2.5">
                                            @if ($i < $validUnits->count())
                                                @php $unit = $validUnits[$i]; @endphp
                                                
                                                @if ($isParkingRow)
                                                    @php 
                                                        $isOccupied = in_array(strtolower($unit->status), ['sold', 'blocked']); 
                                                    @endphp
                                                    <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: 'Car Parking Space', status: '{{ $isOccupied ? 'Reserved' : 'Available' }}', price: '₹{{ number_format($unit->expected_sale_amount ?? 300000) }}' }; hoveredEl = $el"
                                                         @mouseleave="hoveredUnit = null"
                                                         @click="fetchUnit({{ $unit->id }})"
                                                         class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl shadow-[0_2px_4px_-1px_rgba(0,0,0,0.05)] border border-transparent transition-all hover:-translate-y-1 hover:shadow-md cursor-pointer duration-200
                                                         @if($isOccupied) bg-[#0B1E36] text-white shadow-slate-300/50 hover:shadow-slate-400/50 hover:border-slate-500 @else bg-emerald-500 text-white shadow-emerald-200/50 hover:shadow-emerald-300/50 hover:border-emerald-400 @endif">
                                                        <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-sm">{{ $unit->door_no }}</span>
                                                        <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-sm">Parking</span>
                                                    </div>
                                                @else
                                                    @php
                                                        $status = strtolower($unit->status);
                                                        $isSold     = in_array($status, ['sold']);
                                                        $isBlocked  = ($status === 'blocked');
                                                        $custName = '';
                                                        if ($isSold) {
                                                            if ($unit->sale && $unit->sale->customer) {
                                                                $custName = $unit->sale->customer->name;
                                                            } elseif ($unit->saleUnits && $unit->saleUnits->isNotEmpty()) {
                                                                $activeSaleUnit = $unit->saleUnits->first(function($su) {
                                                                    return $su->sale && $su->sale->status === 'active';
                                                                });
                                                                if ($activeSaleUnit && $activeSaleUnit->sale && $activeSaleUnit->sale->customer) {
                                                                    $custName = $activeSaleUnit->sale->customer->name;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <div @mouseenter="hoveredUnit = { door_no: '{{ addslashes($unit->door_no) }}', floor: '{{ addslashes($row['display_name']) }}', area: '{{ $unit->built_up_area ? $unit->built_up_area.' sq.ft' : 'N/A' }}', status: '{{ ucfirst($unit->status) }}', price: '₹{{ number_format($unit->expected_sale_amount ?? 0) }}', customer: '{{ addslashes($custName) }}' }; hoveredEl = $el"
                                                         @mouseleave="hoveredUnit = null"
                                                         @click="fetchUnit({{ $unit->id }})"
                                                         class="w-full min-w-[85px] py-2 px-2 flex flex-col items-center justify-center rounded-xl shadow-[0_2px_6px_-2px_rgba(0,0,0,0.1)] border border-transparent transition-all hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-200/40 cursor-pointer duration-200
                                                         @if ($isSold) bg-rose-600 text-white shadow-rose-200/50 hover:shadow-rose-300/50 hover:border-rose-400
                                                         @elseif ($isBlocked) bg-amber-500 text-white shadow-amber-200/50 hover:shadow-amber-300/50 hover:border-amber-400
                                                         @else bg-emerald-500 text-white shadow-emerald-200/50 hover:shadow-emerald-300/50 hover:border-emerald-400 @endif">

                                                        <span class="text-[11px] font-black uppercase font-sans tracking-wide leading-tight drop-shadow-sm">
                                                            {{ $unit->door_no }}
                                                        </span>
                                                        <span class="text-[8.5px] font-bold mt-1 font-mono leading-none opacity-90 drop-shadow-sm">
                                                            {{ $unit->unitType?->name ? (strtolower($unit->unitType->name) === 'flat' ? 'Apartment' : ucfirst($unit->unitType->name)) : 'N/A' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="w-full min-w-[85px] h-12 border-2 border-dashed border-slate-100/80 rounded-xl bg-slate-50/30"></div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

                <!-- Footer Summary Bar matching the design -->
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-6 py-6 px-8 bg-gradient-to-r from-[#FAF8F2] to-white border border-[#EFECE1] shadow-sm rounded-3xl items-center">
                    
                    <!-- Summary Icon Column -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#FAF0D7] text-[#9C6D3B] flex items-center justify-center shadow-sm border border-[#EFECE1]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/>
                            </svg>
                        </div>
                        <span class="font-extrabold text-[#0B1E36] text-sm uppercase tracking-widest">Summary</span>
                    </div>

                    <!-- Total Units -->
                    <div class="pl-2 border-l border-slate-200">
                        <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Total Units</span>
                        <span class="text-slate-800 font-black text-xl font-mono mt-0.5 block">{{ $totalUnitsCount }}</span>
                    </div>

                    <!-- Available -->
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <span class="w-5 h-5 rounded-lg bg-emerald-500 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Available</span>
                            <span class="text-slate-800 font-black text-xl font-mono mt-0.5 block">{{ $availableCount }}</span>
                        </div>
                    </div>

                    <!-- Pending / Blocked -->
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <span class="w-5 h-5 rounded-lg bg-amber-500 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Blocked</span>
                            <span class="text-slate-800 font-black text-xl font-mono mt-0.5 block">{{ $blockedCount }}</span>
                        </div>
                    </div>

                    <!-- Sold -->
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <span class="w-5 h-5 rounded-lg bg-rose-600 shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Sold</span>
                            <span class="text-slate-800 font-black text-xl font-mono mt-0.5 block">{{ $soldCount }}</span>
                        </div>
                    </div>

                    <!-- Parking -->
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <span class="w-5 h-5 rounded-lg bg-[#0B1E36] shadow-sm shrink-0"></span>
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wider">Parking</span>
                            <span class="text-slate-800 font-black text-xl font-mono mt-0.5 block">{{ $parkingCount }}</span>
                        </div>
                    </div>
                </div>
            @endif
            
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
                                                    <span x-show="getUnitActiveSale(unit)" class="text-[9px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded" x-text="getUnitActiveSale(unit) ? getUnitActiveSale(unit).sale_number : ''"></span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3.5">
                                                    <div class="col-span-2">
                                                        <span class="text-emerald-650 block font-medium">Customer / Sold To</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="getUnitActiveSale(unit) && getUnitActiveSale(unit).customer ? getUnitActiveSale(unit).customer.name : '-'"></strong>
                                                    </div>
                                                    <div x-show="getUnitActiveSale(unit) && getUnitActiveSale(unit).sale_date">
                                                        <span class="text-emerald-650 block font-medium">Sale Date</span>
                                                        <strong class="text-slate-800 font-extrabold" x-text="getUnitActiveSale(unit) ? new Date(getUnitActiveSale(unit).sale_date).toLocaleDateString() : 'N/A'"></strong>
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
                                                        <strong class="text-slate-800 font-extrabold" x-text="unit ? '₹' + Number(unit.sale_rate_per_sqft || (getUnitActiveSale(unit) ? getUnitActiveSale(unit).rate_per_sqft : 0)).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Expected Price</span>
                                                        <strong class="text-slate-850 font-extrabold" x-text="unit ? '₹' + Number(unit.expected_sale_amount || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div>
                                                        <span class="text-emerald-650 block font-medium">Actual Sale Price</span>
                                                        <strong class="text-emerald-800 font-extrabold" x-text="unit ? '₹' + Number(unit.sale_amount || (getUnitActiveSale(unit) ? getUnitActiveSale(unit).sale_amount : 0)).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div class="col-span-2 bg-rose-50 border border-rose-100 rounded-lg p-2.5">
                                                        <span class="text-rose-600 block font-bold text-[9px] uppercase tracking-wider">Shortfall / Difference</span>
                                                        <strong class="text-rose-750 font-extrabold text-sm" x-text="unit ? '₹' + Number(unit.difference || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div x-show="getUnitActiveSale(unit)">
                                                        <span class="text-emerald-650 block font-medium">Total Amount (Tax Inc.)</span>
                                                        <strong class="text-emerald-850 font-extrabold" x-text="getUnitActiveSale(unit) ? '₹' + Number(getUnitActiveSale(unit).total_amount || 0).toLocaleString('en-US') : ''"></strong>
                                                    </div>
                                                    <div x-show="getUnitActiveSale(unit)">
                                                        <span class="text-emerald-650 block font-medium">Remaining Bal.</span>
                                                        <strong class="text-rose-750 font-extrabold" x-text="getUnitActiveSale(unit) ? '₹' + Number(getUnitActiveSale(unit).remaining_balance || 0).toLocaleString('en-US') : ''"></strong>
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
    


    {{-- Modals Wrapper to prevent space-y-6 margin inheritance --}}
    <div>

    {{-- ═══════════════════════════════════════════
         MODAL 1: ADD UNIT MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.add.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        {{-- Backdrop --}}
        <div x-show="modals.add.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="closeAddModal()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="modals.add.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Single Unit Setup</p>
                        <h2 class="text-lg font-extrabold text-white">Add New Unit</h2>
                    </div>
                    <button @click="closeAddModal()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitAddUnit()" class="flex flex-col overflow-hidden max-h-[calc(90vh-100px)]">
                <div class="p-6 space-y-4 overflow-y-auto">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Floor <span class="text-rose-500">*</span></label>
                            <select x-model="forms.add.floor_id"
                                    :class="errors.floor_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                    class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <option value="">Select Floor...</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.floor_id) ? errors.floor_id[0] : errors.floor_id"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type <span class="text-rose-500">*</span></label>
                            <select x-model="forms.add.unit_type_id"
                                    :class="errors.unit_type_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                    class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <option value="">Select Type...</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.unit_type_id) ? errors.unit_type_id[0] : errors.unit_type_id"></p></template>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Door No (e.g. A-404) <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="forms.add.door_no" placeholder="Enter door number..."
                               :class="errors.door_no ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        <template x-if="errors.door_no"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.door_no) ? errors.door_no[0] : errors.door_no"></p></template>
                    </div>

                    {{-- Area fields: hidden for Parking type --}}
                    <template x-if="!isParking(forms.add.unit_type_id)">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Built Up Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.add.built_up_area" placeholder="e.g. 1200"
                                       :class="errors.built_up_area ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                       class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.built_up_area"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.built_up_area) ? errors.built_up_area[0] : errors.built_up_area"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Carpet Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.add.carpet_area" placeholder="e.g. 1000"
                                       :class="errors.carpet_area ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                       class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.carpet_area) ? errors.carpet_area[0] : errors.carpet_area"></p></template>
                            </div>
                        </div>
                    </template>

                    {{-- Rate field: hidden for Parking; show direct Expected Sale instead --}}
                    <template x-if="!isParking(forms.add.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Rate per Sq Ft (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="forms.add.expected_rate_per_sqft" placeholder="e.g. 4500"
                                   :class="errors.expected_rate_per_sqft ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                   class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.expected_rate_per_sqft"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.expected_rate_per_sqft) ? errors.expected_rate_per_sqft[0] : errors.expected_rate_per_sqft"></p></template>
                        </div>
                    </template>

                    {{-- Parking: direct Expected Sale field (no area, no rate) --}}
                    <template x-if="isParking(forms.add.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Sale Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="forms.add.expected_sale_amount" placeholder="e.g. 300000"
                                   :class="errors.expected_sale_amount ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                   class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <p class="text-[10px] text-slate-400 italic">Auto-calculation not applicable for Parking.</p>
                            <template x-if="errors.expected_sale_amount"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.expected_sale_amount) ? errors.expected_sale_amount[0] : errors.expected_sale_amount"></p></template>
                        </div>
                    </template>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="closeAddModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide">Add Unit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 2: EDIT UNIT & ACTIONS MODAL
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.edit.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-cloak>
        {{-- Backdrop --}}
        <div x-show="modals.edit.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="closeEditModal()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="modals.edit.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh]"
             @click.stop>

            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Unit Management</p>
                        <h2 class="text-lg font-extrabold text-white" x-text="'Unit: ' + (activeUnit.door_no || '—')"></h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="badge-pill text-[9px]" :class="getStatusBadgeClass(activeUnit.status)" x-text="activeUnit.status"></span>
                            <span class="text-slate-400 text-[10px]" x-text="activeUnit.floor ? activeUnit.floor.name : ''"></span>
                            <span class="text-slate-600 text-[10px]">•</span>
                            <span class="text-slate-400 text-[10px]" x-text="activeUnit.unit_type ? activeUnit.unit_type.name : ''"></span>
                        </div>
                    </div>
                    <button @click="closeEditModal()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body: Two Panes --}}
            <div class="grid lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100 overflow-y-auto max-h-[calc(90vh-130px)]">

                {{-- LEFT PANE: Edit Form --}}
                <div class="p-6 space-y-4 overflow-y-auto">
                    <template x-if="permissions.manage">
                        <form @submit.prevent="submitEditUnit()">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Floor <span class="text-rose-500">*</span></label>
                                        <select x-model="forms.edit.floor_id"
                                                :class="errors.floor_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            @foreach($floors as $floor)
                                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                            @endforeach
                                        </select>
                                        <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.floor_id) ? errors.floor_id[0] : errors.floor_id"></p></template>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type <span class="text-rose-500">*</span></label>
                                        <select x-model="forms.edit.unit_type_id"
                                                :class="errors.unit_type_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            @foreach($unitTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.unit_type_id) ? errors.unit_type_id[0] : errors.unit_type_id"></p></template>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Door No <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="forms.edit.door_no"
                                           :class="errors.door_no ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                           class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    <template x-if="errors.door_no"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.door_no) ? errors.door_no[0] : errors.door_no"></p></template>
                                </div>

                                {{-- Area fields: hidden for Parking --}}
                                <template x-if="!isParking(forms.edit.unit_type_id)">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Built Up Area (Sq Ft)</label>
                                            <input type="number" step="0.01" x-model="forms.edit.built_up_area"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            <template x-if="errors.built_up_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.built_up_area[0]"></p></template>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Carpet Area (Sq Ft)</label>
                                            <input type="number" step="0.01" x-model="forms.edit.carpet_area"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                                        </div>
                                    </div>
                                </template>

                                {{-- Parking: editable Expected Sale Amount --}}
                                <template x-if="isParking(forms.edit.unit_type_id)">
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Sale Amount (₹) <span class="text-rose-500">*</span></label>
                                        <input type="number" step="0.01" x-model="forms.edit.expected_sale_amount"
                                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                        <p class="text-[10px] text-slate-400 italic">Auto-calculation not applicable for Parking.</p>
                                        <template x-if="errors.expected_sale_amount"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_sale_amount[0]"></p></template>
                                    </div>
                                </template>

                                {{-- Pricing Summary Card --}}
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Pricing Summary</p>
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <template x-if="!isParking(forms.edit.unit_type_id)">
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Expected Rate</span>
                                                <strong class="text-slate-800 text-xs" x-text="activeUnit.expected_rate_per_sqft ? '₹' + Number(activeUnit.expected_rate_per_sqft).toLocaleString() : 'N/A'"></strong>
                                            </div>
                                        </template>
                                        <div>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Expected Sale</span>
                                            <strong class="text-emerald-700 text-xs" x-text="activeUnit.expected_sale_amount ? '₹' + Number(activeUnit.expected_sale_amount).toLocaleString() : 'N/A'"></strong>
                                        </div>
                                    </div>
                                    <template x-if="activeUnit.sale_rate_per_sqft">
                                        <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-200 pt-3">
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Sale Rate</span>
                                                <strong class="text-slate-900 text-xs" x-text="'₹' + Number(activeUnit.sale_rate_per_sqft).toLocaleString()"></strong>
                                            </div>
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Sale Amount</span>
                                                <strong class="text-emerald-700 text-xs" x-text="'₹' + Number(activeUnit.sale_amount).toLocaleString()"></strong>
                                            </div>
                                            <div class="col-span-2 border-t border-slate-200 pt-2">
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Difference</span>
                                                <strong class="text-rose-600 text-xs" x-text="'₹' + Number(activeUnit.difference).toLocaleString()"></strong>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="submit"
                                    class="w-full py-2.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-xs font-bold transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide">
                                    Save Details
                                </button>
                            </div>
                        </form>
                    </template>
                    <template x-if="!permissions.manage">
                        <div class="space-y-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block border-b pb-1">Unit Info (Read-Only)</span>
                            <div class="grid grid-cols-2 gap-4 text-xs font-medium">
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Floor</p><p class="text-slate-900" x-text="activeUnit.floor ? activeUnit.floor.name : ''"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Type</p><p class="text-slate-900" x-text="activeUnit.unit_type ? activeUnit.unit_type.name : ''"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Built Up Area</p><p class="text-slate-900" x-text="activeUnit.built_up_area + ' Sq Ft'"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Carpet Area</p><p class="text-slate-900" x-text="activeUnit.carpet_area ? activeUnit.carpet_area + ' Sq Ft' : 'N/A'"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Expected Rate</p><p class="text-slate-900" x-text="'₹' + Number(activeUnit.expected_rate_per_sqft).toLocaleString()"></p></div>
                                <div><p class="text-slate-450 font-bold uppercase text-[9px] tracking-wider">Expected Sale</p><p class="text-slate-900" x-text="'₹' + Number(activeUnit.expected_sale_amount).toLocaleString()"></p></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- RIGHT PANE: Status Transitions & Rate Updates --}}
                <div class="p-6 space-y-5 overflow-y-auto">
                    {{-- Status Transitions --}}
                    <div class="space-y-3">
                        <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Status Transitions</p>

                        {{-- Inline error shown inside the modal (not behind it) --}}
                        <template x-if="statusError">
                            <div class="px-3 py-2 bg-rose-50 border border-rose-200 rounded-lg text-[11px] font-bold text-rose-700 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span x-text="statusError"></span>
                            </div>
                        </template>

                        <div class="flex flex-wrap gap-2">
                            {{-- Block Unit button — only shown when unit is available --}}
                            <template x-if="activeUnit.status === 'available'">
                                <button type="button" @click="transitionStatus('blocked')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg text-xs transition uppercase tracking-wider shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    Block Unit
                                </button>
                            </template>
                            <template x-for="state in allowedTransitions" :key="state">
                                <template x-if="!(activeUnit.status === 'available' && state === 'blocked')">
                                    <button type="button" @click="transitionStatus(state)"
                                            class="px-3 py-1.5 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-lg text-xs transition">
                                        Move to <span class="capitalize font-bold" x-text="state"></span>
                                    </button>
                                </template>
                            </template>
                            <template x-if="activeUnit.status === 'sold' && allowedTransitions.includes('available')">
                                <label class="inline-flex items-center gap-1.5 text-xs text-slate-500 ml-1 cursor-pointer">
                                    <input type="checkbox" x-model="forms.status.is_resale" class="rounded text-[#a38c29] focus:ring-[#a38c29]/20">
                                    <span class="font-bold">Resale Flag</span>
                                </label>
                            </template>
                            <template x-if="allowedTransitions.length === 0 && activeUnit.status !== 'available'">
                                <p class="text-xs text-slate-400 italic">No transitions available.</p>
                            </template>
                        </div>
                        <div x-show="allowedTransitions.length > 0 || activeUnit.status === 'available'">
                            <input type="text" x-model="forms.status.reason" placeholder="Reason for transition (optional)..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>

                    {{-- Rate Update Form --}}
                    <template x-if="permissions.rateManage">
                        <div class="space-y-3 border-t border-slate-100 pt-4">
                            <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Update Base Rate</p>
                            <form @submit.prevent="submitUpdateRate()">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">New Rate (₹)</label>
                                            <input type="number" step="0.01" name="amount" x-model="forms.rate.rate" placeholder="e.g. 5000"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            <span class="text-[10px] text-rose-500 font-bold block mt-1" x-show="errors.rate" x-text="errors.rate[0]"></span>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Effective From</label>
                                            <input type="date" x-model="forms.rate.effective_from"
                                                class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                            <span class="text-[10px] text-rose-500 font-bold block mt-1" x-show="errors.effective_from" x-text="errors.effective_from[0]"></span>
                                        </div>
                                    </div>
                                    <input type="text" x-model="forms.rate.reason" placeholder="Reason for rate change..."
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                    <button type="submit"
                                        class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition uppercase tracking-wide">
                                        Update Rate
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>

                    {{-- Pricing History Logs --}}
                    <div class="space-y-3 border-t border-slate-100 pt-4">
                        <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Pricing Log History</p>
                        <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                            <template x-for="log in activeUnit.rate_logs" :key="log.id">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[10px] space-y-1">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-900 text-xs" x-text="'₹' + Number(log.rate).toLocaleString()"></span>
                                        <span class="text-slate-400 font-medium" x-text="formatDate(log)"></span>
                                    </div>
                                    <p class="text-slate-500" x-text="log.reason || 'No reason provided'"></p>
                                    <p class="text-[9px] text-slate-400 font-semibold" x-text="'By: ' + (log.user ? log.user.name : 'System')"></p>
                                </div>
                            </template>
                            <template x-if="!activeUnit.rate_logs || activeUnit.rate_logs.length === 0">
                                <p class="text-xs text-slate-400 italic">No rate changes logged.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                <button type="button" @click="closeEditModal()"
                    class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MODAL 3: BULK ADD UNITS
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.bulk.open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        {{-- Backdrop --}}
        <div x-show="modals.bulk.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="closeBulkModal()"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="modals.bulk.open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.stop>
            
            {{-- Dark Header --}}
            <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 flex-shrink-0">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#a38c29]/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[#a38c29] text-[10px] font-semibold uppercase tracking-widest mb-1">Bulk Generation</p>
                        <h2 class="text-lg font-extrabold text-white">Bulk Add Units</h2>
                    </div>
                    <button @click="closeBulkModal()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitBulkAdd()" class="flex flex-col overflow-hidden max-h-[calc(90vh-100px)]">
                <div class="p-6 space-y-4 overflow-y-auto">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Target Floor <span class="text-rose-500">*</span></label>
                            <select x-model="forms.bulk.floor_id"
                                    :class="errors.floor_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                    class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <option value="">Select Floor...</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.floor_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.floor_id) ? errors.floor_id[0] : errors.floor_id"></p></template>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Type <span class="text-rose-500">*</span></label>
                            <select x-model="forms.bulk.unit_type_id"
                                    :class="errors.unit_type_id ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300 bg-white'"
                                    class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <option value="">Select Type...</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <template x-if="errors.unit_type_id"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.unit_type_id) ? errors.unit_type_id[0] : errors.unit_type_id"></p></template>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Prefix (e.g. A-)</label>
                            <input type="text" x-model="forms.bulk.unit_prefix" placeholder="A-" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.unit_prefix"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.unit_prefix) ? errors.unit_prefix[0] : errors.unit_prefix"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Starting Num <span class="text-rose-500">*</span></label>
                            <input type="number" x-model="forms.bulk.start_number"
                                   :class="errors.start_number ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                   class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.start_number"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.start_number) ? errors.start_number[0] : errors.start_number"></p></template>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Count <span class="text-rose-500">*</span></label>
                            <input type="number" x-model="forms.bulk.count"
                                   :class="errors.count ? 'border-rose-500 ring-2 ring-rose-500/20 bg-rose-50/30' : 'border-slate-300'"
                                   class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.count"><p class="text-[10px] text-rose-600 font-semibold mt-1" x-text="Array.isArray(errors.count) ? errors.count[0] : errors.count"></p></template>
                        </div>
                    </div>

                    {{-- Area fields: hidden for Parking --}}
                    <template x-if="!isParking(forms.bulk.unit_type_id)">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Built Up Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.bulk.built_up_area" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.built_up_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.built_up_area[0]"></p></template>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Carpet Area (Sq Ft)</label>
                                <input type="number" step="0.01" x-model="forms.bulk.carpet_area" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                                <template x-if="errors.carpet_area"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.carpet_area[0]"></p></template>
                            </div>
                        </div>
                    </template>

                    {{-- Expected Rate: hidden for Parking --}}
                    <template x-if="!isParking(forms.bulk.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Rate per Sq Ft (₹)</label>
                            <input type="number" step="0.01" x-model="forms.bulk.expected_rate_per_sqft" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.expected_rate_per_sqft"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_rate_per_sqft[0]"></p></template>
                        </div>
                    </template>

                    {{-- Parking: Expected Sale Amount --}}
                    <template x-if="isParking(forms.bulk.unit_type_id)">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expected Sale Amount (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" x-model="forms.bulk.expected_sale_amount" placeholder="e.g. 300000" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                            <template x-if="errors.expected_sale_amount"><p class="text-[10px] text-rose-600 font-semibold" x-text="errors.expected_sale_amount[0]"></p></template>
                        </div>
                    </template>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                    <button type="button" @click="closeBulkModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 border border-slate-200 hover:bg-slate-50 rounded-lg transition uppercase tracking-wide">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold rounded-lg transition shadow-lg shadow-[#a38c29]/30 uppercase tracking-wide">Generate Units</button>
                </div>
            </form>
        </div>
    </div>



{{-- ═══════════════════════ EDIT PROJECT MODAL ═══════════════════════ --}}
<div x-show="editProjectModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
    <div x-show="editProjectModal" class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-fade-in-up" @click.away="editProjectModal = false">
        {{-- Header --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 flex-shrink-0">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex items-center justify-between gap-4">
                <div>
                    <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Edit Project</span>
                    <h2 class="text-xs font-extrabold text-white mt-1">{{ $project->name }}</h2>
                </div>
                <button type="button" @click="editProjectModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
            </div>
        </div>

        <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-h-0">
            @csrf
            @method('PUT')

            {{-- Single-pane body --}}
            <div class="p-5 space-y-4 overflow-y-auto flex-1 min-h-0">
                {{-- Media & Image --}}
                <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 space-y-3">
                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest">Media & Image</p>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0 relative">
                            <img x-show="!imagePreview" src="{{ $projectImage }}" class="w-full h-full object-cover" alt="Project image">
                            <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover" x-cloak>
                        </div>
                        <div class="flex-1">
                            <label class="cursor-pointer inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white text-[10px] font-bold rounded-lg transition shadow-sm uppercase tracking-wide">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Upload
                                <input type="file" name="image" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file);">
                            </label>
                            <p class="text-[9px] text-slate-400 mt-1">JPG, PNG up to 2MB</p>
                        </div>
                    </div>
                </div>

                {{-- Project Details Section --}}
                <div class="space-y-3">
                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-100 pb-1">Project Details</p>
                    
                   
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Name</label>
                            <input type="text" name="name" value="{{ old('name', $project->name) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                       
                    

                    <div class="space-y-1">
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Location / Address</label>
                        <input type="text" name="location" value="{{ old('location', $project->location) }}"
                            class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">City</label>
                            <input type="text" name="city" value="{{ old('city', $project->city) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">State</label>
                            <input type="text" name="state_or_emirate" value="{{ old('state_or_emirate', $project->state_or_emirate) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Country</label>
                            <input type="text" name="country" value="{{ old('country', $project->country) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>
                </div>

                {{-- Status & Scope Section --}}
                <div class="space-y-3">
                    <p class="text-[9px] font-bold text-[#a38c29] uppercase tracking-widest border-b border-slate-100 pb-1">Status & Scope</p>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Status</label>
                            <select name="status" class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white">
                                @foreach(['planning' => 'Planning', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'on_hold' => 'On Hold'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $project->status) == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Total Floors</label>
                            <input type="number" name="total_floors" value="{{ old('total_floors', $project->total_floors) }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-3">
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Target Completion</label>
                            <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', $project->expected_completion_date ? \Carbon\Carbon::parse($project->expected_completion_date)->format('Y-m-d') : '') }}"
                                class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition">
                        </div>
                    </div>

                    <div class="space-y-1 mt-3">
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">Project Description</label>
                        <textarea name="description" id="ck_units_project_description" rows="4"
                            placeholder="Write a detailed project description..."
                            class="ck-editor-field w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition resize-none">{{ old('description', $project->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50 flex-shrink-0">
                <button type="button" @click="editProjectModal = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md uppercase transition tracking-wider">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>















    <!-- MODAL 4: DELETE CONFIRMATION -->
    <div x-show="modals.delete.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" @click.away="closeDeleteModal()">
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-rose-900/10">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div>
                        <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Warning</span>
                        <h2 class="text-xs font-extrabold text-white uppercase tracking-wider mt-1">Delete Unit</h2>
                    </div>
                    <button type="button" @click="closeDeleteModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>
            <div class="p-6 space-y-3 font-sans text-xs bg-slate-50/50">
                <p class="text-xs text-slate-655 font-medium">
                    Are you sure you want to delete unit <strong class="text-slate-900" x-text="activeUnit.door_no"></strong>?
                </p>
                <p class="text-[10px] text-rose-550 font-bold uppercase tracking-wider">This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-2 bg-slate-50">
                <button type="button" @click="closeDeleteModal()" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Cancel</button>
                <button type="button" @click="submitDelete()" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md uppercase transition tracking-wider">Confirm Delete</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         VIEW UNIT MODAL
    ═══════════════════════════════════════════ --}}
    <!-- VIEW UNIT MODAL -->
    <div x-show="modals.view.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-cloak>
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-fade-in-up" @click.away="modals.view.open = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 flex-shrink-0">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 flex items-center justify-center text-[#d9bf3b] shadow-inner shadow-[#a38c29]/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">View Info</span>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5">Unit Details & Specs</h2>
                        </div>
                    </div>
                    <button type="button" @click="modals.view.open = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-150 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Door / Unit No</span>
                        <span class="text-base font-extrabold text-slate-900" x-text="viewTarget?.door_no"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono uppercase inline-block mt-0.5"
                              :class="getStatusBadgeClass(viewTarget?.status)"
                              x-text="viewTarget?.status"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Floor</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.floor?.name"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Unit Type</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block" x-text="viewTarget?.unit_type?.name"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Built-up Area (BUA)</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono" x-text="viewTarget?.built_up_area != null ? Number(viewTarget.built_up_area).toLocaleString() + ' Sq Ft' : 'N/A'"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Carpet Area</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono" x-text="viewTarget?.carpet_area != null ? Number(viewTarget.carpet_area).toLocaleString() + ' Sq Ft' : 'N/A'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Rate / Sq Ft</span>
                        <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono" x-text="(viewTarget?.unit_type?.name?.toLowerCase() === 'parking' || viewTarget?.unit_type?.category?.toLowerCase() === 'parking') ? 'N/A' : (viewTarget?.expected_rate_per_sqft != null ? '₹' + Number(viewTarget.expected_rate_per_sqft).toLocaleString() : 'N/A')"></span>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white shadow-2xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Expected Sale Amount</span>
                        <span class="text-xs font-bold text-emerald-700 mt-0.5 block font-mono" x-text="viewTarget?.expected_sale_amount != null ? '₹' + Number(viewTarget.expected_sale_amount).toLocaleString() : 'N/A'"></span>
                    </div>
                </div>

                <template x-if="viewTarget?.sale_amount || (viewTarget?.gst_behavior && viewTarget?.gst_behavior !== 'none') || viewTarget?.booking">
                    <div class="p-3.5 rounded-xl border border-amber-200/80 bg-amber-50/50 shadow-2xs space-y-2.5 mt-2">
                        <div class="flex items-center justify-between border-b border-amber-200/60 pb-1.5">
                            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Active Sale & GST Handling</span>
                            <span class="text-[9px] font-mono px-2 py-0.5 rounded-full font-bold uppercase"
                                  :class="viewTarget?.gst_behavior === 'inclusive' ? 'bg-amber-100 text-amber-800' : (viewTarget?.gst_behavior === 'exclusive' ? 'bg-purple-100 text-purple-800' : 'bg-[#a38c29] text-white')"
                                  x-text="viewTarget?.gst_behavior === 'inclusive' ? 'GST Included (18%)' : (viewTarget?.gst_behavior === 'exclusive' ? 'GST Additional (+18%)' : 'No GST')"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Sale Rate / Sq Ft</span>
                                <strong class="text-slate-800 font-mono" x-text="(viewTarget?.unit_type?.name?.toLowerCase() === 'parking' || viewTarget?.unit_type?.category?.toLowerCase() === 'parking') ? 'N/A' : (viewTarget?.sale_rate_per_sqft ? '₹' + Number(viewTarget.sale_rate_per_sqft).toLocaleString() : 'N/A')"></strong>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block font-bold">Total Sale Value</span>
                                <strong class="text-emerald-700 font-mono" x-text="viewTarget?.sale_amount ? '₹' + Number(viewTarget.sale_amount).toLocaleString() : 'N/A'"></strong>
                            </div>
                            <div class="col-span-2 pt-1 border-t border-amber-200/50 flex justify-between items-center" x-show="viewTarget?.gst_behavior !== 'none'">
                                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">GST Amount Payable (18%)</span>
                                <strong class="text-indigo-700 font-mono text-xs" x-text="viewTarget?.gst_amount ? '₹' + Number(viewTarget.gst_amount).toLocaleString() : '₹0.00'"></strong>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end bg-slate-50">
                <button type="button" @click="modals.view.open = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         RATE HISTORY MODAL
    ═══════════════════════════════════════════ --}}
    <!-- RATE HISTORY MODAL -->
    <div x-show="modals.rateHistory.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-cloak>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up" @click.away="modals.rateHistory.open = false">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10 flex-shrink-0">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#a38c29]/20 flex items-center justify-center text-[#d9bf3b] shadow-inner shadow-[#a38c29]/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Audit Trail</span>
                            <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-0.5">Rate Change History Register</h2>
                        </div>
                    </div>
                    <button type="button" @click="modals.rateHistory.open = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                </div>
            </div>

            {{-- Modal Body (Scrollable) --}}
            <div class="p-6 overflow-y-auto flex-grow space-y-5">
                {{-- Top Summary Theme Card --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50/70 via-white to-amber-50/40 border border-[#a38c29]/30 p-5 shadow-md text-slate-900">
                    {{-- Decorative Background Glows --}}
                    <div class="absolute -top-14 -right-14 w-48 h-48 bg-[#a38c29]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-14 -left-14 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#a38c29]/15 relative z-10">
                        <div class="flex items-center gap-3.5">
                            <div class="min-w-[3rem] min-h-[3rem] px-3.5 py-2 rounded-xl bg-gradient-to-br from-[#a38c29] to-[#8a7522] flex items-center justify-center text-white font-black text-sm md:text-base tracking-tight shadow-md shadow-[#a38c29]/20 border border-[#a38c29]/20 max-w-[220px] break-words text-center leading-tight flex-shrink-0" x-text="rateHistoryTarget?.door_no || 'U'">
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-[#a38c29] bg-[#a38c29]/10 px-2.5 py-0.5 rounded-full border border-[#a38c29]/30">Unit Profile</span>
                                    <span class="text-xs font-bold text-slate-600" x-show="rateHistoryTarget?.unit_type?.name" x-text="rateHistoryTarget?.unit_type?.name"></span>
                                </div>
                                <h4 class="text-lg font-black tracking-tight text-slate-900 mt-0.5" x-text="'Unit ' + (rateHistoryTarget?.door_no || 'N/A')"></h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <div class="px-3 py-1.5 rounded-xl bg-white border border-[#a38c29]/20 shadow-2xs">
                                <span class="text-slate-500 font-medium">Floor:</span>
                                <strong class="text-slate-900 ml-1 font-bold" x-text="rateHistoryTarget?.floor?.name || 'N/A'"></strong>
                            </div>
                            <div class="px-3 py-1.5 rounded-xl bg-white border border-[#a38c29]/20 shadow-2xs" x-show="!isParking(rateHistoryTarget?.unit_type_id)">
                                <span class="text-slate-500 font-medium">Built-up Area:</span>
                                <strong class="text-slate-900 ml-1 font-mono font-bold" x-text="rateHistoryTarget?.built_up_area != null ? Number(rateHistoryTarget.built_up_area).toLocaleString('en-IN', {minimumFractionDigits: 1, maximumFractionDigits: 2}) + ' Sq.Ft' : 'N/A'"></strong>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Row: Financial / Pricing Metrics --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 pt-4 relative z-10">
                        <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Unit Type</span>
                            <span class="text-xs font-bold text-slate-800 mt-1 block" x-text="rateHistoryTarget?.unit_type?.name || 'Flat'"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-white border border-slate-200/80 shadow-2xs">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Floor Level</span>
                            <span class="text-xs font-bold text-slate-800 mt-1 block" x-text="rateHistoryTarget?.floor?.name || 'N/A'"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-[#a38c29]/15 via-amber-500/10 to-white border border-[#a38c29]/40 shadow-sm">
                            <span class="text-[9px] font-extrabold text-[#a38c29] uppercase tracking-widest block flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29] animate-pulse"></span>
                                Current Rate
                            </span>
                            <span class="text-sm font-black text-slate-950 mt-1 block font-mono" x-text="rateHistoryTarget?.expected_rate_per_sqft != null ? '₹' + Number(rateHistoryTarget.expected_rate_per_sqft).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + (isParking(rateHistoryTarget?.unit_type_id) ? '' : '/Sq.Ft') : (isParking(rateHistoryTarget?.unit_type_id) ? '₹0.00' : '₹0.00/Sq.Ft')"></span>
                        </div>
                        <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500/15 via-emerald-500/10 to-white border border-emerald-500/40 shadow-sm">
                            <span class="text-[9px] font-extrabold text-emerald-700 uppercase tracking-widest block flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Expected Sale Value
                            </span>
                            <span class="text-sm font-black text-slate-950 mt-1 block font-mono tracking-tight" x-text="rateHistoryTarget?.expected_sale_amount != null ? '₹' + Number(rateHistoryTarget.expected_sale_amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₹0.00'"></span>
                        </div>
                    </div>
                </div>

                {{-- Loading State --}}
                <div x-show="loadingRateHistory" class="py-12 text-center space-y-3">
                    <div class="inline-block w-8 h-8 border-3 border-[#a38c29] border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-xs font-bold text-slate-500">Fetching latest rate logs and audit trails...</p>
                </div>

                {{-- Empty State --}}
                <div x-show="!loadingRateHistory && (!rateHistoryLogs || rateHistoryLogs.length === 0)" class="py-12 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-full bg-[rgb(67,56,212)]/10 text-[rgb(67,56,212)] flex items-center justify-center mx-auto mb-3" style="color: rgb(67 56 212);">
                        <svg class="w-6 h-6" style="color: rgb(67 56 212);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-700">No Rate Modifications Logged</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">The rate for this unit has not been modified since its initial setup.</p>
                </div>

                {{-- Table of Rate Changes --}}
                <div x-show="!loadingRateHistory && rateHistoryLogs && rateHistoryLogs.length > 0" class="space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <span>Rate Change History</span>
                            <span class="px-1.5 py-0.5 rounded-full bg-slate-100 text-[10px] text-slate-600" x-text="rateHistoryLogs.length + ' records'"></span>
                        </h4>
                        <div class="flex items-center gap-3 text-[10px] font-medium text-slate-500">
                            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> IST (UTC+5:30)</span>
                            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> UAE (UTC+4:00)</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white shadow-2xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="px-3.5 py-3 w-12 text-center">#</th>
                                    <th class="px-4 py-3">Changed At</th>
                                    <th class="px-4 py-3 text-right">Old Rate <span x-show="!isParking(rateHistoryTarget?.unit_type_id)">(₹/Sq.Ft)</span></th>
                                    <th class="px-4 py-3 text-right">New Rate <span x-show="!isParking(rateHistoryTarget?.unit_type_id)">(₹/Sq.Ft)</span></th>
                                    <th class="px-4 py-3 text-right">Change</th>
                                    <th class="px-4 py-3">Changed By</th>
                                    <th class="px-4 py-3">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                <template x-for="(log, index) in rateHistoryLogs" :key="log.id || index">
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-3.5 py-3 text-center font-bold text-slate-400 font-mono" x-text="index + 1"></td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                                <span x-text="formatDate(log)"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-medium text-slate-500 whitespace-nowrap"
                                            x-text="log.previous_rate !== undefined && log.previous_rate !== null ? '₹' + Number(log.previous_rate).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '₹0.00'">
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-extrabold text-slate-900 whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5">
                                                <span x-text="'₹' + Number(log.rate).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                                <template x-if="index === 0">
                                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase bg-emerald-100 text-emerald-800">Current</span>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap font-mono font-extrabold">
                                            <span class="px-2 py-0.5 rounded-md text-[11px]"
                                                  :class="(Number(log.rate) - Number(log.previous_rate || 0)) >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'"
                                                  x-text="((Number(log.rate) - Number(log.previous_rate || 0)) >= 0 ? '+₹' : '-₹') + Math.abs(Number(log.rate) - Number(log.previous_rate || 0)).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#a38c29]/15 text-[#a38c29] flex items-center justify-center font-bold text-[10px] flex-shrink-0"
                                                     x-text="log.user ? log.user.name.charAt(0).toUpperCase() : 'S'"></div>
                                                <div>
                                                    <div class="font-bold text-slate-800 text-xs" x-text="log.user ? log.user.name : (log.changed_by ? 'User #' + log.changed_by : 'User')"></div>
                                                    <div class="text-[10px] text-slate-400" x-text="log.user ? (log.user.email || 'Authorized Role') : 'System / Admin'"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700">
                                            <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100/80 border border-slate-200/60 text-xs font-medium whitespace-nowrap" x-text="log.reason || 'Initial rate set'"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end bg-slate-50 flex-shrink-0">
                <button type="button" @click="modals.rateHistory.open = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">Close</button>
            </div>
        </div>
    </div>

    </div>
    
        {{-- ═══════════════════════════════════════════
         VIEW SALE MODAL (read-only)
    ═══════════════════════════════════════════ --}}
    <div x-show="modals.viewSale.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="closeViewSaleModal()">
            {{-- Header --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-6 border-b border-primary-500/10">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Agreement Details</span>
                            <span class="badge-pill text-[9px] whitespace-nowrap" :class="getStatusBadgeClass(activeSale.status)" x-text="activeSale.status"></span>
                        </div>
                        <h2 class="text-xl font-extrabold text-white tracking-tight truncate break-all" x-text="activeSale.sale_number"></h2>
                    </div>
                    <button @click="closeViewSaleModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0">✕</button>
                </div>
            </div>
            {{-- Scrollable Container --}}
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                {{-- Row 1: Sale Profile --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Project Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Project Name</span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.project ? activeSale.project.name : '—'"></strong>
                        </div>
                    </div>
                    {{-- Unit Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Unit & Floor</span>
                            <strong class="text-slate-800 text-xs block mt-1" x-text="activeSale.sale_units && activeSale.sale_units.length ? activeSale.sale_units.map(su => su.unit ? su.unit.door_no : '').join(', ') : (activeSale.unit ? activeSale.unit.door_no + ' — ' + (activeSale.unit.floor ? activeSale.unit.floor.name : '') : '—')"></strong>
                        </div>
                    </div>
                    {{-- Customer Card --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="overflow-hidden">
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Customer Details</span>
                            <strong class="text-slate-800 text-xs block mt-1 truncate" x-text="activeSale.customer ? activeSale.customer.name : '—'"></strong>
                            <span class="text-slate-450 text-[10px] block mt-0.5 truncate" x-text="activeSale.customer ? activeSale.customer.phone : ''"></span>
                        </div>
                    </div>
                </div>
                {{-- Multi Unit Details Table --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3" x-show="activeSale.sale_units && activeSale.sale_units.length > 0">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">🏢 Booked Inventory / Units</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[11px] border-collapse min-w-[500px]">
                            <thead>
                                <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider text-[9px]">
                                    <th class="py-2 px-2">Unit</th>
                                    <th class="py-2 px-2">Floor</th>
                                    <th class="py-2 px-2">Area (Sq.Ft)</th>
                                    <th class="py-2 px-2">Rate/Sq.Ft</th>
                                    <th class="py-2 px-2">GST</th>
                                    <th class="py-2 px-2 text-right">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                <template x-for="su in activeSale.sale_units" :key="su.id">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-2 px-2 font-bold text-indigo-700" x-text="su.unit ? su.unit.door_no : '—'"></td>
                                        <td class="py-2 px-2" x-text="su.unit && su.unit.floor ? su.unit.floor.name : '—'"></td>
                                        <td class="py-2 px-2 font-mono" x-text="Number(su.area_sqft).toLocaleString()"></td>
                                        <td class="py-2 px-2 font-mono" x-text="'₹' + Number(su.rate_per_sqft).toLocaleString()"></td>
                                        <td class="py-2 px-2 whitespace-nowrap" x-text="su.gst_type !== 'none' ? '₹' + Number(su.gst_amount).toLocaleString() + ' (' + su.gst_type + ')' : 'None'"></td>
                                        <td class="py-2 px-2 text-right font-mono text-slate-900" x-text="'₹' + Number(su.line_total).toLocaleString()"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Row 1.5: Extra Works Details --}}
                <template x-if="activeSale.extra_works && activeSale.extra_works.length > 0">
                    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
                        <div class="p-4 border-b border-slate-100 bg-slate-55/30">
                            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">🛠️ Custom Alterations / Extra Work Details</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-slate-50/50 text-[9px] font-bold text-slate-455 uppercase tracking-wider border-b border-slate-100">
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3 text-right">Amount</th>
                                        <th class="px-4 py-3">GST Type</th>
                                        <th class="px-4 py-3">GST (%)</th>
                                        <th class="px-4 py-3 text-right">GST Amount</th>
                                        <th class="px-4 py-3 text-right font-bold text-emerald-800">Total Payable</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                                    <template x-for="ew in activeSale.extra_works" :key="ew.id">
                                        <tr class="hover:bg-slate-55/40 transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-900" x-text="ew.description"></td>
                                            <td class="px-4 py-3 text-right font-mono" x-text="'₹' + Number(ew.amount).toLocaleString()"></td>
                                            <td class="px-4 py-3 uppercase" x-text="ew.gst_type"></td>
                                            <td class="px-4 py-3" x-text="ew.gst_percentage + '%'"></td>
                                            <td class="px-4 py-3 text-right font-mono" x-text="'₹' + Number(ew.gst_amount).toLocaleString()"></td>
                                            <td class="px-4 py-3 text-right font-mono text-emerald-800 font-bold" x-text="'₹' + Number(ew.line_total).toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
                {{-- Row 2: Financial Summary Card --}}
                <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">💰 Pricing & GST Breakdown</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Agreed Rate / Sqft</span>
                            <span class="font-extrabold text-slate-850 text-sm mt-1 block font-mono" x-text="activeSale.rate_per_sqft > 0 ? '₹' + Number(activeSale.rate_per_sqft).toLocaleString() : '₹0 (Flat Price)'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total Base Amount</span>
                            <span class="font-bold text-slate-800 text-sm mt-1 block font-mono" x-text="activeSale.base_amount ? '₹' + Number(activeSale.base_amount).toLocaleString() : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total GST Amount</span>
                            <span class="font-bold text-slate-800 text-sm mt-1 block" x-text="activeSale.gst_amount > 0 ? '₹' + Number(activeSale.gst_amount || 0).toLocaleString() : 'None / Excluded'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Total Contract Value</span>
                            <span class="font-extrabold text-[#a38c29] text-base mt-1 block font-mono" x-text="'₹' + Number(activeSale.total_amount || 0).toLocaleString()"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100 bg-slate-50/50 -mx-5 -mb-5 p-5 rounded-b-xl">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Agreement Date</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="formatSaleDate(activeSale.sale_date)"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Registration Date</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="formatSaleDate(activeSale.registration_date)"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Selected Plan</span>
                            <span class="font-extrabold text-indigo-600 mt-1 block uppercase" x-text="activeSale.payment_plan === 'emi' ? 'EMI (' + (activeSale.emi_installment_count || 12) + '-Mo ' + (activeSale.emi_frequency || 'Monthly') + ')' : 'Lump Sum'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-455 font-bold uppercase block tracking-wider">Remaining Balance</span>
                            <span class="font-extrabold text-sm mt-1 block font-mono" :class="activeSale.remaining_balance > 0 ? 'text-rose-600' : 'text-emerald-700'" x-text="'₹' + Number(activeSale.remaining_balance || 0).toLocaleString()"></span>
                        </div>
                    </div>
                </div>
                {{-- Row 3: Receipts Ledger --}}
                <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-55/30">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest">💳 Collection Receipts History</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="bg-slate-50/50 text-[9px] font-bold text-slate-450 uppercase tracking-wider border-b border-slate-100">
                                    <th class="px-4 py-3">Receipt No</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Payment Mode</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="r in activeSale.receipts" :key="r.id">
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-4 py-3 font-bold text-slate-900" x-text="'REC-' + String(r.id).padStart(5, '0') + (r.reference_no ? ' (' + r.reference_no + ')' : '')"></td>
                                        <td class="px-4 py-3 text-slate-500" x-text="formatSaleDate(r.receipt_date)"></td>
                                        <td class="px-4 py-3 text-slate-500 uppercase" x-text="r.payment_mode"></td>
                                        <td class="px-4 py-3 text-right font-extrabold text-emerald-700 font-mono" x-text="'₹' + Number(r.amount).toLocaleString()"></td>
                                    </tr>
                                </template>
                                <template x-if="!activeSale.receipts || activeSale.receipts.length === 0">
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-400 italic bg-white">No receipts recorded for this sale.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Row 4: Broker details --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 mb-3">💼 Broker & Commission Details</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-show="activeSale.brokerage">
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Broker Name</span>
                            <span class="font-bold text-slate-800 mt-1 block" x-text="activeSale.broker ? activeSale.broker.name : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider">Commission Structure</span>
                            <span class="font-bold text-slate-855 mt-1 block" x-text="activeSale.brokerage ? (activeSale.brokerage.commission_type === 'percentage' ? activeSale.brokerage.commission_percent + '% of Sale Price' : 'Fixed Commission') : '—'"></span>
                        </div>
                        <div>
                            <span class="text-[9px] text-slate-455 font-bold uppercase block tracking-wider">Payout Amount / Status</span>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="font-extrabold text-slate-900 font-mono" x-text="activeSale.brokerage ? '₹' + Number(activeSale.brokerage.commission_amount).toLocaleString() : '—'"></span>
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-wider inline-block"
                                      :class="activeSale.brokerage && activeSale.brokerage.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                      x-text="activeSale.brokerage ? activeSale.brokerage.status : ''"></span>
                            </div>
                        </div>
                    </div>
                    <div x-show="!activeSale.brokerage" class="text-slate-400 italic text-[11px] py-1">
                        No broker was associated with this transaction (Direct Sale).
                    </div>
                </div>
                {{-- Row 5: Logs & Remarks --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Transition logs --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm space-y-2.5">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2 flex items-center justify-between">
                            <span>📜 Transition History Logs</span>
                            <span class="text-[9px] font-bold text-slate-400 font-mono" x-text="(activeSale.status_logs ? activeSale.status_logs.length : 0) + ' Logs'"></span>
                        </p>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            <template x-for="log in activeSale.status_logs" :key="log.id">
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 text-[10px] space-y-1.5" x-data="{ openSnap: false }">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-slate-800 uppercase tracking-wide" x-text="(log.from_status || 'created') + ' → ' + log.to_status"></span>
                                            <template x-if="log.snapshot_data">
                                                <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 text-[8px] font-extrabold uppercase">Archived Log</span>
                                            </template>
                                        </div>
                                        <span class="text-slate-400 font-mono text-[9px]" x-text="formatSaleDate(log.created_at)"></span>
                                    </div>
                                    <p class="text-slate-600 italic font-sans" x-text="log.reason || 'No narrative provided'"></p>

                                    {{-- Archived Unit Snapshot Viewer --}}
                                    <template x-if="log.snapshot_data">
                                        <div class="mt-2 pt-2 border-t border-slate-200/80">
                                            <button type="button" @click="openSnap = !openSnap"
                                                    class="text-[9px] font-extrabold text-blue-600 hover:text-blue-800 uppercase tracking-wider flex items-center gap-1 cursor-pointer">
                                                <span x-text="openSnap ? 'Hide Archived Unit Log' : '📦 View Archived Unit Log Snapshot'"></span>
                                                <svg class="w-3 h-3 transition-transform" :class="openSnap ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </button>

                                            <div x-show="openSnap" class="mt-2 p-2.5 bg-white rounded-lg border border-blue-200 space-y-2 text-[10px] font-sans">
                                                <div class="grid grid-cols-2 gap-2 border-b border-slate-100 pb-1.5">
                                                    <div>
                                                        <span class="text-slate-400 font-bold block text-[8px] uppercase">Old Unit</span>
                                                        <span class="font-extrabold text-slate-800" x-text="log.snapshot_data.old_unit ? log.snapshot_data.old_unit.door_no : 'N/A'"></span>
                                                        <span class="text-slate-500 text-[9px]" x-text="log.snapshot_data.old_unit && log.snapshot_data.old_unit.floor_name ? ' (' + log.snapshot_data.old_unit.floor_name + ')' : ''"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 font-bold block text-[8px] uppercase">Old Sale No</span>
                                                        <span class="font-extrabold text-slate-800 font-mono" x-text="log.snapshot_data.old_sale ? log.snapshot_data.old_sale.sale_number : 'N/A'"></span>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-1 bg-slate-50 p-1.5 rounded-md text-center font-mono">
                                                    <div>
                                                        <span class="text-[7px] text-slate-400 font-bold uppercase block">Contract Value</span>
                                                        <span class="font-bold text-slate-800" x-text="log.snapshot_data.old_sale ? fmtSale(log.snapshot_data.old_sale.total_amount) : '₹0'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[7px] text-slate-400 font-bold uppercase block">Total Paid</span>
                                                        <span class="font-bold text-emerald-700" x-text="log.snapshot_data.old_sale ? fmtSale(log.snapshot_data.old_sale.total_paid) : '₹0'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[7px] text-slate-400 font-bold uppercase block">Balance</span>
                                                        <span class="font-bold text-slate-700" x-text="log.snapshot_data.old_sale ? fmtSale(log.snapshot_data.old_sale.remaining_balance) : '₹0'"></span>
                                                    </div>
                                                </div>

                                                <div class="flex justify-between items-center text-[9px] text-slate-500 pt-1 font-semibold">
                                                    <span>Receipts Recorded: <strong class="text-slate-800 font-mono" x-text="log.snapshot_data.receipts ? log.snapshot_data.receipts.length : 0"></strong></span>
                                                    <span>EMI Schedule Count: <strong class="text-slate-800 font-mono" x-text="log.snapshot_data.installments ? log.snapshot_data.installments.length : 0"></strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!activeSale.status_logs || activeSale.status_logs.length === 0">
                                <p class="text-xs text-slate-400 italic py-2">No transition history logged for this agreement.</p>
                            </template>
                        </div>
                    </div>
                    {{-- Remarks --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col">
                        <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-2">💬 Agreement Notes & Remarks</p>
                        <div class="flex-1 mt-3">
                            <p class="text-slate-650 font-sans text-xs bg-slate-50 p-3 rounded-lg border border-slate-200/80 h-full min-h-[80px]" x-text="activeSale.notes || 'No remarks recorded for this agreement.'"></p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
                <a :href="'{{ url('/emi-collections/ledger') }}/' + activeSale.id" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>EMI & Collection Ledger</span>
                </a>
                <button @click="closeViewSaleModal()" class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md">Close</button>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ALPINE.JS LOGIC CODE
═══════════════════════════════════════════ --}}
<script>
function unitsApp() {
    return {
        // App states
        projectId: {{ $project->id }},
        unitTypeMap: {!! json_encode($unitTypes->keyBy('id')->map(fn($t) => ['name' => $t->name, 'category' => $t->category])) !!},
        editProjectModal: false,
        imagePreview: null,
        units: [],
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 50
        },
        customerList: {!! json_encode($customers->map(function($c) { return ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone, 'email' => $c->email]; })) !!},
        localSelectedIds: @js(is_array(request('customer_id')) ? request('customer_id') : (request('customer_id') ? [request('customer_id')] : [])),
        filters: {
            search: '',
            floor_id: '',
            unit_type_id: '',
            status: ''
        },
        permissions: {
            manage: {{ auth()->user()->hasPermissionTo('units.manage') ? 'true' : 'false' }},
            rateManage: {{ auth()->user()->hasPermissionTo('units.rate.manage') ? 'true' : 'false' }}
        },
        modals: {
            add: { open: false },
            edit: { open: false },
            bulk: { open: false },
            delete: { open: false },
            view: { open: false },
            rateHistory: { open: false },
            viewSale: { open: false }
        },
        viewTarget: null,
        activeSale: {},
        loadingSaleDetails: false,
        rateHistoryTarget: null,
        rateHistoryLogs: [],
        loadingRateHistory: false,
        forms: {
            add: {
                floor_id: '',
                unit_type_id: '',
                door_no: '',
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: '',
                expected_sale_amount: ''
            },
            edit: {
                floor_id: '',
                unit_type_id: '',
                door_no: '',
                built_up_area: '',
                carpet_area: '',
                expected_sale_amount: ''
            },
            bulk: {
                floor_id: '',
                unit_type_id: '',
                unit_prefix: '',
                start_number: 1,
                count: 10,
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: '',
                expected_sale_amount: ''
            },
            status: {
                status: '',
                reason: '',
                is_resale: false
            },
            rate: {
                rate: '',
                effective_from: new Date().toISOString().split('T')[0],
                reason: ''
            }
        },
        activeUnit: {},
        allowedTransitions: [],
        errors: {},
        toast: {
            open: false,
            message: '',
            type: 'success'
        },
        statusError: '',

        getFilteredCustomersList(search = '') {
            const q = (search || '').toLowerCase().trim();
            if (!q) return this.customerList;
            return this.customerList.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.phone && c.phone.toLowerCase().includes(q))
            );
        },
        get selectedCustomers() {
            return this.customerList.filter(c => this.localSelectedIds.includes(c.id.toString()));
        },
        toggleCustomer(id) {
            const strId = id.toString();
            const idx = this.localSelectedIds.indexOf(strId);
            if (idx > -1) {
                this.localSelectedIds.splice(idx, 1);
            } else {
                this.localSelectedIds.push(strId);
            }
            this.$nextTick(() => {
                const form = document.getElementById('unitsCustomerFilterForm');
                if (form) form.submit();
            });
        },
        getUnitActiveSale(unit) {
            if (!unit) return null;
            if (unit.sale) return unit.sale;
            if (unit.sale_units && unit.sale_units.length > 0) {
                const activeSaleUnit = unit.sale_units.find(su => su.sale && su.sale.status === 'active');
                if (activeSaleUnit && activeSaleUnit.sale) {
                    return activeSaleUnit.sale;
                }
            }
            return null;
        },

        init() {
            // Pre-populate filters from URL query parameters (e.g. ?status=sold from dashboard)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('status'))       this.filters.status       = urlParams.get('status');
            if (urlParams.get('floor_id'))     this.filters.floor_id     = urlParams.get('floor_id');
            if (urlParams.get('search'))       this.filters.search       = urlParams.get('search');
            if (urlParams.get('unit_type_id')) this.filters.unit_type_id = urlParams.get('unit_type_id');
            // handled via localSelectedIds array from server
            this.fetchUnits();
        },

        // Fetch Listings
        fetchUnits(page = 1) {
            let params = new URLSearchParams();
            params.append('page', page);
            if (this.projectId) params.append('project_id', this.projectId);
            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.floor_id) params.append('floor_id', this.filters.floor_id);
            if (this.filters.unit_type_id) params.append('unit_type_id', this.filters.unit_type_id);
            if (this.filters.status) params.append('status', this.filters.status);
            if (this.localSelectedIds && this.localSelectedIds.length > 0) {
                this.localSelectedIds.forEach(id => {
                    params.append('customer_id[]', id);
                });
            }

            fetch('{{ route('units.index') }}?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.units = data.units;
                if(data.pagination) {
                    this.pagination = data.pagination;
                }
            })
            .catch(err => {
                console.error('Error fetching units:', err);
                this.showToast('Failed to fetch units list.', 'error');
            });
        },

        getPageNumbers() {
            let current = this.pagination.current_page;
            let last = this.pagination.last_page;
            let delta = 2;
            let left = current - delta;
            let right = current + delta + 1;
            let range = [];
            let rangeWithDots = [];
            let l;

            for (let i = 1; i <= last; i++) {
                if (i === 1 || i === last || (i >= left && i < right)) {
                    range.push(i);
                }
            }

            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l > 2) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            return rangeWithDots;
        },

        isParking(typeId) {
            if (!typeId || !this.unitTypeMap[typeId]) return false;
            const info = this.unitTypeMap[typeId];
            return (info.name || '').toLowerCase() === 'parking' || (info.category || '').toLowerCase() === 'parking';
        },

        resetFilters() {
            this.filters.search = '';
            this.filters.floor_id = '';
            this.filters.unit_type_id = '';
            this.filters.status = '';
            this.fetchUnits();
        },

        // Group units by floor for rowspan rendering
        groupedUnits() {
            let groups = [];
            let currentFloorId = null;
            let currentGroup = null;
            for (let unit of this.units) {
                let floorId = unit.floor ? unit.floor.id : null;
                if (floorId !== currentFloorId) {
                    currentFloorId = floorId;
                    currentGroup = {
                        floor_id: floorId,
                        floor_name: unit.floor ? unit.floor.name : '-',
                        units: []
                    };
                    groups.push(currentGroup);
                }
                currentGroup.units.push(unit);
            }
            return groups;
        },

        // Render units table with rowspan floor grouping via direct DOM injection
        renderUnitsTable() {
            const tbody = document.getElementById('units-tbody');
            if (!tbody) return;

            if (this.units.length === 0) {
                tbody.innerHTML = `<tr><td colspan="13" class="px-6 py-10 text-center text-slate-400 italic">No units match the query filters.</td></tr>`;
                return;
            }

            const fmtNum = (v) => v != null && v !== '' ? Number(v).toLocaleString() : 'N/A';
            const fmtMoney = (v) => v != null && v !== '' ? '₹' + Number(v).toLocaleString() : 'N/A';
            const fmtArea = (v) => v != null && v !== '' ? Number(v).toLocaleString() + ' Sq Ft' : 'N/A';
            const statusBadge = (s) => {
                const cls = {
                    'available': 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                    'blocked': 'bg-amber-50 text-amber-700 border border-amber-100',
                    'booked': 'bg-indigo-50 text-indigo-700 border border-indigo-100',
                    'sold': 'bg-rose-50 text-rose-700 border border-rose-100',
                }[s] || 'bg-slate-50 text-slate-700 border border-slate-200';
                return `<span class="badge-pill ${cls}">${s}</span>`;
            };
            const canManage = this.permissions.manage;
            const actionsBtns = (unit) => {
                if (!canManage) return '';
                const disabledDel = unit.status !== 'available' ? 'opacity-30 cursor-not-allowed' : '';
                return `<div class="inline-flex items-center justify-end gap-1.5">
                    <button data-action="view" data-id="${unit.id}" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] transition inline-flex items-center justify-center shadow-sm" title="View Unit Details">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <button data-action="rate-history" data-id="${unit.id}" class="p-2 rounded-lg bg-[rgb(67,56,212)]/10 hover:bg-[rgb(67,56,212)]/20 text-[rgb(67,56,212)] transition inline-flex items-center justify-center shadow-sm" title="View Rate History">
                        <svg class="w-4 h-4" style="color:rgb(67 56 212)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>
                    <button data-action="edit" data-id="${unit.id}" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] transition inline-flex items-center justify-center shadow-sm" title="Edit Unit">
                        <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button data-action="delete" data-id="${unit.id}" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 transition inline-flex items-center justify-center shadow-sm ${disabledDel}" title="Delete Unit" ${unit.status !== 'available' ? 'disabled' : ''}>
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>`;
            };

            let html = '';
            const groups = this.groupedUnits();
            for (const group of groups) {
                group.units.forEach((unit, ui) => {
                    html += `<tr class="unit-table-row transition-colors cursor-pointer text-center text-xs font-semibold text-slate-700" data-unit-id="${unit.id}">`;
                    if (ui === 0) {
                        html += `<td rowspan="${group.units.length}" class="border text-slate-900 font-extrabold text-[11px] uppercase bg-[#a38c29]/10 select-none" style="writing-mode:vertical-rl;text-orientation:mixed;transform:rotate(180deg);min-width:38px;padding:14px 8px;text-align:center;vertical-align:middle;letter-spacing:0.13em;">${group.floor_name}</td>`;
                    }
                    const isParkingUnit = unit.unit_type && (unit.unit_type.name.toLowerCase() === 'parking' || (unit.unit_type.category || '').toLowerCase() === 'parking');
                    const expRateDisp = isParkingUnit ? 'N/A' : fmtMoney(unit.expected_rate_per_sqft);
                    const saleRateDisp = isParkingUnit ? 'N/A' : fmtMoney(unit.sale_rate_per_sqft);

                    let saleAmountDisp = fmtMoney(unit.sale_amount);
                    let saleCellAttrs = 'class="px-3 py-3 border font-bold"';
                    if (unit.status === 'sold') {
                        const activeSale = this.getUnitActiveSale(unit);
                        if (activeSale) {
                            saleCellAttrs = `class="px-3 py-3 border font-bold cursor-pointer hover:bg-emerald-50 text-emerald-700 transition-colors" data-sale-id="${activeSale.id}" title="Click to view sale details"`;
                            if (activeSale.customer) {
                                saleAmountDisp += `<br><div class="mt-1.5 inline-flex items-center gap-1.5 bg-emerald-50/50 text-emerald-700 px-2.5 py-1 rounded-md shadow-sm border border-emerald-500"><span class="text-[9px] font-extrabold uppercase tracking-widest whitespace-nowrap text-emerald-700">Sold To: ${activeSale.customer.name}</span></div>`;
                            }
                        }
                    }

                    html += `
                        <td class="px-3 py-3 border font-extrabold text-slate-800 whitespace-nowrap">${unit.floor ? unit.floor.name : ''}</td>
                        <td class="px-3 py-3 border text-slate-600">${unit.unit_type ? unit.unit_type.name : ''}</td>
                        <td class="px-3 py-3 border font-bold text-slate-900">${unit.door_no}</td>
                        <td class="px-3 py-3 border">${fmtArea(unit.built_up_area)}</td>
                        <td class="px-3 py-3 border">${fmtArea(unit.carpet_area)}</td>
                        <td class="px-3 py-3 border font-bold text-slate-900">${expRateDisp}</td>
                        <td class="px-3 py-3 border font-bold text-emerald-700">${fmtMoney(unit.expected_sale_amount)}</td>
                        <td class="px-3 py-3 border font-bold text-slate-900">${saleRateDisp}</td>
                        <td ${saleCellAttrs}>${saleAmountDisp}</td>
                        <td class="px-3 py-3 border font-bold">${fmtMoney(unit.difference)}</td>
                        <td class="px-3 py-3 border">${statusBadge(unit.status)}</td>
                        <td class="px-3 py-3 border text-right">${actionsBtns(unit)}</td>
                    </tr>`;
                });
            }
            tbody.innerHTML = html;

            // Attach click handlers via delegation
            const self = this;
            tbody.querySelectorAll('[data-action]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = parseInt(this.dataset.id);
                    const unit = self.units.find(u => u.id === id);
                    if (this.dataset.action === 'view') self.openViewModal(unit);
                    else if (this.dataset.action === 'rate-history') self.openRateHistoryModal(unit);
                    else if (this.dataset.action === 'edit') self.openEditModal(id);
                    else if (this.dataset.action === 'delete') self.confirmDelete(unit);
                });
            });
            tbody.querySelectorAll('.unit-table-row').forEach(row => {
                const id = parseInt(row.dataset.unitId);
                row.addEventListener('click', function() {
                    const unit = self.units.find(u => u.id === id);
                    if (unit) {
                        self.openViewModal(unit);
                    }
                });
            });
            tbody.querySelectorAll('[data-sale-id]').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.stopPropagation();
                    self.openViewSaleModal(this.dataset.saleId);
                });
            });
        },

        // Helper Status Colors
        getStatusBadgeClass(status) {
            switch(status) {
                case 'available': return 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                case 'blocked': return 'bg-amber-50 text-amber-700 border border-amber-100';
                case 'booked': return 'bg-indigo-50 text-indigo-700 border border-indigo-100';
                case 'sold': return 'bg-rose-50 text-rose-700 border border-rose-100';
                default: return 'bg-slate-50 text-slate-700 border border-slate-200';
            }
        },

        // Toast Messages
        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.open = true;
            setTimeout(() => {
                this.toast.open = false;
            }, 3000);
        },

        // Modal triggers
        openAddModal() {
            this.errors = {};
            this.forms.add = {
                floor_id: '',
                unit_type_id: '',
                door_no: '',
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: ''
            };
            this.modals.add.open = true;
        },
        closeAddModal() {
            this.modals.add.open = false;
        },

        openViewModal(unit) {
            this.viewTarget = unit;
            this.modals.view.open = true;
        },

        openViewSaleModal(saleId) {
            if (!saleId) return;
            this.loadingSaleDetails = true;
            fetch(`{{ url('sales') }}/${saleId}/json`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                this.activeSale = data.sale || data;
                this.modals.viewSale.open = true;
            })
            .catch(err => {
                console.error(err);
                this.showToast('Failed to load sale details.', 'error');
            })
            .finally(() => {
                this.loadingSaleDetails = false;
            });
        },
        closeViewSaleModal() {
            this.modals.viewSale.open = false;
        },
        formatSaleDate(val) {
            if (!val) return '—';
            try {
                const clean = val.replace('Z', '').split('T')[0];
                const parts = clean.split('-');
                if (parts.length === 3) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const yr = parts[0];
                    const mo = months[parseInt(parts[1], 10) - 1];
                    const dy = parts[2];
                    return `${dy} ${mo} ${yr}`;
                }
                return clean;
            } catch(e) {
                return val.split('T')[0];
            }
        },
        fmtSale(value) {
            return '₹' + Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        openRateHistoryModal(unit) {
            this.rateHistoryTarget = unit;
            this.rateHistoryLogs = unit.rate_logs || [];
            this.modals.rateHistory.open = true;
            this.loadingRateHistory = true;

            fetch(`{{ url('units') }}/${unit.id}/json`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.unit && data.unit.rate_logs) {
                    this.rateHistoryLogs = data.unit.rate_logs;
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                this.loadingRateHistory = false;
            });
        },

        parseLogDate(log) {
            let raw = log.created_at || log.effective_from;
            if (!raw) return new Date();
            
            if (typeof raw === 'string' && !raw.includes('Z') && !raw.match(/[+-]\d{2}:?\d{2}$/)) {
                // Replace space with T and append Z to force UTC parsing for Laravel timestamps
                raw = raw.replace(' ', 'T') + 'Z';
            }
            
            let dt = new Date(raw);
            return isNaN(dt.getTime()) ? new Date() : dt;
        },

        formatDate(log) {
            try {
                const dt = this.parseLogDate(log);
                return new Intl.DateTimeFormat('en-IN', {
                    timeZone: 'Asia/Kolkata',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }).format(dt);
            } catch (e) {
                return (log.created_at || log.effective_from || '');
            }
        },

        openEditModal(unitId) {
            this.errors = {};
            this.forms.status = { status: '', reason: '', is_resale: false };
            this.forms.rate = { rate: '', effective_from: new Date().toISOString().split('T')[0], reason: '' };

            fetch(`{{ url('units') }}/${unitId}/json`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    this.showToast(data.error, 'error');
                    return;
                }
                this.activeUnit = data.unit;
                this.allowedTransitions = data.allowed_transitions;

                // Prefill Form
                this.forms.edit = {
                    floor_id: this.activeUnit.floor_id,
                    unit_type_id: this.activeUnit.unit_type_id,
                    door_no: this.activeUnit.door_no,
                    built_up_area: this.activeUnit.built_up_area,
                    carpet_area: this.activeUnit.carpet_area,
                    expected_sale_amount: this.activeUnit.expected_sale_amount
                };

                this.modals.edit.open = true;
            })
            .catch(err => {
                console.error(err);
                this.showToast('Failed to load unit details.', 'error');
            });
        },
        closeEditModal() {
            this.modals.edit.open = false;
        },

        openBulkModal() {
            this.errors = {};
            this.forms.bulk = {
                floor_id: '',
                unit_type_id: '',
                unit_prefix: '',
                start_number: 1,
                count: 10,
                built_up_area: '',
                carpet_area: '',
                expected_rate_per_sqft: '',
                expected_sale_amount: ''
            };
            this.modals.bulk.open = true;
        },
        closeBulkModal() {
            this.modals.bulk.open = false;
        },

        // Deletions
        confirmDelete(unit) {
            if (unit.status !== 'available') {
                this.showToast('Only available units can be deleted.', 'error');
                return;
            }
            this.activeUnit = unit;
            this.modals.delete.open = true;
        },
        closeDeleteModal() {
            this.modals.delete.open = false;
        },

        // POST/AJAX Actions
        submitAddUnit() {
            // Client-side required field validation
            let clientErrors = {};
            if (!this.forms.add.floor_id) {
                clientErrors.floor_id = ['The floor field is required.'];
            }
            if (!this.forms.add.unit_type_id) {
                clientErrors.unit_type_id = ['The unit type field is required.'];
            }
            if (!this.forms.add.door_no || !this.forms.add.door_no.trim()) {
                clientErrors.door_no = ['The door number field is required.'];
            }

            const isParking = this.isParking(this.forms.add.unit_type_id);
            if (isParking) {
                if (!this.forms.add.expected_sale_amount && this.forms.add.expected_sale_amount !== 0) {
                    clientErrors.expected_sale_amount = ['The expected sale amount field is required.'];
                }
            } else {
                if (!this.forms.add.expected_rate_per_sqft && this.forms.add.expected_rate_per_sqft !== 0) {
                    clientErrors.expected_rate_per_sqft = ['The expected rate per sq ft field is required.'];
                }
            }

            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }

            let payload = {
                project_id: this.projectId,
                ...this.forms.add
            };

            fetch('{{ route('units.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast('Unit added successfully.');
                    this.closeAddModal();
                    this.resetFilters();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitEditUnit() {
            // Client-side required field validation
            let clientErrors = {};
            if (!this.forms.edit.floor_id) {
                clientErrors.floor_id = ['The floor field is required.'];
            }
            if (!this.forms.edit.unit_type_id) {
                clientErrors.unit_type_id = ['The unit type field is required.'];
            }
            if (!this.forms.edit.door_no || !this.forms.edit.door_no.trim()) {
                clientErrors.door_no = ['The door number field is required.'];
            }

            const isParking = this.isParking(this.forms.edit.unit_type_id);
            if (isParking) {
                if (!this.forms.edit.expected_sale_amount && this.forms.edit.expected_sale_amount !== 0) {
                    clientErrors.expected_sale_amount = ['The expected sale amount field is required.'];
                }
            }

            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }

            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            let payload = {
                ...this.forms.edit,
                _token: csrfToken,
                _method: 'PUT'
            };

            fetch(`{{ url('units') }}/${this.activeUnit.id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let text = await res.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error('Non-JSON response:', text);
                }

                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || data.message || ('Server error when updating unit (HTTP ' + res.status + ').'), 'error');
                } else {
                    this.showToast('Unit details updated successfully.');
                    this.fetchUnits();
                    this.closeEditModal();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitBulkAdd() {
            // Client-side required field validation
            let clientErrors = {};
            if (!this.forms.bulk.floor_id) {
                clientErrors.floor_id = ['The floor field is required.'];
            }
            if (!this.forms.bulk.unit_type_id) {
                clientErrors.unit_type_id = ['The unit type field is required.'];
            }
            if (!this.forms.bulk.start_number && this.forms.bulk.start_number !== 0) {
                clientErrors.start_number = ['The starting number is required.'];
            }
            if (!this.forms.bulk.count) {
                clientErrors.count = ['The count is required.'];
            }

            const isParking = this.isParking(this.forms.bulk.unit_type_id);
            if (isParking) {
                if (!this.forms.bulk.expected_sale_amount && this.forms.bulk.expected_sale_amount !== 0) {
                    clientErrors.expected_sale_amount = ['The expected sale amount field is required.'];
                }
            } else {
                if (!this.forms.bulk.expected_rate_per_sqft && this.forms.bulk.expected_rate_per_sqft !== 0) {
                    clientErrors.expected_rate_per_sqft = ['The expected rate per sq ft field is required.'];
                }
            }

            if (Object.keys(clientErrors).length > 0) {
                this.errors = clientErrors;
                return;
            }

            let payload = {
                project_id: this.projectId,
                ...this.forms.bulk
            };

            fetch('{{ route('units.bulk-store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (res.status === 422) {
                    this.errors = data.errors || {};
                } else if (!res.ok) {
                    this.showToast(data.error || 'Server error occurred.', 'error');
                } else {
                    this.showToast(`Bulk created ${data.count} units successfully.`);
                    this.closeBulkModal();
                    this.resetFilters();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        },

        submitDelete() {
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            fetch(`{{ url('units') }}/${this.activeUnit.id}/remove`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _token: csrfToken
                })
            })
            .then(async res => {
                let text = await res.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    console.error('Non-JSON response from server:', text);
                }

                if (!res.ok) {
                    this.showToast(data.error || data.message || ('Server error (HTTP ' + res.status + ').'), 'error');
                } else {
                    this.showToast('Unit deleted successfully.');
                    this.closeDeleteModal();
                    this.fetchUnits();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred while connecting to server.', 'error');
            });
        },

        // Status Transition Handler
        transitionStatus(targetState) {
            this.statusError = ''; // clear previous inline error
            let payload = {
                status: targetState,
                reason: this.forms.status.reason,
                is_resale: this.forms.status.is_resale ? 1 : 0
            };

            fetch(`{{ url('units') }}/${this.activeUnit.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    // Show error inline inside the modal, not behind it
                    this.statusError = data.error || 'Failed to update status.';
                } else {
                    this.statusError = '';
                    this.showToast(`Transitioned status to ${targetState} successfully.`);
                    this.fetchUnits();
                    this.openEditModal(this.activeUnit.id);
                }
            })
            .catch(err => {
                console.error(err);
                this.statusError = 'Network error occurred.';
            });
        },

        // Rate History Handler
        submitUpdateRate() {
            this.errors = {};
            if (this.forms.rate.rate === '' || this.forms.rate.rate === null || this.forms.rate.rate === undefined) {
                this.errors.rate = ['The rate field is required.'];
                return;
            }
            fetch(`{{ url('units') }}/${this.activeUnit.id}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.forms.rate)
            })
            .then(async res => {
                let data = await res.json();
                if (!res.ok) {
                    if (data.errors) {
                        this.errors = data.errors;
                    }
                    this.showToast(data.error || data.message || 'Failed to update base rate.', 'error');
                } else {
                    this.showToast('Base rate updated successfully.');
                    this.fetchUnits();
                    this.closeEditModal();
                }
            })
            .catch(err => {
                console.error(err);
                this.showToast('Network error occurred.', 'error');
            });
        }
    };
}
</script>

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
            <col width="160" style="width: 120pt;" />  {{-- TOTAL RECEIVED CHEQUE --}}
            <col width="150" style="width: 113pt;" />  {{-- CHEQUE RECEIPT DATE --}}
            <col width="160" style="width: 120pt;" />  {{-- CHEQUE BALANCE DUE --}}
            <col width="110" style="width: 83pt;" />   {{-- INSTALMENT --}}
            <col width="160" style="width: 120pt;" />  {{-- CHEQUE COLLECTION % --}}
            <col width="160" style="width: 120pt;" />  {{-- PROFIT / LOSS --}}
        </colgroup>
        <thead>
            {{-- Title Header Row (Exact A3 print layout friendly combined 1 row title) --}}
            <tr height="45" style="height: 45pt;">
                <th colspan="23" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                    HINDUSTAN ERP: REAL ESTATE SALES BOOKING MASTER (WITH AUDIT DATES & DUAL-TRACK SPLIT)
                </th>
            </tr>
            {{-- Super Section Headers Row with correct requested colors --}}
            <tr height="30" style="height: 30pt;">
                <th colspan="7" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. UNIT & CUSTOMER INFORMATION</th>
                <th colspan="4" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. PRICING & RATE VARIANCE</th>
                <th colspan="5" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. TAXES & ADD-ONS</th>
                <th colspan="7" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">4. CHEQUE VALUE</th>
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
                <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">TOTAL CHEQUE VALUE</th>
                <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">TOTAL RECEIVED CHEQUE</th>
                <th width="150" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">CHEQUE RECEIPT DATE</th>
                <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE BALANCE DUE</th>
                <th width="110" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">INSTALMENT</th>
                <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE COLLECTION %</th>
                <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">PROFIT / LOSS</th>
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
                    'total_received_cheque' => 0,
                    'cheque_due' => 0,
                    'profit_loss' => 0
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
                    $chequeReceived = (float)$sale->receipts->where('payment_mode', 'Cheque')->sum('amount');
                    $totalReceivedCheque = (float)$sale->receipts->sum('amount');
                    $chequeBalanceDue = (float)$sale->remaining_balance;
                    
                    // Receipt details
                    $latestChequeReceipt = $sale->receipts->where('payment_mode', 'Cheque')->sortByDesc('receipt_date')->first();
                    $receiptDate = $latestChequeReceipt?->receipt_date?->format('Y-m-d') ?? '';
                    $bookingDate = $sale->sale_date?->format('Y-m-d') ?? '';
                    $agreementDate = $sale->agreement_date?->format('Y-m-d') ?? $sale->sale_date?->format('Y-m-d') ?? '';
                    $installmentsCount = $sale->emi_installment_count ?? '';
                    $collectionPct = $totalChequeValue > 0 ? ($totalReceivedCheque / $totalChequeValue) * 100 : 0.00;

                    // Profit / Loss calculation (Sale Amount minus expected sale units amount)
                    $expectedAmount = 0.00;
                    if ($sale->saleUnits && $sale->saleUnits->isNotEmpty()) {
                        foreach ($sale->saleUnits as $su) {
                            $expectedAmount += (float)($su->unit?->expected_sale_amount ?? 0.00);
                        }
                    } else {
                        $expectedAmount = (float)($sale->unit?->expected_sale_amount ?? 0.00);
                    }
                    $saleAmount = (float)($sale->sale_amount ?? 0.00);
                    $profitLoss = $saleAmount - $expectedAmount;
 
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
                    $totals['total_received_cheque'] += $totalReceivedCheque;
                    $totals['cheque_due'] += $chequeBalanceDue;
                    $totals['profit_loss'] += $profitLoss;
 
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

                    // Conditional highlights for Profit/Loss (Loss < 0 = Red text, Profit > 0 = Green text)
                    $profitLossStyle = '';
                    if ($profitLoss < 0) {
                        $profitLossStyle = 'color: #991b1b; font-weight: bold;';
                    } elseif ($profitLoss > 0) {
                        $profitLossStyle = 'color: #166534; font-weight: bold;';
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
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; background-color: #dcfce7; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totalChequeValue }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totalReceivedCheque }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $receiptDate }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $balanceStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $chequeBalanceDue }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $installmentsCount }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; {{ $pctStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $collectionPct / 100 }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: right; padding-right: 8px; {{ $profitLossStyle }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $profitLoss != 0 ? abs($profitLoss) : '0' }}</td>
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
                <td colspan="2" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['gst_amount'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['parking'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['additional'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['grand_total'] }}</td>
                {{-- Section 4 Totals --}}
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['cheque_value'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['total_received_cheque'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['cheque_due'] }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
                @php
                    $overallCollectionPct = $totals['cheque_value'] > 0 ? ($totals['total_received_cheque'] / $totals['cheque_value'] * 100) : 0.00;
                    $profitLossTotalStyle = $totals['profit_loss'] < 0 ? 'color: #fee2e2;' : ($totals['profit_loss'] > 0 ? 'color: #dcfce7;' : 'color: #ffffff;');
                @endphp
                <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '0\.0%';">{{ $overallCollectionPct / 100 }}</td>
                <td bgcolor="#17365D" style="background-color: #17365D; {{ $profitLossTotalStyle }} text-align: right; padding-right: 8px; border: 0.5pt solid #475569; font-size: 9pt; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['profit_loss'] }}</td>
              </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script>
    function exportCurrentTable() {
        const table = document.querySelector("#salesExcelTable");
        if (!table) {
            alert("No table available to export.");
            return;
        }

        const filename = 'HindustanERP_Sales_Booking_Report.xlsx';

        // Create workbook and worksheet
        const workbook = new ExcelJS.Workbook();
        const sheetName = 'Sales Booking Master';
        const worksheet = workbook.addWorksheet(sheetName);

        // Configure views and page setups
        worksheet.views = [{ state: 'frozen', xSplit: 1, ySplit: 3, activePane: 'bottomRight' }];
        worksheet.pageSetup = {
            paperSize: 8, // A3
            orientation: 'landscape',
            fitToPage: true,
            fitToWidth: 1,
            fitToHeight: 0
        };
        worksheet.pageSetup.printTitles = '1:3';

        // Set column widths from colgroup/cols
        const cols = table.querySelectorAll("colgroup col");
        if (cols.length > 0) {
            worksheet.columns = Array.from(cols).map((col) => {
                const widthPt = col.style.width || col.getAttribute("width");
                let widthVal = 15; // default
                if (widthPt) {
                    const match = widthPt.match(/[\d\.]+/);
                    if (match) {
                        const val = parseFloat(match[0]);
                        if (widthPt.includes("pt")) {
                            widthVal = val / 6.5;
                        } else {
                            widthVal = val / 7.5;
                        }
                    }
                }
                return { width: Math.max(widthVal, 8) };
            });
        }

        // Helper to convert CSS color to Hex
        function cssColorToHex(cssColor) {
            if (!cssColor) return null;
            cssColor = cssColor.trim();
            if (cssColor.startsWith('#')) {
                let hex = cssColor.substring(1);
                if (hex.length === 3) {
                    hex = hex.split('').map(c => c + c).join('');
                }
                return 'FF' + hex.toUpperCase();
            }
            if (cssColor.startsWith('rgb')) {
                const parts = cssColor.match(/\d+/g);
                if (parts && parts.length >= 3) {
                    const r = parseInt(parts[0]).toString(16).padStart(2, '0');
                    const g = parseInt(parts[1]).toString(16).padStart(2, '0');
                    const b = parseInt(parts[2]).toString(16).padStart(2, '0');
                    return 'FF' + (r + g + b).toUpperCase();
                }
            }
            const nameMap = {
                'white': 'FFFFFFFF',
                'black': 'FF000000',
                'red': 'FFFF0000',
                'green': 'FF00FF00',
                'blue': 'FF0000FF'
            };
            return nameMap[cssColor.toLowerCase()] || null;
        }

        // Parse grid rows and cells
        const rows = table.querySelectorAll("tr");
        const mergedCells = [];

        function isMerged(r, c) {
            return mergedCells.some(m => r >= m.s.r && r <= m.e.r && c >= m.s.c && c <= m.e.c);
        }

        rows.forEach((tr, rIdx) => {
            const sheetRow = worksheet.getRow(rIdx + 1);
            
            // Set row height
            const heightAttr = tr.getAttribute("height") || tr.style.height;
            if (heightAttr) {
                const match = heightAttr.match(/[\d\.]+/);
                if (match) {
                    sheetRow.height = parseFloat(match[0]);
                }
            }

            const cells = tr.cells;
            let colIdx = 1;

            for (let cIdx = 0; cIdx < cells.length; cIdx++) {
                const cell = cells[cIdx];

                // Find next free cell column in sheet
                while (isMerged(rIdx + 1, colIdx)) {
                    colIdx++;
                }

                const colspan = parseInt(cell.getAttribute("colspan")) || 1;
                const rowspan = parseInt(cell.getAttribute("rowspan")) || 1;

                if (colspan > 1 || rowspan > 1) {
                    worksheet.mergeCells(rIdx + 1, colIdx, rIdx + rowspan, colIdx + colspan - 1);
                    mergedCells.push({
                        s: { r: rIdx + 1, c: colIdx },
                        e: { r: rIdx + rowspan, c: colIdx + colspan - 1 }
                    });
                }

                const excelCell = worksheet.getCell(rIdx + 1, colIdx);
                const rawVal = cell.textContent ? cell.textContent.trim() : '';

                // Styling extractions (using inline style attributes directly to support hidden table)
                const bgColorAttr = cell.getAttribute("bgcolor") || cell.style.backgroundColor;
                const bgColorHex = cssColorToHex(bgColorAttr);
                
                const textColorAttr = cell.style.color;
                const textColorHex = cssColorToHex(textColorAttr) || 'FF000000';

                const isBold = cell.tagName === 'TH' || cell.style.fontWeight === 'bold';
                const fontSizeMatch = (cell.style.fontSize || '').match(/[\d\.]+/);
                const fontSize = fontSizeMatch ? parseFloat(fontSizeMatch[0]) : 10;

                // Alignments
                let horizAlign = cell.style.textAlign || (cell.tagName === 'TH' ? 'center' : 'left');
                if (horizAlign === 'start') horizAlign = 'left';
                if (horizAlign === 'end') horizAlign = 'right';

                let vertAlign = cell.style.verticalAlign || 'middle';

                // Formatting detection from custom mso-number-format
                const numberFormat = cell.style.msoNumberFormat || '';
                
                // Populate excelCell value and format
                if (numberFormat.includes('dd-mmm-yyyy') || numberFormat.includes('dd\\-mmm\\-yyyy')) {
                    // Check if valid YYYY-MM-DD
                    if (rawVal && /^\d{4}-\d{2}-\d{2}$/.test(rawVal)) {
                        excelCell.value = new Date(rawVal);
                    } else {
                        excelCell.value = rawVal;
                    }
                    excelCell.numFormat = 'dd-mmm-yyyy';
                } else if (numberFormat.includes('0\\.0%') || numberFormat.includes('0.0%')) {
                    const parsedFloat = parseFloat(rawVal);
                    if (!isNaN(parsedFloat)) {
                        excelCell.value = parsedFloat;
                    } else {
                        excelCell.value = rawVal;
                    }
                    excelCell.numFormat = '0.0%';
                } else if (numberFormat.includes('\\#\\,\\#\\#0') || numberFormat.includes('#,##0')) {
                    const cleanVal = rawVal.replace(/[^\d\.\-]/g, '');
                    const parsedNum = parseFloat(cleanVal);
                    if (rawVal && !isNaN(parsedNum)) {
                        excelCell.value = parsedNum;
                    } else {
                        excelCell.value = '';
                    }
                    excelCell.numFormat = '#,##0';
                } else {
                    // General number parsing if it looks like a clean integer/float
                    if (rawVal && /^\-?\d+(\.\d+)?$/.test(rawVal)) {
                        excelCell.value = parseFloat(rawVal);
                    } else {
                        excelCell.value = rawVal;
                    }
                }

                // Apply formatting styles
                excelCell.font = {
                    name: 'Calibri',
                    size: fontSize,
                    bold: isBold,
                    color: { argb: textColorHex }
                };

                if (bgColorHex) {
                    excelCell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: bgColorHex }
                    };
                }

                excelCell.alignment = {
                    horizontal: horizAlign,
                    vertical: vertAlign,
                    wrapText: true
                };

                // Add thin gray borders
                excelCell.border = {
                    top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                    right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                };

                colIdx += colspan;
            }
        });

        // Write workbook and download
        workbook.xlsx.writeBuffer().then(function (data) {
            const blob = new Blob([data], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            const url = window.URL.createObjectURL(blob);
            const anchor = document.createElement("a");
            anchor.href = url;
            anchor.download = filename;
            anchor.click();
            window.URL.revokeObjectURL(url);
        });
    }
</script>

</x-erp-layout>




