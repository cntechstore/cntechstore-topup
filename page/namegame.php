<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require "../database.php";
require_once "../config.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}


/*
====================================
GET GAME ID
====================================
*/

$id = (int)($_GET['id'] ?? 0);

if($id <= 0){

    die("Game not found");

}


/*
====================================
GET GAME
====================================
*/

$stmt = $conn->prepare("
SELECT *
FROM games
WHERE id=?
LIMIT 1
");

$stmt->bind_param(
"i",
$id
);

$stmt->execute();

$game =
$stmt
->get_result()
->fetch_assoc();


if(!$game){

    die("Game not found");

}



/*
====================================
VIEW SYSTEM
====================================
*/

if(
!isset(
$_SESSION['view_game_'.$id]
)
){

$ip =
$_SERVER['REMOTE_ADDR']
?? '';

$user_agent =
$_SERVER['HTTP_USER_AGENT']
?? '';



$view =
$conn->prepare("
INSERT INTO game_views
(
game_id,
ip_address,
user_agent
)
VALUES (?,?,?)
");


$view->bind_param(
"iss",
$id,
$ip,
$user_agent
);

$view->execute();
$view->close();



$update =
$conn->prepare("
UPDATE games
SET
views = views + 1,
last_played = NOW()
WHERE id=?
");

$update->bind_param(
"i",
$id
);

$update->execute();
$update->close();



$_SESSION['view_game_'.$id]
=
true;

}



/*
====================================
PRODUCT LIST
====================================
*/

$productStmt =
$conn->prepare("
SELECT *
FROM game_products
WHERE game_id=?
AND status='active'
ORDER BY price ASC
");

$productStmt->bind_param(
"i",
$id
);

$productStmt->execute();

$products =
$productStmt
->get_result();



/*
====================================
CART COUNT
====================================
*/

$cart_count = 0;

if(
isset($_SESSION['cart'])
&&
is_array($_SESSION['cart'])
){

$cart_count =
count($_SESSION['cart']);

}

?>
<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">
<meta name="theme-color" content="#ff0000">
<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>
<?=htmlspecialchars($game['name'])?>
 | CNTECH STORE
</title>

<meta
name="description"
content="<?=htmlspecialchars($game['name'])?> Top-up Service">

<link rel="canonical"
href="<?=BASE_URL?>/game/namegame.php?id=<?=$id?>">

<!-- FONT AWESOME -->

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/*
====================================
RESET
====================================
*/

*{

margin:0;
padding:0;

box-sizing:border-box;

}

:root{

--bg:#050505;

--card:
rgba(255,255,255,.05);

--border:
rgba(255,255,255,.08);

--red:#e50914;

--red-dark:#8b0000;

--text:#ffffff;

--shadow:
0 12px 30px rgba(0,0,0,.35);

}


body{

background:
linear-gradient(
180deg,
#050505,
#0a0a0a,
#120000
);

font-family:
Arial,
sans-serif;

color:#fff;

padding-bottom:90px;

}



/*
====================================
HEADER
====================================
*/

.app-header{

position:sticky;

top:0;

z-index:999;

height:70px;

display:flex;

align-items:center;

justify-content:space-between;

padding:0 18px;

background:
rgba(0,0,0,.75);

backdrop-filter:
blur(18px);

border-bottom:
1px solid var(--border);

}



.logo{

font-size:22px;

font-weight:900;

letter-spacing:1px;

}


.logo span{

color:var(--red);

}



.back-btn{

width:45px;
height:45px;

display:flex;

align-items:center;
justify-content:center;

border-radius:14px;

text-decoration:none;

background:
rgba(229,9,20,.15);

border:
1px solid rgba(229,9,20,.4);

color:#fff;

}



/*
====================================
CONTAINER
====================================
*/

.game-container{

max-width:750px;

margin:auto;

padding:15px;

}



/*
====================================
GAME HEADER
====================================
*/

.game-header{

display:flex;

gap:18px;

align-items:center;

background:
var(--card);

backdrop-filter:
blur(15px);

border:
1px solid var(--border);

border-radius:24px;

padding:18px;

box-shadow:
var(--shadow);

}



.game-cover{

width:110px;
height:110px;

object-fit:cover;

border-radius:22px;

border:
3px solid var(--red);

}



.game-info h1{

font-size:24px;

margin-bottom:8px;

}



.game-meta{

display:flex;

gap:12px;

flex-wrap:wrap;

font-size:14px;

color:#ddd;

}

.game-meta span{

background:
rgba(255,255,255,.06);

padding:6px 10px;

border-radius:10px;

}

</style>

</head>

<body>

<!-- HEADER -->

<header class="app-header">

<div class="logo">
CNTECH
<span>STORE</span>
</div>

<a
href="<?=BASE_URL?>page/game_topup.php"
class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>

</header>

<main class="game-container">

<!-- GAME HEADER -->

<section class="game-header">

<img
src="/admin/uploads/<?=htmlspecialchars($game['icon'])?>"
class="game-cover"
loading="lazy">

<div class="game-info">

<h1>

<?=htmlspecialchars($game['name'])?>

</h1>

<div class="game-meta">

<span>

<i class="fa-solid fa-eye"></i>

<?=number_format(
$game['views'] ?? 0
)?>

Views

</span>

<span>

<i class="fa-solid fa-fire"></i>

<?=number_format(
$game['play_count'] ?? 0
)?>

Played

</span>

<span>

<?php
echo
$game['status']=="active"
?
' Active'
:
' Maintenance';
?>

</span>

</div>

</div>

    </section>
    
    <style>

/*
====================================
SECTION
====================================
*/

.game-section{

margin-top:20px;

background:
rgba(255,255,255,.05);

backdrop-filter:
blur(15px);

border:
1px solid rgba(255,255,255,.08);

border-radius:22px;

padding:18px;

box-shadow:
0 12px 25px rgba(0,0,0,.25);

}



.game-section h2{

font-size:20px;

margin-bottom:15px;

font-weight:800;

}



.game-section h2 i{

color:#e50914;

margin-right:8px;

}



/*
====================================
PLAYER
====================================
*/

.player-row{

display:flex;

gap:12px;

}



.player-input{

flex:1;

position:relative;

}



.player-input input{

width:100%;

padding:15px 50px 15px 15px;

border-radius:16px;

border:
1px solid rgba(255,255,255,.08);

background:
rgba(255,255,255,.05);

color:#fff;

font-size:15px;

transition:.25s;

}



.player-input input:focus{

outline:none;

border-color:#e50914;

box-shadow:
0 0 15px rgba(229,9,20,.25);

}



.player-input input::placeholder{

color:#bbb;

}



.help{

position:absolute;

right:12px;

top:50%;

transform:translateY(-50%);

width:28px;

height:28px;

border-radius:50%;

background:#e50914;

display:flex;

justify-content:center;

align-items:center;

font-weight:bold;

cursor:pointer;

color:white;

}



/*
====================================
CHECK STATUS
====================================
*/

.check-status{

margin-top:15px;

padding:12px;

border-radius:14px;

font-size:14px;

background:
rgba(255,255,255,.04);

min-height:46px;

display:flex;

align-items:center;

}



/*
====================================
MODAL
====================================
*/

.modal-help{

display:none;

position:fixed;

top:0;
left:0;

width:100%;
height:100%;

background:
rgba(0,0,0,.75);

backdrop-filter:
blur(5px);

z-index:99999;

justify-content:center;

align-items:center;

}



.modal-content{

width:90%;

max-width:420px;

background:white;

border-radius:24px;

padding:25px;

color:#111;

text-align:center;

}



.modal-content h3{

margin-bottom:12px;

}



.modal-content p{

line-height:1.8;

color:#555;

}



.closehelp{

margin-top:15px;

padding:12px 25px;

border:none;

border-radius:14px;

background:
linear-gradient(
135deg,
#ff2020,
#9b0000
);

color:white;

font-weight:bold;

cursor:pointer;

}



/*
====================================
MOBILE
====================================
*/

@media(max-width:600px){

.player-row{

flex-direction:column;

}

}

</style>





<?php

$gameName =
strtoupper($game['name']);

?>



<!-- PLAYER SECTION -->

<section class="game-section">

<h2>

<i class="fa-solid fa-user"></i>

Player Information

</h2>



<?php

if(

in_array(

$gameName,

[

"MLBB",
"MOBILE LEGENDS"

]

)

){

?>



<div class="player-row">

<div class="player-input">

<input

id="player_id"

type="text"

placeholder="User ID">

<div

class="help"

onclick="openHelp()">

?

</div>

</div>



<div class="player-input">

<input

id="server_id"

type="text"

placeholder="Server ID">

<div

class="help"

onclick="openHelp()">

?

</div>

</div>

</div>



<?php

}else{

?>



<div class="player-input">

<input

id="player_id"

type="text"

placeholder="Player ID">

<div

class="help"

onclick="openHelp()">

?

</div>

</div>



<?php

}

?>




<div

id="playerStatus"

class="check-status">

<i class="fa-solid fa-circle-info"></i>

&nbsp;

กรอกข้อมูลผู้เล่นเพื่อเริ่มตรวจสอบ

</div>



</section>







<!-- HELP MODAL -->

<div

id="helpModal"

class="modal-help">



<div class="modal-content">

<h3>

<i class="fa-solid fa-circle-question"></i>

วิธีดู Player ID

</h3>

<p>

เปิดเกม

→ ไปที่โปรไฟล์

→ คัดลอก Player ID

และ Server ID (ถ้ามี)

</p>



<button

class="closehelp"

onclick="closeHelp()">

ปิด

</button>

</div>

</div>


<section class="game-section">

<h2>
<i class="fa-solid fa-gem"></i>
 Select Package
</h2>

<div class="topup-grid">

<?php

if($products && $products->num_rows > 0){

while($row = $products->fetch_assoc()){

$image = "/admin/uploads/" .
htmlspecialchars($row['image']);

if(
empty($row['image']) ||
!file_exists(
$_SERVER['DOCUMENT_ROOT'] .
"/admin/uploads/" .
$row['image']
)
){
    $image = "/assets/no-image.png";
}

$price = (float)$row['price'];

$discount = (int)($row['discount'] ?? 0);

$finalPrice = $price;

if($discount > 0){

$finalPrice =
$price - ($price * $discount / 100);

}

?>

<div class="topup-card"

onclick="
selectProduct(
this,
<?=$row['id']?>,
'<?=htmlspecialchars($row['title'],ENT_QUOTES)?>',
<?=$finalPrice?>
)
">

<div class="topup-image">

    <img
src="<?=$image?>"
alt="<?=htmlspecialchars($row['title'])?>"
loading="lazy">

</div>

<div class="topup-info">

<h3>

<?=htmlspecialchars($row['title'])?>

</h3>

<?php if($discount > 0){ ?>

<div class="old-price">

<?=number_format($price)?> ₭

</div>

<div class="price discount-price">

<?=number_format($finalPrice)?> ₭

<span class="discount-badge">

-<?=$discount?>%

</span>

</div>

<div class="save-price">

ประหยัด
<?=number_format($price-$finalPrice)?> ₭

</div>

<?php }else{ ?>

<div class="price">

<?=number_format($price)?> ₭

</div>

<?php } ?>

<button type="button">

<i class="fa-solid fa-cart-plus"></i>
 เลือกแพ็กเกจ

</button>

</div>

</div>

<?php

}

}else{

echo '

<div class="empty-box">

<i class="fa-solid fa-box-open"></i>

ยังไม่มีสินค้า

</div>

';

}

?>

</div>

    </section>
    
    <section class="game-section">

<h2>
<i class="fa-solid fa-envelope"></i>
 Contact Information
</h2>

<div class="contact-box">

<label>
<i class="fa-solid fa-at"></i>
 Email Address
</label>

<input
type="email"
id="email"
class="email-input"
placeholder="example@gmail.com"
>

<p class="contact-note">

ระบบจะส่งรายละเอียดคำสั่งซื้อไปยังอีเมลนี้

</p>

</div>

    </section>

    <section class="game-section">

<h2>
<i class="fa-solid fa-file-invoice"></i>
 Order Summary
</h2>

<div class="summary-card">

<div class="summary-row">

<span>
เกม
</span>

<span>

<?=htmlspecialchars($game['name'])?>

</span>

</div>

<div class="summary-row">

<span>
แพ็กเกจ
</span>

<span id="selected">

ยังไม่ได้เลือก

</span>

</div>

<div class="summary-row">

<span>
Player ID
</span>

<span id="show-player-id">

-

</span>

</div>

<?php if(
strtoupper($game['name'])=="MLBB"
){ ?>

<div class="summary-row">

<span>
Server ID
</span>

<span id="show-server-id">

-

</span>

</div>

<?php } ?>

<hr>

<div class="summary-total">

<span>

ยอดชำระ

</span>

<b id="total">

0 ₭

</b>

</div>

</div>

<div
id="formError"
class="form-error">

</div>

<button
id="payBtn"
class="pay-btn"
onclick="goPayment()">

<i class="fa-solid fa-credit-card"></i>

ดำเนินการต่อ

</button>

    </section>

<script>

    <style>
    
/*
====================================
HELP MODAL
====================================
*/

function openHelp(){

document
.getElementById(
"helpModal"
)
.style.display="flex";

}



function closeHelp(){

document
.getElementById(
"helpModal"
)
.style.display="none";

}



document
.getElementById(
"helpModal"
)
.addEventListener(

"click",

function(e){

if(e.target===this){

closeHelp();

}

}

);

    </script>
    
    <style>

/* =====================================================
   CNTECH STORE
   ANCIENT MMO / MOBA GAME UI
   LOL 2013 INSPIRED
   MAGIC • FIRE • GLASS • RPG
===================================================== */

:root{

    --red:#ff2020;
    --red2:#a40000;
    --gold:#ffd76a;
    --gold2:#ff9d00;

    --glass:
        rgba(255,255,255,.055);

    --glass-dark:
        rgba(5,5,8,.72);

    --border:
        rgba(255,255,255,.12);

    --magic:
        rgba(255,40,40,.65);

    --shadow:
        0 20px 60px
        rgba(0,0,0,.65);

}


/* =====================================================
   GLOBAL
===================================================== */

*{
    box-sizing:border-box;
    margin:0;
    padding:0;
}


html{
    scroll-behavior:smooth;
}


body{

    min-height:100vh;

    color:#fff;

    font-family:
        Arial,
        "Noto Sans Thai",
        sans-serif;

    background:

        radial-gradient(
            circle at 50% -10%,
            rgba(255,0,0,.18),
            transparent 35%
        ),

        radial-gradient(
            circle at 10% 40%,
            rgba(255,170,0,.06),
            transparent 30%
        ),

        radial-gradient(
            circle at 90% 70%,
            rgba(255,0,0,.08),
            transparent 30%
        ),

        linear-gradient(
            180deg,
            #020204 0%,
            #080808 45%,
            #120202 100%
        );

    overflow-x:hidden;

}


/* =====================================================
   MAGICAL BACKGROUND
===================================================== */

body::before{

    content:"";

    position:fixed;

    inset:-50%;

    pointer-events:none;

    z-index:-2;

    background:

        conic-gradient(
            from 0deg,
            transparent,
            rgba(255,0,0,.08),
            transparent,
            rgba(255,190,50,.05),
            transparent
        );

    animation:
        cntechMagicRotate
        25s linear infinite;

}


body::after{

    content:"";

    position:fixed;

    inset:0;

    pointer-events:none;

    z-index:-1;

    background:

        radial-gradient(
            circle at 50% 100%,
            rgba(255,30,0,.16),
            transparent 35%
        );

    animation:
        cntechGlowPulse
        5s ease-in-out infinite;

}


@keyframes cntechMagicRotate{

    from{
        transform:rotate(0deg);
    }

    to{
        transform:rotate(360deg);
    }

}


@keyframes cntechGlowPulse{

    0%,100%{
        opacity:.35;
    }

    50%{
        opacity:.8;
    }

}


/* =====================================================
   HEADER
===================================================== */

.app-header{

    position:sticky;

    top:0;

    z-index:999;

    height:68px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 18px;

    background:

        linear-gradient(
            180deg,
            rgba(5,5,8,.94),
            rgba(5,5,8,.72)
        );

    backdrop-filter:
        blur(18px);

    -webkit-backdrop-filter:
        blur(18px);

    border-bottom:
        1px solid
        rgba(255,40,40,.25);

    box-shadow:

        0 5px 30px
        rgba(0,0,0,.65);

}


.logo{

    position:relative;

    font-size:22px;

    font-weight:1000;

    letter-spacing:2px;

    text-shadow:

        0 0 10px
        rgba(255,255,255,.25);

}


.logo span{

    color:var(--red);

    text-shadow:

        0 0 10px
        rgba(255,20,20,.8),

        0 0 25px
        rgba(255,0,0,.45);

}


.back-btn{

    width:44px;
    height:44px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:13px;

    color:#fff;

    text-decoration:none;

    background:

        linear-gradient(
            145deg,
            rgba(255,40,40,.20),
            rgba(70,0,0,.35)
        );

    border:
        1px solid
        rgba(255,50,50,.45);

    box-shadow:

        inset 0 0 15px
        rgba(255,0,0,.12),

        0 0 15px
        rgba(255,0,0,.15);

    transition:.25s;

}


.back-btn:hover{

    transform:
        translateX(-3px)
        scale(1.05);

    box-shadow:

        0 0 25px
        rgba(255,30,30,.55);

}


/* =====================================================
   MAIN CONTAINER
===================================================== */

.game-container{

    position:relative;

    max-width:850px;

    margin:auto;

    padding:
        22px 15px 100px;

}


/* =====================================================
   GAME HEADER
===================================================== */

.game-header{

    position:relative;

    overflow:hidden;

    display:flex;

    align-items:center;

    gap:18px;

    padding:20px;

    border-radius:24px;

    background:

        linear-gradient(
            135deg,
            rgba(255,255,255,.09),
            rgba(255,255,255,.025)
        );

    border:
        1px solid
        rgba(255,255,255,.14);

    backdrop-filter:
        blur(20px);

    -webkit-backdrop-filter:
        blur(20px);

    box-shadow:
        var(--shadow);

}


/* MAGIC BORDER */

.game-header::before{

    content:"";

    position:absolute;

    inset:-2px;

    border-radius:26px;

    background:

        conic-gradient(
            from 0deg,
            transparent,
            rgba(255,30,30,.7),
            transparent,
            rgba(255,210,80,.45),
            transparent
        );

    animation:
        borderMagic
        6s linear infinite;

    z-index:-1;

}


@keyframes borderMagic{

    to{
        transform:rotate(360deg);
    }

}


/* LIGHT SWEEP */

.game-header::after{

    content:"";

    position:absolute;

    top:-100%;

    left:-50%;

    width:40%;

    height:300%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.12),
            transparent
        );

    transform:rotate(20deg);

    animation:
        glassSweep
        5s ease-in-out infinite;

}


@keyframes glassSweep{

    0%{
        left:-60%;
    }

    60%,100%{
        left:130%;
    }

}


/* =====================================================
   GAME COVER
===================================================== */

.game-cover{

    position:relative;

    width:110px;
    height:110px;

    flex-shrink:0;

    object-fit:cover;

    border-radius:22px;

    border:
        2px solid
        rgba(255,70,70,.8);

    box-shadow:

        0 0 0 3px
        rgba(255,0,0,.08),

        0 0 25px
        rgba(255,0,0,.45),

        inset 0 0 20px
        rgba(255,0,0,.3);

    animation:
        coverGlow
        3s ease-in-out infinite;

}


@keyframes coverGlow{

    0%,100%{
        box-shadow:
            0 0 0 3px rgba(255,0,0,.08),
            0 0 20px rgba(255,0,0,.35);
    }

    50%{
        box-shadow:
            0 0 0 4px rgba(255,180,50,.15),
            0 0 35px rgba(255,50,20,.7);
    }

}


/* =====================================================
   GAME INFO
===================================================== */

.game-info{

    position:relative;

    z-index:2;

}


.game-info h1{

    font-size:27px;

    font-weight:1000;

    margin-bottom:10px;

    text-shadow:

        0 2px 10px
        #000;

}


.game-meta{

    display:flex;

    flex-wrap:wrap;

    gap:8px;

}


.game-meta span{

    padding:
        7px 11px;

    border-radius:10px;

    background:
        rgba(255,255,255,.06);

    border:
        1px solid
        rgba(255,255,255,.08);

    color:#ddd;

    font-size:13px;

    box-shadow:
        inset 0 0 10px
        rgba(255,255,255,.02);

}


/* =====================================================
   RPG SECTION
===================================================== */

.game-section{

    position:relative;

    overflow:hidden;

    margin-top:20px;

    padding:20px;

    border-radius:22px;

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.065),
            rgba(0,0,0,.32)
        );

    border:
        1px solid
        rgba(255,255,255,.11);

    backdrop-filter:
        blur(18px);

    -webkit-backdrop-filter:
        blur(18px);

    box-shadow:

        0 18px 50px
        rgba(0,0,0,.45),

        inset 0 0 30px
        rgba(255,0,0,.025);

}


