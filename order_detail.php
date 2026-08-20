<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors',1);

require "database.php";


// ==========================
// CHECK LOGIN
// ==========================

if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit;

}


// ==========================
// CHECK DATA
// ==========================

if(!isset($_GET['id']) || !isset($_GET['type'])){

    die("Order Data Missing");

}


$id = intval($_GET['id']);

$type = strtolower(trim($_GET['type']));



// ==========================
// TYPE MAP
// ==========================

$type_map = [

    "mobile"=>"mobile",
    "mobile_orders"=>"mobile",
    "mobile order"=>"mobile",

    "game"=>"game",
    "game_orders"=>"game",
    "game order"=>"game",

    "voucher"=>"voucher",
    "voucher_orders"=>"voucher",
    "voucher order"=>"voucher",

    "shop"=>"shop",
    "shop_orders"=>"shop",
    "shop order"=>"shop"

];


if(!isset($type_map[$type])){

    die("Invalid Order Type : ".$type);

}


$type = $type_map[$type];



// ==========================
// TABLE CONFIG
// ==========================

$tables = [

    "mobile"=>[
        "table"=>"mobile_orders",
        "name"=>"Mobile",
        "icon"=>"fa-mobile-screen"
    ],


    "game"=>[
        "table"=>"game_orders",
        "name"=>"Game",
        "icon"=>"fa-gamepad"
    ],


    "voucher"=>[
        "table"=>"voucher_orders",
        "name"=>"Voucher",
        "icon"=>"fa-ticket"
    ],


    "shop"=>[
        "table"=>"shop_orders",
        "name"=>"Shop",
        "icon"=>"fa-cart-shopping"
    ]

];


$table = $tables[$type]['table'];

$type_name = $tables[$type]['name'];

$icon = $tables[$type]['icon'];



// ==========================
// GET ORDER
// ==========================

$sql = "

SELECT *,

'$type_name' AS type_name

FROM `$table`

WHERE id=?

LIMIT 1

";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$result = $stmt->get_result();


$order = $result->fetch_assoc();



if(!$order){

    die("Order Not Found");

}




// ==========================
// FORMAT PHONE
// ==========================

function formatPhone($phone){

    if(empty($phone)){

        return "-";

    }


    $phone = str_replace(
        " ",
        "",
        $phone
    );


    if(substr($phone,0,1)!="0"){

        $phone="0".$phone;

    }


    return $phone;

}



// ==========================
// STATUS
// ==========================

$status = strtolower(

    $order['status']
    ??
    $order['order_status']
    ??
    "pending"

);



$status_class="pending";


if($status=="completed" || $status=="success"){

    $status_class="success";

}
elseif($status=="cancelled" || $status=="failed"){

    $status_class="failed";

}


?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">


<title>
CN TECH STORE ORDER
</title>


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">


<style>

*{

box-sizing:border-box;
font-family:Arial,sans-serif;

}


body{

margin:0;

padding:30px;

min-height:100vh;

background:
linear-gradient(
135deg,
#050505,
#300000
);

color:white;

}


.card{

max-width:650px;

margin:auto;

background:#111;

border:1px solid #ff0000;

border-radius:25px;

padding:30px;

box-shadow:
0 0 30px rgba(255,0,0,.5);

}


.logo{

text-align:center;

font-size:32px;

font-weight:bold;

color:#ff0000;

text-shadow:
0 0 15px red;

}


.type{

text-align:center;

margin:15px;

font-size:20px;

color:#ddd;

}


.type i{

color:red;

margin-right:8px;

}


.item{

background:#1a1a1a;

padding:15px;

border-radius:15px;

margin:15px 0;

border-left:4px solid red;

}


.label{

color:#ff4444;

font-weight:bold;

}


.value{

margin-top:8px;

word-break:break-word;

}


.amount{

font-size:30px;

font-weight:bold;

color:#ffd700;

}



.status{

display:inline-block;

padding:10px 20px;

border-radius:30px;

font-weight:bold;

text-transform:uppercase;

}



