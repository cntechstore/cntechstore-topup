<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../database.php";

if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Database connection error");
}

$sql = "
    SELECT id,name,image,status
    FROM voucher_categories
    ORDER BY id DESC
    LIMIT 12
";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . htmlspecialchars($conn->error));
}

function e($v){
    return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');
}

$base = defined('BASE_URL') ? BASE_URL : '/';

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1,maximum-scale=1">

<meta name="theme-color" content="#050505">

<title>Game Cards & Top-up | CNTECH STORE</title>

<meta name="description"
content="Buy Game Cards and Top-up from CNTECH STORE. Fast, secure and convenient.">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/* =========================
RESET
========================= */

*{
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{
    margin:0;
    background:#050505;
    color:#fff;
    font-family:
        Arial,
        Helvetica,
        sans-serif;
}

/* =========================
NAVBAR
========================= */

.cn-navbar{

    position:sticky;
    top:0;
    z-index:9999;

    height:64px;

    background:rgba(5,5,5,.96);

    border-bottom:1px solid #292929;

    backdrop-filter:blur(12px);
}

.nav-inner{

    max-width:1200px;

    height:100%;

    margin:auto;

    padding:0 18px;

    display:flex;

    align-items:center;

    justify-content:space-between;
}

.logo{

    color:#fff;

    text-decoration:none;

    font-size:22px;

    font-weight:900;

    letter-spacing:-.5px;
}

.logo span{
    color:#ff2020;
}

.nav-menu{

    display:flex;

    align-items:center;

    gap:8px;
}

.nav-menu a{

    color:#aaa;

    text-decoration:none;

    padding:9px 12px;

    border-radius:9px;

    font-size:13px;

    transition:.2s;
}

.nav-menu a:hover,
.nav-menu a.active{

    color:#fff;

    background:#1a1a1a;
}

.nav-menu a.active{
    color:#ff3030;
}

.nav-cart{

    position:relative;

    color:#fff !important;

    font-size:16px;
}

.cart-dot{

    position:absolute;

    top:3px;
    right:3px;

    width:7px;
    height:7px;

    border-radius:50%;

    background:#ff2020;
}

/* =========================
PAGE
========================= */

.page{

    max-width:1200px;

    margin:auto;

    padding:25px 18px 50px;
}

/* =========================
HEADER
========================= */

.page-header{

    margin-bottom:22px;
}

.breadcrumb{

    color:#666;

    font-size:12px;

    margin-bottom:9px;
}

.breadcrumb span{
    color:#ff2020;
}

.page-title{

    margin:0;

    font-size:28px;

    font-weight:900;
}

.page-title i{

    color:#ff2020;

    margin-right:7px;
}

.page-subtitle{

    margin:7px 0 0;

    color:#888;

    font-size:13px;
}

/* =========================
GRID
========================= */

.voucher-grid{

    display:grid;

    grid-template-columns:
        repeat(4,minmax(0,1fr));

    gap:18px;
}

/* =========================
CARD
========================= */

.voucher-card{

    position:relative;

    display:block;

    overflow:hidden;

    text-decoration:none;

    color:#fff;

    background:#111;

    border:1px solid #252525;

    border-radius:16px;

    transition:
        transform .25s,
        border-color .25s,
        box-shadow .25s;
}

.voucher-card:hover{

    transform:translateY(-5px);

    border-color:#ff2020;

    box-shadow:
        0 12px 35px rgba(255,32,32,.16);
}

/* =========================
IMAGE
========================= */

.voucher-image{

    position:relative;

    width:100%;

    aspect-ratio:16/9;

    overflow:hidden;

    background:#080808;
}

.voucher-image img{

    width:100%;

    height:100%;

    display:block;

    object-fit:cover;

    transition:.4s;
}

.voucher-card:hover
.voucher-image img{

    transform:scale(1.05);
}

/* =========================
BADGE
========================= */

.voucher-badge{

    position:absolute;

    top:10px;
    left:10px;

    padding:5px 8px;

    border-radius:7px;

    background:#e51b23;

    color:#fff;

    font-size:9px;

    font-weight:900;

    letter-spacing:.3px;
}

/* =========================
INFO
========================= */

.voucher-info{

    padding:13px;
}

.voucher-name{

    margin:0;

    color:#fff;

    font-size:16px;

    font-weight:800;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;
}

.voucher-desc{

    margin:5px 0 11px;

    color:#777;

    font-size:12px;
}

.voucher-button{

    display:block;

    width:100%;

    padding:9px;

    text-align:center;

    border-radius:9px;

    background:#e51b23;

    color:#fff;

    font-size:12px;

    font-weight:800;
}

.voucher-button i{

    margin-right:4px;
}

/* =========================
DISABLED
========================= */

.voucher-disabled{

    cursor:pointer;

    opacity:.8;
}

.voucher-disabled img{

    filter:grayscale(1);
}

.voucher-overlay{

    position:absolute;

    inset:0;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    text-align:center;

    background:rgba(0,0,0,.74);

    backdrop-filter:blur(3px);
}

.voucher-overlay-icon{

    width:44px;

    height:44px;

    display:flex;

    align-items:center;

    justify-content:center;

    margin-bottom:7px;

    border-radius:50%;

    background:#351010;

    color:#ff3030;

    font-size:19px;
}

.voucher-overlay strong{

    color:#fff;

    font-size:14px;
}

.voucher-overlay span{

    margin-top:4px;

    color:#aaa;

    font-size:11px;
}

.voucher-unavailable{

    display:block;

    padding:9px;

    border-radius:9px;

    background:#222;

    color:#777;

    text-align:center;

    font-size:12px;

    font-weight:bold;
}

/* =========================
EMPTY
========================= */

.voucher-empty{

    padding:55px 20px;

    text-align:center;

    background:#111;

    border:1px solid #252525;

    border-radius:16px;

    color:#777;
}

.voucher-empty i{

    color:#ff2020;

    font-size:35px;
}

/* =========================
FOOTER
========================= */

.cn-footer{

    background:#080808;

    border-top:1px solid #252525;

    padding:35px 18px 20px;
}

.footer-inner{

    max-width:1200px;

    margin:auto;

    display:grid;

    grid-template-columns:
        2fr 1fr 1fr 1fr;

    gap:30px;
}

.footer-logo{

    font-size:21px;

    font-weight:900;
}

.footer-logo span{
    color:#ff2020;
}

.footer-text{

    margin-top:8px;

    max-width:330px;

    color:#777;

    font-size:12px;

    line-height:1.7;
}

.footer-title{

    margin-bottom:10px;

    color:#fff;

    font-size:13px;

    font-weight:800;
}

.footer-links{

    display:flex;

    flex-direction:column;

    gap:7px;
}

.footer-links a{

    color:#777;

    text-decoration:none;

    font-size:12px;
}

.footer-links a:hover{
    color:#ff3030;
}

.footer-bottom{

    max-width:1200px;

    margin:30px auto 0;

    padding-top:18px;

    border-top:1px solid #202020;

    color:#555;

    text-align:center;

    font-size:11px;
}

/* =========================
MOBILE NAV
========================= */

.mobile-nav{

    display:none;
}

/* =========================
TABLET
========================= */

@media(max-width:1000px){

    .voucher-grid{

        grid-template-columns:
            repeat(3,minmax(0,1fr));

    }

    .footer-inner{

        grid-template-columns:
            repeat(2,1fr);

    }
}

/* =========================
MOBILE
========================= */

@media(max-width:700px){

    body{

        padding-bottom:64px;

    }

    .cn-navbar{

        height:58px;

    }

    .nav-inner{

        padding:0 14px;

    }

    .logo{

        font-size:20px;

    }

    .nav-menu{

        display:none;

    }

    .page{

        padding:

            18px
            12px
            35px;

    }

    .page-header{

        margin-bottom:15px;

    }

    .page-title{

        font-size:22px;

    }

    .page-subtitle{

        font-size:12px;

    }

    .voucher-grid{

        grid-template-columns:
            repeat(2,minmax(0,1fr));

        gap:10px;

    }

    .voucher-card{

        border-radius:13px;

    }

    .voucher-info{

        padding:9px;

    }

    .voucher-name{

        font-size:13px;

    }

    .voucher-desc{

        font-size:10px;

        margin:

            4px
            0
            8px;

    }

    .voucher-button,
    .voucher-unavailable{

        padding:8px 4px;

        font-size:10px;

        border-radius:8px;

    }

    .voucher-badge{

        top:6px;
        left:6px;

        padding:4px 6px;

        font-size:8px;

    }

    .voucher-overlay-icon{

        width:38px;

        height:38px;

        font-size:16px;

    }

    .voucher-overlay strong{

        font-size:12px;

    }

    .voucher-overlay span{

        font-size:9px;

    }

    /* FOOTER MOBILE */

    .cn-footer{

        display:none;

    }

    /* BOTTOM NAV */

    .mobile-nav{

        position:fixed;

        left:0;
        right:0;
        bottom:0;

        z-index:9999;

        height:64px;

        display:flex;

        align-items:center;

        justify-content:space-around;

        background:#080808;

        border-top:1px solid #292929;

        box-shadow:
            0 -5px 20px rgba(0,0,0,.5);

    }

    .mobile-nav a{

        min-width:60px;

        display:flex;

        flex-direction:column;

        align-items:center;

        gap:4px;

        color:#777;

        text-decoration:none;

        font-size:10px;

    }

    .mobile-nav i{

        font-size:18px;

    }

    .mobile-nav a.active{

        color:#ff2020;

    }

}

/* =========================
SMALL PHONE
========================= */

@media(max-width:380px){

    .voucher-grid{

        gap:8px;

    }

    .voucher-name{

        font-size:12px;

    }

    .voucher-button,
    .voucher-unavailable{

        font-size:9px;

    }

}

</style>

</head>

<body>

<!-- ================= NAVBAR ================= -->

<header class="cn-navbar">

<div class="nav-inner">

<a
href="<?=e($base)?>"
class="logo"
>
CN<span>TECH</span>
</a>

<nav class="nav-menu">

<a href="<?=e($base)?>">
<i class="fa-solid fa-house"></i>
Home
</a>

<a
href="<?=e($base)?>page/voucher.php"
class="active"
>
<i class="fa-solid fa-ticket"></i>
Game Cards
</a>

<a href="<?=e($base)?>page/game.php">
<i class="fa-solid fa-gamepad"></i>
Games
</a>

<a href="<?=e($base)?>login.php">
<i class="fa-solid fa-user"></i>
Account
</a>

<a
href="<?=e($base)?>cart.php"
class="nav-cart"
>
<i class="fa-solid fa-cart-shopping"></i>
<span class="cart-dot"></span>
</a>

</nav>

</div>

</header>


<!-- ================= CONTENT ================= -->

<main class="page">

<div class="page-header">

<div class="breadcrumb">
CNTECH STORE
<i class="fa-solid fa-chevron-right"></i>
<span>Game Cards</span>
</div>

<h1 class="page-title">

<i class="fa-solid fa-ticket"></i>

Game Cards & Top-up

</h1>

<p class="page-subtitle">

เติมเกมและซื้อ Game Cards ได้ง่าย
รวดเร็ว และปลอดภัย

</p>

</div>


<?php if($result->num_rows === 0): ?>

<div class="voucher-empty">

<i class="fa-regular fa-credit-card"></i>

<br><br>

ยังไม่มีบัตรเติมเงินในขณะนี้

</div>

<?php else: ?>

<div class="voucher-grid">

<?php while($row = $result->fetch_assoc()): ?>

<?php

$id = (int)($row['id'] ?? 0);

$name = e(
    $row['name'] ?? 'Unknown'
);

if(!empty($row['image'])){

    $filename =
        basename($row['image']);

    $image =
        "/admin/uploads/" .
        rawurlencode($filename);

}else{

    $image =
        "/assets/no-image.png";

}

$status = strtolower(
    trim($row['status'] ?? 'inactive')
);

$active =
    ($status !== 'inactive');

$link =
    $base .
    "game/voucher_pd.php?id=" .
    $id;

?>

<?php if($active): ?>

<a
href="<?=e($link)?>"
class="voucher-card"
>

<?php else: ?>

<div
class="voucher-card voucher-disabled"
onclick="showVoucherMaintenance('<?=$name?>')"
>

<?php endif; ?>


<div class="voucher-image">

<img
src="<?=e($image)?>"
alt="<?=$name?>"
loading="lazy"
>

<?php if($active): ?>

<div class="voucher-badge">
AVAILABLE
</div>

<?php else: ?>

<div class="voucher-overlay">

<div class="voucher-overlay-icon">

<i class="fa-solid fa-wrench"></i>

</div>

<strong>
ปิดปรับปรุง
</strong>

<span>
<?=$name?>
</span>

</div>

<?php endif; ?>

</div>


<div class="voucher-info">

<h3 class="voucher-name">
<?=$name?>
</h3>

<p class="voucher-desc">
Game Cards & Top-up
</p>


<?php if($active): ?>

<div class="voucher-button">

<i class="fa-solid fa-cart-shopping"></i>

ซื้อสินค้า

</div>

<?php else: ?>

<div class="voucher-unavailable">

<i class="fa-solid fa-lock"></i>

Unavailable

</div>

<?php endif; ?>

</div>


<?php if($active): ?>

</a>

<?php else: ?>

</div>

<?php endif; ?>


<?php endwhile; ?>

</div>

<?php endif; ?>

</main>


<!-- ================= FOOTER ================= -->

<footer class="cn-footer">

<div class="footer-inner">

<div>

<div class="footer-logo">
CN<span>TECH</span> STORE
</div>

<div class="footer-text">

Computer • Mobile • Parts & Accessories

<br>

Game Top-up, Game Cards and Digital Products.

</div>

</div>


<div>

<div class="footer-title">
SHOP
</div>

<div class="footer-links">

<a href="<?=e($base)?>">
Home
</a>

<a href="<?=e($base)?>page/game.php">
Games
</a>

<a href="<?=e($base)?>page/voucher.php">
Game Cards
</a>

</div>

</div>


<div>

<div class="footer-title">
ACCOUNT
</div>

<div class="footer-links">

<a href="<?=e($base)?>login.php">
Login
</a>

<a href="<?=e($base)?>register.php">
Register
</a>

<a href="<?=e($base)?>forgot-password.php">
Forgot Password
</a>

</div>

</div>


<div>

<div class="footer-title">
SUPPORT
</div>

<div class="footer-links">

<a href="<?=e($base)?>contact.php">
Contact
</a>

<a href="mailto:support@cntechstore.shop">
Email Support
</a>

</div>

</div>

</div>


<div class="footer-bottom">

© <?=date('Y')?> CNTECH STORE.
All rights reserved.

</div>

</footer>


<!-- ================= MOBILE NAV ================= -->

<nav class="mobile-nav">

<a
href="<?=e($base)?>"
>

<i class="fa-solid fa-house"></i>

<span>Home</span>

</a>


<a
href="<?=e($base)?>page/game.php"
>

<i class="fa-solid fa-gamepad"></i>

<span>Games</span>

</a>


<a
href="<?=e($base)?>page/voucher.php"
class="active"
>

<i class="fa-solid fa-ticket"></i>

<span>Cards</span>

</a>


<a
href="<?=e($base)?>cart.php"
>

<i class="fa-solid fa-cart-shopping"></i>

<span>Cart</span>

</a>


<a
href="<?=e($base)?>login.php"
>

<i class="fa-solid fa-user"></i>

<span>Account</span>

</a>

</nav>


<script>

function showVoucherMaintenance(name){

    alert(
        "ปิดปรับปรุง\n\n"+
        name+
        "\n\n"+
        "ระบบ Game Card นี้อยู่ระหว่างการปรับปรุง"
    );

}

</script>

</body>

</html>