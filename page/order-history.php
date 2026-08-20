<?php



error_reporting(E_ALL);
ini_set('display_errors',1);

require_once "../config.php";
require_once "../feature.php";


$orders = [];
$error = "";
$searched = false;
$success = false;



if(isset($_POST['search'])){


    $searched = true;


    $order_id = trim($_POST['order_id']);



/*
====================
GAME ORDERS
====================
*/


$stmt = $conn->prepare("

SELECT

order_id,

'Game Top-up' AS type,

CONCAT('Product ID: ',product) AS product_name,

price AS amount,

status,

created_at


FROM game_orders

WHERE order_id=?

");


$stmt->bind_param("s",$order_id);

$stmt->execute();


$result=$stmt->get_result();


while($row=$result->fetch_assoc()){

$orders[]=$row;

}




/*
====================
SHOP ORDERS
====================
*/


$stmt=$conn->prepare("

SELECT

order_id,

'Product' AS type,

items AS product_name,

total AS amount,

status,

created_at


FROM shop_orders

WHERE order_id=?

");


$stmt->bind_param("s",$order_id);

$stmt->execute();


$result=$stmt->get_result();


while($row=$result->fetch_assoc()){

$orders[]=$row;

}





/*
====================
MOBILE ORDERS
====================
*/


$stmt=$conn->prepare("

SELECT

order_id,

'Mobile Top-up' AS type,

CONCAT(provider,' - ',phone) AS product_name,

amount,

status,

created_at


FROM mobile_orders

WHERE order_id=?

");


$stmt->bind_param("s",$order_id);

$stmt->execute();


$result=$stmt->get_result();


while($row=$result->fetch_assoc()){

$orders[]=$row;

}




/*
====================
VOUCHER ORDERS
====================
*/


$stmt=$conn->prepare("

SELECT

order_id,

'Voucher' AS type,

CONCAT('Voucher Product ID: ',product_id) AS product_name,

total AS amount,

status,

created_at


FROM voucher_orders

WHERE order_id=?

");


$stmt->bind_param("s",$order_id);

$stmt->execute();


$result=$stmt->get_result();


while($row=$result->fetch_assoc()){

$orders[]=$row;

}




if(empty($orders)){


$error="ไม่พบ Order ID นี้";


}else{


$success=true;


}


}

?>
    
    
    <!DOCTYPE html>  
<html lang="th">  
    <head>  
        <meta charset="UTF-8"> 
        <title>ตรวจสอบ Order | CN Tech Store</title>

<meta name="description"
content="ตรวจสอบสถานะคำสั่งซื้อ เติมเกมออนไลน์ MLBB, Free Fire, HOK, PUBG Mobile และบริการดิจิทัลจาก CN Tech Store">

<meta name="keywords"
content="cntechstore, CN Tech Store, ເຕີມເກມລາວ, topup laos, MLBB Laos, Mobile Legends Laos, Codashop Laos, ตรวจสอบออเดอร์, order tracking">

<meta name="robots" content="index,follow">

        <meta property="og:title" content="ตรวจสอบ Order | CN Tech Store">
<meta property="og:description" content="ตรวจสอบสถานะคำสั่งซื้อและบริการเติมเกมออนไลน์">
<meta property="og:url" content="https://cntechstore.shop/page/order-history.php">
<meta property="og:type" content="website">
        <link rel="stylesheet" href="../style.css?v=1.0.0">  <link rel="stylesheet" href="../page.css?v=1.0.0">  
        <link rel="canonical"

href="<?= $currentURL ?>">
        <script src="../app.js?v=1.0"></script>  
  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>  
  
.order-container{  
  
max-width:1900px;  
margin:auto;  
padding:0px;  
  
}  
  
  
.card{  
  
background:white;  
padding:25px;  
border-radius:20px;  
box-shadow:0 5px 20px #ddd;  
margin-top:20px;  
  
}  
  
  
/* =========================  
   MODERN INPUT STYLE  
========================= */  
  
.form-group{  
    position:relative;  
    margin-bottom:20px;  
}  
  
  
.form-control{  
  
    width:100%;  
  
    padding:15px 18px 15px 48px;  
  
    font-size:16px;  
  
    color:#111827;  
  
    background:#ffffff;  
  
    border:1.5px solid #e5e7eb;  
  
    border-radius:14px;  
  
    outline:none;  
  
    transition:  
    all .25s ease;  
  
    box-sizing:border-box;  
  
}  
  
  
/* Icon inside input */  
  
.form-group i{  
  
    position:absolute;  
  
    left:18px;  
  
    top:50%;  
  
    transform:translateY(-50%);  
  
    color:#9ca3af;  
  
    font-size:18px;  
  
}  
  
  
  
/* Focus */  
  
.form-control:focus{  
  
    border-color:#2563eb;  
  
    box-shadow:  
    0 0 0 4px rgba(37,99,235,.15);  
  
}  
  
  
  
/* Placeholder */  
  
.form-control::placeholder{  
  
    color:#9ca3af;  
  
}  
  
  
  
/* Hover */  
  
.form-control:hover{  
  
    border-color:#93c5fd;  
  
    }  
      
/* Mobile */  
  
@media(max-width:600px){  
  
.form-control{  
  
    font-size:15px;  
  
    padding:14px 16px 14px 45px;  
  
    }  
      
    .btn{  
  
width:100%;  
  
    }  
  }  
  
      
    /* =========================  
   MODERN BUTTON  
========================= */  
  
.btn{  
  
display:inline-flex;  
  
align-items:center;  
  
justify-content:center;  
  
gap:10px;  
  
padding:14px 28px;  
  
border-radius:14px;  
  
border:none;  
  
font-size:16px;  
  
font-weight:600;  
  
cursor: pointer;  
  
text-decoration:none;  
  
transition:.3s ease;  
  
}  
  
  
/* Primary Button */  
  
.btn-primary{  
  
background:  
linear-gradient(135deg,#2563eb,#1d4ed8);  
  
color:#fff;  
  
box-shadow:  
0 8px 20px rgba(37,99,235,.25);  
  
}  
  
  
.btn-primary:hover{  
  
transform:translateY(-3px);  
  
box-shadow:  
0 12px 25px rgba(37,99,235,.35);  
  
}  
  
  
  
/* Success */  
  
.btn-success{  
  
background:  
linear-gradient(135deg,#10b981,#059669);  
  
color:white;  
  
}  
  
  
  
/* Danger */  
  
.btn-danger{  
  
background:  
linear-gradient(135deg,#ef4444,#dc2626);  
  
color:white;  
  
}  
  
  
  
/* Dark */  
  
.btn-dark{  
  
background:#111827;  
  
color:white;  
  
}  
  
  
  
/* Outline */  
  
.btn-outline{  
  
background:white;  
  
border:2px solid #2563eb;  
  
color:#2563eb;  
  
}  
  
  
.btn-outline:hover{  
  
background:#2563eb;  
  
color:white;  
  
}  
  
  
  
/* Small */  
  
.btn-sm{  
  
padding:10px 18px;  
  
font-size:14px;  
  
}  
  
  
  
/* Large */  
  
.btn-lg{  
  
padding:18px 35px;  
  
font-size:18px;  
  
    }  
      
  
  
.status{  
  
padding:8px 15px;  
background:#dbeafe;  
border-radius:20px;  
  
}  
  
  /* ORDER RESULT */

.order-result{

animation:fade .35s ease;

}


@keyframes fade{

from{

opacity:0;

transform:translateY(20px);

}

to{

opacity:1;

transform:none;

}

}



.order-header{

display:flex;

justify-content:space-between;

align-items:center;

gap:15px;

}



.order-header h3{

margin:0;

color:#1e293b;

}



.order-info p{

padding:10px 0;

margin:0;

color:#334155;

}



.order-info i{

width:25px;

color:#2563eb;

}



.status{

display:inline-block;

font-weight:600;

color:#2563eb;

background:#dbeafe;

padding:8px 18px;

border-radius:30px;

}



@media(max-width:600px){


.order-header{

flex-direction:column;

align-items:flex-start;

}


.card{

padding:18px;

}


    }
    
</style> 
    
    </head>  
    <body>  
        <?php include "../navbar.php"; ?>  
        
        <div class="order-container">  
    
    
   <!-- =========================
     SEARCH FORM
========================= -->

<div class="card">

<h2>
<i class="fa-solid fa-magnifying-glass"></i>
ตรวจสอบ Order
</h2>


<form method="POST">


<div class="form-group">


<i class="fa-solid fa-receipt"></i>


<input

type="text"

name="order_id"

class="form-control"

placeholder="กรอก Order ID เช่น CN2026070001"

required>


</div>



<button

type="submit"

name="search"

class="btn btn-primary">


<i class="fa-solid fa-search"></i>

ค้นหา Order


</button>


</form>


    </div>
    
    <?php if($searched && $error!=""){ ?>  <script>  
  
document.addEventListener("DOMContentLoaded",function(){  
  
Swal.fire({  
  
    icon:'error',  
  
    title:'ไม่พบข้อมูล',  
  
    text:'<?=htmlspecialchars($error)?>',  
  
    confirmButtonText:'ตกลง',  
  
    confirmButtonColor:'#2563eb'  
  
});  
  
});  
  
</script>  <?php } ?>  
        
        <!-- =========================
     RESULT ORDER LIST
========================= -->

<?php foreach($orders as $row){ ?>

<div class="card order-result">


<div class="order-header">

<h3>
<i class="fa-solid fa-box"></i>

<?=htmlspecialchars($row['type'])?>

</h3>


<span class="status">

<?=htmlspecialchars($row['status'])?>

</span>


</div>



<hr>


<div class="order-info">


<p>

<i class="fa-solid fa-hashtag"></i>

<b>Order ID:</b>

<?=htmlspecialchars($row['order_id'])?>

</p>



<p>

<i class="fa-solid fa-cart-shopping"></i>

<b>สินค้า:</b>

<?=htmlspecialchars($row['product_name'])?>

</p>



<p>

<i class="fa-solid fa-money-bill"></i>

<b>จำนวนเงิน:</b>

₭ <?=number_format($row['amount'])?>

</p>



<p>

<i class="fa-solid fa-calendar"></i>

<b>วันที่สั่งซื้อ:</b>

<?=htmlspecialchars($row['created_at'])?>

</p>


</div>



</div>


<?php } ?>



        </div>
    
    <?php include "../footer.php"; ?>  
    
    <script>  
  
function toggleDropdown(el){  
  
const parent = el.parentElement;  
  
parent.classList.toggle("active");  
  
}  
  
    </script>  <?php if($success){ ?>

<script>  
  
document.addEventListener("DOMContentLoaded",function(){  
  
Swal.fire({  
  
icon:'success',  
  
title:'พบ Order แล้ว',  
  
text:'กำลังแสดงรายละเอียดคำสั่งซื้อ',  
  
timer:2000,  
  
showConfirmButton:false  
  
});  
  
});  
  
</script>  <?php } ?>  </body>  </html>  