/* MAGIC TOP LINE */

.game-section::before{

    content:"";

    position:absolute;

    top:0;
    left:10%;

    width:80%;
    height:1px;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,50,50,.8),
            rgba(255,215,100,.8),
            rgba(255,50,50,.8),
            transparent
        );

    box-shadow:

        0 0 12px
        rgba(255,40,40,.7);

}


/* FLOATING LIGHT */

.game-section::after{

    content:"";

    position:absolute;

    width:100px;
    height:100px;

    right:-50px;
    top:-50px;

    border-radius:50%;

    background:

        radial-gradient(
            circle,
            rgba(255,30,30,.16),
            transparent 70%
        );

    animation:
        orbFloat
        5s ease-in-out infinite;

}


@keyframes orbFloat{

    0%,100%{
        transform:
            translate(0,0)
            scale(1);
    }

    50%{
        transform:
            translate(-30px,30px)
            scale(1.4);
    }

}


.game-section h2{

    position:relative;

    z-index:2;

    margin-bottom:18px;

    font-size:19px;

    font-weight:900;

    letter-spacing:.3px;

}


.game-section h2 i{

    color:var(--red);

    margin-right:8px;

    text-shadow:

        0 0 10px
        rgba(255,0,0,.8);

}


/* =====================================================
   INPUT
===================================================== */

