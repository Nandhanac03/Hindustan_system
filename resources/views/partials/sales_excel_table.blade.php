<table id="salesExcelTable" border="1" style="border-collapse: collapse; font-family: 'Calibri', 'Aptos', sans-serif; font-size: 10pt; border: 2.0pt solid #1e293b;">
    <colgroup>
        {{-- Section 1 --}}
        <col width="60" style="width: 45pt;" />    {{-- SL NO --}}
        <col width="220" style="width: 165pt;" />  {{-- CUSTOMER NAME --}}
        <col width="130" style="width: 98pt;" />   {{-- BOOKING DATE --}}
        <col width="90" style="width: 68pt;" />    {{-- FLOOR --}}
        <col width="110" style="width: 83pt;" />   {{-- UNIT TYPE --}}
        <col width="140" style="width: 105pt;" />  {{-- AGREEMENT DATE --}}
        <col width="110" style="width: 83pt;" />   {{-- AREA (SQFT) --}}
        {{-- Section 2 --}}
        <col width="150" style="width: 113pt;" />  {{-- EXPECTED RATE / SQFT --}}
        <col width="140" style="width: 105pt;" />  {{-- ACTUAL RATE / SQFT --}}
        <col width="150" style="width: 113pt;" />  {{-- BASE TOTAL AMOUNT --}}
        <col width="190" style="width: 143pt;" />  {{-- RATE VARIANCE --}}
        {{-- Section 3 --}}
        <col width="80" style="width: 60pt;" />    {{-- GST % --}}
        <col width="130" style="width: 98pt;" />   {{-- GST AMOUNT --}}
        <col width="140" style="width: 105pt;" />  {{-- PARKING CHARGES --}}
        <col width="140" style="width: 105pt;" />  {{-- ADDITIONAL WORK --}}
        <col width="170" style="width: 128pt;" />  {{-- GRAND TOTAL DEAL PRICE --}}
        {{-- Section 4 --}}
        <col width="160" style="width: 120pt;" />  {{-- TOTAL CHEQUE VALUE --}}
        <col width="160" style="width: 120pt;" />  {{-- TOTAL RECEIVED CHEQUE --}}
        <col width="150" style="width: 113pt;" />  {{-- CHEQUE RECEIPT DATE --}}
        <col width="160" style="width: 120pt;" />  {{-- CHEQUE BALANCE DUE --}}
        <col width="110" style="width: 83pt;" />   {{-- INSTALMENT --}}
        <col width="160" style="width: 120pt;" />  {{-- CHEQUE COLLECTION % --}}
        <col width="160" style="width: 120pt;" />  {{-- PROFIT / LOSS --}}
    </colgroup>
    <thead>
        {{-- Title Header Row (Exact A3 print layout friendly combined 1 row title) --}}
        <tr height="45" style="height: 45pt;">
            <th colspan="23" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; font-weight: bold; font-size: 14pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 12px 0; font-family: 'Calibri', 'Aptos', sans-serif;">
                {{ strtoupper($project->name ?? 'PROJECT') }} - SALE REPORT
            </th>
        </tr>
        {{-- Super Section Headers Row with correct requested colors --}}
        <tr height="30" style="height: 30pt;">
            <th colspan="7" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">1. UNIT & CUSTOMER INFORMATION</th>
            <th colspan="4" bgcolor="#0e7490" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">2. PRICING & RATE VARIANCE</th>
            <th colspan="5" bgcolor="#334155" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">3. TAXES & ADD-ONS</th>
            <th colspan="7" bgcolor="#047857" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 10pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 6px 0; font-family: 'Calibri', 'Aptos', sans-serif;">4. CHEQUE VALUE</th>
        </tr>
        {{-- Main Column Headers --}}
        <tr height="40" style="height: 40pt;">
            {{-- Section 1 --}}
            <th width="60" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 45pt;">SL NO</th>
            <th width="220" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 165pt;">CUSTOMER NAME</th>
            <th width="130" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 98pt;">BOOKING DATE</th>
            <th width="90" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 68pt;">FLOOR</th>
            <th width="110" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">UNIT TYPE</th>
            <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">AGREEMENT DATE</th>
            <th width="110" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">AREA (SQFT)</th>
            {{-- Section 2 --}}
            <th width="150" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">EXPECTED RATE / SQFT</th>
            <th width="140" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">ACTUAL RATE / SQFT</th>
            <th width="150" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">BASE TOTAL AMOUNT</th>
            <th width="190" bgcolor="#0e7490" x:autofilter="all" style="background-color: #0e7490; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 143pt;">RATE VARIANCE (DISCOUNT / LOSS)</th>
            {{-- Section 3 --}}
            <th width="80" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 60pt;">GST %</th>
            <th width="130" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 98pt;">GST AMOUNT</th>
            <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">PARKING CHARGES</th>
            <th width="140" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 105pt;">ADDITIONAL WORK</th>
            <th width="170" bgcolor="#334155" x:autofilter="all" style="background-color: #334155; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 128pt;">TOTAL AMOUNT INCLUDING GST /PARKING/ADDITIONAL</th>
            {{-- Section 4 --}}
            <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">TOTAL CHEQUE VALUE</th>
            <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">TOTAL RECEIVED CHEQUE</th>
            <th width="150" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 113pt;">CHEQUE RECEIPT DATE</th>
            <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE BALANCE DUE</th>
            <th width="110" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 83pt;">INSTALMENT</th>
            <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">CHEQUE COLLECTION %</th>
            <th width="160" bgcolor="#047857" x:autofilter="all" style="background-color: #047857; color: #ffffff; font-weight: bold; font-size: 8.5pt; text-align: center; vertical-align: middle; border: 1px solid #475569; padding: 8px 4px; font-family: 'Calibri', 'Aptos', sans-serif; white-space: normal; mso-wrap-text: true; width: 120pt;">PROFIT / LOSS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totals = [
                'area'                => 0,
                'base_total'          => 0,
                'variance'            => 0,
                'gst_amount'          => 0,
                'parking'             => 0,
                'additional'          => 0,
                'grand_total'         => 0,
                'cheque_value'        => 0,
                'cheque_received'     => 0,
                'total_received_cheque' => 0,
                'cheque_due'          => 0,
                'profit_loss'         => 0,
            ];
            $globalRowCounter = 0;
            $salesListSafe = isset($salesList) ? $salesList : collect();
        @endphp
        @foreach($salesListSafe as $sale)
            @php
                // ── Floor ordinal helper ─────────────────────────────────────
                $fmtFlr = function(string $fn): string {
                    $c = preg_replace('/[^0-9]/', '', $fn);
                    if ($c !== '') {
                        $n = (int)$c;
                        $sfx = in_array($n % 100, [11,12,13]) ? 'TH' : (['TH','ST','ND','RD'][$n % 10] ?? 'TH');
                        return $c . $sfx;
                    }
                    return strtoupper(trim($fn));
                };

                // ── Sale-level payment / date data ───────────────────────────
                $bookingDate_     = $sale->sale_date?->format('Y-m-d') ?? '';
                $agreementDate_   = $sale->agreement_date?->format('Y-m-d') ?? $sale->sale_date?->format('Y-m-d') ?? '';
                $installmentsCount_ = $sale->emi_installment_count ?? '';
                $latestChqRcpt_   = $sale->receipts ? $sale->receipts->where('payment_mode', 'Cheque')->sortByDesc('receipt_date')->first() : null;
                $receiptDate_     = $latestChqRcpt_?->receipt_date?->format('Y-m-d') ?? '';
                $totalChequeValue_    = (float)$sale->total_amount;
                $chequeReceived_      = $sale->receipts ? (float)$sale->receipts->where('payment_mode', 'Cheque')->sum('amount') : 0;
                $totalReceivedCheque_ = $sale->receipts ? (float)$sale->receipts->sum('amount') : 0;
                $chequeBalanceDue_    = (float)$sale->remaining_balance;
                $collectionPct_       = $totalChequeValue_ > 0 ? ($totalReceivedCheque_ / $totalChequeValue_) * 100 : 0.00;
                $additionalWork_      = $sale->extraWorks ? (float)$sale->extraWorks->sum('amount') : 0;

                // Profit / Loss
                $expAmtTotal_ = 0.00;
                if ($sale->saleUnits) {
                    foreach ($sale->saleUnits as $_su) {
                        $expAmtTotal_ += (float)($_su->unit?->expected_sale_amount ?? 0);
                    }
                }
                if ($expAmtTotal_ == 0) $expAmtTotal_ = (float)($sale->unit?->expected_sale_amount ?? 0);
                $saleAmount_   = (float)($sale->sale_amount ?? 0);
                $profitLoss_   = $saleAmount_ - $expAmtTotal_;

                // Accumulate sale-level totals (once per sale, not per unit)
                $totals['cheque_value']         += $totalChequeValue_;
                $totals['cheque_received']       += $chequeReceived_;
                $totals['total_received_cheque'] += $totalReceivedCheque_;
                $totals['cheque_due']            += $chequeBalanceDue_;
                $totals['additional']            += $additionalWork_;
                $totals['profit_loss']           += $profitLoss_;

                // Units to iterate (fall back to null for sales with no saleUnits)
                $unitsToRender_ = ($sale->saleUnits && $sale->saleUnits->isNotEmpty())
                    ? $sale->saleUnits->values()
                    : collect([null]);
                $isMultiUnit_ = $unitsToRender_->count() > 1;
                $saleSlNo_    = $loop->iteration;

                // Shared conditional styles
                $balanceStyle_ = $chequeBalanceDue_ > 0 ? 'background-color: #fee2e2; color: #991b1b; font-weight: bold;' : '';
                if ($collectionPct_ >= 100)     { $pctStyle_ = 'background-color: #dcfce7; color: #166534; font-weight: bold;'; }
                elseif ($collectionPct_ >= 50)  { $pctStyle_ = 'background-color: #fef9c3; color: #854d0e; font-weight: bold;'; }
                else                             { $pctStyle_ = 'background-color: #fee2e2; color: #991b1b; font-weight: bold;'; }
                $plStyle_ = $profitLoss_ < 0 ? 'color: #991b1b; font-weight: bold;' : ($profitLoss_ > 0 ? 'color: #166534; font-weight: bold;' : '');
            @endphp

            @foreach($unitsToRender_ as $suIdx => $su)
                @php
                    $globalRowCounter++;
                    $isFirst_  = ($suIdx === 0);
                    $isPark_   = ($su
                        ? (str_contains(strtolower($su->unit?->unitType?->name ?? ''), 'parking') || str_contains(strtolower($su->unit?->unitType?->category ?? ''), 'parking'))
                        : (str_contains(strtolower($sale->unit?->unitType?->name ?? ''), 'parking') || str_contains(strtolower($sale->unit?->unitType?->category ?? ''), 'parking')));

                    // Floor
                    $floorNm_  = $su ? ($su->unit?->floor?->name ?? '') : ($sale->unit?->floor?->name ?? '');
                    $floorD_   = $floorNm_ ? $fmtFlr($floorNm_) : '';

                    // Door / unit label
                    $doorRaw_  = $su
                        ? trim(explode(',', $su->unit?->door_no ?? '')[0])
                        : trim(explode(',', $sale->unit?->door_no ?? '')[0]);
                    $unitD_    = $isPark_ ? $doorRaw_ . ' (Parking)' : $doorRaw_;

                    // Area & rates (Parking does not have sqft area; in DB it stores 1.0 as a slot counter, which should not display as area or count toward total area)
                    $suArea_   = $isPark_ ? 0 : ($su ? (float)($su->area_sqft ?? 0) : (float)($sale->unit?->area_sqft ?? 0));
                    $suExpR_   = $su ? (float)($su->unit?->expected_rate_per_sqft ?? 0) : 0;
                    $suActR_   = $su ? (float)($su->rate_per_sqft ?? 0) : 0;
                    $suBase_   = $su ? (float)($su->base_amount ?? 0) : 0;
                    $suVar_    = ($suExpR_ - $suActR_) * $suArea_;
                    $suGstP_   = $su ? (float)($su->gst_percentage ?? 0) : 0;
                    $suGstA_   = $su ? (float)($su->gst_amount ?? 0) : 0;

                    // Split base vs parking
                    $suParking_  = $isPark_ ? $suBase_ : 0;
                    $suBaseCol_  = $isPark_ ? 0 : $suBase_;

                    // Grand total for this row
                    $suAdd_    = $isFirst_ ? $additionalWork_ : 0;
                    $suGrand_  = $suBase_ + $suGstA_ + $suAdd_;

                    // Accumulate unit-level totals
                    $totals['area']        += $suArea_;
                    $totals['base_total']  += $suBaseCol_;
                    $totals['variance']    += $suVar_;
                    $totals['gst_amount']  += $suGstA_;
                    $totals['parking']     += $suParking_;
                    $totals['grand_total'] += $suGrand_;

                    // Variance style
                    if ($suVar_ > 0)      { $varStyle_ = 'background-color: #fee2e2; color: #991b1b;'; }
                    elseif ($suVar_ < 0)  { $varStyle_ = 'background-color: #dcfce7; color: #166534;'; }
                    else                  { $varStyle_ = ''; }

                    $rowBg_  = ($globalRowCounter % 2 === 0) ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
                    $slLbl_  = (string)$globalRowCounter;
                @endphp
                <tr height="25" style="height: 25pt; text-align: center; vertical-align: middle; {{ $rowBg_ }}">
                    {{-- Section 1 --}}
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $slLbl_ }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ strtoupper($sale->customer?->name ?? '') }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $isFirst_ ? $bookingDate_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $floorD_ }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $unitD_ }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $isFirst_ ? $agreementDate_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ ($suArea_ > 0 && !$isPark_) ? $suArea_ : '' }}</td>

                    {{-- Section 2 --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ ($suExpR_ > 0 && !$isPark_) ? $suExpR_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ ($suActR_ > 0 && !$isPark_) ? $suActR_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $suBaseCol_ > 0 ? $suBaseCol_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-weight: bold; {{ $varStyle_ }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ ($suVar_ != 0 && !$isPark_) ? abs($suVar_) : '' }}</td>

                    {{-- Section 3 --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif;">{{ $suGstP_ > 0 ? number_format($suGstP_, 2) . '%' : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $suGstA_ > 0 ? $suGstA_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $suParking_ > 0 ? $suParking_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $suAdd_ > 0 ? $suAdd_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-weight: bold; background-color: #f1f5f9; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $suGrand_ > 0 ? $suGrand_ : '' }}</td>

                    {{-- Section 4: payment data only on first unit row per sale --}}
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-weight: bold; background-color: #dcfce7; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $isFirst_ ? $totalChequeValue_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $isFirst_ ? $totalReceivedCheque_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: 'dd-mmm-yyyy';">{{ $isFirst_ ? $receiptDate_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; {{ $isFirst_ ? $balanceStyle_ : '' }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $isFirst_ ? $chequeBalanceDue_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\@';">{{ $isFirst_ ? $installmentsCount_ : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; font-weight: bold; text-align: center; {{ $isFirst_ ? $pctStyle_ : '' }} font-family: 'Calibri', 'Aptos', sans-serif;">{{ $isFirst_ ? number_format($collectionPct_, 2) . '%' : '' }}</td>
                    <td style="border: 0.5pt solid #cbd5e1; text-align: center; {{ $isFirst_ ? $plStyle_ : '' }} font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $isFirst_ ? ($profitLoss_ != 0 ? abs($profitLoss_) : '0') : '' }}</td>
                </tr>
            @endforeach
        @endforeach

          <tr height="36" style="height: 36pt; font-weight: bold; color: #ffffff;">
            {{-- Section 1 Totals --}}
            <td colspan="6" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif;">TOTAL SUMMARY</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['area'] }}</td>
            {{-- Section 2 Totals --}}
            <td colspan="2" bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['base_total'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['variance'] }}</td>
            {{-- Section 3 Totals --}}
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['gst_amount'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['parking'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['additional'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['grand_total'] }}</td>
            {{-- Section 4 Totals --}}
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['cheque_value'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['total_received_cheque'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['cheque_due'] }}</td>
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; border: 0.5pt solid #475569; font-family: 'Calibri', 'Aptos', sans-serif;"></td>
            @php
                $overallCollectionPct = $totals['cheque_value'] > 0 ? ($totals['total_received_cheque'] / $totals['cheque_value'] * 100) : 0.00;
                $profitLossTotalStyle = $totals['profit_loss'] < 0 ? 'color: #fee2e2;' : ($totals['profit_loss'] > 0 ? 'color: #dcfce7;' : 'color: #ffffff;');
            @endphp
            <td bgcolor="#17365D" style="background-color: #17365D; color: #ffffff; text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif;">{{ number_format($overallCollectionPct, 2) }}%</td>
            <td bgcolor="#17365D" style="background-color: #17365D; {{ $profitLossTotalStyle }} text-align: center; border: 0.5pt solid #475569; font-size: 13pt; font-weight: bold; font-family: 'Calibri', 'Aptos', sans-serif; mso-number-format: '\#\,\#\#0';">{{ $totals['profit_loss'] }}</td>
          </tr>
    </tbody>
</table>

