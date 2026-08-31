<x-erp-layout title="Document Repository" headerTitle="Document Management Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{ 
    showUploadModal: false,
    selectedCategory: '{{ $selectedCategory ?? '' }}',
    uploadCategory: 'project',
    uploadDocType: '',
    fileName: '',
    imagePreview: null,
    
    // Dynamic document types based on category
    categoryTypes: {
        project: [
            'Approved Building Plan', 'Municipality Approval', 'RERA Certificate', 
            'Fire NOC', 'Environmental Clearance', 'Occupancy Certificate', 
            'Completion Certificate', 'Site Layout Drawing', 'Master Plan', 'Other'
        ],
        legal: [
            'Land Deed', 'Encumbrance Certificate', 'Power of Attorney', 
            'Partnership Agreement', 'Legal Opinion', 'Court Document', 
            'Government Approval', 'Other'
        ],
        company: [
            'GST Registration', 'PAN Card', 'Trade License', 
            'Company Incorporation Certificate', 'ISO Certificate', 
            'Insurance Policy', 'Audit Report', 'Other'
        ],
        hr: [
            'Appointment Letter', 'Employment Contract', 'NDA Agreement', 
            'Policy Document', 'Employee ID Proof', 'Experience Certificate', 'Other'
        ],
        partner: [
            'Investment Agreement', 'MOU Document', 'Share Allocation Document', 
            'Settlement Agreement', 'Board Resolution', 'Other'
        ],
        drawings: [
            'Architectural Drawing', 'Structural Drawing', 'Electrical Layout', 
            'Plumbing Layout', 'BOQ Document', 'Engineering Report', 'Other'
        ],
        templates: [
            'Sale Agreement Template', 'Possession Letter Template', 
            'Cancellation Letter Template', 'Demand Letter Template', 
            'Payment Reminder Template', 'Other'
        ]
    },

    getDocTypesForUpload() {
        return this.categoryTypes[this.uploadCategory] || [];
    },

    handleFileSelect(e) {
        if(e.target.files.length > 0) {
            this.fileName = e.target.files[0].name;
        }
    }
}">

    {{-- Top Action Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">
                Document Repository
            </h1>
            <p class="text-xs text-slate-500 mt-1">Centralized, secure document vault for project approvals, legal deeds, company records, and templates.</p>
        </div>

        <button @click="showUploadModal = true; uploadCategory = 'project'; uploadDocType = ''; fileName = '';" 
                class="btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-xl text-xs font-bold transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload Document
        </button>
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

    {{-- Repository Categorization Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
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
                        'bg' => 'bg-amber-50', 
                        'text' => 'text-amber-600', 
                        'border' => 'border-amber-200',
                        'border-l' => 'border-l-amber-500',
                        'hover-shadow' => 'hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.25)]',
                        'hover-border' => 'hover:border-r-amber-500/20 hover:border-y-amber-500/20',
                        'hover-icon' => 'group-hover:bg-amber-600 group-hover:text-white',
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
                    'project' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'legal' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                    'company' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'hr' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                    'partner' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                    'drawings' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                    'templates' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>'
                ][$catKey] ?? '';
            @endphp            <a href="{{ route('dms.index', array_merge(request()->except(['category', 'page']), $isSelected ? [] : ['category' => $catKey])) }}" 
               class="bg-white rounded-2xl border border-l-[6px] {{ $colors['border-l'] }} border-y-slate-200/80 border-r-slate-200/80 transition-all duration-300 py-6 px-3 flex flex-col items-center justify-center text-center relative overflow-hidden group shadow-xs hover:-translate-y-1.5 {{ $colors['hover-shadow'] }} {{ $colors['hover-border'] }} h-full min-h-[145px] {{ $isSelected ? 'ring-2 ring-[#a38c29]/25 bg-slate-50/10' : '' }}">
                
                <div class="w-11 h-11 rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} flex items-center justify-center shrink-0 mb-3.5 transition-all duration-300 {{ $colors['hover-icon'] }} group-hover:shadow-md group-hover:scale-110">
                    {!! $iconSvg !!}
                </div>
                
                <div class="flex flex-col justify-between">
                    <h4 class="text-[10px] font-extrabold text-slate-800 uppercase tracking-wider leading-normal px-1 mb-1.5 min-h-[32px] flex items-center justify-center transition-colors group-hover:text-slate-900">{{ $info['label'] }}</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide transition-colors group-hover:text-slate-500">{{ $docCount }} Documents</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- File Browser & Sidebar Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- Left: Repository Filter & Table --}}
        <div class="lg:col-span-2 space-y-4">
            
            {{-- Filters Bar --}}
            <form method="GET" action="{{ route('dms.index') }}" class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex flex-col sm:flex-row sm:items-end gap-3.5">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                {{-- Category filter if not selected --}}
                @if(!$selectedCategory)
                    <div class="space-y-1 w-full sm:w-48">
                        <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block font-extrabold">Category</label>
                        <select name="category" onchange="this.form.submit()" class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach($categoriesInfo as $k => $info)
                                <option value="{{ $k }}" @selected(request('category') === $k)>{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Document Type --}}
                <div class="space-y-1 w-full sm:flex-1">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block font-extrabold">Document Type</label>
                    <select name="document_type" onchange="this.form.submit()" class="w-full px-2.5 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 cursor-pointer">
                        <option value="">All Types</option>
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

                {{-- Search --}}
                <div class="space-y-1 w-full sm:flex-1">
                    <label class="text-[9px] font-bold text-slate-500 uppercase tracking-wide block font-extrabold">Search</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name/number..." 
                               class="w-full pl-3 pr-8 py-1.5 text-[11px] border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#a38c29]/40 focus:border-[#a38c29] outline-none transition bg-white font-semibold text-slate-700 placeholder-slate-400">
                        @if(request('search') || request('document_type') || request('category'))
                            <a href="{{ route('dms.index', request('category') ? ['category' => request('category')] : []) }}" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 transition" title="Clear Filters">✕</a>
                        @endif
                    </div>
                </div>

                {{-- Reset Filters Button --}}
                <div class="w-full sm:w-auto shrink-0">
                    <a href="{{ route('dms.index', request('category') ? ['category' => request('category')] : []) }}" class="inline-flex items-center justify-center px-4 py-1.5 bg-[#a38c29] hover:bg-[#8a7522] text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition shadow-sm text-center w-full leading-normal whitespace-nowrap">
                        Reset Filters
                    </a>
                </div>
            </form>

            {{-- Table List Explorer Card --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                
                {{-- Header/Title of block --}}
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Document Explorer</h2>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">
                        Showing {{ $documents->total() }} Documents
                    </span>
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
                            <th class="px-5 py-3">Uploaded By</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-150 text-xs">
                        @forelse($documents as $doc)
                            @php
                                // Mime Type Visuals
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
                                }

                                // Status Badge
                                $statusLabel = 'Active';
                                $statusBadge = 'bg-emerald-50 text-emerald-800 border-emerald-100';
                                if ($doc->expiry_date) {
                                    if ($doc->expiry_date->isPast()) {
                                        $statusLabel = 'Expired';
                                        $statusBadge = 'bg-rose-50 text-rose-800 border-rose-100';
                                    } elseif ($doc->expiry_date->diffInDays(now()) <= 30) {
                                        $statusLabel = 'Expiring Soon';
                                        $statusBadge = 'bg-amber-50 text-amber-800 border-amber-100';
                                    }
                                }

                                // Resolve Display reference name
                                $refName = 'System / Company';
                                if ($doc->documentable) {
                                    if ($doc->documentable_type === Project::class) {
                                        $refName = $doc->documentable->name;
                                    } elseif ($doc->documentable_type === Employee::class) {
                                        $refName = $doc->documentable->name;
                                    } elseif ($doc->documentable_type === Payee::class) {
                                        $refName = $doc->documentable->name;
                                    } elseif ($doc->documentable_type === Customer::class) {
                                        $refName = $doc->documentable->name;
                                    } elseif ($doc->documentable_type === Unit::class) {
                                        $refName = $doc->documentable->formatted_name;
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg border flex items-center justify-center font-bold text-[10px] {{ $iconBg }} shrink-0 shadow-2xs">
                                            {{ strtoupper($fileExt) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-bold text-slate-800 tracking-tight leading-snug truncate" title="{{ $doc->title }}">{{ $doc->title }}</h4>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $doc->formatted_size }}</p>
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
                                        <span class="text-[9px] text-[#a38c29] font-bold uppercase tracking-wide block mt-0.5">Tower: {{ $doc->tower }}</span>
                                    @elseif($doc->department)
                                        <span class="text-[9px] text-purple-600 font-bold uppercase tracking-wide block mt-0.5">Dept: {{ $doc->department }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-semibold text-slate-700 whitespace-nowrap">{{ $doc->document_type }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-600 font-mono text-[10px]">
                                        {{ $doc->issue_date ? $doc->issue_date->format('d-M-Y') : 'N/A' }}
                                    </div>
                                    <div class="font-semibold text-rose-600 font-mono text-[10px] mt-0.5">
                                        {{ $doc->expiry_date ? $doc->expiry_date->format('d-M-Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-slate-700">{{ $doc->uploader->name ?? 'System' }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $doc->created_at->format('d-M-Y') }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="badge border px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase whitespace-nowrap {{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('dms.download', $doc->id) }}" 
                                           class="p-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-lg hover:text-slate-800 transition" 
                                           title="Download Document">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>
                                        
                                        <form action="{{ route('dms.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 bg-slate-50 hover:bg-rose-50 border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-250 rounded-lg transition" 
                                                    title="Delete Document">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center bg-slate-50/20">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 shadow-2xs">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
                    <a href="{{ route('dms.index') }}" class="text-[9px] font-extrabold text-[#a38c29] uppercase tracking-wide hover:underline">View All</a>
                </div>
                <div class="p-4.5 space-y-3.5">
                    @forelse($expiringSoon as $expDoc)
                        @php
                            $daysLeft = max(now()->diffInDays($expDoc->expiry_date, false), 0);
                            $alertColor = 'text-amber-600 bg-amber-50 border-amber-100';
                            if ($daysLeft <= 15) {
                                $alertColor = 'text-rose-600 bg-rose-50 border-rose-100';
                            }
                            $expRefName = 'System / Company';
                            if ($expDoc->referenceProject) {
                                $expRefName = $expDoc->referenceProject->name;
                            } elseif ($expDoc->documentable && $expDoc->documentable_type === Project::class) {
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

            {{-- Card 2: Recent Uploads --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Recent Uploads</h3>
                    <a href="{{ route('dms.index') }}" class="text-[9px] font-extrabold text-[#a38c29] uppercase tracking-wide hover:underline">View All</a>
                </div>
                <div class="p-4.5 space-y-3">
                    @forelse($recentUploads as $recent)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-150 text-slate-400 flex items-center justify-center shrink-0 font-bold text-[9px]">
                                {{ strtoupper(pathinfo($recent->file_name, PATHINFO_EXTENSION)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('dms.download', $recent->id) }}" class="text-xs font-bold text-slate-700 hover:text-[#a38c29] transition truncate block">{{ $recent->title }}</a>
                                <p class="text-[9px] text-slate-450 font-semibold mt-0.5">{{ $recent->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400">
                            <p class="text-[10px] font-bold uppercase tracking-wider">No Recent Uploads</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Card 3: Storage Overview --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Storage Overview</h3>
                </div>
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                        {{-- Circular Progress SVG --}}
                        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-[#a38c29]" stroke-dasharray="{{ $storageStats['used_percent'] }}, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="text-center">
                            <span class="text-sm font-black text-slate-800 font-mono">{{ $storageStats['used_percent'] }}%</span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Used</span>
                        </div>
                    </div>

                    <div class="flex-1 space-y-1.5 text-right">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Total Space</span>
                            <span class="text-xs font-bold text-slate-750 font-mono">{{ $storageStats['limit'] }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Used Space</span>
                            <span class="text-xs font-bold text-slate-750 font-mono">{{ $storageStats['used'] }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Available Space</span>
                            <span class="text-xs font-semibold text-slate-500 font-mono">{{ $storageStats['available'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- File Upload Modal --}}
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition.opacity 
         style="display: none;">
        
        <div @click.outside="showUploadModal = false" 
             class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-fade-in-up"
             x-transition.scale>
            
            {{-- Modal Header --}}
            <div class="px-6 py-6 bg-slate-900 border-b border-[#a38c29]/10 flex items-center justify-between text-white relative overflow-hidden shrink-0">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Central Repository Vault</span>
                    <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Index & Secure Document</h2>
                </div>
                <button @click="showUploadModal = false" class="text-slate-400 hover:text-white transition w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-xs">✕</button>
            </div>

            {{-- Modal Body Form --}}
            <form action="{{ route('dms.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto flex-1 p-6 space-y-4 text-left font-sans text-xs">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    {{-- Category selector --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                            Repository Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" 
                                x-model="uploadCategory"
                                required 
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                            @foreach($categoriesInfo as $k => $info)
                                <option value="{{ $k }}">{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Document Subtype (Standard Documents list) --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                            Document Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="document_type" 
                                x-model="uploadDocType"
                                required 
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                            <option value="">-- Select type --</option>
                            <template x-for="type in getDocTypesForUpload()" :key="type">
                                <option :value="type" x-text="type"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Conditional Reference Selectors depending on selected category --}}
                    
                    {{-- 1. Project Selector (used in project, drawings, legal, partner) --}}
                    <template x-if="['project', 'drawings', 'legal', 'partner'].includes(uploadCategory)">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                                Associated Project <span x-show="['project', 'drawings'].includes(uploadCategory)" class="text-rose-500">*</span>
                            </label>
                            <select name="project_id" 
                                    :required="['project', 'drawings'].includes(uploadCategory)"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                                <option value="">-- Choose project --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    {{-- 2. Employee Selector (used in hr) --}}
                    <template x-if="uploadCategory === 'hr'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                                Employee / Staff <span class="text-rose-500">*</span>
                            </label>
                            <select name="employee_id" 
                                    :required="uploadCategory === 'hr'"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                                <option value="">-- Choose employee --</option>
                                @foreach($employees as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->employee_id }})</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    {{-- 3. Partner / Investor Selector (used in partner) --}}
                    <template x-if="uploadCategory === 'partner'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                                Partner / Investor <span class="text-rose-500">*</span>
                            </label>
                            <select name="partner_id" 
                                    :required="uploadCategory === 'partner'"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                                <option value="">-- Choose partner/investor --</option>
                                @foreach($partners as $part)
                                    <option value="{{ $part->id }}">{{ $part->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    {{-- Specific Category Metadata Inputs --}}
                    
                    {{-- Tower (Project category) --}}
                    <template x-if="uploadCategory === 'project'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Tower / Block Name (Optional)</label>
                            <input type="text" name="tower" placeholder="e.g. Tower A, Block B"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm">
                        </div>
                    </template>

                    {{-- Legal Category (Legal category) --}}
                    <template x-if="uploadCategory === 'legal'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Legal Category</label>
                            <input type="text" name="legal_category" placeholder="e.g. Land Title Records"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm">
                        </div>
                    </template>

                    {{-- Doc Number (Legal category) --}}
                    <template x-if="uploadCategory === 'legal'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Document Number</label>
                            <input type="text" name="document_number" placeholder="e.g. Deed No: 2315/2026"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm">
                        </div>
                    </template>

                    {{-- Department (HR category) --}}
                    <template x-if="uploadCategory === 'hr'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">HR Department</label>
                            <select name="department" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                                <option value="">-- Choose department --</option>
                                @foreach(['Management', 'HR', 'Operations', 'Accounts & Finance', 'Sales & Marketing', 'Projects', 'Legal', 'General'] as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    {{-- Drawing Category (Drawings category) --}}
                    <template x-if="uploadCategory === 'drawings'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Drawing Category</label>
                            <select name="drawing_type" class="w-full px-3 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition cursor-pointer">
                                <option value="">-- Select category --</option>
                                @foreach(['Architectural', 'Structural', 'Electrical', 'Plumbing', 'HVAC Layout', 'Engineering Reports', 'Other'] as $draw)
                                    <option value="{{ $draw }}">{{ $draw }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    {{-- Revision Number (Drawings category) --}}
                    <template x-if="uploadCategory === 'drawings'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Revision Number</label>
                            <input type="text" name="revision_number" placeholder="e.g. Rev 1.2"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm">
                        </div>
                    </template>

                    {{-- Template Category (Templates category) --}}
                    <template x-if="uploadCategory === 'templates'">
                        <div class="space-y-1.5 col-span-1">
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Template Category</label>
                            <input type="text" name="template_category" placeholder="e.g. Sales, Legal, Operations"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm">
                        </div>
                    </template>

                    {{-- Global Metadata: Title --}}
                    <div class="space-y-1.5 col-span-1 sm:col-span-2">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                            Document Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               required 
                               placeholder="e.g. Approved Building Blueprint - Tower 1"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm">
                    </div>

                    {{-- Dates --}}
                    <div class="space-y-1.5 col-span-1">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Issue Date</label>
                        <input type="date" name="issue_date"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm cursor-pointer">
                    </div>
                    <div class="space-y-1.5 col-span-1">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Expiry Date</label>
                        <input type="date" name="expiry_date"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition shadow-sm cursor-pointer">
                    </div>

                    {{-- Description --}}
                    <div class="space-y-1.5 col-span-1 sm:col-span-2">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">
                            Description / Remarks
                        </label>
                        <textarea name="description" 
                                  rows="3" 
                                  placeholder="Add any extra notes or remarks here..."
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-250 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] transition resize-none shadow-sm"></textarea>
                    </div>

                    {{-- File Input --}}
                    <div class="space-y-1.5 col-span-1 sm:col-span-2">
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block font-bold">
                            Choose Document File <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative w-full border-2 border-dashed border-slate-250 rounded-xl bg-slate-50/50 hover:bg-slate-50 transition cursor-pointer flex flex-col items-center justify-center p-6 shadow-2xs">
                            <input type="file" 
                                   name="file" 
                                   required 
                                   @change="handleFileSelect"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-xs font-bold text-slate-600" x-text="fileName ? fileName : 'Drag & drop file or click to browse'"></p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Accepts PDF, JPG, PNG, DOCX, XLSX, ZIP (Max 15MB)</p>
                        </div>
                    </div>
                </div>

                {{-- Footer Controls --}}
                <div class="px-6 py-4 border-t border-slate-250 flex items-center justify-end gap-2 bg-slate-50 -mx-6 -mb-6 mt-6 shrink-0">
                    <button type="button" @click="showUploadModal = false" class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold shadow-md uppercase transition tracking-wider">
                        Index & Secure
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

</x-erp-layout>