.player-input input,
.email-input{

    width:100%;

    height:54px;

    padding:
        0 50px 0 16px;

    border-radius:14px;

    outline:none;

    color:#fff;

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.08),
            rgba(0,0,0,.35)
        );

    border:
        1px solid
        rgba(255,255,255,.12);

    box-shadow:

        inset 0 0 15px
        rgba(0,0,0,.3);

    transition:.3s;

}


.player-input input:focus,
.email-input:focus{

    border-color:
        rgba(255,40,40,.8);

    box-shadow:

        0 0 0 2px
        rgba(255,0,0,.08),

        0 0 25px
        rgba(255,0,0,.25),

        inset 0 0 20px
        rgba(255,0,0,.04);

}


/* =====================================================
   HELP BUTTON
===================================================== */

.help{

    background:

        radial-gradient(
            circle,
            #ff4a4a,
            #8b0000
        ) !important;

    border:
        1px solid
        rgba(255,150,150,.55);

    box-shadow:

        0 0 15px
        rgba(255,0,0,.55);

    transition:.2s;

}


.help:hover{

    transform:
        translateY(-50%)
        scale(1.15);

    box-shadow:

        0 0 25px
        rgba(255,30,30,.9);

}


/* =====================================================
   TOPUP GRID
===================================================== */

