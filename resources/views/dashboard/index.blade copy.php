{{-- <!DOCTYPE html>
<html>
<head>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
    font-family:Poppins;
    background:#f4f6f9;
}

.sidebar{
    height:100vh;
    background:#1f2937;
    color:white;
    padding:20px;
}

.sidebar a{
    color:white;
    display:block;
    padding:10px;
    text-decoration:none;
    border-radius:6px;
}

.sidebar a:hover{
    background:#374151;
}

.card-box{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.stat{
    font-size:22px;
    font-weight:600;
}

</style>

</head>

<body>

<div class="container-fluid">
<div class="row">

<!-- SIDEBAR -->
<div class="col-md-2 sidebar">

<h4>GST Admin</h4>

<a href="/dashboard">Dashboard</a>
<a href="/users">Users</a>
<a href="/invoice/create">Create Invoice</a>

<form method="POST" action="/logout">
@csrf
<button class="btn btn-danger btn-sm mt-3">Logout</button>
</form>

</div>

<!-- MAIN -->
<div class="col-md-10 p-4">

<h3>Dashboard</h3>

<div class="row mt-4">

<div class="col-md-4">
<div class="card-box">
    <p>Total Users</p>
    <div class="stat">{{ $totalUsers }}</div>
</div>
</div>

<div class="col-md-4">
<div class="card-box">
    <p>Total Invoices</p>
    <div class="stat">{{ $totalInvoices }}</div>
</div>
</div>

<div class="col-md-4">
<div class="card-box">
    <p>Total Sales</p>
    <div class="stat">₹ {{ number_format($totalSales,2) }}</div>
</div>
</div>

</div>

</div>

</div>
</div>

</body>
</html> --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Management Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-dark text-white fw-bold">Pending User Approvals</div>
        <div class="card-body">
            @if($pendingUsers->isEmpty())
                <p class="text-muted mb-0">There are no pending approvals at the moment.</p>
            @else
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->created_at->format('d-M-Y H:i') }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.users.approve', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success px-4 fw-bold">Approve User</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
