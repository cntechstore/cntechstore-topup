<?php

require "../config.php";
require "../database.php";


if(session_status() === PHP_SESSION_NONE){
    session_start();
}



$cart_count = 0;


if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){

    $cart_count = count($_SESSION['cart']);

}


?>


<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#000">

<title>
Game Top-up | CNTECH STORE
</title>


<meta name="description"
content="CN Tech Store Game Top-up Platform">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="canonical"
href="<?= $currentURL ?>">
    
    <link rel="icon"
href="../uploads/favicon.png">

<style>
/* =====================================================
   CNTECH STORE
   CINEMATIC FANTASY / FIBER ENERGY UI
   Transparent Glass + Red Magic + Fast Animation
===================================================== */

*{
    box-sizing:border-box;
}

:root{
    --red:#ff2020;
    --red2:#ff4b35;
    --orange:#ff9d3b;
    --gold:#ffd166;
    --white:#fff;

    --glass:rgba(255,255,255,.035);
    --glass2:rgba(255,255,255,.065);

    --line:rgba(255,255,255,.10);

    --shadow:
        0 15px 45px rgba(0,0,0,.65),
        0 0 30px rgba(255,32,32,.12);
}

html{
    scroll-behavior:smooth;
}

body{
    margin:0;
    min-height:100vh;

    background:
        radial-gradient(
            circle at 50% -10%,
            rgba(255,32,32,.22),
            transparent 38%
        ),
        radial-gradient(
            circle at 0% 50%,
            rgba(255,0,0,.08),
            transparent 30%
        ),
        radial-gradient(
            circle at 100% 80%,
            rgba(255,90,30,.06),
            transparent 30%
        ),
        #030303;

    color:#fff;

    font-family:
        Arial,
        sans-serif;

    overflow-x:hidden;

    padding-bottom:95px;
}


/* =====================================================
   CINEMATIC LIGHT LAYERS
===================================================== */

body::before{
    content:"";

    position:fixed;
    inset:0;

    pointer-events:none;

    z-index:-1;

    background:
        repeating-linear-gradient(
            115deg,
            transparent 0px,
            transparent 90px,
            rgba(255,255,255,.018) 91px,
            transparent 93px
        );

    animation:
        fiberMove 2.8s linear infinite;
}

body::after{
    content:"";

    position:fixed;
    width:420px;
    height:420px;

    left:50%;
    top:20%;

    transform:
        translate(-50%,-50%);

    pointer-events:none;

    border-radius:50%;

    background:
        radial-gradient(
            circle,
            rgba(255,32,32,.12),
            transparent 70%
        );

    filter:blur(25px);

    animation:
        cinematicGlow 1.8s ease-in-out infinite alternate;

    z-index:-1;
}

@keyframes fiberMove{

    from{
        transform:translate3d(-80px,0,0);
    }

    to{
        transform:translate3d(80px,0,0);
    }
}

@keyframes cinematicGlow{

    from{
        opacity:.45;
        transform:
            translate(-50%,-50%)
            scale(.8);
    }

    to{
        opacity:1;
        transform:
            translate(-50%,-50%)
            scale(1.25);
    }
}


/* =====================================================
   HEADER
===================================================== */

.app-header{

    height:70px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 18px;

    position:sticky;

    top:0;

    z-index:9999;

    background:
        rgba(0,0,0,.58);

    backdrop-filter:
        blur(20px);

    -webkit-backdrop-filter:
        blur(20px);

    border-bottom:
        1px solid rgba(255,32,32,.35);

    box-shadow:
        0 8px 35px rgba(0,0,0,.6);

    overflow:hidden;
}

.app-header::after{

    content:"";

    position:absolute;

    left:-100%;
    bottom:0;

    width:100%;
    height:2px;

    background:
        linear-gradient(
            90deg,
            transparent,
            #ff2020,
            #ffd166,
            #ff2020,
            transparent
        );

    animation:
        headerLaser 1.2s linear infinite;
}

@keyframes headerLaser{

    to{
        left:100%;
    }
}

.logo{

    font-size:22px;

    font-weight:1000;

    letter-spacing:1px;

    text-shadow:
        0 0 12px rgba(255,32,32,.45);
}

.logo span{
    color:var(--red);

    text-shadow:
        0 0 15px rgba(255,32,32,.8);
}

.header-btn{

    width:46px;
    height:46px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:50%;

    color:#fff;

    background:
        rgba(255,32,32,.12);

    border:
        1px solid rgba(255,32,32,.65);

    box-shadow:
        inset 0 0 15px rgba(255,32,32,.12),
        0 0 18px rgba(255,32,32,.25);

    transition:.18s;

    position:relative;

    overflow:hidden;
}

