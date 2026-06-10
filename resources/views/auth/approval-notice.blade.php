@extends('layouts.app')

@section('content')
<div class="container text-center my-5">
    <div class="card shadow p-5">
        <h2 class="text-warning">Account Awaiting Approval</h2>
        <p class="lead mt-3">Thank you for registering, <strong>{{ auth()->user()->name }}</strong>!</p>
        <p class="text-muted">Your account is currently under review by an Administrator. Once approved, you will get full access to add your company GST, Bank details, and generate professional invoices.</p>
        <hr>
        <p class="small text-secondary">Please check back later or contact support if this is taking longer than expected.</p>
    </div>
</div>
@endsection
