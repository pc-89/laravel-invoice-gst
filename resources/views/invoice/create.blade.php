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

    /* ---- Page header ---- */
    .page-head h2{
        font-weight:700;
        letter-spacing:-0.01em;
        color:var(--ink);
    }
    .page-head p{
        color:var(--ink-soft);
        max-width:640px;
    }
    .page-head .badge-system{
        font-size:.7rem;
        font-weight:700;
        letter-spacing:.08em;
        color:var(--accent);
        background:var(--accent-soft);
        padding:.3rem .7rem;
        border-radius:999px;
    }

    /* ---- Section cards ---- */
    .section-card{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:14px;
        margin-bottom:1.25rem;
        overflow:hidden;
    }
    .section-card .section-head{
        display:flex;
        align-items:center;
        gap:.75rem;
        padding:1.1rem 1.4rem;
        border-bottom:1px solid var(--border);
        background:linear-gradient(180deg,#fff, #FAFBFD);
    }
    .section-head .icon-badge{
        width:2.4rem;
        height:2.4rem;
        flex:0 0 auto;
        border-radius:10px;
        background:var(--accent-soft);
        color:var(--accent);
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .section-head .icon-badge svg{ width:1.25rem; height:1.25rem; }
    .section-head h5{
        margin:0;
        font-weight:700;
        font-size:1rem;
        color:var(--ink);
    }
    .section-head small{
        color:var(--ink-soft);
        display:block;
        font-size:.8rem;
    }
    .section-body{ padding:1.4rem; }

    /* ---- Form fields ---- */
    .settings-page label.form-label{
        font-weight:600;
        font-size:.85rem;
        color:var(--ink);
        margin-bottom:.3rem;
    }
    .settings-page label.form-label .req{
        color:var(--danger);
        margin-left:.15rem;
    }
    .settings-page .form-control,
    .settings-page .form-select{
        border-color:var(--border);
        border-radius:8px;
        padding:.55rem .75rem;
        font-size:.92rem;
    }
    .settings-page .form-control:focus,
    .settings-page .form-select:focus{
        border-color:var(--accent);
        box-shadow:0 0 0 .2rem var(--accent-soft);
    }
    .settings-page .form-text{
        color:var(--ink-soft);
        font-size:.78rem;
    }
    .settings-page .invalid-feedback{ font-size:.78rem; }

    /* ---- Items table ---- */
    .item-table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
    }
    .item-table thead th{
        font-size:.75rem;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.06em;
        color:var(--ink-soft);
        padding:.5rem .5rem;
        border-bottom:2px solid var(--border);
        white-space:nowrap;
    }
    .item-table tbody td{
        padding:.5rem .5rem;
        vertical-align:top;
    }
    .item-table tbody tr + tr td{
        border-top:1px solid var(--border);
    }
    .item-table .amount-display{
        font-family:'IBM Plex Mono',monospace;
        font-weight:600;
        color:var(--ink);
        padding-top:.6rem;
        white-space:nowrap;
    }
    .item-table .col-desc{ width:42%; }
    .item-table .col-qty{ width:13%; }
    .item-table .col-rate{ width:17%; }
    .item-table .col-amount{ width:18%; }
    .item-table .col-remove{ width:6%; }

    .remove-row{
        border:1px solid var(--border);
        background:#fff;
        color:var(--ink-soft);
        border-radius:8px;
        width:2.3rem;
        height:2.3rem;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-top:.1rem;
        transition:all .15s ease;
    }
    .remove-row:hover{
        color:var(--danger);
        border-color:#F4D6D6;
        background:#FDF3F3;
    }
    .remove-row[hidden]{ visibility:hidden; }

    .btn-add-item{
        background:var(--accent-soft);
        color:var(--accent);
        border:1px dashed var(--accent);
        border-radius:8px;
        font-weight:600;
        font-size:.85rem;
        padding:.5rem 1.1rem;
        margin-top:.6rem;
    }
    .btn-add-item:hover{
        background:var(--accent);
        color:#fff;
    }

    /* ---- Footer / actions ---- */
    .actions-bar{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:14px;
        padding:1rem 1.4rem;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:1rem;
        flex-wrap:wrap;
    }
    .actions-bar .hint{
        color:var(--ink-soft);
        font-size:.82rem;
        margin:0;
    }
    .btn-save{
        background:var(--accent);
        border-color:var(--accent);
        font-weight:700;
        padding:.6rem 2rem;
        border-radius:8px;
        color:#fff;
    }
    .btn-save:hover{ background:#265d4f; border-color:#265d4f; color:#fff; }
    .btn-reset{
        background:#fff;
        border:1px solid var(--border);
        color:var(--ink-soft);
        font-weight:600;
        border-radius:8px;
        padding:.6rem 1.4rem;
    }
    .btn-reset:hover{ background:var(--bg); }

    /* ---- Live summary preview ---- */
    .preview-wrap{ position:sticky; top:1rem; }
    .preview-card{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:14px;
        overflow:hidden;
    }
    .preview-card .preview-label{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:.85rem 1.2rem;
        border-bottom:1px solid var(--border);
        background:linear-gradient(180deg,#fff,#FAFBFD);
    }
    .preview-card .preview-label span{
        font-weight:700;
        font-size:.85rem;
        color:var(--ink);
    }
    .preview-card .preview-label .badge-live{
        font-size:.7rem;
        font-weight:700;
        color:var(--accent);
        background:var(--accent-soft);
        padding:.25rem .6rem;
        border-radius:999px;
        letter-spacing:.05em;
    }
    .summary-body{ padding:1.2rem; }
    .summary-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        font-size:.85rem;
        color:var(--ink-soft);
        padding:.45rem 0;
    }
    .summary-row b{
        font-family:'IBM Plex Mono',monospace;
        color:var(--ink);
        font-weight:600;
    }
    .summary-row.total{
        border-top:1px dashed var(--border);
        margin-top:.4rem;
        padding-top:.8rem;
        font-size:1rem;
        font-weight:700;
        color:var(--ink);
    }
    .summary-row.total b{
        font-size:1.25rem;
        color:var(--accent);
    }
    .summary-client{
        margin:0 1.2rem 1.2rem;
        padding:.9rem 1rem;
        background:var(--bg);
        border:1px solid var(--border);
        border-radius:8px;
        font-size:.82rem;
        color:var(--ink-soft);
    }
    .summary-client .sc-name{
        font-family:'Lora',serif;
        font-weight:700;
        font-size:1rem;
        color:var(--ink);
        margin-bottom:.1rem;
    }
    .summary-client .sc-empty{
        color:#A6B0C3;
        font-style:italic;
    }
    .preview-foot{
        padding:0 1.2rem 1.2rem;
        font-size:.78rem;
        color:var(--ink-soft);
    }

    @media (max-width: 991px){
        .preview-wrap{ position:relative; top:0; margin-top:1.25rem; }
        .item-table thead{ display:none; }
        .item-table tbody td{ display:block; width:100% !important; padding:.3rem .25rem; }
        .item-table tbody tr{ display:block; padding:.75rem 0; }
        .item-table tbody tr + tr{ border-top:1px solid var(--border); }
        .item-table tbody td.col-remove{ text-align:right; }
        .item-table .amount-display{ padding-top:.3rem; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&family=Lora:wght@600;700&display=swap" rel="stylesheet">

<div class="settings-page py-4">
    <div class="container">

        <div class="page-head mb-3 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Create GST invoice</h2>
                <p class="mb-0">Add the client's details and the work items to bill. Amounts update automatically as you type — review the summary on the right before generating.</p>
            </div>
            <span class="badge-system">GST SYSTEM</span>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">

                <form method="POST" action="/invoice/store" id="invoiceForm" class="needs-validation" novalidate>
                    @csrf

                    <!-- Customer & invoice details -->
                    <div class="section-card">
                        <div class="section-head">
                            <div class="icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                            <div>
                                <h5>Customer &amp; invoice details</h5>
                                <small>Who this invoice is for, and when it's dated</small>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Client name<span class="req">*</span></label>
                                    <input type="text" name="client_name" id="client_name" class="form-control" placeholder="e.g., Rajesh Kumar" required>
                                    <div class="invalid-feedback">Enter the client's name.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Client GSTIN</label>
                                    <input type="text" name="client_gstin" id="client_gstin" class="form-control mono text-uppercase" placeholder="22AAAAA0000A1Z5" maxlength="15" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$">
                                    <div class="form-text">Optional — leave blank for unregistered clients.</div>
                                    <div class="invalid-feedback">If entered, must be a valid 15-character GSTIN.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Invoice date<span class="req">*</span></label>
                                    <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
                                    <div class="invalid-feedback">Pick the invoice date.</div>
                                </div>
                                <div class="col-12 mb-1">
                                    <label class="form-label">Client address<span class="req">*</span></label>
                                    <textarea name="client_address" id="client_address" class="form-control" rows="2" placeholder="Billing address" required></textarea>
                                    <div class="invalid-feedback">Enter the client's billing address.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work items -->
                    <div class="section-card">
                        <div class="section-head">
                            <div class="icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                            </div>
                            <div>
                                <h5>Work items</h5>
                                <small>Add each line item with quantity and rate</small>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="table-responsive">
                                <table class="item-table">
                                    <thead>
                                        <tr>
                                            <th class="col-desc">Description</th>
                                            <th class="col-qty">Qty</th>
                                            <th class="col-rate">Rate (₹)</th>
                                            <th class="col-amount">Amount (₹)</th>
                                            <th class="col-remove"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-wrapper">
                                        <tr class="item-row">
                                            <td class="col-desc">
                                                <input type="text" name="description[]" class="form-control item-desc" placeholder="e.g., Cement work — ground floor" required>
                                                <div class="invalid-feedback">Required</div>
                                            </td>
                                            <td class="col-qty">
                                                <input type="number" name="qty[]" class="form-control item-qty" placeholder="0" step="any" min="0.01" required>
                                                <div class="invalid-feedback">&gt; 0</div>
                                            </td>
                                            <td class="col-rate">
                                                <input type="number" name="rate[]" class="form-control item-rate" placeholder="0.00" step="any" min="0" required>
                                                <div class="invalid-feedback">&gt;= 0</div>
                                            </td>
                                            <td class="col-amount">
                                                <span class="amount-display item-amount">₹ 0.00</span>
                                            </td>
                                            <td class="col-remove">
                                                <button type="button" class="remove-row" hidden title="Remove item">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" id="add-row" class="btn-add-item">
                                + Add another item
                            </button>
                        </div>
                    </div>

                    <div class="actions-bar">
                        <p class="hint">Double-check quantities and rates — these amounts will be carried straight onto the printed invoice.</p>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn-reset">Reset</button>
                            <button type="submit" class="btn-save">Generate invoice</button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Live summary -->
            <div class="col-lg-4">
                <div class="preview-wrap">
                    <div class="preview-card">
                        <div class="preview-label">
                            <span>Invoice summary</span>
                            <span class="badge-live">LIVE</span>
                        </div>

                        <div class="summary-client">
                            <div class="sc-name" id="sm-client">Client name</div>
                            <div id="sm-address" class="sc-empty">Billing address will appear here</div>
                            <div class="mt-1">GSTIN: <span class="mono" id="sm-gstin">—</span></div>
                            <div>Date: <span class="mono" id="sm-date">—</span></div>
                        </div>

                        <div class="summary-body pt-0">
                            <div class="summary-row">
                                <span>Items added</span>
                                <b id="sm-count">0</b>
                            </div>
                            <div class="summary-row total">
                                <span>Estimated total</span>
                                <b id="sm-total">₹ 0.00</b>
                            </div>
                        </div>
                        <div class="preview-foot">
                            This is a running total of the work items below. Taxes or rounding applied during generation may adjust the final figure.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('invoiceForm');
    const wrapper = document.getElementById('items-wrapper');
    const addBtn = document.getElementById('add-row');

    // ---- Validation ----
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Auto-uppercase client GSTIN
    const clientGstin = document.getElementById('client_gstin');
    clientGstin.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    // ---- Item rows: add / remove / live amount calc ----
    function bindRow(row) {
        const qty = row.querySelector('.item-qty');
        const rate = row.querySelector('.item-rate');
        const amount = row.querySelector('.item-amount');

        function recalcRow() {
            const q = parseFloat(qty.value) || 0;
            const r = parseFloat(rate.value) || 0;
            amount.textContent = '₹ ' + (q * r).toFixed(2);
            updateSummary();
        }

        qty.addEventListener('input', recalcRow);
        rate.addEventListener('input', recalcRow);
    }

    function updateRemoveButtons() {
        const rows = wrapper.querySelectorAll('.item-row');
        rows.forEach(function (row, idx) {
            const btn = row.querySelector('.remove-row');
            btn.hidden = rows.length <= 1;
        });
    }

    function updateSummary() {
        let total = 0;
        let count = 0;
        wrapper.querySelectorAll('.item-row').forEach(function (row) {
            const q = parseFloat(row.querySelector('.item-qty').value) || 0;
            const r = parseFloat(row.querySelector('.item-rate').value) || 0;
            if (row.querySelector('.item-desc').value.trim() !== '' || q || r) count++;
            total += q * r;
        });
        document.getElementById('sm-count').textContent = count;
        document.getElementById('sm-total').textContent = '₹ ' + total.toFixed(2);
    }

    addBtn.addEventListener('click', function () {
        const firstRow = wrapper.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(function (input) { input.value = ''; });
        newRow.querySelector('.item-amount').textContent = '₹ 0.00';
        newRow.classList.remove('was-validated');

        wrapper.appendChild(newRow);
        bindRow(newRow);
        updateRemoveButtons();
        updateSummary();
    });

    wrapper.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-row');
        if (btn) {
            btn.closest('.item-row').remove();
            updateRemoveButtons();
            updateSummary();
        }
    });

    // ---- Customer summary fields ----
    const summaryFields = {
        client_name: { target: 'sm-client', empty: 'Client name' },
        client_address: { target: 'sm-address', empty: 'Billing address will appear here' },
        client_gstin: { target: 'sm-gstin', empty: '—' },
        invoice_date: { target: 'sm-date', empty: '—' }
    };
    Object.keys(summaryFields).forEach(function (id) {
        const input = document.getElementById(id);
        const conf = summaryFields[id];
        input.addEventListener('input', function () {
            const target = document.getElementById(conf.target);
            const value = input.value.trim();
            target.textContent = value !== '' ? value : conf.empty;
            target.classList.toggle('sc-empty', value === '' && conf.target !== 'sm-client');
        });
    });

    // ---- Init ----
    bindRow(wrapper.querySelector('.item-row'));
    updateRemoveButtons();
    updateSummary();
});
</script>
@endsection
