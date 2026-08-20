<?php
session_start();
require_once "../config.php";

$page_title = "FAQ | CN Tech Store";
$page_description = "Frequently Asked Questions - CN Tech Store";
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $page_title ?></title>

<meta name="description"
content="<?= $page_description ?>">

<link rel="icon" href="<?= BASE_URL ?>assets/img/favicon.png">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="../style.css?v=1.0.0">
    
    <link rel="stylesheet" href="../faq.css?v=1.0.0">
    
    <script src="../app.js?v=1.0"></script>
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
<style>

:root{

--primary:#2563eb;
--primary2:#1d4ed8;

--success:#16a34a;
--danger:#dc2626;

--warning:#f59e0b;

--bg:#f5f7fb;
--card:#ffffff;

--text:#1f2937;
--muted:#6b7280;

--border:#e5e7eb;

--radius:18px;

--shadow:
0 10px 30px rgba(0,0,0,.08);

}

*{

margin:0;
padding:0;

box-sizing:border-box;

font-family:
Arial,
Helvetica,
sans-serif;

}

html{

scroll-behavior:smooth;

}

body{

background:var(--bg);

color:var(--text);

line-height:1.7;

}

/* ==========================
CONTAINER
========================== */

    .logo-image{

    width:120px;
    height:68px;

    }
    
.container{

width:95%;
max-width:1200px;

margin:auto;

}

/* ==========================
HERO
========================== */

.hero{

background:
linear-gradient(
135deg,
#2563eb,
#0f172a
);

color:#fff;

padding:70px 20px;

margin-bottom:35px;

}

.hero-flex{

display:flex;

align-items:center;

justify-content:space-between;

gap:40px;

flex-wrap:wrap;

}

.hero-left{

flex:1;

min-width:280px;

}

.hero-left h1{

font-size:42px;

margin-bottom:15px;

font-weight:700;

}

.hero-left p{

font-size:18px;

opacity:.95;

max-width:700px;

}

.hero-right{

flex:1;

text-align:center;

min-width:260px;

}

.hero-right i{

font-size:170px;

opacity:.12;

}

/* ==========================
SEARCH
========================== */

.search-box{

margin-top:35px;

display:flex;

gap:12px;

background:#fff;

padding:12px;

border-radius:60px;

box-shadow:var(--shadow);

}

.search-box input{

flex:1;

border:none;

outline:none;

font-size:16px;

padding:15px;

background:transparent;

}

.search-box button{

border:none;

background:var(--primary);

color:#fff;

padding:15px 30px;

border-radius:50px;

cursor:pointer;

font-size:16px;

transition:.25s;

}

.search-box button:hover{

background:var(--primary2);

}

/* ==========================
BREADCRUMB
========================== */

.breadcrumb{

background:#fff;

padding:14px 20px;

border-radius:15px;

margin-bottom:25px;

box-shadow:var(--shadow);

font-size:15px;

}

.breadcrumb a{

color:var(--primary);

text-decoration:none;

font-weight:bold;

}

.breadcrumb span{

color:#888;

margin:0 8px;

}

/* ==========================
INFO BOX
========================== */

.info-box{

display:grid;

grid-template-columns:
repeat(
auto-fit,
minmax(230px,1fr)
);

gap:20px;

margin-bottom:30px;

}

.info-card{

background:#fff;

padding:22px;

border-radius:18px;

box-shadow:var(--shadow);

transition:.25s;

}

.info-card:hover{

transform:translateY(-4px);

}

.info-card i{

font-size:34px;

color:var(--primary);

margin-bottom:15px;

}

.info-card h3{

margin-bottom:10px;

}

.info-card p{

color:var(--muted);

font-size:15px;

}

/* Responsive */

@media(max-width:768px){

.hero{

padding:45px 20px;

}

.hero-left h1{

font-size:30px;

}

.hero-right{

display:none;

}

.search-box{

flex-direction:column;

border-radius:18px;

}

.search-box button{

width:100%;

}

}

</style>

</head>

<body>

<?php include "../navbar.php"; ?>

<!-- ==========================
HERO
========================== -->

<section class="hero">

<div class="container">

<div class="hero-flex">

<div class="hero-left">

<h1>
<i class="fa-solid fa-circle-question"></i>
Help Center
</h1>

<p>

ยินดีต้อนรับสู่ศูนย์ช่วยเหลือของ
<b>CN Tech Store</b>

