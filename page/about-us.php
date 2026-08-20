<?php

require_once "../config.php";
require_once "../database.php";

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

$cartCount = 0;

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cartCount += (int)$item['qty'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>About Us | CN Tech Store</title>

<meta name="description"
content="Learn more about CN Tech Store, our mission, products and online services.">

<link rel="stylesheet" href="style.css?v=1.0.0">
<link rel="stylesheet" href="page.css?v=1.0.0">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

<link rel="icon" href="uploads/favicon.png">

<style>

.page-banner{
background:linear-gradient(135deg,#2563eb,#22c55e);
color:#fff;
padding:60px 20px;
text-align:center;
border-radius:20px;
margin-bottom:30px;
}

.page-banner h1{
font-size:38px;
margin-bottom:10px;
}

.about-box{
background:#fff;
padding:25px;
border-radius:18px;
margin-bottom:20px;
box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.about-box h2{
margin-bottom:15px;
color:#2563eb;
}

.about-box p,
.about-box li{
line-height:1.8;
}

.feature-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:20px;
margin-top:20px;
}

.feature-card{
background:#f8fafc;
border-radius:15px;
padding:20px;
text-align:center;
}

.feature-card i{
font-size:40px;
color:#2563eb;
margin-bottom:15px;
}

</style>

</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">

<main>

<section class="page-banner">

<h1>About CN Tech Store</h1>

<p>
Computer • Mobile • Parts & Accessories
</p>

</section>

<section class="about-box">

<h2>Who We Are</h2>

<p>

CN Tech Store เป็นแพลตฟอร์มจำหน่ายสินค้าเทคโนโลยี
อุปกรณ์คอมพิวเตอร์ อุปกรณ์เสริม
และบริการดิจิทัล เช่น เติมเกมออนไลน์
เพื่อมอบประสบการณ์ที่สะดวก รวดเร็ว และปลอดภัยให้กับลูกค้า

</p>

</section>

<section class="about-box">

<h2>Our Mission</h2>

<ul>

<li>จำหน่ายสินค้าไอทีคุณภาพ</li>

<li>พัฒนาระบบเติมเกมออนไลน์</li>

<li>รองรับการชำระเงินที่ปลอดภัย</li>

<li>พัฒนาเว็บไซต์อย่างต่อเนื่อง</li>

<li>ให้บริการลูกค้าอย่างมืออาชีพ</li>

</ul>

</section>

<section class="feature-grid">

<div class="feature-card">

<i class="fa-solid fa-computer"></i>

<h3>Technology</h3>

<p>Computer, Laptop, SSD, RAM และอุปกรณ์ไอที</p>

</div>

<div class="feature-card">

<i class="fa-solid fa-gamepad"></i>

<h3>Game Top-up</h3>

<p>เติมเกมออนไลน์หลายเกมอย่างรวดเร็ว</p>

</div>

<div class="feature-card">

<i class="fa-solid fa-shield-halved"></i>

<h3>Security</h3>

<p>ให้ความสำคัญกับความปลอดภัยของข้อมูลลูกค้า</p>

</div>

<div class="feature-card">

<i class="fa-solid fa-headset"></i>

<h3>Support</h3>

<p>ทีมงานพร้อมให้บริการทุกวัน</p>

</div>

</section>

<section class="about-box">

<h2>Contact</h2>

<p>

เว็บไซต์ :
https://cntechstore.shop

<br><br>

Email :
support@cntechstore.shop

</p>

</section>

</main>

</div>

<?php include "footer.php"; ?>

<script src="app.js?v=1.0"></script>

<script>

function toggleDropdown(el){
el.parentElement.classList.toggle("active");
}

function toggleUserMenu(){
document.getElementById("userMenu").classList.toggle("show");
}

</script>

</body>

</html>