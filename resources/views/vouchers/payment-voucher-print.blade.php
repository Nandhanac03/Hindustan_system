<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher — {{ $voucher->voucher_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #1e293b;
            background: #f1f5f9;
            padding: 24px 16px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Screen Page Preview ── */
        .page-container {
            max-width: 860px;
            margin: 0 auto;
        }

        .page {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            padding: 36px 40px;
            position: relative;
            overflow: hidden;
        }

        /* ── Floating Action Toolbar (Hidden on Print) ── */
        .print-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            padding: 14px 22px;
            background: #0f172a;
            border-radius: 14px;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            color: #ffffff;
        }

        .toolbar-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-title {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        .toolbar-pill {
            font-family: 'JetBrains Mono', monospace;
            background: rgba(255, 255, 255, 0.15);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 9pt;
            font-weight: 700;
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            background: #334155;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 9.5pt;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-back:hover { background: #475569; }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            background: #059669;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 10pt;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
        }
        .btn-print:hover { background: #047857; }

        /* ── Document Header ── */
        .doc-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 22px;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a38c29;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
            border: 1px solid #a38c29;
        }

        .company-name {
            font-size: 15pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }

        .company-sub {
            font-size: 8.5pt;
            color: #64748b;
            font-weight: 600;
            margin-top: 3px;
        }

        .company-tax {
            font-size: 8pt;
            color: #a38c29;
            font-weight: 700;
            margin-top: 2px;
        }

        .voucher-title-group {
            text-align: right;
        }

        .voucher-tag {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #a38c29;
        }

        .voucher-title {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin: 2px 0 6px 0;
        }

        .voucher-meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .voucher-num-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9.5pt;
            font-weight: 800;
            background: #f8fafc;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            padding: 3px 10px;
            border-radius: 6px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        /* ── Metadata Grid ── */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 7pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
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

        /* ── Highlight Outflow Banner ── */
        .outflow-banner {
            background: linear-gradient(135deg, #fffdf5 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-left: 5px solid #a38c29;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .outflow-label {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #a38c29;
            margin-bottom: 4px;
        }

        .outflow-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 20pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .outflow-words {
            font-size: 8.5pt;
            font-style: italic;
            color: #475569;
            font-weight: 600;
            margin-top: 4px;
        }

        .outflow-badge {
            text-align: right;
            border-left: 1px solid #e2e8f0;
            padding-left: 20px;
        }

        .verified-stamp {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Purpose Narration ── */
        .purpose-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
        }

        .purpose-label {
            font-size: 7pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .purpose-text {
            font-size: 9.5pt;
            font-weight: 600;
            color: #1e293b;
        }

        /* ── Particulars Table ── */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .details-table th {
            background: #a38c29;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 14px;
            text-align: left;
        }

        .details-table td {
            padding: 14px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9.5pt;
            vertical-align: middle;
        }

        .details-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .ref-pill {
            display: inline-block;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 2px 7px;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 8pt;
            font-weight: 700;
            margin-top: 3px;
        }

        .details-table .text-right {
            text-align: right;
        }

        .amount-col {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11pt;
            font-weight: 800;
            color: #0f172a;
        }

        .total-row td {
            background: #f1f5f9 !important;
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            font-weight: 800;
            padding: 12px 14px;
        }

        .total-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13pt;
            font-weight: 800;
            color: #059669;
        }

        /* ── Executive Signature Section ── */
        .sig-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .sig-box {
            text-align: center;
        }

        .sig-line {
            border-bottom: 1.5px dashed #94a3b8;
            height: 48px;
            margin-bottom: 8px;
        }

        .sig-title {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #475569;
        }

        .sig-subtitle {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 2px;
            font-weight: 600;
        }

        /* ── Footer ── */
        .doc-footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 7.5pt;
            color: #94a3b8;
            font-weight: 500;
        }

        /* ── Subtle Watermark ── */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 75pt;
            font-weight: 900;
            color: rgba(163, 140, 41, 0.035);
            text-transform: uppercase;
            letter-spacing: 10px;
            pointer-events: none;
            white-space: nowrap;
            user-select: none;
        }

        /* ── Print Styles ── */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .print-toolbar {
                display: none !important;
            }
            .page {
                box-shadow: none;
                border: 1px solid #cbd5e1;
                border-radius: 0;
                padding: 24px 28px;
                max-width: 100%;
            }
            .watermark {
                color: rgba(0, 0, 0, 0.025);
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        {{-- Floating Action Toolbar --}}
        <div class="print-toolbar">
            <div class="toolbar-info">
                <span class="toolbar-title">📄 Official Payment Disbursement Voucher</span>
                <span class="toolbar-pill">{{ $voucher->voucher_number }}</span>
            </div>
            <div class="toolbar-actions">
                <a href="{{ url()->previous() ?: route('site-expenses.payment-release') }}" onclick="if(window.history.length > 1) { history.back(); return false; }" class="btn-back">
                    ← Back to Desk
                </a>
                <button onclick="window.print()" class="btn-print">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Voucher</span>
                </button>
            </div>
        </div>

        {{-- Printable Page --}}
        <div class="page">
            <div class="watermark">DISBURSED</div>

            {{-- Document Header --}}
            <div class="doc-header">
                <div class="brand-group">
                    @if(file_exists(public_path('img/logo1.png')))
                        <img src="{{ asset('img/logo1.png') }}" alt="Hindustan System Logo" style="height: 52px; width: auto; max-width: 130px; object-fit: contain; margin-right: 6px;">
                    @elseif(file_exists(public_path('img/logo.jpg')))
                        <img src="{{ asset('img/logo.jpg') }}" alt="Hindustan System Logo" style="height: 52px; width: auto; max-width: 130px; object-fit: contain; margin-right: 6px;">
                    @else
                        <div class="brand-logo">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div class="company-name">Hindustan System</div>
                        <div class="company-sub">Real Estate &amp; Construction ERP</div>
                        <div class="company-tax">GST Registered Entity</div>
                    </div>
                </div>

                <div class="voucher-title-group">
                    <div class="voucher-tag">Official Accounting Document</div>
                    <div class="voucher-title">PAYMENT VOUCHER</div>
                    <div class="voucher-meta-pill">
                        <span class="voucher-num-badge">{{ $voucher->voucher_number }}</span>
                        <span class="status-badge">
                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span>DISBURSED</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Metadata Grid --}}
            <div class="meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Voucher Date</span>
                    <span class="meta-value">{{ $voucher->date ? $voucher->date->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                </div>

                <div class="meta-item" style="grid-column: span 1.5;">
                    <span class="meta-label">Beneficiary / Payee</span>
                    <span class="meta-value" style="color: #0f172a;">{{ $payeeName ?? 'Authorized Payee' }}</span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Payment Mode</span>
                    <span class="meta-value" style="color: #0369a1;">{{ $paymentMode ?? 'Bank Transfer' }}</span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Source Bank Account</span>
                    <span class="meta-value" style="font-size: 8.5pt;">{{ $bankName ?? 'Corporate Bank Account' }}</span>
                    @if(!empty($bankAccountNo))
                        <span style="font-size: 7.5pt; color: #64748b; font-family: monospace;">A/C: {{ $bankAccountNo }}</span>
                    @endif
                </div>

                <div class="meta-item">
                    <span class="meta-label">Ref No. / UTR</span>
                    <span class="meta-value mono">{{ $voucher->reference_no ?: '—' }}</span>
                </div>
            </div>

            {{-- Total Amount Box --}}
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
                    <div class="outflow-label">Total Outflow Disbursed</div>
                    <div class="outflow-amount">₹ {{ number_format($totalAmount, 2) }}</div>
                    <div class="outflow-words">INR {{ amountInWords((float)$totalAmount) }}</div>
                </div>
                <div class="outflow-badge">
                    <div class="verified-stamp">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Treasury Verified</span>
                    </div>
                </div>
            </div>

            {{-- Narration / Purpose --}}
            @if($voucher->narration)
                <div class="purpose-box">
                    <div class="purpose-label">Disbursement Narration &amp; Purpose</div>
                    <div class="purpose-text">{{ $voucher->narration }}</div>
                </div>
            @endif

            {{-- Particulars Table --}}
            <table class="details-table">
                <thead>
                    <tr>
                        <th style="width: 6%; text-align: center;">#</th>
                        <th style="width: 38%;">Beneficiary / Payee Particulars</th>
                        <th style="width: 36%;">Payment Details &amp; Accounting Head</th>
                        <th style="width: 20%; text-align: right;">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; font-weight: 700; color: #64748b;">1</td>
                        <td>
                            <div style="font-weight: 800; color: #0f172a; font-size: 10pt;">
                                {{ $payeeName ?? 'Authorized Payee' }}
                            </div>
                            @if(!empty($projectName))
                                <div style="font-size: 8pt; color: #64748b; margin-top: 2px;">
                                    Project: <strong>{{ $projectName }}</strong>
                                </div>
                            @endif
                            @if(!empty($billReference))
                                <div class="ref-pill">
                                    Ref: {{ $billReference }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #334155;">
                                {{ $categoryName ?: ($voucher->narration ?: 'Corporate Payment Outflow') }}
                            </div>
                            <div style="font-size: 8pt; color: #64748b; margin-top: 3px;">
                                Mode: <strong style="color: #0f172a;">{{ $paymentMode ?? 'Direct Bank Transfer' }}</strong>
                                @if($voucher->reference_no)
                                    | Ref: <strong style="font-family: monospace; color: #0f172a;">{{ $voucher->reference_no }}</strong>
                                @endif
                            </div>
                        </td>
                        <td class="text-right amount-col">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; text-transform: uppercase; letter-spacing: 0.8px; font-size: 8.5pt; color: #475569;">
                            Total Net Disbursement Outflow
                        </td>
                        <td class="text-right total-amount">
                            ₹ {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Signature & Authorization Block --}}
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

            {{-- Footer --}}
            <div class="doc-footer">
                <div>Voucher No: <strong style="color: #0f172a; font-family: monospace;">{{ $voucher->voucher_number }}</strong> | Generated: {{ now()->format('d M Y, h:i A') }}</div>
                <div>Official System-Generated Disbursement Voucher • Hindustan System</div>
                <div>Page 1 of 1</div>
            </div>
        </div>
    </div>

</body>
</html>
