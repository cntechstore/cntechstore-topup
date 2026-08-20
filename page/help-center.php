<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "../config.php";
require_once "../feature.php";

$cartCount = 0;

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cartCount += $item['qty'];
    }
}

?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
Help Center | CN Tech Store
</title>


<link 
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stlesheet">


<link 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">


<link rel="stylesheet" href="../style.css?v=1.0.0">
    <link rel="stylesdeet" href="../page.css?v=1.0.0">

    <link rel="stylesheet" href="../faq.css?v=1.0.0">
    <script src="../app.js?v=1.0"></script>
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
    
    <script>

document.querySelectorAll(".faq-toggle")
.forEach(btn=>{


btn.addEventListener("click",()=>{


let item =
btn.parentElement.parentElement;



document.querySelectorAll(".faq-item")
.forEach(faq=>{


if(faq !== item){

faq.classList.remove("active");

}


});



item.classList.toggle("active");



});


});


    </script>
<style>
.logo-image{

    width:120px;
    height:68px;

    }

.help-wrapper .row{

    display: grid;
    
    max-width:1200px;

    margin:40px auto;

    padding:0px 5px;

    gap: 10px;
}

.help-wrapper > section{

    margin-bottom:40px;

    }
    
/* HERO */

.help-hero{

    background:

    linear-gradient(
    135deg,
    #2563eb,
    #0f172a
    );

    color:white;

    border-radius:25px;

    padding:50px 30px;

    text-align:center;

    box-shadow:
    0 15px 40px rgba(0,0,0,.15);

}


.help-hero h1{

    font-size:38px;

    font-weight:700;

}


.help-hero p{

    opacity:.9;

    font-size:18px;

}


/* SEARCH */

.help-search{

    margin-top:-35px;

    position:relative;

}


.search-card{

    background:white;

    padding:20px;

    border-radius:18px;

    box-shadow:
    0 10px 30px rgba(0,0,0,.12);

}


.search-box{

    display:flex;

    gap:10px;

}


.search-box input{

    flex:1;

    border-radius:12px;

    padding:15px;

    border:1px solid #ddd;

    font-size:16px;

}


.search-box button{

    border:none;

    padding:0 25px;

    border-radius:12px;

    background:#2563eb;

    color:white;

    font-weight:bold;

}


.search-box button:hover{

    background:#1d4ed8;

}



/* breadcrumb */

.breadcrumb-box{

    margin-top:30px;

    background:#f8fafc;

    padding:15px 20px;

    border-radius:12px;

    gap: 10px;
}


.breadcrumb-box a{

    text-decoration:none;

    color:#2563eb;

}

.faq-toggle{

width:100%;

padding:20px;

border:none;

background:#fff;

display:flex;

align-items:center;

gap:12px;

font-size:18px;

font-weight:700;

cursor:pointer;

}


.faq-toggle i{

color:#2563eb;

}



.faq-arrow{

margin-left:auto;

transition:.3s;

}



.faq-content{


max-height:0;

overflow:hidden;

padding:0 25px;

background:#fafafa;

transition:max-height .4s ease;


}



.faq-item.active 
.faq-content{


max-height:500px;

padding:25px;


}



.faq-item.active 
.faq-arrow{


transform:rotate(180deg);


}



.faq-item{


background:white;

border-radius:18px;

margin-bottom:15px;

overflow:hidden;

border:1px solid #ddd;

    }

    .help-wrapper .col-md-3,
.help-wrapper .col-md-4{

    width:100%;

    }
    
</style>


</head>


<body>


<?php

include "../navbar.php";

?>


<div class="help-wrapper">


<section class="help-hero">


<h1>

<i class="fa-solid fa-circle-question"></i>

Help Center

</h1>


<p>

ศูนย์ช่วยเหลือ CN Tech Store

<br>

ค้นหาคำตอบเกี่ยวกับ

การสั่งซื้อ

การเติมเกม

Voucher

การชำระเงิน

และบริการดิจิทัล

</p>


</section>




<section class="help-search">


<div class="search-card">


<div class="search-box">


<input

type="text"

id="faqSearch"

