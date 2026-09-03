<x-erp-layout title="Balance Sheet (Statement of Financial Position)" headerTitle="Business Reports Center">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="{ 
    activeTab: 'balance_sheet', 
    viewType: 'vertical', 
    hideZero: false,
    printReport() { window.print(); },
    exportExcel() {
        const table = document.querySelector('#balanceSheetExcelTable');
        if (!table) return;
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Balance Sheet');
        worksheet.views = [{ showGridLines: true }];
        worksheet.autoFilter = null;

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

        const rows = table.querySelectorAll('tr');
        const mergedCells = [];
        function isMerged(r, c) {
            return mergedCells.some(m => r >= m.s.r && r <= m.e.r && c >= m.s.c && c <= m.e.c);
        }

        let sheetRowIdx = 1;
        rows.forEach((tr) => {
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
                if (numberFormat.includes('\\#\\,\\#\\#0') || numberFormat.includes('#,##0')) {
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
            anchor.download = 'HindustanERP_Balance_Sheet_Summary.xlsx';
            anchor.click();
            window.URL.revokeObjectURL(url);
        });
    }
}">
    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

    @include('reports.partials.nav')

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        {{-- Top Header & Action Bar --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Balance Sheet (Statement of Financial Position)</h1>
                    <span class="px-3 py-1 bg-amber-50 text-[#a38c29] border border-[#a38c29]/40 rounded-full text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-4 h-4 text-[#a38c29]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        BALANCE SHEET EQUAL & VALIDATED
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500 mt-1">Statement of financial position as on the selected date</p>
            </div>

            {{-- Right Action Buttons --}}
            <div class="flex items-center gap-2.5 shrink-0">
                <button @click="exportExcel()" 
                        class="px-4 py-2 bg-white border border-slate-300 hover:border-emerald-600 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 rounded-xl text-xs font-bold transition-all shadow-2xs flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export to Excel</span>
                </button>

                <button @click="printReport()" 
                        class="px-4 py-2 bg-white border border-slate-300 hover:border-rose-600 hover:bg-rose-50 text-slate-700 hover:text-rose-700 rounded-xl text-xs font-bold transition-all shadow-2xs flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Download PDF</span>
                </button>

                <button @click="printReport()" 
                        class="px-4 py-2 bg-white border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-2xs flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print</span>
                </button>
            </div>
        </div>

        {{-- Filter Bar Panel --}}
        <form method="GET" action="{{ route('reports.balance_sheet') }}" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
            <div class="flex flex-wrap items-center gap-4 justify-between">
                
                <div class="flex flex-wrap items-center gap-4 flex-1">
                    {{-- Entity / Scope --}}
                    <div class="flex items-center gap-2 min-w-[240px]">
                        <label class="text-xs font-extrabold text-slate-700 shrink-0">Entity / Scope</label>
                        <select name="project_id" class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition-all">
                            <option value="">Skyline Heights (All Towers)</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- As-On Date --}}
                    <div class="flex items-center gap-2 min-w-[200px]">
                        <label class="text-xs font-extrabold text-slate-700 shrink-0">As-On Date</label>
                        <input type="date" name="date_as_on" value="{{ request('date_as_on', '2026-03-31') }}" class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#a38c29]/20 focus:border-[#a38c29] transition-all">
                    </div>

                    {{-- View Type Radio Options --}}
                    <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                        <label class="text-xs font-extrabold text-slate-700">View Type</label>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-800 cursor-pointer">
                            <input type="radio" name="view_type" value="vertical" x-model="viewType" class="text-[#a38c29] focus:ring-[#a38c29]">
                            <span>Standard Vertical</span>
                        </label>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-800 cursor-pointer">
                            <input type="radio" name="view_type" value="t_format" x-model="viewType" class="text-[#a38c29] focus:ring-[#a38c29]">
                            <span>T-Format Horizontal</span>
                        </label>
                    </div>

                    {{-- Zero-Balance Accounts --}}
                    <div class="flex items-center gap-2 border-l border-slate-200 pl-4">
                        <label class="text-xs font-extrabold text-slate-700">Zero-Balance Accounts</label>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="hide_zero" x-model="hideZero" class="rounded text-[#a38c29] focus:ring-[#a38c29]">
                            <span>Hide Zero-Balance Accounts</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Action Buttons --}}
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white font-extrabold text-xs rounded-xl shadow-sm transition-all uppercase tracking-wider cursor-pointer">
                        Apply Filters
                    </button>
                    <a href="{{ route('reports.balance_sheet') }}" class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-extrabold text-xs rounded-xl transition-all uppercase tracking-wider">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- STATEMENT OF FINANCIAL POSITION (ASSETS Left vs LIABILITIES & EQUITY Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" :class="viewType === 't_format' ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1 lg:grid-cols-2'">
            
            {{-- 1. ASSETS TABLE CONTAINER --}}
            <div class="border border-slate-300 rounded-xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                <div class="bg-[#a38c29] text-white px-5 py-2.5 text-center font-black text-xs uppercase tracking-widest border-b border-[#8a7522]">
                    ASSETS
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs text-slate-800">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-700 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2.5 w-32 border-r border-slate-200">Account Code / Category</th>
                                <th class="px-4 py-2.5 border-r border-slate-200">Description / Account Head</th>
                                <th class="px-4 py-2.5 text-right border-r border-slate-200 w-32">Amount (Rs.)</th>
                                <th class="px-4 py-2.5 text-right w-36">Total Group Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            
                            {{-- 1000 ASSETS CATEGORY --}}
                            <tr class="bg-emerald-50/40">
                                <td class="px-4 py-2 font-black text-emerald-700 border-r border-slate-200">1000</td>
                                <td colspan="3" class="px-4 py-2 font-black text-emerald-700 uppercase tracking-wider">ASSETS</td>
                            </tr>

                            {{-- 1100 CURRENT ASSETS --}}
                            <tr>
                                <td class="px-4 py-2 font-black text-blue-700 border-r border-slate-200 pl-6">1100</td>
                                <td colspan="3" class="px-4 py-2 font-black text-blue-700 uppercase tracking-wider">CURRENT ASSETS</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1101</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Bank Balances (Karnataka Bank / HDFC Escrow)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">85,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1102</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Site Petty Cash Box Balances</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">25,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1110</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Customer Receivables (Pending Installments Billed)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">1,10,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1120</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Advance Payments to Contractors & Suppliers</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">15,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1130</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Land & Construction Work-in-Progress (Unbilled Cost)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">3,50,00,000</td>
                                <td class="px-4 py-2 text-right font-bold text-blue-700 font-mono">5,60,25,000</td>
                            </tr>
                            <tr class="bg-emerald-50/70 border-y border-emerald-200 font-black">
                                <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase">SUBTOTAL</td>
                                <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase tracking-wider">Total Current Assets</td>
                                <td class="px-4 py-2.5 text-right border-r border-emerald-200 font-mono"></td>
                                <td class="px-4 py-2.5 text-right text-emerald-800 font-mono">5,60,25,000</td>
                            </tr>

                            {{-- 1200 NON-CURRENT / FIXED ASSETS --}}
                            <tr>
                                <td class="px-4 py-2 font-black text-blue-700 border-r border-slate-200 pl-6">1200</td>
                                <td colspan="3" class="px-4 py-2 font-black text-blue-700 uppercase tracking-wider">NON-CURRENT / FIXED ASSETS</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1201</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Site Machinery, JCB & Heavy Equipment</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">40,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1210</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Corporate Office Property & Vehicles</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">60,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1220</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Computer Hardware & ERP Software Licenses</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">5,00,000</td>
                                <td class="px-4 py-2 text-right font-bold text-blue-700 font-mono">95,00,000</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">1290</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Less: Accumulated Depreciation</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono text-rose-600">(10,00,000)</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="bg-emerald-50/70 border-y border-emerald-200 font-black">
                                <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase">SUBTOTAL</td>
                                <td class="px-4 py-2.5 text-emerald-800 border-r border-emerald-200 uppercase tracking-wider">Total Non-Current / Fixed Assets</td>
                                <td class="px-4 py-2.5 text-right border-r border-emerald-200 font-mono"></td>
                                <td class="px-4 py-2.5 text-right text-emerald-800 font-mono">95,00,000</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- TOTAL ASSETS BANNER ROW --}}
                <div class="bg-indigo-50 border-t-2 border-indigo-200 px-4 py-3 flex items-center justify-between font-black text-indigo-950 uppercase tracking-wider text-xs">
                    <div>
                        <span class="text-indigo-600 mr-2">TOTAL (A)</span>
                        <span>TOTAL ASSETS</span>
                    </div>
                    <span class="font-mono text-indigo-700 text-sm">6,55,25,000</span>
                </div>
            </div>

            {{-- 2. LIABILITIES & EQUITY TABLE CONTAINER --}}
            <div class="border border-slate-300 rounded-xl overflow-hidden shadow-sm bg-white flex flex-col justify-between">
                <div class="bg-[#a38c29] text-white px-5 py-2.5 text-center font-black text-xs uppercase tracking-widest border-b border-[#8a7522]">
                    LIABILITIES & EQUITY
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs text-slate-800">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-700 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2.5 w-32 border-r border-slate-200">Account Code / Category</th>
                                <th class="px-4 py-2.5 border-r border-slate-200">Description / Account Head</th>
                                <th class="px-4 py-2.5 text-right border-r border-slate-200 w-32">Amount (Rs.)</th>
                                <th class="px-4 py-2.5 text-right w-36">Total Group Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            
                            {{-- 2000 LIABILITIES CATEGORY --}}
                            <tr class="bg-rose-50/40">
                                <td class="px-4 py-2 font-black text-rose-700 border-r border-slate-200">2000</td>
                                <td colspan="3" class="px-4 py-2 font-black text-rose-700 uppercase tracking-wider">LIABILITIES</td>
                            </tr>

                            {{-- 2100 CURRENT LIABILITIES & PAYABLES --}}
                            <tr>
                                <td class="px-4 py-2 font-black text-rose-700 border-r border-slate-200 pl-6">2100</td>
                                <td colspan="3" class="px-4 py-2 font-black text-rose-700 uppercase tracking-wider">CURRENT LIABILITIES & PAYABLES</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">2101</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Contractor RA Work Bills Payable</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">40,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">2110</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Material Supplier & Vendor Payables</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">30,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">2120</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Statutory Payables (GST, TDS, Provident Fund)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">12,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">2130</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Advances Received from Customers (Unbilled)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">25,00,000</td>
                                <td class="px-4 py-2 text-right font-bold text-rose-700 font-mono">1,07,00,000</td>
                            </tr>
                            <tr class="bg-rose-50/70 border-y border-rose-200 font-black">
                                <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase">SUBTOTAL</td>
                                <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase tracking-wider">Total Current Liabilities</td>
                                <td class="px-4 py-2.5 text-right border-r border-rose-200 font-mono"></td>
                                <td class="px-4 py-2.5 text-right text-rose-800 font-mono">1,07,00,000</td>
                            </tr>

                            {{-- 2200 NON-CURRENT / LONG-TERM LIABILITIES --}}
                            <tr>
                                <td class="px-4 py-2 font-black text-rose-700 border-r border-slate-200 pl-6">2200</td>
                                <td colspan="3" class="px-4 py-2 font-black text-rose-700 uppercase tracking-wider">NON-CURRENT / LONG-TERM LIABILITIES</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">2201</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Bank Construction Loans / Project Credit Limits</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">2,00,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">2210</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Bank Land Acquisition Loan Accounts</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">1,50,00,000</td>
                                <td class="px-4 py-2 text-right font-bold text-rose-700 font-mono">3,50,00,000</td>
                            </tr>
                            <tr class="bg-rose-50/70 border-y border-rose-200 font-black">
                                <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase">SUBTOTAL</td>
                                <td class="px-4 py-2.5 text-rose-800 border-r border-rose-200 uppercase tracking-wider">Total Long-Term Liabilities</td>
                                <td class="px-4 py-2.5 text-right border-r border-rose-200 font-mono"></td>
                                <td class="px-4 py-2.5 text-right text-rose-800 font-mono">3,50,00,000</td>
                            </tr>
                            <tr class="bg-rose-100/60 font-black text-rose-950 uppercase border-b border-rose-200">
                                <td class="px-4 py-2.5 text-rose-700 border-r border-rose-200">TOTAL (B)</td>
                                <td class="px-4 py-2.5 border-r border-rose-200 uppercase tracking-wider">TOTAL LIABILITIES</td>
                                <td class="px-4 py-2.5 text-right border-r border-rose-200 font-mono"></td>
                                <td class="px-4 py-2.5 text-right text-rose-900 font-mono">4,57,00,000</td>
                            </tr>

                            {{-- 3000 EQUITY & PARTNER CAPITAL --}}
                            <tr class="bg-purple-50/40">
                                <td class="px-4 py-2 font-black text-purple-700 border-r border-slate-200">3000</td>
                                <td colspan="3" class="px-4 py-2 font-black text-purple-700 uppercase tracking-wider">EQUITY & PARTNER CAPITAL</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">3001</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Partner A Capital Account (60% Share)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">85,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">3002</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Partner B Capital Account (40% Share)</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">57,00,000</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">3010</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Retained Earnings / Accumulated Profit from P&L</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono">56,25,000</td>
                                <td class="px-4 py-2 text-right font-bold text-purple-700 font-mono">1,98,25,000</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-slate-500 border-r border-slate-200 pl-8">3020</td>
                                <td class="px-4 py-2 text-slate-700 border-r border-slate-200 font-bold">Less: Partner Drawings & Profit Distributions</td>
                                <td class="px-4 py-2 text-right border-r border-slate-200 font-bold font-mono text-rose-600">(0)</td>
                                <td class="px-4 py-2 text-right font-mono"></td>
                            </tr>
                            <tr class="bg-purple-50/70 border-y border-purple-200 font-black">
                                <td class="px-4 py-2.5 text-purple-800 border-r border-purple-200 uppercase">TOTAL (C)</td>
                                <td class="px-4 py-2.5 text-purple-800 border-r border-purple-200 uppercase tracking-wider">Total Equity & Owners' Reserves</td>
                                <td class="px-4 py-2.5 text-right border-r border-purple-200 font-mono"></td>
                                <td class="px-4 py-2.5 text-right text-purple-800 font-mono">1,98,25,000</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- CHECK TOTAL LIABILITIES & EQUITY BANNER ROW --}}
                <div class="bg-indigo-50 border-t-2 border-indigo-200 px-4 py-3 flex items-center justify-between font-black text-indigo-950 uppercase tracking-wider text-xs">
                    <div>
                        <span class="text-indigo-600 mr-2">CHECK</span>
                        <span>TOTAL LIABILITIES & OWUITY (B + C)</span>
                    </div>
                    <span class="font-mono text-indigo-700 text-sm">6,55,25,000</span>
                </div>
            </div>

        </div>

        {{-- FOOTER SUMMARY CARDS (3 Columns) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            
            {{-- Card 1: Report Summary --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
                <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Report Summary</h4>
                <div class="space-y-2 text-xs font-bold text-slate-700 font-mono">
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="font-sans font-semibold text-slate-600">Total Assets</span>
                        <span class="text-slate-900">Rs. 6,55,25,000</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="font-sans font-semibold text-slate-600">Total Liabilities</span>
                        <span class="text-slate-900">Rs. 4,57,00,000</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="font-sans font-semibold text-slate-600">Total Equity</span>
                        <span class="text-slate-900">Rs. 1,98,25,000</span>
                    </div>
                </div>
                <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                    <span class="text-xs font-extrabold text-slate-800">Assets = Liabilities + Equity</span>
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        BALANCED
                    </span>
                </div>
            </div>

            {{-- Card 2: Notes --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-2">
                <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-2">Notes:</h4>
                <ol class="space-y-1.5 text-xs font-medium text-slate-600 list-decimal list-inside leading-relaxed">
                    <li>All amounts are in Indian Rupees (Rs.).</li>
                    <li>Figures shown are as per Accrual Basis of Accounting.</li>
                    <li>Click on any account head to view detailed ledger transactions.</li>
                </ol>
            </div>

            {{-- Card 3: Sign-Off / Verification Metadata --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3">
                <div class="space-y-2.5 text-xs text-slate-700">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="font-semibold text-slate-500 w-24">Prepared By</span>
                        <span class="text-slate-400">:</span>
                        <span class="font-extrabold text-slate-900">Finance Team</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-semibold text-slate-500 w-24">Prepared On</span>
                        <span class="text-slate-400">:</span>
                        <span class="font-bold text-slate-800 font-mono">{{ date('d/m/Y h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="font-semibold text-slate-500 w-24">Report Type</span>
                        <span class="text-slate-400">:</span>
                        <span class="font-bold text-slate-800">Balance Sheet (Standard Vertical)</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── HIDDEN EXCEL EXPORT TABLE ── --}}
        <div class="hidden" style="display: none;">
            <table id="balanceSheetExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt;">
                <colgroup>
                    <col width="140" style="width: 105pt;" />
                    <col width="320" style="width: 240pt;" />
                    <col width="160" style="width: 120pt;" />
                    <col width="180" style="width: 135pt;" />
                </colgroup>
                <thead>
                    <tr height="45" style="height: 45pt;">
                        <th colspan="4" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle;">
                            HINDUSTAN ERP: BALANCE SHEET (STATEMENT OF FINANCIAL POSITION)
                        </th>
                    </tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="4" bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle;">
                            ASSETS
                        </th>
                    </tr>
                    <tr height="35" style="height: 35pt;">
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">ACCOUNT CODE / CATEGORY</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">DESCRIPTION / ACCOUNT HEAD</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">AMOUNT (RS.)</th>
                        <th bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center;">TOTAL GROUP AMOUNT (RS.)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #059669;">1000</td><td colspan="3" style="font-weight: bold; color: #059669;">ASSETS</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #1d4ed8;">1100</td><td colspan="3" style="font-weight: bold; color: #1d4ed8;">CURRENT ASSETS</td></tr>
                    <tr height="24" style="height: 24pt;"><td>1101</td><td>Bank Balances (Karnataka Bank / HDFC Escrow)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">8500000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>1102</td><td>Site Petty Cash Box Balances</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">25000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>1110</td><td>Customer Receivables (Pending Installments Billed)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">11000000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>1120</td><td>Advance Payments to Contractors & Suppliers</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">1500000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>1130</td><td>Land & Construction Work-in-Progress (Unbilled Cost)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">35000000</td><td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">56025000</td></tr>
                    <tr height="28" style="height: 28pt; background-color: #d1fae5;"><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">SUBTOTAL</td><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">Total Current Assets</td><td></td><td bgcolor="#d1fae5" style="text-align: right; font-weight: bold; color: #065f46; mso-number-format: '\#\,\#\#0\.00';">56025000</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #1d4ed8;">1200</td><td colspan="3" style="font-weight: bold; color: #1d4ed8;">NON-CURRENT / FIXED ASSETS</td></tr>
                    <tr height="24" style="height: 24pt;"><td>1201</td><td>Site Machinery, JCB & Heavy Equipment</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">4000000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>1210</td><td>Corporate Office Property & Vehicles</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">6000000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>1220</td><td>Computer Hardware & ERP Software Licenses</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">500000</td><td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">9500000</td></tr>
                    <tr height="24" style="height: 24pt;"><td>1290</td><td>Less: Accumulated Depreciation</td><td style="text-align: right; color: #e11d48; mso-number-format: '\#\,\#\#0\.00';">-1000000</td><td></td></tr>
                    <tr height="28" style="height: 28pt; background-color: #d1fae5;"><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">SUBTOTAL</td><td bgcolor="#d1fae5" style="font-weight: bold; color: #065f46;">Total Non-Current / Fixed Assets</td><td></td><td bgcolor="#d1fae5" style="text-align: right; font-weight: bold; color: #065f46; mso-number-format: '\#\,\#\#0\.00';">9500000</td></tr>
                    <tr height="30" style="height: 30pt; background-color: #e0e7ff;"><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">TOTAL (A)</td><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">TOTAL ASSETS</td><td></td><td bgcolor="#e0e7ff" style="text-align: right; font-weight: bold; color: #3730a3; mso-number-format: '\#\,\#\#0\.00';">65525000</td></tr>

                    <tr height="15" style="height: 15pt;"><td colspan="4" style="border: none;"></td></tr>
                    <tr height="30" style="height: 30pt;">
                        <th colspan="4" bgcolor="#a38c29" style="background-color: #a38c29; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle;">
                            LIABILITIES & EQUITY
                        </th>
                    </tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #e11d48;">2000</td><td colspan="3" style="font-weight: bold; color: #e11d48;">LIABILITIES</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #e11d48;">2100</td><td colspan="3" style="font-weight: bold; color: #e11d48;">CURRENT LIABILITIES & PAYABLES</td></tr>
                    <tr height="24" style="height: 24pt;"><td>2101</td><td>Contractor RA Work Bills Payable</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">4000000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>2110</td><td>Material Supplier & Vendor Payables</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">3000000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>2120</td><td>Statutory Payables (GST, TDS, Provident Fund)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">1200000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>2130</td><td>Advances Received from Customers (Unbilled)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">2500000</td><td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">10700000</td></tr>
                    <tr height="28" style="height: 28pt; background-color: #fee2e2;"><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">SUBTOTAL</td><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">Total Current Liabilities</td><td></td><td bgcolor="#fee2e2" style="text-align: right; font-weight: bold; color: #9f1239; mso-number-format: '\#\,\#\#0\.00';">10700000</td></tr>
                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #e11d48;">2200</td><td colspan="3" style="font-weight: bold; color: #e11d48;">NON-CURRENT / LONG-TERM LIABILITIES</td></tr>
                    <tr height="24" style="height: 24pt;"><td>2201</td><td>Bank Construction Loans / Project Credit Limits</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">20000000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>2210</td><td>Bank Land Acquisition Loan Accounts</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">15000000</td><td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">35000000</td></tr>
                    <tr height="28" style="height: 28pt; background-color: #fee2e2;"><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">SUBTOTAL</td><td bgcolor="#fee2e2" style="font-weight: bold; color: #9f1239;">Total Long-Term Liabilities</td><td></td><td bgcolor="#fee2e2" style="text-align: right; font-weight: bold; color: #9f1239; mso-number-format: '\#\,\#\#0\.00';">35000000</td></tr>
                    <tr height="28" style="height: 28pt; background-color: #ffe4e6;"><td bgcolor="#ffe4e6" style="font-weight: bold; color: #be123c;">TOTAL (B)</td><td bgcolor="#ffe4e6" style="font-weight: bold; color: #be123c;">TOTAL LIABILITIES</td><td></td><td bgcolor="#ffe4e6" style="text-align: right; font-weight: bold; color: #be123c; mso-number-format: '\#\,\#\#0\.00';">45700000</td></tr>

                    <tr height="25" style="height: 25pt;"><td style="font-weight: bold; color: #7c3aed;">3000</td><td colspan="3" style="font-weight: bold; color: #7c3aed;">EQUITY & PARTNER CAPITAL</td></tr>
                    <tr height="24" style="height: 24pt;"><td>3001</td><td>Partner A Capital Account (60% Share)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">8500000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>3002</td><td>Partner B Capital Account (40% Share)</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">5700000</td><td></td></tr>
                    <tr height="24" style="height: 24pt;"><td>3010</td><td>Retained Earnings / Accumulated Profit from P&L</td><td style="text-align: right; mso-number-format: '\#\,\#\#0\.00';">5625000</td><td style="text-align: right; font-weight: bold; mso-number-format: '\#\,\#\#0\.00';">19825000</td></tr>
                    <tr height="24" style="height: 24pt;"><td>3020</td><td>Less: Partner Drawings & Profit Distributions</td><td style="text-align: right; color: #e11d48; mso-number-format: '\#\,\#\#0\.00';">0</td><td></td></tr>
                    <tr height="28" style="height: 28pt; background-color: #f3e8ff;"><td bgcolor="#f3e8ff" style="font-weight: bold; color: #6b21a8;">TOTAL (C)</td><td bgcolor="#f3e8ff" style="font-weight: bold; color: #6b21a8;">Total Equity & Owners' Reserves</td><td></td><td bgcolor="#f3e8ff" style="text-align: right; font-weight: bold; color: #6b21a8; mso-number-format: '\#\,\#\#0\.00';">19825000</td></tr>
                    <tr height="30" style="height: 30pt; background-color: #e0e7ff;"><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">CHECK</td><td bgcolor="#e0e7ff" style="font-weight: bold; color: #3730a3;">TOTAL LIABILITIES & OWUITY (B + C)</td><td></td><td bgcolor="#e0e7ff" style="text-align: right; font-weight: bold; color: #3730a3; mso-number-format: '\#\,\#\#0\.00';">65525000</td></tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

</x-erp-layout>
