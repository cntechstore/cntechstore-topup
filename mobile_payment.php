<?php
require "../database.php";
session_start();

$order_id = $_GET['order_id'] ?? $_GET['order'] ?? '';

if(!$order_id){
    die("Missing Order ID");
}

$stmt = $conn->prepare("
    SELECT *
    FROM mobile_orders
    WHERE order_id=?
    LIMIT 1
");

$stmt->bind_param("s", $order_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

if(!$order){
    die("Order not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Mobile Payment</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#0f172a;
    color:#fff;
    padding:20px;
}

.container{
    max-width:800px;
    margin:auto;
}

.card{
    background:#1e293b;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
}

h2,h3{margin:0 0 10px 0;}

.info{
    line-height:1.8;
}

.btn-pay{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    background:#22c55e;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    transition:.2s;
}

.btn-pay:hover{
    background:#16a34a;
    transform:scale(1.02);
}

.loading{
    display:none;
    text-align:center;
    margin-top:15px;
    color:#38bdf8;
}

</style>
</head>

<body>

<div class="container">

    <!-- ORDER INFO -->
    <div class="card">
        <h2>📱 Mobile Payment</h2>
        <hr>

        <div class="info">
            <p>Order ID: <b><?= htmlspecialchars($order['order_id']) ?></b></p>
            <p>Phone: <b><?= htmlspecialchars($order['phone']) ?></b></p>
            <p>Provider: <b><?= htmlspecialchars($order['provider']) ?></b></p>
            <p>Amount: <b>₭ <?= number_format($order['amount']) ?></b></p>
        </div>
    </div>

    <!-- PAYMENT BUTTON -->
    <div class="card">

        <h3>Confirm Payment</h3>

        <button class="btn-pay" onclick="startPayment()">
            Pay Now
        </button>

        <div class="loading" id="loading">
            ⏳ Processing payment...
        </div>

    </div>

</div>

<script>

function startPayment(){

    document.getElementById("loading").style.display = "block";

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "mobile_sc.php";

    const order = document.createElement("input");
    order.type = "hidden";
    order.name = "order_id";
    order.value = "<?= $order['order_id'] ?>";

    const amount = document.createElement("input");
    amount.type = "hidden";
    amount.name = "amount";
    amount.value = "<?= $order['amount'] ?>";

    const provider = document.createElement("input");
    provider.type = "hidden";
    provider.name = "provider";
    provider.value = "<?= $order['provider'] ?>";

    form.appendChild(order);
    form.appendChild(amount);
    form.appendChild(provider);

    document.body.appendChild(form);
    form.submit();
}

    </script>

</body>
</html>