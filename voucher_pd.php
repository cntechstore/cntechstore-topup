<?php

error_reporting(E_ALL);
ini_set('display_errors',1);



require "../config.php";
require "../database.php";




if(session_status() === PHP_SESSION_NONE){

    session_start();

}


/*
=================================
GET VOUCHER CATEGORY ID
=================================
*/

$category_id = (int)($_GET['id'] ?? 0);



if($category_id <= 0){

    die("Voucher category not found");

}



/*
=================================
GET VOUCHER CATEGORY
=================================
*/

$stmt = $conn->prepare("
    SELECT *
    FROM voucher_categories
    WHERE id=?
    LIMIT 1
");


$stmt->bind_param(
    "i",
    $category_id
);


$stmt->execute();


$voucher = $stmt
->get_result()
->fetch_assoc();



if(!$voucher){

    die("Voucher category not found");

}




/*
=================================
VIEW COUNT
=================================
*/

$ip = $_SERVER['REMOTE_ADDR'] ?? '';

$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';



$view = $conn->prepare("
INSERT INTO voucher_views
(
category_id,
ip_address,
user_agent
)
VALUES
(?,?,?)
");



if($view){

    $view->bind_param(
        "iss",
        $category_id,
        $ip,
        $user_agent
    );


    $view->execute();

    $view->close();

}




/*
=================================
GET PRODUCTS
=================================
*/


$productStmt = $conn->prepare("

SELECT *

FROM voucher_cards

WHERE category_id=?

AND status='active'

ORDER BY price ASC

");



$productStmt->bind_param(
    "i",
    $category_id
);



$productStmt->execute();



$products =
$productStmt
->get_result();






/*
=================================
CART COUNT
=================================
*/


$cart_count = 0;



if(
isset($_SESSION['cart'])
&&
is_array($_SESSION['cart'])
){

    $cart_count =
    count($_SESSION['cart']);

}




?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">
<meta name="theme-color" content="#ff0000">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">



<title>

<?=htmlspecialchars($voucher['name'])?>

- CN Tech Store

</title>



<meta name="description"
content="CN Tech Store Voucher Digital Product">





<!-- FONT AWESOME -->

<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



<script>

(function(){

const theme =
localStorage.getItem("theme") || "dark";


document.documentElement.classList.toggle(
"dark",
theme==="dark"
);


})();


    </script>
    
    <body>


<!-- =========================
MOBILE APP HEADER
========================= -->


<header class="app-header">


<div class="logo">

CNTECH

<span>
STORE
</span>


</div>



<div class="header-icon">

<i class="fa-solid fa-ticket"></i>

</div>


</header>





<div class="voucher-container">





<!-- =========================
VOUCHER HEADER
========================= -->


<section class="voucher-header">



<?php


$voucherImage =
!empty($voucher['image'])

?

"/admin/uploads/".$voucher['image']

:

"/assets/no-image.png";


?>



<img

src="<?=$voucherImage?>"

class="voucher-cover"

alt="<?=htmlspecialchars($voucher['name'])?>"

>




<div class="voucher-info">



<h1>

<i class="fa-solid fa-gem"></i>

<?=htmlspecialchars($voucher['name'])?>

</h1>



<p>

Digital Voucher • Game Card • Top Up

</p>



<div class="voucher-status">

<i class="fa-solid fa-circle-check"></i>

พร้อมให้บริการ

</div>



</div>



</section>









<!-- =========================
DESCRIPTION
========================= -->


<section class="game-section">



<h2>

<i class="fa-solid fa-circle-info"></i>

รายละเอียด

</h2>




<div class="glass-box">



<p>

<?=nl2br(
htmlspecialchars(
$voucher['description'] ?? 
"เลือก Voucher และดำเนินการชำระเงิน"
)
)?>

</p>



</div>



</section>









<!-- =========================
SELECT PRODUCT TITLE
========================= -->


<section class="game-section">


<h2>

<i class="fa-solid fa-gem"></i>

Select Package

</h2>





<div class="topup-grid">
    
    <?php


if($products && $products->num_rows > 0){



while($row = $products->fetch_assoc()){



$productImage =

!empty($row['image'])

?

"/admin/uploads/".$row['image']

:
"/assets/no-image.png";




$productName =

htmlspecialchars(
$row['name'] ?? 
$row['title'] ?? 
"Voucher"
);




$price =

(float)$row['price'];




$discount =

(int)($row['discount'] ?? 0);




$finalPrice = $price;




if($discount > 0){


$discountAmount =

$price * ($discount / 100);



$finalPrice =

$price - $discountAmount;



}else{


$discountAmount = 0;


}



$promotion =

htmlspecialchars(
$row['promo_text'] ?? ""
);



?>



<!-- PRODUCT CARD -->


<div class="topup-card"


onclick="

selectProduct(

this,

<?=$row['id']?>,

'<?=htmlspecialchars(
$productName,
ENT_QUOTES
)?>',

<?=$finalPrice?>

)

">






<!-- IMAGE -->


<div class="topup-image">


<img

src="<?=$productImage?>"

alt="<?=$productName?>"

loading="lazy">


</div>








<!-- INFO -->


<div class="topup-info">





<h3>


<?=$productName?>


</h3>





<?php if($promotion != ""){ ?>


<div class="promo-box">


<i class="fa-solid fa-bolt"></i>


<?=$promotion?>


</div>



<?php } ?>







<?php if($discount > 0){ ?>




<div class="old-price">


<?=number_format($price)?> ₭


</div>





<div class="discount-price">


<?=number_format($finalPrice)?> ₭



<span class="discount-badge">


-<?=$discount?>%


</span>


</div>





<div class="save-price">


<i class="fa-solid fa-tags"></i>


ประหยัด


<?=number_format($discountAmount)?> ₭



</div>





<?php }else{ ?>





<div class="discount-price">


<?=number_format($price)?> ₭


</div>





<?php } ?>






<button type="button" class="topup-cord">


<i class="fa-solid fa-cart-plus"></i>


เลือกแพ็กเกจ


</button>





</div>



</div>





<?php


}



}else{


?>


<div class="empty-box">


<i class="fa-solid fa-box-open"></i>


ยังไม่มีสินค้า


</div>


<?php


}


?>



</div>



    </section>
    
    
<!-- =========================
CONTACT EMAIL
========================= -->

<section class="game-section">


<h2>

<i class="fa-solid fa-envelope"></i>

Contact Email

</h2>




<div class="input-box">


<i class="fa-solid fa-at"></i>


<input

type="email"

id="email"

placeholder="example@gmail.com">


</div>



</section>









<!-- =========================
ORDER SUMMARY
========================= -->


<section class="game-section">


<h2>

<i class="fa-solid fa-file-invoice"></i>

Order Summary


</h2>





<div class="summary-card">





<div class="summary-row">


<span>

Voucher

</span>


<span>

<?=htmlspecialchars($voucher['name'])?>

</span>


</div>







<div class="summary-row">


<span>

แพ็กเกจ

</span>


<span id="selected">


ยังไม่ได้เลือก


</span>


</div>









<div class="summary-row">


<span>

Email

</span>


<span id="summaryEmail">


-

</span>


</div>







<hr>





<div class="summary-total">


<span>

ยอดชำระ


</span>



<b id="total">


0 ₭


</b>



</div>





</div>






<div

id="formError"

class="form-error">

</div>







<button

id="payBtn"

class="pay-btn"


onclick="goPayment()">


<i class="fa-solid fa-credit-card"></i>


ดำเนินการต่อ


</button>





</section>






        </div>
        
        <style>

*{
box-sizing:border-box;
}


body{

margin:0;

background:
linear-gradient(
180deg,
#050505,
#160707,
#050505
);

color:#fff;

font-family:
Arial,
sans-serif;

padding-bottom:40px;

}


/* HEADER */

.app-header{

height:70px;

display:flex;

align-items:center;

justify-content:space-between;

padding:0 18px;

background:
linear-gradient(
135deg,
#000,
#8b0000
);

border-bottom:
2px solid #ef4444;

position:sticky;

top:0;

z-index:100;

}



.logo{

font-size:24px;

font-weight:900;

}



.logo span{

color:#ef4444;

}



.header-icon{

width:45px;

height:45px;

border-radius:50%;

background:#ef4444;

display:flex;

align-items:center;

justify-content:center;

font-size:22px;

}




/* CONTAINER */

.voucher-container{

max-width:1200px;

margin:auto;

padding:20px;

}





/* HEADER CARD */

.voucher-header{

display:flex;

gap:20px;

align-items:center;

padding:20px;

border-radius:24px;

background:

rgba(255,255,255,.06);

backdrop-filter:blur(15px);

border:

1px solid rgba(255,255,255,.12);

margin-bottom:20px;

}



.voucher-cover{

width:120px;

height:120px;

border-radius:22px;

object-fit:cover;

}



.voucher-info h1{

margin:0;

font-size:28px;

}



.voucher-info p{

color:#ccc;

}



.voucher-status{

color:#4ade80;

font-weight:bold;

}




/* SECTION */


.game-section{

background:

rgba(255,255,255,.05);

backdrop-filter:blur(15px);

border:

1px solid rgba(255,255,255,.12);

border-radius:24px;

padding:20px;

margin-bottom:20px;

}


.game-section h2{

font-size:20px;

margin-top:0;

}


.game-section h2 i{

color:#ef4444;

}




/* GRID */


.topup-grid{

display:grid;

grid-template-columns:

repeat(4,1fr);

gap:18px;

}




/* PRODUCT CARD */


.topup-card{

background:

rgba(255,255,255,.06);

border:

1px solid rgba(255,255,255,.1);

border-radius:20px;

overflow:hidden;

cursor:pointer;

transition:.25s;

}



.topup-card:hover{

transform:translateY(-5px);

border-color:#ef4444;

box-shadow:

0 10px 30px rgba(239,68,68,.3);

}



.topup-card.selected{

border:

2px solid #ef4444;

box-shadow:

0 0 25px rgba(239,68,68,.5);

}





.topup-image{

width:100%;

aspect-ratio:1/1;

overflow:hidden;

}



.topup-image img{

width:100%;

height:100%;

object-fit:cover;

}




.topup-info{

padding:15px;

}



.topup-info h3{

font-size:15px;

min-height:40px;

}




.old-price{

color:#999;

text-decoration:line-through;

font-size:13px;

}




.discount-price{

font-size:22px;

font-weight:900;

color:#ffd54f;

}




.discount-badge{

background:#ef4444;

padding:4px 10px;

border-radius:20px;

font-size:12px;

color:white;

}



.save-price{

color:#4ade80;

font-size:13px;

margin-top:8px;

}



.promo-box{

background:

rgba(239,68,68,.2);

border-radius:10px;

padding:8px;

font-size:13px;

margin-bottom:8px;

color:#ffdddd;

}





/* INPUT */


.input-box{

position:relative;

}



.input-box i{

position:absolute;

left:15px;

top:18px;

color:#ef4444;

}



.input-box input{


width:100%;

height:55px;

padding-left:45px;

border-radius:15px;

border:

1px solid rgba(255,255,255,.2);

background:

rgba(255,255,255,.08);

color:white;

font-size:16px;

}





/* SUMMARY */


.summary-card{

background:

rgba(0,0,0,.35);

border-radius:18px;

padding:15px;

}



.summary-row{

display:flex;

justify-content:space-between;

padding:12px 0;

border-bottom:

1px solid rgba(255,255,255,.1);

}



.summary-total{

display:flex;

justify-content:space-between;

font-size:22px;

color:#ffd54f;

padding-top:15px;

}



.form-error{

color:#ff5555;

margin-top:15px;

}





/* BUTTON */


.pay-btn{

width:100%;

height:58px;

margin-top:20px;

border:none;

border-radius:18px;

background:

linear-gradient(
135deg,
#ef4444,
#991b1b
);

color:white;

font-size:18px;

font-weight:bold;

cursor:pointer;

}



.pay-btn:hover{

transform:scale(1.02);

}




.empty-box{

padding:30px;

text-align:center;

color:#aaa;

}


/* =========================
   SELECT PACKAGE BUTTON
========================= */

.topup-card button{

    width:100%;

    margin-top:15px;

    padding:12px 15px;

    border:none;

    border-radius:14px;


    background:
    linear-gradient(
        135deg,
        #ff0000,
        #990000
    );


    color:#fff;

    font-size:15px;

    font-weight:800;


    display:flex;

    align-items:center;

    justify-content:center;

    gap:8px;


    cursor:pointer;


    box-shadow:

    0 8px 20px
    rgba(255,0,0,.35);


    transition:.25s ease;


}


/* Hover */

.topup-card button:hover{

    transform:

    translateY(-3px);


    background:

    linear-gradient(
        135deg,
        #ff3333,
        #cc0000
    );


    box-shadow:

    0 12px 30px
    rgba(255,0,0,.55);

}



/* Click */

.topup-card button:active{

    transform:scale(.96);

}



/* Selected Card */

.topup-card.selected{


    border:

    2px solid #ff0000;


    background:

    linear-gradient(
        135deg,
        rgba(255,0,0,.25),
        rgba(255,255,255,.08)
    );


    box-shadow:

    0 0 30px
    rgba(255,0,0,.45);


}



/* ICON */

.topup-card button i{

    font-size:18px;

    color:#fff;

}



/* Mobile */

@media(max-width:768px){

.topup-card button{

    font-size:13px;

    padding:10px;

    border-radius:12px;

}

            }

/* MOBILE */


@media(max-width:768px){


.voucher-container{

padding:15px;

}


.voucher-header{

flex-direction:column;

text-align:center;

}


.topup-grid{

grid-template-columns:

repeat(2,1fr);

gap:12px;

}


.voucher-cover{

width:100px;

height:100px;

}


}


        </style>
        
        <script>


const CATEGORY_ID =
<?= $category_id ?>;


let PRODUCT_ID = 0;

let PRODUCT_PRICE = 0;



function selectProduct(
element,
id,
name,
price
){


PRODUCT_ID=id;

PRODUCT_PRICE=Number(price);



document
.querySelectorAll(".topup-card")
.forEach(card=>{

card.classList.remove("selected");

});



element.classList.add("selected");



document
.getElementById("selected")
.innerHTML=name;



document
.getElementById("total")
.innerHTML=

PRODUCT_PRICE
.toLocaleString()
+" ₭";


}




document
.getElementById("email")
.addEventListener(
"input",
function(){


document
.getElementById("summaryEmail")
.innerHTML =
this.value || "-";


});






function goPayment(){



let error =
document.getElementById("formError");



error.innerHTML="";



let email =
document
.getElementById("email")
.value
.trim();





if(PRODUCT_ID===0){


error.innerHTML=
"กรุณาเลือกสินค้า";


return;

}




if(email===""){


error.innerHTML=
"กรุณากรอก Email";


return;

}





let btn =
document.getElementById("payBtn");


btn.disabled=true;


btn.innerHTML=
"กำลังสร้างรายการ...";






fetch("../api/set_voucher_checkout.php",{


method:"POST",


headers:{


"Content-Type":
"application/json"


},


body:JSON.stringify({


category_id:CATEGORY_ID,


product_id:PRODUCT_ID,


price:PRODUCT_PRICE,


email:email


})


})



.then(r=>r.json())



.then(data=>{


if(data.success){


location.href=data.payment_url;


}else{


error.innerHTML=data.message;


}


})



.catch(()=>{


error.innerHTML=
"ระบบผิดพลาด";


})



.finally(()=>{


btn.disabled=false;


btn.innerHTML=
"ดำเนินการต่อ";


});



}


        </script>
        
    </body>
    </html>