ค้นหาคำตอบเกี่ยวกับการสั่งซื้อ
การเติมเกม
Voucher
การชำระเงิน
และบริการต่าง ๆ ได้ที่นี่

</p>

<div class="search-box">

<input
type="text"
id="faqSearch"
placeholder="Search FAQ...">

<button onclick="searchFAQ()">

<i class="fa-solid fa-magnifying-glass"></i>

Search

</button>

</div>

</div>

<div class="hero-right">

<i class="fa-solid fa-headset"></i>

</div>

</div>

</div>

</section>

<div class="container">

<div class="breadcrumb">

<a href="<?=BASE_URL?>index.php">

Home

</a>

<span>/</span>

FAQ

</div>

<div class="info-box">

<div class="info-card">

<i class="fa-solid fa-gamepad"></i>

<h3>Game Top-up</h3>

<p>

เติมเกมออนไลน์
Mobile Legends,
Free Fire,
PUBG,
ROV,
Honor of Kings
และเกมยอดนิยมอื่น ๆ

</p>

</div>

<div class="info-card">

<i class="fa-solid fa-ticket"></i>

<h3>Voucher</h3>

<p>

Garena Shell,
Razer Gold,
Steam Wallet,
PlayStation Gift Card
และบัตรเติมเงินอื่น ๆ

</p>

</div>

<div class="info-card">

<i class="fa-solid fa-credit-card"></i>

<h3>Payment</h3>

<p>

รองรับหลายช่องทางการชำระเงิน
พร้อมระบบตรวจสอบสถานะคำสั่งซื้ออัตโนมัติ

</p>

</div>

<div class="info-card">

<i class="fa-solid fa-headset"></i>

<h3>Support</h3>

<p>

ทีมงานพร้อมให้ความช่วยเหลือ
หากพบปัญหาในการใช้งานเว็บไซต์

</p>

</div>

</div>

<!-- ===== END PART 1.1 ===== -->
    
    <!-- =========================
FAQ NAVIGATION
========================= -->

<div class="faq-nav">

<button class="tab-btn active"
onclick="filterFAQ('all')">

<i class="fa-solid fa-layer-group"></i>
All

</button>

<button class="tab-btn"
onclick="filterFAQ('general')">

<i class="fa-solid fa-circle-info"></i>
General

</button>

<button class="tab-btn"
onclick="filterFAQ('order')">

<i class="fa-solid fa-box"></i>
Orders

</button>

<button class="tab-btn"
onclick="filterFAQ('game')">

<i class="fa-solid fa-gamepad"></i>
Game Top-up

</button>

<button class="tab-btn"
onclick="filterFAQ('voucher')">

<i class="fa-solid fa-ticket"></i>
Voucher

</button>

<button class="tab-btn"
onclick="filterFAQ('payment')">

<i class="fa-solid fa-credit-card"></i>
Payment

</button>

<button class="tab-btn"
onclick="filterFAQ('account')">

<i class="fa-solid fa-user"></i>
Account

</button>

<button class="tab-btn"
onclick="filterFAQ('support')">

<i class="fa-solid fa-headset"></i>
Support

</button>

</div>


<!-- =========================
CATEGORY CARDS
========================= -->

<div class="category-grid">

<div class="category-card">

<i class="fa-solid fa-gamepad"></i>

<h3>Game Top-up</h3>

<p>

เติมเกมออนไลน์
รวดเร็ว
รองรับหลายเกม

</p>

</div>

<div class="category-card">

<i class="fa-solid fa-mobile-screen"></i>

<h3>Mobile Top-up</h3>

<p>

เติมเงินมือถือ
Unitel
Lao Telecom
ETL

</p>

</div>

<div class="category-card">

<i class="fa-solid fa-ticket"></i>

<h3>Gift Voucher</h3>

<p>

Garena

Razer Gold

Steam

PlayStation

</p>

</div>

<div class="category-card">

<i class="fa-solid fa-credit-card"></i>

<h3>Payment</h3>

<p>

BCEL

LDB

QR

Bank Transfer

</p>

</div>

</div>



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



<div class="accordion-item faq-item">

<button class="accordion-header">

สามารถใช้งานได้ทุกประเทศหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

ปัจจุบันให้บริการหลักในประเทศลาว

และกำลังขยายบริการไปยังประเทศอื่น

ในอนาคต