.header-btn:hover{

    transform:
        scale(1.08)
        rotate(-4deg);

    box-shadow:
        0 0 30px rgba(255,32,32,.65);
}


/* =====================================================
   CONTAINER
===================================================== */

.container{

    width:100%;

    max-width:900px;

    margin:auto;

    padding:16px;
}


/* =====================================================
   HERO
===================================================== */

.hero{

    position:relative;

    overflow:hidden;

    min-height:180px;

    padding:28px;

    border-radius:28px;

    background:
        linear-gradient(
            135deg,
            rgba(255,20,20,.20),
            rgba(255,255,255,.035),
            rgba(0,0,0,.28)
        );

    backdrop-filter:
        blur(22px);

    -webkit-backdrop-filter:
        blur(22px);

    border:
        1px solid rgba(255,255,255,.12);

    box-shadow:
        var(--shadow);

    isolation:isolate;
}


/* Fiber */

.hero::before{

    content:"";

    position:absolute;

    width:180%;
    height:2px;

    left:-40%;
    top:50%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,32,32,.8),
            rgba(255,209,102,.9),
            rgba(255,32,32,.8),
            transparent
        );

    filter:
        blur(2px);

    transform:
        rotate(-14deg);

    animation:
        fiberBeam .75s linear infinite;

    opacity:.8;
}

@keyframes fiberBeam{

    from{
        transform:
            translateX(-35%)
            rotate(-14deg);
    }

    to{
        transform:
            translateX(35%)
            rotate(-14deg);
    }
}

.hero h1{

    position:relative;

    z-index:2;

    margin:0;

    font-size:
        clamp(24px,6vw,38px);

    font-weight:1000;

    letter-spacing:-1px;

    text-shadow:
        0 0 15px rgba(255,32,32,.35);
}

.hero p{

    position:relative;

    z-index:2;

    color:#cfcfcf;

    line-height:1.7;

}


/* =====================================================
   SECTION
===================================================== */

.section{

    margin-top:25px;

    position:relative;
}

.section-title{

    position:relative;

    display:flex;

    align-items:center;

    gap:9px;

    margin-bottom:15px;

    font-size:20px;

    font-weight:900;
}

.section-title i{

    color:#ff2020;

    filter:
        drop-shadow(
            0 0 8px
            rgba(255,32,32,.8)
        );

    animation:
        iconPulse .7s ease-in-out infinite alternate;
}

@keyframes iconPulse{

    from{
        transform:scale(1);
    }

    to{
        transform:scale(1.15);
    }
}


/* =====================================================
   GRID
===================================================== */

.grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:15px;
}


/* =====================================================
   GAME CARD
===================================================== */

.card{

    position:relative;

    overflow:hidden;

    min-width:0;

    padding:10px;

    border-radius:22px;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.075),
            rgba(255,255,255,.018)
        );

    border:
        1px solid rgba(255,255,255,.10);

    backdrop-filter:
        blur(18px);

    -webkit-backdrop-filter:
        blur(18px);

    box-shadow:
        0 15px 35px rgba(0,0,0,.45);

    transition:
        transform .18s ease,
        border-color .18s ease,
        box-shadow .18s ease;

    isolation:isolate;
}


/* =====================================================
   FIBER PARTICLES
===================================================== */

.card::before{

    content:"";

    position:absolute;

    width:7px;
    height:70px;

    right:8px;
    top:15px;

    background:
        linear-gradient(
            transparent,
            #ff2020,
            transparent
        );

    filter:
        blur(3px);

    transform:
        rotate(35deg);

    opacity:.65;

    animation:
        fiberDrop .65s linear infinite;
}

.card::after{

    content:"";

    position:absolute;

    width:5px;
    height:35px;

    left:5px;
    bottom:20px;

    background:
        linear-gradient(
            transparent,
            #ffd166,
            transparent
        );

    filter:
        blur(2px);

    transform:
        rotate(-35deg);

    animation:
        fiberDrop2 .8s linear infinite;
}

@keyframes fiberDrop{

    0%{
        transform:
            translateY(-80px)
            rotate(35deg);

        opacity:0;
    }

    35%{
        opacity:1;
    }

    100%{
        transform:
            translateY(160px)
            rotate(35deg);

        opacity:0;
    }
}

