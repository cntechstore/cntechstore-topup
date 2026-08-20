<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "database.php";


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}


$user_id = (int)$_SESSION['user_id'];

$order_id =
$_GET['order_id']
?? "";


if ($order_id === "") {

    die("Order ID Missing");

}



$stmt = $conn->prepare("

SELECT *

FROM cn_coin_orders

WHERE order_id=?

AND user_id=?

LIMIT 1

");


$stmt->bind_param(
"si",
$order_id,
$user_id
);

$stmt->execute();

$order =
$stmt->get_result()
->fetch_assoc();


if (!$order) {

    die("Order Not Found");

}


?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>
CN Coins Payment
</title>


<style>

*{
box-sizing:border-box;
}

body{

margin:0;

background:
linear-gradient(
135deg,
#050505,
#280000
);

color:white;

font-family:Arial;

padding:20px;

}


.card{

max-width:550px;

margin:auto;

background:#111;

border:1px solid red;

border-radius:20px;

padding:25px;

}


.logo{

text-align:center;

font-size:25px;

font-weight:bold;

color:red;

}


.row{

padding:15px;

margin-top:12px;

background:#1a1a1a;

border-radius:12px;

}


.label{

color:#999;

font-size:13px;

}


.value{

margin-top:6px;

font-weight:bold;

}


.amount{

font-size:30px;

color:#ffd700;

}


.qr{

margin:
25px auto;

width:250px;

height:250px;

background:white;

display:flex;

align-items:center;

justify-content:center;

color:#111;

text-align:center;

}


.btn{

display:block;

padding:15px;

background:red;

color:white;

text-decoration:none;

text-align:center;

border-radius:12px;

margin-top:12px;

font-weight:bold;

}


</style>

</head>


<body>


<div class="card">


<div class="logo">

CN TECH STORE

</div>


<div class="row">

<div class="label">
Order ID
</div>

<div class="value">
<?=htmlspecialchars($order['order_id'])?>
</div>

</div>


<div class="row">

<div class="label">
CN Coins
</div>

<div class="value">

🪙
<?=number_format($order['coins'])?>

CN Coins

</div>

</div>


<div class="row">

<div class="label">
Amount
</div>

<div class="value amount">

<?=number_format($order['amount'])?>

LAK

</div>

</div>


<div class="qr">

QR PAYMENT

<br>

เชื่อมต่อระบบ QR<br>
ของ CN Tech Store

</div>


<a
class="btn"
href="coins.php">

← กลับ CN Coins

</a>


</div>

</body>

</html>