.topup-grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:15px;

}


/* =====================================================
   TOPUP CARD
===================================================== */

.topup-card{

    position:relative;

    overflow:hidden;

    border-radius:20px;

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.08),
            rgba(0,0,0,.42)
        );

    border:
        1px solid
        rgba(255,255,255,.12);

    cursor:pointer;

    transition:

        transform .3s,
        border .3s,
        box-shadow .3s;

    box-shadow:

        0 12px 30px
        rgba(0,0,0,.45);

}


/* FIRE GLOW */

.topup-card::before{

    content:"";

    position:absolute;

    width:150px;
    height:150px;

    left:50%;
    bottom:-120px;

    transform:
        translateX(-50%);

    background:

        radial-gradient(
            ellipse,
            rgba(255,40,0,.38),
            transparent 70%
        );

    filter:
        blur(10px);

    animation:
        fireGlow
        2.5s ease-in-out infinite;

}


@keyframes fireGlow{

    0%,100%{
        transform:
            translateX(-50%)
            scale(.8);
        opacity:.5;
    }

    50%{
        transform:
            translateX(-50%)
            scale(1.25);
        opacity:1;
    }

}


/* GLASS LIGHT */

.topup-card::after{

    content:"";

    position:absolute;

    top:-100%;
    left:-30%;

    width:25%;
    height:300%;

    transform:
        rotate(25deg);

    background:
        rgba(255,255,255,.12);

    animation:
        cardLight
        6s linear infinite;

}


@keyframes cardLight{

    0%{
        left:-50%;
    }

    70%,100%{
        left:150%;
    }

}


.topup-card:hover{

    transform:
        translateY(-7px)
        scale(1.015);

    border-color:
        rgba(255,50,50,.8);

    box-shadow:

        0 15px 45px
        rgba(0,0,0,.65),

        0 0 30px
        rgba(255,20,20,.25);

}


/* =====================================================
   SELECTED RPG CARD
===================================================== */

.topup-card.selected{

    border:
        2px solid
        var(--gold);

    box-shadow:

        0 0 0 2px
        rgba(255,215,80,.08),

        0 0 25px
        rgba(255,180,0,.55),

        0 15px 45px
        rgba(0,0,0,.7);

    animation:
        selectedAura
        1.8s ease-in-out infinite;

}


@keyframes selectedAura{

    0%,100%{
        filter:
            brightness(1);
    }

    50%{
        filter:
            brightness(1.18);
    }

}


/* =====================================================
   PRODUCT IMAGE
===================================================== */

.topup-image{

    position:relative;

    overflow:hidden;

    aspect-ratio:1/1;

}


.topup-image::after{

    content:"";

    position:absolute;

    inset:0;

    background:

        linear-gradient(
            180deg,
            transparent 50%,
            rgba(0,0,0,.55)
        );

}


.topup-image img{

    width:100%;
    height:100%;

    object-fit:cover;

    transition:
        transform .5s,
        filter .5s;

}


.topup-card:hover
.topup-image img{

    transform:
        scale(1.08);

    filter:
        brightness(1.15)
        saturate(1.15);

}


/* =====================================================
   PRODUCT INFO
===================================================== */

.topup-info{

    position:relative;

    z-index:3;

    padding:15px;

}


.topup-info h3{

    min-height:44px;

    margin-bottom:10px;

    font-size:15px;

    line-height:1.45;

}


.price,
.discount-price{

    color:var(--gold);

    font-size:21px;

    font-weight:1000;

    text-shadow:

        0 0 10px
        rgba(255,190,40,.35);

}


.old-price{

    color:#777;

}


.discount-badge{

    background:

        linear-gradient(
            135deg,
            #ff3131,
            #790000
        ) !important;

    box-shadow:

        0 0 10px
        rgba(255,0,0,.45);

}


.save-price{

    color:#50e88a;

    text-shadow:

        0 0 8px
        rgba(50,255,130,.2);

}


/* =====================================================
   RPG BUTTON
===================================================== */

.topup-info button,
.pay-btn,
.closehelp{

    position:relative;

    overflow:hidden;

    border:1px solid
        rgba(255,120,120,.35);

    background:

        linear-gradient(
            135deg,
            #ff3030 0%,
            #a80000 50%,
            #580000 100%
        );

    box-shadow:

        0 6px 20px
        rgba(255,0,0,.3),

        inset 0 1px 0
        rgba(255,255,255,.2);

    transition:.25s;

}


.topup-info button::after,
.pay-btn::after{

    content:"";

    position:absolute;

    top:-100%;
    left:-40%;

    width:30%;
    height:300%;

    background:
        rgba(255,255,255,.18);

    transform:
        rotate(25deg);

    animation:
        buttonShine
        4s linear infinite;

}


@keyframes buttonShine{

    0%{
        left:-50%;
    }

    65%,100%{
        left:150%;
    }

}


.topup-info button:hover,
.pay-btn:hover{

    transform:
        translateY(-3px);

    box-shadow:

        0 10px 30px
        rgba(255,0,0,.55),

        0 0 20px
        rgba(255,40,40,.25);

}


/* =====================================================
   SUMMARY
===================================================== */

.summary-card{

    background:

        linear-gradient(
            145deg,
            rgba(255,255,255,.07),
            rgba(0,0,0,.4)
        );

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius:18px;

    padding:20px;

    box-shadow:
        inset 0 0 25px
        rgba(255,0,0,.025);

}


.summary-row{

    padding-bottom:10px;

    margin-bottom:10px;

    border-bottom:
        1px solid
        rgba(255,255,255,.06);

}


.summary-total{

    color:var(--gold);

    text-shadow:

        0 0 12px
        rgba(255,190,30,.45);

}


.form-error{

    animation:
        errorShake
        .35s ease;

}


@keyframes errorShake{

    0%,100%{
        transform:translateX(0);
    }

    25%{
        transform:translateX(-5px);
    }

    75%{
        transform:translateX(5px);
    }

}


/* =====================================================
   CHECK STATUS
===================================================== */

.check-status{

    padding:12px 14px;

    border-radius:12px;

    background:
        rgba(0,0,0,.25);

    border:
        1px solid
        rgba(255,255,255,.06);

}


/* =====================================================
   MODAL
===================================================== */

.modal-help{

    background:
        radial-gradient(
            circle,
            rgba(255,30,30,.12),
            rgba(0,0,0,.9)
        );

    backdrop-filter:
        blur(12px);

}


.modal-content{

    background:

        linear-gradient(
            145deg,
            rgba(30,30,35,.96),
            rgba(5,5,8,.98)
        ) !important;

    color:#fff !important;

    border:
        1px solid
        rgba(255,50,50,.3);

    box-shadow:

        0 0 50px
        rgba(255,0,0,.25),

        0 25px 80px
        rgba(0,0,0,.8);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .game-container{
        padding:
            15px 10px 90px;
    }

    .game-header{
        padding:15px;
        gap:13px;
    }

    .game-cover{
        width:82px;
        height:82px;
        border-radius:17px;
    }

    .game-info h1{
        font-size:20px;
    }

    .game-section{
        padding:15px;
        border-radius:19px;
    }

    .topup-grid{
        grid-template-columns:
            repeat(2,1fr);

        gap:10px;
    }

    .topup-info{
        padding:11px;
    }

    .topup-info h3{
        font-size:14px;
    }

    .price,
    .discount-price{
        font-size:18px;
    }

}


