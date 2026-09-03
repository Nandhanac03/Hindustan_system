<x-erp-layout title="Cancellation Charges & Additional Work" headerTitle="Cancellation Charges & Additional Work">
    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{ 
        activeTab: 'cancellation', 
        search: '', 
        status: '',
        printReport() { window.print(); },
        exportExcel(type) {
            let tableId = '#cancellationChargesExcelTable';
            let defaultFilename = 'HindustanERP_Cancellation_Charges_Report.xlsx';
            if (type === 'additional_work' || this.activeTab === 'additional') {
                tableId = '#additionalWorkExcelTable';
                defaultFilename = 'HindustanERP_Additional_Work_Report.xlsx';
            }
            const table = document.querySelector(tableId);
            if (!table) return;

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Report');
            worksheet.views = [{ showGridLines: true }];
            worksheet.autoFilter = { from: { row: 5, column: 1 }, to: { row: 5, column: 8 } };

            const cols = table.querySelectorAll('colgroup col');
            if (cols.length > 0) {
                worksheet.columns = Array.from(cols).map((col) => {
                    const widthPt = col.style.width || col.getAttribute('width');
                    let widthVal = 18;
                    if (widthPt) {
                        const match = widthPt.match(/[\d\.]+/);
                        if (match) {
                            const val = parseFloat(match[0]);
                            widthVal = widthPt.includes('pt') ? val / 6.0 : val / 7.0;
                        }
                    }
                    return { width: Math.max(widthVal + 6, 16) };
                });
            }

            function cssColorToHex(cssColor) {
                if (!cssColor) return null;
                cssColor = cssColor.trim();
                if (cssColor.startsWith('#')) {
                    let hex = cssColor.substring(1);
                    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
                    return 'FF' + hex.toUpperCase();
                }
                return null;
            }

            const searchLower = (this.search || '').toLowerCase().trim();
            const statusLower = (this.status || '').toLowerCase().trim();
            const rows = table.querySelectorAll('tr');
            const mergedCells = [];
            function isMerged(r, c) {
                return mergedCells.some(m => r >= m.s.r && r <= m.e.r && c >= m.s.c && c <= m.e.c);
            }

            let sheetRowIdx = 1;
            rows.forEach((tr) => {
                if (tr.parentElement.tagName === 'TBODY') {
                    const rowText = (tr.textContent || '').toLowerCase();
                    const rowStatusAttr = (tr.getAttribute('data-status') || '').toLowerCase();
                    if (searchLower && !rowText.includes(searchLower)) {
                        return;
                    }
                    if (statusLower && rowStatusAttr && rowStatusAttr !== statusLower) {
                        return;
                    }
                }

                const sheetRow = worksheet.getRow(sheetRowIdx);
                const heightAttr = tr.getAttribute('height') || tr.style.height;
                if (heightAttr) {
                    const match = heightAttr.match(/[\d\.]+/);
                    sheetRow.height = match ? Math.max(parseFloat(match[0]), 26) : 26;
                } else {
                    sheetRow.height = 26;
                }

                const cells = tr.cells;
                let colIdx = 1;
                for (let cIdx = 0; cIdx < cells.length; cIdx++) {
                    const cell = cells[cIdx];
                    while (isMerged(sheetRowIdx, colIdx)) colIdx++;

                    const colspan = parseInt(cell.getAttribute('colspan')) || 1;
                    const rowspan = parseInt(cell.getAttribute('rowspan')) || 1;
                    if (colspan > 1 || rowspan > 1) {
                        worksheet.mergeCells(sheetRowIdx, colIdx, sheetRowIdx + rowspan - 1, colIdx + colspan - 1);
                        mergedCells.push({ s: { r: sheetRowIdx, c: colIdx }, e: { r: sheetRowIdx + rowspan - 1, c: colIdx + colspan - 1 } });
                    }

                    const excelCell = worksheet.getCell(sheetRowIdx, colIdx);
                    const rawVal = cell.textContent ? cell.textContent.trim() : '';

                    const bgColorAttr = cell.getAttribute('bgcolor') || cell.style.backgroundColor;
                    const bgColorHex = cssColorToHex(bgColorAttr);
                    const textColorAttr = cell.style.color;
                    const textColorHex = cssColorToHex(textColorAttr) || 'FF000000';
                    const isBold = cell.tagName === 'TH' || cell.style.fontWeight === 'bold';

                    let horizAlign = cell.style.textAlign || (cell.tagName === 'TH' ? 'center' : 'left');
                    if (horizAlign === 'start') horizAlign = 'left';
                    if (horizAlign === 'end') horizAlign = 'right';

                    const numberFormat = cell.style.msoNumberFormat || '';
                    if (numberFormat.includes('yyyy\\-mm\\-dd')) {
                        if (rawVal && /^\d{4}-\d{2}-\d{2}$/.test(rawVal)) {
                            excelCell.value = new Date(rawVal);
                        } else {
                            excelCell.value = rawVal;
                        }
                        excelCell.numFormat = 'yyyy-mm-dd';
                    } else if (numberFormat.includes('\\#\\,\\#\\#0') || numberFormat.includes('#,##0')) {
                        const cleanVal = rawVal.replace(/[^\d\.\-]/g, '');
                        const parsedNum = parseFloat(cleanVal);
                        if (rawVal && !isNaN(parsedNum)) {
                            excelCell.value = parsedNum;
                        } else {
                            excelCell.value = '';
                        }
                        excelCell.numFormat = '#,##0.00';
                    } else {
                        excelCell.value = rawVal;
                    }

                    excelCell.font = { name: 'Calibri', size: 10, bold: isBold, color: { argb: textColorHex } };
                    if (bgColorHex) {
                        excelCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: bgColorHex } };
                    }
                    excelCell.alignment = { horizontal: horizAlign, vertical: 'middle', wrapText: true };
                    excelCell.border = {
                        top: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        left: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        bottom: { style: 'thin', color: { argb: 'FFCBD5E1' } },
                        right: { style: 'thin', color: { argb: 'FFCBD5E1' } }
                    };
                    colIdx += colspan;
                }
                sheetRowIdx++;
            });

            workbook.xlsx.writeBuffer().then(function (data) {
                const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const anchor = document.createElement('a');
                anchor.href = url;
                anchor.download = defaultFilename;
                anchor.click();
                window.URL.revokeObjectURL(url);
            });
        }
    }">
        <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
        
        {{-- Header & Breadcrumb (Treasury Style) --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Cancellation Charges & Additional Work</h1>
                <p class="text-xs text-slate-500 mt-1">Home / Sales & Property / Cancellation & Additional Work</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Cancellation Charges Export Button --}}
                <div x-show="activeTab === 'cancellation'" class="flex items-center gap-2">
                    <button @click="exportExcel('cancellation_charges')" 
                            class="px-5 py-2.5 bg-[#059669] hover:bg-[#047857] text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md shadow-emerald-700/20 hover:shadow-lg transition-all duration-300 flex items-center gap-2.5 cursor-pointer group active:scale-95">
                        <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="tracking-wide">EXPORT EXCEL</span>
                    </button>
                </div>

                {{-- Additional Work Export Button --}}
                <div x-show="activeTab === 'additional'" class="flex items-center gap-2" style="display:none;">
                    <button @click="exportExcel('additional_work')" 
                            class="px-5 py-2.5 bg-[#059669] hover:bg-[#047857] text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md shadow-emerald-700/20 hover:shadow-lg transition-all duration-300 flex items-center gap-2.5 cursor-pointer group active:scale-95">
                        <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="tracking-wide">EXPORT EXCEL</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Summary Cards (Treasury Style) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card 1: Total Cancellation Charges --}}
            <div class="bg-white border-y border-r border-l-4 border-l-[#a38c29] border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(163,140,41,0.2)] hover:border-r-[#a38c29]/20 hover:border-y-[#a38c29]/20">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#a38c29]/10 flex items-center justify-center text-[#a38c29] transition-all duration-300 group-hover:bg-[#a38c29] group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Cancellation Charges</span>
                    </div>
                    <!-- <span class="px-2 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">Collected</span> -->
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-[#a38c29]">₹{{ number_format($cancellationCharges->sum('cancellation_fee'), 2) }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total Accumulated</p>
                </div>
            </div>
            
            {{-- Card 2: Total Additional Work --}}
            <div class="bg-white border-y border-r border-l-4 border-l-emerald-500 border-slate-200 rounded-xl p-5 shadow-sm relative flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] hover:border-r-emerald-500/20 hover:border-y-emerald-500/20">
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-600">Total Additional Work</span>
                    </div>
                    <!-- <span class="px-2 py-0.5 rounded border border-slate-200 text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">Completed</span> -->
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight transition-colors duration-300 group-hover:text-emerald-600">₹{{ number_format($additionalWorks->sum('amount'), 2) }}</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Total Accumulated</p>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-1 flex mb-4">
            <button @click="activeTab = 'cancellation'" 
                    class="px-6 py-3 text-[11px] font-black uppercase tracking-wider transition-all rounded-lg"
                    :class="activeTab === 'cancellation' ? 'bg-[#a38c29] text-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'">
                Cancellation Charges
            </button>
            <button @click="activeTab = 'additional'" 
                    class="px-6 py-3 text-[11px] font-black uppercase tracking-wider transition-all rounded-lg"
                    :class="activeTab === 'additional' ? 'bg-[#a38c29] text-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'">
                Additional Work
            </button>
        </div>

        {{-- Ultra-Clean Modern Light Search & Filter Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm flex flex-col xl:flex-row xl:items-center justify-between gap-3.5 transition-all">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 flex-1">
                {{-- Search Input --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#a38c29] group-focus-within:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search Customer/Unit/Sale No..." 
                           x-model="search"
                           class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-extrabold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    
                    {{-- Clear Button --}}
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                        <button type="button" x-show="search" @click="search = ''"
                                class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition" title="Clear Search">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10m-7 5h7"/></svg>
                    </div>
                    <select x-model="status"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 cursor-pointer focus:outline-none transition-all shadow-2xs appearance-none">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            {{-- Reset Filters Button --}}
            <button type="button" @click="search = ''; status = '';"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-6 py-2.5 text-xs font-extrabold text-slate-700 shadow-sm shadow-slate-200/50 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95">
                <svg class="h-3.5 w-3.5 text-slate-500 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Reset</span>
            </button>
        </div>

        {{-- Main Content Area --}}
        
        {{-- Cancellation Charges Table --}}
        <div x-show="activeTab === 'cancellation'" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Cancellation Charges Directory
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all cancellation charges for cancelled sales.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">#</th>
                            <th class="px-5 py-3">Sale / Booking No.</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Cancellation Date</th>
                            <th class="px-5 py-3 text-right">Cancellation Fee (₹)</th>
                            <th class="px-5 py-3">Reason</th>
                            <th class="px-5 py-3 text-center w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cancellationCharges as $index => $charge)
                            @php
                                $chargeUnitDisplay = 'N/A';
                                if ($charge->saleUnits && $charge->saleUnits->count() > 0) {
                                    $chargeUnitDisplay = $charge->saleUnits->map(function($su) {
                                        return $su->unit ? $su->unit->formatted_name : '';
                                    })->filter()->implode(', ');
                                } elseif ($charge->unit) {
                                    $chargeUnitDisplay = $charge->unit->formatted_name;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition group"
                                x-show="(search === '' || 
                                    '{{ addslashes($charge->sale_number ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($charge->customer->name ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($chargeUnitDisplay) }}'.toLowerCase().includes(search.toLowerCase())
                                ) && (status === '' || '{{ addslashes($charge->status ?? '') }}'.toLowerCase() === status)">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $charge->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $chargeUnitDisplay }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->updated_at ? $charge->updated_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="px-5 py-3 text-right text-xs font-black text-[#a38c29]">
                                    ₹{{ number_format((float)($charge->cancellation_fee ?? 0), 2) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->cancellation_reason ?? 'Customer Request' }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($charge->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-rose-50 text-rose-700 border border-rose-100">{{ $charge->status }}</span>
                                    @elseif($charge->status === 'active')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $charge->status }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">{{ $charge->status ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">No cancellation charges found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Controls --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900">{{ count($cancellationCharges) > 0 ? 1 : 0 }}</span> TO 
                    <span class="text-slate-900">{{ count($cancellationCharges) }}</span> OF 
                    <span class="text-slate-900">{{ count($cancellationCharges) }}</span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">PREV</button>
                    <span class="inline-flex items-center gap-1">
                        <button type="button" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#a38c29] text-white border border-[#a38c29] shadow-2xs">1</button>
                    </span>
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">NEXT</button>
                </div>
            </div>
        </div>

        {{-- Additional Work Table --}}
        <div x-show="activeTab === 'additional'" style="display:none;" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <div class="w-1 h-4 bg-[#a38c29] rounded-full"></div>
                        Additional Work Directory
                    </h3>
                    <p class="text-[10px] font-bold text-slate-500 mt-1 pl-3">Directory of all additional work associated with sales.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-[#a38c29] text-[10px] font-black text-white uppercase tracking-wider border-y border-[#8a7522]">
                        <tr>
                            <th class="px-5 py-3 w-16 text-center">#</th>
                            <th class="px-5 py-3">Sale / Booking No.</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Unit No.</th>
                            <th class="px-5 py-3">Work Description</th>
                            <th class="px-5 py-3 text-right">Amount (₹)</th>
                            <th class="px-5 py-3">Work Date</th>
                            <th class="px-5 py-3 text-center w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($additionalWorks as $index => $work)
                            @php
                                $workUnitDisplay = 'N/A';
                                if ($work->sale && $work->sale->saleUnits && $work->sale->saleUnits->count() > 0) {
                                    $workUnitDisplay = $work->sale->saleUnits->map(function($su) {
                                        return $su->unit ? $su->unit->formatted_name : '';
                                    })->filter()->implode(', ');
                                } elseif ($work->sale && $work->sale->unit) {
                                    $workUnitDisplay = $work->sale->unit->formatted_name;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition group"
                                x-show="(search === '' || 
                                    '{{ addslashes($work->sale->sale_number ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($work->sale->customer->name ?? '') }}'.toLowerCase().includes(search.toLowerCase()) || 
                                    '{{ addslashes($workUnitDisplay) }}'.toLowerCase().includes(search.toLowerCase())
                                ) && (status === '' || '{{ addslashes($work->sale->status ?? '') }}'.toLowerCase() === status)">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $work->sale->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->sale->customer->name ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $workUnitDisplay }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->description }}</td>
                                <td class="px-5 py-3 text-right text-xs font-black text-[#a38c29]">
                                    ₹{{ number_format((float)($work->amount ?? 0), 2) }}
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->created_at ? $work->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($work->sale && $work->sale->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-rose-50 text-rose-700 border border-rose-100">{{ $work->sale->status }}</span>
                                    @elseif($work->sale && $work->sale->status === 'active')
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $work->sale->status }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide inline-block bg-blue-50 text-blue-700 border border-blue-100">{{ $work->sale->status ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">No additional work found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Controls --}}
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                    SHOWING <span class="text-slate-900">{{ count($additionalWorks) > 0 ? 1 : 0 }}</span> TO 
                    <span class="text-slate-900">{{ count($additionalWorks) }}</span> OF 
                    <span class="text-slate-900">{{ count($additionalWorks) }}</span> ENTRIES
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">PREV</button>
                    <span class="inline-flex items-center gap-1">
                        <button type="button" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#a38c29] text-white border border-[#a38c29] shadow-2xs">1</button>
                    </span>
                    <button type="button" disabled class="px-2.5 py-1 bg-white border border-slate-200 text-slate-650 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors opacity-50 cursor-not-allowed shadow-2xs">NEXT</button>
                </div>
            </div>
        </div>

        {{-- ── HIDDEN EXCEL EXPORT TABLES ── --}}
        <div class="hidden" style="display: none;">

            {{-- 1. CANCELLATION CHARGES EXCEL TABLE --}}
            <table id="cancellationChargesExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
                <colgroup>
                    <col width="60" style="width: 45pt;" />
                    <col width="160" style="width: 120pt;" />
                    <col width="200" style="width: 150pt;" />
                    <col width="280" style="width: 210pt;" />
                    <col width="140" style="width: 105pt;" />
                    <col width="180" style="width: 135pt;" />
                    <col width="250" style="width: 187pt;" />
                    <col width="120" style="width: 90pt;" />
                </colgroup>
                <thead>
                    <tr height="45" style="height: 45pt;">
                        <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                            HINDUSTAN ERP: CANCELLATION CHARGES DIRECTORY REPORT
                        </th>
                    </tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                            EXECUTIVE SUMMARY & METRIC KPIS
                        </th>
                    </tr>
                    <tr height="25" style="height: 25pt;">
                        <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Cancellation Charges:</th>
                        <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($cancellationCharges->sum('cancellation_fee') ?? 0) }}</th>
                        <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Cancelled Bookings:</th>
                        <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1;">{{ count($cancellationCharges) }}</th>
                    </tr>
                    <tr height="15" style="height: 15pt;"><th colspan="8" bgcolor="#ffffff" style="border: none;"></th></tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                            A. CANCELLATION CHARGES MASTER DIRECTORY
                        </th>
                    </tr>
                    <tr height="35" style="height: 35pt;">
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SALE / BOOKING NO.</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">CUSTOMER NAME</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">UNIT / PROPERTY</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; mso-number-format: 'yyyy\-mm\-dd';">CANCELLATION DATE</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">CANCELLATION FEE (₹)</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">REASON</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cancellationCharges as $index => $charge)
                        @php
                            $cUnitDisplay = 'N/A';
                            if ($charge->saleUnits && $charge->saleUnits->count() > 0) {
                                $cUnitDisplay = $charge->saleUnits->map(function($su) {
                                    return $su->unit ? $su->unit->formatted_name : '';
                                })->filter()->implode(', ');
                            } elseif ($charge->unit) {
                                $cUnitDisplay = $charge->unit->formatted_name;
                            }
                        @endphp
                        <tr height="28" style="height: 28pt;">
                            <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                            <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $charge->sale_number ?? 'N/A' }}</td>
                            <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $charge->customer->name ?? 'N/A' }}</td>
                            <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $cUnitDisplay }}</td>
                            <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'yyyy\-mm\-dd';">{{ $charge->updated_at ? $charge->updated_at->format('Y-m-d') : 'N/A' }}</td>
                            <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($charge->cancellation_fee ?? 0) }}</td>
                            <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $charge->cancellation_reason ?? 'Customer Request' }}</td>
                            <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; text-transform: uppercase;">{{ $charge->status ?? 'CANCELLED' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr height="28" style="height: 28pt; background-color: #ffffff;">
                        <td colspan="5" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL CANCELLATION CHARGES</td>
                        <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($cancellationCharges->sum('cancellation_fee') ?? 0) }}</td>
                        <td colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; border: 1px solid #cbd5e1;"></td>
                    </tr>
                </tfoot>
            </table>

            {{-- 2. ADDITIONAL WORK EXCEL TABLE --}}
            <table id="additionalWorkExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
                <colgroup>
                    <col width="60" style="width: 45pt;" />
                    <col width="160" style="width: 120pt;" />
                    <col width="200" style="width: 150pt;" />
                    <col width="200" style="width: 150pt;" />
                    <col width="320" style="width: 240pt;" />
                    <col width="180" style="width: 135pt;" />
                    <col width="140" style="width: 105pt;" />
                    <col width="120" style="width: 90pt;" />
                </colgroup>
                <thead>
                    <tr height="45" style="height: 45pt;">
                        <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                            HINDUSTAN ERP: ADDITIONAL WORK MASTER DIRECTORY REPORT
                        </th>
                    </tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                            EXECUTIVE SUMMARY & METRIC KPIS
                        </th>
                    </tr>
                    <tr height="25" style="height: 25pt;">
                        <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Additional Work:</th>
                        <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($additionalWorks->sum('amount') ?? 0) }}</th>
                        <th colspan="2" bgcolor="#f8fafc" style="background-color: #f8fafc; color: #17365D; font-weight: bold; font-size: 9.5pt; text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">Total Work Orders:</th>
                        <th colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; color: #17365D; font-weight: bold; font-size: 10pt; text-align: right; vertical-align: middle; border: 1px solid #cbd5e1;">{{ count($additionalWorks) }}</th>
                    </tr>
                    <tr height="15" style="height: 15pt;"><th colspan="8" bgcolor="#ffffff" style="border: none;"></th></tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="8" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: left; vertical-align: middle; border: 1px solid #475569; padding: 6px 10px; font-family: 'Calibri', 'Aptos', sans-serif;">
                            A. ADDITIONAL WORK MASTER DIRECTORY
                        </th>
                    </tr>
                    <tr height="35" style="height: 35pt;">
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SL NO</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">SALE / BOOKING NO.</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">CUSTOMER NAME</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">UNIT NO.</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">WORK DESCRIPTION</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">AMOUNT (₹)</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; mso-number-format: 'yyyy\-mm\-dd';">WORK DATE</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($additionalWorks as $index => $work)
                        @php
                            $wUnitDisplay = 'N/A';
                            if ($work->sale && $work->sale->saleUnits && $work->sale->saleUnits->count() > 0) {
                                $wUnitDisplay = $work->sale->saleUnits->map(function($su) {
                                    return $su->unit ? $su->unit->formatted_name : '';
                                })->filter()->implode(', ');
                            } elseif ($work->sale && $work->sale->unit) {
                                $wUnitDisplay = $work->sale->unit->formatted_name;
                            }
                        @endphp
                        <tr height="28" style="height: 28pt;">
                            <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                            <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $work->sale->sale_number ?? 'N/A' }}</td>
                            <td style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $work->sale->customer->name ?? 'N/A' }}</td>
                            <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $wUnitDisplay }}</td>
                            <td style="text-align: left; vertical-align: middle; border: 1px solid #cbd5e1;">{{ $work->description }}</td>
                            <td style="text-align: right; color: #059669; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($work->amount ?? 0) }}</td>
                            <td style="text-align: center; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: 'yyyy\-mm\-dd';">{{ $work->created_at ? $work->created_at->format('Y-m-d') : 'N/A' }}</td>
                            <td style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #cbd5e1; text-transform: uppercase;">{{ $work->sale->status ?? 'ACTIVE' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr height="28" style="height: 28pt; background-color: #ffffff;">
                        <td colspan="5" bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #cbd5e1;">TOTAL ADDITIONAL WORK AMOUNT</td>
                        <td bgcolor="#ffffff" style="background-color: #ffffff; font-weight: bold; text-align: right; color: #059669; vertical-align: middle; border: 1px solid #cbd5e1; mso-number-format: '\#\,\#\#0\.00';">{{ (float)($additionalWorks->sum('amount') ?? 0) }}</td>
                        <td colspan="2" bgcolor="#ffffff" style="background-color: #ffffff; border: 1px solid #cbd5e1;"></td>
                    </tr>
                </tfoot>
            </table>

        </div>

    </div>
</x-erp-layout>
