<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tax Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: sans-serif; color: #111; font-size: 11px; line-height: 1.4; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }

        .invoice-title { font-size: 18px; font-weight: bold; letter-spacing: 1px; margin: 0; padding-bottom: 5px; }
        .original-tag { border: 1px solid #000; padding: 3px 8px; font-size: 9px; display: inline-block; text-transform: uppercase; }

        .main-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        .main-table th { background-color: #f2f2f2; font-weight: bold; font-size: 10px; }

        .nested-table { width: 100%; border-collapse: collapse; }
        .nested-table td { border: none; padding: 2px 0; }

        .bank-details { font-size: 10px; line-height: 1.5; }
        .terms { font-size: 9px; color: #555; padding-top: 10px; }
        .signature-space { height: 45px; }
    </style>
</head>
<body>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td>
                <div class="original-tag">Tax Invoice - Original for Recipient</div>
            </td>
            <td class="text-right">
                <h1 class="invoice-title" style="color: #1a365d;">TAX INVOICE</h1>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <tr>
            <td colspan="4" style="width: 50%;">
                @if($logo)
                    <img src="{{ $logo }}" style="width: 100px; height: auto; margin-bottom: 5px;"><br>
                @endif
                <div class="bold" style="font-size: 13px;">ABC CONSTRUCTION PVT LTD</div>
                <div>123, Business Park, Sector 5, Salt Lake</div>
                <div>Kolkata, West Bengal, 700091</div>
                <div class="bold">GSTIN: 19AAACA1234A1Z5</div>
                <div>State Name: West Bengal, Code: 19</div>
            </td>
            <td colspan="4" style="width: 50%;">
                <table class="nested-table">
                    <tr><td><span class="bold">Invoice No:</span></td><td class="text-right">{{ $invoice->invoice_no }}</td></tr>
                    <tr><td><span class="bold">Date of Issue:</span></td><td class="text-right">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td></tr>
                    <tr><td><span class="bold">Place of Supply:</span></td><td class="text-right">West Bengal (Code 19)</td></tr>
                    <tr><td><span class="bold">Reverse Charge:</span></td><td class="text-right">No</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4">
                <div class="bold" style="text-transform: uppercase; color: #555; font-size: 9px; margin-bottom: 3px;">Billed To (Recipient)</div>
                <div class="bold" style="font-size: 11px;">{{ $invoice->client_name }}</div>
                <div>{!! nl2br(e($invoice->client_address)) !!}</div>
                <div class="bold">GSTIN: {{ $invoice->client_gstin }}</div>
            </td>
            <td colspan="4">
                <div class="bold" style="text-transform: uppercase; color: #555; font-size: 9px; margin-bottom: 3px;">Shipped To</div>
                <div class="bold" style="font-size: 11px;">{{ $invoice->client_name }}</div>
                <div>{!! nl2br(e($invoice->client_address)) !!}</div>
            </td>
        </tr>

        <tr style="background-color: #f2f2f2;">
            <th style="width: 5%;" class="text-center">#</th>
            <th style="width: 35%;">Description of Goods / Services</th>
            <th style="width: 8%;" class="text-center">Qty</th>
            <th style="width: 12%;" class="text-right">Rate</th>
            <th style="width: 14%;" class="text-right">Taxable Value</th>
            <th style="width: 12%;" class="text-right">CGST</th>
            <th style="width: 12%;" class="text-right">SGST</th>
            <th style="width: 12%;" class="text-right">Total Amount</th>
        </tr>

        @foreach($invoice->items as $index => $item)
        @php
            // Assume an overall invoice fallback parameter if the explicit rate isn't set per line
            $itemGstRate = isset($item->gst_rate) ? $item->gst_rate : 18;

            // Reverse-calculate components from your schema columns
            $itemRateDecimal = $item->rate / 100;
            $taxableValueDecimal = $item->qty * $itemRateDecimal;

            $cgstAmountDecimal = $taxableValueDecimal * (($itemGstRate / 2) / 100);
            $sgstAmountDecimal = $taxableValueDecimal * (($itemGstRate / 2) / 100);

            $totalAmountDecimal = $item->amount / 100;
        @endphp
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td><span class="bold">{{ $item->description }}</span></td>
            <td class="text-center">{{ $item->qty }}</td>
            <td class="text-right">₹{{ number_format($itemRateDecimal, 2) }}</td>
            <td class="text-right">₹{{ number_format($taxableValueDecimal, 2) }}</td>
            <td class="text-right">₹{{ number_format($cgstAmountDecimal, 2) }}<br><small style="color:#555;">({{ $itemGstRate / 2 }}%)</small></td>
            <td class="text-right">₹{{ number_format($sgstAmountDecimal, 2) }}<br><small style="color:#555;">({{ $itemGstRate / 2 }}%)</small></td>
            <td class="text-right bold">₹{{ number_format($totalAmountDecimal, 2) }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="5" rowspan="4" class="bank-details">
                <div class="bold" style="text-decoration: underline; margin-bottom: 2px;">Bank Details:</div>
                <div>Account Name: <span class="bold">ABC CONSTRUCTION PVT LTD</span></div>
                <div>Account No: <span class="bold">98765432101234</span></div>
                <div>Bank & Branch: <span class="bold">HDFC Bank, Salt Lake Sector 5</span></div>
                <div>IFSC Code: <span class="bold">HDFC0001234</span></div>

                <div style="margin-top: 12px;">
                    <span class="bold">Total Amount in Words:</span><br>
                    <span style="font-style: italic; text-transform: capitalize;">
                        INR {{ $amountInWords }} Only.
                    </span>
                </div>
            </td>
            <td colspan="2"><span class="bold">Subtotal (Taxable):</span></td>
            <td class="text-right">₹{{ number_format($invoice->subtotal / 100, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">Central Tax (CGST):</td>
            <td class="text-right">₹{{ number_format($invoice->cgst_total / 100, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">State Tax (SGST):</td>
            <td class="text-right">₹{{ number_format($invoice->sgst_total / 100, 2) }}</td>
        </tr>
        <tr style="background-color: #e2e8f0;">
            <td colspan="2"><span class="bold">Grand Total (Net):</span></td>
            <td class="text-right bold" style="font-size: 12px;">₹{{ number_format($invoice->grand_total / 100, 2) }}</td>
        </tr>

        <tr>
            <td colspan="4" class="terms">
                <div class="bold">Terms & Conditions:</div>
                <div>1. Goods once sold will not be taken back or exchanged.</div>
                <div>2. Interest @18% p.a. will be charged if payment is delayed.</div>
                <div>3. All disputes are subject to local jurisdiction only.</div>
            </td>
            <td colspan="4" class="text-center" style="vertical-align: middle;">
                <div style="margin-bottom: 5px;">For <span class="bold">ABC CONSTRUCTION PVT LTD</span></div>
                <div class="signature-space"></div>
                <div class="bold" style="text-decoration: overline;">Authorised Signatory</div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 15px; width: 100%;">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 9px; color: #666; vertical-align: bottom;">
                    Certified that the particulars given above are true and correct.
                </td>
                <td class="text-right" style="width: 120px;">
                    <div style="display: inline-block; padding: 4px; border: 1px dashed #bbb;">
                        {!! $qrCodeSvg !!}
                        <div style="font-size: 8px; text-align: center; margin-top: 2px; color: #444;">Verify Details</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