/* =====================================================
   LARGE SCREEN
===================================================== */

@media(min-width:768px){

    .topup-grid{
        grid-template-columns:
            repeat(3,1fr);
    }

}


@media(min-width:1200px){

    .topup-grid{
        grid-template-columns:
            repeat(4,1fr);
    }

}


/* =====================================================
   REDUCED MOTION
===================================================== */

@media(
    prefers-reduced-motion:reduce
){

    *,
    *::before,
    *::after{

        animation-duration:
            .01ms !important;

        animation-iteration-count:
            1 !important;

        scroll-behavior:
            auto !important;

    }

}

      /* =====================================================
   CNTECH RPG BUTTON SYSTEM
   Ancient MMO / MOBA / Magic Fire
===================================================== */

button,
.pay-btn,
.topup-info button,
.closehelp,
.back-btn,
.help{

    position:relative;

    overflow:hidden;

    isolation:isolate;

    border:1px solid
        rgba(255,75,75,.55);

    color:#fff;

    font-weight:900;

    cursor:pointer;

    background:

        linear-gradient(
            135deg,
            rgba(255,50,50,.95),
            rgba(160,0,0,.95) 48%,
            rgba(70,0,0,.98)
        );

    box-shadow:

        0 5px 15px
        rgba(0,0,0,.55),

        0 0 12px
        rgba(255,20,20,.25),

        inset 0 1px 0
        rgba(255,255,255,.25),

        inset 0 -8px 15px
        rgba(0,0,0,.25);

    transition:
        transform .18s ease,
        box-shadow .18s ease,
        filter .18s ease,
        border-color .18s ease;

}


/* =====================================================
   MAGIC LIGHT LINE
===================================================== */

button::before,
.pay-btn::before,
.topup-info button::before{

    content:"";

    position:absolute;

    top:0;
    left:-100%;

    width:45%;
    height:100%;

    z-index:-1;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.35),
            rgba(255,210,120,.25),
            transparent
        );

    transform:
        skewX(-25deg);

    animation:
        rpgButtonShine
        3.5s ease-in-out infinite;

}


@keyframes rpgButtonShine{

    0%{
        left:-100%;
    }

    45%,100%{
        left:160%;
    }

}


/* =====================================================
   MAGICAL BORDER
===================================================== */

button::after,
.pay-btn::after,
.topup-info button::after{

    content:"";

    position:absolute;

    inset:1px;

    border-radius:inherit;

    pointer-events:none;

    border:1px solid
        rgba(255,170,100,.12);

    box-shadow:

        inset 0 0 10px
        rgba(255,50,20,.15);

}


/* =====================================================
   HOVER
===================================================== */

button:hover,
.pay-btn:hover,
.topup-info button:hover{

    transform:
        translateY(-3px)
        scale(1.015);

    border-color:
        rgba(255,130,80,.9);

    filter:
        brightness(1.15);

    box-shadow:

        0 8px 25px
        rgba(0,0,0,.7),

        0 0 25px
        rgba(255,25,25,.55),

        0 0 45px
        rgba(255,70,20,.18),

        inset 0 1px 0
        rgba(255,255,255,.3);

}


/* =====================================================
   PRESS / CLICK
===================================================== */

button:active,
.pay-btn:active,
.topup-info button:active{

    transform:
        translateY(1px)
        scale(.96);

    filter:
        brightness(.9);

    box-shadow:

        0 2px 8px
        rgba(0,0,0,.8),

        0 0 18px
        rgba(255,20,20,.65),

        inset 0 5px 12px
        rgba(0,0,0,.35);

}


/* =====================================================
   DISABLED
===================================================== */

button:disabled,
.pay-btn:disabled{

    cursor:not-allowed;

    opacity:.55;

    filter:
        grayscale(.5);

    transform:none;

    box-shadow:
        none;

}


/* =====================================================
   PAY BUTTON
===================================================== */

.pay-btn{

    width:100%;

    min-height:58px;

    margin-top:20px;

    border-radius:16px;

    font-size:17px;

    letter-spacing:.3px;

    background:

        linear-gradient(
            180deg,
            #ff3434 0%,
            #d00000 45%,
            #780000 100%
        );

}


/* =====================================================
   TOPUP BUTTON
===================================================== */

.topup-info button{

    width:100%;

    min-height:46px;

    margin-top:12px;

    padding:0 14px;

    border-radius:13px;

    font-size:14px;

}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-btn{

    width:45px;
    height:45px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:14px;

    text-decoration:none;

    background:

        linear-gradient(
            145deg,
            rgba(255,40,40,.25),
            rgba(50,0,0,.75)
        );

}


.back-btn:hover{

    transform:
        translateX(-3px)
        scale(1.06);

}


/* =====================================================
   HELP / QUESTION BUTTON
===================================================== */

.help{

    width:30px;
    height:30px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:50%;

    padding:0;

    font-size:13px;

    background:

        radial-gradient(
            circle at 35% 30%,
            #ff7777,
            #e00000 45%,
            #650000 100%
        );

    box-shadow:

        0 0 12px
        rgba(255,20,20,.5);

}


.help:hover{

    transform:
        translateY(-50%)
        scale(1.15);

}


/* =====================================================
   GOLD RPG SELECTED BUTTON
===================================================== */

.topup-card.selected
.topup-info button{

    border-color:
        rgba(255,215,80,.9);

    background:

        linear-gradient(
            135deg,
            #ffdb62,
            #b87900,
            #704000
        );

    color:#160d00;

    box-shadow:

        0 0 20px
        rgba(255,190,40,.45),

        inset 0 1px 0
        rgba(255,255,255,.5);

}


/* =====================================================
   GOLD TEXT
===================================================== */

.topup-card.selected
.topup-info button i{

    filter:
        drop-shadow(
            0 0 5px
            rgba(255,255,255,.7)
        );

}


/* =====================================================
   CLOSE HELP
===================================================== */

.closehelp{

    width:100%;

    min-height:50px;

    border-radius:14px;

    font-size:15px;

}


/* =====================================================
   ICON
===================================================== */

button i,
.pay-btn i,
.topup-info button i{

    margin-right:7px;

    filter:
        drop-shadow(
            0 0 5px
            rgba(255,255,255,.45)
        );

}


/* =====================================================
   RPG BUTTON GLOW PULSE
===================================================== */

.pay-btn{

    animation:
        payButtonGlow
        2.5s ease-in-out infinite;

}


