@php
// ── Totals (Direct assignment without division) ──────────────────
$subtotal   = $invoice->subtotal;
$cgstTotal  = $invoice->cgst_total;
$sgstTotal  = $invoice->sgst_total;
$grandTotal = $invoice->grand_total;

// ── Shared inline style fragments ────────────────────────────────
$border  = 'border:1pt solid #000000;';
$bdrBlue = 'border:1pt solid #1a365d;';
$bdrNone = 'border:none;';
$font    = 'font-family:Arial,sans-serif;font-size:10pt;color:#000000;';
$pad     = 'padding:5pt 7pt;';
$padLg   = 'padding:8pt 10pt;';
@endphp
<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
@php echo ''; @endphp
<style>
  body  { margin:0; padding:0; font-family:Arial,sans-serif; font-size:10pt; }
  table { border-collapse:collapse; width:100%; table-layout:fixed; }
  td    { font-family:Arial,sans-serif; font-size:10pt; color:#000000; vertical-align:middle; }
</style>
</head>
<body>
<table>

<tr>
  <td colspan="8" style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:16pt;font-weight:bold;text-align:center;letter-spacing:3pt;padding:10pt;">
    TAX &nbsp; INVOICE
  </td>
</tr>
<tr>
  <td colspan="8" style="{{ $bdrBlue }}background-color:#2d5086;color:#c8d8f0;font-family:Arial,sans-serif;font-size:9pt;font-style:italic;text-align:center;padding:3pt 7pt;">
    Original for Recipient
  </td>
</tr>

<tr>
  <td colspan="4" style="{{ $bdrBlue }}background-color:#2b4a7c;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;{{ $pad }}">
    Supplier Details
  </td>
  <td colspan="4" style="{{ $bdrBlue }}background-color:#2b4a7c;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;{{ $pad }}">
    Invoice Details
  </td>
</tr>

<tr>
  {{-- <td colspan="4" rowspan="3" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;vertical-align:top;{{ $padLg }}">
    <div style="font-size:13pt;font-weight:bold;color:#1a365d;margin-bottom:4pt;">DILIP SUPPLIER &amp; ENTERPRISE</div>
    <div style="color:#333333;font-size:9.5pt;margin-bottom:2pt;">Gudipalli, Jagannath Prasad, Ganjam, Odisha</div>
    <div style="color:#333333;font-size:9.5pt;margin-bottom:2pt;">Ph: 7538026548 &nbsp;|&nbsp; dilipsupplierandenterprises@gmail.com</div>
    <div style="font-size:9.5pt;margin-top:4pt;"><b>GSTIN:</b> 21CWJPG6532J1ZL &nbsp;|&nbsp; <b>State Code:</b> 21 (Odisha)</div>
  </td> --}}
  <td colspan="4" rowspan="3" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;vertical-align:top;{{ $padLg }}">
    <div style="font-size:13pt;font-weight:bold;color:#1a365d;margin-bottom:4pt;">{{ $company->company_name }}</div>
    <div style="color:#333333;font-size:9.5pt;margin-bottom:2pt;">{{ $company->address }}</div>
    <div style="color:#333333;font-size:9.5pt;margin-bottom:2pt;">Ph: {{ $company->mobile }} &nbsp;|&nbsp; {{ $company->email }}</div>
    <div style="font-size:9.5pt;margin-top:4pt;"><b>GSTIN:</b> {{ $company->gstin }} &nbsp;|&nbsp; <b>State Code:</b> {{ $company->state_code }} ({{ $company->state_name }})</div>
  </td>
  <td colspan="2" style="{{ $border }}background-color:#dce8f5;font-family:Arial,sans-serif;font-size:10pt;font-weight:bold;color:#1a365d;{{ $pad }}">Invoice No:</td>
  <td colspan="2" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:11pt;font-weight:bold;text-align:right;{{ $pad }}">{{ $invoice->invoice_no }}</td>
</tr>
<tr>
  <td colspan="2" style="{{ $border }}background-color:#dce8f5;font-family:Arial,sans-serif;font-size:10pt;font-weight:bold;color:#1a365d;{{ $pad }}">Invoice Date:</td>
  <td colspan="2" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</td>
</tr>
<tr>
  <td colspan="2" style="{{ $border }}background-color:#dce8f5;font-family:Arial,sans-serif;font-size:10pt;font-weight:bold;color:#1a365d;{{ $pad }}">PO No &amp; Date:</td>
  <td colspan="2" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:9pt;text-align:right;{{ $pad }}">{{ $invoice->po_number ?? 'N/A' }}</td>
</tr>

<tr>
  <td colspan="4" style="{{ $bdrBlue }}background-color:#2b4a7c;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;{{ $pad }}">
    Billed To (Buyer)
  </td>
  <td colspan="4" style="{{ $bdrBlue }}background-color:#2b4a7c;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;{{ $pad }}">
    Project Shipping Target Location
  </td>
</tr>
<tr>
  <td colspan="4" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;vertical-align:top;{{ $padLg }}">
    <div style="font-size:12pt;font-weight:bold;color:#1a365d;margin-bottom:3pt;">{{ $invoice->client_name }}</div>
    <div style="color:#333333;font-size:9.5pt;margin-bottom:3pt;">{{ $invoice->client_address }}</div>
    <div style="font-size:9.5pt;"><b>GSTIN:</b> {{ $invoice->client_gstin }}</div>
  </td>
  <td colspan="4" style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;vertical-align:top;{{ $padLg }}">
    <div style="font-size:12pt;font-weight:bold;color:#1a365d;margin-bottom:3pt;">{{ $invoice->client_name }}</div>
    <div style="color:#333333;font-size:9.5pt;margin-bottom:3pt;">{{ $invoice->shipping_address ?? $invoice->client_address }}</div>
    <div style="font-size:9.5pt;"><b>Project:</b> SOMSAGAR, Dist-Wanaparthy (TS)</div>
  </td>
</tr>

<tr>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:4%;">#</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:30%;">Description of Goods / Services</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:8%;">SAC</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:7%;">Qty</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:13%;">Rate (&#8377;)</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:14%;">Taxable Value (&#8377;)</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:12%;">CGST 9% (&#8377;)</td>
  <td style="{{ $bdrBlue }}background-color:#1a365d;color:#ffffff;font-family:Arial,sans-serif;font-size:9.5pt;font-weight:bold;text-align:center;padding:6pt 4pt;width:12%;">SGST 9% (&#8377;)</td>
</tr>

@foreach($invoice->items as $index => $item)
@php
  // 🌟 FIXED: Removed division by 100 since the rate field stores the actual decimal values
  $rate    = $item->rate;
  $taxable = $item->qty * $rate;
  $cgst    = $taxable * 0.09;
  $sgst    = $taxable * 0.09;
  $rowBg   = $index % 2 === 0 ? '#ffffff' : '#f0f5fb';
@endphp
<tr>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:center;{{ $pad }}">{{ $index + 1 }}</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;font-weight:bold;{{ $pad }}">{{ $item->description }}</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:center;color:#555555;{{ $pad }}">995426</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:center;{{ $pad }}">{{ $item->qty }}</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ number_format($rate, 2) }}</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ number_format($taxable, 2) }}</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ number_format($cgst, 2) }}</td>
  <td style="{{ $border }}background-color:{{ $rowBg }};font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ number_format($sgst, 2) }}</td>
</tr>
@endforeach

<tr>
  <td colspan="4" rowspan="4" style="{{ $border }}background-color:#f7f9fd;font-family:Arial,sans-serif;font-size:9pt;vertical-align:top;padding:10pt;">
    <div style="font-weight:bold;font-size:10.5pt;color:#1a365d;text-decoration:underline;margin-bottom:6pt;">Bank Settlement Parameters</div>
    <table style="border-collapse:collapse;width:100%;">
      {{-- <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;width:42%;">Account Name</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;">DILLIP KUMAR GOUDA</td>
      </tr>
      <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;">Account No</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;;vnd.ms-excel.numberformat:@;">185211010000050</td>
      </tr>
      <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;">Bank / Branch</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;">UNION BANK OF INDIA, BELLAGUNTHA</td>
      </tr>
      <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;">IFSC Code</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;">UBIN0818526</td>
      </tr> --}}
      <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;width:42%;">Account Name</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;">{{ $company->account_name }}</td>
     </tr>
    <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;">Account No</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;vnd.ms-excel.numberformat:@;">{{ $company->account_no }}</td>
    </tr>
    <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;">Bank / Branch</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;">{{ $company->bank_name }}</td>
    </tr>
    <tr>
        <td style="{{ $bdrNone }}padding:2pt 0;color:#555555;font-family:Arial,sans-serif;font-size:9pt;">IFSC Code</td>
        <td style="{{ $bdrNone }}padding:2pt 0;font-weight:bold;font-family:Arial,sans-serif;font-size:9pt;">{{ $company->ifsc_code }}</td>
    </tr>
    </table>
  </td>
  <td colspan="3" style="{{ $border }}background-color:#edf2f9;font-family:Arial,sans-serif;font-size:10pt;font-weight:bold;text-align:right;color:#1a365d;{{ $pad }}">Subtotal (Taxable Value):</td>
  <td style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;font-weight:bold;text-align:right;{{ $pad }}">{{ number_format($subtotal, 2) }}</td>
</tr>
<tr>
  <td colspan="3" style="{{ $border }}background-color:#edf2f9;font-family:Arial,sans-serif;font-size:10pt;text-align:right;color:#1a365d;{{ $pad }}">Central Tax (CGST @ 9%):</td>
  <td style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ number_format($cgstTotal, 2) }}</td>
</tr>
<tr>
  <td colspan="3" style="{{ $border }}background-color:#edf2f9;font-family:Arial,sans-serif;font-size:10pt;text-align:right;color:#1a365d;{{ $pad }}">State Tax (SGST @ 9%):</td>
  <td style="{{ $border }}background-color:#ffffff;font-family:Arial,sans-serif;font-size:10pt;text-align:right;{{ $pad }}">{{ number_format($sgstTotal, 2) }}</td>
</tr>
<tr>
  <td colspan="3" style="{{ $border }}background-color:#1a365d;font-family:Arial,sans-serif;font-size:11pt;font-weight:bold;text-align:right;color:#ffffff;{{ $pad }}">Grand Total (Net Payable):</td>
  <td style="{{ $border }}background-color:#1a365d;font-family:Arial,sans-serif;font-size:12pt;font-weight:bold;text-align:right;color:#ffffff;{{ $pad }}">&#8377; {{ number_format($grandTotal, 2) }}</td>
</tr>

<tr>
  <td colspan="8" style="border:1pt solid #4a90d9;background-color:#ebf4ff;font-family:Arial,sans-serif;font-size:10pt;font-style:italic;font-weight:bold;color:#1a365d;padding:7pt;">
    Amount in Words: &nbsp; INR {{ $amountInWords }} Only.
  </td>
</tr>

<tr>
  <td colspan="4" style="{{ $border }}background-color:#f7f9fd;font-family:Arial,sans-serif;font-size:8.5pt;color:#444444;vertical-align:top;{{ $padLg }}">
    <div style="font-weight:bold;color:#1a365d;margin-bottom:4pt;">System Verification Data:</div>
    {{-- <div>INV-NO: {{ $invoice->invoice_no }} &nbsp;|&nbsp; SELLER-GST: 21CWJPG6532J1ZL</div> --}}
    <div>INV-NO: {{ $invoice->invoice_no }} &nbsp;|&nbsp; SELLER-GST: {{ $company->gstin }}</div>

    <div style="margin-top:2pt;">TOTAL-NET: &#8377;{{ number_format($grandTotal, 2) }}</div>
    <div style="margin-top:6pt;font-size:8pt;color:#777777;font-style:italic;">* Computer-generated invoice. No physical signature required.</div>
  </td>
  <td colspan="4" style="{{ $border }}background-color:#f7f9fd;font-family:Arial,sans-serif;font-size:9pt;text-align:center;vertical-align:bottom;padding-bottom:10pt;height:60pt;">
    <div style="border-top:1.5pt solid #1a365d;padding-top:5pt;font-weight:bold;color:#1a365d;">
      {{-- For DILIP SUPPLIER &amp; ENTERPRISE --}}
      For {{ $company->company_name }}
    </div>
    <div style="font-size:8.5pt;color:#666666;margin-top:3pt;">Authorised Signatory / Proprietor</div>
  </td>
</tr>

</table>
</body>
</html>
