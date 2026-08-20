<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>404 - Page Not Found | CN Tech Store</title>

<link rel="icon" href="/assets/favicon.ico">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#0f172a;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    text-align:center;
    padding:20px;
}

.card{
    max-width:650px;
    width:100%;
    background:#1e293b;
    padding:50px 30px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,.35);
}

h1{
    font-size:90px;
    color:#3b82f6;
    margin-bottom:10px;
}

h2{
    font-size:30px;
    margin-bottom:15px;
}

p{
    color:#cbd5e1;
    line-height:1.7;
    margin-bottom:30px;
}

.btn{
    display:inline-block;
    padding:14px 28px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    transition:.3s;
    margin:5px;
}

.btn:hover{
    background:#1d4ed8;
}

.footer{
    margin-top:35px;
    color:#94a3b8;
    font-size:14px;
}

</style>

</head>
<body>

<div class="card">

<h1>404</h1>

<h2>Page Not Found</h2>

<p>
The page you are looking for doesn't exist or has been moved.
</p>

<a href="<?=BASE_URL?>page/index.php" class="btn">
 Home
</a>

<a href="/page/product.php" class="btn">
 Products
</a>

<div class="footer">
CN Tech Store v1.5.5 BR<br>
Computer • Mobile • Parts • Game Top-up
</div>

</div>

</body>
</html>