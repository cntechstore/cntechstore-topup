<?php
session_start();
?>
<!DOCTYPE html>
<html lang="lo">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#ff0000">

<title>ຕິດຕໍ່ພວກເຮົາ | CN Tech Store</title>

<link rel="icon" href="../assets/favicon.png">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

html,
body{
background:#000;
color:#fff;
min-height:100%;
}

body{
padding-bottom:80px;
}

.header{

position:sticky;
top:0;
z-index:999;

padding:15px;

background:rgba(15,15,15,.85);

backdrop-filter:blur(15px);

border-bottom:1px solid rgba(255,255,255,.08);

}

.logo{

text-align:center;

font-size:24px;

font-weight:700;

color:#ff2020;

}

.logo span{

color:#fff;

}

.container{

max-width:700px;

margin:auto;

padding:15px;

}

.card{

background:rgba(255,255,255,.05);

backdrop-filter:blur(20px);

border:1px solid rgba(255,255,255,.08);

border-radius:20px;

padding:18px;

margin-bottom:15px;

box-shadow:
0 8px 25px rgba(255,0,0,.12);

}

.card h2,
.card h3{

color:#ff3030;

margin-bottom:15px;

}

.subtitle{

color:#aaa;

font-size:14px;

line-height:1.6;

}

.status{

display:inline-block;

margin-top:12px;

padding:8px 14px;

border-radius:30px;

background:#102010;

color:#4ade80;

font-size:13px;

font-weight:bold;

}

.info-item{

display:flex;

align-items:center;

gap:12px;

padding:14px 0;

border-bottom:1px solid rgba(255,255,255,.08);

}

.info-item:last-child{

border-bottom:none;

}

.info-item i{

width:24px;

font-size:18px;

color:#ff3030;

    }
    
    .form-control{

width:100%;

padding:14px 16px;

margin-bottom:12px;

border-radius:14px;

border:1px solid #333;

background:#111;

color:#fff;

font-size:15px;

transition:.3s;

}

.form-control:focus{

outline:none;

border-color:#ff3030;

box-shadow:
0 0 0 3px rgba(255,0,0,.15);

}

textarea{

resize:none;

min-height:140px;

}

.btn-send{

width:100%;

border:none;

padding:15px;

border-radius:14px;

cursor:pointer;

font-size:16px;

font-weight:700;

color:#fff;

background:
linear-gradient(
135deg,
#ff0000,
#b30000
);

transition:.3s;

}

.btn-send:hover{

transform:translateY(-2px);

box-shadow:
0 10px 25px rgba(255,0,0,.25);

}

.btn-send:active{

transform:scale(.98);

}

#errorBox{

margin-top:12px;

}

.social-grid{

display:grid;

grid-template-columns:
repeat(2,1fr);

gap:12px;

margin-top:10px;

}

.social-btn{

display:flex;

align-items:center;

justify-content:center;

gap:10px;

padding:14px;

border-radius:14px;

text-decoration:none;

color:#fff;

background:#111;

border:1px solid #222;

transition:.3s;

}

.social-btn:hover{

border-color:#ff3030;

}

.footer{

text-align:center;

padding:25px 15px;

color:#888;

font-size:13px;

}

.bottom-nav{

position:fixed;

left:0;
right:0;
bottom:0;

height:65px;

background:rgba(10,10,10,.95);

backdrop-filter:blur(20px);

border-top:1px solid #222;

display:flex;

justify-content:space-around;

align-items:center;

z-index:9999;

}

.bottom-nav a{

color:#bbb;

text-decoration:none;

font-size:12px;

text-align:center;

}

.bottom-nav a.active{

color:#ff3030;

}

.bottom-nav i{

display:block;

font-size:20px;

margin-bottom:4px;

}

@media(max-width:600px){

.social-grid{

grid-template-columns:1fr;

}

.logo{

font-size:22px;

}

    }
    
    </style>
</head>

<body>

<div class="header">

<div class="logo">
CNTECH <span>STORE</span>
</div>

</div>

<div class="container">
    
    <div class="card">

<h2>
<i class="fa-solid fa-headset"></i>
 ຕິດຕໍ່ຝ່າຍບໍລິການ
