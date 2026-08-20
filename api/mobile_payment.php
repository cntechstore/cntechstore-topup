<?php

require "../database.php";

session_start();


$order_id = $_GET['order_id'] ?? $_GET['order'] ?? '';


if(!$order_id){

die("Missing Order ID");

}



$stmt=$conn->prepare("

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


$order=$stmt->get_result()->fetch_assoc();



if(!$order){

die("Order not found");

}


?>


<!DOCTYPE html>

<html lang="lo">

<head>


<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1">


<meta name="theme-color"
content="#ff0000">


<title>
Mobile Payment | CN Tech Store
</title>


<link rel="icon"
href="../assets/favicon.png">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



<style>


*{

box-sizing:border-box;

font-family:Arial,sans-serif;

}



body{

margin:0;

background:#000;

color:#fff;

}



.container{

max-width:450px;

margin:auto;

padding:20px;

}



.logo{

text-align:center;

font-size:26px;

font-weight:bold;

color:#ff2020;

margin-bottom:20px;

}


.logo span{

color:#fff;

}



.card{


background:

rgba(255,255,255,.08);


border:1px solid #333;


backdrop-filter:blur(15px);


border-radius:22px;


padding:20px;


margin-bottom:20px;


box-shadow:

0 10px 30px rgba(255,0,0,.1);


}



h2,h3{

margin-top:0;

}



.row{

display:flex;

justify-content:space-between;

padding:12px 0;

border-bottom:

1px solid #333;

}



.row:last-child{

border:none;

}



.amount{


text-align:center;


font-size:32px;


font-weight:bold;


color:#ff3030;


margin:25px 0;


}




.status{


display:inline-block;


padding:7px 15px;


border-radius:30px;


background:#332000;


color:#ffcc00;


font-size:13px;


}



.btn{


width:100%;


padding:16px;


border:none;


border-radius:15px;


font-size:16px;


font-weight:bold;


cursor:pointer;


margin-top:12px;


}



.pay{


background:

linear-gradient(
135deg,
#ff2020,
#990000
);


color:#fff;


}



.cancel{


background:#222;

color:#fff;

border:1px solid #444;

text-decoration:none;

display:block;

text-align:center;

}



.loading{

display:none;

text-align:center;

margin-top:15px;

color:#ff3030;

}



</style>


</head>


<body>



<div class="container">


<div class="logo">

CNTECH <span>STORE</span>

</div>




<div class="card">


<h2>

<i class="fa-solid fa-mobile-screen"></i>

Mobile Payment

</h2>



<div class="status">

<?=htmlspecialchars($order['status'])?>

</div>



<br><br>



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
Phone
</span>


<b>
<?=htmlspecialchars($order['phone'])?>
</b>

</div>



<div class="row">

<span>
Provider
</span>


<b>
<?=htmlspecialchars($order['provider'])?>
</b>

</div>



<div class="amount">

₭ <?=number_format($order['amount'])?>

</div>



</div>





<div class="card">


<h3>

<i class="fa-solid fa-credit-card"></i>

Payment

</h3>



<button

class="btn pay"

onclick="startPayment()">


<i class="fa-solid fa-qrcode"></i>

Pay Now


</button>



<a

class="btn cancel"

href="cancel_mobile_order.php?order=<?=$order['order_id']?>"

onclick="return confirm('ຕ້ອງການຍົກເລີກ Order ບໍ?')">


<i class="fa-solid fa-xmark"></i>

Cancel Order


</a>



<div class="loading" id="loading">

<i class="fa-solid fa-spinner fa-spin"></i>

Processing Payment...


</div>



</div>


</div>




<script>


function startPayment(){


document
.getElementById("loading")
.style.display="block";



const form=document.createElement("form");


form.method="POST";


form.action="mobile_sc.php";



let data={


order_id:"<?=htmlspecialchars($order['order_id'])?>",


amount:"<?=$order['amount']?>",


provider:"<?=htmlspecialchars($order['provider'])?>"


};



for(let key in data){


let input=document.createElement("input");


input.type="hidden";


input.name=key;


input.value=data[key];


form.appendChild(input);


}



document.body.appendChild(form);


form.submit();


}


</script>



</body>

</html>