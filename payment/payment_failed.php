<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require "../database.php";

/*
==================================
GET ORDER ID
==================================
*/

$order_id = trim($_GET['order_id'] ?? '');

if(empty($order_id)){

    die("Order ID not found");

}


/*
==================================
DEFAULT
==================================
*/

$order = null;

$order_type = '';

$table_name = '';



/*
==================================
SEARCH GAME ORDERS
==================================
*/

$stmt = $conn->prepare("
SELECT *
FROM game_orders
WHERE order_id=?
LIMIT 1
");

$stmt->bind_param(
"s",
$order_id
);

$stmt->execute();

$result =
$stmt->get_result();

if($result->num_rows > 0){

    $order =
    $result->fetch_assoc();

    $table_name =
    "game_orders";

    $order_type =
    "Game Top-up";

}



/*
==================================
SEARCH VOUCHER
==================================
*/

if(!$order){

$stmt = $conn->prepare("
SELECT *
FROM voucher_orders
WHERE order_id=?
LIMIT 1
");

$stmt->bind_param(
"s",
$order_id
);

$stmt->execute();

$result =
$stmt->get_result();

if($result->num_rows > 0){

    $order =
    $result->fetch_assoc();

    $table_name =
    "voucher_orders";

    $order_type =
    "Voucher";

}

}



/*
==================================
SEARCH SHOP
==================================
*/

if(!$order){

$stmt = $conn->prepare("
SELECT *
FROM shop_orders
WHERE order_id=?
LIMIT 1
");

$stmt->bind_param(
"s",
$order_id
);

$stmt->execute();

$result =
$stmt->get_result();

if($result->num_rows > 0){

    $order =
    $result->fetch_assoc();

    $table_name =
    "shop_orders";

    $order_type =
    "Shop";

}

}



/*
==================================
SEARCH MOBILE
==================================
*/

if(!$order){

$stmt = $conn->prepare("
SELECT *
FROM mobile_orders
WHERE order_id=?
LIMIT 1
");

$stmt->bind_param(
"s",
$order_id
);

$stmt->execute();

$result =
$stmt->get_result();

if($result->num_rows > 0){

    $order =
    $result->fetch_assoc();

    $table_name =
    "mobile_orders";

    $order_type =
    "Mobile Top-up";

}

}



/*
==================================
ORDER NOT FOUND
==================================
*/

if(!$order){

    die("Order not found");

}



/*
==================================
TOTAL
==================================
*/

$total = 0;

if($table_name == "game_orders"){

    $total =
    (float)$order['price'];

}

elseif($table_name == "voucher_orders"){

    $total =
    (float)$order['total'];

}

elseif($table_name == "shop_orders"){

    $total =
    (float)$order['total'];

}

elseif($table_name == "mobile_orders"){

    $total =
    (float)$order['amount'];

}



/*
==================================
STATUS
==================================
*/

$payment_status =
$order['payment_status']
?? 'failed';



/*
==================================
DATE
==================================
*/

$created_at =
$order['created_at']
?? date("Y-m-d H:i:s");

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">


<title>
Payment Failed | CN Tech Store
</title>


<style>


*{

margin:0;
padding:0;
box-sizing:border-box;
font-family:
"Segoe UI",
Arial,
sans-serif;

}



body{

min-height:100vh;

background:
linear-gradient(
135deg,
#f8fafc,
#eef2ff
);

display:flex;

justify-content:center;

align-items:center;

padding:20px;

}



/*
=====================
CARD
=====================
*/


.payment-card{


width:100%;

max-width:520px;

background:#fff;

border-radius:28px;

padding:35px;

box-shadow:

0 20px 50px
rgba(0,0,0,.12);


text-align:center;


}




/*
=====================
ICON
=====================
*/


.failed-icon{


width:100px;

height:100px;

margin:auto;

border-radius:50%;


display:flex;

align-items:center;

justify-content:center;


font-size:55px;


background:
#fee2e2;


color:
#dc2626;


margin-bottom:20px;


}



/*
=====================
TITLE
=====================
*/


h1{


color:#dc2626;

font-size:30px;

margin-bottom:10px;


}



.subtitle{


color:#64748b;

font-size:16px;

margin-bottom:25px;


}



/*
=====================
ORDER BOX
=====================
*/


.order-box{


background:#f8fafc;

border-radius:18px;

padding:20px;

text-align:left;


margin-bottom:20px;


}



.row{


display:flex;

justify-content:space-between;

padding:12px 0;

border-bottom:
1px solid #e5e7eb;


}



.row:last-child{

border:none;

}



.row span{

color:#64748b;

}



.row b{

color:#111827;

}




.failed-status{


color:#dc2626!important;

}



/*
=====================
REASON
=====================
*/


.reason{


background:#fff7ed;

border:

1px solid #fdba74;


padding:18px;

border-radius:16px;


color:#9a3412;


line-height:1.7;


margin-bottom:25px;


}



/*
=====================
BUTTON
=====================
*/


.btn{


width:100%;


padding:15px;


border-radius:15px;


border:none;


font-size:16px;


font-weight:600;


cursor:pointer;


text-decoration:none;


display:block;


margin-bottom:12px;


background:

linear-gradient(
135deg,
#2563eb,
#1d4ed8
);


color:white;


transition:.25s;


}



.btn:hover{


transform:
translateY(-2px);


}




.btn-outline{


background:#fff;


color:#2563eb;


border:

2px solid #2563eb;


}



/*
=====================
COUNTDOWN
=====================
*/


.redirect{


margin-top:15px;

font-size:14px;

color:#64748b;


}


#count{


font-weight:bold;

color:#2563eb;


}



</style>


</head>


<body>



<div class="payment-card">



<div class="failed-icon">

❌

</div>




<h1>

Payment Failed

</h1>



<p class="subtitle">

การชำระเงินไม่สำเร็จ

</p>





<div class="order-box">



<div class="row">

<span>
Order ID
</span>

<b>

<?=htmlspecialchars($order['order_id'])?>

</b>


</div>





<div class="row">

<span>
Service
</span>

<b>

<?=htmlspecialchars($order_type)?>

</b>


</div>





<div class="row">

<span>
Total
</span>

<b>

₭ <?=number_format($total,2)?>

</b>


</div>





<div class="row">

<span>
Payment Status
</span>


<b class="failed-status">

<?=strtoupper($payment_status)?>

</b>


</div>





<div class="row">

<span>
Created
</span>


<b>

<?=date(
"d/m/Y H:i",
strtotime($created_at)
)?>

</b>


</div>



</div>






<div class="reason">


<strong>
Reason
</strong>


<br><br>


จำนวนเงินในบัญชีไม่เพียงพอ


<br>


กรุณาตรวจสอบยอดเงิน


และทำรายการใหม่อีกครั้ง



</div>





<a

href="payment.php?order_id=<?=$order_id?>"

class="btn">

 ชำระเงินอีกครั้ง

</a>




<a

href="/"

class="btn btn-outline">

 กลับหน้าแรก

</a>





<div class="redirect">

กลับหน้าแรกอัตโนมัติใน

<span id="count">

8

</span>

วินาที


</div>




    </div>
    
    <script>

/*
=========================
AUTO REDIRECT
=========================
*/


let seconds = 8;


let count =
document.getElementById("count");



let timer =
setInterval(()=>{


    seconds--;


    count.innerHTML =
    seconds;



    if(seconds <= 0){


        clearInterval(timer);


        window.location.href = "/";


    }


},1000);



</script>


</body>

</html>