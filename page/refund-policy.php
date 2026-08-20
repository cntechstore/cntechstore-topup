<?php

session_start();

require_once "../config.php";
require_once "../database.php";

?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">

<title>
Refund Policy | CN Tech Store
</title>


<link rel="stylesheet" href="../style.css?v=1.0.0">

<link rel="stylesheet" href="../page.css?v=1.0.0">

<link rel="stylesheet" href="../guide.css?v=1.0.0">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    
    /* ==========================================
CN Tech Store Guide Pages
========================================== */

body{
    background:#f5f7fb;
    font-family:Arial,sans-serif;
}

       .logo-image{

    width:120px;
    height:68px;

        } 
        
.container{
    max-width:1200px;
    margin:auto;
}

.hero-card{
    background:linear-gradient(135deg,#2563eb,#1e3a8a);
    color:#fff;
    padding:60px 30px;
    border-radius:25px;
    text-align:center;
    margin-bottom:40px;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
}

.hero-card h1{
    font-size:42px;
    margin-bottom:15px;
    font-weight:700;
}

.hero-card p{
    font-size:18px;
    opacity:.95;
    line-height:1.8;
}

.info-card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    text-align:center;
    height:100%;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
    transition:.3s;
}

.info-card:hover{
    transform:translateY(-8px);
}

.info-card i{
    width:70px;
    height:70px;
    line-height:70px;
    border-radius:50%;
    background:#2563eb;
    color:#fff;
    font-size:28px;
    margin-bottom:15px;
}

.info-card h4{
    margin-bottom:10px;
}

.info-card p{
    color:#64748b;
}

.step-box{
    display:flex;
    gap:20px;
    background:#fff;
    border-radius:18px;
    padding:25px;
    margin-bottom:20px;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}

.step-number{
    width:60px;
    height:60px;
    border-radius:50%;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:bold;
    flex-shrink:0;
}

.step-content{
    flex:1;
}

.step-content h3{
    color:#2563eb;
    margin-bottom:10px;
}

.step-content p{
    line-height:1.8;
    color:#555;
}

.notice-box{
    background:#fff8e1;
    border-left:6px solid #f59e0b;
    padding:20px;
    border-radius:12px;
    margin:30px 0;
}

.notice-box h4{
    color:#b45309;
}

.notice-box p{
    color:#92400e;
    margin:10px 0 0;
}

.faq-card{
    background:#fff;
    border-radius:18px;
    margin-bottom:18px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}

.faq-question{
    background:#2563eb;
    color:#fff;
    padding:18px 20px;
    font-weight:bold;
}

.faq-answer{
    padding:20px;
    color:#555;
    line-height:1.8;
}

.contact-box{
    background:#0f172a;
    color:#fff;
    padding:45px;
    border-radius:20px;
    text-align:center;
    margin-top:50px;
}

.btn-guide{
    display:inline-block;
    margin-top:20px;
    padding:14px 28px;
    border-radius:10px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    font-weight:bold;
    transition:.3s;
}

.btn-guide:hover{
    background:#1d4ed8;
}

@media(max-width:768px){

.hero-card h1{
    font-size:30px;
}

.hero-card{
    padding:40px 20px;
}

.step-box{
    flex-direction:column;
    text-align:center;
}

.step-number{
    margin:auto;
}

        }
    
    </style>
    
    <script src="../app.js?v=1.0"></script>
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
    
</head>


<body>


<?php include "../navbar.php"; ?>



<div class="container py-5">


<nav class="mb-4">

<a href="<?=BASE_URL?>index.php">

Home

</a>

<i class="fa-solid fa-angle-right"></i>


<a href="<?=BASE_URL?>page/help-center.php">

Help Center

</a>

<i class="fa-solid fa-angle-right"></i>


<b>

Refund Policy

</b>

</nav>





<section class="hero-card">


<h1>

<i class="fa-solid fa-rotate-left"></i>

Refund Policy

</h1>


<p>

นโยบายการคืนเงิน

และการแก้ไขปัญหาคำสั่งซื้อ

ของ CN Tech Store

</p>


</section>





<div class="row g-4">


<div class="col-md-4">


<div class="info-card">

<i class="fa-solid fa-shield-halved"></i>


<h4>

ตรวจสอบรายการ

</h4>


<p>

ทีมงานตรวจสอบทุกคำร้อง

ก่อนดำเนินการ

</p>


</div>


</div>




<div class="col-md-4">


