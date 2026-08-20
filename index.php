<?php

require_once "config.php";
require_once "database.php";
require_once "site_guard.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

/*
=================================
CART COUNT
=================================
*/
$cartCount = 0;

if(isset($_SESSION['cart'])){

    foreach($_SESSION['cart'] as $item){

        $cartCount += (int)$item['qty'];

    }

}

/*
=================================
HOME DATA
=================================
*/

$games = $conn->query("
SELECT *
FROM games
WHERE status='active'
ORDER BY play_count DESC
LIMIT 12
");

$vouchers = $conn->query("
SELECT *
FROM voucher_categories
WHERE status='active'
ORDER BY id DESC
LIMIT 12
");

$products = $conn->query("
SELECT *
FROM products
WHERE status='active'
ORDER BY sold DESC
LIMIT 12
");

$blogs = $conn->query("
SELECT *
FROM blogs
WHERE status='published'
ORDER BY created_at DESC
LIMIT 8
");

?>
<!DOCTYPE html>
<html lang="la">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<meta
name="theme-color"
content="#000">

<title>
CN Tech Store
    </title>
    
     <meta name="description"
content="CN Tech Store ຮ້ານຄ້າອອນລາຍໃນ ສປປ ລາວ ໃຫ້ບໍລິການເຕີມເກມອອນລາຍ, Game Top Up, Mobile Legends, Free Fire, PUBG Mobile, ROV, Honor of Kings, ຈຳໜ່າຍ Game Cards, Digital Voucher, ເຕີມເງິນມືຖື, ຂາຍ Computer, Laptop, PC Parts, IT Accessories, ອຸປະກອນໄອທີ ແລະ ບໍລິການຊຳລະເງິນອອນລາຍ ດ້ວຍລະບົບທີ່ປອດໄພ ວ່ອງໄວ ແລະ ໜ້າເຊື່ອຖື ສຳລັບລູກຄ້າທຸກຄົນ">

<meta name="keywords"
content="
CN Tech Store,
cntechstore.shop,
Game Top Up Laos,
เติมเกม,
เติมเกมออนไลน์,
เติมเกมลาว,
เติมเกมมือถือ,
Mobile Legends,
Mobile Legends Bang Bang,
MLBB Top Up,
Free Fire Top Up,
PUBG Mobile Top Up,
ROV Top Up,
Honor of Kings,
HOK Top Up,
Game Cards,
Digital Voucher,
Garena Voucher,
Razer Gold,
Mobile Top Up,
เติมเงินมือถือ,
BCEL One,
QR Payment Laos,
Computer Laos,
Laptop Laos,
PC Parts,
Computer Accessories,
IT Accessories,
Gaming Store Laos,
Online Game Store,
Digital Store Laos,
ร้านเติมเกม,
ร้านเกมออนไลน์,
ร้านคอมพิวเตอร์,
อุปกรณ์ไอที,
ขายโน๊ตบุ๊ค,
ขายคอมพิวเตอร์,
Gaming Gear,
CN Tech Store Laos
">

<link rel="canonical"
href="<?= $currentURL ?>">

   <meta property="og:title" content="CN Tech Store">
<meta property="og:description" content="Game Top-up & Computer Store">
<meta property="og:image" content="https://cntechstore.shop/assets/banner.jpg">
<meta property="og:url" content="https://cntechstore.shop">
<meta property="og:type" content="website">
    <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
    <script type="application/ld+json">
{
 "@context":"https://schema.org",
 "@type":"Store",
 "name":"CN Tech Store",
 "url":"https://cntechstore.shop",
 "logo":"https://cntechstore.shop/logo.png",
 "email":"support@cntechstore.shop"
}
    </script>
      
     <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9543860279937476"
             crossorigin="anonymous"></script>
    
<link
rel="icon"
href="/uploads/favicon.png">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

<link
rel="preconnect"
href="https://fonts.googleapis.com">

<link
rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>

<link
href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

/* =====================================================
   CNTECH STORE
   HEADER UI
   MMO / MOBA OLD VISUAL
   2014–2017 STYLE
   DARK / BLACK / RED / GOLD
===================================================== */


/* =====================================================
   ROOT
===================================================== */

:root{

    --old-black:#050505;
    --old-black-2:#0b0b0b;
    --old-panel:rgba(8,8,8,.88);

    --old-red:#9d1111;
    --old-red-hot:#d51b1b;

    --old-gold:#b88a32;
    --old-gold-light:#e4bd63;
    --old-gold-dark:#60451b;

    --old-text:#eee;
    --old-muted:#8d8d8d;

    --old-border:
        rgba(206,164,70,.38);

    --old-shadow:
        rgba(0,0,0,.85);

}


/* =====================================================
   HEADER
===================================================== */

.app-header{

    position:sticky;

    top:0;

    z-index:9999;

    width:100%;

    background:

        linear-gradient(
            180deg,
            rgba(5,5,5,.96),
            rgba(10,10,10,.88)
        );

    border-bottom:
        1px solid
        var(--old-gold-dark);

    box-shadow:

        0 5px 18px
        rgba(0,0,0,.85),

        inset 0 -1px 0
        rgba(255,210,100,.12);

    backdrop-filter:
        blur(10px);

    -webkit-backdrop-filter:
        blur(10px);

}


/* =====================================================
   OLD MMO TOP EDGE
===================================================== */

.app-header::before{

    content:"";

    position:absolute;

    left:0;
    right:0;

    top:0;

    height:2px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #6c0909,
            #c28e32,
            #f0cf72,
            #c28e32,
            #6c0909,
            transparent
        );

    opacity:.75;

}


/* =====================================================
   GOLD ENERGY LINE
===================================================== */

.app-header::after{

    content:"";

    position:absolute;

    left:0;
    right:0;

    bottom:-2px;

    height:2px;

    background:

        linear-gradient(
            90deg,
            transparent 0%,
            #571010 15%,
            #a51b1b 30%,
            #d2a64d 50%,
            #a51b1b 70%,
            #571010 85%,
            transparent 100%
        );

    box-shadow:

        0 0 5px
        rgba(205,160,60,.45);

}


/* =====================================================
   HEADER ROW
===================================================== */

.header-row{

    width:100%;

    min-height:64px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    gap:12px;

    padding:
        7px 12px;

}


/* =====================================================
   LOGO AREA
===================================================== */

.logo{

    position:relative;

    display:flex;

    align-items:center;

    gap:9px;

    min-width:0;

}


/* =====================================================
   LOGO FRAME
===================================================== */

.logo img{

    width:42px;

    height:42px;

    flex-shrink:0;

    object-fit:cover;

    border-radius:4px;

    border:
        1px solid
        var(--old-gold);

    background:#080808;

    box-shadow:

        0 2px 8px
        rgba(0,0,0,.9),

        inset 0 0 0 1px
        rgba(255,255,255,.08),

        0 0 6px
        rgba(184,138,50,.18);

    filter:
        brightness(.96)
        saturate(.95);

}


/* =====================================================
   LOGO TEXT
===================================================== */

.logo-text{

    min-width:0;

}


/* =====================================================
   CNTECH TITLE
===================================================== */

.logo-text h1{

    margin:0;

    font-size:18px;

    line-height:1;

    font-weight:900;

    letter-spacing:.8px;

    color:#eee;

    text-shadow:

        1px 1px 0 #000,

        0 0 4px
        rgba(0,0,0,.9);

}


/* CN */

.logo-text h1{

    color:#eee;

}


/* =====================================================
   SUB TITLE
===================================================== */

.logo-text p{

    margin-top:4px;

    color:#777;

    font-size:8px;

    line-height:1;

    letter-spacing:1.2px;

    text-transform:uppercase;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}


/* =====================================================
   HEADER ACTIONS
===================================================== */

.header-actions{

    display:flex;

    align-items:center;

    gap:6px;

    flex-shrink:0;

}


/* =====================================================
   OLD MMO BUTTON
===================================================== */

.icon-btn{

    position:relative;

    width:40px;

    height:40px;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#bdbdbd;

    background:

        linear-gradient(
            145deg,
            #1a1a1a,
            #070707
        );

    border:
        1px solid
        rgba(184,138,50,.42);

    border-radius:4px;

    cursor:pointer;

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.08),

        inset 0 -4px 8px
        rgba(0,0,0,.7),

        0 3px 8px
        rgba(0,0,0,.75);

    transition:

        color .18s ease,

        border-color .18s ease,

        filter .18s ease,

        transform .18s ease;

}


/* =====================================================
   8-SIDED INNER SHAPE
===================================================== */

.icon-btn::before{

    content:"";

    position:absolute;

    inset:2px;

    pointer-events:none;

    border:
        1px solid
        rgba(255,215,120,.10);

    clip-path:

        polygon(
            5px 0,
            calc(100% - 5px) 0,
            100% 5px,
            100% calc(100% - 5px),
            calc(100% - 5px) 100%,
            5px 100%,
            0 calc(100% - 5px),
            0 5px
        );

}


/* =====================================================
   BUTTON ICON
===================================================== */

.icon-btn i{

    position:relative;

    z-index:2;

    font-size:16px;

    color:#aaa;

    text-shadow:

        1px 1px 0 #000;

}


/* =====================================================
   BUTTON HOVER
===================================================== */

.icon-btn:hover{

    color:#fff;

    border-color:
        var(--old-gold-light);

    filter:
        brightness(1.15);

    transform:
        translateY(-1px);

    box-shadow:

        inset 0 1px 0
        rgba(255,230,150,.16),

        0 0 8px
        rgba(190,140,45,.28),

        0 5px 12px
        rgba(0,0,0,.9);

}


.icon-btn:hover i{

    color:
        var(--old-gold-light);

}


/* =====================================================
   CLICK
===================================================== */

.icon-btn:active{

    transform:
        translateY(1px)
        scale(.95);

    filter:
        brightness(.9);

}


/* =====================================================
   CART BADGE
===================================================== */

.cart-badge{

    position:absolute;

    top:-6px;

    right:-6px;

    min-width:18px;

    height:18px;

    padding:
        0 4px;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:3px;

    color:#fff;

    background:

        linear-gradient(
            180deg,
            #c62222,
            #650707
        );

    border:
        1px solid
        #e0a84a;

    font-size:9px;

    font-weight:900;

    line-height:1;

    box-shadow:

        0 2px 6px
        rgba(0,0,0,.9),

        0 0 5px
        rgba(180,20,20,.45);

}


/* =====================================================
   SEARCH WRAPPER
===================================================== */

.search-wrapper{

    position:relative;

    width:
        min(700px,100%);

    margin:
        10px auto 13px;

}


/* =====================================================
   SEARCH BOX
===================================================== */

.search-box{

    position:relative;

    display:flex;

    width:100%;

    min-height:43px;

    overflow:hidden;

    background:

        linear-gradient(
            180deg,
            rgba(20,20,20,.94),
            rgba(5,5,5,.96)
        );

    border:
        1px solid
        rgba(184,138,50,.42);

    border-radius:5px;

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.06),

        inset 0 -5px 10px
        rgba(0,0,0,.65),

        0 5px 15px
        rgba(0,0,0,.75);

}


/* =====================================================
   SEARCH GOLD INNER FRAME
===================================================== */

.search-box::before{

    content:"";

    position:absolute;

    inset:2px;

    pointer-events:none;

    border:
        1px solid
        rgba(255,215,120,.08);

}


/* =====================================================
   SEARCH INPUT
===================================================== */

.search-box input{

    flex:1;

    min-width:0;

    height:43px;

    padding:
        0 13px;

    color:#eee;

    background:transparent;

    border:0;

    outline:0;

    font-size:13px;

}


.search-box input::placeholder{

    color:#686868;

}


/* =====================================================
   SEARCH FOCUS
===================================================== */

.search-box:focus-within{

    border-color:
        var(--old-gold-light);

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.08),

        0 0 8px
        rgba(190,145,50,.22),

        0 6px 18px
        rgba(0,0,0,.85);

}


/* =====================================================
   SEARCH BUTTON
===================================================== */

.search-box button{

    width:46px;

    height:43px;

    flex-shrink:0;

    border:0;

    color:#e8c76d;

    background:

        linear-gradient(
            180deg,
            #5c0e0e,
            #260505
        );

    border-left:
        1px solid
        rgba(184,138,50,.38);

    cursor:pointer;

    text-shadow:
        1px 1px 0 #000;

    transition:
        filter .18s,
        color .18s;

}


.search-box button:hover{

    color:#fff;

    filter:
        brightness(1.25);

}


/* =====================================================
   SEARCH RESULTS
===================================================== */

.search-results{

    position:absolute;

    top:48px;

    left:0;

    right:0;

    z-index:99999999;

    display:none;

    max-height:420px;

    overflow-y:auto;

    background:

        linear-gradient(
            180deg,
            rgba(12,12,12,.98),
            rgba(4,4,4,.98)
        );

    border:
        1px solid
        var(--old-gold-dark);

    border-radius:4px;

    box-shadow:

        0 15px 40px
        rgba(0,0,0,.95),

        0 0 12px
        rgba(150,90,20,.15);

}


/* =====================================================
   SEARCH ITEM
===================================================== */

.search-item{

    position:relative;

    display:flex;

    align-items:center;

    gap:10px;

    min-height:65px;

    padding:8px 10px;

    border-bottom:
        1px solid
        rgba(255,255,255,.05);

    transition:
        background .18s,
        transform .18s;

}


.search-item:hover{

    background:

        linear-gradient(
            90deg,
            rgba(125,15,15,.28),
            rgba(100,70,20,.12),
            transparent
        );

    transform:
        translateX(2px);

}


/* =====================================================
   SEARCH IMAGE
===================================================== */

.search-image{

    width:48px;

    height:48px;

    flex-shrink:0;

    overflow:hidden;

    background:#111;

    border:
        1px solid
        rgba(184,138,50,.35);

    border-radius:3px;

}


.search-image img{

    width:100%;

    height:100%;

    object-fit:cover;

}


/* =====================================================
   SEARCH INFO
===================================================== */

.search-info{

    min-width:0;

    flex:1;

}


.search-name{

    color:#eee;

    font-size:13px;

    font-weight:900;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}


.search-type{

    margin-top:3px;

    color:#777;

    font-size:10px;

}


.search-price{

    margin-top:3px;

    color:
        var(--old-gold-light);

    font-size:12px;

    font-weight:900;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .header-row{

        min-height:58px;

        padding:
            6px 9px;

    }


    .logo{

        gap:7px;

    }


    .logo img{

        width:37px;

        height:37px;

    }


    .logo-text h1{

        font-size:15px;

    }


    .logo-text p{

        font-size:7px;

        letter-spacing:.8px;

    }


    .header-actions{

        gap:4px;

    }


    .icon-btn{

        width:36px;

        height:36px;

    }


    .icon-btn i{

        font-size:14px;

    }


    .search-wrapper{

        margin:
            8px 9px 11px;

        width:
            calc(100% - 18px);

    }


    .search-box{

        min-height:40px;

    }


    .search-box input{

        height:40px;

        font-size:12px;

    }


    .search-box button{

        width:42px;

        height:40px;

    }


    .search-results{

        top:45px;

    }


    .search-image{

        width:44px;

        height:44px;

    }


}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .logo-text p{

        display:none;

    }


    .logo img{

        width:35px;

        height:35px;

    }


    .logo-text h1{

        font-size:14px;

    }


    .icon-btn{

        width:34px;

        height:34px;

    }

}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    .app-header,
    .icon-btn,
    .search-box,
    .search-item{

        transition:none;

    }

}

  </style>
  
<style>

/* =====================================================
   CNTECH STORE
   INDEX.CSS
   FPS + MOBA OLD VISUAL 2013–2016
   FANTASY / SCI-FI / RPG
   DARK / FIRE / GOLD / ENERGY
   HIGH MOTION 90%
===================================================== */



/* =====================================================
   RESET
===================================================== */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

/* =====================================================
   CNTECH BACKGROUND SLIDESHOW
   2-4 IMAGES / RANDOM ORDER / UP-DOWN
===================================================== */


        

 /* =====================================================
   CNTECH BACKGROUND SLIDESHOW - FIX
===================================================== */


/* =====================================================
   BACKGROUND IMAGE
===================================================== */

.cn-bg{

    position:fixed;

    inset:0;

    z-index:0;

    overflow:hidden;

    pointer-events:none;

    background:#020204;
}


/* =====================================================
   BRIGHT BACKGROUND
===================================================== */

.cn-bg-image{

    position:absolute;

    top:-8%;
    left:-8%;

    width:116%;
    height:116%;

    object-fit:cover;

    /* ภาพชัดขึ้น */
    opacity: 2;

    display:block;

    filter:
        brightness(.95)
        saturate(1.05)
        contrast(1.08);

    will-change:
        transform,
        opacity;

    transition:
        opacity 1.5s ease,
        transform 8s cubic-bezier(.22,.61,.36,1);
}


/* =====================================================
   DARK OVERLAY — ลดความดำ
===================================================== */

.cn-bg:after{

    content:"";

    position:absolute;

    inset:0;

    z-index:2;

    background:

        linear-gradient(
            180deg,
            rgba(0,0,0,0.079),
            rgba(160,160,160,0.379) 50%,
            rgba(95,93,93,0.154)
        );

}




/* =====================================================
   RED / GOLD ATMOSPHERE
===================================================== */

.cn-bg:before{

    content:"";

    position:absolute;

    inset:0;

    z-index:3;

    background:
        radial-gradient(
            circle at 50% 10%,
            rgba(255,40,0,.14),
            transparent 35%
        ),
        radial-gradient(
            circle at 15% 60%,
            rgba(255,120,0,.06),
            transparent 30%
        ),
        radial-gradient(
            circle at 85% 40%,
            rgba(139,92,255,.05),
            transparent 30%
        );

    mix-blend-mode:screen;

}


/* =====================================================
   CONTENT MUST BE ABOVE BACKGROUND
===================================================== */

body > *:not(.cn-bg){

    position:relative;

    z-index:1;
}


/* =====================================================
   SLIDE DOWN
===================================================== */

.cn-bg-image.slide-down{

    transform:
        scale(1.08)
        translate3d(0,5%,0);

}


/* =====================================================
   SLIDE UP
===================================================== */

.cn-bg-image.slide-up{

    transform:
        scale(1.08)
        translate3d(0,-5%,0);

}


/* =====================================================
   SLIDE LEFT
===================================================== */

.cn-bg-image.slide-left{

    transform:
        scale(1.08)
        translate3d(-3%,0,0);

}


/* =====================================================
   SLIDE RIGHT
===================================================== */

.cn-bg-image.slide-right{

    transform:
        scale(1.08)
        translate3d(3%,0,0);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .cn-bg-image{

        top:-12%;
        left:-12%;

        width:124%;
        height:124%;

        filter:
            brightness(.50)
            saturate(.85)
            contrast(1.1);

    }

  }

        




        

@keyframes particleMove{

    0%{
        transform:
            translate3d(0,0,0)
            scale(1);
    }

    50%{
        transform:
            translate3d(-35px,-70px,0)
            scale(1.05);
    }

    100%{
        transform:
            translate3d(20px,-150px,0)
            scale(1);
    }
}






/* =====================================================
   CART BADGE
===================================================== */

.cart-badge{

    position:absolute;

    top:-6px;
    right:-6px;

    min-width:20px;
    height:20px;

    padding:0 5px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:50%;

    background:

        linear-gradient(
            135deg,
            var(--red),
            var(--red-dark)
        );

    color:#fff;

    font-size:10px;

    font-weight:900;

    border:
        1px solid
        rgba(255,255,255,.25);

    box-shadow:

        0 0 10px
        rgba(255,32,32,.8),

        0 0 25px
        rgba(255,32,32,.35);

    animation:
        badgePulse
        .6s
        infinite;
}

@keyframes badgePulse{

    0%,100%{
        transform:
            scale(1);
    }

    50%{
        transform:
            scale(1.2);
    }
}


/* =====================================================
   SEARCH
===================================================== */

.search-wrapper{

    position:relative;

    width:100%;

    max-width:700px;

    margin:15px auto;
}

.search-box{

    display:flex;

    overflow:hidden;

    border-radius:8px;

    background:
        rgba(8,8,12,.95);

    border:
        1px solid
        rgba(255,210,100,.15);

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.04),

        0 10px 35px
        rgba(0,0,0,.8);

    transition:.25s;
}

.search-box:focus-within{

    border-color:
        var(--gold);

    box-shadow:

        0 0 12px
        rgba(255,32,32,.6),

        0 0 30px
        rgba(255,170,0,.18);
}

.search-box input{

    flex:1;

    min-width:0;

    padding:14px 16px;

    border:0;

    outline:0;

    color:#fff;

    background:transparent;
}

.search-box input::placeholder{

    color:#555;
}




/* =====================================================
   SEARCH RESULTS
===================================================== */