</div>

</div>



</div>

</div>



<!-- ===== END PART 1.2 ===== -->
    <!-- =========================
ORDER FAQ
========================= -->

<div class="faq-group"
data-category="order">

<h2 class="faq-title">

<i class="fa-solid fa-box"></i>

Order & Shopping

</h2>

<div class="accordion">

<div class="accordion-item faq-item">

<button class="accordion-header">

สั่งซื้อสินค้าอย่างไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

เลือกสินค้าที่ต้องการ →
กด Buy Now หรือ Add to Cart →
กรอกข้อมูล →
เลือกช่องทางชำระเงิน →
ชำระเงิน →
ระบบยืนยันคำสั่งซื้ออัตโนมัติ

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ตรวจสอบสถานะคำสั่งซื้อได้อย่างไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

สามารถตรวจสอบได้จากหน้า

Order History

โดยใช้หมายเลข Order ID
หรือบัญชีผู้ใช้งานของคุณ

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

สามารถยกเลิกคำสั่งซื้อได้หรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

หากระบบยังไม่เริ่มดำเนินการ
สามารถติดต่อทีมงานเพื่อยกเลิกได้

แต่หากชำระเงินและดำเนินการแล้ว
จะไม่สามารถยกเลิกได้

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

การจัดส่งใช้เวลานานเท่าไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

สินค้าดิจิทัล

ประมาณ 5 วินาที - 5 นาที

สินค้าไอที

1-7 วันทำการ

ขึ้นอยู่กับพื้นที่จัดส่ง

</div>

</div>

</div>

</div>



<!-- =========================
GAME TOPUP
========================= -->

<div class="faq-group"
data-category="game">

<h2 class="faq-title">

<i class="fa-solid fa-gamepad"></i>

Game Top-up

</h2>

<div class="accordion">

<div class="accordion-item faq-item">

<button class="accordion-header">

รองรับเกมอะไรบ้าง

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

Mobile Legends

Free Fire

PUBG Mobile

ROV

Honor of Kings

Arena Breakout

และเกมอื่น ๆ
ที่จะเพิ่มในอนาคต

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

เติมเกมใช้เวลากี่นาที

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

ส่วนใหญ่ใช้เวลา

5 วินาที

ถึง

3 นาที

หากระบบของผู้ให้บริการล่าช้า
อาจใช้เวลาสูงสุดประมาณ 30 นาที

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

เติมเกมผิด UID ทำอย่างไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

กรุณาตรวจสอบ UID
ก่อนกดยืนยันทุกครั้ง

หากระบบส่งสินค้าแล้ว
จะไม่สามารถเรียกคืนได้

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

เว็บไซต์ตรวจสอบชื่อผู้เล่นได้หรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

บางเกมรองรับการตรวจสอบชื่อผู้เล่น
ผ่านระบบ API

หากเกมใดไม่รองรับ
ระบบจะให้กรอก UID โดยตรง

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ทำไมสถานะยัง Pending

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

อาจเกิดจาก

• รอการชำระเงิน

• รอ API ผู้ให้บริการ

• ระบบกำลังดำเนินการ

หากเกิน 30 นาที
กรุณาติดต่อทีมงาน

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

เติมเกมแล้วไม่ได้รับสินค้า

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

กรุณาแจ้ง

Order ID

พร้อมหลักฐานการชำระเงิน

ทีมงานจะตรวจสอบให้ทันที

</div>

</div>

</div>

</div>

<!-- ===== END PART 2 ===== -->
    
    <!-- =========================
VOUCHER FAQ
========================= -->

<div class="faq-group"
data-category="voucher">

<h2 class="faq-title">

<i class="fa-solid fa-ticket"></i>

Voucher & Gift Card

</h2>

<div class="accordion">

<div class="accordion-item faq-item">

<button class="accordion-header">

Voucher คืออะไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

Voucher หรือ Gift Card คือรหัสสำหรับเติมเครดิต
หรือแลกสินค้าและบริการดิจิทัล
เช่น Garena, Razer Gold, Steam และ PlayStation เป็นต้น

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

รองรับ Voucher อะไรบ้าง

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

• Garena Shell

• Razer Gold

• Steam Wallet

• PlayStation Store

• Apple Gift Card

• Google Play Gift Card

และจะเพิ่มบริการใหม่ในอนาคต

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

Voucher มีวันหมดอายุหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

