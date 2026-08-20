<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>403 - Access Denied | CN Tech Store</title>

<link rel="icon" href="/assets/favicon.ico">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#111827;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.card{
    width:100%;
    max-width:650px;
    background:#1f2937;
    padding:45px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 15px 35px rgba(0,0,0,.4);
}

h1{
    font-size:90px;
    color:#ef4444;
    margin-bottom:10px;
}

h2{
    margin-bottom:15px;
    font-size:30px;
}

p{
    color:#cbd5e1;
    line-height:1.8;
    margin-bottom:30px;
}

.btn{
    display:inline-block;
    margin:5px;
    padding:12px 24px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    transition:.3s;
}

.btn:hover{
    background:#1d4ed8;
}

.footer{
    margin-top:30px;
    color:#94a3b8;
    font-size:14px;
}

</style>

</head>

<body>

<div class="card">

<h1>403</h1>

<h2>Access Denied</h2>

<p>
You don't have permission to access this page.<br>
If you believe this is an error, please contact CN Tech Store Support.
</p>



<a href="page/contact-method.php" class="btn"> Contact Support</a>

<div class="footer">
CN Tech Store v1.5.5 BR<br>
Computer • Mobile • Parts • Game Top-up
</div>

</div>

</body>
</html>