<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="lo">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<meta name="theme-color"
content="#0099ff">

<title>My Orders</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
background:#0f0f0f;
color:#fff;
font-family:Arial;
margin:0;
}

.header{
height:60px;
display:flex;
align-items:center;
padding:0 15px;
background:#111;
}

.header a{
color:#fff;
font-size:20px;
margin-right:15px;
}

.order-card{
background:#161616;
margin:15px;
padding:15px;
border-radius:15px;
}

.status{
color:#00ff88;
font-weight:bold;
}

</style>

</head>
<body>

<div class="header">

<a href="../profile.php">
<i class="fa-solid fa-arrow-left"></i>
</a>

<h3>My Orders</h3>

</div>

<div class="order-card">

<h4>Order #CN10001</h4>

<p>Status:
<span class="status">
Completed
</span>
</p>

<p>Amount: 100,000 LAK</p>

</div>

<div class="order-card">

<h4>No orders found</h4>

</div>

</body>
</html>