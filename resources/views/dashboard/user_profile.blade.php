@extends('layouts.app')

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
@endsection