@keyframes fiberDrop2{

    0%{
        transform:
            translateY(70px)
            rotate(-35deg);

        opacity:0;
    }

    40%{
        opacity:1;
    }

    100%{
        transform:
            translateY(-150px)
            rotate(-35deg);

        opacity:0;
    }
}

.card:hover{

    transform:
        translateY(-7px)
        scale(1.015);

    border-color:
        rgba(255,32,32,.65);

    box-shadow:
        0 20px 45px rgba(0,0,0,.65),
        0 0 35px rgba(255,32,32,.22);
}


/* =====================================================
   IMAGE 100x100
===================================================== */

.card img{

    display:block;

    width:100px;
    height:100px;

    margin:auto;

    object-fit:cover;

    border-radius:22px;

    border:
        1px solid rgba(255,255,255,.18);

    box-shadow:
        0 0 0 1px rgba(255,32,32,.15),
        0 0 20px rgba(255,32,32,.18);

    transition:
        transform .2s ease,
        filter .2s ease;
}

.card:hover img{

    transform:
        scale(1.08)
        rotate(1deg);

    filter:
        saturate(1.25)
        brightness(1.12);

    box-shadow:
        0 0 30px rgba(255,32,32,.5);
}

.card h3,
.card h4{

    margin:
        11px 0 7px;

    text-align:center;

    font-size:15px;

    font-weight:900;

    line-height:1.4;
}


/* =====================================================
   SMALL IMAGE 50x50
===================================================== */

.img-50,
.card .img-50{

    width:50px !important;
    height:50px !important;

    border-radius:14px;

    object-fit:cover;

    box-shadow:
        0 0 15px rgba(255,32,32,.3);
}


/* =====================================================
   GAME ITEM
===================================================== */

.game-item{

    position:relative;

    display:block;

    text-decoration:none;

    color:#fff;
}


/* =====================================================
   DISABLED
===================================================== */

.game-disabled{

    filter:
        grayscale(.8);

    opacity:.58;
}


/* =====================================================
   GAME IMAGE
===================================================== */

.game-image{

    position:relative;

    display:flex;

    align-items:center;

    justify-content:center;

    min-height:115px;

    overflow:hidden;
}


/* =====================================================
   MAGIC OVERLAY
===================================================== */

.game-overlay{

    position:absolute;

    inset:0;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    text-align:center;

    border-radius:20px;

    background:
        radial-gradient(
            circle,
            rgba(0,0,0,.35),
            rgba(0,0,0,.85)
        );

    backdrop-filter:
        blur(3px);
}

.game-overlay i{

    font-size:30px;

    color:#ffd166;

    filter:
        drop-shadow(
            0 0 12px
            rgba(255,209,102,.8)
        );

    animation:
        warningFloat .55s ease-in-out infinite alternate;
}

@keyframes warningFloat{

    from{
        transform:
            translateY(0)
            rotate(-5deg);
    }

    to{
        transform:
            translateY(-5px)
            rotate(5deg);
    }
}

.game-overlay h3{

    margin-top:8px;

    color:#fff;

    text-shadow:
        0 0 10px #000;
}


/* =====================================================
   VIEWS
===================================================== */

.views{

    font-size:12px;

    color:#999;

    text-align:center;
}

.views i{

    color:#ff2020;

    margin-right:3px;

    filter:
        drop-shadow(
            0 0 5px
            rgba(255,32,32,.8)
        );
}


/* =====================================================
   BUTTON
===================================================== */

.btn-disabled{

    width:100%;

    margin-top:10px;

    padding:11px;

    border:
        1px solid rgba(255,255,255,.08);

    border-radius:14px;

    background:
        rgba(255,255,255,.045);

    color:#aaa;

    font-weight:800;
}


/* =====================================================
   FANTASY BUTTON
===================================================== */

.card button,
.game-button,
.btn-primary{

    position:relative;

    width:100%;

    min-height:45px;

    border:0;

    border-radius:14px;

    overflow:hidden;

    cursor:pointer;

    color:#fff;

    font-weight:900;

    background:
        linear-gradient(
            110deg,
            #760000,
            #ff2020,
            #ff6935,
            #a00000
        );

    background-size:
        300% 100%;

    box-shadow:
        0 8px 20px rgba(255,32,32,.25),
        inset 0 1px rgba(255,255,255,.22);

    animation:
        buttonEnergy 1.1s linear infinite;

    transition:
        transform .12s,
        box-shadow .12s;
}

@keyframes buttonEnergy{

    0%{
        background-position:0% 50%;
    }

    100%{
        background-position:300% 50%;
    }
}