</h2>

<p class="subtitle">

CN Tech Store ໃຫ້ບໍລິການເຕີມເກມອອນລາຍ,
ບັດເຕີມເງິນ,
ແລະ ສິນຄ້າດິຈິຕອນ
ພ້ອມບໍລິການລູກຄ້າຕະຫຼອດ 24 ຊົ່ວໂມງ

</p>

<div class="status">
● Online Support
</div>

</div>



<div class="card">

<h3>
<i class="fa-solid fa-building"></i>
 ຂໍ້ມູນບໍລິສັດ
</h3>

<div class="info-item">
<i class="fa-solid fa-store"></i>
<span>CN Tech Store</span>
</div>

<div class="info-item">
<i class="fa-solid fa-envelope"></i>
<span>support@cntechstore.shop</span>
</div>

<div class="info-item">
<i class="fa-solid fa-globe"></i>
<span>www.cntechstore.shop</span>
</div>

<div class="info-item">
<i class="fa-solid fa-clock"></i>
<span>ບໍລິການອອນລາຍ 24/7</span>
</div>

</div>



<div class="card">

<h3>
<i class="fa-solid fa-share-nodes"></i>
 ຊ່ອງທາງຕິດຕໍ່
</h3>

<div class="social-grid">

<a href="#"
class="social-btn">

<i class="fa-brands fa-facebook"></i>
Facebook

</a>

<a href="#"
class="social-btn">

<i class="fa-brands fa-tiktok"></i>
TikTok

</a>

<a href="#"
class="social-btn">

<i class="fa-brands fa-telegram"></i>
Telegram

</a>

<a href="mailto:support@cntechstore.shop"
class="social-btn">

<i class="fa-solid fa-envelope"></i>
Email

</a>

</div>

</div>



<div class="card">

<h3>
<i class="fa-solid fa-paper-plane"></i>
 ສົ່ງຂໍ້ຄວາມ
</h3>

<form
id="contactForm"
method="POST"
action="contact-save.php">

<input
type="text"
name="name"
class="form-control"
placeholder="ຊື່ - ນາມສະກຸນ">

<input
type="email"
name="email"
class="form-control"
placeholder="Email Address">

<input
type="text"
name="subject"
class="form-control"
placeholder="ຫົວຂໍ້">

<textarea
name="message"
class="form-control"
placeholder="ລາຍລະອຽດຂໍ້ຄວາມ"></textarea>

<button
type="submit"
class="btn-send">

<i class="fa-solid fa-paper-plane"></i>
 ສົ່ງຂໍ້ຄວາມ

</button>

<div id="errorBox"></div>

</form>

</div>



<div class="card">

<h3>
<i class="fa-solid fa-circle-info"></i>
 ສະຖານະລະບົບ
</h3>

<p>🎮 ລະບົບເຕີມເກມ : ພ້ອມໃຊ້ງານ</p>

<br>

<p>💳 ລະບົບຊຳລະເງິນ : ພ້ອມໃຊ້ງານ</p>

<br>

<p>🛠️ Customer Support : Online</p>

</div>

</div>




<div class="footer">
    
    <div class="logo">

CNTECH <span>STORE</span>

    </div>

© <?php echo date("Y"); ?> CN Tech Store

<br>

All Rights Reserved.

</div>

<script>

document
.getElementById("contactForm")
.addEventListener(
"submit",
function(e){

let fields=[
this.name,
this.email,
this.subject,
this.message
];

let error=false;

fields.forEach(item=>{

if(item.value.trim()==""){

item.style.border=
"2px solid #ff3030";

error=true;

}else{

item.style.border=
"1px solid #22c55e";

}

});

if(error){

e.preventDefault();

document
.getElementById("errorBox")
.innerHTML=

`
<div style="
margin-top:10px;
padding:12px;
border-radius:12px;
background:rgba(255,0,0,.15);
border:1px solid rgba(255,0,0,.3);
color:#ff8080;
font-size:14px;
">

<i class="fa-solid fa-circle-exclamation"></i>

 ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບທຸກຊ່ອງ

</div>
`;

}

});

</script>

</body>
</html>