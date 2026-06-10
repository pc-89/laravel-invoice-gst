<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        .title { font-size: 16pt; font-weight: bold; color: #1a365d; text-align: center; }
        .header-bg { background-color: #f2f2f2; font-weight: bold; border: 0.5pt solid #000000; text-align: center; }
        .border-cell { border: 0.5pt solid #000000; font-size: 10pt; }
        .bold { font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr><td colspan="8" class="title">TAX INVOICE</td></tr>
        <tr><td colspan="8"></td></tr>

        <tr>
            <td colspan="4" class="border-cell bold" style="font-size: 12pt;">DILIP SUPPLIER & ENTERPRISE</td>
            <td colspan="4" class="border-cell bold">Invoice Details</td>
        </tr>
        <tr>
            <td colspan="4" class="border-cell">Gudipalli, Jagannath Prasad, Ganjam, Odisha</td>
            <td colspan="2" class="border-cell bold">Invoice No:</td>
            <td colspan="2" class="border-cell text-right">{{ $invoice->invoice_no }}</td>
        </tr>
        <tr>
            <td colspan="4" class="border-cell">GSTIN: 21CWJPG6532J1ZL | Code: 21</td>
            <td colspan="2" class="border-cell bold">Invoice Date:</td>
            <td colspan="2" class="border-cell text-right">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td colspan="4" class="border-cell bold" style="background-color: #e2e8f0;">Billed To (Recipient)</td>
            <td colspan="4" class="border-cell bold" style="background-color: #e2e8f0;">Project Shipping Execution Target</td>
        </tr>
        <tr>
            <td colspan="4" class="border-cell bold">{{ $invoice->client_name }}</td>
            <td colspan="4" class="border-cell">SOMSAGAR, Dist-Wanaparthy, Telangana, India</td>
        </tr>
        <tr>
            <td colspan="4" class="border-cell">{{ $invoice->client_address }}</td>
            <td colspan="4" class="border-cell bold">Buyer GSTIN: {{ $invoice->client_gstin }}</td>
        </tr>

        <tr><td colspan="8"></td></tr>

        <tr>
            <td class="header-bg" style="width: 5px;">#</td>
            <td class="header-bg" style="width: 40px;">Description of Services</td>
            <td class="header-bg">SAC</td>
            <td class="header-bg">Qty</td>
            <td class="header-bg">Rate</td>
            <td class="header-bg">Taxable Value</td>
            <td class="header-bg">CGST (9%)</td>
            <td class="header-bg">SGST (9%)</td>
        </tr>

        @foreach($invoice->items as $index => $item)
        @php
            $rate = $item->rate / 100;
            $taxable = $item->qty * $rate;
            $cgst = $taxable * 0.09;
            $sgst = $taxable * 0.09;
        @endphp
        <tr>
            <td class="border-cell text-center">{{ $index + 1 }}</td>
            <td class="border-cell bold">{{ $item->description }}</td>
            <td class="border-cell text-center">995426</td>
            <td class="border-cell text-center">{{ $item->qty }}</td>
            <td class="border-cell text-right">{{ number_format($rate, 2, '.', '') }}</td>
            <td class="border-cell text-right">{{ number_format($taxable, 2, '.', '') }}</td>
            <td class="border-cell text-right">{{ number_format($cgst, 2, '.', '') }}</td>
            <td class="border-cell text-right">{{ number_format($sgst, 2, '.', '') }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="4" rowspan="4" class="border-cell" style="vertical-align: top;">
                <span class="bold">Bank Account Particulars:</span><br>
                Acc Name: DILLIP KUMAR GOUDA<br>
                Acc No: 185211010000050<br>
                Bank/Branch: UNION BANK, BELLAGUNTHA<br>
                IFSC: UBIN0818526
            </td>
            <td colspan="3" class="border-cell bold">Subtotal (Taxable):</td>
            <td class="border-cell text-right bold">{{ number_format($invoice->subtotal / 100, 2, '.', '') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="border-cell">Central Tax (CGST 9%):</td>
            <td class="border-cell text-right">{{ number_format($invoice->cgst_total / 100, 2, '.', '') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="border-cell">State Tax (SGST 9%):</td>
            <td class="border-cell text-right">{{ number_format($invoice->sgst_total / 100, 2, '.', '') }}</td>
        </tr>
        <tr style="background-color: #cbd5e1;">
            <td colspan="3" class="border-cell bold">Grand Total (Net Amount):</td>
            <td class="border-cell text-right bold">{{ number_format($invoice->grand_total / 100, 2, '.', '') }}</td>
        </tr>

        <tr>
            <td colspan="8" class="border-cell bold" style="font-style: italic;">
                Amount in Words: INR {{ $amountInWords }} Only.
            </td>
        </tr>
    </table>
</body>
</html>