ขึ้นอยู่กับผู้ให้บริการแต่ละประเภท

กรุณาตรวจสอบรายละเอียดของ Voucher
ก่อนทำการสั่งซื้อทุกครั้ง

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ซื้อแล้วได้รับ Voucher เมื่อไร

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

เมื่อชำระเงินสำเร็จ

ระบบจะจัดส่ง PIN Code
ภายในไม่กี่วินาที

หากเกิดความล่าช้า
ทีมงานจะตรวจสอบให้ทันที

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

Voucher คืนเงินได้หรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

Voucher ที่จัดส่งแล้ว

ไม่สามารถคืนเงิน
หรือเปลี่ยนสินค้าได้

ยกเว้นเกิดจากความผิดพลาดของระบบ

</div>

</div>

</div>

</div>



<!-- =========================
PAYMENT FAQ
========================= -->

<div class="faq-group"
data-category="payment">

<h2 class="faq-title">

<i class="fa-solid fa-credit-card"></i>

Payment

</h2>

<div class="accordion">

<div class="accordion-item faq-item">

<button class="accordion-header">

รองรับช่องทางชำระเงินอะไรบ้าง

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

รองรับ

BCEL

LDB

QR Payment

Bank Transfer

Visa / MasterCard
(ในอนาคต)

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ชำระเงินแล้วแต่สถานะยังไม่เปลี่ยน

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

ระบบอาจกำลังตรวจสอบธุรกรรม

กรุณารอประมาณ 1-5 นาที

หากยังไม่เปลี่ยน
กรุณาติดต่อทีมงาน

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ชำระเงินผิดจำนวน

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

กรุณาติดต่อทีมงานทันที

พร้อมแนบ

Order ID

และหลักฐานการโอนเงิน

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

เว็บไซต์ปลอดภัยหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

เว็บไซต์ใช้ HTTPS

มีระบบเข้ารหัสข้อมูล

และบันทึกธุรกรรมเพื่อความปลอดภัย

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

รองรับการคืนเงินหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

การคืนเงินเป็นไปตาม

Refund Policy

ของเว็บไซต์

เฉพาะกรณีที่เข้าเงื่อนไขเท่านั้น

</div>

</div>

</div>

</div>



<!-- =========================
ACCOUNT FAQ
========================= -->

<div class="faq-group"
data-category="account">

<h2 class="faq-title">

<i class="fa-solid fa-user"></i>

Account

</h2>

<div class="accordion">

<div class="accordion-item faq-item">

<button class="accordion-header">

จำเป็นต้องสมัครสมาชิกหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

บางบริการสามารถใช้งานได้ทันที

แต่การสมัครสมาชิก
จะช่วยให้สามารถตรวจสอบประวัติคำสั่งซื้อ
และรับโปรโมชั่นได้ง่ายขึ้น

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ลืมรหัสผ่าน

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

สามารถกด

Forgot Password

เพื่อรีเซ็ตรหัสผ่านผ่านอีเมลได้

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

ข้อมูลส่วนตัวปลอดภัยหรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

ข้อมูลลูกค้าจะถูกจัดเก็บ
ตามนโยบาย Privacy Policy

และจะไม่เปิดเผยแก่บุคคลภายนอก

</div>

</div>

<div class="accordion-item faq-item">

<button class="accordion-header">

เปลี่ยนอีเมลได้หรือไม่

<i class="fa-solid fa-chevron-down"></i>

</button>

<div class="accordion-body">

สามารถติดต่อทีมงาน

เพื่อเปลี่ยนข้อมูลบัญชีได้

หลังจากยืนยันตัวตนเรียบร้อย

</div>

</div>

</div>

</div>

<!-- ===== END PART 3 ===== -->
    
    <!-- =========================
SUPPORT FAQ
========================= -->

<div class="faq-group"
data-category="support">

<h2 class="faq-title">

<i class="fa-solid fa-headset"></i>

Support Center

</h2>


<div class="accordion">


<div class="accordion-item faq-item">

<button class="accordion-header">

ติดต่อทีมงาน CN Tech Store ได้อย่างไร

<i class="fa-solid fa-chevron-down"></i>

</button>


<div class="accordion-body">

สามารถติดต่อทีมงานผ่านช่องทาง

<br><br>

• Contact Us

<br>
• Facebook Page

<br>
• Email Support

<br>
• Help Center

