<x-erp-layout title="Document Repository" headerTitle="Document Management Center">

<div class="max-w-[1800px] mx-auto space-y-6" 
     x-data="dmsExplorerApp()" 
     @open-dms-upload.window="openUploadModal('{{ $selectedCategory ?: 'project' }}')">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">
                Document Repository
            </h1>
            <p class="text-xs text-slate-500 mt-1">Centralized, secure document vault for project approvals, contractor agreements, legal deeds, company records, and templates.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button"
                    @click="openUploadModal('{{ $selectedCategory ?: 'project' }}')" 
                    class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Upload Document
            </button>
        </div>
    </div>

    {{-- Feedback Messages --}}
    @if(session('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold rounded-xl uppercase tracking-wide flex items-center justify-between shadow-sm">
            <span>{{ session('status') }}</span>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-250 text-rose-800 text-xs font-bold rounded-xl uppercase tracking-wide flex items-center justify-between shadow-sm">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">✕</button>
        </div>
    @endif

    {{-- Repository Categorization Grid (8 Repositories including Contractor) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
        @foreach($categoriesInfo as $catKey => $info)
            @php
                $isSelected = ($selectedCategory === $catKey);
                $docCount = $categoryCounts[$catKey] ?? 0;
                
                // Color configuration mapping
                $colors = [
                    'project' => [
                        'bg' => 'bg-blue-50', 
                        'text' => 'text-blue-600', 
                        'border' => 'border-blue-200',
                        'border-l' => 'border-l-blue-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.25)]',
                        'hover-border' => 'hover:border-r-blue-500/20 hover:border-y-blue-500/20',
                        'hover-icon' => 'group-hover:bg-blue-600 group-hover:text-white',
                    ],
                    'contractor' => [
                        'bg' => 'bg-amber-50', 
                        'text' => 'text-amber-700', 
                        'border' => 'border-amber-200',
                        'border-l' => 'border-l-amber-600',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(217,119,6,0.25)]',
                        'hover-border' => 'hover:border-r-amber-600/20 hover:border-y-amber-600/20',
                        'hover-icon' => 'group-hover:bg-amber-600 group-hover:text-white',
                    ],
                    'legal' => [
                        'bg' => 'bg-emerald-50', 
                        'text' => 'text-emerald-600', 
                        'border' => 'border-emerald-200',
                        'border-l' => 'border-l-emerald-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.25)]',
                        'hover-border' => 'hover:border-r-emerald-500/20 hover:border-y-emerald-500/20',
                        'hover-icon' => 'group-hover:bg-emerald-600 group-hover:text-white',
                    ],
                    'company' => [
                        'bg' => 'bg-orange-50', 
                        'text' => 'text-orange-600', 
                        'border' => 'border-orange-200',
                        'border-l' => 'border-l-orange-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(249,115,22,0.25)]',
                        'hover-border' => 'hover:border-r-orange-500/20 hover:border-y-orange-500/20',
                        'hover-icon' => 'group-hover:bg-orange-600 group-hover:text-white',
                    ],
                    'hr' => [
                        'bg' => 'bg-purple-50', 
                        'text' => 'text-purple-600', 
                        'border' => 'border-purple-200',
                        'border-l' => 'border-l-purple-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(168,85,247,0.25)]',
                        'hover-border' => 'hover:border-r-purple-500/20 hover:border-y-purple-500/20',
                        'hover-icon' => 'group-hover:bg-purple-600 group-hover:text-white',
                    ],
                    'partner' => [
                        'bg' => 'bg-teal-50', 
                        'text' => 'text-teal-600', 
                        'border' => 'border-teal-200',
                        'border-l' => 'border-l-teal-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(20,184,166,0.25)]',
                        'hover-border' => 'hover:border-r-teal-500/20 hover:border-y-teal-500/20',
                        'hover-icon' => 'group-hover:bg-teal-600 group-hover:text-white',
                    ],
                    'drawings' => [
                        'bg' => 'bg-indigo-50', 
                        'text' => 'text-indigo-600', 
                        'border' => 'border-indigo-200',
                        'border-l' => 'border-l-indigo-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(99,102,241,0.25)]',
                        'hover-border' => 'hover:border-r-indigo-500/20 hover:border-y-indigo-500/20',
                        'hover-icon' => 'group-hover:bg-indigo-600 group-hover:text-white',
                    ],
                    'templates' => [
                        'bg' => 'bg-rose-50', 
                        'text' => 'text-rose-600', 
                        'border' => 'border-rose-200',
                        'border-l' => 'border-l-rose-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(244,63,94,0.25)]',
                        'hover-border' => 'hover:border-r-rose-500/20 hover:border-y-rose-500/20',
                        'hover-icon' => 'group-hover:bg-rose-600 group-hover:text-white',
                    ],
                ][$catKey] ?? [
                    'bg' => 'bg-slate-50', 
                    'text' => 'text-slate-600', 
                    'border' => 'border-slate-200',
                    'border-l' => 'border-l-[#a38c29]',
                    'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.25)]',
                    'hover-border' => 'hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20',
                    'hover-icon' => 'group-hover:bg-[#a38c29] group-hover:text-white',
                ];

                // Icon mapping
                $iconSvg = [
                    'project' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'contractor' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>',
                    'legal' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                    'company' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'hr' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                    'partner' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>',
                    'drawings' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                    'templates' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>'
                ][$catKey] ?? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
            @endphp
            <a href="{{ route('dms.index', array_merge(request()->except(['category', 'page']), $isSelected ? [] : ['category' => $catKey])) }}" 
               class="bg-white rounded-2xl border border-l-[5px] {{ $colors['border-l'] }} border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-4 px-2.5 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1 {{ $colors['hover-shadow'] }} {{ $colors['hover-border'] }} h-full min-h-[130px] {{ $isSelected ? 'ring-2 ring-[#a38c29]/25 bg-slate-50/10' : '' }}">
                
                <div class="w-9 h-9 rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} flex items-center justify-center shrink-0 mb-2.5 transition-all duration-300 {{ $colors['hover-icon'] }} group-hover:shadow-md group-hover:scale-110">
                    {!! $iconSvg !!}
                </div>
                
                <div class="flex flex-col justify-between">
                    <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-tight leading-tight px-1 mb-1 min-h-[26px] flex items-center justify-center transition-colors group-hover:text-slate-900">{{ $info['label'] }}</h4>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">{{ $docCount }} Docs</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- File Browser & Sidebar Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- Left: Repository Filter & Table --}}
        <div class="lg:col-span-2 space-y-4">
            
            {{-- Status Filter Tabs (Active, Expiring Soon, Expired, Archived, All) --}}
            <div class="flex flex-wrap items-center gap-2 bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200">
                @php
                    $curStatus = request('status', 'active');
                @endphp
                <a href="{{ route('dms.index', array_merge(request()->except(['status', 'page']), ['status' => 'active'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $curStatus === 'active' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>Active Records</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $curStatus === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">{{ $totalActiveCount ?? 0 }}</span>
                </a>

                <a href="{{ route('dms.index', array_merge(request()->except(['status', 'page']), ['status' => 'expiring_soon'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $curStatus === 'expiring_soon' ? 'bg-white text-amber-800 shadow-sm border border-amber-200' : 'text-slate-600 hover:text-amber-800 hover:bg-white/50' }}">
                    <span>Expiring Soon (30d)</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $curStatus === 'expiring_soon' ? 'bg-amber-100 text-amber-900' : 'bg-amber-100/60 text-amber-800' }}">{{ $totalExpiringCount ?? 0 }}</span>
                </a>

                <a href="{{ route('dms.index', array_merge(request()->except(['status', 'page']), ['status' => 'expired'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $curStatus === 'expired' ? 'bg-white text-rose-800 shadow-sm border border-rose-200' : 'text-slate-600 hover:text-rose-800 hover:bg-white/50' }}">
                    <span>Expired</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $curStatus === 'expired' ? 'bg-rose-100 text-rose-900' : 'bg-rose-100/60 text-rose-800' }}">{{ $totalExpiredCount ?? 0 }}</span>
                </a>

                <a href="{{ route('dms.index', array_merge(request()->except(['status', 'page']), ['status' => 'archived'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $curStatus === 'archived' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <span>📦 Archived Documents</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $curStatus === 'archived' ? 'bg-slate-200 text-slate-800' : 'bg-slate-200/60 text-slate-600' }}">{{ $totalArchivedCount ?? 0 }}</span>
                </a>

                <a href="{{ route('dms.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $curStatus === 'all' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    All Records
                </a>
            </div>
            
            {{-- Advanced Filters Bar (Single Horizontal Line) --}}
            <form method="GET" action="{{ route('dms.index') }}" class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-sm flex flex-col lg:flex-row items-stretch lg:items-end gap-2.5">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="hidden" name="status" value="{{ request('status', 'active') }}">
                
                {{-- Search keyword --}}
                <div class="flex-1 min-w-[180px] space-y-1">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Search Title / Document #</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, doc #..." 
                               class="w-full pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 placeholder-slate-400">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                {{-- Category filter if not selected --}}
                @if(!$selectedCategory)
                    <div class="w-full lg:w-40 space-y-1">
                        <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Category</label>
                        <select name="category" class="w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach($categoriesInfo as $k => $info)
                                <option value="{{ $k }}" @selected(request('category') === $k)>{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Document Type --}}
                <div class="w-full lg:w-44 space-y-1">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Document Type</label>
                    <select name="document_type" class="w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 cursor-pointer">
                        <option value="">All Document Types</option>
                        @if($selectedCategory && isset($categoriesInfo[$selectedCategory]))
                            @foreach($categoriesInfo[$selectedCategory]['types'] as $type)
                                <option value="{{ $type }}" @selected(request('document_type') === $type)>{{ $type }}</option>
                            @endforeach
                        @else
                            @foreach($categoriesInfo as $k => $info)
                                <optgroup label="{{ $info['label'] }}">
                                    @foreach($info['types'] as $type)
                                        <option value="{{ $type }}" @selected(request('document_type') === $type)>{{ $type }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Upload Date Range --}}
                <div class="w-full lg:w-36 space-y-1">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 cursor-pointer">
                </div>
                <div class="w-full lg:w-36 space-y-1">
                    <label class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider block">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-2.5 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 cursor-pointer">
                </div>

                {{-- Submit Button --}}
                <div class="shrink-0">
                    <button type="submit" class="w-full lg:w-auto px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Apply</span>
                    </button>
                </div>
            </form>

            {{-- Table List Explorer Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                
                {{-- Header/Title of block --}}
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                            Document Explorer {{ $selectedCategory ? '— ' . ($categoriesInfo[$selectedCategory]['label'] ?? '') : '' }}
                        </h2>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Showing {{ $documents->total() }} recorded documents</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="openUploadModal('{{ $selectedCategory ?: 'project' }}')" 
                                class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-[10px] font-bold uppercase tracking-wide transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New Upload
                        </button>
                    </div>
                </div>

                {{-- Table List --}}
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-[9px] font-bold uppercase text-slate-500 tracking-wider">
                                <th class="px-5 py-3">Document Details</th>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3">Reference Entity</th>
                                <th class="px-5 py-3">Document Type</th>
                                <th class="px-5 py-3">Issue/Expiry</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-150 text-xs">
                            @forelse($documents as $doc)
                                @php
                                    $fileExt = strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                                    $iconBg = 'bg-slate-50 text-slate-400 border-slate-150';
                                    if ($fileExt === 'pdf') {
                                        $iconBg = 'bg-rose-50 text-rose-500 border-rose-150';
                                    } elseif (in_array($fileExt, ['doc', 'docx'])) {
                                        $iconBg = 'bg-blue-50 text-blue-500 border-blue-150';
                                    } elseif (in_array($fileExt, ['png', 'jpg', 'jpeg', 'webp'])) {
                                        $iconBg = 'bg-emerald-50 text-emerald-500 border-emerald-150';
                                    } elseif (in_array($fileExt, ['xls', 'xlsx'])) {
                                        $iconBg = 'bg-teal-50 text-teal-600 border-teal-150';
                                    } elseif (in_array($fileExt, ['dwg', 'dxf'])) {
                                        $iconBg = 'bg-indigo-50 text-indigo-600 border-indigo-150';
                                    }

                                    // Status & Validity Badge
                                    $statusLabel = 'Active';
                                    $statusBadge = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                    if ($doc->is_archived) {
                                        $statusLabel = '📦 Archived';
                                        $statusBadge = 'bg-slate-100 text-slate-600 border-slate-200';
                                    } elseif ($doc->expiry_date) {
                                        if ($doc->expiry_date->isPast()) {
                                            $statusLabel = '🔴 Expired (' . $doc->expiry_date->diffForHumans() . ')';
                                            $statusBadge = 'bg-rose-50 text-rose-800 border-rose-200';
                                        } elseif ($doc->expiry_date->diffInDays(now()) <= 30) {
                                            $daysLeft = round(now()->diffInDays($doc->expiry_date, false));
                                            $statusLabel = '⚠️ ' . $daysLeft . ' Days Left';
                                            $statusBadge = 'bg-amber-50 text-amber-900 border-amber-200';
                                        }
                                    }

                                    // Resolve Display reference name
                                    $refName = 'System / Company';
                                    if ($doc->documentable) {
                                        $refName = $doc->documentable->name ?? ($doc->documentable->title ?? 'Entity Record');
                                    } elseif ($doc->referenceProject) {
                                        $refName = $doc->referenceProject->name;
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors {{ $doc->is_archived ? 'opacity-70 bg-slate-50/30' : '' }}">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg border flex items-center justify-center font-bold text-[10px] {{ $iconBg }} shrink-0 shadow-2xs">
                                                {{ strtoupper($fileExt ?: 'FILE') }}
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="text-xs font-bold text-slate-800 tracking-tight leading-snug truncate" title="{{ $doc->title }}">{{ $doc->title }}</h4>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    @if($doc->document_number)
                                                        <span class="text-[9px] px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 font-mono font-bold">{{ $doc->document_number }}</span>
                                                    @endif
                                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $doc->formatted_size }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="badge border bg-slate-50 text-slate-600 border-slate-200 text-[9px] font-bold uppercase px-2 py-0.5 rounded-md whitespace-nowrap">
                                            {{ $categoriesInfo[$doc->category]['label'] ?? $doc->category }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-700 truncate max-w-[150px]" title="{{ $refName }}">
                                            {{ $refName }}
                                        </div>
                                        @if($doc->tower)
                                            <span class="text-[9px] text-[#a38c29] font-bold uppercase tracking-wide block mt-0.5">Unit: {{ $doc->tower }}</span>
                                        @elseif($doc->department)
                                            <span class="text-[9px] text-purple-600 font-bold uppercase tracking-wide block mt-0.5">Dept: {{ $doc->department }}</span>
                                        @elseif($doc->drawing_type)
                                            <span class="text-[9px] text-indigo-600 font-bold uppercase tracking-wide block mt-0.5">{{ $doc->drawing_type }} {{ $doc->revision_number ? '('.$doc->revision_number.')' : '' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-semibold text-slate-700 whitespace-nowrap">{{ $doc->document_type }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-medium text-slate-600 font-mono text-[10px]">
                                            Issue: {{ $doc->issue_date ? $doc->issue_date->format('d-M-Y') : 'N/A' }}
                                        </div>
                                        <div class="font-semibold text-rose-600 font-mono text-[10px] mt-0.5">
                                            Exp: {{ $doc->expiry_date ? $doc->expiry_date->format('d-M-Y') : '—' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="badge border px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase whitespace-nowrap {{ $statusBadge }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            {{-- Preview Button (Gold Theme) --}}
                                            <button type="button" 
                                                    @click='openPreview("{{ route("dms.preview", $doc->id) }}", {{ json_encode($doc->title) }}, "{{ $doc->mime_type }}", "{{ route("dms.download", $doc->id) }}")'
                                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-amber-50 hover:bg-[#a38c29] border border-amber-200 hover:border-[#a38c29] text-[#a38c29] hover:text-white transition-all duration-200 shadow-2xs cursor-pointer" 
                                                    title="Preview Document">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>

                                            {{-- Download Button (Indigo Theme) --}}
                                            <a href="{{ route('dms.download', $doc->id) }}" 
                                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-indigo-50 hover:bg-indigo-600 border border-indigo-200 hover:border-indigo-600 text-indigo-600 hover:text-white transition-all duration-200 shadow-2xs cursor-pointer" 
                                               title="Download File">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                            
                                            {{-- Archive / Unarchive Toggle Button (Teal / Emerald Theme) --}}
                                            @if(!$doc->is_archived)
                                                <button type="button" 
                                                        @click='confirmArchive({{ $doc->id }}, {{ json_encode($doc->title) }}, "archive")'
                                                        class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-teal-50 hover:bg-teal-600 border border-teal-200 hover:border-teal-600 text-teal-600 hover:text-white transition-all duration-200 shadow-2xs cursor-pointer" 
                                                        title="Archive Document">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                                </button>
                                            @else
                                                <button type="button" 
                                                        @click='confirmArchive({{ $doc->id }}, {{ json_encode($doc->title) }}, "unarchive")'
                                                        class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-emerald-50 hover:bg-emerald-600 border border-emerald-200 hover:border-emerald-600 text-emerald-600 hover:text-white transition-all duration-200 shadow-2xs cursor-pointer" 
                                                        title="Restore to Active">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                </button>
                                            @endif

                                            {{-- Permanent Delete (Rose Theme) --}}
                                            <button type="button" 
                                                    @click='confirmDelete({{ $doc->id }}, {{ json_encode($doc->title) }})'
                                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 text-rose-600 hover:text-white transition-all duration-200 shadow-2xs cursor-pointer" 
                                                    title="Delete Document">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center bg-slate-50/20">
                                        <div class="w-12 h-12 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 shadow-2xs">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-1">No Documents Found</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase max-w-xs mx-auto">No repository records match your filter criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination footer --}}
                @if($documents->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/30">
                        {{ $documents->links() }}
                    </div>
                @endif

            </div>
        </div>

        {{-- Right: Sidebar Info Cards --}}
        <div class="space-y-6">
            
            {{-- Card 1: Expirations --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Documents Expiring Soon</h3>
                    <a href="{{ route('dms.index', ['status' => 'expiring_soon']) }}" class="text-[9px] font-extrabold text-[#a38c29] uppercase tracking-wide hover:underline">View All</a>
                </div>
                <div class="p-4.5 space-y-3">
                    @forelse($expiringSoon as $expDoc)
                        @php
                            $daysLeft = max(round(now()->diffInDays($expDoc->expiry_date, false)), 0);
                            $alertColor = 'text-amber-600 bg-amber-50 border-amber-100';
                            if ($daysLeft <= 15) {
                                $alertColor = 'text-rose-600 bg-rose-50 border-rose-100';
                            }
                            $expRefName = 'System / Company';
                            if ($expDoc->referenceProject) {
                                $expRefName = $expDoc->referenceProject->name;
                            } elseif ($expDoc->documentable && $expDoc->documentable_type === 'App\Models\Project') {
                                $expRefName = $expDoc->documentable->name;
                            }
                        @endphp
                        <div class="flex items-start gap-3 p-3 bg-white border border-slate-150 rounded-xl hover:shadow-xs transition">
                            <div class="w-8 h-8 rounded-lg border flex items-center justify-center shrink-0 {{ $alertColor }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-slate-800 truncate">{{ $expDoc->title }}</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5 truncate">{{ $expRefName }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[9px] font-extrabold uppercase tracking-wide block {{ $daysLeft <= 15 ? 'text-rose-600' : 'text-amber-600' }}">{{ $daysLeft }} Days Left</span>
                                <span class="text-[9px] font-mono text-slate-500 font-bold block mt-0.5">{{ $expDoc->expiry_date->format('d-M-Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400">
                            <p class="text-[10px] font-bold uppercase tracking-wider">No Expiring Documents</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Card 2: Storage Overview --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Storage Overview</h3>
                </div>
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="text-[#a38c29]" stroke-width="3.5" stroke-dasharray="{{ $storageStats['used_percent'] }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                        <span class="absolute font-extrabold text-[11px] text-slate-800">{{ $storageStats['used_percent'] }}%</span>
                    </div>
                    <div class="flex-1 space-y-1.5 text-xs">
                        <div class="flex justify-between text-slate-500 font-semibold">
                            <span>Total Space:</span>
                            <span class="text-slate-900 font-bold font-mono">{{ $storageStats['limit'] }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 font-semibold">
                            <span>Used Space:</span>
                            <span class="text-[#a38c29] font-bold font-mono">{{ $storageStats['used'] }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 font-semibold">
                            <span>Available:</span>
                            <span class="text-emerald-600 font-bold font-mono">{{ $storageStats['available'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- File Upload Modal (Original Aesthetic with Height-Optimized Drop Zone - Zero Scroll) --}}
    <template x-teleport="body">
        <div x-show="showUploadModal" 
             class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-md"
             x-transition.opacity 
             style="display: none;">
            
            <div @click.outside="showUploadModal = false" 
                 class="bg-white w-full max-w-3xl rounded-3xl shadow-2xl overflow-hidden flex flex-col animate-fade-in-up"
                 x-transition.scale>
                
                {{-- Modal Header (Increased Corner Radius & Generous Padding) --}}
                <div class="px-8 py-6 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-900 flex items-center justify-between text-white relative overflow-hidden shrink-0">
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/25 text-[#d9bf3b] text-[9px] font-extrabold uppercase tracking-widest">Document Management</span>
                            <span class="text-[10px] text-slate-400 font-semibold">• New Entry</span>
                        </div>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Upload & Register Document</h2>
                        <p class="text-[11px] text-slate-300 font-medium mt-0.5">Upload, categorize, and archive company, project, contractor, employee, and legal documents.</p>
                    </div>
                    <button type="button" @click="showUploadModal = false" class="text-slate-400 hover:text-white transition w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs font-bold shrink-0 relative z-10 cursor-pointer">✕</button>
                </div>

                {{-- Modal Body Form --}}
                <form action="{{ route('dms.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col m-0 p-0">
                    @csrf
                    
                    <div class="p-6 space-y-3.5 text-left font-sans text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        
                        {{-- ROW 1 - LEFT: Document Category --}}
                        <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                            <label class="text-[11px] font-bold text-slate-700 block">
                                Document Category <span class="text-rose-500">*</span>
                            </label>
                            <input type="hidden" name="category" :value="uploadCategory" required>
                            
                            <button type="button" 
                                    @click="open = !open; if(open) $nextTick(() => $refs.catSearch.focus())"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                <span class="flex items-center gap-2 truncate">
                                    <svg class="w-4 h-4 text-[#a38c29] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span x-text="categoryLabels[uploadCategory] || '-- Choose Category --'" 
                                          :class="!uploadCategory ? 'text-slate-400 font-normal' : 'text-slate-900 font-bold'"></span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" 
                                 @click.outside="open = false; search = ''"
                                 x-transition.origin.top.duration.150ms
                                 class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                 style="display: none;">
                                <div class="relative">
                                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" 
                                           x-ref="catSearch"
                                           x-model="search" 
                                           placeholder="Search category..." 
                                           class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                </div>
                                <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                    <template x-for="cat in filteredCategories(search)" :key="cat.code">
                                        <button type="button" 
                                                @click="selectCategory(cat.code); open = false; search = ''"
                                                class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                :class="uploadCategory === cat.code ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                            <span x-text="cat.label"></span>
                                            <span x-show="uploadCategory === cat.code" class="text-xs text-[#a38c29]">✓</span>
                                        </button>
                                    </template>
                                    <div x-show="filteredCategories(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                        No category found
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 1 - RIGHT: Associated Entity Selector (Project / Contractor / Employee / Partner) --}}
                        
                        {{-- 1. Project Selector (when category is project, drawings, legal, templates, company) --}}
                        <template x-if="['project', 'drawings', 'legal', 'templates', 'company'].includes(uploadCategory)">
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                                <label class="text-[11px] font-bold text-slate-700 block">
                                    Project <span x-show="['project', 'drawings'].includes(uploadCategory)" class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="project_id" :value="uploadProjectId" :required="['project', 'drawings'].includes(uploadCategory)">
                                
                                <button type="button" 
                                        @click="open = !open; if(open) $nextTick(() => $refs.projSearch.focus())"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                    <span x-text="projectLabels[uploadProjectId] || '-- Choose Project --'" 
                                          :class="!uploadProjectId ? 'text-slate-400 font-normal' : 'text-slate-900 font-bold'"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false; search = ''"
                                     x-transition.origin.top.duration.150ms
                                     class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                     style="display: none;">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-ref="projSearch"
                                               x-model="search" 
                                               placeholder="Search project..." 
                                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                    </div>
                                    <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                        <template x-for="p in filteredProjects(search)" :key="p.id">
                                            <button type="button" 
                                                    @click="uploadProjectId = p.id; uploadUnit = ''; open = false; search = ''"
                                                    class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                    :class="String(uploadProjectId) === String(p.id) ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                                <span x-text="p.name"></span>
                                                <span x-show="String(uploadProjectId) === String(p.id)" class="text-xs text-[#a38c29]">✓</span>
                                            </button>
                                        </template>
                                        <div x-show="filteredProjects(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                            No project found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- 2. Contractor Selector (when category is contractor) --}}
                        <template x-if="uploadCategory === 'contractor'">
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                                <label class="text-[11px] font-bold text-slate-700 block">
                                    Contractor / Supplier <span class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="contractor_id" :value="uploadContractorId" :required="uploadCategory === 'contractor'">
                                
                                <button type="button" 
                                        @click="open = !open; if(open) $nextTick(() => $refs.contSearch.focus())"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                    <span x-text="contractorLabels[uploadContractorId] || '-- Choose Contractor --'" 
                                          :class="!uploadContractorId ? 'text-slate-400 font-normal' : 'text-slate-900 font-bold'"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false; search = ''"
                                     x-transition.origin.top.duration.150ms
                                     class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                     style="display: none;">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-ref="contSearch"
                                               x-model="search" 
                                               placeholder="Search contractor..." 
                                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                    </div>
                                    <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                        <template x-for="c in filteredContractors(search)" :key="c.id">
                                            <button type="button" 
                                                    @click="uploadContractorId = c.id; open = false; search = ''"
                                                    class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                    :class="String(uploadContractorId) === String(c.id) ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                                <span x-text="c.name + (c.phone ? ' (' + c.phone + ')' : (c.type ? ' [' + c.type + ']' : ''))"></span>
                                                <span x-show="String(uploadContractorId) === String(c.id)" class="text-xs text-[#a38c29]">✓</span>
                                            </button>
                                        </template>
                                        <div x-show="filteredContractors(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                            No contractor found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- 3. Employee Selector (when category is hr) --}}
                        <template x-if="uploadCategory === 'hr'">
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                                <label class="text-[11px] font-bold text-slate-700 block">
                                    Employee <span class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="employee_id" :value="uploadEmployeeId" :required="uploadCategory === 'hr'">
                                
                                <button type="button" 
                                        @click="open = !open; if(open) $nextTick(() => $refs.empSearch.focus())"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                    <span x-text="employeeLabels[uploadEmployeeId] || '-- Choose Employee --'" 
                                          :class="!uploadEmployeeId ? 'text-slate-400 font-normal' : 'text-slate-900 font-bold'"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false; search = ''"
                                     x-transition.origin.top.duration.150ms
                                     class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                     style="display: none;">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-ref="empSearch"
                                               x-model="search" 
                                               placeholder="Search employee..." 
                                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                    </div>
                                    <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                        <template x-for="e in filteredEmployees(search)" :key="e.id">
                                            <button type="button" 
                                                    @click="uploadEmployeeId = e.id; open = false; search = ''"
                                                    class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                    :class="String(uploadEmployeeId) === String(e.id) ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                                <span x-text="e.name + (e.employee_id ? ' (' + e.employee_id + ')' : '')"></span>
                                                <span x-show="String(uploadEmployeeId) === String(e.id)" class="text-xs text-[#a38c29]">✓</span>
                                            </button>
                                        </template>
                                        <div x-show="filteredEmployees(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                            No employee found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- 4. Partner Selector (when category is partner) --}}
                        <template x-if="uploadCategory === 'partner'">
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                                <label class="text-[11px] font-bold text-slate-700 block">
                                    Partner / Investor <span class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="partner_id" :value="uploadPartnerId" :required="uploadCategory === 'partner'">
                                
                                <button type="button" 
                                        @click="open = !open; if(open) $nextTick(() => $refs.partSearch.focus())"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                    <span x-text="partnerLabels[uploadPartnerId] || '-- Choose Partner/Investor --'" 
                                          :class="!uploadPartnerId ? 'text-slate-400 font-normal' : 'text-slate-900 font-bold'"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false; search = ''"
                                     x-transition.origin.top.duration.150ms
                                     class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                     style="display: none;">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-ref="partSearch"
                                               x-model="search" 
                                               placeholder="Search partner/investor..." 
                                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                    </div>
                                    <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                        <template x-for="p in filteredPartners(search)" :key="p.id">
                                            <button type="button" 
                                                    @click="uploadPartnerId = p.id; open = false; search = ''"
                                                    class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                    :class="String(uploadPartnerId) === String(p.id) ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                                <span x-text="p.name"></span>
                                                <span x-show="String(uploadPartnerId) === String(p.id)" class="text-xs text-[#a38c29]">✓</span>
                                            </button>
                                        </template>
                                        <div x-show="filteredPartners(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                            No partner/investor found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ROW 2 - LEFT: Contextual Sub-Entity (Unit / Department / Legal / Contractor Project / Drawing Type) --}}
                        
                        {{-- 1. Unit / Tower (Project) --}}
                        <template x-if="uploadCategory === 'project'">
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                                <label class="text-[11px] font-bold text-slate-700 block">Associated Unit / Tower (Optional)</label>
                                <input type="hidden" name="tower" :value="uploadUnit">
                                
                                <button type="button" 
                                        @click="open = !open; if(open) $nextTick(() => $refs.unitSearch.focus())"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                    <span x-text="uploadUnit ? 'Unit: ' + uploadUnit : '-- All Units / Entire Project --'" 
                                          :class="!uploadUnit ? 'text-slate-500 font-normal' : 'text-slate-900 font-bold'"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false; search = ''"
                                     x-transition.origin.top.duration.150ms
                                     class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                     style="display: none;">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-ref="unitSearch"
                                               x-model="search" 
                                               placeholder="Search unit / door no..." 
                                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                    </div>
                                    <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                        <button type="button" 
                                                @click="uploadUnit = ''; open = false; search = ''"
                                                class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                :class="!uploadUnit ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                            <span>-- All Units / Entire Project --</span>
                                            <span x-show="!uploadUnit" class="text-xs text-[#a38c29]">✓</span>
                                        </button>
                                        
                                        <template x-for="u in filteredUnits(search)" :key="u.id">
                                            <button type="button" 
                                                    @click="uploadUnit = u.door_no; open = false; search = ''"
                                                    class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                    :class="uploadUnit === u.door_no ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                                <span x-text="u.door_no"></span>
                                                <span x-show="uploadUnit === u.door_no" class="text-xs text-[#a38c29]">✓</span>
                                            </button>
                                        </template>
                                        <div x-show="filteredUnits(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                            No matching unit
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- 2. Associated Project for Contractor --}}
                        <template x-if="uploadCategory === 'contractor'">
                            <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                                <label class="text-[11px] font-bold text-slate-700 block">Associated Project (Optional)</label>
                                <input type="hidden" name="project_id" :value="uploadProjectId">
                                
                                <button type="button" 
                                        @click="open = !open; if(open) $nextTick(() => $refs.projSearchCont.focus())"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                    <span x-text="projectLabels[uploadProjectId] || '-- All Projects / General --'" 
                                          :class="!uploadProjectId ? 'text-slate-500 font-normal' : 'text-slate-900 font-bold'"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false; search = ''"
                                     x-transition.origin.top.duration.150ms
                                     class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                     style="display: none;">
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" 
                                               x-ref="projSearchCont"
                                               x-model="search" 
                                               placeholder="Search project..." 
                                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                    </div>
                                    <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                        <button type="button" 
                                                @click="uploadProjectId = ''; open = false; search = ''"
                                                class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                :class="!uploadProjectId ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                            <span>-- All Projects / General --</span>
                                            <span x-show="!uploadProjectId" class="text-xs text-[#a38c29]">✓</span>
                                        </button>
                                        <template x-for="p in filteredProjects(search)" :key="p.id">
                                            <button type="button" 
                                                    @click="uploadProjectId = p.id; open = false; search = ''"
                                                    class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                    :class="String(uploadProjectId) === String(p.id) ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                                <span x-text="p.name"></span>
                                                <span x-show="String(uploadProjectId) === String(p.id)" class="text-xs text-[#a38c29]">✓</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- 3. Drawing Type (Drawings) --}}
                        <template x-if="uploadCategory === 'drawings'">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-700 block">Drawing Type</label>
                                <select name="drawing_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer shadow-2xs">
                                    <option value="">-- Choose Drawing Type --</option>
                                    @foreach(['Architectural Drawing', 'Structural Drawing', 'Electrical Layout', 'Plumbing Layout', 'HVAC Layout', 'BOQ Document', 'Engineering Report', 'Site Master Layout', 'Other'] as $dType)
                                        <option value="{{ $dType }}">{{ $dType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </template>

                        {{-- 4. Department (HR & Company) --}}
                        <template x-if="['hr', 'company'].includes(uploadCategory)">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-700 block">Department</label>
                                <select name="department" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition cursor-pointer shadow-2xs">
                                    <option value="">-- Choose Department --</option>
                                    @foreach(['Project Management', 'HR', 'Operations', 'Accounts & Finance', 'Sales & Marketing', 'Projects', 'Legal', 'General'] as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </template>

                        {{-- 5. Legal Category (Legal) --}}
                        <template x-if="uploadCategory === 'legal'">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-700 block">Legal Category</label>
                                <input type="text" name="legal_category" placeholder="e.g. Land Title Records, Court Documents"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                            </div>
                        </template>

                        {{-- 6. Template Category (Templates) --}}
                        <template x-if="uploadCategory === 'templates'">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-700 block">Template Category</label>
                                <input type="text" name="template_category" placeholder="e.g. Sales, Legal, Operations, Billing"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                            </div>
                        </template>

                        {{-- ROW 2 - RIGHT: Document Type --}}
                        <div class="space-y-1.5 relative" x-data="{ open: false, search: '' }">
                            <label class="text-[11px] font-bold text-slate-700 block">
                                Document Type <span class="text-rose-500">*</span>
                            </label>
                            <input type="hidden" name="document_type" :value="uploadDocType" required>
                            
                            <button type="button" 
                                    @click="open = !open; if(open) $nextTick(() => $refs.docTypeSearch.focus())"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition flex items-center justify-between shadow-2xs cursor-pointer">
                                <span x-text="uploadDocType || '-- Choose Document Type --'" 
                                      :class="!uploadDocType ? 'text-slate-400 font-normal' : 'text-slate-900 font-bold'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#a38c29]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" 
                                 @click.outside="open = false; search = ''"
                                 x-transition.origin.top.duration.150ms
                                 class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 p-2 space-y-1.5 flex flex-col"
                                 style="display: none;">
                                <div class="relative">
                                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <input type="text" 
                                           x-ref="docTypeSearch"
                                           x-model="search" 
                                           placeholder="Search document type..." 
                                           class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:bg-white focus:border-[#a38c29] focus:ring-1 focus:ring-[#a38c29] rounded-lg text-xs font-semibold text-slate-800 outline-none transition placeholder-slate-400">
                                </div>
                                <div class="overflow-y-auto max-h-48 space-y-0.5 scrollbar-thin">
                                    <template x-for="type in filteredDocTypes(search)" :key="type">
                                        <button type="button" 
                                                @click="uploadDocType = type; open = false; search = ''"
                                                class="w-full px-3 py-2 text-left rounded-lg text-xs font-semibold transition flex items-center justify-between cursor-pointer"
                                                :class="uploadDocType === type ? 'bg-[#a38c29]/15 text-[#a38c29] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'">
                                            <span x-text="type"></span>
                                            <span x-show="uploadDocType === type" class="text-xs text-[#a38c29]">✓</span>
                                        </button>
                                    </template>
                                    <div x-show="filteredDocTypes(search).length === 0" class="px-3 py-3 text-slate-400 text-center text-xs font-medium">
                                        No document type found
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ROW 3 - LEFT: Document Name --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-700 block">
                                Document Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   required 
                                   placeholder="e.g. Approved Building Plan - Tower A"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                        </div>

                        {{-- ROW 3 - RIGHT: Document Number / Revision --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="text-[11px] font-bold text-slate-700 block">Document Number</label>
                                <template x-if="uploadCategory === 'drawings'">
                                    <span class="text-[10px] text-slate-400 font-bold">Rev: <input type="text" name="revision_number" placeholder="Rev 1.0" class="w-16 px-1.5 py-0.5 border border-slate-200 rounded text-center text-slate-700 font-mono"></span>
                                </template>
                            </div>
                            <input type="text" 
                                   name="document_number" 
                                   placeholder="e.g. SR/TWR-A/ABP/2025/001"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs">
                        </div>

                        {{-- ROW 4 - LEFT: Issue Date --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-700 block">Issue Date</label>
                            <input type="date" 
                                   name="issue_date"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs cursor-pointer">
                        </div>

                        {{-- ROW 4 - RIGHT: Expiry Date (If Applicable) --}}
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-700 block">Expiry Date (If Applicable)</label>
                            <input type="date" 
                                   name="expiry_date"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition shadow-2xs cursor-pointer">
                        </div>

                        {{-- ROW 5 - FULL WIDTH: Description / Remarks --}}
                        <div class="space-y-1.5 col-span-1 sm:col-span-2">
                            <label class="text-[11px] font-bold text-slate-700 block">
                                Description / Remarks
                            </label>
                            <textarea name="description" 
                                      rows="2" 
                                      placeholder="e.g. Municipality approved building plan for Tower A."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 hover:border-[#a38c29]/60 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition resize-none shadow-2xs"></textarea>
                        </div>

                        {{-- ROW 6 - FULL WIDTH: Upload File & Preview (Reduced Height Centered Dropzone) --}}
                        <div class="space-y-1.5 col-span-1 sm:col-span-2">
                            <label class="text-[11px] font-bold text-slate-700 block">
                                Upload File <span class="text-rose-500">*</span>
                            </label>
                            
                            {{-- Drop Zone (Centered Design with Reduced Height) --}}
                            <div class="relative w-full border-2 border-dashed border-slate-300 hover:border-[#a38c29] rounded-2xl bg-slate-50/70 hover:bg-slate-50/90 transition-all py-3 px-4 flex flex-col items-center justify-center text-center group cursor-pointer">
                                <input type="file" 
                                       id="dmsFileInput"
                                       name="file" 
                                       required 
                                       @change="handleFileSelect"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                {{-- Cloud Icon --}}
                                <div class="w-8 h-8 rounded-full bg-[#a38c29]/10 text-[#a38c29] flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                    </svg>
                                </div>
                                
                                <p class="text-xs font-semibold text-slate-700">
                                    Drag & Drop file here or
                                </p>
                                <button type="button" class="mt-1.5 px-3.5 py-1 bg-white border border-slate-300 group-hover:border-[#a38c29] text-slate-700 rounded-lg text-xs font-bold shadow-2xs pointer-events-none">
                                    Choose File
                                </button>
                            </div>

                            {{-- Selected File Pill / Card --}}
                            <div x-show="fileName" 
                                 x-transition 
                                 class="flex items-center justify-between p-2.5 bg-white border border-slate-200 rounded-xl shadow-sm" 
                                 style="display: none;">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 font-bold text-[9px] uppercase">
                                        <span x-text="fileExt ? fileExt.substring(0,4) : 'PDF'"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="fileName"></p>
                                        <p class="text-[9px] text-slate-400 font-medium" x-text="fileSizeFormatted || 'Document file'"></p>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="clearFile()" 
                                        class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition cursor-pointer" 
                                        title="Remove file">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            {{-- Allowed Formats and Size Help Text --}}
                            <p class="text-[10px] text-slate-400 font-medium">
                                Allowed file types: PDF, DOCX, JPG, PNG, DWG, ZIP (Max 100MB)
                            </p>
                        </div>
                    </div>

                    {{-- Footer Action Buttons (Pure White Background) --}}
                    <div class="px-6 pb-6 pt-2 bg-white flex items-center justify-end gap-3 shrink-0">
                        <button type="button" 
                                @click="showUploadModal = false" 
                                class="px-5 py-2.5 bg-white border border-slate-250 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition shadow-2xs cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2.5 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md hover:shadow-lg transition cursor-pointer">
                            Upload
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </template>

    {{-- Preview Modal (Teleported to Body) --}}
    <template x-teleport="body">
        <div x-show="showPreviewModal" 
             class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs"
             x-transition.opacity 
             style="display: none;">
            
            <div @click.outside="closePreview()" 
                 :class="(previewIsPdf || previewIsImage) ? 'max-w-3xl h-[80vh]' : 'max-w-md h-auto'"
                 class="bg-white w-full rounded-2xl shadow-2xl overflow-hidden flex flex-col animate-fade-in-up transition-all duration-300">
                
                <div class="px-6 py-5 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-900 flex items-center justify-between text-white relative overflow-hidden shrink-0">
                    <div class="absolute -top-10 -right-10 w-36 h-36 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10 min-w-0 pr-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#a38c29]/25 text-[#d9bf3b] text-[9px] font-extrabold uppercase tracking-widest">Document Preview</span>
                        </div>
                        <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1 truncate max-w-sm" x-text="previewTitle"></h2>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 relative z-10">
                        <button type="button" 
                                x-show="previewIsPdf || previewIsImage"
                                @click="printDocument()" 
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-[11px] font-bold uppercase tracking-wider transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-[#d9bf3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Print</span>
                        </button>

                        <button type="button" 
                                @click="closePreview()" 
                                class="text-slate-400 hover:text-white transition w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs font-bold shrink-0 cursor-pointer">
                            ✕
                        </button>
                    </div>
                </div>

                <div :class="(previewIsPdf || previewIsImage) ? 'flex-1 bg-slate-100 p-3 sm:p-4 overflow-auto flex items-center justify-center' : 'p-6 bg-white flex items-center justify-center text-center'">
                    {{-- If PDF --}}
                    <div x-show="previewIsPdf" class="w-full h-full flex items-center justify-center" style="display: none;">
                        <iframe id="dmsPreviewIframe" :src="previewIsPdf ? previewUrl : ''" class="w-full h-full rounded-xl bg-white shadow-inner"></iframe>
                    </div>

                    {{-- If Image --}}
                    <div x-show="previewIsImage" class="flex items-center justify-center h-full w-full p-2" style="display: none;">
                        <img :src="previewIsImage ? previewUrl : ''" alt="Document Preview" class="max-h-full max-w-full object-contain rounded-xl shadow-lg bg-white">
                    </div>

                    {{-- If Non-PDF / Non-Image --}}
                    <div x-show="!previewIsPdf && !previewIsImage" class="space-y-4 max-w-xs mx-auto py-2">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-[#a38c29] flex items-center justify-center mx-auto shadow-2xs">
                            <svg class="w-7 h-7 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-1" x-text="previewTitle"></h4>
                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">This file format is best viewed in native applications. Click below to download directly.</p>
                        </div>
                        <a :href="previewDownloadUrl" class="inline-flex items-center justify-center gap-2 w-full px-5 py-2.5 bg-[#a38c29] hover:bg-[#8e7a23] text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-md cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>DOWNLOAD FILE NOW</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="deleteModal.open" 
             class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs" 
             x-transition.opacity 
             style="display: none;">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 sm:p-8 text-center transform transition-all animate-fade-in-up" 
                 @click.outside="deleteModal.open = false">
                <div class="w-14 h-14 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-4 text-rose-600 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider mb-2">DELETE DOCUMENT</h3>
                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    You are about to delete <span class="font-bold text-rose-600" x-text="deleteModal.docTitle"></span>. This action cannot be undone.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button type="button" @click="deleteModal.open = false" 
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider rounded-xl transition cursor-pointer">
                        CANCEL
                    </button>
                    <form :action="deleteModal.deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md shadow-rose-600/20 flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>YES, DELETE NOW</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="archiveModal.open" 
             class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs" 
             x-transition.opacity 
             style="display: none;">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 sm:p-8 text-center transform transition-all animate-fade-in-up" 
                 @click.outside="archiveModal.open = false">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner"
                     :class="archiveModal.actionType === 'archive' ? 'bg-teal-50 text-teal-600' : 'bg-emerald-50 text-emerald-600'">
                    <template x-if="archiveModal.actionType === 'archive'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    </template>
                    <template x-if="archiveModal.actionType === 'unarchive'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </template>
                </div>
                <h3 class="text-base font-black text-slate-900 uppercase tracking-wider mb-2" 
                    x-text="archiveModal.actionType === 'archive' ? 'ARCHIVE DOCUMENT' : 'RESTORE DOCUMENT'"></h3>
                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    <span x-text="archiveModal.actionType === 'archive' ? 'You are about to archive ' : 'You are about to restore to active records '"></span>
                    <span class="font-bold text-slate-900" x-text="archiveModal.docTitle"></span>.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button type="button" @click="archiveModal.open = false" 
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider rounded-xl transition cursor-pointer">
                        CANCEL
                    </button>
                    <form :action="archiveModal.actionUrl" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-5 py-2.5 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer"
                                :class="archiveModal.actionType === 'archive' ? 'bg-teal-600 hover:bg-teal-700 shadow-teal-600/20' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20'">
                            <span x-text="archiveModal.actionType === 'archive' ? 'YES, ARCHIVE NOW' : 'YES, RESTORE NOW'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
    function dmsExplorerApp() {
        const categoriesMap = @json($categoriesInfo);
        const projectsList = @json($projects ?? []);
        const employeesList = @json($employees ?? []);
        const contractorsList = @json($contractors ?? []);
        const partnersList = @json($partners ?? []);
        const allUnits = @json($units ?? []);

        const categoriesList = [];
        const categoryLabels = {};
        const typesMap = {};
        for (const code in categoriesMap) {
            categoriesList.push({ code: code, label: categoriesMap[code].label });
            categoryLabels[code] = categoriesMap[code].label;
            typesMap[code] = categoriesMap[code].types || [];
        }

        const projectLabels = {};
        projectsList.forEach(p => { projectLabels[p.id] = p.name; });

        const employeeLabels = {};
        employeesList.forEach(e => { employeeLabels[e.id] = e.name + (e.employee_id ? ` (${e.employee_id})` : ''); });

        const contractorLabels = {};
        contractorsList.forEach(c => { contractorLabels[c.id] = c.name + (c.phone ? ` (${c.phone})` : ''); });

        const partnerLabels = {};
        partnersList.forEach(p => { partnerLabels[p.id] = p.name; });

        const defaultProjId = '{{ $selectedProject ?? ($projects->first()?->id ?? '') }}';

        return {
            showUploadModal: false,
            selectedCategory: '{{ $selectedCategory ?? '' }}',
            uploadCategory: '{{ $selectedCategory ?: 'project' }}',
            uploadDocType: '',
            uploadProjectId: defaultProjId,
            defaultProjectId: defaultProjId,
            uploadUnit: '',
            uploadEmployeeId: '{{ $employees->first()?->id ?? '' }}',
            uploadContractorId: '{{ $contractors->first()?->id ?? '' }}',
            uploadPartnerId: '{{ $partners->first()?->id ?? '' }}',
            fileName: '',
            fileSizeFormatted: '',
            fileExt: '',

            // Preview modal state
            showPreviewModal: false,
            previewUrl: '',
            previewTitle: '',
            previewMime: '',
            previewDownloadUrl: '',
            previewIsPdf: false,
            previewIsImage: false,

            // Delete modal state
            deleteModal: {
                open: false,
                docId: null,
                docTitle: '',
                deleteUrl: ''
            },
            confirmDelete(id, title) {
                this.deleteModal.docId = id;
                this.deleteModal.docTitle = title;
                this.deleteModal.deleteUrl = '{{ url('/dms') }}/' + id + '/delete';
                this.deleteModal.open = true;
            },

            // Archive / Unarchive modal state
            archiveModal: {
                open: false,
                docId: null,
                docTitle: '',
                actionType: 'archive',
                actionUrl: ''
            },
            confirmArchive(id, title, actionType) {
                this.archiveModal.docId = id;
                this.archiveModal.docTitle = title;
                this.archiveModal.actionType = actionType;
                this.archiveModal.actionUrl = actionType === 'archive' 
                    ? '{{ url('/dms') }}/' + id + '/archive' 
                    : '{{ url('/dms') }}/' + id + '/unarchive';
                this.archiveModal.open = true;
            },

            categoriesList: categoriesList,
            categoryLabels: categoryLabels,
            projectsList: projectsList,
            projectLabels: projectLabels,
            employeesList: employeesList,
            employeeLabels: employeeLabels,
            contractorsList: contractorsList,
            contractorLabels: contractorLabels,
            partnersList: partnersList,
            partnerLabels: partnerLabels,
            categoryTypes: typesMap,
            allUnits: allUnits,

            init() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('upload') === '1' || urlParams.get('action') === 'upload') {
                    this.$nextTick(() => {
                        this.openUploadModal('{{ $selectedCategory ?: 'project' }}');
                    });
                }
            },

            selectCategory(code) {
                this.uploadCategory = code;
                this.uploadDocType = '';
            },

            filteredCategories(query) {
                if (!query) return this.categoriesList;
                const q = query.toLowerCase().trim();
                return this.categoriesList.filter(c => c.label.toLowerCase().includes(q));
            },

            get currentDocTypes() {
                return this.categoryTypes[this.uploadCategory] || [];
            },

            filteredDocTypes(query) {
                const types = this.currentDocTypes;
                if (!query) return types;
                const q = query.toLowerCase().trim();
                return types.filter(t => t.toLowerCase().includes(q));
            },

            filteredProjects(query) {
                if (!query) return this.projectsList;
                const q = query.toLowerCase().trim();
                return this.projectsList.filter(p => p.name.toLowerCase().includes(q));
            },

            get currentProjectUnits() {
                if (!this.uploadProjectId) return [];
                return this.allUnits.filter(u => String(u.project_id) === String(this.uploadProjectId));
            },

            filteredUnits(query) {
                const units = this.currentProjectUnits;
                if (!query) return units;
                const q = query.toLowerCase().trim();
                return units.filter(u => (u.door_no || '').toLowerCase().includes(q));
            },

            filteredEmployees(query) {
                if (!query) return this.employeesList;
                const q = query.toLowerCase().trim();
                return this.employeesList.filter(e => 
                    (e.name || '').toLowerCase().includes(q) || 
                    (e.employee_id || '').toLowerCase().includes(q)
                );
            },

            filteredContractors(query) {
                if (!query) return this.contractorsList;
                const q = query.toLowerCase().trim();
                return this.contractorsList.filter(c => 
                    (c.name || '').toLowerCase().includes(q) ||
                    (c.phone || '').toLowerCase().includes(q) ||
                    (c.email || '').toLowerCase().includes(q) ||
                    (c.type || '').toLowerCase().includes(q)
                );
            },

            filteredPartners(query) {
                if (!query) return this.partnersList;
                const q = query.toLowerCase().trim();
                return this.partnersList.filter(p => (p.name || '').toLowerCase().includes(q));
            },

            openUploadModal(defaultCategory = 'project') {
                this.uploadCategory = defaultCategory || 'project';
                this.uploadDocType = '';
                this.uploadProjectId = this.defaultProjectId;
                this.uploadUnit = '';
                this.fileName = '';
                this.fileSizeFormatted = '';
                this.fileExt = '';
                const fileInput = document.getElementById('dmsFileInput');
                if (fileInput) fileInput.value = '';
                this.showUploadModal = true;
            },

            closeUploadModal() {
                this.showUploadModal = false;
            },

            handleFileSelect(e) {
                if (e.target.files && e.target.files.length) {
                    const file = e.target.files[0];
                    this.fileName = file.name;
                    this.fileSizeFormatted = this.formatFileSize(file.size);
                    const ext = file.name.split('.').pop().toLowerCase();
                    this.fileExt = ext;
                }
            },

            clearFile() {
                this.fileName = '';
                this.fileSizeFormatted = '';
                this.fileExt = '';
                const fileInput = document.getElementById('dmsFileInput');
                if (fileInput) fileInput.value = '';
            },

            formatFileSize(bytes) {
                if (!bytes || bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            // Document Preview & Print Handlers
            openPreview(url, title, mime, downloadUrl) {
                this.previewUrl = url;
                this.previewTitle = title;
                this.previewMime = mime || 'Document';
                this.previewDownloadUrl = downloadUrl;

                const m = (mime || '').toLowerCase();
                const u = url.toLowerCase();
                const t = (title || '').toLowerCase();

                this.previewIsPdf = m.includes('pdf') || u.endsWith('.pdf') || t.endsWith('.pdf');
                this.previewIsImage = m.includes('image') || u.endsWith('.png') || u.endsWith('.jpg') || u.endsWith('.jpeg') || u.endsWith('.webp') || u.endsWith('.svg') || u.endsWith('.gif') || t.endsWith('.png') || t.endsWith('.jpg') || t.endsWith('.jpeg') || t.endsWith('.webp');

                this.showPreviewModal = true;
            },

            closePreview() {
                this.showPreviewModal = false;
                this.previewUrl = '';
            },

            printDocument() {
                const iframe = document.getElementById('dmsPreviewIframe');
                if (iframe && iframe.contentWindow) {
                    try {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        return;
                    } catch (e) {
                        console.warn('Iframe print error:', e);
                    }
                }
                if (this.previewUrl) {
                    const printWindow = window.open(this.previewUrl, '_blank');
                    if (printWindow) {
                        printWindow.focus();
                        printWindow.print();
                    }
                }
            }
        };
    }
</script>

</x-erp-layout>
