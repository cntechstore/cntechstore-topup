<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "database.php";


// ==========================
// LOGIN
// ==========================

if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit;

}


$user_id = $_SESSION['user_id'];


// ==========================
// GET USER EMAIL
// ==========================

$user_stmt=$conn->prepare("
SELECT email
FROM users
WHERE id=?
LIMIT 1
");


$user_stmt->bind_param(
"i",
$user_id
);


$user_stmt->execute();


$user_data=$user_stmt
->get_result()
->fetch_assoc();


$user_email=$user_data['email'] ?? "";



// ==========================
// ORDER ARRAY
// ==========================

$orders=[];



// ==========================
// MOBILE ORDERS
// ==========================


$sql="
SELECT

id,
order_id,
provider,
phone,
amount,
payment_status,
status,
created_at

FROM mobile_orders

WHERE user_id=?

ORDER BY id DESC
";


$stmt=$conn->prepare($sql);


$stmt->bind_param(
"i",
$user_id
);


$stmt->execute();


$result=$stmt->get_result();



while($row=$result->fetch_assoc()){


$orders[]=[

"id"=>$row['id'],

"type"=>"mobile",

"order_id"=>$row['order_id'],

"product"=>
$row['provider']." - ".$row['phone'],

"amount"=>$row['amount'],

"payment_status"=>$row['payment_status'],

"status"=>$row['status'],

"created_at"=>$row['created_at']

];


}



// ==========================
// GAME ORDERS
// ==========================


$sql="
SELECT

id,
order_id,
product,
price,
payment_status,
status,
created_at

FROM game_orders

WHERE

user_id=?
OR
email=?

ORDER BY id DESC
";


$stmt=$conn->prepare($sql);


$stmt->bind_param(
"is",
$user_id,
$user_email
);


$stmt->execute();


$result=$stmt->get_result();



while($row=$result->fetch_assoc()){


$orders[]=[

"id"=>$row['id'],

"type"=>"game",

"order_id"=>$row['order_id'],

"product"=>
"Game ID : ".$row['product'],

"amount"=>$row['price'],

"payment_status"=>$row['payment_status'],

"status"=>$row['status'],

"created_at"=>$row['created_at']

];


}

// ==========================
// VOUCHER ORDERS
// ==========================


$sql="
SELECT

id,
order_id,
product_id,
total,
payment_status,
order_status,
status,
created_at

FROM voucher_orders

WHERE

user_id=?
OR
email=?

ORDER BY id DESC
";


$stmt=$conn->prepare($sql);


$stmt->bind_param(
"is",
$user_id,
$user_email
);


$stmt->execute();


$result=$stmt->get_result();



while($row=$result->fetch_assoc()){


$order_status =
$row['order_status']
??
$row['status']
??
"pending";



$orders[]=[

"id"=>$row['id'],

"type"=>"voucher",

"order_id"=>$row['order_id'],

"product"=>

"Voucher ID : "
.$row['product_id'],

"amount"=>$row['total'],

"payment_status"=>$row['payment_status'],

"status"=>$order_status,

"created_at"=>$row['created_at']

];


}




// ==========================
// SHOP ORDERS
// ==========================


$sql="
SELECT

id,
order_id,
items,
total,
payment_status,
status,
created_at

FROM shop_orders

WHERE

user_id=?
OR
email=?

ORDER BY id DESC
";



$stmt=$conn->prepare($sql);


$stmt->bind_param(
"is",
$user_id,
$user_email
);


$stmt->execute();


$result=$stmt->get_result();



while($row=$result->fetch_assoc()){


$item_text=$row['items'];



if(strlen($item_text)>80){

$item_text=
substr($item_text,0,80)
."...";

}



$orders[]=[


"id"=>$row['id'],

"type"=>"shop",

"order_id"=>$row['order_id'],

"product"=>$item_text,

"amount"=>$row['total'],

"payment_status"=>$row['payment_status'],

"status"=>$row['status'],

"created_at"=>$row['created_at']


];


}




// ==========================
// SORT DATE
// ==========================


usort(

$orders,

function($a,$b){

return strtotime($b['created_at'])
-
strtotime($a['created_at']);

}

);




// ==========================
// STATISTICS
// ==========================


$total_orders=count($orders);


$paid_orders=0;

$pending_orders=0;

$total_spending=0;



foreach($orders as $order){



if(
strtolower($order['payment_status'])
=="paid"
){

$paid_orders++;

$total_spending +=
$order['amount'];

}
else{

$pending_orders++;

}



}


?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">


<title>
CN TECH STORE - My Orders
</title>


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">


<style>


*{

box-sizing:border-box;
font-family:Arial,Poppins,sans-serif;

}


body{

margin:0;

background:

linear-gradient(
135deg,
#050505,
#250000
);

color:white;

}



/* NAVBAR */


.navbar{

height:70px;

background:#090909;

border-bottom:1px solid red;

display:flex;

align-items:center;

justify-content:space-between;

padding:0 30px;

}



.logo{

font-size:25px;

font-weight:bold;

color:#ff0000;

}



.navbar a{

color:white;

text-decoration:none;

margin-left:20px;

}



.container{

max-width:1200px;

margin:auto;

padding:30px;

}



/* TITLE */


.title{

font-size:32px;

font-weight:bold;

margin-bottom:25px;

}



/* CARD */


.stats{

display:grid;

grid-template-columns:
repeat(4,1fr);

gap:20px;

margin-bottom:30px;

}



.stat{

background:#111;

padding:25px;

border-radius:20px;

border:1px solid #333;

box-shadow:

0 0 20px rgba(255,0,0,.25);

}



.stat i{

font-size:30px;

color:red;

}



.stat h3{

color:#aaa;

margin:10px 0;

}



.stat span{

font-size:28px;

font-weight:bold;

}




/* SEARCH */


.search{

margin-bottom:20px;

}



.search input{

width:100%;

padding:15px;

background:#222;

border:none;

border-radius:15px;

color:white;

font-size:16px;

}




/* TABLE */


.table-box{

background:#111;

padding:20px;

border-radius:20px;

overflow:auto;

}



table{

width:100%;

border-collapse:collapse;

}



th{

background:#b00000;

padding:15px;

text-align:left;

}



td{

padding:14px;

border-bottom:1px solid #333;

}




.type{

background:#222;

color:#ff3333;

padding:6px 12px;

border-radius:20px;

font-weight:bold;

}




.status{

padding:6px 12px;

border-radius:20px;

font-weight:bold;

}



.completed{

background:#16a34a;

}



.pending{

background:#f59e0b;

}



.cancelled{

background:#dc2626;

}




.view-btn{

background:red;

color:white;

padding:8px 15px;

border-radius:10px;

text-decoration:none;

font-weight:bold;

}



.view-btn:hover{

background:#900;

}



@media(max-width:800px){


.stats{

grid-template-columns:1fr 1fr;

}


}



</style>


</head>


<body>



<nav class="navbar">


<div class="logo">

<i class="fa-solid fa-store"></i>

CN TECH STORE

</div>



<div>


<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

Dashboard

</a>


<a href="profile.php">

<i class="fa-solid fa-user"></i>

Profile

</a>


<a href="logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>


</div>


</nav>




<div class="container">



<div class="title">

<i class="fa-solid fa-clock-rotate-left"></i>

My Orders

</div>




<div class="stats">



<div class="stat">

<i class="fa-solid fa-cart-shopping"></i>

<h3>
Total Orders
</h3>

<span>

<?=$total_orders?>

</span>

</div>




<div class="stat">

<i class="fa-solid fa-circle-check"></i>

<h3>
Paid Orders
</h3>

<span>

<?=$paid_orders?>

</span>

</div>





<div class="stat">

<i class="fa-solid fa-hourglass-half"></i>

<h3>
Pending
</h3>

<span>

<?=$pending_orders?>

</span>

</div>





<div class="stat">

<i class="fa-solid fa-coins"></i>

<h3>
Total Spending
</h3>

<span>

<?=number_format($total_spending,2)?>

</span>

</div>


    </div>
    
    <!-- SEARCH -->

<div class="search">

<input 
type="text"
id="searchInput"
placeholder="Search Order ID / Product..."
onkeyup="searchOrder()"
>

</div>




<div class="table-box">


<table id="orderTable">


<thead>

<tr>

<th>
Order ID
</th>

<th>
Type
</th>

<th>
Product
</th>

<th>
Amount
</th>

<th>
Payment
</th>

<th>
Status
</th>

<th>
Date
</th>

<th>
Action
</th>

</tr>

</thead>



<tbody>



<?php foreach($orders as $row): ?>


<tr>


<td>

#

<?=htmlspecialchars($row['order_id'])?>

</td>




<td>

<span class="type">

<?=ucfirst($row['type'])?>

</span>

</td>




<td>

<?=htmlspecialchars($row['product'])?>

</td>




<td>

<?=number_format($row['amount'],2)?>

 LAK

</td>




<td>

<?=htmlspecialchars(
$row['payment_status']
)?>

</td>




<td>


<?php


$status=strtolower(
$row['status']
);



$class="pending";


if(
$status=="completed"
||
$status=="paid"
){

$class="completed";

}

elseif(

$status=="cancelled"
||
$status=="failed"

){

$class="cancelled";

}


?>


<span class="status <?=$class?>">

<?=htmlspecialchars(
$row['status']
)?>

</span>


</td>





<td>

<?=date(

"d/m/Y H:i",

strtotime(
$row['created_at']
)

)?>

</td>





<td>


<a class="view-btn"

href="order_detail.php?type=<?=urlencode($row['type'])?>&id=<?=$row['id']?>">


<i class="fa-solid fa-eye"></i>

View


</a>


</td>




</tr>



<?php endforeach; ?>



</tbody>


</table>


</div>


</div>




<script>


function searchOrder(){


let input =
document
.getElementById("searchInput")
.value
.toLowerCase();



let rows =
document
.querySelectorAll(
"#orderTable tbody tr"
);



rows.forEach(row=>{


let text =
row.innerText
.toLowerCase();



if(
text.includes(input)
){


row.style.display="";


}

else{


row.style.display="none";


}



});


}



</script>



</body>

</html>