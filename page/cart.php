<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
========================================
DATABASE
========================================
*/

require_once "../config.php";
require_once "../database.php";


/*
========================================
CART SESSION
========================================
*/

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/*
========================================
ADD
========================================
*/

if (isset($_GET['add'])) {

    $id = (int)$_GET['add'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['qty']++;

    }

    header("Location: cart.php");
    exit;
}


/*
========================================
MINUS
========================================
*/

if (isset($_GET['minus'])) {

    $id = (int)$_GET['minus'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['qty']--;

        if ($_SESSION['cart'][$id]['qty'] <= 0) {

            unset($_SESSION['cart'][$id]);

        }

    }

    header("Location: cart.php");
    exit;
}


/*
========================================
REMOVE
========================================
*/

if (isset($_GET['remove'])) {

    $id = (int)$_GET['remove'];

    if (isset($_SESSION['cart'][$id])) {

        unset($_SESSION['cart'][$id]);

    }

    header("Location: cart.php");
    exit;
}


/*
========================================
CLEAR
========================================
*/

if (isset($_GET['clear'])) {

    $_SESSION['cart'] = [];

    header("Location: cart.php");
    exit;
}


/*
========================================
TOTAL
========================================
*/

$total = 0;

foreach ($_SESSION['cart'] as $item) {

    $price = (float)($item['price'] ?? 0);
    $qty   = (int)($item['qty'] ?? 0);

    $total += $price * $qty;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta
name="theme-color"
content="#ff0000">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Cart | CN TECH STORE</title>
<link rel="canonical"
href="<?= $currentURL ?>">
    
<link
rel="icon"
href="/uploads/favicon.png">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<style>

* {
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
}

html,
body {

    margin: 0;
    padding: 0;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background: #050505;
    color: #fff;

}


/* =================================
   HEADER
================================= */

.mobile-header {

    height: 64px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 18px;

    background:
        linear-gradient(
            135deg,
            #000,
            #160000
        );

    border-bottom:
        1px solid #ff2020;

    position: sticky;

    top: 0;

    z-index: 1000;

}

.logo {

    font-size: 21px;

    font-weight: 900;

    letter-spacing: .5px;

}

.logo span {

    color: #ff2020;

}

.cart-icon {

    width: 42px;
    height: 42px;

    border-radius: 50%;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #ff2020;

    font-size: 20px;

}


/* =================================
   MAIN
================================= */

.container {

    width: 100%;

    max-width: 700px;

    margin: auto;

    padding:

        18px
        15px
        130px;

}


/* =================================
   TITLE
================================= */

.page-title {

    margin-bottom: 18px;

}

.page-title h1 {

    margin: 0;

    font-size: 25px;

}

.page-title p {

    margin-top: 6px;

    color: #aaa;

    font-size: 13px;

}


/* =================================
   CART ITEM
================================= */

.cart-item {

    display: flex;

    gap: 14px;

    padding: 14px;

    margin-bottom: 12px;

    border-radius: 18px;

    background:
        linear-gradient(
            145deg,
            #151515,
            #080808
        );

    border:
        1px solid
        rgba(255,255,255,.08);

    box-shadow:
        0 8px 25px
        rgba(0,0,0,.35);

}


/* IMAGE */

.cart-image {

    width: 90px;
    height: 90px;

    flex-shrink: 0;

    border-radius: 15px;

    overflow: hidden;

    background: #fff;

}

.cart-image img {

    width: 100%;
    height: 100%;

    object-fit: cover;

}


/* INFO */

.cart-info {

    flex: 1;

    min-width: 0;

}

.cart-name {

    margin: 0 0 7px;

    font-size: 16px;

    font-weight: 800;

    line-height: 1.3;

}

.price {

    color: #ff3030;

    font-size: 17px;

    font-weight: 900;

}


/* =================================
   QTY
================================= */

.qty-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 12px;

}

.qty-box {

    display: flex;

    align-items: center;

    gap: 10px;

}

.qty-btn {

    width: 34px;
    height: 34px;

    display: flex;

    align-items: center;
    justify-content: center;

    text-decoration: none;

    color: white;

    background: #222;

    border:
        1px solid
        #444;

    border-radius: 10px;

    font-size: 20px;

    font-weight: bold;

}

.qty-btn.plus {

    background: #ff2020;

    border-color: #ff2020;

}

.qty-number {

    min-width: 25px;

    text-align: center;

    font-weight: 900;

}


/* =================================
   REMOVE
================================= */

.remove {

    color: #888;

    text-decoration: none;

    font-size: 12px;

    padding: 6px;

}

.remove:hover {

    color: #ff3030;

}


/* =================================
   SUBTOTAL
================================= */

.subtotal {

    margin-top: 8px;

    font-size: 13px;

    color: #aaa;

}

.subtotal strong {

    color: #fff;

}


/* =================================
   EMPTY
================================= */

.empty {

    text-align: center;

    padding: 60px 20px;

    border-radius: 22px;

    background: #101010;

    border:
        1px solid
        #222;

}

.empty-icon {

    font-size: 55px;

    margin-bottom: 15px;

}

.empty h2 {

    margin: 0 0 8px;

}

.empty p {

    color: #888;

}