.success{

background:#16a34a;

}



.pending{

background:#f59e0b;

}



.failed{

background:#dc2626;

}



.btn{

display:block;

margin-top:25px;

padding:15px;

text-align:center;

background:

linear-gradient(
135deg,
#ff0000,
#990000
);

color:white;

text-decoration:none;

border-radius:15px;

font-weight:bold;

}


.btn:hover{

box-shadow:
0 0 20px red;

}


</style>


</head>


<body>



<div class="card">


<div class="logo">

CN TECH STORE

</div>



<div class="type">

<i class="fa-solid <?=$icon?>"></i>

<?=$type_name?> ORDER

</div>




<div class="item">

<div class="label">

<i class="fa-solid fa-receipt"></i>
 Order ID

</div>

<div class="value">

<?=htmlspecialchars($order['order_id'])?>

</div>

</div>





<?php if($type=="mobile"){ ?>


<div class="item">

<div class="label">

<i class="fa-solid fa-sim-card"></i>
 Provider

</div>

<div class="value">

<?=htmlspecialchars($order['provider'])?>

</div>

</div>



<div class="item">

<div class="label">

<i class="fa-solid fa-phone"></i>
 Phone

</div>

<div class="value">

<?=formatPhone($order['phone'])?>

</div>

</div>


<?php } ?>






<?php if($type=="game"){ ?>


<div class="item">

<div class="label">

<i class="fa-solid fa-user"></i>
 UID

</div>

<div class="value">

<?=htmlspecialchars($order['uid'])?>

</div>

</div>



<div class="item">

<div class="label">

<i class="fa-solid fa-server"></i>
 Server

</div>

<div class="value">

<?=htmlspecialchars($order['server'])?>

</div>

</div>



<div class="item">

<div class="label">

<i class="fa-solid fa-gamepad"></i>
 Game Product ID

</div>

<div class="value">

<?=htmlspecialchars($order['product'])?>

</div>

</div>


<?php } ?>






<?php if($type=="voucher"){ ?>


<div class="item">

<div class="label">

<i class="fa-solid fa-ticket"></i>
 Product ID

</div>

<div class="value">

<?=htmlspecialchars($order['product_id'])?>

</div>

</div>



<div class="item">

<div class="label">

<i class="fa-solid fa-building"></i>
 Provider

</div>

<div class="value">

<?=htmlspecialchars($order['provider'] ?? "-")?>

</div>

</div>


<?php } ?>







<?php if($type=="shop"){ ?>


<div class="item">

<div class="label">

<i class="fa-solid fa-user"></i>
 Customer

</div>

<div class="value">

<?=htmlspecialchars($order['customer_name'])?>

</div>

</div>




<div class="item">

<div class="label">

<i class="fa-solid fa-box"></i>
 Products

</div>

<div class="value">

<?=htmlspecialchars($order['items'])?>

</div>

</div>


<?php } ?>







<div class="item">

<div class="label">

<i class="fa-solid fa-money-bill"></i>
 Amount

</div>


<div class="amount">

<?=number_format(

$order['amount']
??
$order['total']
??
$order['price']
??
0

)?>

 LAK

</div>

</div>






<div class="item">

<div class="label">

<i class="fa-solid fa-credit-card"></i>
 Payment Status

</div>


<div class="value">

<?=htmlspecialchars(

$order['payment_status']
??
"-"

)?>

</div>


</div>






<div class="item">

<div class="label">

<i class="fa-solid fa-circle-info"></i>
 Status

</div>


<span class="status <?=$status_class?>">

<?=htmlspecialchars($status)?>

</span>


</div>






<div class="item">

<div class="label">

<i class="fa-solid fa-calendar"></i>
 Created At

</div>


<div class="value">

<?=htmlspecialchars($order['created_at'])?>

</div>


</div>






<a class="btn" href="orders.php">

<i class="fa-solid fa-arrow-left"></i>

 Back Orders

</a>



</div>


</body>

</html>