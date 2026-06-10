<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function create()
    {
        return view('invoice.create');
    }

    public function store_backup(Request $request)
    {
        $subtotal = 0;

        // 1. Calculate subtotal natively using input decimal values
        foreach ($request->qty as $key => $qty) {
            $subtotal += ($qty * $request->rate[$key]);
        }

        $cgst = $subtotal * 9 / 100;
        $sgst = $subtotal * 9 / 100;
        $grandTotal = $subtotal + $cgst + $sgst;

        // 2. 🌟 FIXED: Save the values exactly as they are without multiplying by 100
        $invoice = Invoice::create([
            'invoice_no'     => 'INV-' . time(),
            'invoice_date'   => now(),
            'client_name'    => $request->client_name,
            'client_address' => $request->client_address,
            'client_gstin'   => $request->client_gstin,
            'subtotal'       => $subtotal,
            'cgst_rate'      => 9,
            'cgst_amount'    => $cgst,
            'sgst_rate'      => 9,
            'sgst_amount'    => $sgst,
            'grand_total'    => $grandTotal
        ]);

        foreach ($request->description as $key => $description) {
            $itemSubtotal = $request->qty[$key] * $request->rate[$key];

            // 3. 🌟 FIXED: Save item details safely straight to your NUMERIC columns
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'description' => $description,
                'qty'         => $request->qty[$key],
                'rate'        => $request->rate[$key], // Saves 1050 as 1050.00 perfectly
                'amount'      => $itemSubtotal
            ]);
        }

        return redirect('/invoice/' . $invoice->id);
    }

    public function store(Request $request)
{
    $subtotal = 0;

    foreach ($request->qty as $key => $qty) {
        $subtotal += ($qty * $request->rate[$key]);
    }

    $cgst = $subtotal * 9 / 100;
    $sgst = $subtotal * 9 / 100;
    $grandTotal = $subtotal + $cgst + $sgst;

    // 🌟 FIXED: Added 'user_id' mapping reference to bind ownership securely
    $invoice = Invoice::create([
        'user_id'        => auth()->id(), // 🚀 Saves the logged-in user's ID
        'invoice_no'     => 'INV-' . time(),
        'invoice_date'   => now(),
        'client_name'    => $request->client_name,
        'client_address' => $request->client_address,
        'client_gstin'   => $request->client_gstin,
        'subtotal'       => $subtotal,
        'cgst_rate'      => 9,
        'cgst_amount'    => $cgst,
        'sgst_rate'      => 9,
        'sgst_amount'    => $sgst,
        'grand_total'    => $grandTotal
    ]);

    foreach ($request->description as $key => $description) {
        $itemSubtotal = $request->qty[$key] * $request->rate[$key];

        InvoiceItem::create([
            'invoice_id'  => $invoice->id,
            'description' => $description,
            'qty'         => $request->qty[$key],
            'rate'        => $request->rate[$key],
            'amount'      => $itemSubtotal
        ]);
    }

    return redirect('/invoice/' . $invoice->id);
}

    public function show($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        return view('invoice.preview', compact('invoice'));
    }

   public function pdf_working_10_06_2026($id)
    {
        ini_set('memory_limit', '256M');

        $invoice = Invoice::with(['items' => function($query) {
            $query->select('id', 'invoice_id', 'description', 'qty', 'rate', 'amount');
        }])->findOrFail($id);

        // 1. Process inline company logo safely
        $logoPath = public_path('company/logo.png');
        $logoBase64Data = null;
        if (file_exists($logoPath)) {
            $logoBase64Data = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // 2. Format localized Indian compliance structured payload
        // 🌟 FIXED: Removed "/ 100" to capture the actual standard decimal values
        $qrData = json_encode([
            'Ino'   => $invoice->invoice_no,
            'Gstin' => '21CWJPG6532J1ZL',
            'Amt'   => number_format($invoice->grand_total, 2, '.', '')
        ]);

        $rawSvg = QrCode::size(100)->errorCorrection('M')->generate($qrData);
        $cleanSvg = preg_replace('/<\?xml.*\?>/', '', $rawSvg);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(trim($cleanSvg));

        // 🌟 FIXED: Removed "/ 100" so numbers map to words properly
        $amountInWords = $this->convertAmountToWords($invoice->grand_total);

        return Pdf::loadView('invoice.pdf', [
            'invoice'       => $invoice,
            'logo'          => $logoBase64Data,
            'qrCodeSvg'     => $qrCodeBase64,
            'amountInWords' => $amountInWords
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled'    => true,
            'isRemoteEnabled'         => false,
            'isFontSubsettingEnabled' => true,
            'chroot'                  => public_path()
        ])
        ->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }

    /////////////////////////
    public function excel_working_10_06_2026($id)
    {
        ini_set('memory_limit', '256M');

        $invoice = Invoice::with(['items' => function($query) {
            $query->select('id', 'invoice_id', 'description', 'qty', 'rate', 'amount');
        }])->findOrFail($id);

        // 🌟 FIXED: Removed "/ 100" to synchronize word mapping with values
        $amountInWords = $this->convertAmountToWords($invoice->grand_total);
        $fileName = 'Invoice_' . $invoice->invoice_no . '.xls';

        return response()->view('invoice.excel', [
            'invoice'       => $invoice,
            'amountInWords' => $amountInWords
        ])
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"")
        ->header('Cache-Control', 'max-age=0');
    }

    public function pdf($id)
    {
        ini_set('memory_limit', '256M');

        // 1. Fetch the invoice and eager-load items
        $invoice = Invoice::with(['items' => function($query) {
            $query->select('id', 'invoice_id', 'description', 'qty', 'rate', 'amount');
        }])->findOrFail($id);
        // 2. 🌟 FULLY DYNAMIC: Fetch the explicit company record for this invoice creator
        // If the user forgot to set up their company profile, it aborts with a clear reminder.
        $company = \App\Models\CompanyDetail::where('user_id', $invoice->user_id)->first();
        // dd($invoice->user_id);
        if (!$company) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Please complete your Company Profile details before downloading documents.');
        }

        // 3. Process inline company logo safely
        $logoPath = public_path('company/logo.png');
        $logoBase64Data = null;
        if (file_exists($logoPath)) {
            $logoBase64Data = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // 4. Format compliance QR payload using the dynamic GSTIN from your table
        $qrData = json_encode([
            'Ino'   => $invoice->invoice_no,
            'Gstin' => $company->gstin, // 🌟 Pulls straight from your table field
            'Amt'   => number_format($invoice->grand_total, 2, '.', '')
        ]);

        $rawSvg = QrCode::size(100)->errorCorrection('M')->generate($qrData);
        $cleanSvg = preg_replace('/<\?xml.*\?>/', '', $rawSvg);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(trim($cleanSvg));

        $amountInWords = $this->convertAmountToWords($invoice->grand_total);

        return Pdf::loadView('invoice.pdf', [
            'invoice'       => $invoice,
            'company'       => $company, // 🌟 Sent to Blade (no more static fields)
            'logo'          => $logoBase64Data,
            'qrCodeSvg'     => $qrCodeBase64,
            'amountInWords' => $amountInWords
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled'    => true,
            'isRemoteEnabled'         => false,
            'isFontSubsettingEnabled' => true,
            'chroot'                  => public_path()
        ])
        ->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }

    /////////////////////////
    public function excel($id)
    {
        ini_set('memory_limit', '256M');

        $invoice = Invoice::with(['items' => function($query) {
            $query->select('id', 'invoice_id', 'description', 'qty', 'rate', 'amount');
        }])->findOrFail($id);

        // 🌟 FULLY DYNAMIC: Fetch the explicit company record for this invoice creator
        $company = \App\Models\CompanyDetail::where('user_id', $invoice->user_id)->first();

        if (!$company) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Please complete your Company Profile details before exporting spreadsheets.');
        }

        $amountInWords = $this->convertAmountToWords($invoice->grand_total);
        $fileName = 'Invoice_' . $invoice->invoice_no . '.xls';

        return response()->view('invoice.excel', [
            'invoice'       => $invoice,
            'company'       => $company, // 🌟 Sent to Blade (no more static fields)
            'amountInWords' => $amountInWords
        ])
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"")
        ->header('Cache-Control', 'max-age=0');
    }


    private function convertAmountToWords($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null; $digits_length = strlen($no); $i = 0; $str = array();
        $words = array(
            0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen',
            20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        );
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100; $number = floor($no % $divider); $no = floor($no / $divider); $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter].$plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $Paise = ($decimal > 0) ? " and " . ($words[$decimal / 10 * 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $Paise;
    }
}
