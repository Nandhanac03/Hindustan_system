@php
    $cancellationExportList = $cancellationCharges->map(function($charge) {
        $uDisplay = 'N/A';
        if ($charge->saleUnits && $charge->saleUnits->count() > 0) {
            $uDisplay = $charge->saleUnits->map(fn($su) => $su->unit ? $su->unit->formatted_name : '')->filter()->implode(', ');
        } elseif ($charge->unit) {
            $uDisplay = $charge->unit->formatted_name;
        }
        return [
            'sale_number' => $charge->sale_number ?? 'N/A',
            'customer_name' => $charge->customer->name ?? ($charge->customer_name ?? 'N/A'),
            'unit_display' => $uDisplay,
            'updated_at' => $charge->updated_at ? $charge->updated_at->format('d/m/Y') : 'N/A',
            'cancellation_fee' => (float)($charge->cancellation_fee ?? 0),
            'cancellation_reason' => $charge->cancellation_reason ?? 'Customer Request',
            'status' => $charge->status ?? 'cancelled',
            'search_text' => strtolower(($charge->sale_number ?? '') . ' ' . ($charge->customer->name ?? $charge->customer_name ?? '') . ' ' . $uDisplay . ' ' . ($charge->cancellation_reason ?? '')),
        ];
    });

    $additionalExportList = $additionalWorks->map(function($work) {
        $uDisplay = 'N/A';
        if ($work->sale && $work->sale->saleUnits && $work->sale->saleUnits->count() > 0) {
            $uDisplay = $work->sale->saleUnits->map(fn($su) => $su->unit ? $su->unit->formatted_name : '')->filter()->implode(', ');
        } elseif ($work->sale && $work->sale->unit) {
            $uDisplay = $work->sale->unit->formatted_name;
        }
        return [
            'sale_number' => $work->sale->sale_number ?? 'N/A',
            'customer_name' => $work->sale?->customer?->name ?? ($work->sale?->customer_name ?? 'N/A'),
            'unit_display' => $uDisplay,
            'description' => $work->description ?? 'N/A',
            'amount' => (float)($work->amount ?? 0),
            'created_at' => $work->created_at ? $work->created_at->format('d/m/Y') : 'N/A',
            'sale_status' => $work->sale->status ?? 'active',
            'search_text' => strtolower(($work->sale->sale_number ?? '') . ' ' . ($work->sale?->customer?->name ?? $work->sale?->customer_name ?? '') . ' ' . $uDisplay . ' ' . ($work->description ?? '')),
        ];
    });
@endphp