@keyframes payButtonGlow{

    0%,100%{

        box-shadow:

            0 5px 18px
            rgba(255,0,0,.3),

            0 0 15px
            rgba(255,0,0,.15),

            inset 0 1px 0
            rgba(255,255,255,.2);

    }

    50%{

        box-shadow:

            0 8px 25px
            rgba(255,0,0,.5),

            0 0 30px
            rgba(255,20,20,.35),

            inset 0 1px 0
            rgba(255,255,255,.3);

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .pay-btn{

        min-height:55px;

        font-size:16px;

    }

    .topup-info button{

        min-height:44px;

        font-size:13px;

    }

}


/* =====================================================
   REDUCED MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    button,
    .pay-btn,
    .topup-info button{

        animation:none !important;

    }

      }
      
</style>
    
    <script>

const GAME_ID = <?= (int)$game['id'] ?>;
const GAME_NAME = "<?= strtoupper($game['name']) ?>";

const REQUIRE_SERVER = [
    "MLBB",
    "MOBILE LEGENDS",
    "HONOR OF KINGS"
].includes(GAME_NAME);

let selectedProduct = 0;
let selectedPrice = 0;
let playerVerified = false;
let checkTimer = null;

/* =========================
HELP MODAL
========================= */

function openHelp(){
    document.getElementById("helpModal").style.display="flex";
}

function closeHelp(){
    document.getElementById("helpModal").style.display="none";
}

/* =========================
SELECT PRODUCT
========================= */

function selectProduct(
    element,
    id,
    name,
    price
){

    selectedProduct = id;
    selectedPrice = price;

    document
    .querySelectorAll(".topup-card")
    .forEach(card=>{
        card.classList.remove("selected");
    });

    element.classList.add("selected");

    document.getElementById(
        "selected"
    ).innerHTML = name;

    document.getElementById(
        "total"
    ).innerHTML =
    Number(price).toLocaleString() +
    " ₭";

    updateSummaryInfo();
}

        
    function updateSummaryInfo(){

    let player =
    document.getElementById(
        "player_id"
    )?.value || "-";

    let server =
    document.getElementById(
        "server_id"
    )?.value || "-";

    let playerBox =
    document.getElementById(
        "show-player-id"
    );

    if(playerBox){
        playerBox.innerHTML = player;
    }

    let serverBox =
    document.getElementById(
        "show-server-id"
    );

    if(serverBox){
        serverBox.innerHTML = server;
    }
    }
/* =========================
AUTO CHECK PLAYER
========================= */

document.addEventListener(
"DOMContentLoaded",
()=>{

    const uid =
    document.getElementById(
        "player_id"
    );

    const server =
    document.getElementById(
        "server_id"
    );

    if(uid){
        uid.addEventListener(
            "input",
            autoCheck
        );
    }

    if(server){
        server.addEventListener(
            "input",
            autoCheck
        );
    }

});

function autoCheck(){

    clearTimeout(checkTimer);

    playerVerified = false;

    document
    .getElementById(
        "playerStatus"
    )
    .innerHTML = "";

    const uid =
    document
    .getElementById(
        "player_id"
    )
    .value.trim();

    let server = "";

    const serverInput =
    document.getElementById(
        "server_id"
    );

    if(serverInput){
        server =
        serverInput.value.trim();
    }

    if(uid===""){
        return;
    }

    if(REQUIRE_SERVER && server===""){

        document
        .getElementById(
            "playerStatus"
        )
        .innerHTML =
        '<span style="color:#f59e0b">⚠ กรุณากรอก Server ID</span>';

        return;
    }

    checkTimer =
    setTimeout(
        checkPlayer,
        1000
    );
}

/* =========================
CHECK PLAYER
========================= */

function checkPlayer(){

    const uid =
    document
    .getElementById(
        "player_id"
    )
    .value.trim();

    let server = "";

    const serverInput =
    document.getElementById(
        "server_id"
    );

    if(serverInput){
        server =
        serverInput.value.trim();
    }

    const box =
    document.getElementById(
        "playerStatus"
    );

    box.innerHTML =
    '<span style="color:#38bdf8">⏳ กำลังตรวจสอบ...</span>';

    fetch(
        "../api/check_player.php",
        {
            method:"POST",
            headers:{
                "Content-Type":"application/json"
            },
            body:JSON.stringify({
                game_id:GAME_ID,
                uid:uid,
                server:server
            })
        }
    )

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            playerVerified = true;

            box.innerHTML =
            '<span style="color:#22c55e">✅ '
            + data.nickname +
            '</span>';

        }else{

            playerVerified = false;

            box.innerHTML =
            '<span style="color:#ef4444">❌ '
            + data.message +
            '</span>';

        }

    })

    .catch(()=>{

        playerVerified = false;

        box.innerHTML =
        '<span style="color:#ef4444">❌ API Error</span>';

    });

}

/* =========================
PAYMENT
========================= */

function goPayment(){

    const error =
    document.getElementById(
        "formError"
    );

    error.innerHTML = "";

    const uid =
    document
    .getElementById(
        "player_id"
    )
    .value.trim();

    const email =
    document
    .getElementById(
        "email"
    )
    .value.trim();

    let server = "";
    let open_id = "";

    const serverInput =
    document.getElementById(
        "server_id"
    );

    if(serverInput){

        server =
        serverInput.value.trim();

    }else{

        open_id = uid;

    }

    if(uid===""){

        error.innerHTML =
        "❌ กรุณากรอก Player ID";

        return;
    }

    if(REQUIRE_SERVER && server===""){

        error.innerHTML =
        "❌ กรุณากรอก Server ID";

        return;
    }

    if(!playerVerified){

        error.innerHTML =
        "❌ กรุณาตรวจสอบบัญชีก่อน";

        return;
    }

    if(selectedProduct===0){

        error.innerHTML =
        "❌ กรุณาเลือกแพ็กเกจ";

        return;
    }

    if(email===""){

        error.innerHTML =
        "❌ กรุณากรอก Email";

        return;
    }

    const btn =
    document.getElementById(
        "payBtn"
    );

    btn.disabled = true;

    btn.innerHTML =
    '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

    fetch(
        "../api/set_checkout.php",
        {
            method:"POST",
            headers:{
                "Content-Type":"application/json"
            },
            body:JSON.stringify({

                game_id:GAME_ID,
                uid:uid,
                open_id:open_id,
                server:server,
                product:selectedProduct,
                price:selectedPrice,
                email:email

            })
        }
    )

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            window.location.href =
            data.payment_url;

        }else{

            error.innerHTML =
            data.message;

        }

    })

    .catch(()=>{

        error.innerHTML =
        "❌ ระบบผิดพลาด";

    })

    .finally(()=>{

        btn.disabled = false;

        btn.innerHTML =
        '<i class="fa-solid fa-credit-card"></i> ดำเนินการต่อ';

    });

}

</script>

  <script>
/* =====================================================
   CNTECH STORE
   MAGIC TOUCH TRAIL
   CLASSIC MOBA / RPG 2013 STYLE

   - Follow finger freely
   - Magic trail
   - Red + Purple
   - Touch burst
   - click03.mp3
   - Mobile + Mouse
   - No HTML required
===================================================== */

