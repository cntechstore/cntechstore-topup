<?php

require "config.php";
require "database.php";

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);


/*
==================================================
PRODUCT ID
==================================================
*/

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    die("Invalid Product");
}


/*
==================================================
ADD TO CART
==================================================
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['add_to_cart'])
) {

    $product_id = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));

    if ($product_id !== $id) {
        die("Invalid Product");
    }


    /*
    ----------------------------------------------
    GET CURRENT PRODUCT
    ----------------------------------------------
    */

    $cartStmt = $conn->prepare("
        SELECT *
        FROM products
        WHERE id = ?
        LIMIT 1
    ");

    $cartStmt->bind_param("i", $product_id);
    $cartStmt->execute();

    $cartProduct =
        $cartStmt->get_result()->fetch_assoc();

    $cartStmt->close();


    if (!$cartProduct) {
        die("Product not found");
    }


    $cartStock =
        (int)($cartProduct['stock'] ?? 0);


    if ($cartStock <= 0) {

        header(
            "Location: view-product.php?id="
            . $product_id
            . "&error=out"
        );

        exit;
    }


    /*
    ----------------------------------------------
    PRICE
    ----------------------------------------------
    */

    $cartPrice =
        (float)$cartProduct['price'];

    $cartDiscount =
        (int)($cartProduct['discount'] ?? 0);


    $cartFinalPrice =
        $cartPrice;


    if ($cartDiscount > 0) {

        $cartFinalPrice =
            $cartPrice -
            (
                $cartPrice *
                $cartDiscount /
                100
            );

    }


    /*
    ----------------------------------------------
    CREATE SESSION CART
    ----------------------------------------------
    */

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }


    /*
    ----------------------------------------------
    EXISTING ITEM
    ----------------------------------------------
    */

    if (
        isset(
            $_SESSION['cart'][$product_id]
        )
    ) {

        $newQty =
            $_SESSION['cart'][$product_id]['qty']
            + $qty;


        /*
        ไม่ให้เกิน Stock
        */

        if ($newQty > $cartStock) {
            $newQty = $cartStock;
        }


        $_SESSION['cart'][$product_id]['qty'] =
            $newQty;

    } else {

        /*
        ------------------------------------------
        NEW CART ITEM
        ------------------------------------------
        */

        if ($qty > $cartStock) {
            $qty = $cartStock;
        }


        $_SESSION['cart'][$product_id] = [

            'id' =>
                $product_id,

            'name' =>
                $cartProduct['name'],

            'price' =>
                $cartFinalPrice,

            'original_price' =>
                $cartPrice,

            'discount' =>
                $cartDiscount,

            'image' =>
                $cartProduct['image'] ?? '',

            'qty' =>
                $qty

        ];

    }


    /*
    ----------------------------------------------
    REDIRECT
    ----------------------------------------------
    */

    header(
        "Location: view-product.php?id="
        . $product_id
        . "&added=1"
    );

    exit;
}


/*
==================================================
GET PRODUCT
==================================================
*/

$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);

$stmt->execute();

$product =
    $stmt->get_result()->fetch_assoc();

$stmt->close();


if (!$product) {

    http_response_code(404);

    die("Product not found");

}


/*
==================================================
VIEW +1
==================================================
*/

$conn->query("
    UPDATE products
    SET views = views + 1
    WHERE id = " . $id
);


/*
==================================================
PRODUCT DATA
==================================================
*/

