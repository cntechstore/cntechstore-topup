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

<title>Game Top-up Guide | CN Tech Store</title>

<link rel="stylesheet" href="../style.css?v=1.0.0">
<link rel="stylesheet" href="../page.css?v=1.0.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
    
    /* ======================================
GAME TOPUP GUIDE
CN Tech Store
====================================== */

body{
    background:#f5f7fb;
    font-family:Arial,Helvetica,sans-serif;
}

.container{
    max-width:1200px;
    margin:auto;
}

/* HERO */

.hero-card{
    background:linear-gradient(135deg,#2563eb,#1e3a8a);
    color:#fff;
    padding:60px 30px;
    border-radius:24px;
    text-align:center;
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

/* INFO CARD */

.info-card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    text-align:center;
    height:100%;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
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
    margin-bottom:18px;
}

.info-card h4{
    margin-bottom:10px;
    font-size:22px;
    font-weight:700;
}

.info-card p{
    color:#64748b;
    line-height:1.7;
}

/* STEP */

.step-box{
    background:#fff;
    border-radius:20px;
    padding:25px;
    margin-bottom:20px;
    display:flex;
    gap:20px;
    align-items:flex-start;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
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

.step-content h3{
    margin-bottom:12px;
    font-size:24px;
    color:#2563eb;
}

.step-content p{
    color:#555;
    line-height:1.8;
}

/* NOTICE */

.notice-box{
    background:#fff8e1;
    border-left:6px solid #f59e0b;
    padding:20px;
    border-radius:12px;
    margin:25px 0;
}

.notice-box h4{
    color:#b45309;
    margin-bottom:10px;
}

.notice-box p{
    color:#92400e;
    margin:0;
}

/* FAQ */

.faq-card{
    background:#fff;
    border-radius:18px;
    margin-bottom:15px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
}

.faq-question{
    background:#2563eb;
    color:#fff;
    padding:18px 22px;
    font-size:18px;
    font-weight:700;
}

.faq-answer{
    padding:20px;
    line-height:1.8;
    color:#555;
}

/* BUTTON */

.btn-guide{
    display:inline-block;
    padding:14px 30px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    font-weight:700;
    transition:.3s;
}

.btn-guide:hover{
    background:#1d4ed8;
}

/* CONTACT */

.contact-box{
    background:#0f172a;
    color:#fff;
    padding:40px;
    border-radius:20px;
    text-align:center;
    margin-top:50px;
}

.contact-box h2{
    margin-bottom:15px;
}

.contact-box p{
    opacity:.9;
    margin-bottom:25px;
}

/* MOBILE */

@media(max-width:768px){

.hero-card{
    padding:40px 20px;
}

.hero-card h1{
    font-size:30px;
}

.hero-card p{
    font-size:16px;
}

.step-box{
    flex-direction:column;
}

.step-number{
    margin:auto;
}

.step-content{
    text-align:center;
}

.info-card{
    margin-bottom:15px;
    
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

<a href="<?=BASE_URL?>index.php">Home</a>

<i class="fa-solid fa-angle-right"></i>

<a href="<?=BASE_URL?>page/help-center.php">Help Center</a>

<i class="fa-solid fa-angle-right"></i>

<b>Game Top-up Guide</b>

</nav>

<div class="hero-card">

<h1>

<i class="fa-solid fa-gamepad"></i>

Game Top-up Guide

</h1>

<p>

คู่มือการเติมเกมออนไลน์ผ่าน

CN Tech Store

ปลอดภัย รวดเร็ว ภายในไม่กี่นาที

</p>

</div>

<div class="row mt-5 g-4">

<div class="col-md-4">

<div class="info-card">

<i class="fa-solid fa-clock"></i>

<h4>ใช้เวลา</h4>

<p>

5 วินาที - 5 นาที

</p>

</div>

</div>

<div class="col-md-4">

<div class="info-card">

<i class="fa-solid fa-shield-halved"></i>

<h4>ปลอดภัย</h4>

<p>

ระบบเข้ารหัส HTTPS

</p>

</div>

</div>

<div class="col-md-4">

<div class="info-card">

<i class="fa-solid fa-headset"></i>

<h4>Support</h4>

<p>

ทีมงานช่วยเหลือทุกวัน

</p>

</div>

</div>

    </div>
    
    <?php include "../footer.php"; ?>