.card button::before,
.game-button::before,
.btn-primary::before{

    content:"";

    position:absolute;

    width:35%;
    height:180%;

    top:-40%;
    left:-50%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.65),
            transparent
        );

    transform:
        rotate(20deg);

    animation:
        buttonFlash .8s linear infinite;
}

@keyframes buttonFlash{

    from{
        left:-50%;
    }

    to{
        left:120%;
    }
}

.card button:active,
.game-button:active,
.btn-primary:active{

    transform:
        scale(.94);

    box-shadow:
        0 0 35px rgba(255,32,32,.8);
}


/* =====================================================
   BOTTOM NAV
===================================================== */

.bottom-nav{

    position:fixed;

    left:0;
    right:0;
    bottom:0;

    height:75px;

    display:flex;

    justify-content:
        space-around;

    align-items:center;

    z-index:9999;

    background:
        rgba(3,3,3,.72);

    backdrop-filter:
        blur(22px);

    -webkit-backdrop-filter:
        blur(22px);

    border-top:
        1px solid rgba(255,32,32,.35);

    box-shadow:
        0 -10px 40px rgba(0,0,0,.7);
}

.bottom-nav a{

    position:relative;

    min-width:60px;

    color:#999;

    text-decoration:none;

    font-size:11px;

    text-align:center;

    transition:.15s;
}

.bottom-nav i{

    display:block;

    margin-bottom:5px;

    font-size:22px;

    transition:.15s;
}

.bottom-nav .active{

    color:#ff2020;

    text-shadow:
        0 0 12px rgba(255,32,32,.8);
}

.bottom-nav .active i{

    transform:
        translateY(-3px)
        scale(1.1);
}


/* =====================================================
   CART
===================================================== */

.cart-badge{

    position:absolute;

    min-width:20px;

    height:20px;

    display:flex;

    align-items:center;
    justify-content:center;

    background:#ff2020;

    color:#fff;

    font-size:10px;

    font-weight:900;

    border:
        2px solid #050505;

    border-radius:50%;

    margin-left:10px;

    margin-top:-30px;

    box-shadow:
        0 0 15px rgba(255,32,32,.8);

    animation:
        badgePulse .55s infinite alternate;
}

@keyframes badgePulse{

    from{
        transform:scale(1);
    }

    to{
        transform:scale(1.16);
    }
}


/* =====================================================
   EMPTY
===================================================== */

.empty-box{

    grid-column:
        1 / -1;

    min-height:150px;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    gap:10px;

    border-radius:22px;

    background:
        rgba(255,255,255,.035);

    border:
        1px solid rgba(255,255,255,.08);

    color:#aaa;

    box-shadow:
        inset 0 0 30px rgba(255,255,255,.015);
}

.empty-box i{

    font-size:42px;

    color:#ff2020;

    filter:
        drop-shadow(
            0 0 15px
            rgba(255,32,32,.7)
        );
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:450px){

    .container{
        padding:12px;
    }

    .hero{
        padding:22px;
        border-radius:24px;
    }

    .hero h1{
        font-size:24px;
    }

    .grid{
        grid-template-columns:
            repeat(2,1fr);

        gap:10px;
    }

    .card{
        padding:8px;
        border-radius:18px;
    }

    .card img{

        width:100px;
        height:100px;

        border-radius:18px;
    }

    .card h3,
    .card h4{
        font-size:13px;
    }

    .bottom-nav{
        height:70px;
    }
}


/* =====================================================
   REDUCED MOTION
===================================================== */

@media(prefers-reduced-motion:reduce){

    *,
    *::before,
    *::after{

        animation-duration:
            .001ms !important;

        animation-iteration-count:
            1 !important;

        scroll-behavior:
            auto !important;
    }
}


  /* =====================================================
   CNTECH STORE
   VOUCHER & GAME CARD
   CINEMATIC FANTASY / MAGIC FIBER
===================================================== */

.section{
    position:relative;
    margin-top:30px;
}


/* =====================================================
   TITLE
===================================================== */

.section-title{
    position:relative;

    display:flex;
    align-items:center;
    gap:10px;

    margin-bottom:18px;

    padding:10px 4px;

    color:#fff;

    font-size:20px;
    font-weight:900;
    letter-spacing:.3px;

    text-shadow:
        0 0 12px rgba(255,32,32,.35);

    overflow:hidden;
}

