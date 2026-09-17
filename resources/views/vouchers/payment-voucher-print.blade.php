<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher — {{ $voucher->voucher_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 10.5pt;
            color: #1e293b;
            background: #f1f5f9;
            padding: 24px 16px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Screen Page Preview (Enlarged for High Visibility) ── */
        .page-container {
            max-width: 1040px;
            margin: 0 auto;
        }

        /* ── Floating Action Toolbar (Screen Only) ── */
        .print-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            padding: 12px 22px;
            background: #0f172a;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
            color: #ffffff;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            width: 100%;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: #1e293b;
            color: #cbd5e1;
            border: 1px solid #334155;
            border-radius: 8px;
            font-size: 9pt;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-back:hover { background: #334155; color: #ffffff; }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 22px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 9.5pt;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
        }
        .btn-print:hover { background: linear-gradient(135deg, #047857 0%, #065f46 100%); }

        /* ── Printable Page Document ── */
        .page {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 18px 25px -5px rgba(15, 23, 42, 0.07);
            padding: 36px 42px;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Top Gold Accent Strip */
        .page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4.5px;
            background: linear-gradient(90deg, #a38c29 0%, #d4af37 50%, #a38c29 100%);
        }

        /* ── Document Header ── */
        .doc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #f1f5f9;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo-img {
            height: 54px;
            width: auto;
            max-width: 140px;
            object-fit: contain;
        }

        .brand-logo-fallback {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a38c29;
            border: 1.5px solid #a38c29;
        }

        .project-title {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
            line-height: 1.25;
        }

        .project-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 9pt;
            color: #64748b;
            font-weight: 600;
            margin-top: 4px;
        }

        .location-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #a38c29;
        }

        /* ── Metadata Grid ── */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .meta-cell {
            padding: 11px 15px;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .meta-cell:last-child {
            border-right: none;
        }

        .meta-label {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 9.5pt;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }

        .meta-value.mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9pt;
        }

        .meta-value.bank-acc {
            font-size: 8pt;
            color: #64748b;
            font-family: 'JetBrains Mono', monospace;
            margin-top: 2px;
        }

        /* ── Outflow Banner ── */
        .outflow-banner {
            background: linear-gradient(135deg, #fffdf7 0%, #fcfbf7 50%, #ffffff 100%);
            border: 1px solid #f0e6cb;
            border-left: 5px solid #a38c29;
            border-radius: 12px;
            padding: 15px 22px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .outflow-label {
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #8a7522;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .outflow-label-badge {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #a38c29;
        }

        .outflow-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 23pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.15;
        }

        .outflow-words {
            font-size: 9pt;
            color: #475569;
            font-weight: 600;
            margin-top: 4px;
        }

        .outflow-words strong {
            color: #0f172a;
        }

        /* ── Narration Callout ── */
        .narration-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0f172a;
            border-radius: 9px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }

        .narration-label {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .narration-text {
            font-size: 9.5pt;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.4;
        }

        /* ── Particulars Table ── */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .details-table thead th {
            background: #0f172a;
            color: #f8fafc;
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            padding: 11px 16px;
            text-align: left;
            border-bottom: 2px solid #a38c29;
        }

        .details-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5pt;
            vertical-align: middle;
        }

        .details-table tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        .payee-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 10.5pt;
        }

        .project-subtitle {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 3px;
            font-weight: 500;
        }

        .ref-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 2px 8px;
            border-radius: 5px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 8pt;
            font-weight: 700;
            margin-top: 4px;
        }

        .accounting-head-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 9.5pt;
        }

        .payment-mode-subtext {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 3px;
            font-weight: 500;
        }

        .payment-mode-subtext strong {
            color: #0f172a;
        }

        .amount-col {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5pt;
            font-weight: 800;
            color: #0f172a;
            text-align: right;
        }

        .total-row td {
            background: #f8fafc !important;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            font-weight: 800;
            padding: 12px 16px;
        }

        .total-label {
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            font-size: 8.5pt;
            color: #475569;
            font-weight: 800;
        }

        .total-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14pt;
            font-weight: 900;
            color: #059669;
            text-align: right;
        }

        /* ── Executive Signatures Block ── */
        .sig-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1.5px solid #f1f5f9;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .sig-box {
            text-align: center;
        }

        .sig-line {
            border-bottom: 1.5px dashed #cbd5e1;
            height: 48px;
            margin-bottom: 8px;
        }

        .sig-title {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
        }

        .sig-subtitle {
            font-size: 8.5pt;
            color: #0f172a;
            margin-top: 3px;
            font-weight: 700;
        }

        /* ── Document Footer ── */
        .doc-footer {
            margin-top: 22px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 7.5pt;
            color: #94a3b8;
            font-weight: 500;
        }

        .doc-footer strong {
            color: #475569;
        }

        /* ── Print Media Optimization (Enlarged, Crisp, Fits 1 Sheet 100%) ── */
        @media print {
            html, body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 10pt !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-toolbar {
                display: none !important;
            }

            .page-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .page {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 12px !important;
                padding: 28px 32px !important;
                max-width: 100% !important;
                width: 100% !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .page::before {
                display: block !important;
                height: 4.5px !important;
            }

            .doc-header {
                padding-bottom: 16px !important;
                margin-bottom: 16px !important;
            }

            .brand-logo-img {
                height: 52px !important;
                max-width: 140px !important;
            }

            .project-title {
                font-size: 16pt !important;
            }

            .project-meta {
                font-size: 9pt !important;
            }

            .meta-grid {
                margin-bottom: 16px !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 10px !important;
            }

            .meta-cell {
                padding: 11px 14px !important;
            }

            .meta-label {
                font-size: 7.5pt !important;
                margin-bottom: 3px !important;
            }

            .meta-value {
                font-size: 9.5pt !important;
            }

            .meta-value.mono {
                font-size: 9pt !important;
            }

            .meta-value.bank-acc {
                font-size: 8pt !important;
            }

            .outflow-banner {
                padding: 16px 20px !important;
                margin-bottom: 16px !important;
                border-left-width: 5px !important;
                border-radius: 10px !important;
            }

            .outflow-label {
                font-size: 8pt !important;
            }

            .outflow-amount {
                font-size: 24pt !important;
            }

            .outflow-words {
                font-size: 9.5pt !important;
            }

            .narration-box {
                padding: 10px 16px !important;
                margin-bottom: 18px !important;
                border-left-width: 4px !important;
                border-radius: 8px !important;
            }

            .narration-label {
                font-size: 7.5pt !important;
            }

            .narration-text {
                font-size: 9.5pt !important;
            }

            .details-table {
                margin-bottom: 22px !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 8px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .details-table thead th {
                padding: 11px 14px !important;
                font-size: 8pt !important;
            }

            .details-table tbody td {
                padding: 12px 14px !important;
                font-size: 9.5pt !important;
            }

            .payee-title {
                font-size: 10.5pt !important;
            }

            .project-subtitle {
                font-size: 8.5pt !important;
            }

            .accounting-head-name {
                font-size: 9.5pt !important;
            }

            .payment-mode-subtext {
                font-size: 8.5pt !important;
            }

            .amount-col {
                font-size: 11.5pt !important;
            }

            .total-row td {
                padding: 12px 14px !important;
            }

            .total-label {
                font-size: 8.5pt !important;
            }

            .total-amount {
                font-size: 14pt !important;
            }

            .sig-section {
                margin-top: 36px !important;
                padding-top: 18px !important;
                gap: 18px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .sig-line {
                height: 52px !important;
                margin-bottom: 8px !important;
            }

            .sig-title {
                font-size: 7.5pt !important;
            }

            .sig-subtitle {
                font-size: 8.5pt !important;
            }

            .doc-footer {
                margin-top: 26px !important;
                padding-top: 12px !important;
                font-size: 7.5pt !important;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        {{-- Floating Action Toolbar (Screen Preview Only) --}}
        <div class="print-toolbar">
            <div class="toolbar-actions">
                <a href="{{ url()->previous() ?: route('site-expenses.payment-release') }}" onclick="if(window.history.length > 1) { history.back(); return false; }" class="btn-back">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back to Desk</span>
                </a>
                <button onclick="window.print()" class="btn-print">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Voucher</span>
                </button>
            </div>
        </div>

        {{-- Printable Document Sheet (Fits 1 Sheet 100%) --}}
        <div class="page">

            {{-- 1. Document Header --}}
            <div class="doc-header">
                <div class="brand-group">
                    @if(file_exists(public_path('img/logo1.png')))
                        <img src="{{ asset('img/logo1.png') }}" alt="Logo" class="brand-logo-img">
                    @elseif(file_exists(public_path('img/logo.jpg')))
                        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="brand-logo-img">
                    @else
                        <div class="brand-logo-fallback">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h1 class="project-title">{{ $projectName ?: 'Project Disbursement' }}</h1>
                        @if(!empty($projectLocation))
                            <div class="project-meta">
                                <span class="location-dot"></span>
                                <span>{{ $projectLocation }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Metadata Grid --}}
            <div class="meta-grid">
                <div class="meta-cell">
                    <span class="meta-label">Voucher Date</span>
                    <span class="meta-value">{{ $voucher->date ? $voucher->date->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                </div>

                <div class="meta-cell" style="grid-column: span 1.2;">
                    <span class="meta-label">Beneficiary / Payee</span>
                    <span class="meta-value" style="color: #0f172a;">{{ $payeeName ?? 'Authorized Payee' }}</span>
                </div>

                <div class="meta-cell">
                    <span class="meta-label">Payment Mode</span>
                    <span class="meta-value" style="color: #0369a1;">{{ $paymentMode ?? 'Bank Transfer' }}</span>
                </div>

                <div class="meta-cell" style="grid-column: span 1.3;">
                    <span class="meta-label">Source Bank Account</span>
                    <span class="meta-value">{{ $bankName ?? 'Corporate Bank Account' }}</span>
                    @if(!empty($bankAccountNo))
                        <span class="meta-value bank-acc">A/C: {{ $bankAccountNo }}</span>
                    @endif
                </div>

                <div class="meta-cell">
                    <span class="meta-label">Ref No. / UTR</span>
                    <span class="meta-value mono">{{ $voucher->reference_no ?: '—' }}</span>
                </div>
            </div>

            {{-- 3. Total Amount Box --}}
            @php
                $totalAmount = $voucher->lines->sum('debit') ?: ($voucher->lines->sum('credit') ?: (float)($raBillPayment->paid_amount ?? ($siteExpensePayment->paid_amount ?? 0)));
                
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
            <div class="outflow-banner">
                <div>
                    <div class="outflow-label">
                        <span class="outflow-label-badge"></span>
                        <span>Total Outflow Disbursed</span>
                    </div>
                    <div class="outflow-amount">₹ {{ number_format($totalAmount, 2) }}</div>
                    <div class="outflow-words">INR <strong>{{ amountInWords((float)$totalAmount) }}</strong></div>
                </div>
            </div>

            {{-- 4. Narration / Purpose --}}
            @if($voucher->narration)
                <div class="narration-box">
                    <div class="narration-label">Disbursement Narration &amp; Purpose</div>
                    <div class="narration-text">{{ $voucher->narration }}</div>
                </div>
            @endif

            {{-- 5. Particulars Table --}}
            <table class="details-table">
                <thead>
                    <tr>
                        <th style="width: 6%; text-align: center;">#</th>
                        <th style="width: 42%;">Beneficiary / Payee Particulars</th>
                        <th style="width: 32%;">Payment Details &amp; Accounting Head</th>
                        <th style="width: 20%; text-align: right;">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #64748b;">1</td>
                        <td>
                            <div class="payee-title">
                                {{ $payeeName ?? 'Authorized Payee' }}
                            </div>
                            @if(!empty($projectName))
                                <div class="project-subtitle">
                                    Project: <strong>{{ $projectName }}</strong>
                                </div>
                            @endif
                            @if(!empty($billReference))
                                <div class="ref-pill">
                                    <span>Ref:</span>
                                    <span>{{ $billReference }}</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="accounting-head-name">
                                {{ $categoryName ?: ($voucher->narration ?: 'Corporate Payment Outflow') }}
                            </div>
                            <div class="payment-mode-subtext">
                                Mode: <strong>{{ $paymentMode ?? 'Direct Bank Transfer' }}</strong>
                                @if($voucher->reference_no)
                                    &nbsp;|&nbsp; Ref: <strong style="font-family: monospace;">{{ $voucher->reference_no }}</strong>
                                @endif
                            </div>
                        </td>
                        <td class="amount-col">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" class="total-label">
                            Total Net Disbursement Outflow
                        </td>
                        <td class="total-amount">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- 6. Executive Signature & Authorization Block --}}
            <div class="sig-section">
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-title">Prepared By</div>
                    <div class="sig-subtitle">{{ $voucher->creator?->name ?? 'Accounts Officer' }}</div>
                </div>
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-title">Checked &amp; Verified By</div>
                    <div class="sig-subtitle">Internal Audit</div>
                </div>
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-title">Approved By</div>
                    <div class="sig-subtitle">Finance Director</div>
                </div>
                <div class="sig-box">
                    <div class="sig-line"></div>
                    <div class="sig-title">Receiver's Signature</div>
                    <div class="sig-subtitle">{{ $payeeName ?? 'Payee' }}</div>
                </div>
            </div>

            {{-- 7. Document Footer --}}
            <div class="doc-footer">
                <div>Voucher No: <strong style="font-family: monospace;">{{ $voucher->voucher_number }}</strong> &nbsp;|&nbsp; Generated: {{ now()->format('d M Y, h:i A') }}</div>
                <div>Official System-Generated Disbursement Voucher{{ !empty($projectName) ? ' • ' . $projectName : '' }}</div>
                <div>Page 1 of 1</div>
            </div>
        </div>
    </div>

</body>
</html>
