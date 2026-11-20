<x-erp-layout title="Activity & Audit - Hindustan ERP" header="Activity & Audit">
    <div x-data="activityAuditApp()" class="space-y-4">

        {{-- Toast Notification --}}
        <div x-show="toast.show"
             x-transition.opacity.duration.250ms
             style="display: none;"
             class="fixed top-5 right-5 z-50 flex items-center gap-2.5 px-4 py-2.5 rounded-xl shadow-lg text-xs font-bold text-white"
             :class="toast.type === 'error' ? 'bg-rose-600' : 'bg-emerald-600'">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span x-text="toast.message"></span>
        </div>

        {{-- Top Header Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-[#a38c29]/12 border border-[#a38c29]/25 text-[#a38c29] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Activity & Audit</h1>
                    <p class="text-xs text-slate-500 font-medium">Employee activity, exports and recorded changes</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Logging Active Pill + Switch --}}
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border transition"
                          :class="loggingActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="loggingActive ? 'Logging Active' : 'Logging Paused'"></span>
                    </span>

                    <button type="button"
                            @click="toggleLogging()"
                            :disabled="togglingLogging"
                            role="switch"
                            :aria-checked="loggingActive.toString()"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="loggingActive ? 'bg-emerald-500' : 'bg-slate-300'">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                              :class="loggingActive ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>

                {{-- Clear Logs Button --}}
                <button type="button"
                        @click="clearLogs()"
                        :disabled="clearingLogs"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span x-text="clearingLogs ? 'Clearing...' : 'Clear Logs'"></span>
                </button>
            </div>
        </div>

        {{-- Filter By Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-4">
            <h2 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Filter by</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Employee Dropdown --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1.5">Employee</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <select x-model="filters.user_id"
                                @change="refreshData()"
                                class="w-full pl-9 pr-8 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29]">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- From Date --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1.5">From Date</label>
                    <input type="date"
                           x-model="filters.from_date"
                           @change="refreshData()"
                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29]">
                </div>

                {{-- To Date --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1.5">To Date</label>
                    <input type="date"
                           x-model="filters.to_date"
                           @change="refreshData()"
                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29]">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                <p class="text-[11px] text-slate-500 font-medium">
                    Times: India Standard Time · Updated <span x-text="audit.updated_at"></span>
                    <span class="ml-2 text-slate-400">(<span x-text="audit.total_records"></span> loaded records)</span>
                </p>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button"
                            @click="resetFilters()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>

                    <button type="button"
                            @click="refreshData()"
                            :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-[#a38c29]/40 bg-white hover:bg-[#a38c29]/5 disabled:opacity-50 text-[#8a7522] text-xs font-bold uppercase tracking-wider transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="loading ? 'Refreshing...' : 'Refresh'"></span>
                    </button>

                    @if(auth()->user()->hasModulePermission('activity_logs.manage', 'excel'))
                    <a :href="exportUrl"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider transition shadow-sm shadow-emerald-600/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </a>
                    @endif

                    @if(auth()->user()->hasModulePermission('activity_logs.manage', 'pdf'))
                    <a :href="exportPdfUrl"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold uppercase tracking-wider transition shadow-sm shadow-rose-600/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs & Records Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            {{-- Tab Strip --}}
            <div class="border-b border-slate-200 bg-[#faf8ef]/50 px-4 flex flex-wrap items-center gap-1 overflow-x-auto">
                <template x-for="tab in tabs" :key="tab.key">
                    <button type="button"
                            @click="activeTab = tab.key"
                            class="px-3.5 py-3 text-xs font-bold border-b-2 transition whitespace-nowrap cursor-pointer"
                            :class="activeTab === tab.key ? 'border-[#a38c29] text-[#8a7522]' : 'border-transparent text-slate-500 hover:text-slate-800'">
                        <span x-text="tab.label"></span>
                        <span class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded-full font-extrabold"
                              :class="activeTab === tab.key ? 'bg-[#a38c29]/15 text-[#8a7522]' : 'bg-slate-100 text-slate-600'"
                              x-text="tabCount(tab.key)"></span>
                    </button>
                </template>
            </div>

            {{-- Search & Info Note --}}
            <div class="p-4 border-b border-slate-100 space-y-2 bg-white">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Search in loaded records..."
                           class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#a38c29]/30 focus:border-[#a38c29]">
                </div>
                <p class="text-[11px] text-slate-400">
                    Estimated activity for this batch only, not a full-day total. The date filters when records were received; offline activity may arrive later.
                </p>
            </div>

            {{-- TAB 1: Daily Presence --}}
            <div x-show="activeTab === 'presence'" class="divide-y divide-slate-200">
                {{-- Summary Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                                <th class="px-5 py-3.5 erp-table-header text-white">First Observed</th>
                                <th class="px-5 py-3.5 erp-table-header text-white">Last Recorded Activity</th>
                                <th class="px-5 py-3.5 erp-table-header text-white">Estimated Active</th>
                                <th class="px-5 py-3.5 erp-table-header text-white text-center">Opens / Resumes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="row in filteredPresenceSummary" :key="row.employee_email + row.first_observed">
                                <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                    <td class="px-5 py-3.5 font-semibold text-slate-800">
                                        <span class="block" x-text="row.employee_email"></span>
                                        <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.first_observed"></td>
                                    <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.last_recorded"></td>
                                    <td class="px-5 py-3.5 font-bold text-[#8a7522]" x-text="row.estimated_active"></td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700" x-text="row.opens_resumes"></span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredPresenceSummary.length === 0">
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400 font-medium">No presence records found for the selected filter.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Activity Timeline --}}
                <div>
                    <div class="px-5 py-3 bg-[#faf8ef] border-b border-slate-200 flex items-center justify-between">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Activity Timeline</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-slate-200 text-slate-600"
                              x-text="filteredTimeline.length + ' events'"></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                                    <th class="px-5 py-3.5 erp-table-header text-white">Observed Time</th>
                                    <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                                    <th class="px-5 py-3.5 erp-table-header text-white">Event</th>
                                    <th class="px-5 py-3.5 erp-table-header text-white">Screen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="row in filteredTimeline" :key="row.id">
                                    <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                        <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.observed_time"></td>
                                        <td class="px-5 py-3.5 text-slate-800 font-medium">
                                            <span class="block" x-text="row.employee_email"></span>
                                            <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px]" x-text="row.event"></span>
                                        </td>
                                        <td class="px-5 py-3.5 text-slate-700 font-mono text-[11px]" x-text="row.screen"></td>
                                    </tr>
                                </template>
                                <tr x-show="filteredTimeline.length === 0">
                                    <td colspan="4" class="px-5 py-8 text-center text-slate-400 font-medium">No timeline events found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 2: Screen & Module Usage --}}
            <div x-show="activeTab === 'screen'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Module</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Screen</th>
                            <th class="px-5 py-3.5 erp-table-header text-white text-center">Visits</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Total Time</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Last Visited</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, idx) in filteredScreenUsage" :key="idx">
                            <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    <span class="block" x-text="row.employee_email"></span>
                                    <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-700" x-text="row.module"></td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-600" x-text="row.screen"></td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#a38c29]/10 text-[#8a7522]" x-text="row.visits"></span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 font-semibold" x-text="row.total_time"></td>
                                <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.last_visited"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredScreenUsage.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">No screen or module usage recorded.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TAB 3: Actions --}}
            <div x-show="activeTab === 'actions'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <th class="px-5 py-3.5 erp-table-header text-white">Observed Time</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Action</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Module</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Description</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Screen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="row in filteredActions" :key="row.id">
                            <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.observed_time"></td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    <span class="block" x-text="row.employee_email"></span>
                                    <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded bg-[#a38c29]/12 text-[#8a7522] border border-[#a38c29]/25 font-bold text-[10px]" x-text="row.action"></span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 font-semibold" x-text="row.module"></td>
                                <td class="px-5 py-3.5 text-slate-600" x-text="row.description"></td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-500" x-text="row.screen"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredActions.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">No user actions recorded in this range.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TAB 4: Exports --}}
            <div x-show="activeTab === 'exports'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <th class="px-5 py-3.5 erp-table-header text-white">Observed Time</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Export Type</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Module</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Screen</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="row in filteredExports" :key="row.id">
                            <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.observed_time"></td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    <span class="block" x-text="row.employee_email"></span>
                                    <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px]" x-text="row.export_type"></span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 font-semibold" x-text="row.module"></td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-500" x-text="row.screen"></td>
                                <td class="px-5 py-3.5 text-slate-600" x-text="row.description"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredExports.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">No exports recorded in this range.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TAB 5: Devices & Security --}}
            <div x-show="activeTab === 'security'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <th class="px-5 py-3.5 erp-table-header text-white">Observed Time</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Event</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">IP Address</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Device / Browser</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Screen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="row in filteredSecurity" :key="row.id">
                            <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.observed_time"></td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    <span class="block" x-text="row.employee_email"></span>
                                    <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[10px]" x-text="row.event"></span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-700" x-text="row.ip_address"></td>
                                <td class="px-5 py-3.5 text-slate-600" x-text="row.device"></td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-500" x-text="row.screen"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredSecurity.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">No device or security events found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TAB 6: Record History --}}
            <div x-show="activeTab === 'record'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="erp-table-header text-white uppercase text-[10px] font-extrabold tracking-wider">
                            <th class="px-5 py-3.5 erp-table-header text-white">Observed Time</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Employee</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Record Type</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Record ID</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Action</th>
                            <th class="px-5 py-3.5 erp-table-header text-white">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="row in filteredRecordHistory" :key="row.id">
                            <tr class="hover:bg-[#faf8ef]/60 transition-colors">
                                <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap" x-text="row.observed_time"></td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">
                                    <span class="block" x-text="row.employee_email"></span>
                                    <span class="text-[10px] text-slate-400" x-text="row.employee_name"></span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800" x-text="row.record_type"></td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-slate-600" x-text="'#' + row.record_id"></td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 font-bold text-[10px]" x-text="row.action"></span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600" x-text="row.description"></td>
                            </tr>
                        </template>
                        <tr x-show="filteredRecordHistory.length === 0">
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400 font-medium">No recorded model changes found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function activityAuditApp() {
        return {
            activeTab: 'presence',
            searchQuery: '',
            loading: false,
            togglingLogging: false,
            clearingLogs: false,
            loggingActive: @json($loggingActive),
            filters: {
                user_id: @json((string) ($selectedUserId ?? '')),
                from_date: @json($fromDate),
                to_date: @json($toDate),
            },
            audit: @json($initialData),
            toast: {
                show: false,
                message: '',
                type: 'success',
            },
            tabs: [
                { key: 'presence', label: 'Daily Presence' },
                { key: 'screen', label: 'Screen & Module Usage' },
                { key: 'actions', label: 'Actions' },
                { key: 'exports', label: 'Exports' },
                { key: 'security', label: 'Devices & Security' },
                { key: 'record', label: 'Record History' },
            ],

            get exportUrl() {
                const params = new URLSearchParams({
                    user_id: this.filters.user_id || '',
                    from_date: this.filters.from_date || '',
                    to_date: this.filters.to_date || '',
                });
                return `{{ route('admin.activity-logs.export') }}?${params.toString()}`;
            },

            get exportPdfUrl() {
                const params = new URLSearchParams({
                    user_id: this.filters.user_id || '',
                    from_date: this.filters.from_date || '',
                    to_date: this.filters.to_date || '',
                });
                return `{{ route('admin.activity-logs.export-pdf') }}?${params.toString()}`;
            },

            showToast(message, type = 'success') {
                this.toast = { show: true, message, type };
                setTimeout(() => { this.toast.show = false; }, 3000);
            },

            matchesSearch(obj) {
                const q = (this.searchQuery || '').trim().toLowerCase();
                if (!q) return true;
                return Object.values(obj).some(val =>
                    val !== null && val !== undefined && String(val).toLowerCase().includes(q)
                );
            },

            get filteredPresenceSummary() {
                return (this.audit.presence_summary || []).filter(r => this.matchesSearch(r));
            },
            get filteredTimeline() {
                return (this.audit.presence_timeline || []).filter(r => this.matchesSearch(r));
            },
            get filteredScreenUsage() {
                return (this.audit.screen_usage || []).filter(r => this.matchesSearch(r));
            },
            get filteredActions() {
                return (this.audit.actions || []).filter(r => this.matchesSearch(r));
            },
            get filteredExports() {
                return (this.audit.exports || []).filter(r => this.matchesSearch(r));
            },
            get filteredSecurity() {
                return (this.audit.devices_security || []).filter(r => this.matchesSearch(r));
            },
            get filteredRecordHistory() {
                return (this.audit.record_history || []).filter(r => this.matchesSearch(r));
            },

            tabCount(key) {
                return {
                    presence: this.filteredTimeline.length,
                    screen: this.filteredScreenUsage.length,
                    actions: this.filteredActions.length,
                    exports: this.filteredExports.length,
                    security: this.filteredSecurity.length,
                    record: this.filteredRecordHistory.length,
                }[key] ?? 0;
            },

            refreshData() {
                this.loading = true;
                const params = new URLSearchParams({
                    user_id: this.filters.user_id || '',
                    from_date: this.filters.from_date || '',
                    to_date: this.filters.to_date || '',
                });

                fetch(`{{ route('admin.activity-logs.index') }}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(res => {
                    this.loading = false;
                    if (res.success && res.data) {
                        this.audit = res.data;
                        this.loggingActive = !!res.data.logging_active;
                    }
                })
                .catch(() => {
                    this.loading = false;
                    this.showToast('Failed to refresh activity logs.', 'error');
                });
            },

            resetFilters() {
                this.filters.user_id = '';
                this.filters.from_date = @json(now('Asia/Kolkata')->subDays(7)->toDateString());
                this.filters.to_date = @json(now('Asia/Kolkata')->toDateString());
                this.searchQuery = '';
                this.refreshData();
            },

            toggleLogging() {
                this.togglingLogging = true;
                const nextState = !this.loggingActive;

                fetch(`{{ route('admin.activity-logs.toggle') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ active: nextState }),
                })
                .then(res => res.json())
                .then(res => {
                    this.togglingLogging = false;
                    if (res.success) {
                        this.loggingActive = !!res.logging_active;
                        this.showToast(res.message || 'Logging status updated.');
                    }
                })
                .catch(() => {
                    this.togglingLogging = false;
                    this.showToast('Failed to toggle logging.', 'error');
                });
            },

            clearLogs() {
                if (!confirm('Clear all activity logs matching the current filter?')) {
                    return;
                }
                this.clearingLogs = true;

                fetch(`{{ route('admin.activity-logs.clear') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.filters),
                })
                .then(res => res.json())
                .then(res => {
                    this.clearingLogs = false;
                    if (res.success) {
                        this.showToast(res.message || 'Logs cleared.');
                        this.refreshData();
                    }
                })
                .catch(() => {
                    this.clearingLogs = false;
                    this.showToast('Failed to clear logs.', 'error');
                });
            },
        };
    }
    </script>
</x-erp-layout>

