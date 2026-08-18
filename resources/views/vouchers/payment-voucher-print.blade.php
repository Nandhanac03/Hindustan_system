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
            background: #fff;
            padding: 20px;
        }

        .page {
            width: 210mm;
            min-height: 148mm;
            margin: 0 auto;
            padding: 16mm 18mm;
            border: 2px solid #2d3a1e;
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
            gap: 20px;
        }
        .meta-cell {
            flex: 1;
        }
        .meta-label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #666;
            font-weight: bold;
        }
        .meta-value {
            font-size: 11pt;
            font-weight: bold;
            color: #111;
            margin-top: 2px;
            border-bottom: 1px solid #ccc;
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
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
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
            color: #444;
            border-top: 1px dashed #aaa;
            padding-top: 6px;
            margin-top: 6px;
        }

        /* Details table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 10pt;
        }
        .details-table th {
            background: #2d3a1e;
            color: #fff;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.8px;
            padding: 6px 10px;
            text-align: left;
        }
        .details-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e0e0d0;
            vertical-align: top;
        }
        .details-table tr:nth-child(even) td { background: #f9f9f4; }
        .details-table .text-right { text-align: right; font-family: 'Courier New', Courier, monospace; font-weight: bold; }
        .details-table .total-row td {
            background: #f0f0e8;
            font-weight: bold;
            border-top: 2px solid #2d3a1e;
            font-size: 11pt;
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
            margin-top: 28px;
            padding-top: 12px;
            border-top: 2px solid #2d3a1e;
            gap: 20px;
        }
        .signature-box {
            flex: 1;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            height: 40px;
            margin-bottom: 5px;
        }
        .signature-label {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #555;
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
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
            padding: 12px;
            background: #1e293b;
            border-radius: 12px;
        }
        .print-toolbar h2 {
            color: #fff;
            font-size: 13pt;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            margin-right: auto;
        }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-print:hover { background: #15803d; }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            background: #334155;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-transform: uppercase;
        }

        @media print {
            .print-toolbar { display: none !important; }
            body { padding: 0; }
            .page { border: none; padding: 10mm 12mm; }
        }
    </style>
</head>
<body>

    {{-- Print Toolbar --}}
    <div class="print-toolbar">
        <h2>📄 Payment Voucher — {{ $voucher->voucher_number }}</h2>
        @if(session('success'))
            <span style="color:#86efac; font-family:Arial; font-size:10pt; font-weight:bold;">✅ {{ session('success') }}</span>
        @endif
        <a href="{{ route('expenses.ra-bills.payment-release') }}" onclick="if(window.history.length > 1) { history.back(); return false; }" class="btn-back">← Back to Payment Release Desk</a>
        <button onclick="window.print()" class="btn-print">🖨 Print Voucher</button>
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

        {{-- Meta --}}
        <div class="meta-row">
            <div class="meta-cell">
                <div class="meta-label">Voucher Date</div>
                <div class="meta-value">{{ $voucher->date?->format('d / m / Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Reference No.</div>
                <div class="meta-value">{{ $voucher->reference_no ?: '—' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Prepared By</div>
                <div class="meta-value">{{ $voucher->creator?->name ?? 'System' }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-label">Voucher Type</div>
                <div class="meta-value">{{ $voucher->type }}</div>
            </div>
        </div>

        {{-- Total Amount Box --}}
        @php
            $totalAmount = $voucher->lines->sum('debit') ?: $voucher->lines->sum('credit');
            // Convert amount to words
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
        @endphp
        <div class="amount-box">
            <div class="amount-label">Total Payment Amount</div>
            <div class="amount-value">₹ {{ number_format($totalAmount, 2) }}</div>
            <div class="amount-words">{{ amountInWords((float)$totalAmount) }}</div>
        </div>

        {{-- Narration --}}
        @if($voucher->narration)
            <div class="narration-box">
                <div class="narration-label">Narration / Description</div>
                <div>{{ $voucher->narration }}</div>
            </div>
        @endif

        {{-- Voucher Lines / Double Entry --}}
        @if($voucher->lines->count() > 0)
            <table class="details-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Account Head</th>
                        <th>Type</th>
                        <th>Narration</th>
                        <th class="text-right" style="text-align:right;">Debit (₹)</th>
                        <th class="text-right" style="text-align:right;">Credit (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voucher->lines as $i => $line)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="font-weight:bold;">{{ $line->account?->name ?? '—' }}</td>
                            <td>{{ $line->account?->type ?? '—' }}</td>
                            <td>{{ $line->narration ?: $voucher->narration }}</td>
                            <td class="text-right">{{ $line->debit > 0 ? number_format($line->debit, 2) : '—' }}</td>
                            <td class="text-right">{{ $line->credit > 0 ? number_format($line->credit, 2) : '—' }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4" style="text-align:right; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px;">TOTAL</td>
                        <td class="text-right">₹{{ number_format($voucher->lines->sum('debit'), 2) }}</td>
                        <td class="text-right">₹{{ number_format($voucher->lines->sum('credit'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            {{-- Fallback simple view --}}
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Particulars</th>
                        <th class="text-right" style="text-align:right;">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $voucher->narration ?: 'Payment' }}</td>
                        <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="text-align:right; font-weight:bold;">TOTAL</td>
                        <td class="text-right">₹{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

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
            <span style="font-style:italic;">This is a system-generated Payment Voucher — Hindustan ERP</span>
            <span>Page 1 of 1</span>
        </div>
    </div>

</body>
</html>