placeholder="ค้นหาคำถาม เช่น เติมเกม, Voucher, Payment..."

>


<button>

<i class="fa-solid fa-magnifying-glass"></i>

Search

</button>


</div>


</div>


</section>





<div class="breadcrumb-box">


<a href="../index.php">

<i class="fa-solid fa-house"></i>

Home

</a>


<span>

/

</span>


<b>

Help Center

</b>


    </div>
    
    <!-- ==========================
 QUICK HELP CARDS
=========================== -->


<section class="mt-5">


<div class="row">


<div class="col-md">


<div class="help-card">


<div class="icon">

<i class="fa-solid fa-gamepad"></i>

</div>


<h5>
Game Top-up
</h5>


<p>
เติมเกมออนไลน์ยอดนิยม
Mobile Legends,
Free Fire,
PUBG,
ROV,
Honor of Kings
</p>


<a href="game-topup-guide.php">

ดูวิธีเติมเกม

<i class="fa-solid fa-arrow-right"></i>

</a>


</div>


</div>





<div class="col-md">


<div class="help-card">


<div class="icon">

<i class="fa-solid fa-mobile-screen"></i>

</div>


<h5>
Mobile Top-up
</h5>


<p>
เติมเงินมือถือ

Unitel

Lao Telecom

ETL

และบริการอื่น
</p>


<a href="mobile-topup-guide.php">

คู่มือเติมเงิน

<i class="fa-solid fa-arrow-right"></i>

</a>


</div>


</div>






<div class="col-md">


<div class="help-card">


<div class="icon">

<i class="fa-solid fa-gift"></i>

</div>


<h5>
Gift Voucher
</h5>


<p>
Garena Shell

Razer Gold

Steam Wallet

PlayStation Card
</p>


<a href="voucher-guide.php">

วิธีใช้ Voucher

<i class="fa-solid fa-arrow-right"></i>

</a>


</div>


</div>






<div class="col-md">


<div class="help-card">


<div class="icon">

<i class="fa-solid fa-credit-card"></i>

</div>


<h5>
Payment
</h5>


<p>
BCEL

LDB

QR Payment

Bank Transfer

</p>


<a href="payment-guide.php">

วิธีชำระเงิน

<i class="fa-solid fa-arrow-right"></i>

</a>


</div>


</div>


</div>


</section>







<!-- ==========================
 GUIDE MENU
=========================== -->


<section class="mt-5">


<div class="section-title">


<h2>

<i class="fa-solid fa-book"></i>

คู่มือการใช้งาน

</h2>


<p>

เรียนรู้ขั้นตอนการใช้งาน CN Tech Store

ตั้งแต่เลือกสินค้า

จนถึงตรวจสอบคำสั่งซื้อ

</p>


</div>




<div class="row">



<div class="col-md">


<div class="guide-card">


<i class="fa-solid fa-cart-shopping"></i>


<h5>
การสั่งซื้อสินค้า
</h5>


<p>

เลือกสินค้า

เพิ่มลงตะกร้า

ชำระเงิน

ตรวจสอบ Order

</p>


<a href="shopping-guide.php">

อ่านเพิ่มเติม

</a>


</div>


</div>






<div class="col-md">


<div class="guide-card">


<i class="fa-solid fa-gamepad"></i>


<h5>
วิธีเติมเกม
</h5>


<p>

วิธีกรอก UID

Server

และตรวจสอบสถานะ

</p>


<a href="game-topup-guide.php">

อ่านเพิ่มเติม

</a>


</div>


</div>







<div class="col-md">


<div class="guide-card">


<i class="fa-solid fa-receipt"></i>


<h5>
ตรวจสอบคำสั่งซื้อ
</h5>


<p>

ค้นหา Order ID

ตรวจสอบสถานะ

และประวัติรายการ

</p>


<a href="order-history.php">

ดูประวัติ

</a>


</div>


</div>



</div>


</section>









<!-- ==========================
 HELP CATEGORY TABS
=========================== -->


<section class="mt-5">


<div class="category-box">


<h3>

<i class="fa-solid fa-layer-group"></i>

หมวดหมู่ช่วยเหลือ

</h3>



<div class="category-tabs">


