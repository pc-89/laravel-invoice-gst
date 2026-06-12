@extends('layouts.app')

@section('content')

<div class="card-custom">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">Create GST Invoice</h3>
            <small class="text-muted">Fill customer details & work items</small>
        </div>

        <span class="badge bg-primary">GST SYSTEM</span>

    </div>

    <form method="POST" action="/invoice/store">

        @csrf

        <!-- CUSTOMER INFO -->
        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Client Name</label>
                <input type="text" name="client_name" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">GSTIN</label>
                <input type="text" name="client_gstin" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control">
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Client Address</label>
                <textarea name="client_address" class="form-control" rows="2"></textarea>
            </div>

        </div>

        <hr>

        <!-- ITEMS -->
        <h5 class="mb-3">Work Items</h5>

        {{-- <div class="row g-2 align-items-end">

            <div class="col-md-5">
                <label>Description</label>
                <input type="text" name="description[]" class="form-control" placeholder="e.g. Cement Work">
            </div>

            <div class="col-md-2">
                <label>Qty</label>
                <input type="number" name="qty[]" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Rate</label>
                <input type="number" name="rate[]" class="form-control">
            </div>

        </div> --}}

        <div id="items-wrapper">
            <div class="row g-2 align-items-end item-row mb-2">

                <div class="col-md-5">
                    <label>Description</label>
                    <input type="text" name="description[]" class="form-control" placeholder="e.g. Cement Work" required>
                </div>

                <div class="col-md-2">
                    <label>Qty</label>
                    <input type="number" name="qty[]" class="form-control" step="any" required>
                </div>

                <div class="col-md-2">
                    <label>Rate</label>
                    <input type="number" name="rate[]" class="form-control" step="any" required>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-row w-100" style="display:none;">
                        ✕
                    </button>
                </div>

                <div class="col-md-2 add-row-col">
                    <button type="button" id="add-row" class="btn btn-primary w-100">
                        + Add
                    </button>
                </div>

            </div>
        </div>

        <div class="mt-4">

            <button class="btn btn-primary px-4">
                Generate Invoice
            </button>

            <button type="reset" class="btn btn-light border">
                Reset
            </button>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('items-wrapper');
    const addBtn = document.getElementById('add-row');

    addBtn.addEventListener('click', function () {
        const firstRow = wrapper.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        // Clear input values in the cloned row
        newRow.querySelectorAll('input').forEach(input => input.value = '');

        // Show the "Remove" button on this new row
        const removeBtn = newRow.querySelector('.remove-row');
        removeBtn.style.display = 'inline-block';

        wrapper.appendChild(newRow);
    });

    // Handle remove button clicks (using event delegation)
    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>

@endsection