$name =
    htmlspecialchars(
        $product['name'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );


$description =
    htmlspecialchars(
        $product['description'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );


$price =
    (float)($product['price'] ?? 0);


$stock =
    (int)($product['stock'] ?? 0);


$views =
    (int)($product['views'] ?? 0) + 1;


$sold =
    (int)($product['sold'] ?? 0);


$rating =
    (float)($product['rating'] ?? 0);


$reviews =
    (int)($product['reviews'] ?? 0);


$discount =
    (int)($product['discount'] ?? 0);


/*
==================================================
FINAL PRICE
==================================================
*/

$finalPrice =
    $price;


if ($discount > 0) {

    $finalPrice =
        $price -
        (
            $price *
            $discount /
            100
        );

}


/*
==================================================
STOCK STATUS
==================================================
*/

$out =
    ($stock <= 0);


$low =
    (
        $stock > 0 &&
        $stock <= 2
    );


/*
==================================================
IMAGE
==================================================
*/

$image =
    !empty($product['image'])
    ? "/admin/uploads/"
        . basename($product['image'])
    : "/admin/uploads/no-image.png";


/*
==================================================
CART COUNT
==================================================
*/

$cartCount = 0;


if (!empty($_SESSION['cart'])) {

    foreach (
        $_SESSION['cart']
        as $cartItem
    ) {

        $cartCount +=
            (int)($cartItem['qty'] ?? 0);

    }

}

?>

<!DOCTYPE html>

<html lang="th">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width,
    initial-scale=1.0,
    maximum-scale=1.0,
    user-scalable=no"
>

<meta
    name="theme-color"
    content="#050505"
>

<title>
<?= $name ?> | CN TECH STORE
</title>

<?php include "cdn.php"; ?>
<link rel="canonical"
href="<?= $currentURL ?>">
<style>

/*
==================================================
RESET
==================================================
*/

*{
    box-sizing:border-box;
    -webkit-tap-highlight-color:transparent;
}


html{
    scroll-behavior:smooth;
}


body{

    margin:0;

    padding:0;

    padding-bottom:110px;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:#050505;

    color:#fff;

}


/*
==================================================
APP HEADER
==================================================
*/

.app-header{

    position:sticky;

    top:0;

    z-index:1000;

    height:64px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 16px;

    background:
        rgba(5,5,5,.94);

    backdrop-filter:
        blur(18px);

    border-bottom:
        1px solid
        rgba(255,0,0,.35);

}


.header-left{

    display:flex;

    align-items:center;

    gap:12px;

}


.back-btn{

    width:42px;

    height:42px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#fff;

    text-decoration:none;

    background:#171717;

    border:
        1px solid
        #333;

}


.brand{

    font-size:18px;

    font-weight:900;

    letter-spacing:.5px;

}


.brand span{

    color:#ff2020;

}


.cart-btn{

    position:relative;

    width:42px;

    height:42px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#fff;

    text-decoration:none;

    background:#171717;

    border:
        1px solid
        #333;

}


.cart-count{

    position:absolute;

    top:-4px;

    right:-4px;

    min-width:19px;

    height:19px;

    padding:0 5px;

    border-radius:20px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:#ff2020;

    color:#fff;

    font-size:10px;

    font-weight:900;

}


/*
==================================================
MAIN
==================================================
*/

.product-page{

    width:100%;

    max-width:700px;

    margin:auto;

}


/*
==================================================
IMAGE
==================================================
*/

.product-image{

    position:relative;

    width:100%;

    background:#101010;

}


.product-image img{

    width:100%;

    height:auto;

    min-height:320px;

    max-height:520px;

    object-fit:contain;

    display:block;

}


.discount-badge{

    position:absolute;

    top:16px;

    left:16px;

    padding:8px 12px;

    border-radius:12px;

    background:#ff2020;

    color:#fff;

    font-size:13px;

    font-weight:900;

    box-shadow:
        0 8px 25px
        rgba(255,0,0,.35);

}


/*
==================================================
CONTENT
==================================================
*/

.product-content{

    padding:20px 16px 30px;

}


.product-title{

    margin:0;

    font-size:25px;

    line-height:1.25;

    font-weight:900;

}


.rating{

    display:flex;

    align-items:center;

    gap:4px;

    margin-top:12px;

}


.rating i{

    color:#ffc107;

    font-size:15px;

}


.rating-text{

    margin-left:7px;

    color:#aaa;

    font-size:13px;

}


/*
==================================================
PRICE
==================================================
*/

.price-area{

    margin-top:20px;

}


.old-price{

    color:#777;

    text-decoration:line-through;

    font-size:15px;

}


.price{

    margin-top:4px;

    font-size:31px;

    font-weight:900;

    color:#ff3030;

}


.discount-text{

    display:inline-block;

    margin-left:7px;

    padding:4px 7px;

    border-radius:7px;

    background:#ff2020;

    color:#fff;

    font-size:12px;

    vertical-align:middle;

}


/*
==================================================
STOCK
==================================================
*/

.stock-status{

    margin-top:15px;

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:8px 12px;

    border-radius:12px;

    font-size:13px;

    font-weight:800;

}


.stock-ok{

    color:#4ade80;

    background:
        rgba(34,197,94,.1);

    border:
        1px solid
        rgba(34,197,94,.3);

}


.stock-low{

    color:#facc15;

    background:
        rgba(250,204,21,.1);

    border:
        1px solid
        rgba(250,204,21,.3);

}


.stock-out{

    color:#ff4d4d;

    background:
        rgba(255,0,0,.1);

    border:
        1px solid
        rgba(255,0,0,.3);

}


/*
==================================================
STATS
==================================================
*/

.stats{

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:8px;

    margin-top:20px;

}


.stat{

    padding:12px 5px;

    text-align:center;

    border-radius:14px;

    background:#111;

    border:
        1px solid
        #242424;

}


.stat strong{

    display:block;

    font-size:14px;

}


.stat span{

    display:block;

    margin-top:4px;

    color:#777;

    font-size:10px;

}


/*
==================================================
DESCRIPTION
==================================================
*/

.section{

    margin-top:22px;

    padding-top:20px;

    border-top:
        1px solid
        #242424;

}


.section-title{

    margin:0 0 12px;

    font-size:18px;

    font-weight:900;

}


.description{

    color:#cfcfcf;

    line-height:1.8;

    font-size:14px;

    white-space:normal;

    overflow-wrap:anywhere;

}


/*
==================================================
REVIEW
==================================================
*/

.review-box{

    margin-top:20px;

    padding:16px;

    border-radius:18px;

    background:#101010;

    border:
        1px solid
        #242424;

}


.review-stars{

    display:flex;

    gap:7px;

    margin:12px 0;

}


.review-stars i{

    font-size:27px;

    color:#444;

    cursor:pointer;

}


.review-stars i.active{

    color:#ffc107;

}


.review-box textarea{

    width:100%;

    min-height:100px;

    resize:none;

    padding:13px;

    border-radius:12px;

    border:
        1px solid
        #333;

    outline:none;

    background:#080808;

    color:#fff;

    font-family:inherit;

}


.review-btn{

    width:100%;

    margin-top:10px;

    padding:13px;

    border:0;

    border-radius:12px;

    background:#ff2020;

    color:#fff;

    font-weight:900;

}


/*
==================================================
QUANTITY
==================================================
*/

.quantity-area{

    margin-top:22px;

}


.quantity-title{

    font-size:14px;

    color:#aaa;

    margin-bottom:9px;

}


.quantity{

    display:flex;

    align-items:center;

    justify-content:space-between;

    width:145px;

    height:48px;

    padding:5px;

    border-radius:15px;

    background:#111;

    border:
        1px solid
        #333;

}


.quantity button{

    width:38px;

    height:38px;

    border:0;

    border-radius:11px;

    background:#222;

    color:#fff;

    font-size:20px;

    font-weight:900;

}


.quantity span{

    font-size:16px;

    font-weight:900;

}


/*
==================================================
BOTTOM ACTION
==================================================
*/

.bottom-action{

    position:fixed;

    left:0;

    right:0;

    bottom:0;

    z-index:9999;

    padding:
        10px
        12px
        calc(10px + env(safe-area-inset-bottom));

    background:
        rgba(5,5,5,.96);

    backdrop-filter:
        blur(20px);

    border-top:
        1px solid
        #292929;

}


.bottom-inner{

    max-width:700px;

    margin:auto;

    display:grid;

    grid-template-columns:
        55px 1fr;

    gap:9px;

}


.bottom-cart{

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:15px;

    background:#171717;

    border:
        1px solid
        #333;

    color:#fff;

    text-decoration:none;

    font-size:20px;

}


.add-cart-btn{

    height:55px;

    border:0;

    border-radius:15px;

    background:
        linear-gradient(
            135deg,
            #ff3030,
            #c40000
        );

    color:#fff;

    font-size:16px;

    font-weight:900;

    box-shadow:
        0 8px 25px
        rgba(255,0,0,.25);

}


.add-cart-btn:active{

    transform:scale(.98);

}


.add-cart-btn:disabled{

    background:#333;

    color:#777;

    box-shadow:none;

}


/*
==================================================
TOAST
==================================================
*/

.toast{

    position:fixed;

    left:50%;

    bottom:82px;

    transform:
        translate(-50%,20px);

    z-index:10000;

    width:
        calc(100% - 30px);

    max-width:400px;

    padding:14px;

    text-align:center;

    border-radius:15px;

    background:#151515;

    border:
        1px solid
        #ff2020;

    color:#fff;

    opacity:0;

    pointer-events:none;

    transition:.3s;

}


.toast.show{

    opacity:1;

    transform:
        translate(-50%,0);

}


/*
==================================================
DESKTOP
==================================================
*/

@media(min-width:768px){

    .product-page{

        max-width:1100px;

    }


    .product-layout{

        display:grid;

        grid-template-columns:
            1fr 1fr;

        gap:30px;

        padding:30px 20px;

    }


    .product-image{

        border-radius:22px;

        overflow:hidden;

        height:max-content;

        position:sticky;

        top:85px;

    }


    .product-content{

        padding:10px;

    }

}

</style>

</head>


<body>


<!-- =========================================
HEADER
========================================= -->

<header class="app-header">

    <div class="header-left">

        <a
            href="page/products.php"
            class="back-btn">

            <i class="fa-solid fa-arrow-left"></i>

        </a>

        <div class="brand">

            CN TECH
            <span>STORE</span>

        </div>

    </div>


    <a
        href="page/cart.php"
        class="cart-btn">

        <i class="fa-solid fa-cart-shopping"></i>

        <?php if($cartCount > 0){ ?>

            <span class="cart-count">
                <?= $cartCount ?>
            </span>

        <?php } ?>

    </a>

</header>


<!-- =========================================
PRODUCT
========================================= -->

<main class="product-page">


<div class="product-layout">


<!-- IMAGE -->

<div class="product-image">

    <img
        src="<?= htmlspecialchars($image) ?>"
        alt="<?= $name ?>">

    <?php if($discount > 0){ ?>

        <div class="discount-badge">

            -<?= $discount ?>%

        </div>

    <?php } ?>

</div>


<!-- CONTENT -->

<div class="product-content">


<h1 class="product-title">

    <?= $name ?>

</h1>


<!-- RATING -->

<div class="rating">

<?php

$full =
    floor($rating);


$half =
    ($rating - $full) >= 0.5;


$empty =
    5 -
    $full -
    ($half ? 1 : 0);


for($i=0; $i<$full; $i++){

    echo
    '<i class="fa-solid fa-star"></i>';

}


if($half){

    echo
    '<i class="fa-solid fa-star-half-stroke"></i>';

}


for($i=0; $i<$empty; $i++){

    echo
    '<i class="fa-regular fa-star"></i>';

}

?>

<span class="rating-text">

    <?= number_format($rating,1) ?>

    ·

    <?= number_format($reviews) ?>

    รีวิว

</span>

</div>


<!-- PRICE -->

<div class="price-area">

<?php if($discount > 0){ ?>

    <div class="old-price">

        ₭ <?= number_format($price,2) ?>

    </div>

<?php } ?>


<div class="price">

    ₭ <?= number_format($finalPrice,2) ?>

    <?php if($discount > 0){ ?>

        <span class="discount-text">

            -<?= $discount ?>%

        </span>

    <?php } ?>

</div>

</div>


<!-- STOCK -->

<?php if($out){ ?>

    <div class="stock-status stock-out">

        <i class="fa-solid fa-circle-xmark"></i>

        สินค้าหมด

    </div>

<?php } elseif($low){ ?>

    <div class="stock-status stock-low">

        <i class="fa-solid fa-triangle-exclamation"></i>

        เหลือเพียง <?= $stock ?> ชิ้น

    </div>

<?php } else { ?>

    <div class="stock-status stock-ok">

        <i class="fa-solid fa-circle-check"></i>

        มีสินค้า <?= $stock ?> ชิ้น

    </div>

<?php } ?>


<!-- STATS -->

<div class="stats">

    <div class="stat">

        <strong>
            <?= number_format($sold) ?>
        </strong>

        <span>ขายแล้ว</span>

    </div>


    <div class="stat">

        <strong>
            <?= number_format($views) ?>
        </strong>

        <span>เข้าชม</span>

    </div>


    <div class="stat">

        <strong>
            #<?= $id ?>
        </strong>

        <span>SKU</span>

    </div>

</div>


<!-- DESCRIPTION -->

<div class="section">

    <h2 class="section-title">

        รายละเอียดสินค้า

    </h2>


    <div class="description">

        <?= nl2br($description) ?>

    </div>

</div>


<!-- REVIEW -->

<div class="section">

<div class="review-box">

    <h3 class="section-title">

        <i class="fa-solid fa-star"
           style="color:#ffc107"></i>

        รีวิวสินค้า

    </h3>


    <form
        action="review_submit.php"
        method="POST">

        <input
            type="hidden"
            name="product_id"
            value="<?= $id ?>">


        <input
            type="hidden"
            id="rating"
            name="rating"
            value="5">


        <div class="review-stars">

<?php for($i=1;$i<=5;$i++){ ?>

    <i
        class="fa-solid fa-star active"
        data-rate="<?= $i ?>">
    </i>

<?php } ?>

        </div>


        <textarea
            name="comment"
            placeholder="เขียนรีวิวสินค้า..."
            required></textarea>


        <button
            type="submit"
            class="review-btn">

            <i class="fa-solid fa-paper-plane"></i>

            ส่งรีวิว

        </button>

    </form>

</div>

</div>


</div>

</div>

</main>


<!-- =========================================
TOAST
========================================= -->

<?php if(isset($_GET['added'])){ ?>

<div
    class="toast show"
    id="toast">

    <i
        class="fa-solid fa-circle-check"
        style="color:#4ade80">
    </i>

    เพิ่มสินค้าลงตะกร้าแล้ว

</div>

<script>

setTimeout(function(){

    const toast =
        document.getElementById("toast");

    if(toast){

        toast.classList.remove("show");

    }

},2500);

</script>

<?php } ?>


<?php if(isset($_GET['error']) && $_GET['error']==='out'){ ?>

<div
    class="toast show"
    id="toast">

    <i
        class="fa-solid fa-circle-xmark"
        style="color:#ff3030">
    </i>

    สินค้าหมด

</div>

<script>

setTimeout(function(){

    const toast =
        document.getElementById("toast");

    if(toast){

        toast.classList.remove("show");

    }

},2500);

</script>

<?php } ?>


<!-- =========================================
STICKY BOTTOM
========================================= -->

<div class="bottom-action">

    <div class="bottom-inner">


        <a
            href="page/cart.php"
            class="bottom-cart">

            <i class="fa-solid fa-cart-shopping"></i>

        </a>


        <?php if(!$out){ ?>

        <form
            method="POST"
            style="margin:0">

            <input
                type="hidden"
                name="product_id"
                value="<?= $id ?>">


            <input
                type="hidden"
                name="qty"
                id="qtyInput"
                value="1">


            <input
                type="hidden"
                name="add_to_cart"
                value="1">


            <button
                type="submit"
                class="add-cart-btn">

                <i class="fa-solid fa-cart-plus"></i>

                เพิ่มลงตะกร้า

            </button>

        </form>

        <?php } else { ?>

        <button
            class="add-cart-btn"
            disabled>

            <i class="fa-solid fa-ban"></i>

            สินค้าหมด

        </button>

        <?php } ?>


    </div>

</div>


<script>

/*
==================================================
REVIEW STAR
==================================================
*/

document
.querySelectorAll(
    ".review-stars i"
)
.forEach(function(star){

    star.addEventListener(
        "click",
        function(){

            const rate =
                parseInt(
                    this.dataset.rate
                );


            document.getElementById(
                "rating"
            ).value = rate;


            document
            .querySelectorAll(
                ".review-stars i"
            )
            .forEach(
                function(item,index){

                    if(index < rate){

                        item.classList.add(
                            "active"
                        );

                    }else{

                        item.classList.remove(
                            "active"
                        );

                    }

                }
            );

        }
    );

});


/*
==================================================
AUTO HIDE TOAST
==================================================
*/

setTimeout(function(){

    const toast =
        document.getElementById("toast");

    if(toast){

        toast.classList.remove(
            "show"
        );

    }

},3000);

</script>


</body>

</html>