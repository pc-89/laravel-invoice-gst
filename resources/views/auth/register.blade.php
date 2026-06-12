<!DOCTYPE html>
<html>
<head>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background: linear-gradient(135deg,#43cea2,#185a9d);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family: Arial;
}

.card-box{
    background:#fff;
    padding:30px;
    width:420px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}

</style>

</head>

<body>

<div class="card-box">

<h4 class="text-center mb-3">Create Account</h4>

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
@csrf

<div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-success w-100">
    Register
</button>

</form>

<hr>

<p class="text-center">
    Already have an account?
    <a href="/">Login</a>
</p>

</div>

</body>
</html>