(function(){

    'use strict';


    /* =================================================
       SETTINGS
    ================================================= */

    const TOUCH_SOUND = '';

    const TOUCH_VOLUME = 0.85;

    const COLORS = [
        'hsl(7.7,82.3%,55.4%)',
        'hsl(300,100%,54.9%)',
        'hsl(174.1,100%,68.1%)',
        'hsl(21.7,99.3%,48.6%)',
        'hsl(60,100%,67.1%)'
    ];

    const PARTICLES = 5;

    const TRAIL_DISTANCE = 7;

    const MAX_TRAIL = 120;

    const FX_LIFE = 850;


    /* =================================================
       STATE
    ================================================= */

    let activeTouch = false;

    let lastX = 0;
    let lastY = 0;

    let lastTrailX = -9999;
    let lastTrailY = -9999;

    let trailCount = 0;


    /* =================================================
       SOUND
    ================================================= */

    const touchAudio =
        new Audio(TOUCH_SOUND);

    touchAudio.preload = 'auto';

    touchAudio.volume =
        TOUCH_VOLUME;


    function playTouchSound(){

        try{

            const sound =
                touchAudio.cloneNode(true);

            sound.volume =
                TOUCH_VOLUME;

            sound.currentTime = 0;

            sound.play()
                .catch(function(){});

            sound.addEventListener(
                'ended',
                function(){

                    sound.remove();

                }
            );

        }catch(e){}

    }


    /* =================================================
       RANDOM
    ================================================= */

    function random(min,max){

        return Math.random() *
            (max - min) + min;

    }


    function randomColor(){

        return COLORS[
            Math.floor(
                Math.random() *
                COLORS.length
            )
        ];

    }


    function distance(x1,y1,x2,y2){

        const dx = x2 - x1;

        const dy = y2 - y1;

        return Math.sqrt(
            dx * dx +
            dy * dy
        );

    }


    /* =================================================
       CREATE TRAIL
    ================================================= */

    function createTrail(x,y){

        if(trailCount >= MAX_TRAIL){

            return;

        }


        const d =
            distance(
                x,
                y,
                lastTrailX,
                lastTrailY
            );


        if(d < TRAIL_DISTANCE){

            return;

        }


        lastTrailX = x;

        lastTrailY = y;

        trailCount++;


        /* =================================================
           TRAIL
        ================================================= */

        const trail =
            document.createElement('div');

        trail.className =
            'cntech-magic-trail';


        trail.style.left =
            x + 'px';

        trail.style.top =
            y + 'px';


        trail.style.setProperty(
            '--trail-color',
            randomColor()
        );


        const size =
            random(3,8);


        trail.style.width =
            size + 'px';

        trail.style.height =
            size + 'px';


        document.body.appendChild(
            trail
        );


        setTimeout(
            function(){

                trail.remove();

                trailCount--;

            },
            FX_LIFE
        );


        /* =================================================
           SMALL PARTICLES
        ================================================= */

        for(
            let i = 0;
            i < PARTICLES;
            i++
        ){

            const particle =
                document.createElement('div');

            particle.className =
                'cntech-magic-particle';


            particle.style.left =
                x + 'px';

            particle.style.top =
                y + 'px';


            const angle =
                random(
                    0,
                    Math.PI * 2
                );


            const radius =
                random(12,38);


            particle.style.setProperty(
                '--px',
                Math.cos(angle) *
                radius +
                'px'
            );


            particle.style.setProperty(
                '--py',
                Math.sin(angle) *
                radius +
                'px'
            );


            particle.style.setProperty(
                '--particle-color',
                randomColor()
            );


            particle.style.setProperty(
                '--size',
                random(1.5,4) + 'px'
            );


            document.body.appendChild(
                particle
            );


            setTimeout(
                function(){

                    particle.remove();

                },
                600
            );

        }

    }


    /* =================================================
       BURST
       ตอนนิ้วกดลง
    ================================================= */

    function createBurst(x,y){

        const fx =
            document.createElement('div');

        fx.className =
            'cntech-magic-burst';


        fx.style.left =
            x + 'px';

        fx.style.top =
            y + 'px';


        /* =================================================
           CORE
        ================================================= */

        const core =
            document.createElement('div');

        core.className =
            'cntech-burst-core';


        fx.appendChild(
            core
        );


        /* =================================================
           RINGS
        ================================================= */

        for(
            let i = 0;
            i < 3;
            i++
        ){

            const ring =
                document.createElement('div');

            ring.className =
                'cntech-burst-ring';


            ring.style.animationDelay =
                (i * .08) + 's';


            ring.style.borderColor =
                randomColor();


            fx.appendChild(
                ring
            );

        }


        /* =================================================
           RAYS
        ================================================= */

        for(
            let i = 0;
            i < 10;
            i++
        ){

            const ray =
                document.createElement('div');

            ray.className =
                'cntech-magic-ray';


            ray.style.setProperty(
                '--angle',
                random(0,360) + 'deg'
            );


            ray.style.setProperty(
                '--length',
                random(20,55) + 'px'
            );


            ray.style.background =
                `linear-gradient(
                    90deg,
                    transparent,
                    ${randomColor()},
                    transparent
                )`;


            fx.appendChild(
                ray
            );

        }


        document.body.appendChild(
            fx
        );


        setTimeout(
            function(){

                fx.remove();

            },
            750
        );

    }


    /* =================================================
       TOUCH START
    ================================================= */

    document.addEventListener(
        'touchstart',
        function(e){

            activeTouch = true;

            trailCount = 0;

            const touch =
                e.changedTouches[0];


            lastX =
                touch.clientX;

            lastY =
                touch.clientY;


            lastTrailX =
                lastX;

            lastTrailY =
                lastY;


            createBurst(
                lastX,
                lastY
            );


            playTouchSound();

        },
        {
            passive:true
        }
    );


    /* =================================================
       TOUCH MOVE
    ================================================= */

    document.addEventListener(
        'touchmove',
        function(e){

            if(!activeTouch){

                return;

            }


            for(
                const touch of
                e.changedTouches
            ){

                const x =
                    touch.clientX;

                const y =
                    touch.clientY;


                /*
                 * สร้าง trail ตามนิ้ว
                 */

                createTrail(
                    x,
                    y
                );


                lastX = x;

                lastY = y;

            }

        },
        {
            passive:true
        }
    );


    /* =================================================
       TOUCH END
    ================================================= */

    document.addEventListener(
        'touchend',
        function(){

            activeTouch = false;

            trailCount = 0;

        },
        {
            passive:true
        }
    );


    document.addEventListener(
        'touchcancel',
        function(){

            activeTouch = false;

            trailCount = 0;

        },
        {
            passive:true
        }
    );


    /* =================================================
       MOUSE
       Desktop / PC
    ================================================= */

    let mouseDown = false;


    document.addEventListener(
        'mousedown',
        function(e){

            mouseDown = true;

            lastX =
                e.clientX;

            lastY =
                e.clientY;


            lastTrailX =
                lastX;

            lastTrailY =
                lastY;


            createBurst(
                lastX,
                lastY
            );


            playTouchSound();

        }
    );


    document.addEventListener(
        'mousemove',
        function(e){

            if(!mouseDown){

                return;

            }


            createTrail(
                e.clientX,
                e.clientY
            );

        }
    );


    document.addEventListener(
        'mouseup',
        function(){

            mouseDown = false;

            trailCount = 0;

        }
    );


    /* =================================================
       CSS
       JS สร้าง CSS เอง
    ================================================= */

    const style =
        document.createElement('style');


    style.textContent = `

    /* =============================================
       MAGIC TRAIL
    ============================================= */

    .cntech-magic-trail{

        position:fixed;

        transform:translate(-50%,-50%);

        pointer-events:none;

        z-index:999998;

        border-radius:50%;

        background:
            var(--trail-color);

        box-shadow:

            0 0 5px
            var(--trail-color),

            0 0 12px
            var(--trail-color),

            0 0 24px
            var(--trail-color);

        animation:
            cntechTrail
            .85s
            cubic-bezier(.16,.8,.3,1)
            forwards;

    }


    @keyframes cntechTrail{

        0%{

            opacity:0;

            transform:
                translate(-50%,-50%)
                scale(.3);

        }

        15%{

            opacity:1;

            transform:
                translate(-50%,-50%)
                scale(1.4);

        }

        100%{

            opacity:0;

            transform:
                translate(-50%,-50%)
                scale(.05);

        }

    }


    /* =============================================
       PARTICLE
    ============================================= */

    .cntech-magic-particle{

        position:fixed;

        left:0;
        top:0;

        width:
            var(--size);

        height:
            var(--size);

        border-radius:50%;

        pointer-events:none;

        z-index:999999;

        background:
            var(--particle-color);

        box-shadow:

            0 0 5px
            var(--particle-color),

            0 0 12px
            var(--particle-color);

        transform:
            translate(-50%,-50%);

        animation:
            cntechParticle
            .6s
            ease-out
            forwards;

    }


    @keyframes cntechParticle{

        0%{

            opacity:1;

            transform:
                translate(-50%,-50%)
                scale(1);

        }

        100%{

            opacity:0;

            transform:
                translate(
                    calc(-50% + var(--px)),
                    calc(-50% + var(--py))
                )
                scale(.1);

        }

    }


    /* =============================================
       BURST
    ============================================= */

    .cntech-magic-burst{

        position:fixed;

        left:0;
        top:0;

        width:1px;
        height:1px;

        pointer-events:none;

        z-index:1000000;

    }


    .cntech-burst-core{

        position:absolute;

        left:0;
        top:0;

        width:18px;
        height:18px;

        transform:
            translate(-50%,-50%);

        border-radius:50%;

        background:#fff;

        box-shadow:

            0 0 8px #fff,

            0 0 20px #ff2020,

            0 0 40px #ff2020,

            0 0 65px #8b5cff;

        animation:
            cntechCore
            .45s
            ease-out
            forwards;

    }


    @keyframes cntechCore{

        0%{

            opacity:1;

            transform:
                translate(-50%,-50%)
                scale(.3);

        }

        100%{

            opacity:0;

            transform:
                translate(-50%,-50%)
                scale(2.8);

        }

    }


    /* =============================================
       RINGS
    ============================================= */

    .cntech-burst-ring{

        position:absolute;

        left:0;
        top:0;

        width:24px;
        height:24px;

        transform:
            translate(-50%,-50%);

        border:2px solid;

        border-radius:50%;

        opacity:.9;

        animation:
            cntechRing
            .65s
            cubic-bezier(.15,.8,.3,1)
            forwards;

    }


    @keyframes cntechRing{

        0%{

            opacity:.9;

            transform:
                translate(-50%,-50%)
                scale(.3);

        }

        100%{

            opacity:0;

            transform:
                translate(-50%,-50%)
                scale(4.2);

        }

    }


    /* =============================================
       RAYS
    ============================================= */

    .cntech-magic-ray{

        position:absolute;

        left:0;
        top:0;

        height:1px;

        width:
            var(--length);

        transform-origin:left center;

        transform:
            rotate(var(--angle))
            translateX(8px);

        filter:
            drop-shadow(
                0 0 5px currentColor
            );

        animation:
            cntechRay
            .55s
            ease-out
            forwards;

    }


    @keyframes cntechRay{

        0%{

            opacity:0;

            width:5px;

        }

        25%{

            opacity:1;

        }

        100%{

            opacity:0;

            width:
                var(--length);

            transform:
                rotate(var(--angle))
                translateX(35px);

        }

    }


    /* =============================================
       MOBILE PERFORMANCE
    ============================================= */

    @media(max-width:600px){

        .cntech-magic-particle{

            box-shadow:
                0 0 4px
                var(--particle-color);

        }

        .cntech-magic-trail{

            box-shadow:
                0 0 5px
                var(--trail-color),

                0 0 12px
                var(--trail-color);

        }

    }


    /* =============================================
       REDUCE MOTION
    ============================================= */

    @media(prefers-reduced-motion:reduce){

        .cntech-magic-trail,
        .cntech-magic-particle,
        .cntech-magic-burst,
        .cntech-magic-burst *{

            animation-duration:
                .2s !important;

        }

    }

    `;


    document.head.appendChild(
        style
    );


    /* =================================================
       DEBUG
    ================================================= */

    console.log(
        '%c CNTECH MAGIC TRAIL ',
        'background:#ff2020;color:#fff;font-weight:900;padding:7px 12px;border-radius:6px'
    );

    console.log(
        'Touch trail: ON'
    );

    console.log(
        'Sound:',
        TOUCH_SOUND
    );

})();
  </script>

  <script>
