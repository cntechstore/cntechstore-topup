<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "../config.php";
require_once "../database.php";
?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Support Center | CN Tech Store</title>

<link rel="stylesheet" href="../style.css?v=1.0.0">
<link rel="stylesheet" href="../page.css?v=1.0.0">

    
  <link rel="canonical"
href="<?= $currentURL ?>">  
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script src="../app.js?v=1.0"></script>
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
<style>
.logo-image{

    width:120px;
    height:68px;

    }
    
.support-container{
    max-width:1200px;
    margin:auto;
    padding:30px 15px;
}

.support-hero{
    background:linear-gradient(135deg,#2563eb,#0f172a);
    color:#fff;
    padding:60px 30px;
    border-radius:20px;
    text-align:center;
    margin-bottom:30px;
}

.support-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
}

.support-card{
    background:#fff;
    border-radius:18px;
    padding:25px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
}

.support-card:hover{
    transform:translateY(-8px);
}

.support-card i{
    font-size:45px;
    color:#2563eb;
    margin-bottom:15px;
}

.support-card h3{
    margin-bottom:10px;
    color:#555;
}

.support-card p{
    color:#666;
    line-height:1.7;
}

.support-card a{
    display:inline-block;
    margin-top:18px;
    background:#2563eb;
    color:#fff;
    padding:12px 22px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
}

.support-card a:hover{
    background:#1d4ed8;
}

.contact-box{
    margin-top:50px;
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.contact-box h2{
    margin-bottom:20px;
}

.contact-item{
    margin:15px 0;
    font-size:17px;
}

.contact-item i{
    width:35px;
    color:#2563eb;
}

</style>

</head>

<body>

<?php include "../navbar.php"; ?>

<div class="support-container">

<div class="support-hero">

<h1>
<i class="fa-solid fa-headset"></i>
Support Center
</h1>

<p>
ทีมงาน CN Tech Store พร้อมช่วยเหลือคุณเกี่ยวกับสินค้า
การเติมเกม Voucher การชำระเงิน และปัญหาการใช้งานเว็บไซต์
</p>

</div>

<div class="support-grid">

<div class="support-card">

<i class="fa-solid fa-circle-question"></i>

<h3>Help Center</h3>

<p>
ค้นหาคำตอบสำหรับคำถามที่พบบ่อย
และคู่มือการใช้งานเว็บไซต์
</p>

<a href="help-center.php">
เปิด Help Center
</a>

</div>

<div class="support-card">

<i class="fa-solid fa-receipt"></i>

<h3>Order History</h3>

<p>
ตรวจสอบสถานะคำสั่งซื้อ
และประวัติการสั่งซื้อของคุณ
</p>

<a href="order-history.php">
ตรวจสอบ Order
</a>

</div>

<div class="support-card">

<i class="fa-solid fa-envelope"></i>

<h3>Contact Us</h3>

<p>
ส่งข้อความถึงทีมงาน
หากพบปัญหาในการใช้งานเว็บไซต์
</p>

<a href="contact-method.php">
ติดต่อเรา
</a>

</div>

<div class="support-card">

<i class="fa-solid fa-ticket"></i>

<h3>Promotion</h3>

<p>
ดูโปรโมชั่นล่าสุด
Coupon และส่วนลดทั้งหมด
</p>

<a href="promotion.php">
ดูโปรโมชั่น
</a>

</div>

</div>

<div class="contact-box">

<h2>ข้อมูลการติดต่อ</h2>

<div class="contact-item">
<i class="fa-solid fa-globe"></i>
Website :
https://cntechstore.shop
</div>

<div class="contact-item">
<i class="fa-solid fa-envelope"></i>
Email :
support@cntechstore.shop
</div>

<div class="contact-item">
<i class="fa-solid fa-clock"></i>
Support :
24 ชั่วโมง
</div>

<div class="contact-item">
<i class="fa-solid fa-shield-halved"></i>
Secure • Fast • Reliable
</div>

</div>

</div>

<?php include "../footer.php"; ?>

</body>
</html>