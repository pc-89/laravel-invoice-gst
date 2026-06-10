<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
// use Endroid\QrCode\QrCode;
// use Endroid\QrCode\Writer\PngWriter;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function create()
    {
        return view('invoice.create');
    }

    public function store(Request $request)
    {
        $subtotal = 0;

        foreach ($request->qty as $key => $qty) {
            $subtotal += (
                $qty *
                $request->rate[$key]
            );
        }

        $cgst = $subtotal * 9 / 100;
        $sgst = $subtotal * 9 / 100;

        $grandTotal =
            $subtotal +
            $cgst +
            $sgst;

        $invoice = Invoice::create([
            'invoice_no' => 'INV-'.time(),
            'invoice_date' => now(),
            'client_name' => $request->client_name,
            'client_address' => $request->client_address,
            'client_gstin' => $request->client_gstin,
            'subtotal' => $subtotal,
            'cgst_rate' => 9,
            'cgst_amount' => $cgst,
            'sgst_rate' => 9,
            'sgst_amount' => $sgst,
            'grand_total' => $grandTotal
        ]);

        foreach ($request->description as $key => $description) {

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $description,
                'qty' => $request->qty[$key],
                'rate' => $request->rate[$key],
                'amount' => (
                    $request->qty[$key] *
                    $request->rate[$key]
                )
            ]);
        }

        return redirect('/invoice/'.$invoice->id);
    }

    public function show($id)
    {
        $invoice = Invoice::with('items')
            ->findOrFail($id);

        return view(
            'invoice.preview',
            compact('invoice')
        );
    }

    public function pdf_backup($id)
    {
        // 1. Permanently allocate a memory buffer overhead for processing image data
        ini_set('memory_limit', '256M');
        $invoice = Invoice::with(['items' => function($query) {
            $query->select('id', 'invoice_id', 'description', 'qty', 'rate', 'amount', 'created_at', 'updated_at');
        }])->findOrFail($id);

        // 2. Read the image into memory and transform it into an inline data stream URL
        $logoPath = public_path('company/logo.png');
        $logoBase64Data = null;

        if (file_exists($logoPath)) {
            $fileContent = file_get_contents($logoPath);
            $mimeType    = mime_content_type($logoPath);
            // Generates an inline image payload data format completely immune to Windows disk path configurations
            $logoBase64Data = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
        }

        $qrData = json_encode([
            'invoice_no' => $invoice->invoice_no,
            'client'     => $invoice->client_name,
            'amount'     => number_format($invoice->grand_total / 100, 2)
        ]);

        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(110)
            ->errorCorrection('M')
            ->generate($qrData);

        // Call your custom converter method here securely
        $amountInWords = $this->convertAmountToWords($invoice->grand_total / 100);

        $pdf = Pdf::loadView('invoice.pdf', [
            'invoice'       => $invoice,
            'logo'          => $logoBase64Data,
            'qrCodeSvg'     => $qrCodeSvg,
            'amountInWords' => $amountInWords // Sent down to template variables
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
        'isHtml5ParserEnabled'    => true,
        'isRemoteEnabled'         => true, // 🌟 FIXED: Must be true to support file:/// links
        'isFontSubsettingEnabled' => true
        ]);

        return $pdf->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }

    private function convertAmountToWords_backup($number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        );
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter].$plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $Paise = ($decimal > 0) ? "." . ($words[$decimal / 10 * 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $Paise;
    }
    /////////////////////// 9-06-2026
    public function pdf_9_06_2026($id)
    {
        ini_set('memory_limit', '256M');

        $invoice = Invoice::with(['items' => function($query) {
            $query->select('id', 'invoice_id', 'description', 'qty', 'rate', 'amount');
        }])->findOrFail($id);

        // 1. Process inline image conversion safely
        $logoPath = public_path('company/logo.png');
        $logoBase64Data = null;
        if (file_exists($logoPath)) {
            $logoBase64Data = 'data:' . mime_content_type($logoPath) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // 2. Format localized Indian compliance structured string payload
        $qrData = json_encode([
            'Ino'   => $invoice->invoice_no,
            'Gstin' => '21CWJPG6532J1ZL',
            'Amt'   => number_format($invoice->grand_total / 100, 2, '.', '')
        ]);

        $qrCodeSvg = QrCode::size(100)->errorCorrection('M')->generate($qrData);
        $amountInWords = $this->convertAmountToWords($invoice->grand_total / 100);

        $pdf = Pdf::loadView('invoice.pdf', [
            'invoice'       => $invoice,
            'logo'          => $logoBase64Data,
            'qrCodeSvg'     => $qrCodeSvg,
            'amountInWords' => $amountInWords
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled'    => true,
            'isRemoteEnabled'         => false, // False since logo is self-contained inline text
            'isFontSubsettingEnabled' => true
        ]);

        return $pdf->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }

    //////////////////
    public function pdf_working($id)
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
        $qrData = json_encode([
            'Ino'   => $invoice->invoice_no,
            'Gstin' => '21CWJPG6532J1ZL',
            'Amt'   => number_format($invoice->grand_total / 100, 2, '.', '')
        ]);

        // 3. 🌟 FIXED: Generate raw SVG, clean out any XML declaration wrappers, and base64 encode it
        $rawSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
            ->errorCorrection('M')
            ->generate($qrData);


        $cleanSvg = preg_replace('/<\?xml.*\?>/', '', $rawSvg);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(trim($cleanSvg));

        $amountInWords = $this->convertAmountToWords($invoice->grand_total / 100);

        $pdf = Pdf::loadView('invoice.pdf', [
            'invoice'       => $invoice,
            'logo'          => $logoBase64Data,
            'qrCodeSvg'     => $qrCodeBase64, // 🌟 Sent down as a safe base64 encoded image string
            'amountInWords' => $amountInWords
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled'    => true,
            'isRemoteEnabled'         => true, // 🌟 FIXED: Must be true to decode inline data strings
            'isFontSubsettingEnabled' => true
        ]);

        return $pdf->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }
    /////////////////////////
    public function pdf_working_rupee_sign($id)
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

        // 2. 🌟 FIXED: Read TrueType font files from disk and convert them to Base64 strings
        // This allows Dompdf to load the font directly from memory instead of trying to look up files
        $fontPathRegular = public_path('fonts/NotoSans-Regular.ttf');
        $fontPathBold = public_path('fonts/NotoSans-Bold.ttf');

        $base64FontRegular = null;
        $base64FontBold = null;

        if (file_exists($fontPathRegular)) {
            $base64FontRegular = base64_encode(file_get_contents($fontPathRegular));
        }
        if (file_exists($fontPathBold)) {
            $base64FontBold = base64_encode(file_get_contents($fontPathBold));
        }

        // 3. Format localized Indian compliance structured payload
        $qrData = json_encode([
            'Ino'   => $invoice->invoice_no,
            'Gstin' => '21CWJPG6532J1ZL',
            'Amt'   => number_format($invoice->grand_total / 100, 2, '.', '')
        ]);

        $rawSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
            ->errorCorrection('M')
            ->generate($qrData);

        $cleanSvg = preg_replace('/<\?xml.*\?>/', '', $rawSvg);
        $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(trim($cleanSvg));

        $amountInWords = $this->convertAmountToWords($invoice->grand_total / 100);

        return Pdf::loadView('invoice.pdf', [
            'invoice'           => $invoice,
            'logo'              => $logoBase64Data,
            'qrCodeSvg'         => $qrCodeBase64,
            'amountInWords'     => $amountInWords,
            'base64FontRegular' => $base64FontRegular, // 🌟 Sent down to view
            'base64FontBold'    => $base64FontBold      // 🌟 Sent down to view
        ])
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled'    => true,
            'isRemoteEnabled'         => true,
            'isFontSubsettingEnabled' => true
        ])
        ->download('Invoice_' . $invoice->invoice_no . '.pdf');
    }
    /////////////////////////
    public function pdf($id)
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
    $qrData = json_encode([
        'Ino'   => $invoice->invoice_no,
        'Gstin' => '21CWJPG6532J1ZL',
        'Amt'   => number_format($invoice->amount / 100, 2, '.', '')
    ]);

    $rawSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
        ->errorCorrection('M')
        ->generate($qrData);

    $cleanSvg = preg_replace('/<\?xml.*\?>/', '', $rawSvg);
    $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(trim($cleanSvg));

    $amountInWords = $this->convertAmountToWords($invoice->amount / 100);

    return Pdf::loadView('invoice.pdf', [
        'invoice'           => $invoice,
        'logo'              => $logoBase64Data,
        'qrCodeSvg'         => $qrCodeBase64,
        'amountInWords'     => $amountInWords
    ])
    ->setPaper('a4', 'portrait')
    ->setOptions([
        'isHtml5ParserEnabled'    => true,
        'isRemoteEnabled'         => false,       // 🌟 FIXED: False since everything is hosted locally now
        'isFontSubsettingEnabled' => true,        // Keeps your files under ~50KB
        'chroot'                  => public_path() // 🌟 FIXED: Authorizes Dompdf to securely load files out of your public/ folder
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

        $amountInWords = $this->convertAmountToWords($invoice->amount / 100);

        // Set clean browser download stream headers for Native Excel XML layout injection
        $fileName = 'Invoice_' . $invoice->invoice_no . '.xls';

        return response()->view('invoice.excel', [
            'invoice'       => $invoice,
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
