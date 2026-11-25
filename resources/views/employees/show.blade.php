<x-erp-layout title="Employee Details">
    @php
        $initials = collect(explode(' ', trim($employee->name)))
            ->filter()
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->implode('') ?: 'EM';
        $photoUrl = $employee->photo_path ? asset('storage/' . $employee->photo_path) : null;
        $tabs = [
            'personal' => 'Personal',
            'employment' => 'Employment',
            'salary' => 'Salary Details',
            'payroll' => 'Payroll',
            'documents' => 'Documents',
            'assets' => 'Assets',
            'emergency' => 'Emergency',
        ];
    @endphp

    <style>
        .emp-shell {
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            font-size: 0.75rem;
        }
        @media (min-width: 1024px) {
            .emp-shell {
                flex-direction: row;
                align-items: stretch;
            }
        }
        .emp-sidebar {
            width: 100%;
            flex-shrink: 0;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }
        @media (min-width: 1024px) {
            .emp-sidebar {
                width: 275px;
                border-bottom: 0;
                border-right: 1px solid #e2e8f0;
            }
        }
        .emp-banner {
            background: var(--erp-table-header-bg, #534E47);
            height: 96px;
            border-bottom-left-radius: 50% 34px;
            border-bottom-right-radius: 50% 34px;
            position: relative;
        }
        .emp-avatar-wrap {
            width: 96px;
            height: 96px;
            margin: -52px auto 0 auto;
            position: relative;
            z-index: 2;
        }
        .emp-avatar-box {
            width: 96px;
            height: 96px;
            border-radius: 18px;
            border: 4px solid #ffffff;
            background: #f6f4ee;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.14);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .emp-photo-btn {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 28px;
            height: 28px;
            border-radius: 9999px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #534E47;
            cursor: pointer;
        }
        .emp-main {
            flex: 1 1 0%;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }
        .emp-tabs-wrap {
            padding: 16px 20px 4px 20px;
            background: #f8fafc;
        }
        .emp-tabs {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            max-width: 100%;
            overflow-x: auto;
        }
        .emp-tab-btn {
            padding: 9px 14px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #475569;
            white-space: nowrap;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .emp-tab-btn:hover {
            color: #0f172a;
            background: rgba(241, 245, 249, 0.8);
        }
        .emp-tab-btn.is-active {
            background: linear-gradient(to right, #a38c29, #8a7522);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(163, 140, 41, 0.25);
        }
        .emp-tab-icon {
            width: 20px;
            height: 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(226, 232, 240, 0.65);
            color: #64748b;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .emp-tab-btn:hover .emp-tab-icon {
            background: rgba(163, 140, 41, 0.12);
            color: #a38c29;
        }
        .emp-tab-btn.is-active .emp-tab-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        .emp-sec-header {
            width: 100%;
            background: var(--erp-table-header-bg, #534E47);
            color: var(--erp-table-header-text, #ffffff);
            padding: 10px 16px;
            font-family: inherit;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }
        .emp-edit-btn,
        .emp-theme-btn {
            background: #a38c29;
            color: #ffffff;
            transition: background 0.15s ease;
        }
        .emp-edit-btn:hover,
        .emp-theme-btn:hover {
            background: #8a7522;
        }
        .emp-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.55);
            padding: 16px;
        }
    </style>

    <div x-data="{
        activeTab: '{{ array_key_exists($activeTab, $tabs) ? $activeTab : 'personal' }}',
        openPersonalModal: false,
        openExperienceModal: false,
        openDeleteModal: false,
        deleteTitle: 'DELETE ITEM',
        deleteItemName: '',
        deleteAction: '',
        openDeleteConfirm(actionUrl, title, itemName) {
            this.deleteAction = actionUrl;
            this.deleteTitle = title || 'DELETE ITEM';
            this.deleteItemName = itemName || 'this item';
            this.openDeleteModal = true;
        },
        secPersonal: true,
        secContact: true,
        secAddress: true,
        secExperience: true
    }" class="space-y-4">

        {{-- Flash Status --}}
        @if(session('status'))
            <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Top Header Bar --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Employee Details</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $employee->name }} - {{ $employee->employee_id }}</p>
                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-semibold mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-700">Home</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('employees.index') }}" class="hover:text-slate-700">Employees</a>
                    <span>&rsaquo;</span>
                    <span class="text-slate-600">{{ $employee->employee_id }}</span>
                </div>
            </div>

            <div>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-xs transition">
                    <span>&larr;</span>
                    <span>Back to Employees</span>
                </a>
            </div>
        </div>

        {{-- Main Two-Column Card Container --}}
        <div class="emp-shell">

            {{-- LEFT SIDEBAR: PROFILE CARD --}}
            <div class="emp-sidebar">
                <div class="emp-banner"></div>

                <div class="emp-avatar-wrap">
                    <div class="emp-avatar-box">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $employee->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span class="text-2xl font-black" style="color: #a38c29;">{{ $initials }}</span>
                        @endif
                    </div>
                    <form action="{{ route('employees.photo', $employee->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label title="Upload Photo" class="emp-photo-btn">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <input type="file" name="photo" accept="image/*" style="display: none;" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>

                <div class="pt-3 px-5 pb-5 text-center border-b border-slate-100">
                    <h2 class="text-sm font-extrabold text-slate-900">{{ $employee->name }}</h2>
                    <p class="text-[11px] font-mono text-slate-400 mt-0.5"># {{ $employee->employee_id }}</p>

                    <form action="{{ route('employees.status', $employee->id) }}" method="POST" class="mt-3">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-700 focus:outline-none cursor-pointer">
                            <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active (Active)</option>
                            <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ $employee->status === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ $employee->status === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </form>
                </div>

                {{-- JOB SECTION --}}
                <div class="px-5 py-4 border-b border-slate-100 space-y-2.5 text-xs">
                    <div class="flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>JOB</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">POSITION</p>
                        <p class="font-bold text-slate-800">{{ $employee->designation ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">DEPARTMENT</p>
                        <p class="font-bold text-slate-800">{{ $employee->department ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">BRANCH</p>
                        <p class="font-bold text-slate-800">{{ $employee->branch ?: 'Head Office' }}</p>
                    </div>
                </div>

                {{-- CONTACT SECTION --}}
                <div class="px-5 py-4 space-y-2.5 text-xs">
                    <div class="flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>CONTACT</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">WORK EMAIL</p>
                        <p class="font-bold text-slate-800 break-all">{{ $employee->work_email ?: ($employee->email ?: '—') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">PERSONAL EMAIL</p>
                        <p class="font-bold text-slate-800 break-all">{{ $employee->personal_email ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase text-slate-400">MOBILE PHONE</p>
                        <p class="font-bold text-slate-800">{{ $employee->phone ?: '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT CONTENT: TABS & PANELS --}}
            <div class="emp-main bg-slate-50">
                <div class="emp-tabs-wrap">
                    <div class="emp-tabs">
                        @php
                            $tabIcons = [
                                'personal' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                'employment' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                'salary' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                'payroll' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                'documents' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                                'assets' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                'emergency' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                            ];
                        @endphp
                        @foreach($tabs as $key => $label)
                            <button type="button"
                                    @click="activeTab = '{{ $key }}'"
                                    :class="activeTab === '{{ $key }}' ? 'is-active' : ''"
                                    class="emp-tab-btn group active:scale-95">
                                <span class="emp-tab-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabIcons[$key] ?? '' }}"/>
                                    </svg>
                                </span>
                                <span>{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="p-5 space-y-4 bg-slate-50 flex-1">

                    {{-- TAB 1: PERSONAL --}}
                    <div x-show="activeTab === 'personal'" class="space-y-4">
                        <div class="flex justify-end">
                            <button type="button" @click="openPersonalModal = true" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-xs transition cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Edit Personal Details</span>
                            </button>
                        </div>

                        {{-- Personal Information Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <button type="button" @click="secPersonal = !secPersonal" class="emp-sec-header">
                                <span>Personal Information</span>
                                <span x-text="secPersonal ? '▲' : '▼'" class="text-[10px]"></span>
                            </button>
                            <div x-show="secPersonal" class="p-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">FIRST NAME</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->first_name ?: $employee->name }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">LAST NAME</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->last_name ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">GENDER</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->gender ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">DATE OF BIRTH</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->date_of_birth ? $employee->date_of_birth->format('d M Y') : '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">NATIONALITY</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->nationality ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">BLOOD GROUP</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->blood_group ?: '—' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Information Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <button type="button" @click="secContact = !secContact" class="emp-sec-header">
                                <span>Contact Information</span>
                                <span x-text="secContact ? '▲' : '▼'" class="text-[10px]"></span>
                            </button>
                            <div x-show="secContact" class="p-4 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">PERSONAL EMAIL</p>
                                    <p class="font-bold text-slate-800 mt-0.5 break-all">{{ $employee->personal_email ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">MOBILE PHONE</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->phone ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">ALTERNATE PHONE</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->alternate_phone ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">WORK EMAIL</p>
                                    <p class="font-bold text-slate-800 mt-0.5 break-all">{{ $employee->work_email ?: ($employee->email ?: '—') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Address Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <button type="button" @click="secAddress = !secAddress" class="emp-sec-header">
                                <span>Address</span>
                                <span x-text="secAddress ? '▲' : '▼'" class="text-[10px]"></span>
                            </button>
                            <div x-show="secAddress" class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">ADDRESS LINE 1</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->address_line_1 ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">ADDRESS LINE 2</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->address_line_2 ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">COUNTRY</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->country ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">STATE / PROVINCE / REGION</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->state ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">CITY</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->city ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase text-slate-400">POSTAL CODE</p>
                                    <p class="font-bold text-slate-800 mt-0.5">{{ $employee->postal_code ?: '—' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Previous Experiences Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header">
                                <span @click="secExperience = !secExperience" class="cursor-pointer flex-1">Previous Experiences</span>
                                <button type="button" @click="openExperienceModal = true" class="px-2.5 py-1 rounded bg-white/20 hover:bg-white/30 text-[10px] font-bold uppercase cursor-pointer">+ Add Experience</button>
                            </div>
                            <div x-show="secExperience" class="p-4 text-xs">
                                @forelse($employee->experiences as $exp)
                                    <div class="flex items-start justify-between py-2.5 border-b last:border-b-0 border-slate-100">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $exp->job_title }} <span class="text-slate-400 font-normal">at</span> {{ $exp->company_name }}</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                {{ $exp->from_date ? $exp->from_date->format('M Y') : '—' }} &ndash; {{ $exp->to_date ? $exp->to_date->format('M Y') : 'Present' }}
                                            </p>
                                            @if($exp->description)
                                                <p class="text-slate-600 mt-1">{{ $exp->description }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('employees.experiences.destroy', [$employee->id, $exp->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 text-[11px] font-bold cursor-pointer">Remove</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-slate-500">No previous experiences recorded.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: EMPLOYMENT --}}
                    <div x-show="activeTab === 'employment'" style="display: none;" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                        <div class="emp-sec-header"><span>Employment &amp; Job Details</span></div>
                        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="p-5 space-y-4 text-xs">
                            @csrf
                            <input type="hidden" name="section" value="employment">
                            <input type="hidden" name="redirect_tab" value="employment">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Position / Designation *</label>
                                    <input type="text" name="designation" required value="{{ $employee->designation }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Department</label>
                                    <input type="text" name="department" value="{{ $employee->department }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Branch</label>
                                    <input type="text" name="branch" value="{{ $employee->branch }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Joining Date *</label>
                                    <input type="date" name="joining_date" required value="{{ $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Work Location</label>
                                    <input type="text" name="work_location" value="{{ $employee->work_location }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status *</label>
                                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                        <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="on_leave" {{ $employee->status === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="terminated" {{ $employee->status === 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" style="background:#a38c29;" class="px-5 py-2.5 rounded-xl text-white font-bold uppercase tracking-wider cursor-pointer">Save Employment Details</button>
                            </div>
                        </form>
                    </div>

                    {{-- TAB 3: ATTENDANCE --}}
                    <div x-show="activeTab === 'attendance'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Record Attendance</span></div>
                            <form action="{{ route('employees.attendances.store', $employee->id) }}" method="POST" class="p-4 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Date *</label>
                                    <input type="date" name="attendance_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Check In</label>
                                    <input type="time" name="check_in" value="09:30" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Check Out</label>
                                    <input type="time" name="check_out" value="18:30" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status *</label>
                                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                        <option value="Present">Present</option>
                                        <option value="Half Day">Half Day</option>
                                        <option value="Late">Late</option>
                                        <option value="Absent">Absent</option>
                                        <option value="On Leave">On Leave</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">+ Add Log</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Date</th>
                                        <th class="px-4 py-2.5">Check In</th>
                                        <th class="px-4 py-2.5">Check Out</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->attendances as $att)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">{{ $att->attendance_date->format('d M Y') }}</td>
                                            <td class="px-4 py-2.5 font-mono">{{ $att->check_in ?: '—' }}</td>
                                            <td class="px-4 py-2.5 font-mono">{{ $att->check_out ?: '—' }}</td>
                                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">{{ $att->status }}</span></td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('employees.attendances.destroy', [$employee->id, $att->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">No attendance logs recorded.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 4: SHIFTS --}}
                    <div x-show="activeTab === 'shifts'" style="display: none;" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                        <div class="emp-sec-header"><span>Shift Schedule Assignment</span></div>
                        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="p-5 space-y-4 text-xs">
                            @csrf
                            <input type="hidden" name="section" value="employment">
                            <input type="hidden" name="redirect_tab" value="shifts">
                            <input type="hidden" name="designation" value="{{ $employee->designation }}">
                            <input type="hidden" name="joining_date" value="{{ $employee->joining_date ? $employee->joining_date->format('Y-m-d') : date('Y-m-d') }}">
                            <input type="hidden" name="status" value="{{ $employee->status }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Assigned Shift</label>
                                    <select name="shift" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                        @foreach(['General Day Shift (09:30 AM - 06:30 PM)', 'General Day Shift', 'Morning Shift (07:00 AM - 04:00 PM)', 'Evening Shift (02:00 PM - 11:00 PM)', 'Night Shift (10:00 PM - 07:00 AM)', 'Site Rotational Shift'] as $sh)
                                            <option value="{{ $sh }}" {{ ($employee->shift ?? 'General Day Shift') === $sh ? 'selected' : '' }}>{{ $sh }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Work Location / Site</label>
                                    <input type="text" name="work_location" value="{{ $employee->work_location ?: $employee->branch }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" style="background:#a38c29;" class="px-5 py-2.5 rounded-xl text-white font-bold uppercase cursor-pointer">Update Shift</button>
                            </div>
                        </form>
                    </div>

                    {{-- TAB 5: OVERTIME --}}
                    <div x-show="activeTab === 'overtime'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Log Overtime</span></div>
                            <form action="{{ route('employees.overtimes.store', $employee->id) }}" method="POST" class="p-4 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Date *</label>
                                    <input type="date" name="overtime_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Hours *</label>
                                    <input type="number" step="0.5" min="0.5" name="hours" required value="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Amount (₹)</label>
                                    <input type="number" step="0.01" min="0" name="amount" placeholder="0.00" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status</label>
                                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">+ Add Overtime</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Date</th>
                                        <th class="px-4 py-2.5">Hours</th>
                                        <th class="px-4 py-2.5">Amount</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->overtimes as $ot)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">{{ $ot->overtime_date->format('d M Y') }}</td>
                                            <td class="px-4 py-2.5 font-mono">{{ $ot->hours }} hrs</td>
                                            <td class="px-4 py-2.5 font-mono">₹{{ number_format((float)$ot->amount, 2) }}</td>
                                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">{{ $ot->status }}</span></td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('employees.overtimes.destroy', [$employee->id, $ot->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">No overtime records found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 6: LEAVE --}}
                    <div x-show="activeTab === 'leave'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Apply / Record Leave</span></div>
                            <form action="{{ route('employees.leaves.store', $employee->id) }}" method="POST" class="p-4 grid grid-cols-1 sm:grid-cols-6 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Leave Type *</label>
                                    <select name="leave_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                        <option value="Casual Leave">Casual Leave</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Earned Leave">Earned Leave</option>
                                        <option value="Unpaid Leave">Unpaid Leave</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">From *</label>
                                    <input type="date" name="from_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">To *</label>
                                    <input type="date" name="to_date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Days *</label>
                                    <input type="number" step="0.5" min="0.5" name="days" required value="1" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status</label>
                                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">+ Add Leave</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Type</th>
                                        <th class="px-4 py-2.5">Period</th>
                                        <th class="px-4 py-2.5">Days</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->leaves as $lv)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">{{ $lv->leave_type }}</td>
                                            <td class="px-4 py-2.5">{{ $lv->from_date->format('d M Y') }} &ndash; {{ $lv->to_date->format('d M Y') }}</td>
                                            <td class="px-4 py-2.5 font-mono">{{ $lv->days }}</td>
                                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">{{ $lv->status }}</span></td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('employees.leaves.destroy', [$employee->id, $lv->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">No leave records found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 7: SALARY DETAILS --}}
                    @php
                        $baseSalary = (float) ($employee->basic_salary > 0 ? $employee->basic_salary : $employee->salary);
                        $empAllowances = $employee->salaryComponents->where('type', 'allowance')->values();
                        $empDeductions = $employee->salaryComponents->where('type', 'deduction')->values();
                        $totalAllowances = (float) $empAllowances->sum('calculated_amount');
                        $totalDeductions = (float) $empDeductions->sum('calculated_amount');
                        $totalSalary = max(0, $totalAllowances - $totalDeductions);
                        $salaryStructure = $employee->salary_structure ?: 'Standard Pay Structure';
                        $masterAllowancesArr = $masterAllowances->map(function ($m) {
                            return [
                                'id' => $m->id,
                                'name' => $m->name,
                                'description' => $m->description ?? '',
                            ];
                        })->values()->all();
                        $masterDeductionsArr = $masterDeductions->map(function ($m) {
                            return [
                                'id' => $m->id,
                                'name' => $m->name,
                                'description' => $m->description ?? '',
                            ];
                        })->values()->all();
                    @endphp
                    <script>
                        window.__salaryMasterAllowances = @json($masterAllowancesArr);
                        window.__salaryMasterDeductions = @json($masterDeductionsArr);
                    </script>
                    <div x-show="activeTab === 'salary'" style="display: none;" class="space-y-4"
                        x-data="{
                            openStructureModal: false,
                            openBankModal: false,
                            openRevisionModal: false,
                            openCompModal: false,
                            compDropdownOpen: false,
                            compMode: 'add',
                            compType: 'allowance',
                            compAction: '{{ route('employees.salary-components.store', $employee->id) }}',
                            masterAllowances: window.__salaryMasterAllowances || [],
                            masterDeductions: window.__salaryMasterDeductions || [],
                            compForm: {
                                salary_component_id: '',
                                name: '',
                                description: '',
                                calculation_type: 'Fixed Amount',
                                amount: 0,
                                source: 'Master'
                            },
                            filteredMasterComponents() {
                                const list = this.compType === 'allowance' ? this.masterAllowances : this.masterDeductions;
                                const q = (this.compForm.name || '').trim().toLowerCase();
                                if (!q) return list;
                                return list.filter(x => (x.name || '').toLowerCase().includes(q));
                            },
                            selectMasterComponent(m) {
                                this.compForm.salary_component_id = m.id;
                                this.compForm.name = m.name;
                                this.compForm.description = m.description || '';
                                this.compForm.source = 'Master';
                                this.compDropdownOpen = false;
                            },
                            openAddComponent(type) {
                                this.compMode = 'add';
                                this.compType = type;
                                this.compDropdownOpen = false;
                                this.compAction = '{{ route('employees.salary-components.store', $employee->id) }}';
                                this.compForm = {
                                    salary_component_id: '',
                                    name: '',
                                    description: '',
                                    calculation_type: 'Fixed Amount',
                                    amount: 0,
                                    source: 'Master'
                                };
                                this.openCompModal = true;
                            },
                            openEditComponent(comp) {
                                this.compMode = 'edit';
                                this.compType = comp.type;
                                this.compAction = '{{ url('/employees/' . $employee->id . '/salary-components') }}/' + comp.id + '/update';
                                this.compForm = {
                                    salary_component_id: comp.salary_component_id || '',
                                    name: comp.name || '',
                                    description: comp.description || '',
                                    calculation_type: comp.calculation_type || 'Fixed Amount',
                                    amount: comp.configured_value ?? 0,
                                    source: comp.source || 'Master'
                                };
                                this.openCompModal = true;
                            }
                        }">

                        {{-- Top Summary Grid --}}
                        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Allowances</p>
                                    <p class="text-sm font-extrabold text-emerald-600 font-mono mt-1">₹{{ number_format($totalAllowances, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Deductions</p>
                                    <p class="text-sm font-extrabold text-rose-600 font-mono mt-1">₹{{ number_format($totalDeductions, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Salary</p>
                                    <p class="text-sm font-extrabold text-slate-900 font-mono mt-1">₹{{ number_format($totalSalary, 2) }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Total Allowances - Total Deductions</p>
                                </div>
                            </div>
                        </div>

                        {{-- Allowances Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span>Allowances</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/15 text-white">{{ $empAllowances->count() }}</span>
                                </div>
                                <button type="button" @click="openAddComponent('allowance')" style="background:#a38c29;" class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase text-white hover:opacity-95 cursor-pointer">
                                    + Add Allowance
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                        <tr>
                                            <th class="px-4 py-2.5">Allowance Name</th>
                                            <th class="px-4 py-2.5">Source</th>
                                            <th class="px-4 py-2.5">Calculation Type</th>
                                            <th class="px-4 py-2.5 text-right">Configured Value</th>
                                            <th class="px-4 py-2.5 text-right">Calculated Amount</th>
                                            <th class="px-4 py-2.5 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($empAllowances as $comp)
                                            <tr class="hover:bg-slate-50/70">
                                                <td class="px-4 py-2.5">
                                                    <p class="font-bold text-slate-800">{{ $comp->name }}</p>
                                                    @if($comp->description)
                                                        <p class="text-[10px] text-slate-400">{{ $comp->description }}</p>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2.5">
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $comp->source }}</span>
                                                </td>
                                                <td class="px-4 py-2.5">
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700">{{ $comp->calculation_type }}</span>
                                                </td>
                                                <td class="px-4 py-2.5 text-right font-mono text-slate-700">
                                                    {{ $comp->calculation_type === 'Percentage' ? number_format((float) $comp->configured_value, 2) . '%' : '₹' . number_format((float) $comp->configured_value, 2) }}
                                                </td>
                                                <td class="px-4 py-2.5 text-right font-mono font-bold text-emerald-600">
                                                    ₹{{ number_format((float) $comp->calculated_amount, 2) }}
                                                </td>
                                                <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                                    <div class="inline-flex items-center justify-end gap-1.5">
                                                        <button type="button" @click="openEditComponent(@js($comp))" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Allowance">
                                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                        <button type="button" @click="openDeleteConfirm('{{ route('employees.salary-components.destroy', [$employee->id, $comp->id]) }}', 'DELETE ALLOWANCE', {{ Js::from($comp->name) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Allowance">
                                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="px-4 py-5 text-center text-slate-400">No allowances configured. Click "+ Add Allowance" to assign one.</td></tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-slate-50 border-t border-slate-200 font-bold">
                                        <tr>
                                            <td colspan="4" class="px-4 py-2.5 text-right text-slate-600">Total Allowances</td>
                                            <td class="px-4 py-2.5 text-right font-mono text-emerald-600">₹{{ number_format($totalAllowances, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Deductions Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span>Deductions</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/15 text-white">{{ $empDeductions->count() }}</span>
                                </div>
                                <button type="button" @click="openAddComponent('deduction')" style="background:#a38c29;" class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase text-white hover:opacity-95 cursor-pointer">
                                    + Add Deduction
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                        <tr>
                                            <th class="px-4 py-2.5">Deduction Name</th>
                                            <th class="px-4 py-2.5">Source</th>
                                            <th class="px-4 py-2.5">Calculation Type</th>
                                            <th class="px-4 py-2.5 text-right">Configured Value</th>
                                            <th class="px-4 py-2.5 text-right">Calculated Amount</th>
                                            <th class="px-4 py-2.5 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($empDeductions as $comp)
                                            <tr class="hover:bg-slate-50/70">
                                                <td class="px-4 py-2.5">
                                                    <p class="font-bold text-slate-800">{{ $comp->name }}</p>
                                                    @if($comp->description)
                                                        <p class="text-[10px] text-slate-400">{{ $comp->description }}</p>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2.5">
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $comp->source }}</span>
                                                </td>
                                                <td class="px-4 py-2.5">
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-50 text-rose-700">{{ $comp->calculation_type }}</span>
                                                </td>
                                                <td class="px-4 py-2.5 text-right font-mono text-slate-700">
                                                    {{ $comp->calculation_type === 'Percentage' ? number_format((float) $comp->configured_value, 2) . '%' : '₹' . number_format((float) $comp->configured_value, 2) }}
                                                </td>
                                                <td class="px-4 py-2.5 text-right font-mono font-bold text-rose-600">
                                                    ₹{{ number_format((float) $comp->calculated_amount, 2) }}
                                                </td>
                                                <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                                    <div class="inline-flex items-center justify-end gap-1.5">
                                                        <button type="button" @click="openEditComponent(@js($comp))" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Edit Deduction">
                                                            <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        </button>
                                                        <button type="button" @click="openDeleteConfirm('{{ route('employees.salary-components.destroy', [$employee->id, $comp->id]) }}', 'DELETE DEDUCTION', {{ Js::from($comp->name) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Deduction">
                                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="px-4 py-5 text-center text-slate-400">No deductions configured. Click "+ Add Deduction" to assign one.</td></tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-slate-50 border-t border-slate-200 font-bold">
                                        <tr>
                                            <td colspan="4" class="px-4 py-2.5 text-right text-slate-600">Total Deductions</td>
                                            <td class="px-4 py-2.5 text-right font-mono text-rose-600">₹{{ number_format($totalDeductions, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Bank Details Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header flex items-center justify-between">
                                <span>Bank Details</span>
                                <button type="button" @click="openBankModal = true" style="background:#a38c29;" class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase text-white hover:opacity-95 cursor-pointer">
                                    Edit
                                </button>
                            </div>
                            <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-5 text-xs">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Bank Name</p>
                                    <p class="font-bold text-slate-800 mt-1">{{ $employee->bank_name ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Account Number</p>
                                    <p class="font-bold font-mono text-slate-800 mt-1">{{ $employee->bank_account_number ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">IFSC Code</p>
                                    <p class="font-bold font-mono text-slate-800 mt-1 uppercase">{{ $employee->ifsc_code ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PAN Number</p>
                                    <p class="font-bold font-mono text-slate-800 mt-1 uppercase">{{ $employee->pan_number ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">UAN / PF Number</p>
                                    <p class="font-bold font-mono text-slate-800 mt-1">{{ $employee->uan_number ?: '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Bank Country</p>
                                    <p class="font-bold text-slate-800 mt-1">{{ $employee->bank_country ?: 'India' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Salary History Card --}}
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header flex items-center justify-between">
                                <span>Salary History</span>
                                <button type="button" @click="openRevisionModal = true" style="background:#a38c29;" class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase text-white hover:opacity-95 cursor-pointer">
                                    + Record Salary Revision
                                </button>
                            </div>
                            <div class="p-5 space-y-4">
                                @forelse($employee->salaryRevisions as $rev)
                                    <div x-data="{ openBreak: false }" class="rounded-xl border {{ $rev->is_current ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200 bg-slate-50/40' }} p-4 space-y-3 text-xs">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="font-extrabold text-slate-800">
                                                    {{ $rev->effective_from?->format('d M Y') }} &ndash; {{ $rev->effective_to ? $rev->effective_to->format('d M Y') : 'Present' }}
                                                </span>
                                                <span class="text-slate-400">&bull;</span>
                                                <span class="font-semibold text-slate-700">{{ $rev->structure_name }}</span>
                                                @if($rev->revision_label)
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">{{ $rev->revision_label }}</span>
                                                @endif
                                                @if($rev->is_current)
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Current Salary</span>
                                                @endif
                                            </div>
                                            <button type="button" @click="openDeleteConfirm('{{ route('employees.salary-revisions.destroy', [$employee->id, $rev->id]) }}', 'DELETE SALARY REVISION', {{ Js::from($rev->revision_label ?: 'Salary Revision') }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Revision">
                                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 pt-1">
                                            <div>
                                                <p class="text-[10px] font-bold uppercase text-slate-400">Base Salary</p>
                                                <p class="font-bold font-mono text-slate-800 mt-0.5">₹{{ number_format((float) $rev->base_salary, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold uppercase text-slate-400">Gross Salary</p>
                                                <p class="font-bold font-mono text-slate-800 mt-0.5">₹{{ number_format((float) $rev->gross_salary, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold uppercase text-slate-400">Total Allowances</p>
                                                <p class="font-bold font-mono text-emerald-600 mt-0.5">+₹{{ number_format((float) $rev->total_allowances, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold uppercase text-slate-400">Total Deductions</p>
                                                <p class="font-bold font-mono text-rose-600 mt-0.5">-₹{{ number_format((float) $rev->total_deductions, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold uppercase text-slate-400">Net Salary</p>
                                                <p class="font-extrabold font-mono text-slate-900 mt-0.5">₹{{ number_format((float) $rev->net_salary, 2) }}</p>
                                            </div>
                                        </div>

                                        @if($rev->notes)
                                            <p class="text-[11px] text-slate-500 italic">{{ $rev->notes }}</p>
                                        @endif

                                        @if(!empty($rev->breakdown))
                                            <div>
                                                <button type="button" @click="openBreak = !openBreak" class="text-[11px] font-bold text-[#a38c29] hover:underline cursor-pointer">
                                                    <span x-text="openBreak ? 'Hide component breakdown' : 'View component breakdown'"></span>
                                                </button>
                                                <div x-show="openBreak" x-cloak class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-200">
                                                    @foreach($rev->breakdown as $b)
                                                        <div class="flex items-center justify-between px-3 py-1.5 rounded-lg bg-white border border-slate-100">
                                                            <span class="font-semibold text-slate-700">{{ $b['name'] ?? '' }}</span>
                                                            <span class="font-mono font-bold {{ ($b['type'] ?? '') === 'allowance' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                                {{ ($b['type'] ?? '') === 'allowance' ? '+' : '-' }}₹{{ number_format((float) ($b['amount'] ?? 0), 2) }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-center text-xs text-slate-400 py-4">No salary revisions recorded yet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Modal 1: Edit Salary Structure --}}
                        <div x-show="openStructureModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
                            <div @click.away="openStructureModal = false" class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Edit Salary Structure</h3>
                                    <button type="button" @click="openStructureModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                                </div>
                                <form action="{{ route('employees.salary-structure.update', $employee->id) }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Salary Structure</label>
                                        <input type="text" name="salary_structure" value="{{ $salaryStructure }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                    </div>
                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                        <button type="button" @click="openStructureModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal 2: Add / Edit Allowance or Deduction Component --}}
                        <div x-show="openCompModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
                            <div @click.away="openCompModal = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                                    <h3 class="font-black text-sm uppercase tracking-wider text-white"
                                        x-text="(compMode === 'add' ? 'Add ' : 'Edit ') + (compType === 'allowance' ? 'Allowance' : 'Deduction')"></h3>
                                    <button type="button" @click="openCompModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                                </div>
                                <form :action="compAction" method="POST" class="p-6 space-y-4 text-xs font-sans">
                                    @csrf
                                    <input type="hidden" name="type" :value="compType">
                                    <input type="hidden" name="source" :value="compForm.source">
                                    <input type="hidden" name="salary_component_id" :value="compForm.salary_component_id">

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="sm:col-span-2 relative" @click.away="compDropdownOpen = false">
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Component Name * <span class="text-slate-400 font-normal normal-case">(Search &amp; select or type new)</span></label>
                                            <input type="text" name="name" x-model="compForm.name" required autocomplete="off"
                                                   @focus="if (compMode === 'add') compDropdownOpen = true"
                                                   @input="compForm.salary_component_id = ''; if (compMode === 'add') compDropdownOpen = true"
                                                   placeholder="Search or enter component name"
                                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                            <div x-show="compMode === 'add' && compDropdownOpen" x-cloak
                                                 class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-48 overflow-y-auto z-50 divide-y divide-slate-100">
                                                <template x-for="m in filteredMasterComponents()" :key="m.id">
                                                    <button type="button" @click="selectMasterComponent(m)"
                                                            class="w-full text-left px-3 py-2 text-xs hover:bg-amber-50/70 flex items-center justify-between cursor-pointer">
                                                        <span class="font-bold text-slate-800" x-text="m.name"></span>
                                                    </button>
                                                </template>
                                                <template x-if="filteredMasterComponents().length === 0 && (compForm.name || '').trim() !== ''">
                                                    <div class="px-3 py-2 text-[11px] text-slate-500">
                                                        No existing match — <span class="font-bold text-[#a38c29]" x-text="'\"' + compForm.name.trim() + '\"'"></span> will be saved to master &amp; linked.
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Calculation Type *</label>
                                            <select name="calculation_type" x-model="compForm.calculation_type" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                                <option value="Fixed Amount">Fixed Amount</option>
                                                <option value="Percentage">Percentage (% of Base)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Amount *</label>
                                            <input type="number" step="0.01" min="0" name="configured_value" x-model="compForm.amount" required placeholder="e.g. 12000" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-slate-900">
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                        <button type="button" @click="openCompModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Component</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal 3: Edit Bank Details --}}
                        <div x-show="openBankModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
                            <div @click.away="openBankModal = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Edit Bank Details</h3>
                                    <button type="button" @click="openBankModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                                </div>
                                <form action="{{ route('employees.bank.update', $employee->id) }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Bank Name</label>
                                            <input type="text" name="bank_name" value="{{ $employee->bank_name }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Account Number</label>
                                            <input type="text" name="bank_account_number" value="{{ $employee->bank_account_number }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">IFSC Code</label>
                                            <input type="text" name="ifsc_code" value="{{ $employee->ifsc_code }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold uppercase text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">PAN Number</label>
                                            <input type="text" name="pan_number" value="{{ $employee->pan_number }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold uppercase text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">UAN / PF Number</label>
                                            <input type="text" name="uan_number" value="{{ $employee->uan_number }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Bank Country</label>
                                            <input type="text" name="bank_country" value="{{ $employee->bank_country ?: 'India' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                        <button type="button" @click="openBankModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Bank Details</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal 4: Record Salary Revision --}}
                        <div x-show="openRevisionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" style="font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;">
                            <div @click.away="openRevisionModal = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border-0 ring-0 outline-none">
                                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Record Salary Revision</h3>
                                    <button type="button" @click="openRevisionModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                                </div>
                                <form action="{{ route('employees.salary-revisions.store', $employee->id) }}" method="POST" class="p-6 space-y-4 text-xs font-sans">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Effective From *</label>
                                            <input type="date" name="effective_from" required value="{{ now()->toDateString() }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Effective To</label>
                                            <input type="date" name="effective_to" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Structure Name</label>
                                            <input type="text" name="structure_name" value="{{ $salaryStructure }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Revision Label</label>
                                            <input type="text" name="revision_label" placeholder="e.g. Annual Appraisal" value="Initial Structure" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-semibold text-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Base Salary (₹) *</label>
                                            <input type="number" step="0.01" min="0" name="base_salary" required value="{{ $baseSalary }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-slate-900">
                                        </div>
                                        <div class="flex items-end pb-2">
                                            <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                                <input type="checkbox" name="is_current" value="1" checked class="rounded border-slate-300">
                                                <span>Set as Current Salary</span>
                                            </label>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-600 mb-1">Notes</label>
                                            <input type="text" name="notes" placeholder="Optional revision notes" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 text-slate-900">
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                        <button type="button" @click="openRevisionModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Record Revision</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 8: PAYROLL --}}
                    <div x-show="activeTab === 'payroll'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Generate Payroll Entry</span></div>
                            <form action="{{ route('employees.payrolls.store', $employee->id) }}" method="POST" class="p-4 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Month *</label>
                                    <input type="text" name="payroll_month" required value="{{ now()->format('F Y') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Allowances (₹)</label>
                                    <input type="number" step="0.01" name="allowances" value="{{ $employee->allowances ?? 0 }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Deductions (₹)</label>
                                    <input type="number" step="0.01" name="deductions" value="{{ $employee->deductions ?? 0 }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status</label>
                                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                        <option value="Paid">Paid</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">+ Generate</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Month</th>
                                        <th class="px-4 py-2.5">Allowances</th>
                                        <th class="px-4 py-2.5">Deductions</th>
                                        <th class="px-4 py-2.5">Net Salary</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->payrolls as $pay)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">{{ $pay->payroll_month }}</td>
                                            <td class="px-4 py-2.5 font-mono">₹{{ number_format((float)$pay->allowances, 2) }}</td>
                                            <td class="px-4 py-2.5 font-mono">₹{{ number_format((float)$pay->deductions, 2) }}</td>
                                            <td class="px-4 py-2.5 font-mono font-extrabold text-emerald-700">₹{{ number_format((float)$pay->net_salary, 2) }}</td>
                                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">{{ $pay->status }}</span></td>
                                            <td class="px-4 py-2.5 text-right whitespace-nowrap">
                                                <button type="button" @click="openDeleteConfirm('{{ route('employees.payrolls.destroy', [$employee->id, $pay->id]) }}', 'DELETE PAYROLL ENTRY', {{ Js::from($pay->payroll_month) }})" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition inline-flex items-center justify-center shadow-xs cursor-pointer" title="Delete Payroll Entry">
                                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">No payroll history found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 9: DOCUMENTS --}}
                    <div x-show="activeTab === 'documents'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Upload Employee Document</span></div>
                            <form action="{{ route('employees.documents.store', $employee->id) }}" method="POST" enctype="multipart/form-data" class="p-4 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Title *</label>
                                    <input type="text" name="title" required placeholder="e.g. Offer Letter" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Type</label>
                                    <input type="text" name="document_type" placeholder="e.g. ID Proof / Contract" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">File *</label>
                                    <input type="file" name="file" required class="w-full px-2 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">Upload Document</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Title</th>
                                        <th class="px-4 py-2.5">Type</th>
                                        <th class="px-4 py-2.5">File</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->documents as $doc)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">{{ $doc->title }}</td>
                                            <td class="px-4 py-2.5">{{ $doc->document_type ?: 'General' }}</td>
                                            <td class="px-4 py-2.5">
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" style="color:#104e4f;" class="font-bold underline">View / Download</a>
                                            </td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('employees.documents.destroy', [$employee->id, $doc->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">No documents uploaded.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 10: ASSETS --}}
                    <div x-show="activeTab === 'assets'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Assign Company Asset</span></div>
                            <form action="{{ route('employees.assets.store', $employee->id) }}" method="POST" class="p-4 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Asset Name *</label>
                                    <input type="text" name="asset_name" required placeholder="e.g. MacBook Pro" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Asset Code</label>
                                    <input type="text" name="asset_code" placeholder="e.g. AST-001" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Assigned Date</label>
                                    <input type="date" name="assigned_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status</label>
                                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                        <option value="Assigned">Assigned</option>
                                        <option value="Returned">Returned</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">+ Assign Asset</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Asset</th>
                                        <th class="px-4 py-2.5">Code</th>
                                        <th class="px-4 py-2.5">Assigned Date</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->assets as $asset)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">{{ $asset->asset_name }}</td>
                                            <td class="px-4 py-2.5 font-mono">{{ $asset->asset_code ?: '—' }}</td>
                                            <td class="px-4 py-2.5">{{ $asset->assigned_date ? $asset->assigned_date->format('d M Y') : '—' }}</td>
                                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700">{{ $asset->status }}</span></td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('employees.assets.destroy', [$employee->id, $asset->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">No assets assigned.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 11: EMERGENCY --}}
                    <div x-show="activeTab === 'emergency'" style="display: none;" class="space-y-4">
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                            <div class="emp-sec-header"><span>Add Emergency Contact</span></div>
                            <form action="{{ route('employees.emergency.store', $employee->id) }}" method="POST" class="p-4 grid grid-cols-1 sm:grid-cols-5 gap-3 items-end text-xs">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Contact Name *</label>
                                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Relationship *</label>
                                    <input type="text" name="relationship" required placeholder="e.g. Spouse / Parent" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Phone *</label>
                                    <input type="text" name="phone" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                </div>
                                <div>
                                    <label class="inline-flex items-center gap-2 font-bold text-slate-700 cursor-pointer pb-2">
                                        <input type="checkbox" name="is_primary" value="1" class="rounded border-slate-300">
                                        <span>Primary</span>
                                    </label>
                                </div>
                                <div>
                                    <button type="submit" style="background:#a38c29;" class="w-full px-4 py-2 rounded-xl text-white font-bold cursor-pointer">+ Add Contact</button>
                                </div>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-xs">
                                <thead class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Name</th>
                                        <th class="px-4 py-2.5">Relationship</th>
                                        <th class="px-4 py-2.5">Phone</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($employee->emergencyContacts as $ec)
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold">
                                                {{ $ec->name }}
                                                @if($ec->is_primary)
                                                    <span class="ml-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[9px] uppercase">Primary</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5">{{ $ec->relationship }}</td>
                                            <td class="px-4 py-2.5 font-mono">{{ $ec->phone }}</td>
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('employees.emergency.destroy', [$employee->id, $ec->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">No emergency contacts added.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 12: PERMISSIONS --}}
                    <div x-show="activeTab === 'permissions'" style="display: none;" class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                        <div class="emp-sec-header"><span>ERP Portal Access &amp; Linked User Account</span></div>
                        <div class="p-5 space-y-4 text-xs">
                            <form action="{{ route('employees.link-user', $employee->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 items-end">
                                @csrf
                                <div class="flex-1 w-full">
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Link ERP User Account</label>
                                    <select name="user_id" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 font-bold text-slate-800">
                                        <option value="">-- Not Linked --</option>
                                        @foreach($erpUsers as $u)
                                            <option value="{{ $u->id }}" {{ $employee->user_id === $u->id ? 'selected' : '' }}>
                                                {{ $u->name }} ({{ $u->username ? '@' . $u->username . ' | ' : '' }}{{ $u->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" style="background:#a38c29;" class="px-5 py-2.5 rounded-xl text-white font-bold uppercase cursor-pointer">Save Link</button>
                            </form>

                            @if($employee->user)
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                                    <p class="font-bold text-slate-800">Linked Account: {{ $employee->user->name }} ({{ $employee->user->email }})</p>
                                    <p class="text-slate-600">Assigned Role(s):
                                        @forelse($employee->user->roles as $r)
                                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">{{ $r->name }}</span>
                                        @empty
                                            <span class="text-slate-400">No role assigned</span>
                                        @endforelse
                                    </p>
                                    @if(auth()->user()->canViewModule('users.manage'))
                                        <a href="{{ route('admin.users.index') }}" style="color:#104e4f;" class="inline-block font-bold underline mt-1">Manage Permissions in Users &amp; Roles &rarr;</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- MODAL: EDIT PERSONAL / CONTACT / ADDRESS DETAILS --}}
        <div x-show="openPersonalModal" x-cloak style="display: none; font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;" class="emp-modal-overlay">
            <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none flex flex-col" style="max-height: 90vh;" @click.outside="openPersonalModal = false">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30 shrink-0">
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Edit Personal Details</h3>
                    <button type="button" @click="openPersonalModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="flex flex-col flex-1 min-h-0 text-xs">
                    @csrf
                    <input type="hidden" name="section" value="personal">
                    <div class="p-6 space-y-4 overflow-y-auto flex-1" style="max-height: calc(90vh - 135px);">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">First Name *</label>
                            <input type="text" name="first_name" required value="{{ $employee->first_name ?: $employee->name }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ $employee->last_name }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Gender</label>
                            <select name="gender" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                                <option value="">Select...</option>
                                @foreach(['Male', 'Female', 'Other'] as $g)
                                    <option value="{{ $g }}" {{ strtolower((string)$employee->gender) === strtolower($g) ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '' }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Nationality</label>
                            <input type="text" name="nationality" value="{{ $employee->nationality }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Blood Group</label>
                            <input type="text" name="blood_group" value="{{ $employee->blood_group }}" placeholder="e.g. O+" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Personal Email</label>
                            <input type="email" name="personal_email" value="{{ $employee->personal_email }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Mobile Phone</label>
                            <input type="text" name="phone" value="{{ $employee->phone }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Alternate Phone</label>
                            <input type="text" name="alternate_phone" value="{{ $employee->alternate_phone }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Work Email</label>
                            <input type="email" name="work_email" value="{{ $employee->work_email ?: $employee->email }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Address Line 1</label>
                            <input type="text" name="address_line_1" value="{{ $employee->address_line_1 }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Address Line 2</label>
                            <input type="text" name="address_line_2" value="{{ $employee->address_line_2 }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Country</label>
                            <input type="text" name="country" value="{{ $employee->country }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">State / Province / Region</label>
                            <input type="text" name="state" value="{{ $employee->state }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">City</label>
                            <input type="text" name="city" value="{{ $employee->city }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ $employee->postal_code }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                    </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2 shrink-0">
                        <button type="button" @click="openPersonalModal = false" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold uppercase cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#a38c29] hover:bg-[#8a7522] text-white text-xs font-bold uppercase cursor-pointer">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: ADD PREVIOUS EXPERIENCE --}}
        <div x-show="openExperienceModal" x-cloak style="display: none; font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;" class="emp-modal-overlay">
            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none" @click.outside="openExperienceModal = false">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                    <h3 class="font-black text-sm uppercase tracking-wider text-white">Add Previous Experience</h3>
                    <button type="button" @click="openExperienceModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <form action="{{ route('employees.experiences.store', $employee->id) }}" method="POST" class="p-6 space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Company Name *</label>
                        <input type="text" name="company_name" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Job Title *</label>
                        <input type="text" name="job_title" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">From Date</label>
                            <input type="date" name="from_date" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">To Date</label>
                            <input type="date" name="to_date" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 font-bold"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="openExperienceModal = false" class="px-4 py-2 rounded-xl bg-slate-200 text-slate-800 font-bold uppercase cursor-pointer">Cancel</button>
                        <button type="submit" style="background:#a38c29;" class="px-5 py-2 rounded-xl text-white font-bold uppercase cursor-pointer">Save Experience</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Global Delete Confirmation Modal --}}
        <div x-show="openDeleteModal" x-cloak x-transition.opacity style="display: none; font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden border-0 ring-0 outline-none transform transition-all" @click.outside="openDeleteModal = false">
                <div class="bg-[#2a2415] p-5 text-white flex items-center justify-between border-b border-[#a38c29]/30">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-[#a38c29]/30 text-[#f3e5ab] text-[9px] font-black uppercase tracking-wider rounded border border-[#a38c29]/40 mb-1">CONFIRMATION</span>
                        <h3 class="font-black text-base uppercase tracking-wider text-white" x-text="deleteTitle"></h3>
                    </div>
                    <button type="button" @click="openDeleteModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center font-bold text-xs transition cursor-pointer">✕</button>
                </div>
                <div class="p-6 space-y-4 text-center font-sans">
                    <p class="text-xs font-semibold text-slate-600">Are you sure you want to remove <span class="font-bold text-slate-900" x-text="deleteItemName"></span>?</p>
                    <form :action="deleteAction" method="POST" class="flex justify-center gap-3 pt-2">
                        @csrf
                        <button type="button" @click="openDeleteModal = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-black uppercase rounded-xl transition cursor-pointer">CANCEL</button>
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition shadow-md cursor-pointer">CONFIRM DELETE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-erp-layout>
