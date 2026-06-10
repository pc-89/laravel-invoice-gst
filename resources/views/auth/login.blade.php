<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

{{-- <title>Login - GST System</title> --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
    font-family: Poppins, sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card{
    background: #fff;
    padding: 35px;
    border-radius: 15px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.login-title{
    font-weight: 600;
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.form-control{
    border-radius: 10px;
    padding: 10px;
}

.btn-login{
    border-radius: 10px;
    padding: 10px;
    font-weight: 500;
}

</style>

</head>

<body>

<div class="login-card">

    {{-- <h3 class="login-title">GST Billing System</h3> --}}

    <p class="text-center text-muted mb-4">
        Login to continue
    </p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <!-- LOGIN BUTTON -->
        <button class="btn btn-primary w-100 btn-login">
            Login
        </button>

    </form>
    <hr>

    <p class="text-center">
        Don’t have an account?
        <a href="/register">Register</a>
    </p>
</div>

</body>
</html>