.section-title::after{
    content:"";

    position:absolute;

    left:0;
    bottom:0;

    width:100%;
    height:1px;

    background:
        linear-gradient(
            90deg,
            #ff2020,
            rgba(255,32,32,.4),
            transparent
        );

    box-shadow:
        0 0 12px rgba(255,32,32,.7);
}

.section-title i{

    color:#ff2020;

    filter:
        drop-shadow(
            0 0 8px
            rgba(255,32,32,.9)
        );

    animation:
        voucherIcon .45s
        ease-in-out
        infinite alternate;
}

@keyframes voucherIcon{

    from{
        transform:
            scale(1)
            rotate(-3deg);
    }

    to{
        transform:
            scale(1.15)
            rotate(3deg);
    }
}


/* =====================================================
   VOUCHER GRID
===================================================== */

.section .grid{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:14px;

    position:relative;
}


/* =====================================================
   VOUCHER CARD
===================================================== */

.section .grid > *{

    position:relative;

    overflow:hidden;

    min-width:0;

    padding:10px;

    border-radius:20px;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.075),
            rgba(255,255,255,.018)
        );

    border:
        1px solid
        rgba(255,255,255,.10);

    backdrop-filter:
        blur(18px);

    -webkit-backdrop-filter:
        blur(18px);

    box-shadow:
        0 12px 35px
        rgba(0,0,0,.55),

        inset 0 1px
        rgba(255,255,255,.05);

    transition:
        transform .16s ease,
        border-color .16s ease,
        box-shadow .16s ease;

    isolation:isolate;
}


/* =====================================================
   FIBER LIGHT
===================================================== */

.section .grid > *::before{

    content:"";

    position:absolute;

    width:5px;
    height:90px;

    top:-100px;
    right:12px;

    background:
        linear-gradient(
            transparent,
            #ff2020,
            #ffd166,
            transparent
        );

    filter:
        blur(2px);

    transform:
        rotate(35deg);

    opacity:.8;

    animation:
        voucherFiber .65s
        linear
        infinite;
}

@keyframes voucherFiber{

    0%{
        transform:
            translateY(0)
            rotate(35deg);

        opacity:0;
    }

    25%{
        opacity:1;
    }

    100%{
        transform:
            translateY(260px)
            rotate(35deg);

        opacity:0;
    }
}


/* =====================================================
   SECOND FIBER
===================================================== */

.section .grid > *::after{

    content:"";

    position:absolute;

    width:3px;
    height:50px;

    left:8px;
    bottom:-60px;

    background:
        linear-gradient(
            transparent,
            #ff3b30,
            #ff9d3b,
            transparent
        );

    filter:
        blur(1px);

    transform:
        rotate(-35deg);

    animation:
        voucherFiber2 .8s
        linear
        infinite;
}

@keyframes voucherFiber2{

    0%{
        transform:
            translateY(0)
            rotate(-35deg);

        opacity:0;
    }

    30%{
        opacity:1;
    }

    100%{
        transform:
            translateY(-220px)
            rotate(-35deg);

        opacity:0;
    }
}


/* =====================================================
   HOVER
===================================================== */

.section .grid > *:hover{

    transform:
        translateY(-6px)
        scale(1.015);

    border-color:
        rgba(255,32,32,.65);

    box-shadow:

        0 20px 45px
        rgba(0,0,0,.7),

        0 0 30px
        rgba(255,32,32,.22),

        inset 0 0 25px
        rgba(255,32,32,.04);
}


/* =====================================================
   VOUCHER IMAGE
===================================================== */

.section .grid > * img{

    display:block;

    width:100px;
    height:100px;

    margin:auto;

    object-fit:cover;

    border-radius:18px;

    border:
        1px solid
        rgba(255,255,255,.16);

    box-shadow:

        0 0 0 1px
        rgba(255,32,32,.12),

        0 0 20px
        rgba(255,32,32,.18);

    transition:
        transform .18s ease,
        filter .18s ease,
        box-shadow .18s ease;
}

.section .grid > *:hover img{

    transform:
        scale(1.08)
        rotate(-1deg);

    filter:
        brightness(1.15)
        saturate(1.3);

    box-shadow:

        0 0 30px
        rgba(255,32,32,.55);
}


/* =====================================================
   VOUCHER NAME
===================================================== */

.section .grid > * h3,
.section .grid > * h4{

    margin:
        10px 0 7px;

    color:#fff;

    font-size:14px;

    font-weight:900;

    line-height:1.4;

    text-align:center;

    text-shadow:
        0 0 8px
        rgba(255,255,255,.15);
}


/* =====================================================
   PRICE
===================================================== */

