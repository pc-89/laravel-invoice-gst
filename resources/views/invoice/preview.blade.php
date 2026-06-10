@extends('layouts.app')

@section('content')

<div class="card-custom">

    <!-- HEADER -->
    <div class="text-center mb-3">

        <img src="{{ asset('company/logo.png') }}" height="70">

        <h3 class="mt-2 mb-0">ABC CONSTRUCTION PVT LTD</h3>

        <small class="text-muted">GST TAX INVOICE</small>

    </div>

    <hr>

    <!-- INVOICE INFO -->
    <div class="row mb-3">

        <div class="col-md-6">
            <p class="mb-1"><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
            <p class="mb-1"><strong>Date:</strong> {{ $invoice->invoice_date ?? date('d-m-Y') }}</p>
        </div>

        <div class="col-md-6 text-end">
            <p class="mb-1"><strong>Client:</strong> {{ $invoice->client_name }}</p>
            <p class="mb-1"><strong>GSTIN:</strong> {{ $invoice->client_gstin }}</p>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

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
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-end">₹ {{ number_format($item->rate,2) }}</td>
                    <td class="text-end">₹ {{ number_format($item->amount,2) }}</td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <!-- TOTAL -->
    <div class="text-end mt-3">

        <h4>
            Grand Total:
            <span class="text-success">
                ₹ {{ number_format($invoice->grand_total,2) }}
            </span>
        </h4>

    </div>

    <!-- ACTION BUTTONS -->
    {{-- <div class="mt-4 d-flex justify-content-end gap-2">

        <a href="/invoice/{{ $invoice->id }}/pdf"
           class="btn btn-danger">

            Download PDF
        </a>

        <a href="/invoice/create"
           class="btn btn-secondary">

            New Invoice
        </a>

    </div> --}}
    <div class="d-flex justify-content-start align-items-center gap-3 my-4 pt-2 border-top">

        <a href="{{ route('invoice.pdf', $invoice->id) }}"
           class="btn btn-danger btn-lg d-inline-flex align-items-center shadow-sm px-4">
            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 2v3a1 1 0 001 1h3m-3 7a1 1 0 11-2 0v-3a1 1 0 112 0v3z"></path>
            </svg>
            <span class="fw-bold" style="font-size: 14px;">Download Professional PDF</span>
        </a>

        <a href="{{ route('invoice.excel', $invoice->id) }}"
           class="btn btn-success btn-lg d-inline-flex align-items-center shadow-sm px-4">
            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path>
            </svg>
            <span class="fw-bold" style="font-size: 14px;">Export Layout to Excel</span>
        </a>

    </div>

</div>

@endsection
