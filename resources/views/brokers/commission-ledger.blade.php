<x-erp-layout title="Commission Ledger" headerTitle="Commission Ledger">

<div class="max-w-[1800px] mx-auto space-y-6">

    {{-- Top Header & Title --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#a38c29]/10 text-[#a38c29] font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v8m-6 0h6"/></svg>
                </span>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Commission Ledger</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Real-time visibility into every property deal, commission amount, and payment completion status.</p>
        </div>
    </div>

    {{-- Key Metrics KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1: Accrued (Locked) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-amber-500 p-6 flex flex-col justify-between relative overflow-hidden group hover:border-amber-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.15)]">
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/60 transition-all duration-300 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Accrued (Locked)</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2.5 py-1 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-amber-300 group-hover:text-amber-700 group-hover:bg-amber-50/50">Pending</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-amber-700 transition-colors duration-300">₹{{ number_format($totalAccrued, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Payable only after full payment or EMI completion</p>
            </div>
        </div>

        {{-- Card 2: Payable (Unlocked) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-emerald-500 p-6 flex flex-col justify-between relative overflow-hidden group hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.15)]">
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100/60 transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Payable (Unlocked)</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2.5 py-1 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-emerald-300 group-hover:text-emerald-700 group-hover:bg-emerald-50/50">Ready for Disbursement</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-emerald-700 transition-colors duration-300">₹{{ number_format($totalPayable, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">100% payment / EMI cleared</p>
            </div>
        </div>

        {{-- Card 3: Paid Commission --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 border-l-[6px] border-l-indigo-500 p-6 flex flex-col justify-between relative overflow-hidden group hover:border-indigo-200 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_10px_40px_-10px_rgba(99,102,241,0.15)]">
            
            <div class="flex items-center justify-between mb-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/60 transition-all duration-300 group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-md group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider">Paid Commission</span>
                </div>
                <span class="text-[9px] text-slate-500 font-bold bg-white px-2.5 py-1 rounded-md border border-slate-200 uppercase tracking-wider shadow-sm transition-all duration-300 group-hover:border-indigo-300 group-hover:text-indigo-700 group-hover:bg-indigo-50/50">Settled</span>
            </div>
            
            <div class="relative z-10 mt-2">
                <span class="text-3xl font-black text-slate-800 font-mono tracking-tight block group-hover:text-indigo-700 transition-colors duration-300">₹{{ number_format($totalPaid, 2) }}</span>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Successfully settled across all broker accounts</p>
            </div>
        </div>
    </div>

    {{-- Transaction-wise Commission Visibility Section --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Transaction-wise Commission Visibility</h2>
                <p class="text-[10px] text-slate-450 mt-0.5">Real-time visibility into every property deal, commission amount, and payment completion status.</p>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('brokers.commission-ledger') }}" class="flex items-center gap-2">
                <select name="broker_id" onchange="this.form.submit()"
                        class="px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none shadow-2xs font-semibold">
                    <option value="">All Brokers</option>
                    @foreach($brokers as $b)
                        <option value="{{ $b->id }}" {{ request('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
                @if(request('broker_id'))
                    <a href="{{ route('brokers.commission-ledger') }}" class="text-[10px] text-slate-400 hover:text-slate-700 font-bold underline">Clear</a>
                @endif
            </form>
        </div>

        <style>
            .broker-table thead th { border-color: #8a7522 !important; }
            .broker-tbody tr:nth-child(even) { background-color: #F6F3E9 !important; }
            .broker-tbody tr:hover { background-color: #ebe5d0 !important; }
        </style>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left min-w-[1100px] broker-table border-collapse">
                <thead>
                    <tr class="bg-[#a38c29] text-white border-b border-[#8a7522] text-center font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-3 py-3 border">Booking & Date</th>
                        <th class="px-3 py-3 border">Property / Project</th>
                        <th class="px-3 py-3 border">Broker / Agent</th>
                        <th class="px-3 py-3 border">Net Sale Value</th>
                        <th class="px-3 py-3 border">Commission Calc</th>
                        <th class="px-3 py-3 border">Payment Progress</th>
                        <th class="px-3 py-3 border">Commission Status</th>
                        <th class="px-3 py-3 border">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 broker-tbody">
                    @forelse($deals as $brokerage)
                        @php
                            $sale = $brokerage->sale;
                            if (!$sale) continue;

                            $paidAmt    = (float)($brokerage->paid_amount ?? 0);
                            $commAmt    = (float)($brokerage->commission_amount ?? 0);
                            $commAmount = $commAmt;
                            $remaining  = max(0, $commAmt - $paidAmt);

                            $rawStatus = $brokerage->status ?? 'pending';
                            $effectiveStatus = match(true) {
                                $paidAmt >= $commAmt - 0.01 && $commAmt > 0 => 'paid',
                                $paidAmt > 0 => 'partial',
                                default => $rawStatus
                            };

                            $badgeClass = match($effectiveStatus) {
                                'payable'  => 'bg-emerald-50 text-emerald-700 border-emerald-200 shadow-2xs font-bold',
                                'partial'  => 'bg-blue-50 text-blue-700 border-blue-200 font-bold',
                                'paid'     => 'bg-indigo-50 text-indigo-700 border-indigo-200 font-bold',
                                default    => 'bg-amber-50 text-amber-700 border-amber-200 font-semibold'
                            };

                            $statusLabel = match($effectiveStatus) {
                                'payable'  => 'Payable (Unlocked)',
                                'partial'  => 'Partially Paid',
                                'paid'     => 'Paid Out',
                                default    => 'Accrued (Locked)'
                            };

                            $receiptPayload = [
                                'voucher_no'      => 'COM-' . str_pad($brokerage->id ?? 1, 5, '0', STR_PAD_LEFT),
                                'booking_no'      => $sale->sale_number ?? 'N/A',
                                'date'            => $sale->sale_date ? $sale->sale_date->format('d M Y') : now()->format('d M Y'),
                                'broker_name'     => $brokerage->broker->name ?? 'Direct Agent',
                                'broker_phone'    => $brokerage->broker->phone ?? $brokerage->broker->mobile ?? '—',
                                'customer_name'   => $sale->customer->name ?? 'Customer',
                                'project_name'    => $sale->project->name ?? '—',
                                'unit_door'       => $sale->unit->door_no ?? '—',
                                'sale_value'      => (float)($sale->total_amount ?? 0),
                                'comm_percent'    => (float)($brokerage->commission_percent ?? ($brokerage->broker->default_commission_pct ?? 0)),
                                'comm_amount'     => (float)$commAmount,
                                'paid_amount'     => (float)$paidAmt,
                                'remaining_amt'   => (float)$remaining,
                                'status_label'    => $statusLabel,
                                'effective_status'=> $effectiveStatus,
                                'payment_mode'    => $paidAmt > 0 ? 'Bank Transfer / Commission Payout' : 'Commission Allocation',
                                'narration'       => 'Broker commission against booking ' . ($sale->sale_number ?? '') . ' (' . ($sale->project->name ?? '') . ' - ' . ($sale->unit->door_no ?? '') . ')'
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-3 py-4 border text-center">
                                <div class="font-bold text-[#a38c29] font-mono">{{ $sale->sale_number ?? 'N/A' }}</div>
                                <div class="text-[9px] text-slate-500 mt-0.5">{{ $sale->sale_date ? $sale->sale_date->format('d M Y') : 'N/A' }}</div>
                                <div class="text-[10px] font-semibold text-slate-800 mt-0.5">{{ $sale->customer->name ?? 'Customer' }}</div>
                            </td>
                            <td class="px-3 py-4 border text-center">
                                <div class="font-bold text-slate-900">{{ $sale->project->name ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-600 mt-0.5">Unit: <span class="font-bold text-slate-800 font-mono">{{ $sale->unit->door_no ?? 'N/A' }}</span></div>
                            </td>
                            <td class="px-3 py-4 border text-center">
                                <div class="font-bold text-slate-800">{{ $brokerage->broker->name ?? 'Direct' }}</div>
                                <div class="text-[9px] text-slate-500 font-mono mt-0.5">Rate: {{ number_format($brokerage->commission_percent ?? $brokerage->broker->default_commission_pct ?? 0, 2) }}%</div>
                            </td>
                            <td class="px-3 py-4 border text-center font-mono font-bold text-slate-900">
                                ₹{{ number_format($sale->total_amount ?? 0, 2) }}
                            </td>
                            <td class="px-3 py-4 border text-center">
                                <div class="font-mono font-black text-slate-900 text-sm">₹{{ number_format($commAmount, 2) }}</div>
                                @if($brokerage->commission_percent)
                                <div class="text-[9px] text-slate-500 uppercase mt-0.5">@ {{ number_format($brokerage->commission_percent, 2) }}% of sale</div>
                                @endif
                            </td>
                            <td class="px-3 py-4 border text-center">
                                @if($sale)
                                    @if($sale->remaining_balance <= 0)
                                        <span class="inline-flex items-center justify-center gap-1 text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded border border-emerald-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            100% Paid / EMI Complete
                                        </span>
                                    @else
                                        <div class="space-y-1 mx-auto max-w-[120px]">
                                            <div class="flex justify-between text-[10px]">
                                                <span class="text-slate-600 font-semibold">Pending Bal.</span>
                                                <span class="font-mono font-bold text-rose-700">₹{{ number_format($sale->remaining_balance, 2) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden border border-slate-300">
                                                @php
                                                    $pctPaid = $sale->total_amount > 0 ? (($sale->total_amount - $sale->remaining_balance) / $sale->total_amount) * 100 : 0;
                                                @endphp
                                                <div class="bg-[#a38c29] h-full rounded-full" style="width: {{ min(100, max(0, $pctPaid)) }}%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-500 block text-center">{{ number_format($pctPaid, 0) }}% collected</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-500 italic text-[10px]">N/A</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 border text-center align-middle">
                                <div class="flex flex-col items-center justify-center gap-2 w-[140px] mx-auto">
                                    <span class="w-full border px-2 py-1.5 rounded-xl font-bold text-[9px] uppercase {{ $badgeClass }} shadow-sm tracking-wide text-center">
                                        {{ $statusLabel }}
                                    </span>
                                    
                                    @if($effectiveStatus === 'pending')
                                        <span class="text-[9px] text-slate-400 italic text-center w-full">Unlocks on full payment</span>
                                    @elseif($effectiveStatus === 'payable')
                                        <span class="text-[9px] text-slate-500 italic text-center w-full">Settled via Receipt Allocation</span>
                                    @elseif($effectiveStatus === 'partial')
                                        <div class="w-full space-y-1">
                                            <div class="flex justify-between text-[9px]">
                                                <span class="text-blue-600 font-bold">Paid: ₹{{ number_format($paidAmt, 0) }}</span>
                                                <span class="text-rose-600 font-bold">Bal: ₹{{ number_format($remaining, 0) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                @php $pctPaidComm = $commAmt > 0 ? ($paidAmt / $commAmt) * 100 : 0; @endphp
                                                <div class="bg-blue-500 h-full rounded-full" style="width: {{ min(100, $pctPaidComm) }}%;"></div>
                                            </div>
                                            <span class="text-[9px] text-blue-600 font-bold block text-center">{{ number_format($pctPaidComm, 0) }}% paid</span>
                                        </div>
                                    @elseif($effectiveStatus === 'paid')
                                        <span class="text-[9px] text-indigo-600 font-bold text-center w-full">✓ Fully settled ₹{{ number_format($commAmt, 0) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-4 border text-center align-middle whitespace-nowrap">
                                <button type="button" 
                                        onclick="downloadBrokerReceiptPdf({{ json_encode($receiptPayload) }})" 
                                        title="Download Commission Receipt Voucher"
                                        class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] border border-[#09876B]/20 hover:border-[#09876B]/40 transition inline-flex items-center justify-center shadow-sm cursor-pointer"
                                        aria-label="Download Commission Receipt Voucher">
                                    <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-2-2m2 2l2-2"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-12 border text-center text-slate-500 italic">No broker sales or transactions recorded yet. When sales are registered with a broker, commission entries will appear here automatically.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deals->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $deals->links() }}
            </div>
        @endif
    </div>

</div>

<script>
function downloadBrokerReceiptPdf(data) {
    if (!data) return;

    const escapeHtml = (str) => {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const numToWords = (num) => {
        if (typeof window.convertNumberToWords === 'function') {
            return window.convertNumberToWords(num);
        }
        const a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
        const b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
        const n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return '';
        let str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
        return str.trim();
    };

    const commNum = Number(data.comm_amount || 0);
    const amountFormatted = '₹' + commNum.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    let amountWords = numToWords(commNum);
    if (amountWords) {
        amountWords = amountWords.trim();
        if (amountWords.toLowerCase().endsWith('only')) {
            amountWords = amountWords.slice(0, -4).trim();
        }
        amountWords = amountWords.charAt(0).toUpperCase() + amountWords.slice(1) + ' Only';
    }

    const refNo = escapeHtml(data.voucher_no || 'COM-00001');
    const bookingNo = escapeHtml(data.booking_no || '—');
    const dateVal = escapeHtml(data.date || '—');
    const brokerName = escapeHtml(data.broker_name || 'Broker');
    const brokerPhone = escapeHtml(data.broker_phone || '—');
    const customerName = escapeHtml(data.customer_name || 'Customer');
    const projectName = escapeHtml(data.project_name || '—');
    const unitDoor = escapeHtml(data.unit_door || '—');
    const saleValFormatted = '₹' + Number(data.sale_value || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const commPctFormatted = Number(data.comm_percent || 0).toFixed(2) + '%';
    const statusLabel = escapeHtml(data.status_label || 'Accrued');
    const companyName = 'TABASCO HINDUSTAN INFRA DEVELOPERS PVT. LTD.';

    const printWin = window.open('', '_blank', 'width=940,height=960,top=30,left=100');
    if (!printWin) {
        alert('Please allow popups to preview and download the commission receipt voucher PDF.');
        return;
    }

    const html = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Voucher — ${refNo}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    ` + '<scr' + 'ipt src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></scr' + 'ipt>' + `
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 24px 16px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* Top Page Navigation Bar */
        .top-nav {
            max-width: 860px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .nav-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .nav-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-download {
            background: linear-gradient(135deg, #d4af37 0%, #a38c29 100%);
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            padding: 9px 18px;
            border-radius: 8px;
            border: 1px solid #8f7922;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(163, 140, 41, 0.25);
            transition: all 0.2s ease;
        }
        .btn-download:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }
        .btn-close {
            background: #ffffff;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-close:hover {
            background: #f1f5f9;
        }

        /* White Receipt Sheet Card */
        .receipt-card {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            border: 1.5px solid #e2dcd0;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 6px 24px -4px rgba(15, 23, 42, 0.08);
        }

        /* Sleek Slate Header Banner */
        .company-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 0;
            padding: 22px 28px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            border-bottom: 1.5px solid #334155;
        }
        .hero-left {
            display: flex;
            align-items: center;
            max-width: 65%;
        }
        .company-name {
            font-size: 15.5px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            line-height: 1.35;
        }
        .hero-right {
            text-align: right;
        }
        .receipt-pill-title {
            font-size: 15px;
            font-weight: 900;
            color: #e2b855;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .receipt-no-row {
            margin-top: 4px;
            font-size: 12.5px;
        }
        .no-lbl {
            color: #94a3b8;
            font-weight: 500;
            margin-right: 6px;
        }
        .no-val {
            color: #ffffff;
            font-weight: 900;
            font-size: 14.5px;
        }

        /* Inner Receipt Padding Container */
        .receipt-body {
            padding: 22px 24px;
        }

        /* 3 Metadata Horizontal Strip */
        .meta-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background: #fcfbf8;
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 18px;
        }
        .meta-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .meta-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }
        .meta-label {
            font-size: 8.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 2 Column Details Grid */
        .grid-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 18px;
        }
        .detail-box {
            border: 1.5px solid #ebe5d8;
            border-radius: 14px;
            overflow: hidden;
            background: #ffffff;
        }
        .box-head {
            background: #fcfbf8;
            padding: 10px 16px;
            font-size: 11.5px;
            font-weight: 800;
            color: #8c733e;
            border-bottom: 1.5px solid #ebe5d8;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .box-body {
            padding: 8px 16px;
        }
        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f6f2ea;
            font-size: 11.5px;
        }
        .field-row:last-child {
            border-bottom: none;
        }
        .f-lbl {
            color: #64748b;
            font-weight: 600;
            font-size: 11px;
        }
        .f-val {
            color: #0f172a;
            font-weight: 800;
            text-align: right;
            max-width: 62%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Metallic Golden Amount Banner */
        .amount-banner {
            background: linear-gradient(135deg, #dfb858 0%, #fae69e 45%, #d1a038 100%);
            border: 1.5px solid #c99b32;
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
            box-shadow: 0 4px 12px rgba(184, 138, 37, 0.18);
        }
        .amount-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }
        .coin-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #fff2a8, #d4af37 45%, #96741b 85%, #634d10 100%);
            border: 1.5px solid #ffea88;
            box-shadow: 0 4px 8px rgba(150, 116, 27, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .coin-inner {
            font-size: 21px;
            font-weight: 900;
            color: #4a3809;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8), 0 -1px 1px rgba(0, 0, 0, 0.4);
        }
        .amt-words-lbl {
            font-size: 10px;
            font-weight: 900;
            color: #45340e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amt-words-val {
            font-size: 12.5px;
            font-weight: 800;
            color: #1c1505;
            font-style: italic;
            margin-top: 2px;
            line-height: 1.35;
        }
        .amount-divider {
            width: 1.5px;
            height: 42px;
            background: #a98020;
            margin: 0 20px;
            flex-shrink: 0;
        }
        .amount-right {
            text-align: right;
            flex-shrink: 0;
        }
        .amt-total-lbl {
            font-size: 10px;
            font-weight: 900;
            color: #45340e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .amt-total-val {
            font-size: 25px;
            font-weight: 900;
            color: #110e05;
            margin-top: 2px;
            letter-spacing: -0.5px;
        }

        /* Print Media Styling */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: #ffffff;
                padding: 0;
            }
            .top-nav {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none;
                border: 1.5px solid #ebe5d8;
                border-radius: 14px;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="top-nav">
        <div class="nav-left">
            <div class="nav-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div>
                <h1 class="nav-title">Commission Voucher</h1>
                <p class="nav-sub">View and download broker commission receipt voucher</p>
            </div>
        </div>
        <div class="nav-actions">
            <button onclick="downloadDirectPdf()" class="btn-download">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Download PDF</span>
            </button>
            <button onclick="window.close()" class="btn-close">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="receipt-card">
        <div class="company-hero">
            <div class="hero-left">
                <div class="company-name">${companyName}</div>
            </div>

            <div class="hero-right">
                <div class="receipt-pill-title">COMMISSION VOUCHER</div>
                <div class="receipt-no-row">
                    <span class="no-lbl">Voucher No.</span>
                    <span class="no-val mono">${refNo}</span>
                </div>
            </div>
        </div>

        <div class="receipt-body">
            <div class="meta-strip">
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#ecfdf5; color:#059669;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">VOUCHER NUMBER</span>
                        <span class="meta-value mono">${refNo}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#fff1f2; color:#f43f5e;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">BOOKING DATE</span>
                        <span class="meta-value">${dateVal}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-icon-circle" style="background:#fffbeb; color:#d97706;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <span class="meta-label">COMMISSION STATUS</span>
                        <span class="meta-value">${statusLabel}</span>
                    </div>
                </div>
            </div>

            <div class="grid-details">
                <div class="detail-box">
                    <div class="box-head">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8c733e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Broker &amp; Agent Information</span>
                    </div>
                    <div class="box-body">
                        <div class="field-row">
                            <span class="f-lbl">Broker / Agent Name</span>
                            <span class="f-val">${brokerName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Contact Number</span>
                            <span class="f-val">${brokerPhone}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Booking Reference</span>
                            <span class="f-val mono">${bookingNo}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Customer Name</span>
                            <span class="f-val">${customerName}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-box">
                    <div class="box-head">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8c733e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="5 10 12 3 19 10"></polyline><line x1="6" y1="10" x2="6" y2="21"></line><line x1="10" y1="10" x2="10" y2="21"></line><line x1="14" y1="10" x2="14" y2="21"></line><line x1="18" y1="10" x2="18" y2="21"></line></svg>
                        <span>Property &amp; Deal Calculations</span>
                    </div>
                    <div class="box-body">
                        <div class="field-row">
                            <span class="f-lbl">Project / Property</span>
                            <span class="f-val">${projectName}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Unit / Door No.</span>
                            <span class="f-val mono">${unitDoor}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Net Sale Value</span>
                            <span class="f-val mono">${saleValFormatted}</span>
                        </div>
                        <div class="field-row">
                            <span class="f-lbl">Commission Rate</span>
                            <span class="f-val mono font-bold text-amber-800">${commPctFormatted}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="amount-banner">
                <div class="amount-left">
                    <div class="coin-badge">
                        <div class="coin-inner">₹</div>
                    </div>
                    <div>
                        <div class="amt-words-lbl">Total Commission Amount (in Words)</div>
                        <div class="amt-words-val">${amountWords || '—'}</div>
                    </div>
                </div>
                <div class="amount-divider"></div>
                <div class="amount-right">
                    <div class="amt-total-lbl">Commission Payable</div>
                    <div class="amt-total-val mono">${amountFormatted}</div>
                </div>
            </div>
        </div>
    </div>

    ` + '<scr' + 'ipt>' + `
    function downloadDirectPdf() {
        const element = document.querySelector('.receipt-card');
        const btn = document.querySelector('.btn-download');
        if (!element) return;

        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Downloading...</span>';
        btn.disabled = true;

        const opt = {
            margin: [8, 8, 8, 8],
            filename: 'Commission_Voucher_${refNo}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        if (typeof html2pdf !== 'undefined') {
            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = '<span>✓ Downloaded</span>';
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }, 2500);
            }).catch(err => {
                console.error('PDF Error:', err);
                alert('Could not render PDF directly. Opening print dialog...');
                window.print();
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        } else {
            window.print();
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    }
    ` + '</scr' + 'ipt>' + `
</body>
</html>`;

    printWin.document.open();
    printWin.document.write(html);
    printWin.document.close();
}
</script>

</x-erp-layout>