<br><br>

ทีมงานจะตอบกลับโดยเร็วที่สุด

</div>

</div>



<div class="accordion-item faq-item">

<button class="accordion-header">

ต้องแจ้งข้อมูลอะไรเมื่อพบปัญหา

<i class="fa-solid fa-chevron-down"></i>

</button>


<div class="accordion-body">

กรุณาแจ้งข้อมูลดังนี้

<br><br>

• Order ID

<br>
• Email ที่ใช้สั่งซื้อ

<br>
• เวลาในการทำรายการ

<br>
• หลักฐานการชำระเงิน

<br>
• รายละเอียดปัญหา

<br><br>

เพื่อให้ทีมงานตรวจสอบได้รวดเร็ว

</div>

</div>




<div class="accordion-item faq-item">

<button class="accordion-header">

ระบบเปิดให้บริการเวลาไหน

<i class="fa-solid fa-chevron-down"></i>

</button>


<div class="accordion-body">

เว็บไซต์เปิดให้บริการ 24 ชั่วโมง

ระบบอัตโนมัติสามารถดำเนินการได้ตลอดเวลา

สำหรับ Support

อาจมีเวลาตอบกลับตามเวลาทำการ

</div>

</div>




<div class="accordion-item faq-item">

<button class="accordion-header">

หากระบบเกิดข้อผิดพลาดทำอย่างไร

<i class="fa-solid fa-chevron-down"></i>

</button>


<div class="accordion-body">

หากพบปัญหา เช่น

<br>

• เติมเกมไม่สำเร็จ

<br>
• ไม่ได้รับ PIN CODE

<br>
• ชำระเงินแล้วไม่อัปเดต

<br>

กรุณาติดต่อทีมงาน

ระบบจะตรวจสอบจาก Order ID

</div>

</div>


</div>

</div>





<!-- =========================
HELP CENTER
========================= -->

<div class="faq-group">


<h2 class="faq-title">

<i class="fa-solid fa-circle-question"></i>

Help Center

</h2>



<div class="help-card">


<h3>

<i class="fa-solid fa-book"></i>

คู่มือการใช้งาน

</h3>


<p>

เรียนรู้วิธีใช้งาน CN Tech Store

ตั้งแต่การเลือกสินค้า

การเติมเกม

การชำระเงิน

และตรวจสอบคำสั่งซื้อ

</p>



<div class="button-group">


<a href="../index.php"
class="help-btn">

<i class="fa-solid fa-house"></i>

หน้าแรก

</a>



<a href="order-history.php"
class="help-btn">

<i class="fa-solid fa-clock-rotate-left"></i>

ประวัติคำสั่งซื้อ

</a>



<a href="contact.php"
class="help-btn">

<i class="fa-solid fa-envelope"></i>

Contact

</a>


</div>


</div>


</div>





<!-- =========================
POPULAR QUESTIONS
========================= -->


<div class="faq-bottom-card">


<h2>

คำถามยอดนิยม

</h2>



<div class="quick-links">


<a href="game-topup-guide.php">

 วิธีเติมเกมออนไลน์

</a>


<a href="voucher-guide.php">

 วิธีใช้ Voucher

</a>


<a href="payment-method.php">

 วิธีชำระเงิน

</a>


<a href="refund-policy.php">

 Refund Policy

</a>


<a href="privacy-policy.php">

 Privacy Policy

</a>


<a href="terms-of-service.php">

 Terms Service

</a>


</div>


</div>





<!-- =========================
HOME LINK
========================= -->


<div class="back-home">


<a href="../index.php">

<i class="fa-solid fa-arrow-left"></i>

กลับหน้าแรก CN Tech Store

</a>


</div>



</div>
<!-- END FAQ WRAPPER -->




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




<!-- =========================
FOOTER
========================= -->


<footer class="faq-footer">


<div>


<h3>

CN Tech Store

</h3>


<p>

Computer • Mobile • Parts & Accessories

<br>

Game Top-up • Voucher • Digital Service

</p>


</div>



<div class="footer-links">


<a href="about-us.php">

About Us

</a>


<a href="privacy-policy.php">

Privacy Policy

</a>


<a href="terms-of-service.php">

Terms

</a>


<a href="contact.php">

Contact

</a>


</div>



<p class="copyright">

© <?=date("Y")?>

CN Tech Store

All Rights Reserved.

</p>


    </footer>