(function(){

'use strict';

/* =====================================================
   CNTECH STORE
   LOBBY AUDIO ENGINE
   FIXED MUSIC ORDER
===================================================== */

const START_DELAY = 1000;
const MUSIC_VOLUME = 0.25;


/* =====================================================
   LOBBY FILES
   กำหนดตายตัว
===================================================== */

const LOBBY_FILES = [
    '/audio/lobby02.mp3',
    
];


/* =====================================================
   STATE
===================================================== */

let enabled =
    localStorage.getItem('cntech_sound') !== 'off';

let unlocked = false;

let musicPlaying = false;

let currentMusic = null;

let currentIndex = 0;

let musicTimer = null;

const startAt =
    Date.now() + START_DELAY;


/* =====================================================
   CREATE AUDIO
===================================================== */

function createAudio(src){

    const audio =
        new Audio(src);

    audio.preload = 'auto';

    audio.volume =
        MUSIC_VOLUME;

    return audio;

}


/* =====================================================
   PLAY FIXED LOBBY MUSIC
===================================================== */

function playMusic(){

    if(!enabled)
        return;

    if(!unlocked)
        return;

    if(musicPlaying)
        return;


    /*
     * ใช้เพลงตามลำดับ
     */

    const src =
        LOBBY_FILES[currentIndex];


    currentMusic =
        createAudio(src);


    musicPlaying =
        true;


    console.log(
        '[CNTECH LOBBY]',
        'Playing:',
        src
    );


    currentMusic.addEventListener(
        'ended',
        function(){

            musicPlaying =
                false;

            currentMusic =
                null;


            /*
             * ไปเพลงถัดไป
             */

            currentIndex++;


            /*
             * วนกลับเพลงแรก
             */

            if(
                currentIndex >=
                LOBBY_FILES.length
            ){

                currentIndex = 0;

            }


            musicTimer =
                setTimeout(
                    function(){

                        playMusic();

                    },
                    300
                );

        }
    );


    currentMusic
        .play()
        .catch(function(error){

            console.log(
                '[CNTECH LOBBY] Waiting for interaction'
            );

            musicPlaying =
                false;

        });

}


/* =====================================================
   STOP
===================================================== */

function stopMusic(){

    if(currentMusic){

        try{

            currentMusic.pause();

            currentMusic.currentTime =
                0;

        }catch(e){}

    }


    currentMusic =
        null;

    musicPlaying =
        false;


    if(musicTimer){

        clearTimeout(
            musicTimer
        );

        musicTimer =
            null;

    }

}


/* =====================================================
   USER INTERACTION
===================================================== */

function unlock(){

    unlocked = true;


    if(
        Date.now() >= startAt
    ){

        playMusic();

    }

}


document.addEventListener(
    'pointerdown',
    unlock,
    {
        passive:true
    }
);


document.addEventListener(
    'touchstart',
    unlock,
    {
        passive:true
    }
);


/* =====================================================
   START AFTER 1 SECOND
===================================================== */

setTimeout(
    function(){

        console.log(
            '[CNTECH LOBBY] Timer complete'
        );

        playMusic();

    },
    START_DELAY
);


/* =====================================================
   VISIBILITY
===================================================== */

document.addEventListener(
    'visibilitychange',
    function(){

        if(document.hidden){

            if(currentMusic){

                currentMusic.pause();

            }

        }else{

            if(
                enabled &&
                unlocked &&
                musicPlaying &&
                currentMusic
            ){

                currentMusic
                    .play()
                    .catch(
                        function(){}
                    );

            }

        }

    }
);


/* =====================================================
   GLOBAL CONTROL
===================================================== */

window.lobbyAudio = {

    play: function(){

        playMusic();

    },

    stop: function(){

        stopMusic();

    },

    next: function(){

        stopMusic();

        currentIndex++;

        if(
            currentIndex >=
            LOBBY_FILES.length
        ){

            currentIndex = 0;

        }

        playMusic();

    },

    set: function(index){

        if(
            index < 0 ||
            index >= LOBBY_FILES.length
        ){

            return;

        }

        stopMusic();

        currentIndex = index;

        playMusic();

    }

};


/* =====================================================
   DEBUG
===================================================== */

console.log(
    '%c CNTECH FIXED LOBBY AUDIO ',
    'background:#ff2020;color:#fff;font-weight:900;padding:6px 12px;border-radius:6px'
);

console.log(
    'Playlist:',
    LOBBY_FILES
);

})();
  </script>
  
</body>
    </html>