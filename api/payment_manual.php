<?php

session_start();

require "../config.php";
require "../database.php";

$order_id = trim($_GET['order_id'] ?? '');

if($order_id == ''){
    die("Missing Order ID");
}

$stmt = $conn->prepare("
SELECT *
FROM shop_orders
WHERE order_id = ?
LIMIT 1
");

$stmt->bind_param(
"s",
$order_id
);

$stmt->execute();

$order =
$stmt
->get_result()
->fetch_assoc();

if(!$order){
    die("Order Not Found");
}

$total =
(float)$order['total'];

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>
Manual Payment
</title><link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"><style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

background:#050505;

color:#fff;

font-family:
Arial,
sans-serif;

padding:15px;

}

.container{

max-width:700px;

margin:auto;

}

.card{

background:
linear-gradient(
145deg,
#121212,
#1b0000
);

border:
1px solid
rgba(255,0,0,.25);

border-radius:24px;

padding:20px;

margin-bottom:20px;

box-shadow:
0 10px 30px
rgba(255,0,0,.15);

}

.logo{

text-align:center;

font-size:28px;

font-weight:900;

margin-bottom:20px;

}

.logo span{

color:#ff2020;

}

.title{

font-size:20px;

font-weight:bold;

margin-bottom:15px;

}

.row{

display:flex;

justify-content:space-between;

padding:10px 0;

border-bottom:
1px solid
rgba(255,255,255,.08);

}

.amount{

font-size:30px;

font-weight:bold;

color:#22c55e;

text-align:center;

margin:20px 0;

}

.qr-box{

text-align:center;

}

.qr-box img{

width:260px;

max-width:100%;

background:#fff;

padding:10px;

border-radius:20px;

}

.bank-info{

margin-top:20px;

line-height:2;

}

.upload-box{

margin-top:20px;

}

input[type=file]{

width:100%;

padding:12px;

background:#111;

color:#fff;

border:
1px solid #333;

border-radius:12px;

}

.btn{

width:100%;

margin-top:15px;

border:none;

padding:15px;

border-radius:15px;

font-size:16px;

font-weight:700;

cursor:pointer;

background:
linear-gradient(
135deg,
#ff2020,
#990000
);

color:white;

}

.btn:hover{

opacity:.9;

}

.note{

margin-top:15px;

font-size:13px;

color:#ccc;

line-height:1.8;

}

.status{

display:inline-block;

padding:8px 15px;

border-radius:30px;

background:#422006;

color:#facc15;

font-size:13px;

margin-top:10px;

}

</style></head><body><div class="container"><div class="logo">CNTECH
<span>STORE</span>

</div><div class="card"><div class="title"><i class="fa-solid fa-file-invoice"></i>
Order Information

</div><div class="row"><span>Order ID</span>

<b>
<?=htmlspecialchars(
$order['order_id']
)?>
</b></div><div class="row"><span>Payment Method</span>

<b>
Manual Payment
</b></div><div class="status">Waiting For Payment

</div><div class="amount">₭ <?=number_format(
$total,
2
)?>

</div></div><div class="card"><div class="title"><i class="fa-solid fa-qrcode"></i>
Scan QR Payment

</div><div class="qr-box"><img
src="/uploads/payment_qr.png"
alt="QR Payment">

</div><div class="bank-info"><b>Account Name:</b>
Souksakhon Marketing

<br><b>Bank:</b>
BCEL / LDB

<br><b>Account Number:</b>
020XXXXXXXXX

</div></div><div class="card"><div class="title"><i class="fa-solid fa-upload"></i>
Upload Payment Slip

</div><form
action="upload_slip.php"
method="post"
enctype="multipart/form-data"><input
type="hidden"
name="order_id"
value="<?=htmlspecialchars(
$order['order_id']
)?>">

<input
type="file"
name="slip"
required>

<button
type="submit"
class="btn">

<i class="fa-solid fa-paper-plane"></i>
Submit Slip

</button></form><div class="note">หลังจากอัปโหลดสลิปแล้ว
ระบบจะส่งคำขอไปยังผู้ดูแลระบบ

<br>เมื่อผู้ดูแลตรวจสอบและยืนยันการชำระเงินแล้ว

<br>สินค้า Digital (Game / Mobile / Voucher)
จะถูกดำเนินการทันที

</div></div></div></body>
</html>