.search-results{

    position:absolute;

    top:60px;

    left:0;
    right:0;
    z-index: 99999;
    max-height:500px;

    overflow-y:auto;

    display:none;

    background:
        rgba(7,7,12,.97);

    border:
        1px solid
        rgba(255,210,100,.35);

    border-radius:8px;

    box-shadow:

        0 25px 70px
        rgba(0,0,0,.95),

        0 0 25px
        rgba(255,32,32,.12);

    backdrop-filter:
        blur(18px);

    scrollbar-width:thin;

    scrollbar-color:
        var(--gold)
        #111;
}

.search-results::-webkit-scrollbar{
    width:5px;
}

.search-results::-webkit-scrollbar-thumb{

    background:

        linear-gradient(
            var(--red),
            var(--gold)
        );

    border-radius:10px;
}


/* =====================================================
   SEARCH ITEM
===================================================== */

.search-item{

    position:relative;

    display:flex;

    align-items:center;

    gap:12px;

    padding:11px 13px;

    border-bottom:
        1px solid
        rgba(255,255,255,.05);

    overflow:hidden;

    transition:.2s;
}

.search-item:hover{

    background:

        linear-gradient(
            90deg,
            rgba(255,32,32,.12),
            rgba(255,180,0,.06),
            transparent
        );

    transform:
        translateX(4px);
}

.search-image{

    width:52px;
    height:52px;

    min-width:52px;

    overflow:hidden;

    border-radius:6px;

    background:#15151d;

    border:
        1px solid
        rgba(255,210,100,.2);
}

.search-image img{

    width:100%;
    height:100%;

    object-fit:cover;

    transition:.3s;
}

.search-item:hover
.search-image img{

    transform:
        scale(1.12);

    filter:
        brightness(1.15)
        saturate(1.2);
}

.search-info{

    flex:1;

    min-width:0;
}

.search-name{

    font-size:14px;

    font-weight:900;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;
}

.search-type{

    margin-top:3px;

    font-size:11px;

    color:#666;
}

.search-price{

    margin-top:3px;

    color:var(--gold);

    font-size:13px;

    font-weight:900;

    text-shadow:
        0 0 10px
        rgba(255,210,90,.45);
}


/* =====================================================
   SECTION TITLE
===================================================== */

.section-title{

    position:relative;

    margin:30px 0 17px;

    padding-left:15px;

    font-size:20px;

    font-weight:900;

    letter-spacing:.3px;

    text-shadow:
        2px 2px 0 #000,
        0 0 10px
        rgba(255,32,32,.25);
}

.section-title:before{

    content:"";

    position:absolute;

    left:0;

    top:2px;

    width:5px;

    height:85%;

    background:

        linear-gradient(
            180deg,
            var(--red),
            var(--orange),
            var(--gold)
        );

    box-shadow:

        0 0 10px
        var(--red),

        0 0 25px
        rgba(255,140,0,.4);
}

.section-title:after{

    content:"";

    position:absolute;

    left:15px;

    bottom:-7px;

    width:60px;

    height:2px;

    background:

        linear-gradient(
            90deg,
            var(--red),
            var(--gold),
            transparent
        );

    box-shadow:
        0 0 8px
        var(--red);

    animation:
        titleEnergy
        .7s
        ease-in-out
        infinite alternate;
}

@keyframes titleEnergy{

    from{
        width:45px;
    }

    to{
        width:130px;
    }
}


/* =====================================================
   GRID
===================================================== */

.game-grid,
.product-grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:12px;
}


/* =====================================================
   OLD VISUAL CARD
===================================================== */

.game-card,
.product-card,
.card{

    position:relative;

    overflow:hidden;

    background:

  rgba(122,0,0,0)
  
        ;

    border:
        1px solid
        rgba(255,210,100,.22);

    /*
       NOT MODERN ROUND CARD
       OLD GAME UI
    */

    border-radius:3px;

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.08),

        inset 0 0 0 1px
        rgba(0,0,0,.9),

        0 15px 40px
        rgba(0,0,0,.85);
}


/* =====================================================
   ANGLED CORNERS
===================================================== */

.game-card,
.product-card,
.card{

    clip-path:
        polygon(
            0 10px,
            10px 0,
            calc(100% - 10px) 0,
            100% 10px,
            100% calc(100% - 10px),
            calc(100% - 10px) 100%,
            10px 100%,
            0 calc(100% - 10px)
        );
}


/* =====================================================
   GOLD FRAME
===================================================== */

.game-card:after,
.product-card:after,
.card:after{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    border:
        1px solid
        rgba(255,210,100,.25);

    box-shadow:

        inset 0 0 0 1px
        rgba(0,0,0,.9),

        inset 0 0 25px
        rgba(255,32,32,.08);
}


/* =====================================================
   MOVING LIGHT
===================================================== */

.game-card:before,
.product-card:before,
.card:before{

    content:"";

    position:absolute;

    top:-100%;

    left:-80%;

    width:55%;

    height:300%;

    z-index:6;

    pointer-events:none;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.02),
            rgba(255,210,90,.16),
            rgba(255,32,32,.12),
            transparent
        );

    transform:
        rotate(25deg);

    animation:
        cardSweep
        2.1s
        linear
        infinite;
}

@keyframes cardSweep{

    0%{
        left:-80%;
    }

    100%{
        left:160%;
    }
}


/* =====================================================
   CARD HOVER
===================================================== */

.game-card,
.product-card,
.card{

    transition:
        transform .18s,
        border-color .18s,
        box-shadow .18s;
}

.game-card:hover,
.product-card:hover,
.card:hover{

    transform:
        translateY(-7px)
        scale(1.025);

    border-color:
        var(--gold);

    box-shadow:

        0 0 12px
        rgba(255,32,32,.65),

        0 0 28px
        rgba(255,160,0,.18),

        0 20px 45px
        rgba(0,0,0,.95);
}

.game-card:active,
.product-card:active,
.card:active{

    transform:
        translateY(0)
        scale(.94);
}


/* =====================================================
   CARD IMAGE
===================================================== */

.game-card img,
.product-card img{

    width:100%;

    height:130px;

    display:block;

    object-fit:cover;

    transition:
        transform .35s,
        filter .35s;
}

.game-card:hover img,
.product-card:hover img{

    transform:
        scale(1.09);

    filter:
        brightness(1.12)
        contrast(1.08)
        saturate(1.18);
}


/* =====================================================
   CARD CONTENT
===================================================== */

.game-card-content,
.product-content{

    padding:12px;

    position:relative;

    z-index:10;
}

.game-card-title,
.product-title{

    font-size:14px;

    font-weight:900;

    line-height:1.3;

    text-shadow:
        1px 1px 0 #000;
}

.game-card-meta{

    margin-top:5px;

    font-size:11px;

    color:#777;
}


/* =====================================================
   PRICE
===================================================== */

.price{

    color:var(--gold);

    font-weight:900;

    text-shadow:

        0 0 8px
        rgba(255,210,90,.55),

        1px 1px 0 #000;
}

.old-price{

    color:#555;

    font-size:11px;

    text-decoration:
        line-through;
}

.discount{

    display:inline-block;

    margin-top:5px;

    padding:4px 7px;

    border-radius:3px;

    color:#fff;

    background:

        linear-gradient(
            135deg,
            var(--red),
            var(--red-dark)
        );

    font-size:10px;

    font-weight:900;

    box-shadow:
        0 0 10px
        rgba(255,32,32,.45);
}


/* =====================================================
   STATUS
===================================================== */

.status-active{

    color:#62ffb0;

    text-shadow:
        0 0 10px
        rgba(98,255,176,.4);
}

.status-active:before{

    content:"";

    display:inline-block;

    width:7px;
    height:7px;

    margin-right:5px;

    border-radius:50%;

    background:#62ffb0;

    box-shadow:
        0 0 10px #62ffb0;

    animation:
        statusPulse
        .55s
        infinite;
}

@keyframes statusPulse{

    0%,100%{
        transform:
            scale(1);
    }

    50%{
        transform:
            scale(1.45);
    }
}

.status-inactive{

    color:#ff5555;
}


/* =====================================================
   PRIMARY BUTTON
===================================================== */

.btn-primary{

    position:relative;

    overflow:hidden;

    border:0;

    border-radius:3px;

    padding:13px 18px;

    color:#fff;

    font-weight:900;

    cursor:pointer;

    background:

        linear-gradient(
            135deg,
            var(--red),
            var(--red-dark)
        );

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.15),

        0 0 12px
        rgba(255,32,32,.5),

        0 0 25px
        rgba(255,32,32,.2);

    transition:
        transform .15s,
        box-shadow .15s;
}

.btn-primary:before{

    content:"";

    position:absolute;

    top:0;
    left:-100%;

    width:60%;
    height:100%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.35),
            transparent
        );

    transform:
        skewX(-20deg);

    animation:
        buttonSweep
        1.2s
        linear
        infinite;
}

@keyframes buttonSweep{

    0%{
        left:-100%;
    }

    100%{
        left:150%;
    }
}

.btn-primary:hover{

    transform:
        translateY(-3px)
        scale(1.03);

    box-shadow:

        0 0 18px
        rgba(255,32,32,.85),

        0 0 40px
        rgba(255,100,0,.35);
}

.btn-primary:active{

    transform:
        scale(.88);
}


/* =====================================================
   BOTTOM NAV
===================================================== */

.bottom-nav{

    position:fixed;

    left:0;
    right:0;
    bottom:0;

    z-index:9998;

    height:70px;

    display:flex;

    align-items:center;

    justify-content:space-around;

    padding:6px 8px;

    background:

        linear-gradient(
            180deg,
            rgba(5,5,9,.86),
            rgba(2,2,4,.98)
        );

    backdrop-filter:
        blur(18px);

    -webkit-backdrop-filter:
        blur(18px);

    border-top:
        1px solid
        rgba(255,210,100,.16);

    box-shadow:

        0 -10px 40px
        rgba(0,0,0,.9);
}

.bottom-nav:before{

    content:"";

    position:absolute;

    left:0;
    right:0;

    top:-2px;

    height:2px;

    background:

        linear-gradient(
            90deg,
            transparent,
            var(--red),
            var(--gold),
            var(--orange),
            var(--red),
            transparent
        );

    background-size:
        250% 100%;

    animation:
        navEnergy
        .8s
        linear
        infinite;
}

@keyframes navEnergy{

    from{
        background-position:
            0% 50%;
    }

    to{
        background-position:
            250% 50%;
    }
}

.bottom-nav a{

    position:relative;

    min-width:55px;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    gap:4px;

    color:#666;

    font-size:10px;

    transition:
        .2s;
}

.bottom-nav a i{

    font-size:19px;

    transition:
        .2s;
}

.bottom-nav a:hover{

    color:#fff;

    transform:
        translateY(-4px);
}

.bottom-nav a:hover i{

    color:var(--gold);

    text-shadow:

        0 0 8px
        var(--red),

        0 0 18px
        var(--gold);

    transform:
        scale(1.15);
}

.bottom-nav a.active{

    color:#fff;
}

.bottom-nav a.active i{

    color:var(--red);

    text-shadow:

        0 0 8px
        var(--red),

        0 0 20px
        var(--orange),

        0 0 35px
        var(--gold);

    animation:
        navIconPower
        .7s
        infinite alternate;
}

@keyframes navIconPower{

    from{
        transform:
            scale(1);
    }

    to{
        transform:
            scale(1.18);
    }
}


  /* =====================================================
   FORTUNE POPUP
===================================================== */

.fortune-overlay{

    display:none;

    position:fixed;

    inset:0;

    z-index:999999;

    align-items:center;

    justify-content:center;

    padding:20px;

    background:
        rgba(0,0,0,.88);

    backdrop-filter:
        blur(10px);
}

.fortune-popup{

    position:relative;

    width:100%;

    max-width:400px;

    padding:30px 24px;

    text-align:center;

    background:

        linear-gradient(
            145deg,
            #191318,
            #060609
        );

    border:
        1px solid
        var(--gold);

    border-radius:4px;

    box-shadow:

        inset 0 0 30px
        rgba(255,32,32,.08),

        0 30px 90px
        #000,

        0 0 30px
        rgba(255,32,32,.35);

    animation:
        popupIn
        .22s
        ease-out;
}

@keyframes popupIn{

    from{

        opacity:0;

        transform:
            scale(.75)
            translateY(30px);
    }

    to{

        opacity:1;

        transform:
            scale(1)
            translateY(0);
    }
}

.fortune-close{

    position:absolute;

    right:10px;
    top:10px;

    width:35px;
    height:35px;

    border:0;

    border-radius:3px;

    background:
        rgba(255,255,255,.05);

    color:#888;

    font-size:22px;

    cursor:pointer;
}

.fortune-icon{

    width:72px;
    height:72px;

    margin:auto;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:50%;

    background:
        #350909;

    border:
        1px solid
        var(--red);

    color:
        var(--red);

    font-size:30px;

    box-shadow:

        0 0 15px
        rgba(255,32,32,.7),

        0 0 40px
        rgba(255,120,0,.25);

    animation:
        fortuneFire
        .65s
        infinite;
}

@keyframes fortuneFire{

    0%,100%{
        transform:
            scale(1)
            rotate(0deg);
    }

    50%{
        transform:
            scale(1.1)
            rotate(3deg);
    }
}

.fortune-popup h2{

    margin:
        18px 0 7px;

    background:

        linear-gradient(
            90deg,
            #fff,
            var(--gold),
            var(--red)
        );

    -webkit-background-clip:text;

    background-clip:text;

    -webkit-text-fill-color:transparent;
}

.fortune-status{

    color:
        var(--red);

    font-weight:900;

    text-shadow:
        0 0 10px
        rgba(255,32,32,.5);
}

.fortune-popup p{

    margin-top:8px;

    color:#888;

    line-height:1.7;

    font-size:13px;
}

.fortune-btn{

    width:100%;

    margin-top:17px;

    padding:13px;

    border:0;

    border-radius:3px;

    background:

        linear-gradient(
            135deg,
            var(--red),
            var(--red-dark)
        );

    color:#fff;

    font-weight:900;

    cursor:pointer;

    box-shadow:

        0 0 20px
        rgba(255,32,32,.4);
}


/* =====================================================
   ANIMATION OFF
===================================================== */

.animation-off *,
.animation-off *:before,
.animation-off *:after{

    animation:none !important;

    transition:none !important;
}

.animation-off body:before,
.animation-off body:after{

    animation:none !important;

    opacity:.15 !important;
}

.animation-off .app-header:after,
.animation-off .bottom-nav:before{

    animation:none !important;

    box-shadow:none;
}

.animation-off .logo img{

    animation:none !important;
}

.animation-off .logo-text h1{

    animation:none !important;
}

.animation-off .cart-badge{

    animation:none !important;
}

.animation-off .game-card:before,
.animation-off .product-card:before,
.animation-off .card:before{

    animation:none !important;

    display:none;
}

.animation-off .btn-primary:before{

    animation:none !important;

    display:none;
}

.animation-off .status-active:before{

    animation:none !important;
}

.animation-off .fortune-icon{

    animation:none !important;
}

.animation-off .bottom-nav a.active i{

    animation:none !important;
}


/* =====================================================
   DESKTOP
===================================================== */

@media(min-width:700px){

    .game-grid{

        grid-template-columns:
            repeat(4,1fr);
    }

    .product-grid{

        grid-template-columns:
            repeat(4,1fr);
    }
}

@media(min-width:1100px){

    .game-grid{

        grid-template-columns:
            repeat(6,1fr);
    }

    .product-grid{

        grid-template-columns:
            repeat(5,1fr);
    }
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .container{

        padding:
            10px;
    }

    .header-row{

        min-height:62px;
    }

    .logo img{

        width:40px;
        height:40px;
    }

    .logo-text h1{

        font-size:16px;
    }

    .search-wrapper{

        margin:
            12px auto;
    }

    .search-results{

        max-height:
            65vh;
    }

    .bottom-nav{

        height:68px;
    }

    .game-grid,
    .product-grid{

        gap:8px;
    }
}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .logo-text{

        display:none;
    }

    .header-actions{

        gap:4px;
    }

    .icon-btn{

        width:38px;
        height:38px;
    }

    .game-grid,
    .product-grid{

        grid-template-columns:
            repeat(2,1fr);

        gap:7px;
    }
}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    *,
    *:before,
    *:after{

        animation:
            none !important;

        transition:
            none !important;
    }
}

  
/* =====================================================
   BOTTOM NAV
===================================================== */

.bottom-nav{
    position:fixed;

    left:0;
    right:0;
    bottom:0;

    z-index:9998;

    height:70px;

    padding:7px 10px;

    display:flex;
    align-items:center;
    justify-content:space-around;

    background:
        linear-gradient(
            180deg,
            rgba(8,8,14,.88),
            rgba(2,2,5,.98)
        );

    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);

    border-top:
        1px solid
        rgba(255,255,255,.09);

    box-shadow:
        0 -15px 45px #000d,
        0 -2px 20px #ff202018;
}


/* BOTTOM ENERGY */

.bottom-nav:before{
    content:"";

    position:absolute;

    left:0;
    right:0;
    top:-2px;

    height:2px;

    background:
        linear-gradient(
            90deg,
            transparent,
            var(--red),
            var(--gold),
            var(--purple),
            var(--blue),
            var(--red),
            transparent
        );

    background-size:300% 100%;

    animation:
        bottomEnergy
        .9s
        linear
        infinite;

    box-shadow:
        0 0 8px var(--red),
        0 0 18px var(--gold);
}

@keyframes bottomEnergy{

    0%{
        background-position:0% 50%;
    }

    100%{
        background-position:300% 50%;
    }
}


/* NAV ITEM */

.bottom-nav a{

    position:relative;

    min-width:60px;

    height:56px;

    display:flex;

    flex-direction:column;

    align-items:center;
    justify-content:center;

    gap:4px;

    color:#666;

    font-size:10px;
    font-weight:800;

    transition:
        .2s
        cubic-bezier(.2,.8,.2,1);
}


/* NAV ICON */

.bottom-nav a i{

    font-size:19px;

    transition:
        .2s
        cubic-bezier(.2,.8,.2,1);
}


/* ACTIVE */

.bottom-nav a.active{

    color:#fff;

    text-shadow:
        0 0 8px #fff,
        0 0 15px var(--red);
}

.bottom-nav a.active i{

    color:var(--red);

    transform:
        translateY(-3px)
        scale(1.15);

    text-shadow:
        0 0 8px var(--red),
        0 0 18px var(--red),
        0 0 30px #ff7a0066;
}


/* ACTIVE INDICATOR */

.bottom-nav a.active:after{

    content:"";

    position:absolute;

    bottom:1px;

    width:24px;
    height:3px;

    border-radius:10px;

    background:
        linear-gradient(
            90deg,
            var(--red),
            var(--gold)
        );

    box-shadow:
        0 0 8px var(--red),
        0 0 15px var(--gold);

    animation:
        navPower
        .8s
        ease-in-out
        infinite alternate;
}

@keyframes navPower{

    from{
        width:18px;
        opacity:.55;
    }

    to{
        width:32px;
        opacity:1;
    }
}


/* NAV HOVER */

.bottom-nav a:hover{

    color:#fff;

    transform:
        translateY(-2px);
}

.bottom-nav a:hover i{

    color:var(--gold);

    transform:
        scale(1.15);

    text-shadow:
        0 0 10px var(--gold);
}


/* NAV ACTIVE PRESS */

.bottom-nav a:active{

    transform:
        scale(.88);
}


/* =====================================================
   OLD GAME STYLE DIVIDERS
===================================================== */

.game-divider{

    position:relative;

    width:100%;

    height:18px;

    margin:20px 0;

    overflow:hidden;
}

.game-divider:before{

    content:"";

    position:absolute;

    left:0;
    right:0;

    top:50%;

    height:1px;

    background:
        linear-gradient(
            90deg,
            transparent,
            #ff202055,
            #ffd15caa,
            #ff202055,
            transparent
        );

    box-shadow:
        0 0 10px #ff202044;
}

.game-divider:after{

    content:"◆";

    position:absolute;

    left:50%;
    top:50%;

    transform:
        translate(-50%,-50%);

    padding:0 10px;

    color:var(--gold);

    background:#050509;

    font-size:10px;

    text-shadow:
        0 0 8px var(--gold),
        0 0 15px var(--orange);
}


/* =====================================================
   OLD VISUAL PANEL
===================================================== */

.game-panel{

    position:relative;

    padding:18px;

    margin-top:15px;

    background:
        linear-gradient(
            145deg,
            rgba(25,25,35,.96),
            rgba(5,5,10,.98)
        );

    border:
        1px solid
        rgba(255,209,92,.22);

    box-shadow:
        inset 0 1px 0 #ffffff0a,
        inset 0 0 25px #ff20200a,
        0 15px 45px #000b;

    clip-path:
        polygon(
            0 10px,
            10px 0,
            calc(100% - 10px) 0,
            100% 10px,
            100% calc(100% - 10px),
            calc(100% - 10px) 100%,
            10px 100%,
            0 calc(100% - 10px)
        );
}


/* GOLD CORNER */

