<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors",1);

require __DIR__ . "../database.php";
require __DIR__ . "/api/fulfillment.php";

/*
=================================
GET PARAM
=================================
*/
$order_id = trim($_GET['order_id'] ?? '');
$type     = strtolower(trim($_GET['type'] ?? ''));

if(!$order_id){
    die("Missing Order ID");
}

/*
=================================
TABLE MAP
=================================
*/
$tables = [
    "shop"     => "shop_orders",
    "game"     => "game_orders",
    "mobile"   => "mobile_orders",
    "voucher"  => "voucher_orders"
];

$table = $tables[$type] ?? null;

if(!$table){
    die("Invalid order type");
}

/*
=================================
GET ORDER
=================================
*/
$stmt = $conn->prepare("
    SELECT *
    FROM {$table}
    WHERE order_id=?
    LIMIT 1
");

$stmt->bind_param("s",$order_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

if(!$order){
    die("Order not found");
}

/*
=================================
PAYMENT PROCESS
=================================
*/
if(($order['payment_status'] ?? 'pending') !== 'paid'){

    /*
    -------------------------
    UPDATE PAYMENT STATUS
    -------------------------
    */
    $stmt = $conn->prepare("
        UPDATE {$table}
        SET
            payment_status='paid',
            status='completed',
            paid_at=NOW()
        WHERE order_id=?
    ");

    $stmt->bind_param("s",$order_id);
    $stmt->execute();

    $result = false;

    /*
    -------------------------
    SHOP
    -------------------------
    */
    if($type === "shop"){

        $result = processShop(
            $conn,
            $order
        );
    }

    /*
    -------------------------
    GAME
    -------------------------
    */
    if($type === "game"){

        $result = processGame(
            $conn,
            $order
        );
    }

    /*
    -------------------------
    MOBILE
    -------------------------
    */
    if($type === "mobile"){

        $result = processMobile(
            $conn,
            $order
        );
    }

    /*
    -------------------------
    VOUCHER
    -------------------------
    */
    if($type === "voucher"){

        $result = processVoucher(
            $conn,
            $order
        );
    }

    /*
    -------------------------
    SAVE FULFILLMENT STATUS
    -------------------------
    */
    $fulfillment =
        $result
        ? "success"
        : "failed";

    $stmt = $conn->prepare("
        UPDATE {$table}
        SET fulfillment_status=?
        WHERE order_id=?
    ");

    $stmt->bind_param(
        "ss",
        $fulfillment,
        $order_id
    );

    $stmt->execute();

    unset($_SESSION['cart']);
}

/*
=================================
REFRESH ORDER
=================================
*/
$stmt = $conn->prepare("
    SELECT *
    FROM {$table}
    WHERE order_id=?
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

/*
=================================
TOTAL
=================================
*/
$total = 0;

if(isset($order['total'])){
    $total = $order['total'];
}

if(isset($order['amount'])){
    $total = $order['amount'];
}

if(isset($order['price'])){
    $total = $order['price'];
}

$fulfillment =
    $order['fulfillment_status']
    ?? 'processing';

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport"
      content="width=device-width,initial-scale=1">

<title>Payment Success</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial;
    background:#0f172a;
    color:#fff;
}

.wrapper{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.card{
    width:100%;
    max-width:550px;
    background:#1e293b;
    border-radius:20px;
    padding:40px;
    text-align:center;
}

.icon{
    width:100px;
    height:100px;
    margin:auto;
    border-radius:50%;
    background:#16a34a;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:50px;
}

h1{
    color:#22c55e;
}

.info{
    background:#0f172a;
    margin:10px 0;
    padding:15px;
    border-radius:10px;
    text-align:left;
}

.label{
    color:#94a3b8;
    font-size:14px;
}

.value{
    font-size:18px;
    font-weight:bold;
    margin-top:5px;
}

.success{
    color:#22c55e;
}

.failed{
    color:#ef4444;
}

.processing{
    color:#f59e0b;
}

.footer{
    margin-top:25px;
    color:#94a3b8;
}

</style>

</head>

<body>

<div class="wrapper">

<div class="card">

<div class="icon">
✓
</div>

<h1>
Payment Successful
</h1>

<div class="info">
<div class="label">
Order ID
</div>
<div class="value">
<?= htmlspecialchars($order_id) ?>
</div>
</div>

<div class="info">
<div class="label">
Service Type
</div>
<div class="value">
<?= strtoupper($type) ?>
</div>
</div>

<div class="info">
<div class="label">
Total
</div>
<div class="value">
₭ <?= number_format($total,2) ?>
</div>
</div>

<div class="info">
<div class="label">
Payment Status
</div>
<div class="value success">
<?= htmlspecialchars(
    $order['payment_status']
) ?>
</div>
</div>

<div class="info">
<div class="label">
Fulfillment
</div>
<div class="value <?= $fulfillment ?>">
<?= strtoupper($fulfillment) ?>
</div>
</div>

<?php if($type=="game"){ ?>
<div class="footer">
🎮 ระบบกำลังดำเนินการเติมเกม
</div>
<?php } ?>

<?php if($type=="mobile"){ ?>
<div class="footer">
📱 ระบบกำลังดำเนินการเติมเงินมือถือ
</div>
<?php } ?>

<?php if($type=="voucher"){ ?>
<div class="footer">
🎁 ระบบกำลังดำเนินการส่ง PIN CODE
</div>
<?php } ?>

<?php if($type=="shop"){ ?>
<div class="footer">
📦 ระบบกำลังเตรียมจัดส่งสินค้า
</div>
<?php } ?>

<div class="footer">
Redirecting to homepage...
</div>

</div>

</div>

<script>
setTimeout(function(){
    location.href="index.php";
},8000);
</script>

</body>
</html>