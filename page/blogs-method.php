

<?php

require "../config.php";
require "../database.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$search = trim($_GET['search'] ?? '');

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<meta
name="theme-color"
content="#ff0033">

<title>
CNTECH News Center
</title>

<meta
name="description"
content="CNTECH STORE News, Gaming, Technology, Payment and Digital Services">

<meta
property="og:title"
content="CNTECH News Center">

<meta
property="og:image"
content="https://cntechstore.shop/logo.png">

<meta
property="og:url"
content="https://cntechstore.shop/page/blogs-method.php">

<?php include "../cdn.php"; ?>

<link
rel="stylesheet"
href="../style.css?v=<?=time()?>">

<style>

:root{

--primary:#ff0033;
--primary-dark:#c40028;

--bg:#080808;
--card:rgba(255,255,255,.05);

--border:rgba(255,255,255,.08);

--text:#ffffff;
--muted:#bdbdbd;

}

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

background:var(--bg);

color:var(--text);

font-family:
Inter,
Arial,
sans-serif;

}

.news-container{

max-width:1400px;

margin:auto;

padding:15px;

}

.hero{

background:

linear-gradient(
135deg,
#ff0033,
#700018
);

padding:40px 20px;

border-radius:24px;

text-align:center;

box-shadow:
0 10px 40px rgba(255,0,51,.25);

margin-bottom:20px;

}

.hero h1{

font-size:38px;

font-weight:800;

margin-bottom:10px;

}

.hero p{

opacity:.9;

font-size:15px;

}

.search-box{

margin-top:20px;

display:flex;

gap:10px;

}

.search-box input{

flex:1;

height:52px;

padding:20px 15px;

border:none;

outline:none;

border-radius:14px;

background:
rgba(255,255,255,.1);

color:#fff;

}

.search-box button{

width:130px;

border:none;

border-radius:14px;

background:var(--primary);

color:#fff;

font-weight:bold;

cursor:pointer;

}

.section-title{

font-size:24px;

font-weight:700;

margin:25px 0;

}

@media(max-width:768px){

.hero h1{

font-size:28px;

}

.search-box{

flex-direction:column;

}

.search-box button{

width:100%;

height:52px;

}

}

    /* ==========================
CNTECH MOBILE BOTTOM NAVBAR
========================== */

.mobile-navbar{

position:fixed;

left:0;
right:0;
bottom:0;

height:72px;

display:flex;

align-items:center;
justify-content:space-around;

background:
rgba(15,15,15,.96);

backdrop-filter:
blur(20px);

-webkit-backdrop-filter:
blur(20px);

border-top:
1px solid rgba(255,255,255,.08);

z-index:99999;

padding-bottom:
env(safe-area-inset-bottom);

box-shadow:
0 -5px 25px rgba(0,0,0,.35);

}

/* ITEM */

.mobile-navbar .nav-item{

flex:1;

display:flex;

flex-direction:column;

align-items:center;
justify-content:center;

gap:4px;

height:100%;

text-decoration:none;

color:#9ca3af;

font-size:11px;

font-weight:600;

transition:.25s;

}

/* ICON */

.mobile-navbar .nav-item i{

font-size:20px;

transition:.25s;

}

/* TEXT */

.mobile-navbar .nav-item span{

font-size:11px;

line-height:1;

}

/* ACTIVE */

.mobile-navbar .nav-item.active{

color:#ff0033;

}

.mobile-navbar .nav-item.active i{

transform:translateY(-2px);

text-shadow:
0 0 12px rgba(255,0,51,.45);

}

/* HOVER */

.mobile-navbar .nav-item:hover{

color:#ff3355;

}

/* EFFECT */

.mobile-navbar .nav-item::before{

content:"";

position:absolute;

width:0;

height:3px;

top:0;

background:#ff0033;

border-radius:20px;

transition:.25s;

}

.mobile-navbar .nav-item{

position:relative;

}

.mobile-navbar .nav-item.active::before{

width:40px;

}

/* BODY SAFE SPACE */

body{

padding-bottom:90px;

}

/* DESKTOP HIDE */

@media(min-width:992px){

.mobile-navbar{

display:none;

}

body{

padding-bottom:0;

}

}

/* SMALL PHONE */

@media(max-width:480px){

.mobile-navbar{

height:68px;

}

.mobile-navbar .nav-item i{

font-size:18px;

}

.mobile-navbar .nav-item span{

font-size:10px;

}

    }
