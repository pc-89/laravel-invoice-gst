<head>
    <meta charset="utf-8">
    <title>Tax Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, sans-serif;
            color: #111;
            font-size: 10px;
            line-height: 1.4;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .invoice-title { font-size: 16px; font-weight: bold; color: #1a365d; margin: 0; }
        .main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 5px 6px; vertical-align: top; }
        .main-table th { background-color: #f2f2f2; font-size: 9px; text-transform: uppercase; }
        .nested-table { width: 100%; border-collapse: collapse; }
        .nested-table td { border: none; padding: 1px 0; }

        .currency {
            font-family: DejaVu Sans, sans-serif;
        }
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td><div style="border: 1px solid #000; padding: 2px 5px; font-size: 8px; display: inline-block;">TAX INVOICE - ORIGINAL FOR RECIPIENT</div></td>
            <td class="text-right"><h1 class="invoice-title">TAX INVOICE</h1></td>
        </tr>
    </table>

    <table class="main-table">
        <tr>
            {{-- <td colspan="4" style="width: 50%;">
                @if($logo)
                    <img src="{{ $logo }}" style="width: 90px; height: auto; display: block; margin-bottom: 4px;">
                @endif
                <div class="bold" style="font-size: 11px;">DILIP SUPPLIER & ENTERPRISE</div>
                <div>Gudipalli, Jagannath Prasad, Ganjam, Odisha</div>
                <div>Mobile: 7538026548 | Email: dilipsupplierandenterprises@gmail.com</div>
                <div class="bold">GSTIN: 21CWJPG6532J1ZL</div>
                <div>State: Odisha, Code: 21</div>
            </td> --}}
            <td colspan="4" style="width: 50%;">
                @if($logo)
                    <img src="{{ $logo }}" style="width: 90px; height: auto; display: block; margin-bottom: 4px;">
                @endif
                <div class="bold" style="font-size: 11px;">{{ $company->company_name }}</div>
                <div>{{ $company->address }}</div>
                <div>Mobile: {{ $company->mobile }} | Email: {{ $company->email }}</div>
                <div class="bold">GSTIN: {{ $company->gstin }}</div>
                <div>State: {{ $company->state_name }}, Code: {{ $company->state_code }}</div>
            </td>
            <td colspan="4" style="width: 50%;">
                <table class="nested-table">
                    <tr><td><span class="bold">Invoice No:</span></td><td class="text-right">{{ $invoice->invoice_no }}</td></tr>
                    <tr><td><span class="bold">Invoice Date:</span></td><td class="text-right">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td></tr>
                    <tr><td><span class="bold">PO No & Date:</span></td><td class="text-right">Dillip/KHM/2025-26/03 (20/01/2026)</td></tr>
                    <tr><td><span class="bold">Project Details:</span></td><td class="text-right">SOMSAGAR, Dist-Wanaparthy (TS)</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4">
                <div class="bold" style="font-size: 8px; color: #555;">BILL TO (BUYER)</div>
                <div class="bold" style="font-size: 11px;">{{ $invoice->client_name }}</div>
                <div>{!! nl2br(e($invoice->client_address)) !!}</div>
                <div class="bold">GSTIN: {{ $invoice->client_gstin }}</div>
            </td>
            <td colspan="4">
                <div class="bold" style="font-size: 8px; color: #555;">SHIP TO (DELIVERY TARGET)</div>
                <div class="bold" style="font-size: 11px;">{{ $invoice->client_name }}</div>
                <div>PLOT NO-515, MAHAVIR NAGAR, OLD TOWN, BHUBANESWAR, ODISHA 751002</div>
            </td>
        </tr>

        <tr style="background-color: #f2f2f2;">
            <th class="text-center" style="width: 4%;">#</th>
            <th style="width: 40%;">Description of Services / Milestone</th>
            <th class="text-center" style="width: 8%;">SAC</th>
            <th class="text-center" style="width: 6%;">Qty</th>
            <th class="text-right" style="width: 10%;">Rate</th>
            <th class="text-right" style="width: 10%;">Taxable Value</th>
            <th class="text-right" style="width: 10%;">CGST (9%)</th>
            <th class="text-right" style="width: 12%;">SGST (9%)</th>
        </tr>

        @foreach($invoice->items as $index => $item)
        @php
            // 🌟 FIXED: Removed division by 100 since columns now store the actual decimal values
            $rateDecimal = $item->rate;
            $taxableValue = $item->qty * $rateDecimal;

            $cgstAmount = $taxableValue * 0.09;
            $sgstAmount = $taxableValue * 0.09;
            $totalRowAmount = $taxableValue + $cgstAmount + $sgstAmount;
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td><span class="bold">{{ $item->description }}</span></td>
            <td class="text-center">995426</td>
            <td class="text-center">{{ $item->qty }}</td>
            <td class="text-right currency">&#8377;{{ number_format($rateDecimal, 2) }}</td>
            <td class="text-right currency">&#8377;{{ number_format($taxableValue, 2) }}</td>
            <td class="text-right currency">&#8377;{{ number_format($cgstAmount, 2) }}</td>
            <td class="text-right currency">&#8377;{{ number_format($sgstAmount, 2) }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="5" rowspan="4" class="bank-details" style="font-size: 9px;">
                <div class="bold" style="text-decoration: underline;">Bank Settlement Parameters:</div>
                {{-- <div>Account Name: <span class="bold">DILLIP KUMAR GOUDA</span></div>
                <div>Account No: <span class="bold">185211010000050</span></div>
                <div>Bank Name & Branch: <span class="bold">UNION BANK, BELLAGUNTHA</span></div>
                <div>IFSC Code: <span class="bold">UBIN0818526</span></div> --}}
                <div>Account Name: <span class="bold">{{ $company->account_name }}</span></div>
                <div>Account No: <span class="bold">{{ $company->account_no }}</span></div>
                <div>Bank Name & Branch: <span class="bold">{{ $company->bank_name }}</span></div>
                <div>IFSC Code: <span class="bold">{{ $company->ifsc_code }}</span></div>

                <div style="margin-top: 10px;">
                    <span class="bold">Total Amount in Words:</span><br>
                    <span style="font-style: italic; text-transform: capitalize;">INR {{ $amountInWords }} Only.</span>
                </div>
            </td>
            <td colspan="2"><span class="bold">Subtotal (Taxable):</span></td>
            <td class="text-right currency">&#8377;{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">Central Tax (CGST 9%):</td>
            <td class="text-right currency">&#8377;{{ number_format($invoice->cgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">State Tax (SGST 9%):</td>
            <td class="text-right currency">&#8377;{{ number_format($invoice->sgst_amount, 2) }}</td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td colspan="2"><span class="bold">Grand Total (Net):</span></td>
            <td class="text-right bold currency" style="font-size: 11px;">&#8377;{{ number_format($invoice->grand_total, 2) }}</td>
        </tr>

        <tr>
            <td colspan="4" style="font-size: 8px; color: #444;">
                <div class="bold">Terms & Conditions:</div>
                <div>1. Items declared match implementation structural guidelines.</div>
                <div>2. Disputes subject to Ganjam (Odisha) jurisdiction boundaries.</div>
            </td>
            <td colspan="4" class="text-center" style="vertical-align: middle;">
                {{-- <div>For <span class="bold">M/S DILIP SUPPLIER & ENTERPRISE</span></div> --}}
                <div>For <span class="bold">M/S {{ $company->company_name }}</span></div>
                <div style="height: 30px;"></div>
                <div class="bold" style="text-decoration: overline; font-size: 9px;">Proprietor / Authorised Signatory</div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="font-size: 8px; color: #666; vertical-align: bottom;">Certified that the particulars given above are true and correct.</td>
            <td class="text-right" style="width: 100px;">
                <div style="display: inline-block; padding: 3px; border: 1px dashed #777;">
                    @if($qrCodeSvg)
                        <img src="{{ $qrCodeSvg }}" style="width: 100px; height: 100px; display: block;">
                    @endif
                    <div style="font-size: 7px; text-align: center; margin-top: 4px; color: #444;">Verify Details</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
