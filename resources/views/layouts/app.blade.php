<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

body{
    font-family: Poppins, sans-serif;
    background: #f4f6f9;
}

/* GLOBAL CARD STYLE */
.app-container{
    padding: 40px;
}

/* PROFESSIONAL CARD */
.card-custom{
    background: #fff;
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

/* HEADINGS */
h3,h4,h5{
    font-weight: 600;
    color: #1f2d3d;
}

hr{
    margin: 20px 0;
    opacity: 0.15;
}

</style>

</head>

<body>

<div class="app-container">

    @yield('content')

</div>

</body>
</html>
