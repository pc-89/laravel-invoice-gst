{{-- @extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4 text-dark">User Dashboard - Settings</h2>

            @if(session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger shadow-sm fw-bold">{{ session('error') }}</div>
            @endif

            <form action="{{ route('user.company.save') }}" method="POST" class="card shadow-sm mb-4">
                @csrf
                <div class="card-header bg-dark text-white fw-bold">Company Profile Setup</div>
                <div class="card-body bg-light">

                    <h5 class="text-primary mb-3">1. Basic Details</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Legal Name</label>
                            <input type="text" name="company_name" value="{{ $company->company_name ?? '' }}" class="form-control" placeholder="e.g., ABC ENTERPRISE">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ $company->mobile ?? '' }}" class="form-control" placeholder="Mobile">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Official Email</label>
                            <input type="email" name="email" value="{{ $company->email ?? '' }}" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Registered Office Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Full Address Details...">{{ $company->address ?? '' }}</textarea>
                        </div>
                    </div>

                    <h5 class="text-primary mb-3">2. GST & Compliance Parameters</h5>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Company GSTIN</label>
                            <input type="text" name="gstin" value="{{ $company->gstin ?? '' }}" class="form-control" placeholder="21AAAAA0000A1Z0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">State Name</label>
                            <input type="text" name="state_name" value="{{ $company->state_name ?? '' }}" class="form-control" placeholder="e.g., Odisha">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">State Code</label>
                            <input type="text" name="state_code" value="{{ $company->state_code ?? '' }}" class="form-control" placeholder="e.g., 21">
                        </div>
                    </div>

                    <h5 class="text-primary mb-3">3. Corporate Settlement Bank Parameters</h5>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Beneficiary Account Name</label>
                            <input type="text" name="account_name" value="{{ $company->account_name ?? '' }}" class="form-control" placeholder="Account Holder Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Account Number</label>
                            <input type="text" name="account_no" value="{{ $company->account_no ?? '' }}" class="form-control" placeholder="Account Number">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Bank Name & Branch Branch</label>
                            <input type="text" name="bank_name" value="{{ $company->bank_name ?? '' }}" class="form-control" placeholder="e.g., STATE BANK OF INDIA, MAIN BRANCH">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">IFSC Code</label>
                            <input type="text" name="ifsc_code" value="{{ $company->ifsc_code ?? '' }}" class="form-control" placeholder="SBIN0000000">
                        </div>
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-between align-items-center py-3">
                    <span class="text-muted small">* This profile configuration map attaches to all future exports automatically.</span>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save Profile Changes</button>
                </div>
            </form>

            <div class="mt-4 d-flex gap-3">
                <a href="{{ route('invoice.create') }}" class="btn btn-success fw-bold p-3 shadow-sm flex-fill text-center">
                    Go To Invoice Creation Workspace ➜
                </a>
            </div>

        </div>
    </div>
</div>
@endsection --}}
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

    /* ---- Stepper ---- */
    .step-nav{
        display:flex;
        gap:.5rem;
        flex-wrap:wrap;
        margin-bottom:1.5rem;
    }
    .step-pill{
        display:flex;
        align-items:center;
        gap:.5rem;
        padding:.5rem 1rem;
        border-radius:999px;
        background:var(--card);
        border:1px solid var(--border);
        color:var(--ink-soft);
        font-size:.85rem;
        font-weight:600;
        text-decoration:none;
        transition:all .15s ease;
    }
    .step-pill .num{
        width:1.4rem;
        height:1.4rem;
        border-radius:50%;
        background:var(--bg);
        color:var(--ink-soft);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:.75rem;
        font-weight:700;
        transition:all .15s ease;
    }
    .step-pill.is-complete{
        color:var(--accent);
        border-color:var(--accent-soft);
        background:var(--accent-soft);
    }
    .step-pill.is-complete .num{
        background:var(--accent);
        color:#fff;
    }

    /* ---- Section cards ---- */
    .section-card{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:14px;
        margin-bottom:1.25rem;
        overflow:hidden;
        scroll-margin-top:1rem;
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
    }
    .btn-save:hover{ background:#265d4f; border-color:#265d4f; }
    .btn-go-invoice{
        background:var(--ink);
        border-color:var(--ink);
        font-weight:700;
        border-radius:10px;
    }
    .btn-go-invoice:hover{ background:#0f1c33; border-color:#0f1c33; }

    /* ---- Live letterhead preview ---- */
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
    .letterhead{
        position:relative;
        margin:1.2rem;
        padding:1.4rem 1.4rem 1.1rem;
        border:1px solid var(--border);
        border-radius:8px;
        background:
            repeating-linear-gradient(180deg,#fff 0px,#fff 27px,#FAFBFD 28px);
        min-height:280px;
        overflow:hidden;
    }
    .letterhead::before{
        content:"PREVIEW";
        position:absolute;
        top:1.4rem;
        right:-2.6rem;
        transform:rotate(45deg);
        background:var(--highlight);
        color:#fff;
        font-size:.7rem;
        font-weight:700;
        letter-spacing:.15em;
        padding:.2rem 3rem;
        opacity:.85;
    }
    .letterhead .lh-name{
        font-family:'Lora',serif;
        font-weight:700;
        font-size:1.35rem;
        color:var(--ink);
        line-height:1.2;
        margin-bottom:.2rem;
        word-break:break-word;
    }
    .letterhead .lh-address{
        font-size:.82rem;
        color:var(--ink-soft);
        white-space:pre-line;
        margin-bottom:.7rem;
    }
    .letterhead .lh-meta{
        display:flex;
        flex-wrap:wrap;
        gap:.4rem 1rem;
        font-size:.78rem;
        color:var(--ink-soft);
        border-top:1px dashed var(--border);
        padding-top:.6rem;
        margin-bottom:.9rem;
    }
    .letterhead .lh-meta b{ color:var(--ink); font-family:'IBM Plex Mono',monospace; font-weight:600; }
    .letterhead .lh-bank{
        background:var(--bg);
        border:1px solid var(--border);
        border-radius:8px;
        padding:.7rem .9rem;
        font-size:.78rem;
        color:var(--ink-soft);
    }
    .letterhead .lh-bank-title{
        font-weight:700;
        color:var(--ink);
        font-size:.75rem;
        text-transform:uppercase;
        letter-spacing:.06em;
        margin-bottom:.35rem;
    }
    .letterhead .lh-bank .mono{ color:var(--ink); }
    .letterhead .lh-empty{
        color:#A6B0C3;
        font-style:italic;
        font-size:.85rem;
    }
    .preview-foot{
        padding:0 1.2rem 1.2rem;
        font-size:.78rem;
        color:var(--ink-soft);
    }

    @media (max-width: 991px){
        .preview-wrap{ position:relative; top:0; margin-top:1.25rem; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&family=Lora:wght@600;700&display=swap" rel="stylesheet">

<div class="settings-page py-4">
    <div class="container">

        <div class="page-head mb-3">
            <h2 class="mb-1">Company profile &amp; invoice settings</h2>
            <p class="mb-0">These details appear on every invoice you generate — your business identity, GST registration, and the bank account customers should pay into. Fill them in once and they're applied automatically.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger shadow-sm fw-bold">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">

                <!-- Stepper -->
                <div class="step-nav">
                    <a href="#section-basic" class="step-pill" data-step="basic"><span class="num">1</span> Basic details</a>
                    <a href="#section-gst" class="step-pill" data-step="gst"><span class="num">2</span> GST &amp; compliance</a>
                    <a href="#section-bank" class="step-pill" data-step="bank"><span class="num">3</span> Settlement bank</a>
                </div>

                <form action="{{ route('user.company.save') }}" method="POST" id="companyForm" class="needs-validation" novalidate>
                    @csrf

                    <!-- 1. Basic details -->
                    <div class="section-card" id="section-basic">
                        <div class="section-head">
                            <div class="icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1m-6 4h1m4 0h1m-6 4h1m4 0h1"/></svg>
                            </div>
                            <div>
                                <h5>Basic details</h5>
                                <small>Your registered business name and contact information</small>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company legal name<span class="req">*</span></label>
                                    <input type="text" name="company_name" id="company_name" value="{{ $company->company_name ?? '' }}" class="form-control" placeholder="e.g., ABC Enterprise" required minlength="3">
                                    <div class="invalid-feedback">Enter the legal name as it should appear on invoices.</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Mobile number<span class="req">*</span></label>
                                    <input type="tel" name="mobile" id="mobile" value="{{ $company->mobile ?? '' }}" class="form-control" placeholder="10-digit number" inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" required>
                                    <div class="form-text">10 digits, starting 6–9.</div>
                                    <div class="invalid-feedback">Enter a valid 10-digit mobile number.</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Official email<span class="req">*</span></label>
                                    <input type="email" name="email" id="email" value="{{ $company->email ?? '' }}" class="form-control" placeholder="name@company.com" required>
                                    <div class="invalid-feedback">Enter a valid email address.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Registered office address<span class="req">*</span></label>
                                    <textarea name="address" id="address" class="form-control" rows="2" placeholder="Building, street, city, PIN code" required>{{ $company->address ?? '' }}</textarea>
                                    <div class="invalid-feedback">Add the full registered address shown on invoices.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. GST & Compliance -->
                    <div class="section-card" id="section-gst">
                        <div class="section-head">
                            <div class="icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h5>GST &amp; compliance</h5>
                                <small>Used for tax calculation and statutory fields on invoices</small>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Company GSTIN<span class="req">*</span></label>
                                    <input type="text" name="gstin" id="gstin" value="{{ $company->gstin ?? '' }}" class="form-control mono text-uppercase" placeholder="21AAAAA0000A1Z0" maxlength="15" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$" required>
                                    <div class="form-text">15 characters — used to auto-fill state details.</div>
                                    <div class="invalid-feedback">Enter a valid 15-character GSTIN.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State name<span class="req">*</span></label>
                                    <input type="text" name="state_name" id="state_name" value="{{ $company->state_name ?? '' }}" class="form-control" placeholder="e.g., Odisha" required>
                                    <div class="invalid-feedback">Enter the state your business is registered in.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State code<span class="req">*</span></label>
                                    <input type="text" name="state_code" id="state_code" value="{{ $company->state_code ?? '' }}" class="form-control mono" placeholder="e.g., 21" maxlength="2" pattern="[0-9]{1,2}" required>
                                    <div class="form-text">First two digits of your GSTIN.</div>
                                    <div class="invalid-feedback">Enter the 1–2 digit state code.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Settlement bank details -->
                    <div class="section-card" id="section-bank">
                        <div class="section-head">
                            <div class="icon-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6M4 10v9h16v-9M9 21v-6h6v6"/></svg>
                            </div>
                            <div>
                                <h5>Settlement bank details</h5>
                                <small>Shown on invoices so customers know where to pay</small>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Beneficiary account name<span class="req">*</span></label>
                                    <input type="text" name="account_name" id="account_name" value="{{ $company->account_name ?? '' }}" class="form-control" placeholder="Account holder name" required>
                                    <div class="invalid-feedback">Enter the name on the bank account.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account number<span class="req">*</span></label>
                                    <input type="text" name="account_no" id="account_no" value="{{ $company->account_no ?? '' }}" class="form-control mono" placeholder="Account number" inputmode="numeric" pattern="[0-9]{9,18}" required>
                                    <div class="form-text">9–18 digits, numbers only.</div>
                                    <div class="invalid-feedback">Enter a valid account number (9–18 digits).</div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Bank name &amp; branch<span class="req">*</span></label>
                                    <input type="text" name="bank_name" id="bank_name" value="{{ $company->bank_name ?? '' }}" class="form-control" placeholder="e.g., State Bank of India, Main Branch" required>
                                    <div class="invalid-feedback">Enter the bank name and branch.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">IFSC code<span class="req">*</span></label>
                                    <input type="text" name="ifsc_code" id="ifsc_code" value="{{ $company->ifsc_code ?? '' }}" class="form-control mono text-uppercase" placeholder="SBIN0000000" maxlength="11" pattern="^[A-Z]{4}0[A-Z0-9]{6}$" required>
                                    <div class="invalid-feedback">Enter a valid 11-character IFSC code.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="actions-bar">
                        <p class="hint">This configuration applies to every invoice you export from now on — double-check the bank details before saving.</p>
                        <button type="submit" class="btn btn-save text-white">Save profile changes</button>
                    </div>
                </form>

                <div class="mt-3">
                    <a href="{{ route('invoice.create') }}" class="btn btn-go-invoice text-white p-3 shadow-sm d-block text-center">
                        Go to invoice creation workspace &#8594;
                    </a>
                </div>

            </div>

            <!-- Live preview -->
            <div class="col-lg-4">
                <div class="preview-wrap">
                    <div class="preview-card">
                        <div class="preview-label">
                            <span>Invoice letterhead preview</span>
                            <span class="badge-live">LIVE</span>
                        </div>
                        <div class="letterhead">
                            <div class="lh-name" id="pv-name">Your Company Name</div>
                            <div class="lh-address" id="pv-address">Registered address will appear here</div>

                            <div class="lh-meta">
                                <span>GSTIN: <b id="pv-gstin">—</b></span>
                                <span>State: <b id="pv-state">—</b> (<b id="pv-statecode">—</b>)</span>
                            </div>
                            <div class="lh-meta" style="margin-top:-1.1rem; border-top:none; padding-top:0;">
                                <span>Mobile: <b id="pv-mobile">—</b></span>
                                <span>Email: <b id="pv-email">—</b></span>
                            </div>

                            <div class="lh-bank">
                                <div class="lh-bank-title">Payment details</div>
                                <div>Account name: <span class="mono" id="pv-acc-name">—</span></div>
                                <div>Account no.: <span class="mono" id="pv-acc-no">—</span></div>
                                <div>Bank: <span class="mono" id="pv-bank">—</span></div>
                                <div>IFSC: <span class="mono" id="pv-ifsc">—</span></div>
                            </div>
                        </div>
                        <div class="preview-foot">
                            This is exactly how your details will look at the top of every invoice. Update any field and the preview refreshes instantly.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('companyForm');

    // ---- Bootstrap-style validation ----
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Auto-uppercase GSTIN and IFSC as the user types
    ['gstin', 'ifsc_code'].forEach(function (id) {
        const el = document.getElementById(id);
        el.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });
    });

    // ---- Live letterhead preview ----
    const fields = ['company_name','address','gstin','state_name','state_code','mobile','email','account_name','account_no','bank_name','ifsc_code'];
    const previewMap = {
        company_name: 'pv-name',
        address: 'pv-address',
        gstin: 'pv-gstin',
        state_name: 'pv-state',
        state_code: 'pv-statecode',
        mobile: 'pv-mobile',
        email: 'pv-email',
        account_name: 'pv-acc-name',
        account_no: 'pv-acc-no',
        bank_name: 'pv-bank',
        ifsc_code: 'pv-ifsc'
    };
    const defaults = {
        company_name: 'Your Company Name',
        address: 'Registered address will appear here'
    };

    function updatePreview() {
        fields.forEach(function (id) {
            const input = document.getElementById(id);
            const target = document.getElementById(previewMap[id]);
            const value = input.value.trim();
            target.textContent = value !== '' ? value : (defaults[id] || '—');
        });
    }

    fields.forEach(function (id) {
        document.getElementById(id).addEventListener('input', updatePreview);
    });
    updatePreview();

    // ---- Stepper: mark sections complete as they're filled ----
    const stepGroups = {
        basic: ['company_name','mobile','email','address'],
        gst: ['gstin','state_name','state_code'],
        bank: ['account_name','account_no','bank_name','ifsc_code']
    };

    function updateSteps() {
        Object.keys(stepGroups).forEach(function (step) {
            const pill = document.querySelector('.step-pill[data-step="' + step + '"]');
            const complete = stepGroups[step].every(function (id) {
                return document.getElementById(id).value.trim() !== '';
            });
            pill.classList.toggle('is-complete', complete);
        });
    }

    fields.forEach(function (id) {
        document.getElementById(id).addEventListener('input', updateSteps);
    });
    updateSteps();
})();
</script>
@endsection
