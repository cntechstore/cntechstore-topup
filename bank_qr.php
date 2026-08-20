<?php
include("database.php");

$order_id = intval($_GET["order_id"] ?? 0);

// ดึงข้อมูล order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found");
}

$amount = $order["total"];

// สร้าง payload QR (future-ready format)
$qr_payload = "LAOSPAY|ORDER:$order_id|AMOUNT:$amount";

// generate QR image (demo generator)
$qr_image = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data="
            . urlencode($qr_payload);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bank QR Payment</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    font-family:Arial;
    background:#f5f6fa;
    margin:0;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:#fff;
    padding:20px;
    border-radius:18px;
    width:320px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

.qr img{
    width:220px;
    margin:15px 0;
}

.amount{
    font-size:20px;
    color:#007aff;
    font-weight:bold;
}

.timer{
    color:red;
    font-weight:bold;
    margin-top:10px;
}

.btn{
    margin-top:15px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#28a745;
    color:white;
    font-size:16px;
    cursor:pointer;
}

.bank{
    font-size:14px;
    color:#666;
}
</style>
</head>

<body>

<div class="box">

<h2>🏦 Bank QR Payment</h2>

<p>Order #<?= $order_id ?></p>

<div class="amount">
<?= number_format($amount,2) ?> LAK
</div>

<div class="bank">
BCEL / LDB / LAO QR Supported
</div>

<div class="qr">
    <img src="<?= $qr_image ?>" alt="QR Code">
</div>

<div class="timer" id="timer">10:00</div>

<button class="btn" onclick="checkPaid()">I have paid</button>

</div>

<script>

// ⏱ 10 minute timer
let time = 600;

setInterval(() => {
    time--;

    let m = Math.floor(time / 60);
    let s = time % 60;

    document.getElementById("timer").innerText =
        m + ":" + (s < 10 ? "0" + s : s);

    if(time <= 0){
        location.reload();
    }

}, 1000);


// 🔍 manual check (future auto webhook)
function checkPaid(){
    fetch("check_payment.php?order_id=<?= $order_id ?>")
    .then(res => res.text())
    .then(data => {
        if(data === "PAID"){
            alert("Payment Success!");
            window.location.href = "payment_success.php?order_id=<?= $order_id ?>";
        } else {
            alert("Still waiting payment...");
        }
    });
}

</script>

</body>
</html>