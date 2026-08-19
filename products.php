<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../config.php";
require "../database.php";

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/*
====================================================
ADD TO CART
====================================================
*/

if (isset($_GET['add'])) {

    $product_id = (int)$_GET['add'];

    $stmt = $conn->prepare("
        SELECT *
        FROM products
        WHERE id = ?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param(
            "i",
            $product_id
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $product = $result->fetch_assoc();

        $stmt->close();


        if ($product) {

            $stock = (int)($product['stock'] ?? 0);

            if ($stock > 0) {

                $price = (float)$product['price'];

                $discount = (int)(
                    $product['discount'] ?? 0
                );

                $finalPrice = ($discount > 0)
                    ? $price - (
                        $price * $discount / 100
                    )
                    : $price;


                /*
                =====================================
                IMAGE
                =====================================
                */

                $image = !empty($product['image'])
                    ? $product['image']
                    : "no-image.png";


                /*
                =====================================
                CART
                =====================================
                */

                if (
                    isset(
                        $_SESSION['cart'][$product_id]
                    )
                ) {

                    $currentQty =
                        (int)(
                            $_SESSION['cart']
                            [$product_id]['qty']
                            ?? 0
                        );

                    if ($currentQty < $stock) {

                        $_SESSION['cart']
                        [$product_id]['qty']++;

                    }

                } else {

                    $_SESSION['cart']
                    [$product_id] = [

                        'id' =>
                            $product_id,

                        'name' =>
                            $product['name'],

                        'price' =>
                            $finalPrice,

                        'qty' =>
                            1,

                        'image' =>
                            $image

                    ];

                }

            }

        }

    }


    header("Location: paeg/products.php");

    exit;

}


/*
====================================================
CART COUNT
====================================================
*/

$cartCount = 0;

foreach ($_SESSION['cart'] as $cartItem) {

    $cartCount +=
        (int)($cartItem['qty'] ?? 0);

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta
name="theme-color"
content="#ff0000">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="description"
    content="CN Tech Store - Top Up Games, Mobile Top Up, Gift Cards, Computer Parts in Laos"
>

<meta
    name="keywords"
    content="CN Tech Store, Topup, Mobile Legends, Free Fire, PUBG, Game Topup, Laos"
>

<meta
    name="robots"
    content="index,follow"
>

<link rel="canonical"
href="<?= $currentURL ?>">

<title>
Products | CN TECH STORE
</title>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

<style>

* {

    box-sizing: border-box;

    -webkit-tap-highlight-color:
        transparent;

}


html,
body {

    margin: 0;

    padding: 0;

    width: 100%;

    min-height: 100%;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        #050505;

    color:
        #ffffff;

}


/*
====================================================
APP HEADER
====================================================
*/

.app-header {

    position:
        sticky;

    top:
        0;

    z-index:
        9999;

    height:
        64px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

    padding:
        0 16px;

    background:
        linear-gradient(
            135deg,
            #000000,
            #160000
        );

    border-bottom:
        1px solid
        rgba(255,32,32,.55);

    box-shadow:
        0 5px 25px
        rgba(0,0,0,.5);

}


.logo {

    font-size:
        20px;

    font-weight:
        900;

    letter-spacing:
        .5px;

}


.logo span {

    color:
        #ff2020;

}


.cart-button {

    position:
        relative;

    width:
        43px;

    height:
        43px;

    border-radius:
        50%;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    background:
        #ff2020;

    color:
        white;

    text-decoration:
        none;

    font-size:
        20px;

    box-shadow:
        0 5px 18px
        rgba(255,0,0,.3);

}


.cart-count {

    position:
        absolute;

    top:
        -3px;

    right:
        -3px;

    min-width:
        19px;

    height:
        19px;

    padding:
        0 5px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    border-radius:
        20px;

    background:
        #ffffff;

    color:
        #ff0000;

    font-size:
        10px;

    font-weight:
        900;

    border:
        2px solid
        #ff2020;

}


/*
====================================================
MAIN
====================================================
*/

.app-container {

    width:
        100%;

    max-width:
        1200px;

    margin:
        auto;

    padding:
        16px 14px 110px;

}


/*
====================================================
PAGE TITLE
====================================================
*/

.page-heading {

    padding:
        5px 2px 18px;

}


.page-heading h1 {

    margin:
        0;

    font-size:
        27px;

    font-weight:
        900;

}


.page-heading h1 span {

    color:
        #ff2020;

}


.page-heading p {

    margin:
        7px 0 0;

    color:
        #999;

    font-size:
        13px;

}


/*
====================================================
SEARCH
====================================================
*/

.search-box {

    position:
        relative;

    margin-bottom:
        18px;

}


.search-box input {

    width:
        100%;

    height:
        48px;

    border:
        1px solid
        #2a2a2a;

    border-radius:
        15px;

    outline:
        none;

    background:
        #111111;

    color:
        white;

    padding:
        0 16px 0 45px;

    font-size:
        14px;

}


.search-box input:focus {

    border-color:
        #ff2020;

}


.search-icon {

    position:
        absolute;

    left:
        16px;

    top:
        50%;

    transform:
        translateY(-50%);

    color:
        #ff2020;

}


/*
====================================================
PRODUCT GRID
====================================================
*/

.product-grid {

    display:
        grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap:
        12px;

}


@media(min-width:700px) {

    .product-grid {

        grid-template-columns:
            repeat(3, 1fr);

        gap:
            16px;

    }

}


@media(min-width:1100px) {

    .product-grid {

        grid-template-columns:
            repeat(4, 1fr);

    }

}


/*
====================================================
PRODUCT CARD
====================================================
*/

.product-card {

    position:
        relative;

    overflow:
        hidden;

    border-radius:
        18px;

    background:
        linear-gradient(
            145deg,
            #171717,
            #080808
        );

    border:
        1px solid
        rgba(255,255,255,.08);

    transition:
        .25s ease;

}


.product-card:active {

    transform:
        scale(.97);

}


.product-card:hover {

    border-color:
        rgba(255,32,32,.65);

    box-shadow:
        0 10px 30px
        rgba(255,0,0,.12);

}


.product-card.out-stock {

    opacity:
        .45;

}


/*
====================================================
IMAGE
====================================================
*/

.img-box {

    position:
        relative;

    width:
        100%;

    aspect-ratio:
        1 / 1;

    overflow:
        hidden;

    background:
        #111;

}


.img-box img {

    width:
        100%;

    height:
        100%;

    display:
        block;

    object-fit:
        cover;

    transition:
        .3s ease;

}


.product-card:hover
.img-box img {

    transform:
        scale(1.04);

}


/*
====================================================
BADGE
====================================================
*/

.badge {

    position:
        absolute;

    top:
        9px;

    left:
        9px;

    padding:
        5px 8px;

    border-radius:
        8px;

    font-size:
        9px;

    font-weight:
        900;

    z-index:
        2;

}


.badge.red {

    background:
        #ff2020;

    color:
        #fff;

}


.badge.yellow {

    background:
        #facc15;

    color:
        #000;

}


/*
====================================================
PRODUCT INFO
====================================================
*/

.product-info {

    padding:
        11px;

}


.product-name {

    margin:
        0 0 6px;

    font-size:
        14px;

    font-weight:
        800;

    line-height:
        1.35;

    display:
        -webkit-box;

    -webkit-line-clamp:
        2;

    -webkit-box-orient:
        vertical;

    overflow:
        hidden;

}


.star {

    min-height:
        18px;

    font-size:
        10px;

    color:
        #facc15;

    white-space:
        nowrap;

    overflow:
        hidden;

}


.review-count {

    color:
        #777;

}


/*
====================================================
PRICE
====================================================
*/

.price-area {

    margin-top:
        7px;

}


.price {

    color:
        #ff3030;

    font-size:
        17px;

    font-weight:
        900;

}


.old-price {

    color:
        #666;

    font-size:
        11px;

    text-decoration:
        line-through;

}


.discount {

    margin-left:
        4px;

    color:
        #ff2020;

    font-size:
        10px;

    font-weight:
        900;

}


/*
====================================================
STOCK
====================================================
*/

.stock-info {

    margin-top:
        6px;

    color:
        #777;

    font-size:
        10px;

}


/*
====================================================
ADD CART BUTTON
====================================================
*/

.add-cart {

    width:
        100%;

    margin-top:
        9px;

    height:
        38px;

    border:
        none;

    border-radius:
        11px;

    background:
        linear-gradient(
            135deg,
            #ff2020,
            #a80000
        );

    color:
        white;

    font-size:
        12px;

    font-weight:
        900;

    cursor:
        pointer;

}


.add-cart:active {

    transform:
        scale(.96);

}


.add-cart.disabled {

    background:
        #2b2b2b;

    color:
        #777;

    cursor:
        not-allowed;

}


/*
====================================================
BOTTOM CART BAR
====================================================
*/

.bottom-cart {

    position:
        fixed;

    left:
        0;

    right:
        0;

    bottom:
        0;

    z-index:
        9998;

    padding:
        10px 14px;

    background:
        rgba(5,5,5,.96);

    backdrop-filter:
        blur(15px);

    border-top:
        1px solid
        rgba(255,32,32,.4);

}


.bottom-cart-inner {

    max-width:
        700px;

    margin:
        auto;

    display:
        flex;

    align-items:
        center;

    gap:
        10px;

}


.cart-summary {

    flex:
        1;

}


.cart-summary small {

    display:
        block;

    color:
        #888;

    font-size:
        10px;

}


.cart-summary strong {

    font-size:
        15px;

    color:
        white;

}


.view-cart {

    min-width:
        145px;

    height:
        45px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    gap:
        7px;

    border-radius:
        13px;

    background:
        #ff2020;

    color:
        white;

    text-decoration:
        none;

    font-size:
        13px;

    font-weight:
        900;

}


/*
====================================================
NO PRODUCT
====================================================
*/

.no-products {

    padding:
        60px 20px;

    text-align:
        center;

    border-radius:
        20px;

    background:
        #101010;

    border:
        1px solid
        #222;

}


.no-products-icon {

    font-size:
        50px;

}


.no-products h2 {

    margin:
        12px 0 5px;

}


.no-products p {

    color:
        #777;

    font-size:
        13px;

}


/*
====================================================
MOBILE OPTIMIZATION
====================================================
*/

@media(max-width:420px) {

    .app-container {

        padding-left:
            10px;

        padding-right:
            10px;

    }

    .product-grid {

        gap:
            9px;

    }

    .product-info {

        padding:
            9px;

    }

    .product-name {

        font-size:
            13px;

    }

    .price {

        font-size:
            15px;

    }

    .add-cart {

        height:
            36px;

        font-size:
            11px;

    }

    .view-cart {

        min-width:
            130px;

    }

}

</style>

</head>


<body>


<!-- =================================================
     APP HEADER
================================================= -->

<header class="app-header">

    <div class="logo">

        CN TECH
        <span>STORE</span>

    </div>


    <a
        href="cart.php"
        class="cart-button"
        aria-label="Shopping Cart"
    >

        <i class="fa-solid fa-cart-shopping"></i>

        <?php if($cartCount > 0): ?>

            <span class="cart-count">

                <?= $cartCount ?>

            </span>

        <?php endif; ?>

    </a>

</header>



<!-- =================================================
     MAIN
================================================= -->

<main class="app-container">


    <section class="page-heading">

        <h1>

            All
            <span>Products</span>

        </h1>

        <p>

            ເລືອກສິນຄ້າທີ່ທ່ານຕ້ອງການ

        </p>

    </section>



    <!-- SEARCH -->

    <div class="search-box">

        <span class="search-icon">

            <i class="fa-solid fa-magnifying-glass"></i>

        </span>

        <input
            type="search"
            id="productSearch"
            placeholder="ค้นหาสินค้า..."
            autocomplete="off"
        >

    </div>



    <!-- PRODUCTS -->

    <section>

        <div class="product-grid"
             id="productGrid">


<?php

$sql = "
    SELECT *
    FROM products
    ORDER BY id DESC
";

$result =
    $conn->query($sql);


if (
    $result &&
    $result->num_rows > 0
):


while (
    $row =
    $result->fetch_assoc()
):


    $id =
        (int)$row['id'];


    $stock =
        (int)(
            $row['stock'] ?? 0
        );


    $price =
        (float)$row['price'];


    $discount =
        (int)(
            $row['discount'] ?? 0
        );


    $finalPrice =
        ($discount > 0)

        ? $price -
          (
              $price *
              $discount /
              100
          )

        : $price;


    $image =

        !empty($row['image'])

        ? "/admin/uploads/"
          . basename(
              $row['image']
          )

        : "/admin/uploads/no-image.png";


    $isOut =
        ($stock <= 0);


    $isLow =
        (
            $stock > 0 &&
            $stock < 2
        );


    $rating =
        (float)(
            $row['rating'] ?? 0
        );


    $reviews =
        (int)(
            $row['reviews'] ?? 0
        );


    $fullStars =
        floor($rating);


    $halfStar =
        (
            $rating -
            $fullStars
        ) >= .5;


    $productName =
        htmlspecialchars(
            $row['name']
        );


?>


<article
    class="product-card <?= $isOut ? 'out-stock' : '' ?>"
    data-name="<?= strtolower($productName) ?>"
>


    <!-- IMAGE -->

    <div class="img-box">

        <img
            src="<?= htmlspecialchars($image) ?>"
            alt="<?= $productName ?>"
            loading="lazy"
        >


        <?php if($isOut): ?>

            <div class="badge red">

                OUT OF STOCK

            </div>


        <?php elseif($isLow): ?>

            <div class="badge yellow">

                LOW STOCK

            </div>

        <?php endif; ?>


    </div>



    <!-- INFO -->

    <div class="product-info">


        <h2 class="product-name">

            <?= $productName ?>

        </h2>



        <div class="star">

            <?= str_repeat(
                "⭐",
                $fullStars
            ) ?>

            <?= $halfStar
                ? "✨"
                : ""
            ?>

            <span class="review-count">

                (<?= $reviews ?>)

            </span>

        </div>



        <!-- PRICE -->

        <div class="price-area">


        <?php if($discount > 0): ?>


            <div class="old-price">

                ₭ <?= number_format(
                    $price,
                    2
                ) ?>

            </div>


            <div class="price">

                ₭ <?= number_format(
                    $finalPrice,
                    2
                ) ?>

                <span class="discount">

                    -<?= $discount ?>%

                </span>

            </div>


        <?php else: ?>


            <div class="price">

                ₭ <?= number_format(
                    $price,
                    2
                ) ?>

            </div>


        <?php endif; ?>


        </div>



        <!-- STOCK -->

        <div class="stock-info">

            Stock:
            <?= $stock ?>

            ·

            Sold:
            <?= (int)(
                $row['sold'] ?? 0
            ) ?>

        </div>



        <!-- BUTTON -->

        <?php if(!$isOut): ?>


            <a
                href="products.php?add=<?= $id ?>"
                class="add-cart"
                style="
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    text-decoration:none;
                "
            >

                <i class="fa-solid fa-cart-plus"></i> เพิ่มลงตะกร้า

            </a>


        <?php else: ?>


            <button
                class="add-cart disabled"
                disabled
            >

                สินค้าหมด

            </button>


        <?php endif; ?>


    </div>


</article>


<?php

endwhile;

else:

?>


<div class="no-products">

    <div class="no-products-icon">

        <i class="fa-brands fa-dropbox"></i>

    </div>

    <h2>

        ไม่พบสินค้า

    </h2>

    <p>

        ขณะนี้ยังไม่มีสินค้าในระบบ

    </p>

</div>


<?php endif; ?>


        </div>

    </section>


</main>



<!-- =================================================
     STICKY CART
================================================= -->

<?php if($cartCount > 0): ?>

<div class="bottom-cart">

    <div class="bottom-cart-inner">


        <div class="cart-summary">

            <small>

                สินค้าในตะกร้า

            </small>

            <strong>

                <?= $cartCount ?>
                รายการ

            </strong>

        </div>


        <a
            href="cart.php"
            class="view-cart"
        >

            <i class="fa-solid fa-cart-shopping"></i>

            ดูตะกร้า

            

        </a>


    </div>

</div>

<?php endif; ?>



<!-- =================================================
     SEARCH SCRIPT
================================================= -->

<script>

const searchInput =
    document.getElementById(
        "productSearch"
    );


const products =
    document.querySelectorAll(
        ".product-card"
    );


searchInput.addEventListener(
    "input",
    function(){

        const keyword =
            this.value
                .trim()
                .toLowerCase();


        products.forEach(
            function(card){

                const name =
                    card.dataset.name
                    || "";


                if(
                    name.includes(
                        keyword
                    )
                ){

                    card.style.display =
                        "";

                }else{

                    card.style.display =
                        "none";

                }

            }
        );

    }
);

</script>


</body>

</html>