.game-panel:before{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    border:
        1px solid
        #ffd15c33;

    clip-path:
        polygon(
            0 12px,
            12px 0,
            calc(100% - 12px) 0,
            100% 12px,
            100% calc(100% - 12px),
            calc(100% - 12px) 100%,
            12px 100%,
            0 calc(100% - 12px)
        );
}


/* =====================================================
   OLD VISUAL TITLE
===================================================== */

.game-title{

    position:relative;

    display:flex;

    align-items:center;

    gap:10px;

    margin-bottom:14px;

    font-size:18px;

    font-weight:900;

    letter-spacing:.4px;

    color:#fff;

    text-shadow:
        0 0 8px #ff202055;
}

.game-title:before{

    content:"";

    width:5px;
    height:24px;

    background:
        linear-gradient(
            180deg,
            var(--red),
            var(--gold),
            var(--orange)
        );

    box-shadow:
        0 0 10px var(--red),
        0 0 18px var(--gold);

    transform:
        skewX(-12deg);
}


/* =====================================================
   HEXAGON / OCTAGON GAME FRAME
===================================================== */

.game-frame{

    position:relative;

    padding:2px;

    background:
        linear-gradient(
            135deg,
            #ffd15c,
            #8b5a14,
            #ff2020,
            #3b0808,
            #ffd15c
        );

    background-size:
        250% 250%;

    animation:
        frameEnergy
        2s
        linear
        infinite;

    clip-path:
        polygon(
            8px 0,
            calc(100% - 8px) 0,
            100% 8px,
            100% calc(100% - 8px),
            calc(100% - 8px) 100%,
            8px 100%,
            0 calc(100% - 8px),
            0 8px
        );

    box-shadow:
        0 0 12px #ff202055,
        0 0 30px #ffd15c22;
}

@keyframes frameEnergy{

    0%{
        background-position:0% 50%;
    }

    100%{
        background-position:250% 50%;
    }
}

.game-frame-inner{

    position:relative;

    background:
        linear-gradient(
            145deg,
            #161620,
            #050508
        );

    padding:14px;

    clip-path:
        polygon(
            7px 0,
            calc(100% - 7px) 0,
            100% 7px,
            100% calc(100% - 7px),
            calc(100% - 7px) 100%,
            7px 100%,
            0 calc(100% - 7px),
            0 7px
        );
}


/* =====================================================
   OCTAGON IMAGE
===================================================== */

.game-image-octagon{

    position:relative;

    overflow:hidden;

    clip-path:
        polygon(
            12% 0,
            88% 0,
            100% 12%,
            100% 88%,
            88% 100%,
            12% 100%,
            0 88%,
            0 12%
        );

    border:
        2px solid
        #ffd15c55;

    box-shadow:
        0 0 15px #ff202033;
}

.game-image-octagon img{

    width:100%;
    height:100%;

    display:block;

    object-fit:cover;

    transition:
        .35s
        cubic-bezier(.2,.8,.2,1);
}

.game-image-octagon:hover img{

    transform:
        scale(1.08);

    filter:
        brightness(1.12)
        contrast(1.08)
        saturate(1.18);
}


/* =====================================================
   FPS HUD
===================================================== */

.fps-hud{

    position:relative;

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:7px;

    margin-top:12px;
}

.fps-stat{

    padding:9px 7px;

    text-align:center;

    background:
        linear-gradient(
            145deg,
            #15151e,
            #08080c
        );

    border:
        1px solid
        #ffffff0b;

    color:#888;

    font-size:9px;

    text-transform:uppercase;

    letter-spacing:.5px;
}

.fps-stat strong{

    display:block;

    margin-top:3px;

    color:#fff;

    font-size:13px;

    text-shadow:
        0 0 8px #ff202033;
}


/* =====================================================
   FIRE ENERGY BAR
===================================================== */

.energy-bar{

    position:relative;

    height:6px;

    margin-top:12px;

    overflow:hidden;

    background:#09090d;

    border:
        1px solid
        #ffffff0a;
}

.energy-bar span{

    position:absolute;

    left:0;
    top:0;
    bottom:0;

    width:70%;

    background:
        linear-gradient(
            90deg,
            #8b0000,
            var(--red),
            var(--orange),
            var(--gold)
        );

    box-shadow:
        0 0 8px var(--red),
        0 0 18px var(--orange);

    animation:
        energyBar
        1s
        ease-in-out
        infinite alternate;
}

@keyframes energyBar{

    from{
        filter:brightness(.75);
    }

    to{
        filter:brightness(1.3);
    }
}


/* =====================================================
   RANK BADGE
===================================================== */

.rank-badge{

    display:inline-flex;

    align-items:center;
    justify-content:center;

    min-width:80px;

    padding:6px 12px;

    background:
        linear-gradient(
            135deg,
            #4b2d09,
            #161009
        );

    border:
        1px solid
        #ffd15c77;

    color:var(--gold);

    font-size:10px;

    font-weight:900;

    letter-spacing:1px;

    text-shadow:
        0 0 8px #ffd15c88;

    box-shadow:
        inset 0 0 12px #ffd15c11,
        0 0 12px #ffd15c22;

    clip-path:
        polygon(
            8px 0,
            calc(100% - 8px) 0,
            100% 50%,
            calc(100% - 8px) 100%,
            8px 100%,
            0 50%
        );
}


/* =====================================================
   OLD GAME BUTTON
===================================================== */

.game-btn{

    position:relative;

    width:100%;

    padding:13px 16px;

    border:1px solid
        #ffd15c55;

    color:#fff;

    font-weight:900;

    background:
        linear-gradient(
            180deg,
            #401010,
            #180707
        );

    cursor:pointer;

    clip-path:
        polygon(
            10px 0,
            calc(100% - 10px) 0,
            100% 10px,
            100% calc(100% - 10px),
            calc(100% - 10px) 100%,
            10px 100%,
            0 calc(100% - 10px),
            0 10px
        );

    box-shadow:
        inset 0 0 15px #ff202022,
        0 0 12px #ff202022;

    transition:.2s;
}

.game-btn:hover{

    color:var(--gold);

    border-color:
        var(--gold);

    background:
        linear-gradient(
            180deg,
            #681212,
            #210707
        );

    box-shadow:
        0 0 15px #ff202055,
        0 0 30px #ffd15c22;
}

.game-btn:active{

    transform:
        scale(.95);
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .container{
        padding:10px;
    }

    .header-row{
        min-height:60px;
    }

    .logo img{
        width:40px;
        height:40px;
    }

    .logo-text h1{
        font-size:16px;
    }

    .game-grid,
    .product-grid{
        grid-template-columns:
            repeat(2,1fr);

        gap:9px;
    }

    .game-card img,
    .product-card img{
        height:115px;
    }

    .section-title{
        font-size:18px;
    }

    .bottom-nav{
        height:67px;
    }

    .bottom-nav a{
        min-width:50px;
        font-size:9px;
    }

    .bottom-nav a i{
        font-size:18px;
    }

    .fps-hud{
        gap:4px;
    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .logo-text{
        display:none;
    }

    .game-grid,
    .product-grid{
        gap:7px;
    }

    .game-card img,
    .product-card img{
        height:105px;
    }

    .game-card-content,
    .product-content{
        padding:9px;
    }

    .game-card-title,
    .product-title{
        font-size:12px;
    }

    .game-card-meta{
        font-size:10px;
    }

}


/* =====================================================
   ANIMATION OFF
===================================================== */

.animation-off *,
.animation-off *:before,
.animation-off *:after{

    animation:none !important;

    transition:none !important;
}

.animation-off body:before,
.animation-off body:after{

    animation:none !important;

    opacity:.15 !important;
}

.animation-off .app-header:after{

    animation:none !important;

    box-shadow:none;
}

.animation-off .logo img{

    animation:none !important;

    box-shadow:
        0 0 8px #ff202033;
}

.animation-off .cart-badge,
.animation-off .status-active:before{

    animation:none !important;
}

.animation-off .game-card:before,
.animation-off .product-card:before,
.animation-off .card:before{

    animation:none !important;

    display:none;
}

.animation-off .game-frame{

    animation:none !important;
}

.animation-off .energy-bar span{

    animation:none !important;
}

.animation-off .bottom-nav:before{

    animation:none !important;
}

.animation-off .section-title:after{

    animation:none !important;
}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    *,
    *:before,
    *:after{

        animation:none !important;

        transition:none !important;
    }
}


/* =====================================================
   FINAL VISUAL BOOST
   FPS + MOBA OLD SCHOOL
===================================================== */

.game-card,
.product-card,
.game-frame,
.game-panel{

    image-rendering:auto;

    filter:
        drop-shadow(
            0 0 1px
            rgba(255,255,255,.08)
        );
}


/* DARK VIGNETTE */

body{

    position:relative;
}

body{
    isolation:isolate;
}

body:global-vignette{
    content:"";
}

body{
    background-color: hsla(60,0%,16.2%,0.052);
}


/* =====================================================
   END INDEX.CSS
===================================================== */

/* =====================================================
   CNTECH MUSIC REACTIVE EFFECT
   FPS + MOBA OLD VISUAL
   FIRE / SPARK / ENERGY
===================================================== */

:root{
    --music-power:0;
    --music-fire:0;
}

/* -----------------------------------------
   FIRE / ENERGY REACTIVE
----------------------------------------- */

.music-reactive{
    --power:var(--music-power);

    transform:
        translateY(calc(var(--power) * -2px))
        scale(calc(1 + var(--power) * .012));

    filter:
        brightness(calc(1 + var(--power) * .18))
        saturate(calc(1 + var(--power) * .35));

    box-shadow:
        0 0 calc(12px + var(--power) * 25px)
            rgba(255,32,32,.45),

        0 0 calc(25px + var(--power) * 55px)
            rgba(255,122,0,.25);
}


/* -----------------------------------------
   FIRE AURA
----------------------------------------- */

.music-fire{

    position:relative;

    isolation:isolate;

}

.music-fire:before{

    content:"";

    position:absolute;

    inset:-15px;

    z-index:-1;

    pointer-events:none;

    border-radius:inherit;

    background:

        radial-gradient(
            ellipse at 50% 100%,
            rgba(255,32,32,.65),
            rgba(255,122,0,.35),
            rgba(255,209,92,.15),
            transparent 70%
        );

    filter:
        blur(
            calc(12px + var(--music-power) * 18px)
        );

    opacity:
        calc(.25 + var(--music-power) * .75);

    transform:
        scaleY(
            calc(.75 + var(--music-power) * .45)
        );

    transform-origin:
        bottom center;

    transition:
        opacity .04s linear,
        transform .04s linear;
}


/* -----------------------------------------
   FIRE TOP
----------------------------------------- */

.music-fire:after{

    content:"";

    position:absolute;

    left:10%;
    right:10%;
    bottom:-8px;

    height:
        calc(18px + var(--music-power) * 35px);

    pointer-events:none;

    background:

        radial-gradient(
            ellipse at 20% 100%,
            #ff2020 0%,
            transparent 55%
        ),

        radial-gradient(
            ellipse at 50% 100%,
            #ff7a00 0%,
            transparent 60%
        ),

        radial-gradient(
            ellipse at 80% 100%,
            #ffd15c 0%,
            transparent 55%
        );

    filter:
        blur(
            calc(4px + var(--music-power) * 5px)
        );

    opacity:
        calc(.3 + var(--music-power) * .7);

    transform:
        scaleY(
            calc(.7 + var(--music-power) * 1.2)
        );

    transform-origin:
        bottom;

    mix-blend-mode:
        screen;

    animation:
        fireWave .12s infinite alternate;
}

@keyframes fireWave{

    from{
        transform:
            scaleY(.85)
            skewX(-2deg);
    }

    to{
        transform:
            scaleY(1.12)
            skewX(2deg);
    }

}


/* -----------------------------------------
   SPARK LAYER
----------------------------------------- */

.music-sparks{

    position:relative;

    overflow:visible;

}

.music-sparks i{

    position:absolute;

    left:50%;
    bottom:0;

    width:3px;
    height:3px;

    border-radius:50%;

    background:
        #ffd15c;

    box-shadow:
        0 0 5px #ffd15c,
        0 0 12px #ff7a00,
        0 0 20px #ff2020;

    pointer-events:none;

    opacity:0;

}


/* -----------------------------------------
   PARTICLE MOTION
----------------------------------------- */

.music-sparks.active i{

    animation:
        sparkFly
        var(--spark-time,1s)
        linear
        forwards;

}

@keyframes sparkFly{

    0%{

        opacity:0;

        transform:
            translate3d(
                0,
                0,
                0
            )
            scale(.3);

    }

    15%{
        opacity:1;
    }

    70%{
        opacity:.8;
    }

    100%{

        opacity:0;

        transform:
            translate3d(
                var(--spark-x),
                var(--spark-y),
                0
            )
            scale(.05);

    }

}


/* -----------------------------------------
   MUSIC BEAT FLASH
----------------------------------------- */

.music-beat{

    position:relative;

}

.music-beat.beat{

    animation:
        beatImpact
        .16s
        ease-out;

}

@keyframes beatImpact{

    0%{
        filter:
            brightness(1)
            saturate(1);
    }

    35%{

        filter:
            brightness(1.5)
            saturate(1.5);

        box-shadow:
            0 0 15px #ff2020,
            0 0 35px #ff7a00,
            0 0 60px #ffd15c;

    }

    100%{
        filter:
            brightness(1)
            saturate(1);
    }

}


/* -----------------------------------------
   HEADER ENERGY
----------------------------------------- */

.app-header.music-reactive{

    box-shadow:

        0 10px 40px #000c,

        0 0
        calc(10px + var(--music-power) * 30px)
        rgba(255,32,32,.25);

}


.app-header.music-reactive:after{

    height:
        calc(2px + var(--music-power) * 4px);

    box-shadow:

        0 0 8px #ff2020,

        0 0
        calc(18px + var(--music-power) * 25px)
        #ff7a00,

        0 0
        calc(30px + var(--music-power) * 35px)
        #ffd15c;

}


/* -----------------------------------------
   GAME CARD REACTIVE
----------------------------------------- */

.game-card.music-reactive,
.product-card.music-reactive{

    border-color:

        rgba(
            255,
            80,
            30,
            calc(
                .15 +
                var(--music-power) * .65
            )
        );

}

.game-card.music-reactive img,
.product-card.music-reactive img{

    filter:

        brightness(
            calc(1 + var(--music-power) * .15)
        )

        saturate(
            calc(1 + var(--music-power) * .3)
        );

}


/* -----------------------------------------
   REDUCED MOTION
----------------------------------------- */

@media(prefers-reduced-motion:reduce){

    .music-fire:after,
    .music-sparks i,
    .music-beat.beat{

        animation:none !important;

    }

  }


  
</style>

  
</head>

<body>


  
<!-- HEADER -->

<header class="app-header">

    <div class="header-row">


<a href="index.php" class="logo">


<img

src="/assets/lgo.png"

alt="CN Tech Store">


<div class="logo-text">


<h1>
CN Tech Store
</h1>


<p>
Game Topup & IT Store
</p>


</div>


</a>





<div class="header-actions">



<!-- Notification -->

<?php include "notification-bell.php"; ?>




<!-- Cart -->


<a

href="cart.php"

class="icon-btn">


<i class="fa-solid fa-cart-shopping"></i>



<?php if($cartCount > 0): ?>


<span class="cart-badge">

<?= $cartCount ?>

</span>


<?php endif; ?>


</a>





<!-- Profile -->


<a

href="profile.php"

class="icon-btn">


<i class="fa-solid fa-user"></i>


</a>




</div>


    </div>

    

    <div class="search-wrapper">

    <form
        action="index.php"
        method="GET"
        class="search-box"
        autocomplete="off"
    >

        <input
            type="search"
            id="liveSearch"
            name="search"
            placeholder="ຄົ້ນຫາເກມ, ບັດເກມ, ສິນຄ້າ..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
        >

        <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

    </form>

    <div id="searchResults" class="search-results"></div>

  </div>

</header>

<div class="container">
    
    <!-- =================================
     HERO SLIDER
================================= -->

<section class="hero-slider">

<div class="slider-box">

<div class="slides">


<div class="slide active">

<img src="https://wallpapers.com/images/high/mobile-legends-heroes-cover-v0u46grjbqc6h9ga.webp">

<div class="slide-overlay">

<h2>
<i class="fa-solid fa-gamepad"></i> ເຕີມເກມອອນລາຍ
</h2>

<p>
ໄວ • ປອດໄພ • 24 ຊົ່ວໂມງ
</p>

<a href="page/game_topup.php">

ເຕີມເກມ

</a>

</div>

</div>



<div class="slide">

<img src="https://images3.alphacoders.com/135/thumb-350-1350572.webp">

<div class="slide-overlay">

<h2>
<i class="fa-solid fa-fire"></i> Promotion
</h2>

<p>
ໂປຣໂມຊັນພິເສດ
</p>

<a href="page/promotion.php">

ເບິ່ງໂປຣ

</a>

</div>

</div>



<div class="slide">

<img src="https://cntechstore.shop/admin/uploads/blogs/1785478997_1000016867.jpg">

<div class="slide-overlay">

<h2>
<i class="fa-solid fa-money-bill"></i> Payment
</h2>

<p>
BCEL One • QR Payment
</p>

<a href="page/payment-method.php">

ວິທີຊຳລະ

</a>

</div>

</div>


</div>


<div class="slider-dots">

<span class="active"></span>
<span></span>
<span></span>

</div>


</div>

</section>


<style>
/* =====================================================
   CNTECH STORE
   HERO SLIDER
   MMO / MOBA OLD VISUAL 2017–2018
   STAR DOT NAVIGATION
===================================================== */

.hero-slider{
    margin-top:15px;
  z-index
}


/* =====================================================
   SLIDER BOX
===================================================== */

.slider-box{

    position:relative;

    width:100%;

    aspect-ratio:16/9;

    overflow:hidden;

    border-radius:20px;

    background:#050509;

    border:1px solid rgba(255,255,255,.10);

    box-shadow:
        0 15px 50px rgba(0,0,0,.80),
        0 0 35px rgba(255,32,32,.12);

  z-index: -9997;
}


/* =====================================================
   OLD MMO ENERGY BORDER
===================================================== */

.slider-box:after{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    border-radius:20px;

    padding:1px;

    background:

        linear-gradient(
            90deg,
            transparent,
            #6e5220,
            #ffd15c,
            #ff2020,
            #8f6b28,
            transparent
        );

    background-size:300% 100%;

    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);

    -webkit-mask-composite:xor;

    mask-composite:exclude;

    animation:
        oldMobaBorder 6s linear infinite;

}


@keyframes oldMobaBorder{

    0%{
        background-position:0 0;
    }

    100%{
        background-position:300% 0;
    }

}


/* =====================================================
   SLIDES
===================================================== */

.slides,
.slide{

    width:100%;

    height:100%;

}


.slide{

    position:absolute;

    inset:0;

    opacity:0;

    transform:
        scale(1.06);

    transition:

        opacity .7s ease,

        transform 4s
        cubic-bezier(.22,.61,.36,1);

}


.slide.active{

    opacity:1;

    transform:
        scale(1);

}


.slide img{

    width:100%;

    height:100%;

    object-fit:cover;

    display:block;

}


/* =====================================================
   DARK CINEMATIC MMO OVERLAY
===================================================== */

.slide:after{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    background:

        linear-gradient(
            90deg,
            rgba(5,5,9,.82) 0%,
            rgba(5,5,9,.48) 38%,
            rgba(5,5,9,.08) 75%,
            transparent 100%
        ),

        linear-gradient(
            0deg,
            rgba(5,5,9,.90),
            transparent 58%
        );

}


/* =====================================================
   OLD MMO GOLD ATMOSPHERE
===================================================== */

.slide:before{

    content:"";

    position:absolute;

    inset:0;

    z-index:1;

    pointer-events:none;

    background:

        radial-gradient(
            circle at 20% 30%,
            rgba(255,190,60,.10),
            transparent 28%
        ),

        radial-gradient(
            circle at 80% 20%,
            rgba(255,32,32,.08),
            transparent 30%
        );

}


/* =====================================================
   EVENT CONTENT
===================================================== */

.slide-overlay{

    position:absolute;

    z-index:3;

    left:5%;

    bottom:12%;

    max-width:55%;

}


/* =====================================================
   OLD MMO TITLE
===================================================== */

.slide-overlay h2{

    margin:0;

    color:#fff;

    font-size:
        clamp(
            18px,
            3vw,
            34px
        );

    font-weight:1000;

    line-height:1.1;

    text-transform:uppercase;

    letter-spacing:.5px;

    text-shadow:

        2px 2px 0 #000,

        0 3px 15px #000,

        0 0 12px rgba(255,32,32,.35);

}


/* =====================================================
   DESCRIPTION
===================================================== */

.slide-overlay p{

    margin:

        8px 0
        14px;

    color:#ddd;

    font-size:
        clamp(
            10px,
            1.5vw,
            14px
        );

    line-height:1.5;

    text-shadow:
        1px 1px 3px #000;

}


/* =====================================================
   OLD MMO ACTION BUTTON
===================================================== */

