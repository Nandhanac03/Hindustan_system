<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher — {{ $voucher->voucher_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            background: #f4f6f8;
            padding: 24px;
        }

        .page {
            width: 210mm;
            min-height: 148mm;
            margin: 0 auto;
            padding: 16mm 18mm;
            background: #ffffff;
            border: 2px solid #2d3a1e;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #2d3a1e;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .company-name {
            font-size: 20pt;
            font-weight: bold;
            color: #1a3a1a;
            letter-spacing: 1px;
        }
        .company-sub {
            font-size: 9pt;
            color: #555;
            margin-top: 2px;
        }
        .voucher-title-box {
            text-align: right;
        }
        .voucher-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1a3a1a;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .voucher-number {
            font-size: 11pt;
            font-weight: bold;
            color: #333;
            margin-top: 4px;
            font-family: 'Courier New', Courier, monospace;
            background: #f0f0e8;
            padding: 3px 10px;
            border: 1px solid #ccc;
            display: inline-block;
        }

        /* Meta row */
        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 16px;
            background: #fafaf5;
            border: 1px solid #e2e2d0;
            padding: 10px 14px;
            border-radius: 4px;
        }
        .meta-cell {
            flex: 1;
        }
        .meta-label {
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: bold;
        }
        .meta-value {
            font-size: 10.5pt;
            font-weight: bold;
            color: #111;
            margin-top: 2px;
            padding-bottom: 2px;
        }

        /* Amount highlight */
        .amount-box {
            background: #f5f3e8;
            border: 2px solid #2d3a1e;
            padding: 12px 20px;
            margin: 12px 0;
            text-align: center;
        }
        .amount-label {
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
            font-weight: 600;
        }
        .amount-value {
            font-size: 22pt;
            font-weight: bold;
            color: #1a3a1a;
            font-family: 'Courier New', Courier, monospace;
            margin: 4px 0;
        }
        .amount-words {
            font-size: 10pt;
            font-style: italic;
            color: #3d3d3d;
            border-top: 1px dashed #aaa;
            padding-top: 6px;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Details table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0;
            font-size: 10pt;
        }
        .details-table th {
            background: #2d3a1e;
            color: #fff;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.8px;
            padding: 8px 10px;
            text-align: left;
        }
        .details-table td {
            padding: 10px 10px;
            border-bottom: 1px solid #e0e0d0;
            vertical-align: top;
        }
        .details-table tr:nth-child(even) td { background: #fcfcf8; }
        .details-table .text-right { text-align: right; font-family: 'Courier New', Courier, monospace; font-weight: bold; }
        .details-table .total-row td {
            background: #f0f0e8;
            font-weight: bold;
            border-top: 2px solid #2d3a1e;
            border-bottom: 2px solid #2d3a1e;
            font-size: 11pt;
            padding: 10px;
        }

        /* Narration */
        .narration-box {
            border: 1px solid #d0d0c0;
            padding: 8px 12px;
            margin: 8px 0;
            background: #fafaf5;
            font-size: 10pt;
        }
        .narration-label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .status-posted { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }

        /* Signature section */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            padding-top: 14px;
            border-top: 2px solid #2d3a1e;
            gap: 20px;
        }
        .signature-box {
            flex: 1;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            height: 42px;
            margin-bottom: 6px;
        }
        .signature-label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #444;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #888;
            display: flex;
            justify-content: space-between;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 60pt;
            font-weight: bold;
            color: rgba(45, 58, 30, 0.04);
            text-transform: uppercase;
            pointer-events: none;
            letter-spacing: 4px;
            white-space: nowrap;
        }

        /* Print Toolbar (hidden on print) */
        .print-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            max-width: 210mm;
            margin: 0 auto 20px auto;
            padding: 12px 18px;
            background: #0f172a;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .print-toolbar h2 {
            color: #fff;
            font-size: 12.5pt;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: bold;
            margin: 0;
            white-space: nowrap;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 20px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10.5pt;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            transition: background 0.15s;
        }
        .btn-print:hover { background: #15803d; }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            background: #334155;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-transform: uppercase;
            white-space: nowrap;
            transition: background 0.15s;
        }
        .btn-back:hover { background: #475569; }

        @media print {
            .print-toolbar { display: none !important; }
            body { padding: 0; background: #fff; }
            .page { border: 2px solid #2d3a1e; box-shadow: none; padding: 10mm 12mm; }
        }
    </style>
</head>
<body>

    {{-- Print Toolbar --}}
    <div class="print-toolbar">
        <div>
            <h2>📄 Payment Voucher — {{ $voucher->voucher_number }}</h2>
            @if(session('success'))
                <div style="color:#86efac; font-family:'Segoe UI', sans-serif; font-size:9pt; font-weight:600; margin-top:2px;">
                    ✅ {{ session('success') }}
                </div>
            @endif
        </div>

        <div style="display: flex; gap: 8px; align-items: center;">
            <a href="{{ route('expenses.ra-bills.payment-release') }}" onclick="if(window.history.length > 1) { history.back(); return false; }" class="btn-back">
                ← Back to Payment Release Desk
            </a>
            <button onclick="window.print()" class="btn-print">
                🖨 Print Voucher
            </button>
        </div>
    </div>

    <div class="page">
        <div class="watermark">PAYMENT</div>

        {{-- Header --}}
        <div class="header">
            <div>
                <div class="company-name">Hindustan System</div>
                <div class="company-sub">Real Estate &amp; Construction ERP</div>
                <div class="company-sub" style="margin-top:2px; color:#2d3a1e; font-weight:bold;">GST Registered Entity</div>
            </div>
            <div class="voucher-title-box">
                <div class="voucher-title">Payment Voucher</div>
                <div class="voucher-number">{{ $voucher->voucher_number }}</div>
                <div style="margin-top:6px;">
                    <span class="status-badge status-posted">{{ $voucher->status }}</span>
                </div>
            </div>
        </div>

        {{-- Meta Row --}}
        <div class="meta-row">
            <div class="meta-cell">
                <div class="meta-label">Voucher Date</div>
                <div class="meta-value">{{ $voucher->date?->format('d / m / Y') }}</div>
            </div>
            <div class="meta-cell" style="flex: 1.3;">
                <div class="meta-label">Paid To (Beneficiary / Payee)</div>
                <div class="meta-value" style="color: #1a3a1a;">{{ $payeeName ?? 'Contractor / Payee' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Payment Mode</div>
                <div class="meta-value">{{ $paymentMode ?? 'Bank Transfer' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Reference No. (UTR / Cheque)</div>
                <div class="meta-value" style="font-family: 'Courier New', monospace;">{{ $voucher->reference_no ?: '—' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Prepared By</div>
                <div class="meta-value">{{ $voucher->creator?->name ?? 'System' }}</div>
            </div>
        </div>

        {{-- Total Amount Box --}}
        @php
            $totalAmount = $voucher->lines->sum('debit') ?: ($voucher->lines->sum('credit') ?: (float)($raBillPayment->paid_amount ?? 0));
            
            if (!function_exists('amountInWords')) {
                function amountInWords(float $amount): string {
                    $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
                             'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
                             'Seventeen','Eighteen','Nineteen'];
                    $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
                    $n = (int) floor($amount);
                    $paise = (int) round(($amount - $n) * 100);
                    $convert = function(int $n) use ($ones, $tens, &$convert): string {
                        if ($n < 20)  return $ones[$n];
                        if ($n < 100) return $tens[intdiv($n, 10)] . ($n % 10 ? ' ' . $ones[$n % 10] : '');
                        if ($n < 1000) return $ones[intdiv($n, 100)] . ' Hundred' . ($n % 100 ? ' ' . $convert($n % 100) : '');
                        if ($n < 100000) return $convert(intdiv($n, 1000)) . ' Thousand' . ($n % 1000 ? ' ' . $convert($n % 1000) : '');
                        if ($n < 10000000) return $convert(intdiv($n, 100000)) . ' Lakh' . ($n % 100000 ? ' ' . $convert($n % 100000) : '');
                        return $convert(intdiv($n, 10000000)) . ' Crore' . ($n % 10000000 ? ' ' . $convert($n % 10000000) : '');
                    };
                    $words = $n > 0 ? $convert($n) : 'Zero';
                    if ($paise > 0) $words .= ' and ' . $convert($paise) . ' Paise';
                    return $words . ' Only';
                }
            }
        @endphp
        <div class="amount-box">
            <div class="amount-label">Total Payment Amount</div>
            <div class="amount-value">₹ {{ number_format($totalAmount, 2) }}</div>
            <div class="amount-words">{{ amountInWords((float)$totalAmount) }}</div>
        </div>

        {{-- Narration --}}
        @if($voucher->narration)
            <div class="narration-box">
                <div class="narration-label">Payment Purpose / Description</div>
                <div style="color: #222; font-weight: 500;">{{ $voucher->narration }}</div>
            </div>
        @endif

        {{-- Payee Friendly Single Entry View (Exclusively) --}}
        <div id="customer-view-section">
            <table class="details-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">#</th>
                        <th style="width: 32%;">Particulars / Beneficiary</th>
                        <th style="width: 43%;">Payment Description &amp; Details</th>
                        <th style="width: 20%; text-align: right;">Amount Paid (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: #555;">1</td>
                        <td>
                            <div style="font-size: 11pt; font-weight: bold; color: #1a3a1a;">
                                {{ $payeeName ?? 'Contractor / Payee' }}
                            </div>
                            <div style="font-size: 8.5pt; color: #555; margin-top: 3px;">
                                @if(!empty($billReference))
                                    <span style="background: #eef2ea; color: #2d3a1e; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                        Bill {{ $billReference }}
                                    </span>
                                @endif
                                <span style="display: inline-block; margin-left: 4px;">
                                    Mode: <strong>{{ $paymentMode ?? 'Bank Transfer' }}</strong>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 10pt; color: #222; font-weight: 500; line-height: 1.4;">
                                {{ $voucher->narration ?: 'Staggered RA Bill Disbursement' }}
                            </div>
                            @if($voucher->reference_no)
                                <div style="font-size: 8.5pt; color: #555; margin-top: 3px;">
                                    Transaction Ref / Cheque / UTR: <strong style="font-family: 'Courier New', monospace; color: #111;">{{ $voucher->reference_no }}</strong>
                                </div>
                            @endif
                        </td>
                        <td class="text-right" style="font-size: 12pt; font-weight: bold; color: #1a3a1a; vertical-align: middle;">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; padding-right: 15px;">
                            Total Amount Paid
                        </td>
                        <td class="text-right" style="font-size: 12.5pt; color: #1a3a1a;">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Prepared By</div>
                <div style="font-size:9pt; color:#555; margin-top:2px;">{{ $voucher->creator?->name ?? '—' }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Checked By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Approved By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Received By (Payee)</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <span>Voucher No: <strong>{{ $voucher->voucher_number }}</strong> | Generated: {{ now()->format('d M Y, h:i A') }}</span>
            <span style="font-style:italic;">This is an official system-generated Payment Voucher — Hindustan ERP</span>
            <span>Page 1 of 1</span>
        </div>
    </div>

</body>
</html>