<button class="active">

All

</button>


<button>

General

</button>


<button>

Orders

</button>


<button>

Game Top-up

</button>


<button>

Voucher

</button>


<button>

Payment

</button>


<button>

Account

</button>


<button>

Support

</button>


</div>



</div>


</section>





<style>


.help-card{


background:white;

border-radius:20px;

padding:25px;

height:100%;

box-shadow:

0 8px 25px rgba(0,0,0,.08);

transition:.3s;

gap: 10px;
}



.help-card:hover{


transform:translateY(-8px);


}



.help-card .icon{


width:60px;

height:60px;

display:flex;

align-items:center;

justify-content:center;

background:#2563eb;

color:white;

border-radius:50%;

font-size:25px;

margin-bottom:15px;


}



.help-card h5{


font-weight:700;


}



.help-card p{


color:#64748b;

min-height:80px;


}



.help-card a{


text-decoration:none;

color:#2563eb;

font-weight:bold;


}







.section-title{


margin-bottom:25px;


}



.section-title h2{


font-weight:700;


}





.guide-card{


background:#fff;

padding:25px;

border-radius:18px;

text-align:center;

box-shadow:

0 5px 20px rgba(0,0,0,.08);


}



.guide-card i{


font-size:35px;

color:#2563eb;

margin-bottom:15px;


}



.guide-card a{


color:#2563eb;

text-decoration:none;

font-weight:bold;


}





.category-box{


background:#fff;

padding:25px;

border-radius:20px;

box-shadow:

0 5px 20px rgba(0,0,0,.08);


}



.category-tabs{


display:flex;

gap:10px;

flex-wrap:wrap;

margin-top:20px;


}



.category-tabs button{


border:none;

background:#f1f5f9;

padding:10px 18px;

border-radius:30px;

cursor:pointer;


}



.category-tabs .active{


background:#2563eb;

color:white;

    }
    
.cn-faq-section{
    width:100%;
    padding:40px 15px;
}


.cn-faq-container{

    max-width:1100px;
    margin:auto;

}



.cn-faq-title{

    font-size:28px;
    font-weight:700;
    margin-bottom:30px;

}



.cn-faq-accordion{

    width:100%;

}



.cn-faq-item{

    border:none;
    margin-bottom:15px;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,.08);

}



.cn-faq-item .accordion-button{

    padding:18px 22px;
    font-size:18px;
    font-weight:600;
    background:#fff;

}



.cn-faq-item .accordion-button i{

    margin-right:12px;
    color:#2563eb;

}



.cn-faq-item .accordion-button:not(.collapsed){

    background:#2563eb;
    color:white;

}



.cn-faq-item .accordion-button:not(.collapsed) i{

    color:white;

}



.cn-faq-item .accordion-body{

    background:#fff;
    padding:25px;

}



.faq-box h5{

    margin-top:20px;
    font-size:18px;
    color:#2563eb;

}



.faq-box p{

    line-height:1.8;
    color:#555;

}



@media(max-width:768px){

.cn-faq-title{

font-size:22px;

}


.cn-faq-item .accordion-button{

font-size:15px;

}


.cn-faq-item .accordion-body{

padding:18px;

}

    }
}



    </style>
    
    <!-- ==========================
 FAQ ACCORDION
=========================== -->


<!-- =========================
FAQ LIST START
========================= -->

<div id="faqWrapper">



<!-- =========================
GENERAL
========================= -->

<div class="faq-group"
data-category="general">

<h2 class="faq-title">

<i class="fa-solid fa-circle-info"></i>

General

</h2>



<div class="accordion">

<div class="accordion-item faq-item">

<button class="accordion-header">

CN Tech Store คืออะไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

CN Tech Store เป็นเว็บไซต์จำหน่ายสินค้าไอที
บริการเติมเกมออนไลน์
Gift Voucher
และบริการดิจิทัล
พัฒนาโดยทีมงาน CN Tech Store

</div>

</div>



<div class="accordion-item faq-item">

<button class="accordion-header">

เว็บไซต์เปิดให้บริการเมื่อใด

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

เว็บไซต์เริ่มพัฒนาเมื่อวันที่
7 กรกฎาคม 2569