.shop-btn {

    display: inline-block;

    margin-top: 15px;

    padding: 13px 22px;

    border-radius: 13px;

    background: #ff2020;

    color: #fff;

    text-decoration: none;

    font-weight: 800;

}


/* =================================
   STICKY CHECKOUT
================================= */

.checkout-bar {

    position: fixed;

    left: 0;
    right: 0;
    bottom: 0;

    z-index: 2000;

    padding: 12px 15px;

    background:
        rgba(5,5,5,.96);

    backdrop-filter:
        blur(15px);

    border-top:
        1px solid
        rgba(255,32,32,.35);

}

.checkout-inner {

    width: 100%;

    max-width: 700px;

    margin: auto;

}

.total-row {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 9px;

}

.total-label {

    color: #aaa;

    font-size: 13px;

}

.total-price {

    color: #fff;

    font-size: 21px;

    font-weight: 900;

}

.checkout-actions {

    display: flex;

    gap: 9px;

}

.clear-btn {

    flex: 0 0 42px;

    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    background: #222;

    color: #ff3030;

    text-decoration: none;

    font-size: 18px;

}

.checkout-btn {

    flex: 1;

    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #ff2020,
            #b00000
        );

    color: #fff;

    text-decoration: none;

    font-size: 16px;

    font-weight: 900;

    box-shadow:
        0 8px 25px
        rgba(255,0,0,.25);

}


/* =================================
   DESKTOP
================================= */

@media (min-width: 768px) {

    .container {

        padding-top: 30px;

    }

    .cart-item {

        padding: 18px;

    }

    .cart-image {

        width: 110px;
        height: 110px;

    }

}

</style>

</head>


<body>


<!-- =================================
     MOBILE HEADER
================================= -->

<header class="mobile-header">

    <div class="logo">

        CN TECH
        <span>STORE</span>

    </div>

    <div class="cart-icon">

        <i class="fa-solid fa-cart-shopping"></i>

    </div>

</header>



<main class="container">


<div class="page-title">

    <h1>Shopping Cart</h1>

    <p>
        ตรวจสอบสินค้าและจำนวนก่อนชำระเงิน
    </p>

</div>



<?php if (!empty($_SESSION['cart'])): ?>


<?php foreach ($_SESSION['cart'] as $item): ?>

<?php

$id = (int)($item['id'] ?? 0);

$name =
    htmlspecialchars(
        $item['name'] ?? 'Product'
    );

$price =
    (float)(
        $item['price'] ?? 0
    );

$qty =
    (int)(
        $item['qty'] ?? 1
    );

$subtotal =
    $price * $qty;


$image =

!empty($item['image'])

    ? "../uploads/"
        . basename(
            $item['image']
        )

    : "../uploads/no-image.png";

?>


<div class="cart-item">


    <div class="cart-image">

        <img
            src="<?=htmlspecialchars($image)?>"
            alt="<?=$name?>"
        >

    </div>



    <div class="cart-info">


        <h3 class="cart-name">

            <?=$name?>

        </h3>


        <div class="price">

            ₭<?=number_format($price,2)?>

        </div>


        <div class="subtotal">

            รวม:
            <strong>
                ₭<?=number_format($subtotal,2)?>
            </strong>

        </div>


        <div class="qty-row">


            <div class="qty-box">


                <a
                    class="qty-btn"
                    href="cart.php?minus=<?=$id?>"
                >
                    <i class="fa-solid fa-minus"></i>
                </a>


                <span class="qty-number">

                    <?=$qty?>

                </span>


                <a
                    class="qty-btn plus"
                    href="cart.php?add=<?=$id?>"
                >
                    <i class="fa-solid fa-plus"></i>
                </a>


            </div>


            <a
                class="remove"
                href="cart.php?remove=<?=$id?>"
                onclick="return confirm('ลบสินค้านี้ออกจากตะกร้า?')"
            >
                ลบ
            </a>


        </div>


    </div>


</div>


<?php endforeach; ?>


<?php else: ?>


<div class="empty">

    <div class="empty-icon">

        <i class="fa-solid fa-cart-shopping"></i>

    </div>

    <h2>

        ตะกร้าว่าง

    </h2>

    <p>

        ยังไม่มีสินค้าในตะกร้า

    </p>

    <a
        href="page/products.php"
        class="shop-btn"
    >

        เลือกซื้อสินค้า

    </a>

</div>


<?php endif; ?>


</main>



<?php if (!empty($_SESSION['cart'])): ?>


<!-- =================================
     STICKY CHECKOUT
================================= -->

<div class="checkout-bar">

    <div class="checkout-inner">


        <div class="total-row">

            <span class="total-label">

                ยอดรวมทั้งหมด

            </span>


            <span class="total-price">

                ₭<?=number_format($total,2)?>

            </span>

        </div>


        <div class="checkout-actions">


            <a
                href="cart.php?clear=1"
                class="clear-btn"
                onclick="return confirm('ล้างสินค้าทั้งหมดในตะกร้า?')"
            >

                <i class="fa-solid fa-trash"></i>

            </a>


            <a
                href="checkout.php"
                class="checkout-btn"
            >

                ดำเนินการชำระเงิน →

            </a>


        </div>


    </div>

</div>


<?php endif; ?>


</body>

</html>