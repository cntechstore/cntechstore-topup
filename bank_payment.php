<?php
session_start();
include("database.php");

if (!isset($_GET['order_id'])) {
    die("Order not found");
}

$order_id = intval($_GET['order_id']);

// ดึงข้อมูล order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Invalid order");
}
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>Bank Payment</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body {
    font-family: Arial;
    background:#f5f6fa;
}

.box {
    max-width:600px;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:15px;
}

.amount {
    font-size:24px;
    color:#007aff;
    font-weight:bold;
}

input, button {
    width:100%;
    padding:12px;
    margin-top:10px;
}

button {
    background:#28a745;
    color:white;
    border:none;
    border-radius:10px;
}

</style>

</head>

<body>

<div class="box">

<h2>🏦 Bank Transfer</h2>

<p>Order ID: #<?= $order['id'] ?></p>

<p class="amount">
Amount: ฿<?= number_format($order['total'],2) ?>
</p>

<hr>

<h3>📌 Bank Account</h3>

<p>
Bank: Lao Development Bank (LDB)<br>
Account Name: CN Tech Store<br>
Account No: 0300900410028447
</p>

<hr>

<form method="POST" enctype="multipart/form-data">

<p>Upload Payment Slip</p>

<input type="file" name="slip" accept="image/*" required>

<button type="submit">Confirm Payment</button>

</form>

</div>

</body>

</html>

<?php

// ================= UPLOAD SLIP =================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $file = $_FILES['slip']['name'];
    $tmp  = $_FILES['slip']['tmp_name'];

    $folder = "../uploads/slips/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $newName = time() . "_" . $file;

    move_uploaded_file($tmp, $folder . $newName);


    // อัปเดตฐานข้อมูล
    $stmt = $conn->prepare("
        UPDATE orders
        SET payment_status='pending',
            status='processing',
            gateway='bank_transfer',
            payment_method='bank',
            slip_image=?
        WHERE id=?
    ");

    $stmt->bind_param("si", $newName, $order_id);
    $stmt->execute();


    echo "<script>
        alert('Payment submitted! Waiting for confirmation');
        window.location='order_success.php?order_id=$order_id';
    </script>";

}
?>