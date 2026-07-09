<x-erp-layout>
    <x-slot:title>Project Master</x-slot:title>
    <x-slot:headerTitle>Real Estate Projects</x-slot:headerTitle>

    <div class="space-y-6">
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

        <!-- Project Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $proj)
                <div class="card-hover bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:border-slate-300">
                    <!-- Banner Info -->
                    <div class="p-5 border-b border-slate-100 flex-1 space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[9px] font-bold text-indigo-650 uppercase tracking-wider bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded">
                                    {{ $proj->code }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 mt-2 leading-snug">
                                    <a href="{{ route('projects.show', $proj->id) }}" class="hover:text-primary transition-colors">
                                        {{ $proj->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-450 mt-1 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $proj->city }}, {{ $proj->state_or_emirate }}
                                </p>
                            </div>

                            @php
                                $statusColors = [
                                    'planning' => 'bg-slate-50 text-slate-700 border-slate-200',
                                    'ongoing' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'on_hold' => 'bg-amber-50 text-amber-700 border-amber-100'
                                ];
                                $colorClass = $statusColors[$proj->status] ?? $statusColors['planning'];
                            @endphp
                            <span class="badge border font-bold uppercase {{ $colorClass }} text-[10px]">
                                {{ str_replace('_', ' ', $proj->status) }}
                            </span>
                        </div>

                        <!-- Progress of Units Available vs Total -->
                        @if($proj->units_count > 0)
                            <div class="space-y-1.5 pt-2">
                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <span>Units Available</span>
                                    <span class="text-slate-800">{{ $proj->available_units_count }} / {{ $proj->units_count }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    @php
                                        $percent = ($proj->available_units_count / $proj->units_count) * 100;
                                    @endphp
                                    <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider pt-4 italic">
                                No units generated yet.
                            </div>
                        @endif
                    </div>

                    <!-- Footer Controls -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            rera: {{ $proj->rera_number ?? 'Exempt/Pending' }}
                        </div>
                        <div class="inline-flex items-center justify-end gap-1.5">
                            @can('projects.manage')
                                <a href="{{ route('projects.edit', $proj->id) }}" title="Edit Project Details" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            @endcan
                            <a href="{{ route('projects.show', $proj->id) }}" title="View Project Unit Grid" class="p-2 rounded-lg bg-[#a38c29]/10 hover:bg-[#a38c29]/20 text-[#a38c29] hover:text-[#8a7522] transition inline-flex items-center justify-center shadow-sm">
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
    </div>
</x-erp-layout>
