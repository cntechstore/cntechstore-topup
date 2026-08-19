<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "../config.php";
require_once "../database.php";

$order_id = trim($_GET['order_id'] ?? '');
$type = trim($_GET['type'] ?? '');
$transaction_id = trim($_GET['transaction_id'] ?? '');

if(!$order_id || !$transaction_id){
    die('Invalid Request');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Payment Submitted - CNTECH STORE</title>

<style>
body{
margin:0;
background:#050505;
font-family:Arial,sans-serif;
color:#fff;
display:flex;
align-items:center;
justify-content:center;
min-height:100vh;
padding:20px;
}

.card{
max-width:650px;
width:100%;
background:#111;
border:1px solid #222;
border-radius:25px;
overflow:hidden;
}

.header{
background:#000;
padding:30px;
text-align:center;
border-bottom:4px solid #ff2020;
}

.logo{
font-size:30px;
font-weight:900;
}

.logo span{
color:#ff2020;
}

.content{
padding:35px;
text-align:center;
}

.icon{
width:90px;
height:90px;
margin:auto;
border-radius:50%;
background:#0f2d16;
border:3px solid #32df79;
display:flex;
align-items:center;
justify-content:center;
font-size:42px;
color:#32df79;
}

h1{
margin:20px 0 10px;
font-size:28px;
}

.order-box{
margin-top:25px;
background:#181818;
border-radius:15px;
padding:20px;
text-align:left;
}

.row{
display:flex;
justify-content:space-between;
padding:10px 0;
border-bottom:1px solid #242424;
}

.row:last-child{
border-bottom:0;
}

.label{
color:#999;
}

.value{
font-weight:bold;
word-break:break-all;
}

.btn{
display:block;
margin-top:25px;
background:#ff2020;
padding:15px;
border-radius:12px;
text-decoration:none;
font-weight:bold;
color:#fff;
}

.footer{
padding:20px;
text-align:center;
font-size:12px;
color:#777;
}
</style>

</head>
<body>

<div class="card">

<div class="header">

<div class="logo">
CN<span>TECH</span> STORE
</div>

<div style="color:#888;font-size:12px;">
Computer • Mobile • Parts & Accessories
</div>

</div>

<div class="content">

<div class="icon">
✓
</div>

<h1>Payment Submitted</h1>

<div style="color:#32df79;font-weight:bold;">
ສົ່ງຫຼັກຖານການໂອນເງິນສຳເລັດ
</div>

<p style="color:#999;line-height:1.8;">
Thank you.<br>
We have received your payment slip.<br>
Our team will review and notify you as soon as possible.
</p>

<div class="order-box">

<div class="row">
<div class="label">Order ID</div>
<div class="value"><?=htmlspecialchars($order_id)?></div>
</div>

<div class="row">
<div class="label">Type</div>
<div class="value"><?=htmlspecialchars(strtoupper($type))?></div>
</div>

<div class="row">
<div class="label">Transaction</div>
<div class="value"><?=htmlspecialchars($transaction_id)?></div>
</div>

<div class="row">
<div class="label">Status</div>
<div class="value" style="color:#ffc107;">
WAITING REVIEW
</div>
</div>

</div>

<a class="btn" href="https://cntechstore.shop">
Back to CNTECH STORE
</a>

</div>

<div class="footer">
This payment is awaiting verification by CNTECH STORE.
</div>

</div>

</body>
</html>