.section .grid > * .price{

    color:#ffd166;

    font-size:18px;

    font-weight:1000;

    text-align:center;

    text-shadow:
        0 0 10px
        rgba(255,209,102,.3);
}


/* =====================================================
   BUY BUTTON
===================================================== */

.section .grid > * button{

    position:relative;

    width:100%;

    min-height:43px;

    margin-top:10px;

    overflow:hidden;

    border:0;

    border-radius:13px;

    color:#fff;

    font-weight:900;

    cursor:pointer;

    background:

        linear-gradient(
            110deg,
            #690000,
            #e50914,
            #ff4b35,
            #8b0000
        );

    background-size:
        300% 100%;

    box-shadow:

        0 7px 20px
        rgba(255,32,32,.25),

        inset 0 1px
        rgba(255,255,255,.25);

    animation:
        voucherButton 1s
        linear
        infinite;

    transition:
        transform .12s,
        box-shadow .12s;
}

@keyframes voucherButton{

    from{
        background-position:0% 50%;
    }

    to{
        background-position:300% 50%;
    }
}


/* BUTTON LIGHT */

.section .grid > * button::before{

    content:"";

    position:absolute;

    top:-50%;
    left:-70%;

    width:35%;
    height:200%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.7),
            transparent
        );

    transform:
        rotate(20deg);

    animation:
        voucherButtonLight .75s
        linear
        infinite;
}

@keyframes voucherButtonLight{

    from{
        left:-70%;
    }

    to{
        left:130%;
    }
}

.section .grid > * button:active{

    transform:
        scale(.93);

    box-shadow:
        0 0 35px
        rgba(255,32,32,.8);
}


/* =====================================================
   VOUCHER BADGE
===================================================== */

.section .grid > * .badge,
.section .grid > * .discount-badge{

    display:inline-flex;

    align-items:center;
    justify-content:center;

    padding:
        4px 8px;

    border-radius:999px;

    background:
        linear-gradient(
            135deg,
            #ff2020,
            #8b0000
        );

    color:#fff;

    font-size:10px;

    font-weight:900;

    box-shadow:
        0 0 12px
        rgba(255,32,32,.45);

    animation:
        voucherBadge .55s
        ease-in-out
        infinite alternate;
}

@keyframes voucherBadge{

    from{
        transform:scale(1);
    }

    to{
        transform:scale(1.06);
    }
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:450px){

    .section .grid{

        grid-template-columns:
            repeat(2,1fr);

        gap:9px;
    }

    .section .grid > *{

        padding:8px;

        border-radius:17px;
    }

    .section .grid > * img{

        width:100px;
        height:100px;

        border-radius:16px;
    }

    .section .grid > * h3,
    .section .grid > * h4{

        font-size:12px;
    }

    .section .grid > * .price{

        font-size:16px;
    }

    .section .grid > * button{

        min-height:40px;

        font-size:12px;
    }
  }
  
</style>


</head>



<body>




<header class="app-header">


<div class="logo">

CNTECH

<span>

STORE

</span>


</div>


<div class="header-btn">

<i class="fa-solid fa-gamepad"></i>

</div>


</header>





<main class="container">





<section class="hero">


<h1>

<i class="fa-solid fa-bolt"></i>

Game Top-up

</h1>


<p>

Mobile Game • Voucher • Digital Product

</p>


</section>






<section class="section">


<div class="section-title">

<i class="fa-solid fa-clock-rotate-left"></i>

Recently Played

</div>


<div class="grid">


<?php include "../game/game_recent.php"; ?>


</div>


</section>






<section class="section">


<div class="section-title">


<i class="fa-solid fa-fire"></i>

Popular Games


</div>



<div class="grid">


<?php include "../game/game_popular.php"; ?>


</div>


</section>






<section class="section">


<div class="section-title">


<i class="fa-solid fa-ticket"></i>

Voucher & Game Card


</div>


<div class="grid">


<?php include "../game/voucher_top-up.php"; ?>


</div>


</section>



</main>







<nav class="bottom-nav">


<a href="/">

<i class="fa-solid fa-house"></i>

Home

</a>



<a href="/game/game_topup.php"
class="active">


<i class="fa-solid fa-gamepad"></i>

Games

</a>




<a href="/page/cart.php">


<i class="fa-solid fa-cart-shopping"></i>


<span class="cart-badge">

<?=$cart_count?>

</span>


Cart

</a>



<a href="/page/account.php">


<i class="fa-solid fa-user"></i>


Account

</a>



</nav>


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


</body>

</html>