.slide-overlay a{

    position:relative;

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:7px;

    min-height:38px;

    padding:
        0 18px;

    overflow:hidden;

    color:#fff;

    text-decoration:none;

    font-size:13px;

    font-weight:900;

    text-shadow:
        1px 1px 0 #000;

    background:

        linear-gradient(
            180deg,
            #634a1b 0%,
            #342309 45%,
            #120d04 100%
        );

    border:
        1px solid #c99b3d;

    box-shadow:

        inset 0 1px 0
        rgba(255,235,160,.35),

        inset 0 -6px 12px
        rgba(0,0,0,.65),

        0 4px 12px
        rgba(0,0,0,.75);

    clip-path:
        polygon(
            7px 0,
            calc(100% - 7px) 0,
            100% 7px,
            100% calc(100% - 7px),
            calc(100% - 7px) 100%,
            7px 100%,
            0 calc(100% - 7px),
            0 7px
        );

    transition:
        transform .18s ease,
        filter .18s ease,
        box-shadow .18s ease;

}


/* INNER GOLD FRAME */

.slide-overlay a:after{

    content:"";

    position:absolute;

    inset:3px;

    pointer-events:none;

    border:
        1px solid
        rgba(255,215,120,.25);

    clip-path:
        polygon(
            5px 0,
            calc(100% - 5px) 0,
            100% 5px,
            100% calc(100% - 5px),
            calc(100% - 5px) 100%,
            5px 100%,
            0 calc(100% - 5px),
            0 5px
        );

}


/* BUTTON LIGHT */

.slide-overlay a:before{

    content:"";

    position:absolute;

    top:0;

    left:-120%;

    width:60%;

    height:100%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.35),
            transparent
        );

    transform:
        skewX(-20deg);

    transition:
        left .5s ease;

}


/* BUTTON HOVER */

.slide-overlay a:hover{

    transform:
        translateY(-2px)
        scale(1.03);

    filter:
        brightness(1.15);

    box-shadow:

        inset 0 1px 0
        rgba(255,240,170,.55),

        0 0 15px
        rgba(255,190,60,.30),

        0 5px 15px
        rgba(0,0,0,.9);

}


.slide-overlay a:hover:before{

    left:130%;

}


/* =====================================================
   EVENT BADGE
===================================================== */

.slide-overlay:before{

    content:
        "EVENT • CNTECH STORE";

    display:inline-block;

    margin-bottom:7px;

    padding:
        4px 9px;

    color:#ffd15c;

    font-size:9px;

    font-weight:900;

    letter-spacing:1px;

    text-shadow:
        1px 1px 0 #000;

    background:
        rgba(0,0,0,.70);

    border-left:
        3px solid
        #ff2020;

    border-top:
        1px solid
        rgba(255,209,92,.18);

    border-bottom:
        1px solid
        rgba(255,209,92,.18);

}


/* =====================================================
   OLD MMO STAR DOT NAVIGATION
===================================================== */

.slider-dots{

    position:absolute;

    z-index:6;

    left:50%;

    bottom:10px;

    transform:
        translateX(-50%);

    display:flex;

    align-items:center;

    justify-content:center;

    gap:9px;

    padding:
        6px 11px;

    background:
        rgba(0,0,0,.42);

    border:
        1px solid
        rgba(255,209,92,.16);

    border-radius:8px;

    box-shadow:
        0 3px 10px
        rgba(0,0,0,.65);

}


/* =====================================================
   STAR
===================================================== */

.slider-dots span{

    position:relative;

    display:block;

    width:13px;

    height:13px;

    cursor:pointer;

    background:

        linear-gradient(
            135deg,
            #7d642c,
            #ffd15c 45%,
            #8c6822 70%,
            #3a2a0b
        );

    clip-path:
        polygon(
            50% 0%,
            61% 35%,
            98% 35%,
            69% 57%,
            79% 94%,
            50% 72%,
            21% 94%,
            31% 57%,
            2% 35%,
            39% 35%
        );

    filter:
        drop-shadow(
            0 2px 2px
            rgba(0,0,0,.9)
        );

    opacity:.48;

    transform:
        scale(.82);

    transition:

        transform .25s ease,

        opacity .25s ease,

        filter .25s ease;

}


/* STAR INNER LIGHT */

.slider-dots span:after{

    content:"";

    position:absolute;

    inset:3px;

    background:
        rgba(255,245,190,.55);

    clip-path:
        polygon(
            50% 0%,
            61% 35%,
            98% 35%,
            69% 57%,
            79% 94%,
            50% 72%,
            21% 94%,
            31% 57%,
            2% 35%,
            39% 35%
        );

    opacity:.35;

}


/* =====================================================
   ACTIVE STAR
===================================================== */

.slider-dots span.active{

    width:17px;

    height:17px;

    opacity:1;

    transform:
        scale(1);

    background:

        linear-gradient(
            135deg,
            #fff0a6,
            #ffd15c 35%,
            #ff9d00 65%,
            #7a4200
        );

    filter:

        drop-shadow(
            0 0 3px
            #ffd15c
        )

        drop-shadow(
            0 0 8px
            rgba(255,180,40,.65)
        );

}


/* ACTIVE STAR LIGHT */

.slider-dots span.active:after{

    opacity:.85;

    background:#fff8cf;

}


/* STAR HOVER */

.slider-dots span:hover{

    opacity:.9;

    transform:
        scale(1.12);

    filter:

        drop-shadow(
            0 0 5px
            rgba(255,209,92,.75)
        );

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .slide-overlay{

        left:4%;

        bottom:11%;

        max-width:70%;

    }


    .slide-overlay h2{

        font-size:
            clamp(
                16px,
                5vw,
                25px
            );

    }


    .slide-overlay p{

        display:none;

    }


    .slide-overlay a{

        min-height:34px;

        padding:
            0 14px;

        font-size:11px;

    }


    .slide-overlay:before{

        font-size:7px;

        padding:
            3px 7px;

    }


    .slider-dots{

        bottom:8px;

        gap:7px;

        padding:
            5px 9px;

    }


    .slider-dots span{

        width:11px;

        height:11px;

    }


    .slider-dots span.active{

        width:15px;

        height:15px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .slider-box{

        border-radius:15px;

    }

    .slider-dots{

        gap:6px;

    }

}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    .slide,
    .slider-dots span,
    .slide-overlay a{

        transition:none;

    }

    .slider-box:after{

        animation:none;

    }

}
</style>



<!-- =================================
     SERVICES
================================= -->


<section class="services">


<div class="section-title">

<h3>
<i class="fa-solid fa-bolt"></i>  ບໍລິການ
</h3>

<a href="#" onclick="openServices()">

ທັງໝົດ

</a>

</div>



<div class="service-grid">


<a href="page/game_topup.php">

<i class="fa-solid fa-gamepad"></i>

<span>
ເຕີມເກມ
</span>

</a>


<a href="page/mobile-topup.php">

<i class="fa-solid fa-mobile-screen"></i>

<span>
ເຕີມເງິນມືຖື
</span>

</a>


<a href="page/voucher.php">

<i class="fa-solid fa-gift"></i>

<span>
ບັດກຳນັນ
</span>

</a>


<a href="javascript:void(0);" onclick="showReelsPopup()">
    <i class="fa-solid fa-video"></i>

    <span>
        ຄວາມບັນເທິງ
    </span>
</a>

<!-- REELS POPUP -->
<div id="reelsPopup" class="reels-popup-overlay">

    <div class="reels-popup">

        <div class="reels-icon">
            <i class="fa-solid fa-video"></i>
        </div>

        <h2>CNTECH REELS</h2>

        <p>
            ລະບົບ Reels ຍັງບໍ່ພ້ອມໃຊ້ງານ
        </p>

        <span class="reels-status">
            COMING SOON
        </span>

        <button onclick="closeReelsPopup()">
            ຕົກລົງ
        </button>

    </div>

</div>

<style>
.reels-popup-overlay{
    display:none;
    position:fixed;
    inset:0;
    z-index:99999;
    padding:20px;
    align-items:center;
    justify-content:center;
    background:#000c;
    backdrop-filter:blur(10px);
}

