@extends('layouts.app')

@section('content')
<style>
    :root{
        --ink:#1B2A4A;
        --ink-soft:#5B6B8C;
        --accent:#2F6F5E;
        --accent-soft:#E6F2EF;
        --highlight:#D98E04;
        --bg:#F3F5F9;
        --card:#FFFFFF;
        --border:#E1E6EE;
        --danger:#D64545;
        --success:#2F8F5B;
    }
    .settings-page{
        background:var(--bg);
        font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
        color:var(--ink);
    }
    .settings-page .mono{
        font-family:'IBM Plex Mono',monospace;
        letter-spacing:.02em;
    }

    .page-head h2{
        font-weight:700;
        letter-spacing:-0.01em;
        color:var(--ink);
    }
    .page-head p{
        color:var(--ink-soft);
        max-width:640px;
    }

    /* ---- Invoice document card ---- */
    .invoice-doc{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:14px;
        overflow:hidden;
    }

    /* Letterhead */
    .letterhead{
        padding:1.8rem 2rem 1.5rem;
        border-bottom:1px solid var(--border);
        background:linear-gradient(180deg,#fff, #FAFBFD);
        display:flex;
        align-items:center;
        gap:1.25rem;
        flex-wrap:wrap;
    }
    .letterhead img{
        height:64px;
        width:auto;
        border-radius:8px;
    }
    .letterhead .lh-text{ flex:1 1 240px; }
    .letterhead .lh-name{
        font-family:'Lora',serif;
        font-weight:700;
        font-size:1.6rem;
        color:var(--ink);
        line-height:1.2;
        margin-bottom:.15rem;
    }
    .letterhead .lh-address{
        font-size:.85rem;
        color:var(--ink-soft);
        white-space:pre-line;
        margin-bottom:.3rem;
    }
    .letterhead .lh-meta{
        font-size:.8rem;
        color:var(--ink-soft);
    }
    .letterhead .lh-meta b{
        font-family:'IBM Plex Mono',monospace;
        color:var(--ink);
        font-weight:600;
    }
    .letterhead .lh-tag{
        text-align:right;
        flex:0 0 auto;
    }
    .letterhead .lh-tag .tag-title{
        font-weight:700;
        font-size:1.05rem;
        color:var(--accent);
        letter-spacing:.06em;
    }
    .letterhead .lh-tag small{
        color:var(--ink-soft);
        font-size:.78rem;
    }

    /* Invoice meta strip */
    .invoice-meta{
        padding:1.4rem 2rem;
        border-bottom:1px solid var(--border);
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:1.5rem;
    }
    .invoice-meta .meta-block .meta-label{
        font-size:.72rem;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.07em;
        color:var(--ink-soft);
        margin-bottom:.4rem;
    }
    .invoice-meta .meta-block p{
        margin:0 0 .2rem;
        font-size:.92rem;
        color:var(--ink);
    }
    .invoice-meta .meta-block p b{
        font-weight:600;
    }
    .invoice-meta .meta-block .mono{
        font-size:.88rem;
    }
    .invoice-meta .meta-right{ text-align:right; }

    /* Items table */
    .invoice-items{ padding:1.4rem 2rem; }
    .invoice-items table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
    }
    .invoice-items thead th{
        background:var(--ink);
        color:#fff;
        font-size:.75rem;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.06em;
        padding:.7rem .9rem;
    }
    .invoice-items thead th:first-child{ border-radius:8px 0 0 8px; }
    .invoice-items thead th:last-child{ border-radius:0 8px 8px 0; }
    .invoice-items tbody td{
        padding:.75rem .9rem;
        font-size:.9rem;
        color:var(--ink);
        border-bottom:1px solid var(--border);
    }
    .invoice-items tbody tr:nth-child(even){ background:#FAFBFD; }
    .invoice-items .num{
        font-family:'IBM Plex Mono',monospace;
    }

    /* Totals */
    .invoice-totals{
        padding:0 2rem 1.8rem;
        display:flex;
        justify-content:flex-end;
    }
    .totals-box{
        width:100%;
        max-width:320px;
        background:var(--accent-soft);
        border:1px solid var(--border);
        border-radius:10px;
        padding:1rem 1.2rem;
    }
    .totals-box .tb-row{
        display:flex;
        justify-content:space-between;
        font-size:.95rem;
        font-weight:700;
        color:var(--ink);
    }
    .totals-box .tb-row .amt{
        font-family:'IBM Plex Mono',monospace;
        font-size:1.3rem;
        color:var(--accent);
    }

    /* Actions */
    .invoice-actions{
        padding:1.4rem 2rem 1.8rem;
        border-top:1px solid var(--border);
        display:flex;
        flex-wrap:wrap;
        gap:.75rem;
    }
    .btn-pdf{
        background:var(--ink);
        border-color:var(--ink);
        color:#fff;
        font-weight:700;
        font-size:.88rem;
        padding:.65rem 1.6rem;
        border-radius:10px;
        display:inline-flex;
        align-items:center;
        gap:.5rem;
    }
    .btn-pdf:hover{ background:#0f1c33; border-color:#0f1c33; color:#fff; }
    .btn-excel{
        background:var(--accent);
        border-color:var(--accent);
        color:#fff;
        font-weight:700;
        font-size:.88rem;
        padding:.65rem 1.6rem;
        border-radius:10px;
        display:inline-flex;
        align-items:center;
        gap:.5rem;
    }
    .btn-excel:hover{ background:#265d4f; border-color:#265d4f; color:#fff; }
    .btn-new{
        background:#fff;
        border:1px solid var(--border);
        color:var(--ink-soft);
        font-weight:600;
        font-size:.88rem;
        padding:.65rem 1.6rem;
        border-radius:10px;
    }
    .btn-new:hover{ background:var(--bg); }

    @media (max-width: 767px){
        .invoice-meta{ grid-template-columns:1fr; }
        .invoice-meta .meta-right{ text-align:left; }
        .letterhead .lh-tag{ text-align:left; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&family=Lora:wght@600;700&display=swap" rel="stylesheet">

<div class="settings-page py-4">
    <div class="container">

        <div class="page-head mb-3">
            <h2 class="mb-1">Invoice preview</h2>
            <p class="mb-0">This is how invoice <span class="mono">{{ $invoice->invoice_no }}</span> will look when downloaded. Check the details below, then export it as a PDF or Excel file.</p>
        </div>

        <div class="invoice-doc">

            <!-- Letterhead -->
            <div class="letterhead">
                @if(!empty($company->logo))
                    <img src="{{ asset($company->logo) }}" alt="Company logo">
                @endif
                <div class="lh-text">
                    <div class="lh-name">{{ $company->company_name ?? 'Your Company Name' }}</div>
                    <div class="lh-address">{{ $company->address ?? 'Registered address not set' }}</div>
                    <div class="lh-meta">
                        @if(!empty($company->gstin))
                            GSTIN: <b>{{ $company->gstin }}</b> &nbsp;•&nbsp;
                        @endif
                        @if(!empty($company->mobile))
                            Mobile: <b>{{ $company->mobile }}</b>
                        @endif
                    </div>
                </div>
                <div class="lh-tag">
                    <div class="tag-title">GST TAX INVOICE</div>
                    <small>Invoice No: {{ $invoice->invoice_no }}</small>
                </div>
            </div>

            <!-- Invoice meta -->
            <div class="invoice-meta">
                <div class="meta-block">
                    <div class="meta-label">Invoice details</div>
                    <p><b>Invoice No:</b> <span class="mono">{{ $invoice->invoice_no }}</span></p>
                    <p><b>Date:</b> <span class="mono">{{ $invoice->invoice_date ?? date('d-m-Y') }}</span></p>
                </div>
                <div class="meta-block meta-right">
                    <div class="meta-label">Billed to</div>
                    <p><b>{{ $invoice->client_name }}</b></p>
                    @if(!empty($invoice->client_address))
                        <p class="text-muted" style="font-size:.85rem;">{{ $invoice->client_address }}</p>
                    @endif
                    @if(!empty($invoice->client_gstin))
                        <p><b>GSTIN:</b> <span class="mono">{{ $invoice->client_gstin }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="invoice-items">
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Rate</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-center num">{{ $item->qty }}</td>
                            <td class="text-end num">₹ {{ number_format($item->rate,2) }}</td>
                            <td class="text-end num">₹ {{ number_format($item->amount,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="invoice-totals">
                <div class="totals-box">
                    <div class="tb-row">
                        <span>Grand Total</span>
                        <span class="amt">₹ {{ number_format($invoice->grand_total,2) }}</span>
                    </div>
                </div>
            </div>

            @if(!empty($company->account_no))
            <!-- Payment details -->
            <div class="invoice-items pt-0">
                <div class="meta-label mb-2">Payment details</div>
                <p class="mb-1" style="font-size:.85rem;">Account name: <span class="mono">{{ $company->account_name }}</span></p>
                <p class="mb-1" style="font-size:.85rem;">Account no.: <span class="mono">{{ $company->account_no }}</span></p>
                <p class="mb-1" style="font-size:.85rem;">Bank: <span class="mono">{{ $company->bank_name }}</span></p>
                <p class="mb-0" style="font-size:.85rem;">IFSC: <span class="mono">{{ $company->ifsc_code }}</span></p>
            </div>
            @endif

            <!-- Actions -->
            <div class="invoice-actions">
                <a href="{{ route('invoice.pdf', $invoice->id) }}" class="btn-pdf">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 2v3a1 1 0 001 1h3m-3 7a1 1 0 11-2 0v-3a1 1 0 112 0v3z"></path>
                    </svg>
                    Download professional PDF
                </a>
                <a href="{{ route('invoice.excel', $invoice->id) }}" class="btn-excel">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
                    </svg>
                    Export layout to Excel
                </a>
                <a href="{{ route('invoice.create') }}" class="btn-new">
                    + New invoice
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