</style>

</head>

<body>

<div class="mobile-navbar">

<a href="/" class="nav-item">

<i class="fas fa-home"></i>

<span>Home</span>

</a>

<a href="/games/" class="nav-item">

<i class="fas fa-gamepad"></i>

<span>Games</span>

</a>

<a href="/page/blogs-method.php" class="nav-item active">

<i class="fas fa-newspaper"></i>

<span>News</span>

</a>

<a href="/cart.php" class="nav-item">

<i class="fas fa-shopping-cart"></i>

<span>Cart</span>

</a>

<a href="/account.php" class="nav-item">

<i class="fas fa-user"></i>

<span>Account</span>

</a>

    </div>

<div class="news-container">

<div class="hero">

<h1>
CNTECH NEWS CENTER
</h1>

<p>
Technology • Gaming • Payment • Digital Platform
</p>

<form
method="GET"
class="search-box">

<input
type="text"
name="search"
placeholder="ค้นหาบทความ..."
value="<?=htmlspecialchars($search)?>">

<button type="submit">

ค้นหา

</button>

</form>

</div>

<h2 class="section-title">

Latest Articles

</h2>

<!--Part 2/5 — Grid ข่าว + Query ดึงบทความ + Blog Card -->

<?php

if($search != ""){

$stmt = $conn->prepare("

SELECT *
FROM blogs

WHERE status='published'

AND (

title LIKE ?
OR content LIKE ?
OR category LIKE ?
OR tags LIKE ?

)

ORDER BY id DESC

LIMIT 120

");

$key = "%".$search."%";

$stmt->bind_param(
"ssss",
$key,
$key,
$key,
$key
);

}else{

$stmt = $conn->prepare("

SELECT *
FROM blogs

WHERE status='published'

ORDER BY id DESC

LIMIT 120

");

}

$stmt->execute();

$result = $stmt->get_result();

?>

<style>

.news-grid{

display:grid;

grid-template-columns:
repeat(4,1fr);

gap:18px;

}

.news-card{

background:
var(--card);

backdrop-filter:
blur(12px);

border:
1px solid var(--border);

border-radius:20px;

overflow:hidden;

transition:.25s;

}

.news-card:hover{

transform:
translateY(-5px);

}

.news-image{

height:220px;

overflow:hidden;

background:#111;

}

.news-image img{

width:100%;
height:100%;

object-fit:cover;

display:block;

}

.news-content{

padding:15px;

}

.news-category{

display:inline-flex;

align-items:center;

padding:6px 12px;

border-radius:999px;

background:
rgba(255,0,51,.15);

border:
1px solid rgba(255,0,51,.25);

color:#ff3355;

font-size:12px;

margin-bottom:10px;

}

.news-date{

font-size:12px;

color:#999;

margin-bottom:10px;

}

.news-title{

font-size:18px;

font-weight:700;

line-height:1.5;

margin-bottom:10px;

color:#fff;

}

.news-desc{

font-size:14px;

line-height:1.6;

color:#cfcfcf;

min-height:72px;

}

@media(max-width:1100px){

.news-grid{

grid-template-columns:
repeat(2,1fr);

}

}

@media(max-width:768px){

.news-grid{

grid-template-columns:1fr;

}

.news-image{

height:200px;

}

}

</style>

<div class="news-grid">

<?php

if($result && $result->num_rows > 0){

while($row = $result->fetch_assoc()){

$id = (int)$row['id'];

$title =
htmlspecialchars(
$row['title']
);

$desc =
htmlspecialchars(

mb_substr(

strip_tags(
$row['content']
),

0,
120,
"UTF-8"

)

);

$image =

!empty($row['image'])

?


"/admin/uploads/blogs/".
$row['image']

:

"/admin/uploads/no-image.png";

$date =
date(
"d M Y",
strtotime(
$row['created_at']
)
);

$link =

"/blog-detail.php?id=".
$id;

?>

<article class="news-card">

<div class="news-image">

<img
src="<?=$image?>"
alt="<?=$title?>"
loading="lazy">

</div>

<div class="news-content">

<div class="news-category">

CNTECH NEWS

</div>

<div class="news-date">

<?=$date?>

</div>

<div class="news-title">

<?=$title?>

</div>

<div class="news-desc">

<?=$desc?>...

</div>



<a
class="read-btn"
href="<?=$link?>">

อ่านบทความ

</a>

</div>

</article>

<?php

}

}else{

?>

<div class="empty-state">

<div class="empty-icon">

📰

</div>

<h3>

ไม่พบบทความ

</h3>

<p>

ยังไม่มีบทความที่พร้อมแสดงผล
หรือไม่พบคำค้นหาที่ต้องการ

</p>

<a
href="blogs-method.php"
class="empty-btn">

ดูบทความทั้งหมด

</a>

</div>

<?php

}

?>

</div>

<style>

.read-btn{

display:flex;

justify-content:center;
align-items:center;

margin-top:15px;

height:46px;

border-radius:14px;

background:

linear-gradient(
135deg,
#ff0033,
#c40028
);

color:#fff;

font-weight:700;

text-decoration:none;

transition:.25s;

}

.read-btn:hover{

transform:
translateY(-2px);

box-shadow:
0 8px 20px rgba(255,0,51,.25);

}

.empty-state{

grid-column:
1 / -1;

text-align:center;

padding:80px 20px;

background:
rgba(255,255,255,.04);

border:
1px solid rgba(255,255,255,.08);

border-radius:24px;

}

.empty-icon{

font-size:60px;

margin-bottom:15px;

}

.empty-state h3{

font-size:24px;

margin-bottom:10px;

}

.empty-state p{

color:#aaa;

margin-bottom:20px;

}

.empty-btn{

display:inline-flex;

justify-content:center;
align-items:center;

padding:12px 24px;

border-radius:14px;

background:#ff0033;

color:#fff;

text-decoration:none;

font-weight:700;

}

.ads-box{

margin-top:30px;

border-radius:20px;

overflow:hidden;

background:
rgba(255,255,255,.03);

border:
1px solid rgba(255,255,255,.08);

padding:10px;

}

</style>

<div class="ads-box">

<amp-ad
width="100vw"
height="320"

type="adsense"

data-ad-client="ca-pub-9543860279937476"

data-ad-slot="9762501020"

data-auto-format="rspv"

data-full-width="">

<div overflow=""></div>

</amp-ad>

</div>


<!--Part 4/5 — Featured News + Trending News + Blog Statistics-->

<?php

$totalBlogs = 0;

$countQuery = $conn->query("
SELECT COUNT(*) total
FROM blogs
WHERE status='published'
");

if($countQuery){

$countData =
$countQuery->fetch_assoc();

$totalBlogs =
(int)$countData['total'];

}

?>

<div class="stats-section">

<div class="stat-card">

<div class="stat-number">

<?=$totalBlogs?>

</div>

<div class="stat-label">

Published Articles

</div>

</div>

<div class="stat-card">

<div class="stat-number">

24/7

</div>

<div class="stat-label">

News Updates

</div>

</div>

<div class="stat-card">

<div class="stat-number">

CN

</div>

<div class="stat-label">

Tech Platform

</div>

</div>

</div>

<div class="featured-section">

<h2 class="section-title">

Featured News

</h2>

<div class="featured-box">

<div class="featured-overlay">

<h3>

CNTECH STORE NEWS CENTER

</h3>

<p>

ติดตามข่าวสารเกมออนไลน์
เทคโนโลยี
ระบบเติมเกม
การชำระเงิน
และอัปเดตบริการล่าสุด

</p>

</div>

</div>

</div>

<div class="trending-section">

<h2 class="section-title">

Trending Topics

</h2>

<div class="trending-tags">

<a href="?search=MLBB">

MLBB

</a>

<a href="?search=Free Fire">

Free Fire

</a>

<a href="?search=PUBG">

PUBG Mobile

</a>

<a href="?search=Honor of Kings">

Honor of Kings

</a>

<a href="?search=Technology">

Technology

</a>

<a href="?search=Payment">

Payment

</a>

<a href="?search=CNTECH">

CNTECH

</a>

</div>

</div>

<style>

.stats-section{

display:grid;

grid-template-columns:
repeat(3,1fr);

gap:15px;

margin-top:30px;

}

.stat-card{

background:
rgba(255,255,255,.05);

backdrop-filter:
blur(12px);

border:
1px solid rgba(255,255,255,.08);

border-radius:20px;

padding:20px;

text-align:center;

}

.stat-number{

font-size:30px;

font-weight:800;

color:#ff0033;

margin-bottom:5px;

}

.stat-label{

color:#bbb;

font-size:14px;

}

.featured-section{

margin-top:40px;

}

.featured-box{

height:300px;

border-radius:24px;

overflow:hidden;

position:relative;

background:

linear-gradient(
135deg,
#ff0033,
#5a0012
);

}

.featured-overlay{

position:absolute;

inset:0;

display:flex;

flex-direction:column;

justify-content:center;

padding:40px;

}

.featured-overlay h3{

font-size:38px;

font-weight:800;

margin-bottom:15px;

}

.featured-overlay p{

max-width:650px;

line-height:1.8;

font-size:15px;

color:rgba(255,255,255,.9);

}

.trending-section{

margin-top:40px;

}

.trending-tags{

display:flex;

flex-wrap:wrap;

gap:12px;

}

.trending-tags a{

padding:10px 18px;

border-radius:999px;

text-decoration:none;

background:
rgba(255,0,51,.12);

border:
1px solid rgba(255,0,51,.25);

color:#ff3355;

font-weight:600;

}

@media(max-width:768px){

.stats-section{

grid-template-columns:1fr;

}

.featured-box{

height:220px;

}

.featured-overlay{

padding:25px;

}

.featured-overlay h3{

font-size:24px;

}

}

   .blog-footer{

margin-top:40px;

padding:30px 20px 100px;

background:#0f0f0f;

border-top:
1px solid rgba(255,255,255,.08);

text-align:center;

}

.footer-logo{

font-size:24px;

font-weight:800;

color:#ff0033;

margin-bottom:10px;

}

.footer-text{

color:#aaa;

font-size:14px;

margin-bottom:20px;

}

.footer-links{

display:flex;

justify-content:center;

flex-wrap:wrap;

gap:15px;

margin-bottom:20px;

}

.footer-links a{

color:#fff;

text-decoration:none;

font-size:14px;

}

.footer-links a:hover{

color:#ff0033;

}

.footer-copy{

color:#777;

font-size:12px;

    } 
    
</style>



</div>



<footer class="blog-footer">

<div class="footer-logo">

CNTECH NEWS

</div>

<div class="footer-text">

Technology • Gaming • Payment • Digital Platform

</div>

<div class="footer-links">

<a href="/">Home</a>

<a href="/games/">Games</a>

<a href="/voucher/">Voucher</a>

<a href="/page/blogs-method.php">News</a>

<a href="/account.php">Account</a>

</div>

<div class="footer-copy">

© <?php echo date('Y'); ?> CNTECH STORE

All Rights Reserved

</div>

    </footer>

<script src="../app.js?v=<?=time()?>"></script>

    <script>

const currentPath =
window.location.pathname;

document
.querySelectorAll('.mobile-navbar .nav-item')
.forEach(item=>{

const href =
item.getAttribute('href');

if(
currentPath.includes(href) &&
href !== '/'
){

item.classList.add('active');

}

});

    </script>
    
<script>

/* =========================
AUTO SEARCH
========================= */

let searchTimer;

function autoSearch(){

clearTimeout(searchTimer);

searchTimer = setTimeout(function(){

document
.getElementById("searchForm")
.submit();

},600);

}

/* =========================
DROPDOWN
========================= */

function toggleDropdown(el){

const parent =
el.parentElement;

parent.classList.toggle(
"active"
);

}

/* =========================
LAZY ANIMATION
========================= */

document
.addEventListener(
"DOMContentLoaded",
function(){

const cards =
document.querySelectorAll(
".news-card"
);

const observer =
new IntersectionObserver(

(entries)=>{

entries.forEach(

(entry)=>{

if(
entry.isIntersecting
){

entry.target.style.opacity="1";

entry.target.style.transform=
"translateY(0)";

}

}

);

},

{
threshold:0.1
}

);

cards.forEach(card=>{

card.style.opacity="0";

card.style.transform=
"translateY(30px)";

card.style.transition=
".4s ease";

observer.observe(card);

});

});

/* =========================
IMAGE FALLBACK
========================= */

document
.querySelectorAll(
".news-image img"
)

.forEach(img=>{

img.onerror = function(){

this.src =
"/admin/uploads/no-image.png";

};

});

</script>

</body>
</html>