และเปิดให้ทดลองใช้งานในเวอร์ชัน

CN Tech Store

v1.5.5 BETA

พร้อม CNTCH UI 2.5

</div>

</div>



<div class="accordion-item faq-item">

<button class="accordion-header">

เว็บไซต์ปลอดภัยหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

เว็บไซต์ใช้ HTTPS

มีระบบตรวจสอบคำสั่งซื้อ

และมีการเข้ารหัสข้อมูลการชำระเงิน

เพื่อความปลอดภัยของลูกค้า

</div>

    </div>

    </div>








<!-- ==========================
 CONTACT HELP CARD
=========================== -->


<section class="contact-help mt-5">


<div class="contact-box">


<div>


<h3>

<i class="fa-solid fa-headset"></i>

ยังต้องการความช่วยเหลือ?

</h3>


<p>

ทีมงาน CN Tech Store

พร้อมช่วยเหลือเกี่ยวกับ

การสั่งซื้อ เติมเกม Voucher

และระบบชำระเงิน

</p>


</div>




<div class="contact-buttons">

  <div class="footer-links">
    
<a href="<?=BASE_URL?>page/contact-method.php"

class="btn-primary">

Contact Us

</a>



<a href="<?=BASE_URL?>page/order-history.php"

class="btn-secondary">

Order History

</a>



<a href="<?=BASE_URL?>index.php"

class="btn-dark">

Home

</a>


</div>


</div>


</section>









<!-- ==========================
 FOOTER
=========================== -->


<footer class="help-footer">


<div class="footer-link">



<div>

<h4>

CN Tech Store

</h4>


<p>

Digital Payment & Game Top-up Platform

</p>


<p>

Secure • Fast • Reliable

</p>


</div>





<div>

<h5>

Quick Links

</h5>

<div class="footer-links">
    
<a href="../index.php">

Home

</a>


<a href="products.php">

Products

</a>


<a href="payment-method.php">

Payment Method

</a>


<a href="privacy-policy.php">

Privacy Policy

</a>


<a href="terms-of-service.php">

Terms Service

</a>


</div>







<div>

<h5>

Support

</h5>

<div class="footer-links">
    
<a href="contact-method.php">

Contact Us

</a>


<a href="help-center.php">

Help Center

</a>


<a href="refund-policy.php">

Refund Policy

</a>


</div>





<div>

<h5>

Company Info

</h5>


<p>

Email:

support@cntechstore.shop

</p>


<p>

24/7 Online Support

</p>


</div>




</div>





<div class="copyright">


© 2026 CN Tech Store.

All Rights Reserved.


</div>



</footer>









<!-- ==========================
 FAQ SEARCH SCRIPT
=========================== -->

<!-- =========================
JAVASCRIPT
========================= -->


<script>


// Accordion

const accordionButtons =
document.querySelectorAll(".accordion-header");


accordionButtons.forEach(btn=>{


btn.addEventListener("click",()=>{


btn.classList.toggle("active");


const body =
btn.nextElementSibling;



if(body.style.maxHeight){


body.style.maxHeight=null;


}else{


body.style.maxHeight =
body.scrollHeight+"px";


}


});


});





// FAQ FILTER


function filterFAQ(category){


let groups =
document.querySelectorAll(".faq-group");


let buttons =
document.querySelectorAll(".tab-btn");



buttons.forEach(btn=>{

btn.classList.remove("active");

});



event.target.classList.add("active");



groups.forEach(group=>{


if(category==="all"){


group.style.display="block";


}else{


if(group.dataset.category===category){


group.style.display="block";


}else{


group.style.display="none";


}


}



});


}



    </script>
   
    
<script>


const searchBox = document.getElementById(
"faqSearch"
);


if(searchBox){


searchBox.addEventListener(
"keyup",
function(){


let value =
this.value.toLowerCase();



let items =
document.querySelectorAll(
".faq-item"
);



items.forEach(function(item){


let text =
item.innerText.toLowerCase();



if(text.includes(value)){


item.style.display="block";


}else{


item.style.display="none";


}



});


}


);


}



</script>








<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



</body>

    </html>