.reels-popup{
    position:relative;
    width:100%;
    max-width:390px;
    padding:32px 24px;
    text-align:center;
    color:#fff;
    border:1px solid #8b5cff66;
    border-radius:24px;
    background:
        radial-gradient(circle at 50% 0,#8b5cff20,transparent 45%),
        linear-gradient(145deg,#17131f,#08080e);
    box-shadow:
        0 30px 90px #000d,
        0 0 35px #8b5cff33;
    animation:reelsIn .35s cubic-bezier(.2,.8,.2,1);
}

.reels-popup:before{
    content:"";
    position:absolute;
    inset:-1px;
    z-index:-1;
    border-radius:24px;
    background:linear-gradient(
        135deg,
        #ff2020,
        #8b5cff,
        #00c8ff,
        #ffd15c
    );
    filter:blur(8px);
    opacity:.35;
}

.reels-icon{
    width:76px;
    height:76px;
    margin:0 auto 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    color:#fff;
    font-size:31px;
    background:radial-gradient(circle,#ff202055,#19080d);
    border:1px solid #ff202099;
    box-shadow:
        0 0 20px #ff202055,
        0 0 50px #8b5cff22;
    animation:reelsFloat 2s ease-in-out infinite;
}

.reels-popup h2{
    margin:0 0 8px;
    font-size:23px;
    font-weight:900;
    background:linear-gradient(
        90deg,#fff,#ffd15c,#ff2020
    );
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.reels-popup p{
    margin:0 0 16px;
    color:#aaa;
    line-height:1.7;
    font-size:13px;
}

.reels-status{
    display:inline-flex;
    align-items:center;
    gap:6px;
    margin-bottom:20px;
    padding:7px 14px;
    border-radius:20px;
    color:#ffd15c;
    background:#ffd15c12;
    border:1px solid #ffd15c33;
    font-size:12px;
    font-weight:900;
}

.reels-status:before{
    content:"";
    width:7px;
    height:7px;
    border-radius:50%;
    background:#ffd15c;
    box-shadow:0 0 10px #ffd15c;
    animation:reelsPulse 1.3s infinite;
}

.reels-popup button{
    width:100%;
    padding:14px;
    border:0;
    border-radius:12px;
    color:#fff;
    font-size:15px;
    font-weight:900;
    cursor:pointer;
    background:linear-gradient(
        135deg,#ff2020,#a80038
    );
    box-shadow:0 0 25px #ff202055;
    transition:.25s;
}

.reels-popup button:hover{
    transform:translateY(-2px);
    box-shadow:0 0 35px #ff202099;
}

.reels-popup button:active{
    transform:scale(.97);
}

@keyframes reelsIn{
    from{
        opacity:0;
        transform:scale(.88) translateY(25px);
    }
    to{
        opacity:1;
        transform:none;
    }
}

@keyframes reelsFloat{
    50%{transform:translateY(-6px)}
}

@keyframes reelsPulse{
    50%{opacity:.4;transform:scale(.8)}
}

@media(max-width:400px){
    .reels-popup{
        padding:27px 20px;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded",()=>{

    const popup=document.getElementById("reelsPopup");

    if(!popup) return;

    window.showReelsPopup=()=>{
        popup.style.display="flex";
        document.body.style.overflow="hidden";
    };

    window.closeReelsPopup=()=>{
        popup.style.display="none";
        document.body.style.overflow="";
    };

    /* แตะพื้นที่ด้านนอก Popup */
    popup.addEventListener("click",e=>{
        if(e.target===popup) closeReelsPopup();
    });

    /* กด ESC บน PC */
    document.addEventListener("keydown",e=>{
        if(e.key==="Escape") closeReelsPopup();
    });

});
</script>

<a href="page/orders.php">

<i class="fa-solid fa-box"></i>

<span>
ປະຫວັດທຸລະກຳ
</span>

</a>


<a href="page/blogs-method.php">

<i class="fa-solid fa-newspaper"></i>

<span>
ບົດຄວາມ 
</span>

</a>


<a href="page/contact.php">

<i class="fa-solid fa-headset"></i>

<span>
ທີມງານຊ່ວຍເຫືຼອ
</span>

</a>


<a href="page/fortune.php" >
    <i class="fa-regular fa-eye"></i>
    <span>ດູດວງໃໝ່</span>
</a>

<a href="javascript:void(0);"
   class="lottery-button"
   onclick="showLotteryPopup()">

    <i class="fa-solid fa-ticket"></i>
    <span>ຫວຍລາວ</span>

</a>

<!-- POPUP -->
<div id="lotteryPopup" class="lottery-popup">

    <div class="lottery-popup-box">

        <button
            type="button"
            class="lottery-close"
            onclick="closeLotteryPopup()"
            aria-label="Close"
        >
            ×
        </button>

        <div class="lottery-popup-icon">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>

        <b>ກຳລັງພັດທະນາ</b>

        <p>
            ລະບົບຫວຍພັດທະນາລາວ
            ກຳລັງພັດທະນາ
        </p>

        <button
            type="button"
            class="lottery-ok"
            onclick="closeLotteryPopup()"
        >
            ຕົກລົງ
        </button>

    </div>

</div>

<style>
.lottery-button{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:9px;
    width:100%;
    padding:13px 16px;
    color:#fff;
    background:linear-gradient(135deg,#ff2020,#8b1cff);
    border:1px solid #ffffff18;
    border-radius:13px;
    text-decoration:none;
    font-weight:900;
    box-shadow:0 0 20px #ff202044;
    transition:.25s;
}
.lottery-button:hover{
    transform:translateY(-2px) scale(1.01);
    box-shadow:0 0 30px #8b5cff77;
}

.lottery-popup{
    position:fixed;
    inset:0;
    z-index:99999;
    display:none;
    align-items:center;
    justify-content:center;
    padding:20px;
    background:#000c;
    backdrop-filter:blur(10px);
}
.lottery-popup.show{display:flex}

.lottery-popup-box{
    position:relative;
    width:100%;
    max-width:400px;
    padding:32px 24px 24px;
    text-align:center;
    color:#fff;
    background:
        radial-gradient(circle at 50% 0,#8b5cff25,transparent 45%),
        linear-gradient(145deg,#181320,#08080e);
    border:1px solid #8b5cff66;
    border-radius:24px;
    box-shadow:
        0 30px 90px #000e,
        0 0 40px #8b5cff33;
    animation:lotteryIn .35s ease;
}

.lottery-popup-box:before{
    content:"";
    position:absolute;
    inset:-1px;
    z-index:-1;
    border-radius:24px;
    background:linear-gradient(
        135deg,#ff2020,#8b5cff,#00c8ff,#ffd15c
    );
    filter:blur(9px);
    opacity:.35;
}

@keyframes lotteryIn{
    from{opacity:0;transform:scale(.86) translateY(25px)}
    to{opacity:1;transform:none}
}

.lottery-close{
    position:absolute;
    top:11px;
    right:12px;
    width:36px;
    height:36px;
    border:0;
    border-radius:10px;
    background:#ffffff0d;
    color:#aaa;
    font-size:23px;
    cursor:pointer;
}
.lottery-close:hover{
    color:#fff;
    background:#ff202044;
    transform:rotate(90deg);
}

.lottery-popup-icon{
    width:76px;
    height:76px;
    margin:0 auto 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    color:#ffd15c;
    font-size:30px;
    background:radial-gradient(circle,#ff202044,#19080d);
    border:1px solid #ffd15c77;
    box-shadow:
        0 0 20px #ffd15c44,
        0 0 50px #ff202022;
    animation:lotteryFloat 2s ease-in-out infinite;
}

@keyframes lotteryFloat{
    50%{transform:translateY(-6px) rotate(3deg)}
}

.lottery-popup-box b{
    display:block;
    font-size:23px;
    font-weight:900;
    background:linear-gradient(90deg,#fff,#ffd15c,#ff2020);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.lottery-popup-box p{
    margin:10px 0 20px;
    color:#aaa;
    font-size:13px;
    line-height:1.8;
}

.lottery-ok{
    width:100%;
    padding:14px;
    border:0;
    border-radius:12px;
    color:#fff;
    font-weight:900;
    cursor:pointer;
    background:linear-gradient(135deg,#ff2020,#a80038);
    box-shadow:0 0 25px #ff202055;
    transition:.25s;
}
.lottery-ok:hover{
    transform:translateY(-2px);
    box-shadow:0 0 35px #ff202099;
}
</style>

<script>
function showLotteryPopup(){
    const p=document.getElementById('lotteryPopup');
    if(p)p.classList.add('show');
    document.body.style.overflow='hidden';
}

function closeLotteryPopup(){
    const p=document.getElementById('lotteryPopup');
    if(p)p.classList.remove('show');
    document.body.style.overflow='';
}

document.addEventListener('click',e=>{
    const p=document.getElementById('lotteryPopup');
    if(p&&e.target===p)closeLotteryPopup();
});

document.addEventListener('keydown',e=>{
    if(e.key==='Escape')closeLotteryPopup();
});
</script>




<div id="fortunePopup" class="fortune-overlay">
    <div class="fortune-popup">
        <button class="fortune-close" onclick="closeFortunePopup()">×</button>

        <div class="fortune-icon">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>

        <h2>ດູດວງໃໝ່</h2>

        <div class="fortune-status">
            ກຳລັງພັດທະນາ
        </div>

        <p>
            ລະບົບດູດວງກຳລັງຢູ່ໃນຂັ້ນຕອນພັດທະນາ<br>
            ຈະເປີດໃຫ້ໃຊ້ງານໃນໄວໆນີ້
        </p>

        <button class="fortune-btn" onclick="closeFortunePopup()">
            ຕົກລົງ
        </button>
    </div>
</div>

<a onclick="openServices()">

<i class="fa-solid fa-ellipsis"></i>

<span>
  ເພີ່ມເຕີມ
</span>

</a>


</div>

</section>



<style>

/* =====================================================
   CNTECH SERVICE
   BLACK GLASS / MOBA OLD VISUAL
===================================================== */

.section-title{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:22px 0 13px;
}

.section-title h3{
    font-size:18px;
    font-weight:900;
}

.section-title a{
    color:#ff3030;
    font-size:13px;
    font-weight:800;
}


/* =====================================================
   SERVICE GRID
===================================================== */

.service-grid{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:10px;
}


/* =====================================================
   SERVICE CARD
===================================================== */

.service-grid a{

    position:relative;

    overflow:hidden;

    height:88px;

    display:flex;

    flex-direction:column;

    align-items:center;
    justify-content:center;

    gap:8px;

    color:#fff;

    text-decoration:none;

    /* BLACK TRANSPARENT */

    background:

        linear-gradient(
            145deg,
            rgba(0,0,0,.72),
            rgba(10,10,12,.48)
        );

    border:
        1px solid
        rgba(255,255,255,.09);

    border-radius:10px;

    box-shadow:

        inset 0 1px 0
        rgba(255,255,255,.06),

        inset 0 -8px 15px
        rgba(0,0,0,.35),

        0 8px 25px
        rgba(0,0,0,.65);

    backdrop-filter:
        blur(4px);

    -webkit-backdrop-filter:
        blur(4px);

    transition:
        transform .25s ease,
        border-color .25s ease,
        box-shadow .25s ease,
        filter .25s ease;

}


/* =====================================================
   DARK CORNER LIGHT
===================================================== */

.service-grid a::before{

    content:"";

    position:absolute;

    width:90px;
    height:90px;

    top:-45px;
    right:-40px;

    background:

        radial-gradient(
            circle,
            rgba(255,32,32,.16),
            transparent 70%
        );

    pointer-events:none;

    transition:.35s;

}


/* =====================================================
   OLD MOBA GOLD EDGE
===================================================== */

.service-grid a::after{

    content:"";

    position:absolute;

    inset:2px;

    pointer-events:none;

    border:

        1px solid
        rgba(255,190,70,.10);

    border-radius:8px;

    background:

        linear-gradient(
            135deg,
            rgba(255,210,100,.05),
            transparent 25%,
            transparent 75%,
            rgba(255,40,40,.04)
        );

}


/* =====================================================
   HOVER
===================================================== */

.service-grid a:hover{

    transform:
        translateY(-4px)
        scale(1.02);

    border-color:
        rgba(255,190,70,.42);

    filter:
        brightness(1.08);

    box-shadow:

        inset 0 1px 0
        rgba(255,220,140,.16),

        0 0 15px
        rgba(255,32,32,.16),

        0 12px 35px
        rgba(0,0,0,.85);

}


/* =====================================================
   ICON
===================================================== */

.service-grid i{

    position:relative;

    z-index:5;

    font-size:25px;

    color:#ff3030;

    text-shadow:

        0 0 8px
        rgba(255,32,32,.45),

        1px 1px 0 #000;

    transition:
        color .25s ease,
        transform .25s ease,
        text-shadow .25s ease;

}


.service-grid a:hover i{

    color:#ffd35a;

    transform:
        scale(1.15)
        translateY(-2px);

    text-shadow:

        0 0 7px
        rgba(255,180,50,.7),

        1px 1px 0 #000;

}


/* =====================================================
   TEXT
===================================================== */

.service-grid span{

    position:relative;

    z-index:5;

    color:#ddd;

    font-size:12px;

    font-weight:800;

    text-shadow:

        1px 1px 0 #000;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .service-grid{

        grid-template-columns:
            repeat(4,1fr);

        gap:7px;

    }

    .service-grid a{

        height:78px;

        border-radius:9px;

    }

    .service-grid i{

        font-size:21px;

    }

    .service-grid span{

        font-size:10px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .service-grid{

        grid-template-columns:
            repeat(2,1fr);

    }

    .service-grid a{

        height:82px;

    }

    .service-grid span{

        font-size:12px;

    }

}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    .service-grid a,
    .service-grid i{

        transition:none;

    }

}

</style>




<!-- =================================
     MORE SERVICE POPUP
================================= -->


<div id="serviceModal"
class="service-modal">


<div class="modal-box">


<h3>
ບໍລິການທັງໝົດ
</h3>

    <button onclick="closeServices()">

ປິດ

    </button>

<div class="all-service-grid">


<a href="#">
<i class="fa-brands fa-windows"></i> ຄອມພີວເຕີ
</a>


<a href="#">
<i class="fa-solid fa-laptop"></i> ໂນດບຸກ
</a>


<a href="#">
<i class="fa-solid fa-laptop"></i> ແທບເລັດ
</a>


<a href="#">
<i class="fa-solid fa-gamepad"></i> ອຸປະກອນໄອທີ
</a>


<a href="#">
<i class="fa-solid fa-money-bill"></i> ກະເປົາເງິນ
</a>


<a href="#">
<i class="fa-solid fa-layer-group"></i> ຕິດຕາມທຸລະກຳ
</a>


<a href="#">
<i class="fa-solid fa-book"></i> ບົດຄວາມ
</a>


<a href="#">
<i class="fa-solid fa-comment"></i> ຕິດຕໍທີມງານ
</a>


</div>





</div>


</div>



<style>

/* =====================================================
   CNTECH STORE
   SERVICE MODAL
   MOBA OLD VISUAL 2017–2018
   DARK / RED / GOLD
===================================================== */


/* =====================================================
   MODAL OVERLAY
===================================================== */

.service-modal{

    display:none;

    position:fixed;

    inset:0;

    z-index:99999;

    align-items:flex-end;

    background:
        rgba(0,0,0,.82);

    backdrop-filter:
        blur(7px);

    -webkit-backdrop-filter:
        blur(7px);

}


.service-modal.show{

    display:flex;

}


/* =====================================================
   MODAL BOX
===================================================== */

.modal-box{

    position:relative;

    width:100%;

    padding:
        20px
        16px
        25px;

    overflow:hidden;

    background:

        radial-gradient(
            circle at 20% 0,
            rgba(255,32,32,.10),
            transparent 32%
        ),

        radial-gradient(
            circle at 85% 0,
            rgba(255,190,60,.08),
            transparent 30%
        ),

        linear-gradient(
            180deg,
            #18140d,
            #0b0a08 55%,
            #050505
        );

    border-top:
        1px solid
        rgba(210,160,60,.55);

    border-radius:
        12px 12px 0 0;

    box-shadow:

        0 -15px 60px
        rgba(0,0,0,.9),

        0 0 35px
        rgba(255,32,32,.10);

    animation:
        serviceUp
        .3s
        ease;

}


/* =====================================================
   OLD MOBA TOP FRAME
===================================================== */

.modal-box:after{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    border:

        1px solid
        rgba(255,205,100,.18);

    clip-path:

        polygon(
            12px 0,
            calc(100% - 12px) 0,
            100% 12px,
            100% calc(100% - 12px),
            calc(100% - 12px) 100%,
            12px 100%,
            0 calc(100% - 12px),
            0 12px
        );

}


/* =====================================================
   DRAG HANDLE
===================================================== */

.modal-box:before{

    content:"";

    display:block;

    position:relative;

    z-index:5;

    width:50px;

    height:4px;

    margin:
        0 auto
        18px;

    border-radius:3px;

    background:

        linear-gradient(
            90deg,
            #7a0c0c,
            #d3a23f,
            #7a0c0c
        );

    box-shadow:

        0 0 8px
        rgba(255,190,60,.3);

}


/* =====================================================
   SERVICE GRID
===================================================== */

.all-service-grid{

    position:relative;

    z-index:5;

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:10px;

    margin:
        18px 0;

    max-height:55vh;

    overflow-y:auto;

    scrollbar-width:thin;

    scrollbar-color:
        #8f6b2d
        #090909;

}


/* =====================================================
   SERVICE ITEM
===================================================== */

.all-service-grid a{

    position:relative;

    min-height:78px;

    padding:
        10px
        5px;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    gap:7px;

    overflow:hidden;

    color:#fff;

    text-align:center;

    text-decoration:none;

    background:

        linear-gradient(
            145deg,
            #252015,
            #0c0b08
        );

    border:

        1px solid
        rgba(190,145,55,.38);

    border-radius:7px;

    box-shadow:

        0 5px 15px
        rgba(0,0,0,.7),

        inset 0 1px 0
        rgba(255,225,140,.07);

    clip-path:

        polygon(
            7px 0,
            calc(100% - 7px) 0,
            100% 7px,
            100% calc(100% - 7px),
            calc(100% - 7px) 100%,
            7px 100%,
            0 calc(100% - 7px),
            0 7px
        );

    transition:

        transform .2s ease,
        filter .2s ease,
        border-color .2s ease,
        box-shadow .2s ease;

}


/* =====================================================
   SERVICE LIGHT
===================================================== */

.all-service-grid a:before{

    content:"";

    position:absolute;

    top:-100%;

    left:-100%;

    width:55%;

    height:300%;

    pointer-events:none;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,220,130,.12),
            transparent
        );

    transform:
        rotate(25deg);

    transition:
        left .45s ease;

}


/* =====================================================
   SERVICE ICON
===================================================== */

.all-service-grid a i{

    position:relative;

    z-index:3;

    font-size:22px;

    color:#ffd35a;

    text-shadow:

        0 0 5px
        #ff8c00,

        1px 1px 0 #000;

    transition:
        transform .2s ease,
        filter .2s ease;

}


/* =====================================================
   SERVICE TEXT
===================================================== */

.all-service-grid a span{

    position:relative;

    z-index:3;

    color:#eee;

    font-size:10px;

    font-weight:900;

    line-height:1.2;

    text-shadow:

        1px 1px 0 #000;

}


/* =====================================================
   HOVER
===================================================== */

.all-service-grid a:hover{

    transform:
        translateY(-4px)
        scale(1.02);

    filter:
        brightness(1.12);

    border-color:

        rgba(255,205,90,.75);

    box-shadow:

        0 0 10px
        rgba(255,32,32,.25),

        0 0 18px
        rgba(255,190,50,.15),

        0 8px 20px
        rgba(0,0,0,.9);

}


.all-service-grid a:hover:before{

    left:150%;

}


.all-service-grid a:hover i{

    transform:
        scale(1.1);

    filter:
        brightness(1.15);

}


/* =====================================================
   ACTIVE / CLICK
===================================================== */

.all-service-grid a:active{

    transform:
        translateY(2px)
        scale(.94);

    filter:
        brightness(.9);

}


/* =====================================================
   CLOSE BUTTON
===================================================== */

.modal-box button{

    position:relative;

    z-index:10;

    width:100%;

    min-height:42px;

    padding:10px 14px;

    border:

        1px solid
        #c49a3b;

    border-radius:6px;

    color:#fff;

    background:

        linear-gradient(
            180deg,
            #a71919,
            #4d0508
        );

    font-size:13px;

    font-weight:900;

    text-shadow:

        1px 1px 0 #000;

    box-shadow:

        inset 0 1px 0
        rgba(255,235,160,.3),

        inset 0 -6px 12px
        rgba(0,0,0,.6),

        0 4px 12px
        rgba(0,0,0,.75);

    cursor:pointer;

    transition:

        transform .15s ease,
        filter .15s ease,
        box-shadow .15s ease;

}


.modal-box button:hover{

    transform:
        translateY(-2px);

    filter:
        brightness(1.15);

    border-color:
        #edc65d;

    box-shadow:

        0 0 15px
        rgba(255,32,32,.4),

        0 0 8px
        rgba(255,190,50,.2),

        inset 0 1px 0
        rgba(255,240,170,.4);

}


.modal-box button:active{

    transform:
        translateY(2px)
        scale(.97);

    filter:
        brightness(.9);

}


/* =====================================================
   MODAL ANIMATION
===================================================== */

@keyframes serviceUp{

    from{

        opacity:0;

        transform:
            translateY(100%);

    }

    to{

        opacity:1;

        transform:
            translateY(0);

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .modal-box{

        padding:
            18px
            12px
            20px;

    }


    .all-service-grid{

        grid-template-columns:
            repeat(4,1fr);

        gap:7px;

        margin:
            15px 0;

    }


    .all-service-grid a{

        min-height:70px;

        padding:
            8px 3px;

    }


    .all-service-grid a i{

        font-size:19px;

    }


    .all-service-grid a span{

        font-size:9px;

    }


}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .all-service-grid{

        grid-template-columns:
            repeat(3,1fr);

        gap:7px;

    }


    .all-service-grid a{

        min-height:68px;

    }

}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    .modal-box,
    .all-service-grid a,
    .all-service-grid a i,
    .modal-box button{

        animation:none;

        transition:none;

    }

    .all-service-grid a:before{

        display:none;

    }

}

</style>



<script>

/* =========================================
   SERVICE MODAL
========================================= */

const serviceModal =
    document.getElementById("serviceModal");

let closingService = false;


function openServices(){

    if(!serviceModal) return;

    closingService = false;

    serviceModal.style.display = "flex";

    requestAnimationFrame(() => {

        serviceModal.classList.add("show");

    });

    document.body.style.overflow = "hidden";

}


function closeServices(){

    if(!serviceModal || closingService) return;

    closingService = true;

    serviceModal.classList.remove("show");

    setTimeout(() => {

        serviceModal.style.display = "none";

        document.body.style.overflow = "";

        closingService = false;

    }, 320);

}


/* คลิกพื้นหลังปิด */

if(serviceModal){

    serviceModal.addEventListener("click",function(e){

        if(e.target === serviceModal){

            closeServices();

        }

    });

}


/* ESC */

document.addEventListener("keydown",function(e){

    if(e.key === "Escape"){

        closeServices();

    }

});


/* =========================================
   SLIDER
========================================= */

let slideIndex = 0;
let sliderTimer;


function showSlide(index){

    const slides =
        document.querySelectorAll(".slide");

    const dots =
        document.querySelectorAll(".slider-dots span");


    if(!slides.length) return;


    if(index >= slides.length){

        slideIndex = 0;

    }

    if(index < 0){

        slideIndex = slides.length - 1;

    }


    slides.forEach((slide,i)=>{

        slide.classList.toggle(
            "active",
            i === slideIndex
        );

    });


    dots.forEach((dot,i)=>{

        dot.classList.toggle(
            "active",
            i === slideIndex
        );

    });

}


function autoSlider(){

    const slides =
        document.querySelectorAll(".slide");

    if(!slides.length) return;

    slideIndex++;

    if(slideIndex >= slides.length){

        slideIndex = 0;

    }

    showSlide(slideIndex);

}


function startSlider(){

    clearInterval(sliderTimer);

    sliderTimer =
        setInterval(autoSlider,4000);

}


function stopSlider(){

    clearInterval(sliderTimer);

}


/* =========================================
   SWIPE MOBILE
========================================= */

const slider =
    document.querySelector(".slider-box");

let touchStartX = 0;
let touchEndX = 0;


if(slider){

    slider.addEventListener(
        "touchstart",
        function(e){

            touchStartX =
                e.changedTouches[0].screenX;

            stopSlider();

        },
        {passive:true}
    );


    slider.addEventListener(
        "touchend",
        function(e){

            touchEndX =
                e.changedTouches[0].screenX;

            const distance =
                touchEndX - touchStartX;


            if(Math.abs(distance) > 50){

                const slides =
                    document.querySelectorAll(".slide");

                if(distance < 0){

                    slideIndex++;

                }else{

                    slideIndex--;

                }


                if(slideIndex >= slides.length){

                    slideIndex = 0;

                }

                if(slideIndex < 0){

                    slideIndex =
                        slides.length - 1;

                }


                showSlide(slideIndex);

            }

            startSlider();

        },
        {passive:true}
    );

}


/* =========================================
   INIT
========================================= */

document.addEventListener(
    "DOMContentLoaded",
    function(){

        showSlide(0);

        startSlider();

    }
);

</script>
    
    <script>
function showFortunePopup(e){
    e.preventDefault();
    document.getElementById('fortunePopup').style.display='flex';
}

function closeFortunePopup(){
    document.getElementById('fortunePopup').style.display='none';
}

document.getElementById('fortunePopup').addEventListener('click',function(e){
    if(e.target === this){
        closeFortunePopup();
    }
});
</script>

    <!-- =================================
     POPULAR GAMES
================================= -->

<section class="home-section">


<div class="section-title">

<h3>
<i class="fa-solid fa-gamepad"></i> ເກມຍອດນິຍົມ
</h3>


<a href="page/game_topup.php">

ທັງໝົດ

</a>


</div>



<div class="game-grid">

<?php

$sql = "
    SELECT *
    FROM games
    WHERE status='active'
    ORDER BY play_count DESC
    LIMIT 12
";

$result = $conn->query($sql);

if($result && $result->num_rows > 0){

    while($game = $result->fetch_assoc()){

        $image = !empty($game['icon'])
            ? "/admin/uploads/" . $game['icon']
            : "/assets/no-image.png";

?>

<a
    class="game-card"
    href="game/namegame.php?id=<?= (int)$game['id'] ?>"
>

    <!-- GAME IMAGE -->

    <div class="game-image">

        <img
            src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
            alt="<?= htmlspecialchars($game['name'], ENT_QUOTES, 'UTF-8') ?>"
            loading="lazy"
        >

        <!-- OLD MOBA FRAME -->

        <div class="game-card-frame"></div>

    </div>


    <!-- GAME INFO -->

    <div class="game-info">

        <h4>
            <?= htmlspecialchars(
                $game['name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </h4>


        <p class="game-players">

            <i class="fa-solid fa-fire"></i>

            <?= number_format(
                (int)$game['play_count']
            ) ?>

            Players

        </p>


        <!-- MOBA BUTTON -->

        <div class="moba-btn">

            <i class="fa-solid fa-gamepad"></i>

            <p>
                ເຕີມເກມ
            </p>

        </div>

    </div>

</a>

<?php

    }

}else{

?>

<div class="empty-box">

    ບໍ່ມີເກມ

</div>

<?php

}

?>

</div>


</section>





<!-- =================================
     VOUCHER CATEGORY
================================= -->


<section class="home-section">


<div class="section-title">


<h3>

<i class="fa-solid fa-gift"></i> Voucher

</h3>


<a href="game/voucher.php">

ທັງໝົດ

</a>


</div>




<div class="voucher-grid">



<?php


$sql = "

SELECT *

FROM voucher_categories

WHERE status='active'

ORDER BY id DESC

LIMIT 8

";



$result=$conn->query($sql);



if($result && $result->num_rows>0){


while($voucher=$result->fetch_assoc()){



$image = !empty($voucher['image'])

?

"/admin/uploads/".$voucher['image']

:

"/assets/no-image.png";


?>


<a href="game/voucher_pd.php?id=<?=
(int)$voucher['id']

?>"

class="voucher-card">



<img src="<?=htmlspecialchars($image)?>">



<h4>

<?=htmlspecialchars(
$voucher['name']
)?>

</h4>



<p>

Digital Voucher

</p>



</a>


<?php


}


}else{


?>


<div class="empty-box">

ບໍ່ມີ Voucher

</div>


<?php


}


?>



</div>


</section>






<style>

/* =====================================================
   CNTECH STORE
   MOBA OLD VISUAL 2017–2018
   GAME + VOUCHER
===================================================== */


/* =====================================================
   HOME
===================================================== */

.home-section{
    margin-top:25px;
}


/* =====================================================
   GAME GRID
===================================================== */

.game-grid{

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:12px;
}


/* =====================================================
   GAME CARD
===================================================== */

.game-card{

    position:relative;

    display:block;

    overflow:hidden;

    padding-bottom:10px;

    background:

        linear-gradient(
            145deg,
            rgba(99,69,189,.267),
            rgba(8,8,15,0)
        );

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius:8px;

    box-shadow:
        0 12px 35px rgba(0,0,0,.55);

    transition:
        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;

}


/* =====================================================
   GAME CARD OLD VISUAL LIGHT
===================================================== */

.game-card:before{

    content:"";

    position:absolute;

    width:120px;
    height:120px;

    top:-60px;
    right:-60px;

    background:

        radial-gradient(
            circle,
            rgba(139,92,255,.35),
            transparent 70%
        );

    pointer-events:none;

}


/* =====================================================
   GAME CARD GOLD EDGE
===================================================== */

.game-card:after{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    border:
        1px solid
        rgba(255,209,92,.12);

    box-shadow:
        inset 0 0 15px
        rgba(255,160,30,.04);

}


/* =====================================================
   GAME HOVER
===================================================== */

.game-card:hover{

    transform:
        translateY(-5px)
        scale(1.015);

    border-color:
        rgba(255,209,92,.55);

    box-shadow:

        0 0 12px
        rgba(255,32,32,.25),

        0 0 25px
        rgba(255,209,92,.12),

        0 18px 45px
        rgba(0,0,0,.85);

}


/* =====================================================
   GAME IMAGE
===================================================== */

.game-image{

    position:relative;

    width:100%;

    aspect-ratio:1/1;

    overflow:hidden;

}


/* IMAGE DARK GRADIENT */

.game-image:after{

    content:"";

    position:absolute;

    inset:0;

    background:

        linear-gradient(
            180deg,
            transparent 55%,
            rgba(0,0,0,.72)
        );

    pointer-events:none;

}


/* IMAGE */

.game-image img{

    width:100%;
    height:100%;

    display:block;

    object-fit:cover;

    transition:
        transform .45s ease,
        filter .45s ease;

}


/* IMAGE HOVER */

.game-card:hover
.game-image img{

    transform:
        scale(1.08);

    filter:
        brightness(1.08)
        saturate(1.15);

}


/* =====================================================
   GAME INFO
===================================================== */

.game-info{

    position:relative;

    z-index:5;

    padding:10px;

    background:

        linear-gradient(
            180deg,
            rgba(20,20,20,.96),
            rgba(5,5,5,.99)
        );

}


/* =====================================================
   GAME NAME
===================================================== */

.game-info h4{

    margin:
        0 0 5px;

    color:#fff;

    font-size:14px;

    font-weight:900;

    line-height:1.3;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

    text-shadow:
        1px 1px 2px #000;

}


/* =====================================================
   PLAYERS
===================================================== */

.game-info p{

    margin:0;

    color:#999;

    font-size:11px;

}


/* =====================================================
   BUTTON WRAPPER
===================================================== */

.game-info > span{

    display:block;

    margin-top:8px;

}


/* =====================================================
   MOBA OLD BUTTON
===================================================== */

.moba-btn{

    position:relative;

    width:100%;

    min-height:42px;

    padding:
        0 12px;

    display:flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    overflow:hidden;

    color:#fff;

    font-size:12px;

    font-weight:900;

    letter-spacing:.3px;

    text-shadow:
        1px 1px 0 #000,
        0 0 5px #000;

    background:

        linear-gradient(
            180deg,
            #60491f 0%,
            #39270e 35%,
            #171006 70%,
            #080603 100%
        );

    border:0;

    clip-path:

        polygon(
            8px 0,
            calc(100% - 8px) 0,
            100% 8px,
            100% calc(100% - 8px),
            calc(100% - 8px) 100%,
            8px 100%,
            0 calc(100% - 8px),
            0 8px
        );

    box-shadow:

        inset 0 1px 0
        rgba(255,235,170,.35),

        inset 0 -7px 12px
        rgba(0,0,0,.7),

        0 3px 8px
        rgba(0,0,0,.8);

    cursor:pointer;

    transition:
        transform .15s ease,
        filter .15s ease,
        box-shadow .15s ease;

}


/* =====================================================
   GOLD FRAME
===================================================== */

.moba-btn:after{

    content:"";

    position:absolute;

    inset:2px;

    pointer-events:none;

    border:
        1px solid
        rgba(255,215,120,.4);

    clip-path:

        polygon(
            6px 0,
            calc(100% - 6px) 0,
            100% 6px,
            100% calc(100% - 6px),
            calc(100% - 6px) 100%,
            6px 100%,
            0 calc(100% - 6px),
            0 6px
        );

}


/* =====================================================
   LIGHT SWEEP
===================================================== */

.moba-btn:before{

    content:"";

    position:absolute;

    top:0;

    left:-120%;

    width:60%;

    height:100%;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.28),
            transparent
        );

    transform:
        skewX(-20deg);

    transition:
        left .45s ease;

    pointer-events:none;

}


/* =====================================================
   BUTTON ICON
===================================================== */

.moba-btn i{

    position:relative;

    z-index:5;

    color:#ffd35a;

    font-size:14px;

    text-shadow:

        0 0 5px #ff8c00,

        1px 1px 0 #000;

}


/* =====================================================
   BUTTON TEXT
===================================================== */

.moba-btn span{

    position:relative;

    z-index:5;

    margin:0;

    padding:0;

    color:#fff;

    background:none;

    border:0;

    box-shadow:none;

}


/* =====================================================
   BUTTON HOVER
===================================================== */

.game-card:hover
.moba-btn{

    transform:
        translateY(-1px);

    filter:
        brightness(1.18);

    box-shadow:

        inset 0 1px 0
        rgba(255,240,170,.5),

        inset 0 -7px 12px
        rgba(0,0,0,.55),

        0 0 10px
        rgba(255,170,40,.45),

        0 4px 12px
        rgba(0,0,0,.9);

}


.game-card:hover
.moba-btn:before{

    left:130%;

}


/* =====================================================
   BUTTON CLICK
===================================================== */

.moba-btn:active{

    transform:
        translateY(2px)
        scale(.97);

    filter:
        brightness(.9);

}


/* =====================================================
   BUTTON VARIANTS
===================================================== */

.moba-btn.red{

    background:

        linear-gradient(
            180deg,
            #7b2520,
            #42100e,
            #100403
        );

}


.moba-btn.gold{

    background:

        linear-gradient(
            180deg,
            #70561d,
            #3d2a08,
            #100b02
        );

}


.moba-btn.blue{

    background:

        linear-gradient(
            180deg,
            #28546b,
            #102d3b,
            #050b0f
        );

}


.moba-btn.dark{

    background:

        linear-gradient(
            180deg,
            #454545,
            #202020,
            #080808
        );

}


/* =====================================================
   VOUCHER GRID
===================================================== */

.voucher-grid{

    display:grid;

    grid-template-columns:
        repeat(6,1fr);

    gap:12px;

}


/* =====================================================
   VOUCHER CARD
   SAME VISUAL AS GAME CARD
===================================================== */

.voucher-card{

    position:relative;

    display:block;

    overflow:hidden;

    min-height:125px;

    padding:8px;

    text-align:center;

    background:

        linear-gradient(
            145deg,
            rgba(99,69,189,.20),
            rgba(8,8,15,.95)
        );

    border:
        1px solid
        rgba(255,255,255,.08);

    border-radius:8px;

    box-shadow:

        0 12px 35px
        rgba(0,0,0,.55);

    transition:

        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease,
        filter .25s ease;

}


/* =====================================================
   VOUCHER GOLD CORNER LIGHT
===================================================== */

.voucher-card:before{

    content:"";

    position:absolute;

    width:100px;

    height:100px;

    top:-50px;

    right:-50px;

    background:

        radial-gradient(
            circle,
            rgba(255,209,92,.22),
            transparent 70%
        );

    pointer-events:none;

}


/* =====================================================
   VOUCHER FRAME
===================================================== */

.voucher-card:after{

    content:"";

    position:absolute;

    inset:0;

    z-index:5;

    pointer-events:none;

    background:

        url("/assets/ui/moba/voucher-frame.png")
        center / 100% 100%
        no-repeat;

    opacity:.88;

    filter:

        brightness(1.05)
        drop-shadow(
            0 0 3px
            rgba(255,190,70,.25)
        );

}


/* =====================================================
   VOUCHER HOVER
===================================================== */

.voucher-card:hover{

    transform:
        translateY(-5px)
        scale(1.015);

    border-color:
        rgba(255,209,92,.55);

    filter:
        brightness(1.06);

    box-shadow:

        0 0 12px
        rgba(255,32,32,.25),

        0 0 25px
        rgba(255,209,92,.12),

        0 18px 40px
        rgba(0,0,0,.85);

}


/* =====================================================
   VOUCHER IMAGE
   50 x 50
===================================================== */

.voucher-card img{

    position:relative;

    z-index:4;

    display:block;

    width:50px;

    height:50px;

    margin:
        2px auto 0;

    object-fit:cover;

    border-radius:6px;

    border:
        1px solid
        rgba(255,209,92,.38);

    box-shadow:

        0 3px 8px
        rgba(0,0,0,.75),

        0 0 8px
        rgba(255,180,50,.10);

    filter:

        brightness(.96)
        contrast(1.08)
        saturate(1.08);

    transition:

        transform .3s ease,
        filter .3s ease,
        box-shadow .3s ease;

}


/* IMAGE HOVER */

.voucher-card:hover img{

    transform:
        scale(1.08);

    filter:

        brightness(1.08)
        contrast(1.1)
        saturate(1.15);

    box-shadow:

        0 0 10px
        rgba(255,190,60,.25),

        0 4px 10px
        rgba(0,0,0,.8);

}


/* =====================================================
   VOUCHER INFO
===================================================== */

.voucher-info{

    position:relative;

    z-index:7;

    padding-top:7px;

    text-align:center;

}


/* =====================================================
   VOUCHER NAME
===================================================== */

.voucher-card h4{

    margin:0;

    color:#eee;

    font-size:12px;

    font-weight:900;

    line-height:1.3;

    text-shadow:

        1px 1px 0 #000,

        0 0 5px #000;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}


/* =====================================================
   VOUCHER DESCRIPTION
===================================================== */

.voucher-card p{

    margin-top:3px;

    color:#888;

    font-size:10px;

    line-height:1.3;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}


/* =====================================================
   VOUCHER PRICE
===================================================== */

.voucher-card .price{

    display:inline-block;

    margin-top:5px;

    color:#ffd35a;

    font-size:12px;

    font-weight:900;

    text-shadow:

        0 0 5px
        rgba(255,150,0,.55),

        1px 1px 0 #000;

}


/* =====================================================
   VOUCHER ACTION
===================================================== */

.voucher-card .voucher-action{

    position:relative;

    z-index:8;

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:5px;

    margin-top:7px;

    min-height:27px;

    padding:4px 10px;

    color:#fff;

    font-size:10px;

    font-weight:900;

    text-shadow:
        1px 1px 0 #000;

    background:

        linear-gradient(
            180deg,
            #6b2520,
            #3a0d0b,
            #100303
        );

    border:
        1px solid
        rgba(255,190,70,.42);

    clip-path:

        polygon(
            5px 0,
            calc(100% - 5px) 0,
            100% 5px,
            100% calc(100% - 5px),
            calc(100% - 5px) 100%,
            5px 100%,
            0 calc(100% - 5px),
            0 5px
        );

    box-shadow:

        0 3px 8px
        rgba(0,0,0,.65),

        inset 0 1px 0
        rgba(255,255,255,.12);

    transition:.2s;

}


/* ACTION HOVER */

.voucher-card:hover
.voucher-action{

    filter:
        brightness(1.18);

    border-color:
        rgba(255,209,92,.7);

    box-shadow:

        0 0 10px
        rgba(255,32,32,.35),

        0 0 15px
        rgba(255,190,60,.12);

}


/* ACTION ICON */

.voucher-action i{

    color:#ffd35a;

    text-shadow:

        0 0 5px #ff8c00,

        1px 1px 0 #000;

}


/* =====================================================
   VOUCHER BADGE
===================================================== */

.voucher-card .voucher-badge{

    position:absolute;

    top:6px;

    right:6px;

    z-index:10;

    padding:3px 6px;

    color:#fff;

    font-size:8px;

    font-weight:900;

    background:

        linear-gradient(
            135deg,
            #ff2020,
            #8d001d
        );

    border:
        1px solid
        rgba(255,209,92,.45);

    clip-path:

        polygon(
            4px 0,
            calc(100% - 4px) 0,
            100% 4px,
            100% calc(100% - 4px),
            calc(100% - 4px) 100%,
            4px 100%,
            0 calc(100% - 4px),
            0 4px
        );

    box-shadow:

        0 0 8px
        rgba(255,32,32,.35);

}


/* =====================================================
   EMPTY BOX
===================================================== */

.empty-box{

    padding:30px;

    text-align:center;

    color:#999;

    background:

        linear-gradient(
            145deg,
            #15151f,
            #09090e
        );

    border:
        1px solid
        rgba(255,255,255,.07);

    border-radius:8px;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,.4);

}


/* =====================================================
   TABLET
===================================================== */

@media(max-width:900px){

    .game-grid{

        grid-template-columns:
            repeat(3,1fr);

        gap:10px;

    }

    .voucher-grid{

        grid-template-columns:
            repeat(4,1fr);

        gap:10px;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .game-grid{

        grid-template-columns:
            repeat(3,1fr);

        gap:8px;

    }

    .voucher-grid{

        grid-template-columns:
            repeat(3,1fr);

        gap:8px;

    }

    .game-card{

        border-radius:7px;

    }

    .game-info{

        padding:8px;

    }

    .game-info h4{

        font-size:11px;

    }

    .game-info p{

        font-size:9px;

    }

    .moba-btn{

        min-height:38px;

        font-size:10px;

    }

    .moba-btn i{

        font-size:12px;

    }

    .voucher-card{

        min-height:112px;

        padding:7px;

    }

    .voucher-card img{

        width:50px;

        height:50px;

    }

    .voucher-card h4{

        font-size:11px;

    }

    .voucher-card p{

        font-size:9px;

    }

    .voucher-card .price{

        font-size:11px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .game-grid{

        grid-template-columns:
            repeat(2,1fr);

    }

    .voucher-grid{

        grid-template-columns:
            repeat(2,1fr);

    }

}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    .game-card,
    .game-image img,
    .moba-btn,
    .voucher-card,
    .voucher-card img,
    .voucher-action{

        transition:none;

    }

    .moba-btn:before{

        display:none;

    }

}

</style>
    
    <!-- =================================
     PRODUCTS SECTION
================================= -->

<section class="home-section">


<div class="section-title">

<h3>
<i class="fa-solid fa-laptop"></i> Computer & IT
</h3>


<a href="products.php">

ທັງໝົດ

</a>

</div>



<div class="product-grid">


<?php


$sql="

SELECT *

FROM products

WHERE status='active'

ORDER BY id DESC

LIMIT 12

";


$result=$conn->query($sql);



if($result && $result->num_rows>0){


while($product=$result->fetch_assoc()){



$image = !empty($product['image'])

?

"/admin/uploads/".$product['image']

:

"/assets/no-image.png";


?>


<a href="view-product.php?id=<?=$product['id']?>"

class="product-card">



<img src="<?=htmlspecialchars($image)?>">



<div>


<h4>

<?=htmlspecialchars(
$product['name']
)?>

</h4>


<p>

₭ <?=number_format(
$product['price']
)?>

</p>



<span>

Buy Now

</span>


</div>


</a>


<?php


}


}else{


echo "

<div class='empty-box'>

No Product

</div>

";


}


?>


</div>


</section>





<!-- =================================
     BLOG NEWS
================================= -->


<section class="home-section">


<div class="section-title">

<h3>

<i class="fa-solid fa-newspaper"></i> News & Blog

</h3>


<a href="blogs.php">

ເພີ່ມເຕີມ...

</a>

</div>



<div class="blog-grid">


<?php


$sql="

SELECT *

FROM blogs

WHERE status='published'

ORDER BY id DESC

LIMIT 4

";


$result=$conn->query($sql);



if($result && $result->num_rows>0){


while($blog=$result->fetch_assoc()){


$image=!empty($blog['image'])

?

"/admin/uploads/blogs/".$blog['image']

:

"/assets/no-image.png";


?>


<a href="blog-detail.php?id=<?=$blog['id']?>"

class="blog-card">



<img src="<?=htmlspecialchars($image)?>">



<h4>

<?=htmlspecialchars(
$blog['title']
)?>

</h4>


<p>

<?=mb_substr(
strip_tags($blog['content']),
0,
80
)?>...

</p>


</a>


<?php

}

}


?>


</div>


</section>





<!-- =================================
     REELS VIDEO
================================= -->


<section class="home-section">


<div class="section-title">

<h3>

    <i class="fa-solid fa-video"></i> TopUp Reels

</h3>


<a href="javascript:void(0);" onclick="showReelsPopup()">
    

    <span>
        ເພີ່ມເຕີມ...
    </span>
</a>

</div>




<div class="reels-box">


<video autoplay muted loop playsinline>


<source src="/uploads/reels/demo.mp4"

type="video/mp4">


</video>



<div class="reels-text">


<h3>

CNTECH REELS

</h3>


<p>

Gaming • Movie • Technology

</p>


<a href="javascript:void(0);" onclick="showReelsPopup()">
    

    <span>
        +
    </span>
</a>


</div>



</div>


</section>





<!-- =================================
     SUPPORT SLIDE
================================= -->


<section class="support-banner">


<div class="support-content">


<h2>

💬 Support 24/7

</h2>


<p>

ສອບຖາມບັນຫາ ຫຼື ຊ່ວຍເຫຼືອ

</p>



<a href="page/contact.php">

Contact

</a>


</div>


</section>





<style>

/* =====================================================
   CNTECH STORE
   PRODUCT / BLOG / REELS / SUPPORT
   MOBA OLD VISUAL 2017–2018
   DARK / GOLD / RED / GAME UI
===================================================== */


/* =====================================================
   PRODUCT GRID
===================================================== */

.product-grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:12px;

}


/* =====================================================
   PRODUCT CARD
===================================================== */

.product-card{

    position:relative;

    overflow:hidden;

    padding-bottom:10px;

    background:

        linear-gradient(
            145deg,
            rgba(35,30,22,0.267),
            rgba(9,9,11,0.062)
        );

    border:
        1px solid
        rgba(190,145,55,.35);

    border-radius:8px;

    box-shadow:

        0 12px 35px
        rgba(0,0,0,.75),

        inset 0 1px 0
        rgba(255,220,130,.08);

    transition:

        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease,
        filter .25s ease;

}


/* =====================================================
   OLD MOBA GOLD CORNERS
===================================================== */

.product-card::before{

    content:"";

    position:absolute;

    inset:0;

    z-index:4;

    pointer-events:none;

    border:

        1px solid
        rgba(255,205,100,.22);

    clip-path:

        polygon(
            10px 0,
            calc(100% - 10px) 0,

            100% 10px,

            100% calc(100% - 10px),

            calc(100% - 10px) 100%,

            10px 100%,

            0 calc(100% - 10px),

            0 10px
        );

}


/* =====================================================
   OLD LIGHT SWEEP
===================================================== */

.product-card::after{

    content:"";

    position:absolute;

    top:-120%;

    left:-80%;

    width:55%;

    height:300%;

    z-index:5;

    pointer-events:none;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.03),
            rgba(255,210,100,.13),
            rgba(255,80,30,.07),
            transparent
        );

    transform:
        rotate(25deg);

    animation:
        productLight
        3.5s
        linear
        infinite;

}


@keyframes productLight{

    0%{
        left:-90%;
    }

    100%{
        left:160%;
    }

}


/* =====================================================
   PRODUCT HOVER
===================================================== */

.product-card:hover{

    transform:
        translateY(-5px)
        scale(1.015);

    border-color:
        rgba(255,205,100,.7);

    filter:
        brightness(1.06);

    box-shadow:

        0 0 12px
        rgba(255,32,32,.28),

        0 0 25px
        rgba(255,190,60,.12),

        0 18px 45px
        rgba(0,0,0,.9);

}


/* =====================================================
   PRODUCT IMAGE
===================================================== */

.product-card img{

    position:relative;

    z-index:2;

    display:block;

    width:100%;

    aspect-ratio:1/1;

    object-fit:cover;

    border-bottom:

        1px solid
        rgba(255,205,100,.18);

    filter:

        brightness(.92)
        contrast(1.08)
        saturate(1.05);

    transition:

        transform .4s ease,
        filter .4s ease;

}


.product-card:hover img{

    transform:
        scale(1.06);

    filter:

        brightness(1.06)
        contrast(1.1)
        saturate(1.15);

}


/* =====================================================
   PRODUCT INFO
===================================================== */

.product-card > div{

    position:relative;

    z-index:7;

    padding:10px;

    background:

        linear-gradient(
            180deg,
            rgba(22,20,16,.96),
            rgba(5,5,6,.99)
        );

}


/* =====================================================
   PRODUCT NAME
===================================================== */

.product-card h4{

    margin:0;

    height:38px;

    overflow:hidden;

    color:#eee;

    font-size:14px;

    font-weight:900;

    line-height:1.35;

    text-shadow:

        1px 1px 0 #000,

        0 0 5px #000;

}


/* =====================================================
   PRODUCT PRICE
===================================================== */

.product-card p{

    margin-top:7px;

    color:#ffd35a;

    font-size:15px;

    font-weight:900;

    text-shadow:

        0 0 5px
        rgba(255,150,0,.45),

        1px 1px 0 #000;

}


/* =====================================================
   PRODUCT BUTTON
===================================================== */

.product-card span{

    position:relative;

    z-index:8;

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:6px;

    margin-top:8px;

    min-height:30px;

    padding:5px 14px;

    color:#fff;

    font-size:11px;

    font-weight:900;

    text-shadow:

        1px 1px 0 #000;

    background:

        linear-gradient(
            180deg,
            #9e1717,
            #4b0609
        );

    border:

        1px solid
        #b88a32;

    border-radius:5px;

    box-shadow:

        inset 0 1px 0
        rgba(255,235,160,.25),

        inset 0 -5px 10px
        rgba(0,0,0,.65),

        0 3px 7px
        rgba(0,0,0,.75);

    transition:.2s;

}


/* PRODUCT BUTTON HOVER */

.product-card:hover span{

    transform:
        translateY(-1px);

    filter:
        brightness(1.15);

    border-color:
        #e2b84c;

    box-shadow:

        0 0 10px
        rgba(255,180,50,.35),

        inset 0 1px 0
        rgba(255,240,170,.35);

}


/* =====================================================
   BLOG GRID
===================================================== */

.blog-grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:12px;

}


/* =====================================================
   BLOG CARD
===================================================== */

.blog-card{

    position:relative;

    overflow:hidden;

    padding-bottom:12px;

    background:

        linear-gradient(
            145deg,
            rgba(28,25,20,0.424),
            rgba(7,7,8,0.142)
        );

    border:

        1px solid
        rgba(190,145,55,.3);

    border-radius:8px;

    box-shadow:

        0 12px 35px
        rgba(0,0,0,.7);

    transition:

        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;

}


/* =====================================================
   BLOG FRAME
===================================================== */

.blog-card::before{

    content:"";

    position:absolute;

    inset:0;

    z-index:5;

    pointer-events:none;

    border:

        1px solid
        rgba(255,210,110,.18);

    clip-path:

        polygon(
            10px 0,
            calc(100% - 10px) 0,
            100% 10px,
            100% calc(100% - 10px),
            calc(100% - 10px) 100%,
            10px 100%,
            0 calc(100% - 10px),
            0 10px
        );

}


/* =====================================================
   BLOG LIGHT
===================================================== */

.blog-card::after{

    content:"";

    position:absolute;

    top:-100%;

    left:-90%;

    width:55%;

    height:300%;

    z-index:6;

    pointer-events:none;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,220,130,.12),
            transparent
        );

    transform:
        rotate(25deg);

    animation:
        blogLight
        4s
        linear
        infinite;

}


@keyframes blogLight{

    0%{
        left:-90%;
    }

    100%{
        left:160%;
    }

}


/* =====================================================
   BLOG HOVER
===================================================== */

.blog-card:hover{

    transform:
        translateY(-5px)
        scale(1.01);

    border-color:
        rgba(255,205,100,.6);

    box-shadow:

        0 0 15px
        rgba(255,32,32,.2),

        0 0 25px
        rgba(255,190,60,.1),

        0 18px 40px
        rgba(0,0,0,.9);

}


/* =====================================================
   BLOG IMAGE
===================================================== */

.blog-card img{

    position:relative;

    z-index:2;

    display:block;

    width:100%;

    height:130px;

    object-fit:cover;

    filter:

        brightness(.88)
        contrast(1.08)
        saturate(1.05);

    transition:

        transform .45s ease,
        filter .45s ease;

}


.blog-card:hover img{

    transform:
        scale(1.06);

    filter:

        brightness(1.05)
        contrast(1.1)
        saturate(1.15);

}


/* =====================================================
   BLOG TITLE
===================================================== */

.blog-card h4{

    position:relative;

    z-index:7;

    padding:

        10px 10px 0;

    color:#eee;

    font-size:14px;

    font-weight:900;

    line-height:1.4;

    text-shadow:

        1px 1px 0 #000;

}


/* =====================================================
   BLOG DESCRIPTION
===================================================== */

.blog-card p{

    position:relative;

    z-index:7;

    padding:

        5px 10px 0;

    color:#999;

    font-size:11px;

    line-height:1.5;

}


/* =====================================================
   REELS
===================================================== */

.reels-box{

    position:relative;

    height:300px;

    overflow:hidden;

    background:#030303;

    border:

        1px solid
        rgba(190,145,55,.45);

    border-radius:8px;

    box-shadow:

        0 18px 55px
        rgba(0,0,0,.85),

        0 0 25px
        rgba(255,32,32,.12);

}


/* =====================================================
   REELS OLD FRAME
===================================================== */

.reels-box::before{

    content:"";

    position:absolute;

    inset:0;

    z-index:4;

    pointer-events:none;

    border:

        1px solid
        rgba(255,205,100,.3);

    clip-path:

        polygon(
            12px 0,
            calc(100% - 12px) 0,
            100% 12px,
            100% calc(100% - 12px),
            calc(100% - 12px) 100%,
            12px 100%,
            0 calc(100% - 12px),
            0 12px
        );

}


/* =====================================================
   REELS DARK OVERLAY
===================================================== */

.reels-box::after{

    content:"";

    position:absolute;

    inset:0;

    z-index:2;

    pointer-events:none;

    background:

        linear-gradient(
            180deg,
            rgba(0,0,0,.05),
            transparent 40%,
            rgba(0,0,0,.88)
        );

}


/* =====================================================
   REELS VIDEO
===================================================== */

.reels-box video{

    width:100%;

    height:100%;

    object-fit:cover;

    filter:

        brightness(.86)
        contrast(1.08)
        saturate(1.08);

    transition:
        transform .8s ease,
        filter .5s ease;

}


.reels-box:hover video{

    transform:
        scale(1.04);

    filter:

        brightness(.98)
        contrast(1.1)
        saturate(1.12);

}


/* =====================================================
   REELS TEXT
===================================================== */

.reels-text{

    position:absolute;

    z-index:6;

    bottom:18px;

    left:18px;

    right:18px;

}


.reels-text h3{

    margin-bottom:8px;

    color:#fff;

    font-size:18px;

    font-weight:900;

    text-shadow:

        2px 2px 0 #000,

        0 3px 12px #000;

}


/* =====================================================
   REELS BUTTON
===================================================== */

.reels-text a{

    display:flex;

    align-items:center;

    justify-content:center;

    width:46px;

    height:46px;

    color:#fff;

    background:

        linear-gradient(
            180deg,
            #b51a1a,
            #560008
        );

    border:

        1px solid
        #d1a13e;

    border-radius:6px;

    font-size:18px;

    box-shadow:

        0 0 12px
        rgba(255,32,32,.35),

        inset 0 1px 0
        rgba(255,230,150,.2);

    transition:.2s;

}


.reels-text a:hover{

    transform:
        translateY(-2px)
        scale(1.06);

    filter:
        brightness(1.15);

    box-shadow:

        0 0 20px
        rgba(255,32,32,.6),

        0 0 10px
        rgba(255,190,50,.25);

}


/* =====================================================
   SUPPORT BANNER
===================================================== */

.support-banner{

    position:relative;

    overflow:hidden;

    margin:30px 0;

    min-height:160px;

    display:flex;

    align-items:center;

    padding:22px;

    background:

        linear-gradient(
            135deg,
            #5e0808,
            #260507 45%,
            #090806 100%
        );

    border:

        1px solid
        rgba(205,160,60,.45);

    border-radius:8px;

    box-shadow:

        0 18px 55px
        rgba(0,0,0,.8),

        0 0 25px
        rgba(255,32,32,.12),

        inset 0 1px 0
        rgba(255,220,130,.08);

}


/* =====================================================
   SUPPORT GOLD FRAME
===================================================== */

.support-banner::before{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    border:

        1px solid
        rgba(255,210,100,.22);

    clip-path:

        polygon(
            14px 0,
            calc(100% - 14px) 0,
            100% 14px,
            100% calc(100% - 14px),
            calc(100% - 14px) 100%,
            14px 100%,
            0 calc(100% - 14px),
            0 14px
        );

}


/* =====================================================
   SUPPORT LIGHT
===================================================== */

.support-banner::after{

    content:"";

    position:absolute;

    top:-120%;

    left:-80%;

    width:55%;

    height:300%;

    pointer-events:none;

    background:

        linear-gradient(
            90deg,
            transparent,
            rgba(255,220,130,.12),
            rgba(255,80,30,.08),
            transparent
        );

    transform:
        rotate(25deg);

    animation:
        supportOldLight
        4s
        linear
        infinite;

}


@keyframes supportOldLight{

    0%{
        left:-90%;
    }

    100%{
        left:160%;
    }

}


/* =====================================================
   SUPPORT CONTENT
===================================================== */

.support-content{

    position:relative;

    z-index:5;

}


.support-content h2{

    color:#ffd35a;

    font-size:25px;

    font-weight:900;

    text-shadow:

        1px 1px 0 #000,

        0 0 8px
        rgba(255,170,40,.35);

}


.support-content p{

    margin-top:5px;

    color:#ddd;

    font-size:13px;

    text-shadow:
        1px 1px 0 #000;

}


/* =====================================================
   SUPPORT BUTTON
===================================================== */

.support-content a{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    margin-top:12px;

    min-height:38px;

    padding:7px 22px;

    color:#fff;

    font-size:12px;

    font-weight:900;

    text-shadow:

        1px 1px 0 #000;

    background:

        linear-gradient(
            180deg,
            #a71919,
            #500008
        );

    border:

        1px solid
        #d0a13e;

    border-radius:5px;

    box-shadow:

        inset 0 1px 0
        rgba(255,235,160,.25),

        inset 0 -5px 10px
        rgba(0,0,0,.65),

        0 4px 10px
        rgba(0,0,0,.7);

    transition:.2s;

}


.support-content a:hover{

    transform:
        translateY(-2px);

    filter:
        brightness(1.15);

    border-color:
        #f0c85b;

    box-shadow:

        0 0 15px
        rgba(255,32,32,.45),

        0 0 8px
        rgba(255,190,50,.25);

}


/* =====================================================
   EMPTY
===================================================== */

.empty-box{

    padding:30px;

    text-align:center;

    color:#999;

    background:

        linear-gradient(
            145deg,
            #18150f,
            #080807
        );

    border:

        1px solid
        rgba(190,145,55,.3);

    border-radius:8px;

    box-shadow:

        0 12px 35px
        rgba(0,0,0,.7);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .product-grid,
    .blog-grid{

        gap:9px;

    }


    .product-card,
    .blog-card{

        border-radius:7px;

    }


    .product-card h4{

        height:34px;

        font-size:12px;

    }


    .product-card p{

        font-size:13px;

    }


    .product-card span{

        min-height:27px;

        padding:4px 10px;

        font-size:10px;

    }


    .blog-card img{

        height:105px;

    }


    .blog-card h4{

        font-size:12px;

    }


    .blog-card p{

        font-size:10px;

    }


    .reels-box{

        height:280px;

        border-radius:7px;

    }


    .reels-text h3{

        font-size:16px;

    }


    .support-banner{

        min-height:145px;

        padding:18px;

        border-radius:7px;

    }


    .support-content h2{

        font-size:21px;

    }


    .support-content p{

        font-size:11px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media(max-width:380px){

    .product-grid,
    .blog-grid{

        gap:8px;

    }

}


/* =====================================================
   REDUCE MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    .product-card,
    .product-card img,
    .blog-card,
    .blog-card img,
    .reels-box video,
    .support-content a{

        transition:none;

    }


    .product-card::after,
    .blog-card::after,
    .support-banner::after{

        animation:none;

    }

}

</style>
    
    <!-- =================================
     FOOTER
================================= -->


<footer class="footer">


<div class="footer-grid">


<div>

<h3>

CN TECH STORE

</h3>


<p>

Computer • Mobile • Game Top Up

</p>


<p>

ຮ້ານຄ້າອອນລາຍ ສປປ ລາວ

</p>


</div>




<div>


<h4>

Service

</h4>


<a href="page/game_topup.php">

ເຕີມເກມອອນລາຍ

</a>


<a href="page/products.php">

ສິນຄ້າບໍລິການ

</a>


<a href="javascript:void(0);" onclick="showReelsPopup()">
    

    <span>
        ຄວາມບັນເທິງ
    </span>
</a>



</div>




<div>


<h4>

Support

</h4>


<a href="page/contact.php">

ຕິດຕໍ່ພວກເຮົາ

</a>


<a href="page/payment-method.php">

ບໍລິການຊຳລະເງິນອອນລາຍ

</a>


<a href="page/profile.php">

ບັນຊີ

</a>


</div>



<div>


<h4>

Follow

</h4>


<div class="social">


<a href="https://www.facebook.com/share/18L9zqrV5n/" >

<i class="fab fa-facebook"></i>

</a>


<a href="https://tiktok.com/@cntechstore">

<i class="fab fa-tiktok"></i>

</a>


<a>

<i class="fab fa-youtube"></i>

</a>


</div>


</div>



</div>




<div class="copyright">


© <?=date('Y')?> CN Tech Store

<br>

All Rights Reserved


</div>


</footer>






<!-- =================================
 MOBILE APP NAVBAR
================================= -->


<nav class="mobile-nav">



<a href="index.php">

<i class="fa-solid fa-house"></i>

<span>

ໜ້າຫຼັກ

</span>

</a>



<a href="javascript:void(0);" onclick="showReelsPopup()">
    <i class="fa-solid fa-video"></i>

    <span>
        ຄວາມບັນເທິງ
    </span>
</a>



<a href="page/game_topup.php">


<i class="fa-solid fa-gamepad"></i>

<span>

ເກມ ແລະ ບໍລິການ

</span>

</a>




<a href="page/orders.php">


<i class="fa-solid fa-box"></i>

<span>

ປະຫວັດທຸລະກຳ

</span>

</a>




<a href="profile.php">


<i class="fa-solid fa-user"></i>

<span>

ບັນຊີ

</span>

</a>



</nav>







<style>
:root{
 --red:#ff2020;
 --red2:#a80000;
 --gold:#ffd15c;
 --gold2:#9f7725;
 --white:#fff;
 --black:rgba(0,0,0,.68);
 --glass:rgba(0,0,0,.38);
 --line:rgba(255,209,92,.28);
}

/* SEARCH */
.search-wrapper{
 position:relative;
 z-index:50000;
 max-width:700px;
 margin:auto;
}

.search-box{
 display:flex;
 overflow:hidden;
 border:1px solid var(--line);
 border-radius:7px;
 background:rgba(0,0,0,.42);
 backdrop-filter:blur(12px);
 box-shadow:0 8px 25px rgba(0,0,0,.5);
}

.search-box input{
 flex:1;
 padding:13px 15px;
 border:0;
 outline:0;
 color:#fff;
 background:transparent;
}

.search-box input::placeholder{
 color:rgba(255,255,255,.55);
}

.search-box button{
 width:55px;
 border:0;
 color:#fff;
 background:linear-gradient(180deg,#ff2020,#850000);
}

/* SEARCH RESULTS */
.search-results{
 position:absolute;
 top:63px;
 left:0;
 right:0;
 z-index:99999;
 display:none;
 max-height:70vh;
 overflow:auto;

 background:rgba(0,0,0,.68);
 backdrop-filter:blur(18px);

 border:1px solid rgba(255,209,92,.35);
 border-radius:9px;

 box-shadow:
  0 20px 60px rgba(0,0,0,.75),
  inset 0 1px rgba(255,255,255,.08);
}

.search-item{
 display:flex;
 align-items:center;
 gap:12px;
 padding:10px 13px;
 color:#fff;
 border-bottom:1px solid rgba(255,255,255,.08);
 transition:.2s;
}

.search-item:hover{
 background:linear-gradient(
  90deg,
  rgba(255,32,32,.18),
  rgba(255,209,92,.08),
  transparent
 );
}

.search-image{
 width:50px;
 height:50px;
 min-width:50px;
 overflow:hidden;
 border-radius:5px;
 border:1px solid rgba(255,209,92,.25);
 background:rgba(0,0,0,.5);
}

.search-image img{
 width:100%;
 height:100%;
 object-fit:cover;
}

.search-info{
 flex:1;
 min-width:0;
}

.search-name{
 font-size:14px;
 font-weight:900;
 white-space:nowrap;
 overflow:hidden;
 text-overflow:ellipsis;
}

.search-type{
 margin-top:3px;
 color:rgba(255,255,255,.55);
 font-size:11px;
}

.search-price{
 margin-top:3px;
 color:var(--gold);
 font-size:13px;
 font-weight:900;
}

/* FOOTER */
.footer{
 margin-top:45px;
 padding:15px 18px 100px;
 background:rgba(0,0,0,0);
 backdrop-filter:blur(1px);
 border-top:1px solid rgba(255,209,92,.3);
}

.footer h3{
 color:var(--gold);
}

.footer a{
 display:block;
 margin:8px 0;
 color:rgba(255,255,255,.65);
 font-size:13px;
}

.footer a:hover{
 color:#fff;
}

.social{
 display:flex;
 gap:8px;
 margin-top:12px;
}

.social a{
 width:40px;
 height:40px;
 display:flex;
 align-items:center;
 justify-content:center;
 margin:0;
 border:1px solid rgba(255,209,92,.22);
 border-radius:6px;
 background:rgba(0,0,0,.42);
}

.copyright{
 margin-top:30px;
 padding-top:18px;
 border-top:1px solid rgba(255,255,255,.08);
 text-align:center;
 color:rgba(255,255,255,.4);
 font-size:11px;
}

/* MOBILE NAV */
.mobile-nav{
 position:fixed;
 left:8px;
 right:8px;
 bottom:8px;
 height:64px;
 z-index:100000;

 display:flex;
 justify-content:space-around;
 align-items:center;

 background:rgba(0,0,0,.62);
 backdrop-filter:blur(16px);

 border:1px solid rgba(255,209,92,.3);
 border-radius:9px;

 box-shadow:0 15px 40px rgba(0,0,0,.7);
}

.mobile-nav a{
 width:20%;
 height:100%;
 display:flex;
 flex-direction:column;
 align-items:center;
 justify-content:center;
 gap:4px;

 color:rgba(255,255,255,.5);
 font-size:9px;
 font-weight:800;
}

.mobile-nav i{
 font-size:18px;
}

.mobile-nav a.active{
 color:#fff;
}

.mobile-nav a.active i{
 color:var(--red);
 filter:drop-shadow(0 0 8px var(--red));
}

@media(min-width:768px){
 body{padding-bottom:0}
 .mobile-nav{display:none}
}
</style>



<script>
(()=>{

const S='/audio/Click03.mp3';
const A=new Audio(S);
A.volume=.7;

let down=false,last=0,lock=0;

const colors=['#ff2020','#d946ef','#00d9ff','#ffd15c'];

const rnd=(a,b)=>Math.random()*(b-a)+a;
const col=()=>colors[Math.floor(Math.random()*colors.length)];

function sound(){
  if(Date.now()-lock<180)return;
  lock=Date.now();
  let a=A.cloneNode();
  a.volume=.7;
  a.play().catch(()=>{});
  a.onended=()=>a.remove();
}

function fx(x,y,big=false){

  let e=document.createElement('i');
  e.className='cnfx '+(big?'big':'');
  e.style.left=x+'px';
  e.style.top=y+'px';
  e.style.setProperty('--c',col());
  document.body.appendChild(e);

  for(let i=0;i<(big?18:7);i++){
    let p=document.createElement('b');
    p.style.setProperty('--x',rnd(-70,70)+'px');
    p.style.setProperty('--y',rnd(-70,70)+'px');
    p.style.setProperty('--c',col());
    e.appendChild(p);
  }

  setTimeout(()=>e.remove(),900);
}

function trail(x,y){
  if(Date.now()-last<25)return;
  last=Date.now();

  let p=document.createElement('i');
  p.className='cntrail';
  p.style.left=x+'px';
  p.style.top=y+'px';
  p.style.setProperty('--c',col());
  document.body.appendChild(p);

  setTimeout(()=>p.remove(),500);
}

/* TOUCH */

document.addEventListener('touchstart',e=>{
  down=true;
  let t=e.touches[0];
  fx(t.clientX,t.clientY);
},{passive:true});

document.addEventListener('touchmove',e=>{
  if(!down)return;
  for(let t of e.touches)trail(t.clientX,t.clientY);
},{passive:true});

document.addEventListener('touchend',()=>{
  down=false;
});

/* MOUSE */

document.addEventListener('mousedown',e=>{
  down=true;
  fx(e.clientX,e.clientY);
});

document.addEventListener('mousemove',e=>{
  if(down)trail(e.clientX,e.clientY);
});

document.addEventListener('mouseup',()=>{
  down=false;
});

/* BUTTON / LINK */

document.addEventListener('click',e=>{
  let el=e.target.closest('a,button');
  if(!el)return;

  let r=el.getBoundingClientRect();
  fx(r.left+r.width/2,r.top+r.height/2,true);
  sound();
},true);


/* CSS */

let s=document.createElement('style');

s.textContent=`

.cnfx{
 position:fixed;
 width:20px;height:20px;
 transform:translate(-50%,-50%);
 pointer-events:none;
 z-index:999999;
 border-radius:50%;
 background:#fff;
 box-shadow:0 0 10px #fff,0 0 30px var(--c),0 0 70px var(--c);
 animation:boom .7s ease-out forwards;
}

.cnfx b{
 position:absolute;
 width:5px;height:5px;
 border-radius:50%;
 background:var(--c);
 box-shadow:0 0 12px var(--c);
 animation:star .8s ease-out forwards;
}

.cnfx.big{
 width:30px;height:30px;
 box-shadow:0 0 15px #fff,0 0 50px var(--c),0 0 100px var(--c);
}

.cntrail{
 position:fixed;
 width:7px;height:7px;
 transform:translate(-50%,-50%);
 pointer-events:none;
 border-radius:50%;
 background:var(--c);
 box-shadow:0 0 8px var(--c),0 0 20px var(--c);
 animation:trail .5s ease-out forwards;
 z-index:999998;
}

@keyframes boom{
 0%{opacity:1;transform:translate(-50%,-50%) scale(.2)}
 100%{opacity:0;transform:translate(-50%,-50%) scale(4)}
}

@keyframes star{
 0%{opacity:1;transform:translate(0,0) scale(1)}
 100%{opacity:0;transform:translate(var(--x),var(--y)) scale(0)}
}

@keyframes trail{
 0%{opacity:1;transform:translate(-50%,-50%) scale(1.5)}
 100%{opacity:0;transform:translate(-50%,-50%) scale(.1)}
}

`;

document.head.appendChild(s);

})();
</script>



<!-- =================================
 PWA THEME
================================= -->


<script>


// Active mobile menu

let currentPage =
location.pathname;


document.querySelectorAll(
".mobile-nav a"
).forEach(link=>{


if(
link.href.includes(currentPage)
){

link.classList.add(
"active"
);

}


});



</script>


<script>

const searchInput =
    document.getElementById("liveSearch");

const searchResults =
    document.getElementById("searchResults");


let searchTimer = null;


searchInput.addEventListener(
    "input",
    function(){

        const q =
            this.value.trim();


        clearTimeout(
            searchTimer
        );


        if(q.length === 0){

            searchResults.innerHTML = "";

            searchResults.style.display =
                "none";

            return;
        }


        searchTimer =
            setTimeout(
                function(){

                    fetch(
                        "search.php?q=" +
                        encodeURIComponent(q),
                        {
                            cache:"no-store"
                        }
                    )

                    .then(
                        response =>
                            response.json()
                    )

                    .then(
                        data => {

                            if(
                                !data.success
                            ){

                                searchResults.innerHTML =
                                    `
                                    <div class="search-empty">
                                        
                                      ${escapeHTML(
                                            data.message ||
                                            "Search Error"
                                        )}
                                    </div>
                                    `;

                                searchResults.style.display =
                                    "block";

                                return;
                            }


                            if(
                                !data.results ||
                                data.results.length === 0
                            ){

                                searchResults.innerHTML =
                                    `
                                    <div class="search-empty">
                                        
                                        ບໍ່ພົບຂໍ້ມູນ
                                    </div>
                                    `;

                                searchResults.style.display =
                                    "block";

                                return;
                            }


                            searchResults.innerHTML =
                                data.results
                                .map(
                                    item => `

                                    <a
                                        href="${escapeHTML(item.url)}"
                                        class="search-item"
                                    >

                                        <div class="search-image">

                                            ${
                                                item.image

                                                ?

                                                `
                                                <img
                                                    src="${escapeHTML(item.image)}"
                                                    onerror="
                                                        this.style.display='none';
                                                    "
                                                >
                                                `

                                                :

                                                `
                                                <i class="
                                                    fa-solid
                                                    fa-box
                                                "></i>
                                                `
                                            }

                                        </div>


                                        <div
                                            class="search-info"
                                        >

                                            <div
                                                class="search-name"
                                            >
                                                ${escapeHTML(
                                                    item.name
                                                )}
                                            </div>


                                            <div
                                                class="search-type"
                                            >
                                                ${escapeHTML(
                                                    item.type
                                                )}
                                            </div>


                                            <div
                                                class="search-price"
                                            >
                                                ${escapeHTML(
                                                    item.price_text
                                                )}
                                            </div>

                                        </div>


                                        <i class="
                                            fa-solid
                                            fa-chevron-right
                                            search-arrow
                                        "></i>

                                    </a>

                                    `
                                )
                                .join("");


                            searchResults.style.display =
                                "block";

                        }
                    )

                    .catch(
                        error => {

                            console.error(
                                "Search Error:",
                                error
                            );


                            searchResults.innerHTML =
                                `
                                <div class="search-empty">

                                    
                                    ລະບົບຄົ້ນຫາຂັດຂ້ອງ

                                </div>
                                `;

                            searchResults.style.display =
                                "block";

                        }
                    );

                },
                150
            );

    }
);


/*
==================================================
ESCAPE HTML
==================================================
*/

function escapeHTML(
    value
){

    const div =
        document.createElement(
            "div"
        );

    div.textContent =
        value ?? "";

    return div.innerHTML;
}


/*
==================================================
CLOSE
==================================================
*/

document.addEventListener(
    "click",
    function(e){

        if(
            !e.target.closest(
                ".search-wrapper"
            )
        ){

            searchResults.style.display =
                "none";

        }

    }
);

</script>

<script>
(function(){

'use strict';

/* =====================================================
   CNTECH STORE
   LOBBY AUDIO ENGINE
   เพลง Lobby กำหนดไฟล์ตายตัว
===================================================== */


/* =====================================================
   SETTINGS
===================================================== */

const START_DELAY = 1000;

const MUSIC_VOLUME = 0.25;
const EFFECT_VOLUME = 0.20;


/* =====================================================
   LOBBY MUSIC
   กำหนดเพลง Lobby ตายตัว
   เพิ่มได้ไม่จำกัด
===================================================== */

const LOBBY_FILES = <?php

$lobbyFiles = [];

$lobbyPath = __DIR__ . '/audio';


/*
 * หาไฟล์ lobby01.mp3
 * lobby02.mp3
 * lobby03.mp3
 * ...
 */

foreach(
    glob($lobbyPath . '/lobby*.mp3') ?: []
    as $file
){

    $lobbyFiles[] =
        '/audio/' . basename($file);

}


/*
 * เรียงตามชื่อไฟล์
 */

natsort($lobbyFiles);

$lobbyFiles =
    array_values($lobbyFiles);


echo json_encode(
    $lobbyFiles,
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE
);

?>;


/* =====================================================
   CHECK LOBBY MUSIC
===================================================== */

if(
    !Array.isArray(LOBBY_FILES) ||
    LOBBY_FILES.length === 0
){

    console.warn(
        '[CNTECH LOBBY] No lobby music found'
    );

    return;

}


/* =====================================================
   STATE
===================================================== */

let enabled =
    localStorage.getItem('cntech_sound') !== 'off';

let unlocked = false;

let musicPlaying = false;

let currentMusic = null;

let currentIndex = -1;

let musicTimer = null;


/* =====================================================
   RANDOM LOBBY MUSIC
   ไม่เล่นเพลงเดิมติดกัน
===================================================== */

function randomLobby(){

    if(
        LOBBY_FILES.length === 1
    ){

        currentIndex = 0;

        return LOBBY_FILES[0];

    }


    let index;


    do{

        index =
            Math.floor(
                Math.random() *
                LOBBY_FILES.length
            );

    }while(
        index === currentIndex
    );


    currentIndex = index;


    return LOBBY_FILES[index];

}


/* =====================================================
   CREATE AUDIO
===================================================== */

function createAudio(
    src,
    volume
){

    const a =
        new Audio(src);

    a.preload =
        'auto';

    a.volume =
        volume;

    return a;

}


/* =====================================================
   PLAY LOBBY MUSIC
===================================================== */

function playMusic(){

    if(!enabled)
        return;

    if(!unlocked)
        return;

    if(musicPlaying)
        return;


    const src =
        randomLobby();


    currentMusic =
        createAudio(
            src,
            MUSIC_VOLUME
        );


    musicPlaying =
        true;


    currentMusic.addEventListener(
        'ended',
        function(){

            musicPlaying =
                false;

            currentMusic =
                null;


            musicTimer =
                setTimeout(
                    function(){

                        playMusic();

                    },
                    200
                );

        }
    );


    currentMusic
        .play()
        .then(function(){

            console.log(
                '[CNTECH LOBBY MUSIC]',
                src
            );

        })
        .catch(function(){

            musicPlaying =
                false;

        });

}


/* =====================================================
   STOP LOBBY MUSIC
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

    unlocked =
        true;


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
   START DELAY
===================================================== */

const startAt =
    Date.now() + START_DELAY;


setTimeout(
    function(){

        console.log(
            '[CNTECH LOBBY] START'
        );

        playMusic();

    },
    START_DELAY
);


/* =====================================================
   SOUND BUTTON
===================================================== */

const sound =
    document.createElement(
        'button'
    );


sound.id =
    'cntechSoundControl';


sound.type =
    'button';


sound.innerHTML =
    '';


sound.setAttribute(
    'aria-label',
    'CNTECH Sound'
);


sound.style.cssText = `

position:fixed;
right:14px;
bottom:85px;
z-index:999999;

width:44px;
height:44px;

display:none;

align-items:center;
justify-content:center;

border-radius:13px;

border:
1px solid
rgba(255,32,32,.55);

background:
linear-gradient(
    145deg,
    #191923,
    #07070b
);

color:#fff;

font-size:19px;

cursor:pointer;

box-shadow:
0 0 15px
rgba(255,32,32,.35);

`;


document.body.appendChild(
    sound
);


/* =====================================================
   SOUND TOGGLE
===================================================== */

sound.addEventListener(
    'click',
    function(e){

        e.preventDefault();

        e.stopPropagation();


        enabled =
            !enabled;


        localStorage.setItem(
            'cntech_sound',
            enabled
                ? 'on'
                : 'off'
        );


        if(enabled){

            sound.innerHTML =
                '';

            if(unlocked){

                playMusic();

            }

        }else{

            sound.innerHTML =
                '';

            stopMusic();

        }

    }
);


/* =====================================================
   PAGE VISIBILITY
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
   DEBUG
===================================================== */

console.log(
    '%c CNTECH LOBBY AUDIO ',
    `
    background:#ff2020;
    color:#fff;
    font-weight:900;
    padding:6px 12px;
    border-radius:6px;
    `
);


console.log(
    'Lobby Music:',
    LOBBY_FILES.length
);


console.log(
    LOBBY_FILES
);

})();
</script>

<script>
/* =====================================================
   CNTECH STORE
   AUDIO BUTTON ENGINE
   click01 = OPEN
   click02 = CLOSE
===================================================== */

(function(){

    'use strict';


    /* =================================================
       CONFIG
    ================================================= */

    const AUDIO_PATH = '/audio/';

    const OPEN_SOUND  = 'click01.mp3';
    const CLOSE_SOUND = 'click02.mp3';

    const VOLUME = 0.45;


    /* =================================================
       AUDIO BUTTON STORAGE
    ================================================= */

    const audioButton = {

        openSound: null,
        closeSound: null,


        /* =============================================
           LOAD AUDIO
        ============================================= */

        init: function(){

            this.openSound =
                new Audio(
                    AUDIO_PATH + OPEN_SOUND
                );

            this.closeSound =
                new Audio(
                    AUDIO_PATH + CLOSE_SOUND
                );


            this.openSound.preload =
                'auto';

            this.closeSound.preload =
                'auto';


            this.openSound.volume =
                VOLUME;

            this.closeSound.volume =
                VOLUME;

        },


        /* =============================================
           PLAY
        ============================================= */

        play: function(audio){

            if(!audio)
                return;


            try{

                audio.pause();

                audio.currentTime = 0;

                audio.play()
                    .catch(function(){});

            }catch(error){

                console.warn(
                    'CNTECH audio error:',
                    error
                );

            }

        },


        /* =============================================
           OPEN / ENTER
           click01.mp3
        ============================================= */

        open: function(){

            this.play(
                this.openSound
            );

        },


        /* =============================================
           CLOSE / EXIT
           click02.mp3
        ============================================= */

        close: function(){

            this.play(
                this.closeSound
            );

        }

    };


    /* =================================================
       INIT
    ================================================= */

    audioButton.init();


    /* =================================================
       GLOBAL
    ================================================= */

    window.audioButton =
        audioButton;


    /* =================================================
       EXAMPLE
       
       เปิด:
       audioButton.open();

       ปิด:
       audioButton.close();
    ================================================= */


    console.log(
        '%c CNTECH AUDIO BUTTON ',
        'background:#ff2020;color:#fff;font-weight:900;padding:6px 10px'
    );

    console.log(
        'OPEN :',
        AUDIO_PATH + OPEN_SOUND
    );

    console.log(
        'CLOSE:',
        AUDIO_PATH + CLOSE_SOUND
    );

})();
</script>

<script>

/* =====================================================
   CNTECH MUSIC REACTIVE ENGINE
   Audio → Beat → Fire → Sparks → UI Energy
===================================================== */

(() => {

    "use strict";


    /* ==========================================
       CONFIG
    ========================================== */

    const CONFIG = {

        sensitivity: 1.8,

        bassBoost: 1.35,

        beatThreshold: 1.25,

        sparkAmount: 3,

        maxSparks: 80,

        smoothing: 0.72,

        minBeatDelay: 90

    };


    /* ==========================================
       STATE
    ========================================== */

    let audioContext = null;

    let analyser = null;

    let source = null;

    let dataArray = null;

    let connectedAudio = null;

    let running = false;

    let lastBeat = 0;

    let averageVolume = 0;


    /* ==========================================
       FIND AUDIO
    ========================================== */

    function findAudio(){

        return document.querySelector(
            "audio"
        );

    }


    /* ==========================================
       CREATE AUDIO ENGINE
    ========================================== */

    function initAudio(audio){

        if(!audio)
            return;


        if(connectedAudio === audio)
            return;


        try{

            audioContext =
                new (
                    window.AudioContext ||
                    window.webkitAudioContext
                )();


            analyser =
                audioContext.createAnalyser();


            analyser.fftSize = 256;

            analyser.smoothingTimeConstant =
                CONFIG.smoothing;


            source =
                audioContext.createMediaElementSource(
                    audio
                );


            source.connect(analyser);

            analyser.connect(
                audioContext.destination
            );


            dataArray =
                new Uint8Array(
                    analyser.frequencyBinCount
                );


            connectedAudio = audio;

            running = true;

            createSparkLayer();

            requestAnimationFrame(
                analyse
            );


        }catch(error){

            console.warn(
                "CNTECH Music Engine:",
                error
            );

        }

    }


    /* ==========================================
       AUDIO START
    ========================================== */

    function start(){

        const audio =
            findAudio();

        if(!audio)
            return;


        initAudio(audio);


        if(
            audioContext &&
            audioContext.state === "suspended"
        ){

            audioContext.resume();

        }

    }


    /* ==========================================
       CREATE SPARK LAYER
    ========================================== */

    function createSparkLayer(){

        document
            .querySelectorAll(
                ".music-sparks"
            )
            .forEach(container => {

                if(
                    container.dataset.sparkReady
                )
                    return;


                container.dataset.sparkReady =
                    "1";


                for(
                    let i = 0;
                    i < CONFIG.maxSparks;
                    i++
                ){

                    const spark =
                        document.createElement("i");


                    spark.style.setProperty(
                        "--spark-x",
                        `${random(-160,160)}px`
                    );


                    spark.style.setProperty(
                        "--spark-y",
                        `${random(-90,-260)}px`
                    );


                    spark.style.setProperty(
                        "--spark-time",
                        `${random(.45,1.25)}s`
                    );


                    container.appendChild(
                        spark
                    );

                }

            });

    }


    /* ==========================================
       ANALYSER
    ========================================== */

    function analyse(){

        if(!running)
            return;


        if(!analyser){

            requestAnimationFrame(
                analyse
            );

            return;

        }


        analyser.getByteFrequencyData(
            dataArray
        );


        const volume =
            getVolume();


        const bass =
            getBass();


        const power =
            Math.min(
                1,
                (
                    volume * .65 +
                    bass * .35 * CONFIG.bassBoost
                ) *
                CONFIG.sensitivity
            );


        averageVolume =
            averageVolume * .9 +
            power * .1;


        setMusicPower(
            power
        );


        detectBeat(
            bass,
            power
        );


        if(power > .25){

            emitSparks(
                power
            );

        }


        requestAnimationFrame(
            analyse
        );

    }


    /* ==========================================
       VOLUME
    ========================================== */

    function getVolume(){

        let total = 0;

        for(
            let i = 0;
            i < dataArray.length;
            i++
        ){

            total +=
                dataArray[i];

        }


        return (
            total /
            dataArray.length /
            255
        );

    }


    /* ==========================================
       BASS
    ========================================== */

    function getBass(){

        const bassCount =
            Math.max(
                5,
                Math.floor(
                    dataArray.length * .12
                )
            );


        let total = 0;


        for(
            let i = 0;
            i < bassCount;
            i++
        ){

            total +=
                dataArray[i];

        }


        return (
            total /
            bassCount /
            255
        );

    }


    /* ==========================================
       MUSIC POWER
    ========================================== */

    function setMusicPower(power){

        document.documentElement
            .style
            .setProperty(
                "--music-power",
                power.toFixed(3)
            );

    }


    /* ==========================================
       BEAT DETECTION
    ========================================== */

    function detectBeat(
        bass,
        power
    ){

        const now =
            performance.now();


        const threshold =
            Math.max(
                CONFIG.beatThreshold *
                averageVolume,
                .32
            );


        if(

            bass > threshold &&

            power > .35 &&

            now - lastBeat >
            CONFIG.minBeatDelay

        ){

            lastBeat = now;

            triggerBeat();

        }

    }


    /* ==========================================
       BEAT EFFECT
    ========================================== */

    function triggerBeat(){

        document
            .querySelectorAll(
                ".music-beat"
            )
            .forEach(element => {

                element.classList.remove(
                    "beat"
                );


                void element.offsetWidth;


                element.classList.add(
                    "beat"
                );

            });


        emitSparks(
            1
        );

    }


    /* ==========================================
       SPARK EMITTER
    ========================================== */

    function emitSparks(power){

        document
            .querySelectorAll(
                ".music-sparks"
            )
            .forEach(container => {

                if(
                    !container.dataset.sparkReady
                )
                    return;


                const sparks =
                    container.querySelectorAll(
                        "i"
                    );


                const amount =
                    Math.max(
                        1,
                        Math.floor(
                            CONFIG.sparkAmount *
                            power
                        )
                    );


                container.classList.add(
                    "active"
                );


                for(
                    let n = 0;
                    n < amount;
                    n++
                ){

                    const spark =
                        sparks[
                            randomInt(
                                0,
                                sparks.length - 1
                            )
                        ];


                    if(
                        !spark
                    )
                        continue;


                    spark.style.setProperty(
                        "--spark-x",
                        `${random(-180,180)}px`
                    );


                    spark.style.setProperty(
                        "--spark-y",
                        `${random(-70,-280)}px`
                    );


                    spark.style.setProperty(
                        "--spark-time",
                        `${random(.35,.95)}s`
                    );


                    spark.style.left =
                        `${random(10,90)}%`;


                    spark.style.animation =
                        "none";


                    void spark.offsetWidth;


                    spark.style.animation =
                        `sparkFly ${spark.style.getPropertyValue("--spark-time")} linear forwards`;

                }


                setTimeout(
                    () => {

                        container.classList.remove(
                            "active"
                        );

                    },
                    1000
                );

            });

    }


    /* ==========================================
       RANDOM
    ========================================== */

    function random(
        min,
        max
    ){

        return (
            Math.random() *
            (max - min) +
            min
        );

    }


    function randomInt(
        min,
        max
    ){

        return Math.floor(
            Math.random() *
            (max - min + 1)
        ) + min;

    }


    /* ==========================================
       AUTO START
    ========================================== */

    document.addEventListener(
        "DOMContentLoaded",
        () => {

            const audio =
                findAudio();


            if(!audio)
                return;


            audio.addEventListener(
                "play",
                start
            );


            audio.addEventListener(
                "playing",
                start
            );


            /*
             * ถ้าเพลงถูกเปิดจากปุ่ม
             * หลังจาก DOM โหลดแล้ว
             */

            document.addEventListener(
                "click",
                () => {

                    if(
                        !audio.paused
                    ){

                        start();

                    }

                },
                {
                    passive:true
                }
            );

        }
    );


    /* ==========================================
       PUBLIC API
    ========================================== */

    window.CNTECHMusic = {

        start,

        getPower: () =>
            parseFloat(
                getComputedStyle(
                    document.documentElement
                )
                .getPropertyValue(
                    "--music-power"
                ) || 0
            )

    };


})();

  
</script>


<script>

/* =====================================================
   CNTECH BACKGROUND IMAGE ENGINE
   RANDOM 2-4 IMAGES
   RANDOM ORDER
   RANDOM UP / DOWN
===================================================== */

(() => {

    "use strict";


    /* =================================================
       CONFIG
    ================================================= */

    const CONFIG = {

        /*
         * ใส่ path รูปของคุณตรงนี้
         */

        images: [

            "assets/bg/game-01.jpg",

            "assets/bg/game-02.jpg",

            "assets/bg/game-03.jpg",

            "assets/bg/game-04.jpg"

        ],


        /*
         * จำนวนรูปที่ใช้
         * 2-4
         */

        minImages:2,

        maxImages:4,


        /*
         * เวลาเปลี่ยนรูป
         */

        duration:9000,


        /*
         * ความจาง
         * .20 = จางมาก
         * .40 = เห็นชัดขึ้น
         */

        opacityMin:.20,

        opacityMax:.40,


        /*
         * สุ่มทิศทาง
         */

        directions:[

            "slide-down",

            "slide-up",

            "slide-left",

            "slide-right"

        ]

    };


    /* =================================================
       STATE
    ================================================= */

    let selectedImages = [];

    let order = [];

    let current = 0;

    let image = null;


    /* =================================================
       RANDOM
    ================================================= */

    function randomInt(min,max){

        return Math.floor(
            Math.random() *
            (max - min + 1)
        ) + min;

    }


    function shuffle(array){

        const result =
            [...array];

        for(
            let i=result.length-1;
            i>0;
            i--
        ){

            const j =
                Math.floor(
                    Math.random() *
                    (i+1)
                );

            [
                result[i],
                result[j]
            ] =
            [
                result[j],
                result[i]
            ];

        }

        return result;

    }


    /* =================================================
       SELECT 2-4 IMAGES
    ================================================= */

    function selectImages(){

        const count =
            Math.min(

                CONFIG.images.length,

                randomInt(
                    CONFIG.minImages,
                    CONFIG.maxImages
                )

            );


        selectedImages =
            shuffle(
                CONFIG.images
            ).slice(
                0,
                count
            );

    }


    /* =================================================
       RANDOM ORDER
    ================================================= */

    function createOrder(){

        order =
            shuffle(
                selectedImages.map(
                    (_,index) =>
                        index
                )
            );

    }


    /* =================================================
       CREATE BACKGROUND
    ================================================= */

    function createBackground(){

        const layer =
            document.createElement(
                "div"
            );

        layer.className =
            "cn-bg";


        image =
            document.createElement(
                "img"
            );

        image.className =
            "cn-bg-image";


        layer.appendChild(
            image
        );


        document.body.prepend(
            layer
        );


        return layer;

    }


    /* =================================================
       SHOW IMAGE
    ================================================= */

    function showImage(){

        if(!image)
            return;


        const index =
            order[current];


        const src =
            selectedImages[index];


        /*
         * สุ่มความจาง
         */

        const opacity =
            CONFIG.opacityMin +
            Math.random() *
            (
                CONFIG.opacityMax -
                CONFIG.opacityMin
            );


        /*
         * สุ่มทิศทาง
         */

        const direction =
            CONFIG.directions[
                randomInt(
                    0,
                    CONFIG.directions.length - 1
                )
            ];


        /*
         * เริ่มจากจาง
         */

        image.style.opacity =
            "0";


        image.className =
            "cn-bg-image";


        image.classList.add(
            direction
        );


        image.src =
            src;


        /*
         * รอรูปโหลด
         */

        image.onload = () => {

            requestAnimationFrame(
                () => {

                    image.style.opacity =
                        opacity;

                }
            );

        };


        /*
         * รูปถัดไป
         */

        current++;


        if(
            current >= order.length
        ){

            current = 0;

            /*
             * สุ่ม order ใหม่
             *
             * เช่น
             * 1 2 3 4
             *
             * กลายเป็น
             * 4 3 2 1
             *
             * หรือ
             * 1 3 2 4
             */

            order =
                shuffle(
                    order
                );

        }

    }


    /* =================================================
       START
    ================================================= */

    function start(){

        if(
            !document.body
        )
            return;


        selectImages();

        createOrder();

        createBackground();

        showImage();


        setInterval(
            showImage,
            CONFIG.duration
        );

    }


    /* =================================================
       DOM READY
    ================================================= */

    if(
        document.readyState ===
        "loading"
    ){

        document.addEventListener(
            "DOMContentLoaded",
            start
        );

    }else{

        start();

    }


})();
  
</script>
  
  
</body>

    </htmlฝ