<div class="info-card">


<i class="fa-solid fa-clock"></i>


<h4>

ระยะเวลา

</h4>


<p>

ตรวจสอบภายใน 1-3 วันทำการ

</p>


</div>


</div>




<div class="col-md-4">


<div class="info-card">


<i class="fa-solid fa-headset"></i>


<h4>

Support

</h4>


<p>

ติดต่อทีมงานพร้อม Order ID

</p>


</div>


</div>


</div>






<hr class="my-5">





<h2>

<i class="fa-solid fa-circle-check"></i>

กรณีสามารถขอคืนเงินได้

</h2>



<div class="step-box">


<div class="step-number">

1

</div>


<div class="step-content">


<h3>

ระบบชำระเงินผิดพลาด

</h3>


<p>

ลูกค้าชำระเงินแล้ว

แต่ระบบไม่สามารถดำเนินการ

หรือไม่ได้รับสินค้า

</p>


</div>


</div>





<div class="step-box">


<div class="step-number">

2

</div>


<div class="step-content">


<h3>

คำสั่งซื้อไม่สำเร็จ

</h3>


<p>

ระบบเกิดข้อผิดพลาด

และไม่สามารถส่งมอบบริการได้

</p>


</div>


</div>






<div class="step-box">


<div class="step-number">

3

</div>


<div class="step-content">


<h3>

ชำระเงินซ้ำ

</h3>


<p>

เกิดการชำระเงินมากกว่า 1 ครั้ง

สำหรับ Order เดียวกัน

</p>


</div>


</div>






<div class="notice-box">


<h4>

<i class="fa-solid fa-triangle-exclamation"></i>

กรณีไม่สามารถคืนเงินได้

</h4>


<p>


- เติมเกมสำเร็จแล้ว

<br>

- Voucher ถูกส่ง PIN Code แล้ว

<br>

- ลูกค้ากรอก UID / Server ผิด

<br>

- ลูกค้าใช้งานสินค้า Digital แล้ว

<br>

- เปลี่ยนใจหลังซื้อสินค้า


</p>


</div>






<h2 class="mt-5">

ขั้นตอนการขอคืนเงิน

</h2>





<div class="step-box">


<div class="step-number">

1

</div>


<div class="step-content">


<h3>

เตรียมข้อมูล

</h3>


<p>


Order ID

<br>

ชื่อบัญชี

<br>

หลักฐานการชำระเงิน

<br>

รายละเอียดปัญหา


</p>


</div>


</div>






<div class="step-box">


<div class="step-number">

2

</div>


<div class="step-content">


<h3>

ติดต่อ Support

</h3>


<p>

ส่งข้อมูลผ่านหน้า Contact Us

ทีมงานจะตรวจสอบรายการ

</p>


</div>


</div>






<div class="step-box">


<div class="step-number">

3

</div>


<div class="step-content">


<h3>

รอผลตรวจสอบ

</h3>


<p>

หากผ่านเงื่อนไข

ระบบจะดำเนินการคืนเงิน

ตามช่องทางเดิม

</p>


</div>


</div>








<h2 class="mt-5">

คำถามที่พบบ่อย

</h2>



<div class="faq-card">


<div class="faq-question">

เติมเกมแล้วสามารถคืนเงินได้ไหม?

</div>


<div class="faq-answer">

หากเติมสำเร็จแล้ว

ไม่สามารถคืนเงินได้

เนื่องจากเป็นบริการดิจิทัลที่ส่งมอบแล้ว

</div>


</div>





<div class="faq-card">


<div class="faq-question">

ใช้เวลาคืนเงินกี่วัน?

</div>


<div class="faq-answer">

ขึ้นอยู่กับช่องทางชำระเงิน

โดยทั่วไปใช้เวลา 1-7 วันทำการ

</div>


</div>






<div class="faq-card">


<div class="faq-question">

ต้องติดต่อที่ไหน?

</div>


<div class="faq-answer">


ติดต่อทีมงานผ่านหน้า

Contact Us

พร้อมแจ้ง Order ID


</div>


</div>







<section class="contact-box">


<h2>

ต้องการความช่วยเหลือ?

</h2>


<p>

ทีมงาน CN Tech Store

พร้อมช่วยตรวจสอบคำสั่งซื้อ

</p>


<a href="contact-method.php"

class="btn-guide">

Contact Support

</a>


</section>




</div>



<?php include "../footer.php"; ?>


</body>

</html>