<x-erp-layout title="Cancellation Charges & Additional Work" headerTitle="Cancellation Charges & Additional Work">
    <div class="max-w-[1800px] mx-auto space-y-6" x-data="{ 
        activeTab: 'cancellation', 
        search: '', 
        status: '',
        allCancellationData: {{ Js::from($cancellationExportList) }},
        allAdditionalData: {{ Js::from($additionalExportList) }},
        printReport() { window.print(); },
        async exportExcel(type) {
            const isAdditional = (type === 'additional_work' || this.activeTab === 'additional');
            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'TABASCO Human Capital';
            workbook.lastModifiedBy = 'TABASCO ERP';
            workbook.created = new Date();
            workbook.modified = new Date();

            const searchLower = (this.search || '').toLowerCase().trim();
            const statusLower = (this.status || '').toLowerCase().trim();

            if (isAdditional) {
                let filteredData = (this.allAdditionalData || []).filter(item => {
                    const matchesSearch = !searchLower || (item.search_text && item.search_text.includes(searchLower));
                    const matchesStatus = !statusLower || (item.sale_status && item.sale_status.toLowerCase() === statusLower);
                    return matchesSearch && matchesStatus;
                });

                if (filteredData.length === 0) {
                    alert('No additional work records found to export.');
                    return;
                }

                buildAdditionalWorkWorksheet(
                    workbook,
                    'Additional Work',
                    'TABASCO  HUMAN CAPITAL   |   Additional Work (Master Directory)',
                    filteredData
                );

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'TABASCO_Additional_Work_Report.xlsx';
                a.click();
                window.URL.revokeObjectURL(url);
            } else {
                let filteredData = (this.allCancellationData || []).filter(item => {
                    const matchesSearch = !searchLower || (item.search_text && item.search_text.includes(searchLower));
                    const matchesStatus = !statusLower || (item.status && item.status.toLowerCase() === statusLower);
                    return matchesSearch && matchesStatus;
                });

                if (filteredData.length === 0) {
                    alert('No cancellation charges found to export.');
                    return;
                }

                buildCancellationWorksheet(
                    workbook,
                    'Cancellation Charges',
                    'TABASCO  HUMAN CAPITAL   |   Cancellation Charges (Master Directory)',
                    filteredData
                );

                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'TABASCO_Cancellation_Charges_Report.xlsx';
                a.click();
                window.URL.revokeObjectURL(url);
            }
        }
    }">
        <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>

        <script>
        function buildCancellationWorksheet(workbook, sheetName, bannerTitle, dataList) {
            if (!dataList || dataList.length === 0) return;

            const safeSheetName = (sheetName || 'Cancellation Charges').replace(/[\\/?*:[\]]/g, '').trim().substring(0, 31);
            const worksheet = workbook.addWorksheet(safeSheetName, {
                views: [{ showGridLines: true }]
            });

            worksheet.columns = [
                { header: 'SL NO', key: 'sl_no', width: 10 },
                { header: 'SALE / BOOKING NO.', key: 'sale_no', width: 26 },
                { header: 'CUSTOMER NAME', key: 'customer', width: 28 },
                { header: 'UNIT / PROPERTY', key: 'unit', width: 30 },
                { header: 'CANCELLATION DATE', key: 'date', width: 22 },
                { header: 'CANCELLATION FEE (₹)', key: 'fee', width: 24 },
                { header: 'REASON', key: 'reason', width: 32 },
                { header: 'STATUS', key: 'status', width: 16 }
            ];

            // 1. Top Title Banner Row (Row 1) - Dark Emerald #0B3B2E
            worksheet.spliceRows(1, 0, []);
            worksheet.mergeCells('A1:H1');
            const titleCell = worksheet.getCell('A1');
            titleCell.value = bannerTitle;
            titleCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
            worksheet.getRow(1).height = 36;

            ['A1','B1','C1','D1','E1','F1','G1','H1'].forEach(cellCoord => {
                worksheet.getCell(cellCoord).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            });

            // 2. Table Header Row (Row 2) - Brand Gold #A38C29
            const headerRow = worksheet.getRow(2);
            headerRow.height = 30;
            headerRow.values = [
                'SL NO',
                'SALE / BOOKING NO.',
                'CUSTOMER NAME',
                'UNIT / PROPERTY',
                'CANCELLATION DATE',
                'CANCELLATION FEE (₹)',
                'REASON',
                'STATUS'
            ];

            for (let col = 1; col <= 8; col++) {
                const cell = headerRow.getCell(col);
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF8A7522' } },
                    bottom: { style: 'medium', color: { argb: 'FF8A7522' } },
                    left: { style: 'thin', color: { argb: 'FF8A7522' } },
                    right: { style: 'thin', color: { argb: 'FF8A7522' } }
                };
            }

            // 3. Data Rows
            let currentRowIdx = 3;
            let totalFee = 0;

            dataList.forEach((item, idx) => {
                const row = worksheet.getRow(currentRowIdx);
                const feeVal = parseFloat(item.cancellation_fee || 0);
                totalFee += isNaN(feeVal) ? 0 : feeVal;

                const rawStatus = (item.status || 'cancelled').toLowerCase();
                const statusDisplay = rawStatus.toUpperCase();

                row.values = [
                    idx + 1,
                    item.sale_number || 'N/A',
                    item.customer_name || 'N/A',
                    item.unit_display || 'N/A',
                    item.updated_at || 'N/A',
                    feeVal,
                    item.cancellation_reason || 'Customer Request',
                    statusDisplay
                ];

                row.height = 26;
                const isLastRow = idx === dataList.length - 1;

                for (let col = 1; col <= 8; col++) {
                    const cell = row.getCell(col);
                    cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF1E293B' } };
                    cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: true };

                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        bottom: { style: isLastRow ? 'medium' : 'thin', color: isLastRow ? { argb: 'FFA38C29' } : { argb: 'FFE2E8F0' } },
                        left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
                    };

                    if (idx % 2 === 1) {
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFBFBFA' } };
                    }

                    if (col === 1) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF64748B' } };
                    }
                    if (col === 2) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };
                    }
                    if (col === 5) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    }
                    if (col === 6) {
                        cell.alignment = { horizontal: 'right', vertical: 'middle' };
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFA38C29' } };
                        cell.numFormat = '₹#,##0.00';
                    }
                    if (col === 8) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rawStatus === 'cancelled') {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFEE2E2' } };
                            cell.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FFBE123C' } };
                        } else if (rawStatus === 'active') {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDCFCE7' } };
                            cell.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FF047857' } };
                        } else {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE0E7FF' } };
                            cell.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FF4338CA' } };
                        }
                    }
                }
                currentRowIdx++;
            });

            // 4. Bottom Footer Banner Row (Row N+1) - Dark Emerald #0B3B2E
            const footerRowIdx = currentRowIdx;
            worksheet.mergeCells(`A${footerRowIdx}:H${footerRowIdx}`);
            const footCell = worksheet.getCell(`A${footerRowIdx}`);
            footCell.value = `Total Cancelled Bookings: ${dataList.length}   |   Total Cancellation Fees: ₹${totalFee.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            footCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            footCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            footCell.alignment = { horizontal: 'center', vertical: 'middle' };
            worksheet.getRow(footerRowIdx).height = 34;

            ['A','B','C','D','E','F','G','H'].forEach(c => {
                worksheet.getCell(`${c}${footerRowIdx}`).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            });
        }

        function buildAdditionalWorkWorksheet(workbook, sheetName, bannerTitle, dataList) {
            if (!dataList || dataList.length === 0) return;

            const safeSheetName = (sheetName || 'Additional Work').replace(/[\\/?*:[\]]/g, '').trim().substring(0, 31);
            const worksheet = workbook.addWorksheet(safeSheetName, {
                views: [{ showGridLines: true }]
            });

            worksheet.columns = [
                { header: 'SL NO', key: 'sl_no', width: 10 },
                { header: 'SALE / BOOKING NO.', key: 'sale_no', width: 26 },
                { header: 'CUSTOMER NAME', key: 'customer', width: 28 },
                { header: 'UNIT NO.', key: 'unit', width: 28 },
                { header: 'WORK DESCRIPTION', key: 'description', width: 36 },
                { header: 'AMOUNT (₹)', key: 'amount', width: 24 },
                { header: 'WORK DATE', key: 'date', width: 22 },
                { header: 'STATUS', key: 'status', width: 16 }
            ];

            // 1. Top Title Banner Row (Row 1) - Dark Emerald #0B3B2E
            worksheet.spliceRows(1, 0, []);
            worksheet.mergeCells('A1:H1');
            const titleCell = worksheet.getCell('A1');
            titleCell.value = bannerTitle;
            titleCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            titleCell.alignment = { horizontal: 'center', vertical: 'middle' };
            worksheet.getRow(1).height = 36;

            ['A1','B1','C1','D1','E1','F1','G1','H1'].forEach(cellCoord => {
                worksheet.getCell(cellCoord).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            });

            // 2. Table Header Row (Row 2) - Brand Gold #A38C29
            const headerRow = worksheet.getRow(2);
            headerRow.height = 30;
            headerRow.values = [
                'SL NO',
                'SALE / BOOKING NO.',
                'CUSTOMER NAME',
                'UNIT NO.',
                'WORK DESCRIPTION',
                'AMOUNT (₹)',
                'WORK DATE',
                'STATUS'
            ];

            for (let col = 1; col <= 8; col++) {
                const cell = headerRow.getCell(col);
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFA38C29' } };
                cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
                cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF8A7522' } },
                    bottom: { style: 'medium', color: { argb: 'FF8A7522' } },
                    left: { style: 'thin', color: { argb: 'FF8A7522' } },
                    right: { style: 'thin', color: { argb: 'FF8A7522' } }
                };
            }

            // 3. Data Rows
            let currentRowIdx = 3;
            let totalAmount = 0;

            dataList.forEach((item, idx) => {
                const row = worksheet.getRow(currentRowIdx);
                const amountVal = parseFloat(item.amount || 0);
                totalAmount += isNaN(amountVal) ? 0 : amountVal;

                const rawStatus = (item.sale_status || 'active').toLowerCase();
                const statusDisplay = rawStatus.toUpperCase();

                row.values = [
                    idx + 1,
                    item.sale_number || 'N/A',
                    item.customer_name || 'N/A',
                    item.unit_display || 'N/A',
                    item.description || 'N/A',
                    amountVal,
                    item.created_at || 'N/A',
                    statusDisplay
                ];

                row.height = 26;
                const isLastRow = idx === dataList.length - 1;

                for (let col = 1; col <= 8; col++) {
                    const cell = row.getCell(col);
                    cell.font = { name: 'Calibri', size: 10, color: { argb: 'FF1E293B' } };
                    cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: true };

                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        bottom: { style: isLastRow ? 'medium' : 'thin', color: isLastRow ? { argb: 'FFA38C29' } : { argb: 'FFE2E8F0' } },
                        left: { style: 'thin', color: { argb: 'FFE2E8F0' } },
                        right: { style: 'thin', color: { argb: 'FFE2E8F0' } }
                    };

                    if (idx % 2 === 1) {
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFBFBFA' } };
                    }

                    if (col === 1) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF64748B' } };
                    }
                    if (col === 2) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF0F172A' } };
                    }
                    if (col === 6) {
                        cell.alignment = { horizontal: 'right', vertical: 'middle' };
                        cell.font = { name: 'Calibri', size: 10, bold: true, color: { argb: 'FF047857' } };
                        cell.numFormat = '₹#,##0.00';
                    }
                    if (col === 7) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    }
                    if (col === 8) {
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rawStatus === 'cancelled') {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFEE2E2' } };
                            cell.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FFBE123C' } };
                        } else if (rawStatus === 'active') {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDCFCE7' } };
                            cell.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FF047857' } };
                        } else {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE0E7FF' } };
                            cell.font = { name: 'Calibri', size: 9.5, bold: true, color: { argb: 'FF4338CA' } };
                        }
                    }
                }
                currentRowIdx++;
            });

            // 4. Bottom Footer Banner Row (Row N+1) - Dark Emerald #0B3B2E
            const footerRowIdx = currentRowIdx;
            worksheet.mergeCells(`A${footerRowIdx}:H${footerRowIdx}`);
            const footCell = worksheet.getCell(`A${footerRowIdx}`);
            footCell.value = `Total Work Orders: ${dataList.length}   |   Total Additional Work Amount: ₹${totalAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            footCell.font = { name: 'Calibri', size: 12, bold: true, color: { argb: 'FFFFFFFF' } };
            footCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            footCell.alignment = { horizontal: 'center', vertical: 'middle' };
            worksheet.getRow(footerRowIdx).height = 34;

            ['A','B','C','D','E','F','G','H'].forEach(c => {
                worksheet.getCell(`${c}${footerRowIdx}`).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF0B3B2E' } };
            });
        }
        </script>
        
        {{-- Header & Breadcrumb (Treasury Style) --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Cancellation Charges & Additional Work</h1>
                <p class="text-xs text-slate-500 mt-1">Home / Sales & Property / Cancellation & Additional Work</p>
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

        {{-- Ultra-Clean Modern Light Search & Filter Panel (Zero-Reload Reactive) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm transition-all mb-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
                    {{-- Search Input --}}
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" placeholder="Search Customer/Unit/Sale No..." 
                               x-model="search"
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-250 hover:border-[#a38c29]/60 focus:border-[#a38c29] focus:ring-2 focus:ring-[#a38c29]/20 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                        
                        {{-- Clear Button --}}
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                            <button type="button" x-show="search" @click="search = ''"
                                    class="p-1 rounded-md bg-slate-200/70 hover:bg-rose-500 hover:text-white text-slate-600 transition cursor-pointer" title="Clear Search">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/></svg>
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
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#a38c29] to-[#8a7522] hover:from-[#8a7522] hover:to-[#73611b] px-6 py-2.5 text-xs font-extrabold text-white shadow-sm shadow-[#a38c29]/30 hover:shadow-md transition-all duration-200 flex-shrink-0 uppercase tracking-wider group active:scale-95 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-white transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>RESET FILTERS</span>
                </button>
            </div>
        </div>

        {{-- Premium Segmented Navigation Tabs & Action Bar (Under Filter) --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 mb-4">
            <div class="bg-white p-1.5 rounded-2xl border border-slate-200/90 shadow-sm inline-flex items-center gap-1.5 max-w-full overflow-x-auto">
                {{-- Tab 1: Cancellation Charges --}}
                <button type="button" @click="activeTab = 'cancellation'" 
                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 flex items-center gap-2.5 cursor-pointer relative group active:scale-95"
                        :class="activeTab === 'cancellation' 
                            ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                            : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                    <div class="w-5 h-5 rounded-lg flex items-center justify-center transition-colors"
                         :class="activeTab === 'cancellation' ? 'bg-white/20 text-white' : 'bg-slate-200/60 text-slate-500 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span>Cancellation Charges</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider transition-colors"
                          :class="activeTab === 'cancellation' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-[#a38c29]/15 group-hover:text-[#a38c29]'">
                        {{ count($cancellationCharges) }}
                    </span>
                </button>

                {{-- Tab 2: Additional Work --}}
                <button type="button" @click="activeTab = 'additional'" 
                        class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 flex items-center gap-2.5 cursor-pointer relative group active:scale-95"
                        :class="activeTab === 'additional' 
                            ? 'bg-gradient-to-r from-[#a38c29] to-[#8a7522] text-white shadow-md shadow-[#a38c29]/25' 
                            : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'">
                    <div class="w-5 h-5 rounded-lg flex items-center justify-center transition-colors"
                         :class="activeTab === 'additional' ? 'bg-white/20 text-white' : 'bg-slate-200/60 text-slate-500 group-hover:bg-[#a38c29]/10 group-hover:text-[#a38c29]'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span>Additional Work</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider transition-colors"
                          :class="activeTab === 'additional' ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-[#a38c29]/15 group-hover:text-[#a38c29]'">
                        {{ count($additionalWorks) }}
                    </span>
                </button>
            </div>

            {{-- Export Excel Button (Same row as tabs) --}}
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" 
                        @click="exportExcel(activeTab === 'additional' ? 'additional_work' : 'cancellation_charges')"
                        class="h-[42px] px-5 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-xs font-black rounded-xl transition shadow hover:shadow-md flex items-center gap-2.5 uppercase tracking-wider cursor-pointer group">
                    <svg class="w-4 h-4 text-white group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Export Excel</span>
                </button>
            </div>
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
                                $cCustomerName = $charge->customer->name ?? $charge->customer_name ?? '';
                                $cSaleNumber = $charge->sale_number ?? '';
                                $cReason = $charge->cancellation_reason ?? '';
                                $cSearchTerms = strtolower($cCustomerName . ' ' . $cSaleNumber . ' ' . $chargeUnitDisplay . ' ' . $cReason);
                            @endphp
                            <tr class="hover:bg-slate-50 transition group"
                                data-status="{{ strtolower($charge->status ?? '') }}"
                                x-show="(search.trim() === '' || 
                                    '{{ addslashes(strtolower($cCustomerName)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes(strtolower($cSaleNumber)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes(strtolower($chargeUnitDisplay)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes(strtolower($cReason)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes($cSearchTerms) }}'.includes(search.trim().toLowerCase())
                                ) && (status === '' || '{{ strtolower($charge->status ?? '') }}' === status.toLowerCase())">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $charge->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $charge->customer->name ?? ($charge->customer_name ?? 'N/A') }}</td>
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
                                $wCustomerName = $work->sale?->customer?->name ?? $work->sale?->customer_name ?? '';
                                $wSaleNumber = $work->sale?->sale_number ?? '';
                                $wDescription = $work->description ?? '';
                                $wSearchTerms = strtolower($wCustomerName . ' ' . $wSaleNumber . ' ' . $workUnitDisplay . ' ' . $wDescription);
                            @endphp
                            <tr class="hover:bg-slate-50 transition group"
                                data-status="{{ strtolower($work->sale->status ?? '') }}"
                                x-show="(search.trim() === '' || 
                                    '{{ addslashes(strtolower($wCustomerName)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes(strtolower($wSaleNumber)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes(strtolower($workUnitDisplay)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes(strtolower($wDescription)) }}'.includes(search.trim().toLowerCase()) || 
                                    '{{ addslashes($wSearchTerms) }}'.includes(search.trim().toLowerCase())
                                ) && (status === '' || '{{ strtolower($work->sale->status ?? '') }}' === status.toLowerCase())">
                                <td class="px-5 py-3 text-center text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 text-xs font-black text-slate-800 uppercase tracking-wide">{{ $work->sale->sale_number ?? 'N/A' }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-500">{{ $work->sale->customer->name ?? ($work->sale->customer_name ?? 'N/A') }}</td>
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

    </div>
</x-erp-layout>
