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

<title>Voucher Guide | CN Tech Store</title>

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
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
    
    <script src="../app.js?v=1.0"></script>
    
</head>

<body>

<?php include "../navbar.php"; ?>

<div class="container py-5">

<nav class="mb-4">

<a href="<?=BASE_URL?>index.php">Home</a>

<i class="fa-solid fa-angle-right"></i>

<a href="<?=BASE_URL?>page/help-center.php">Help Center</a>

<i class="fa-solid fa-angle-right"></i>

<b>Voucher Guide</b>

</nav>

<div class="hero-card">

<h1>

<i class="fa-solid fa-gift"></i>

Voucher Guide

</h1>

<p>

คู่มือการซื้อและใช้งาน
Gift Voucher
บนเว็บไซต์
CN Tech Store

</p>

</div>

<div class="row g-4 mt-4">

<div class="col-md-4">

<div class="info-card">

<i class="fa-solid fa-bolt"></i>

<h4>ส่งทันที</h4>

<p>

รับ PIN Code
หลังชำระเงินสำเร็จ

</p>

</div>

</div>

<div class="col-md-4">

<div class="info-card">

<i class="fa-solid fa-lock"></i>

<h4>ปลอดภัย</h4>

<p>

PIN แสดงเฉพาะเจ้าของรายการ

</p>

</div>

</div>

<div class="col-md-4">

<div class="info-card">

<i class="fa-solid fa-headset"></i>

<h4>Support</h4>

<p>

ทีมงานพร้อมช่วยเหลือ

</p>

</div>

</div>

</div>

<hr class="my-5">

<h2>ขั้นตอนการซื้อ Voucher</h2>

<div class="step-box">

<div class="step-number">1</div>

<div class="step-content">

<h3>เลือก Voucher</h3>

<p>

เลือกประเภท Voucher
เช่น

Garena Shell

Razer Gold

Steam Wallet

PlayStation Store

หรือบริการอื่น

</p>

</div>

</div>

<div class="step-box">

<div class="step-number">2</div>

<div class="step-content">

<h3>เลือกมูลค่า</h3>

<p>

เลือกจำนวนเงิน
หรือแพ็กเกจ
ที่ต้องการซื้อ

</p>

</div>

</div>

<div class="step-box">

<div class="step-number">3</div>

<div class="step-content">

<h3>ชำระเงิน</h3>

<p>

ชำระผ่าน

BCEL

LDB

QR Payment

หรือช่องทางที่รองรับ

</p>

</div>

</div>

<div class="step-box">

<div class="step-number">4</div>

<div class="step-content">

<h3>รับ PIN Code</h3>

<p>

เมื่อระบบยืนยันการชำระเงิน

PIN Code

จะปรากฏในหน้า Order

และประวัติการสั่งซื้อ

</p>

</div>

</div>

<div class="step-box">

<div class="step-number">5</div>

<div class="step-content">

<h3>Redeem</h3>

<p>

นำ PIN ไปเติม
บนเว็บไซต์
ของผู้ให้บริการ

</p>

</div>

</div>

<div class="notice-box">

<h4>

<i class="fa-solid fa-circle-exclamation"></i>

ข้อควรทราบ

</h4>

<p>

Voucher เป็นสินค้าดิจิทัล

เมื่อระบบส่ง PIN แล้ว

ไม่สามารถเปลี่ยน
หรือคืนเงินได้

กรุณาตรวจสอบรายการก่อนชำระเงิน

</p>

</div>

<hr class="my-5">

<h2>คำถามที่พบบ่อย</h2>

<div class="faq-card">

<div class="faq-question">

Voucher จะได้รับเมื่อไร?

</div>

<div class="faq-answer">

หลังชำระเงินสำเร็จ
ระบบจะส่ง PIN Code
โดยอัตโนมัติ

</div>

</div>

<div class="faq-card">

<div class="faq-question">

หากไม่ได้รับ PIN ทำอย่างไร?

</div>

<div class="faq-answer">

ตรวจสอบ Order History
หรือ ติดต่อทีมงาน Support
พร้อมแจ้ง Order ID

</div>

</div>

<div class="faq-card">

<div class="faq-question">

Voucher คืนเงินได้หรือไม่?

</div>

<div class="faq-answer">

Voucher ที่ส่ง PIN แล้ว
ไม่สามารถคืนเงินได้

</div>

</div>

<div class="contact-box">

<h2>

ยังต้องการความช่วยเหลือ?

</h2>

<p>

ทีมงาน CN Tech Store
พร้อมให้บริการทุกวัน

</p>

<a href="contact-method.php" class="btn-guide">

ติดต่อ Support

</a>

</div>

</div>

<?php include "../footer.php"; ?>

</body>

</html>