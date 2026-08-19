<?php

session_start();

require "../database.php";

error_reporting(0);
ini_set('display_errors', '0');

/*
|--------------------------------------------------------------------------
| CNTECH STORE
| PAYMENT2 - ORDER REVIEW
|--------------------------------------------------------------------------
*/

$order_id = trim($_GET['order_id'] ?? '');

if ($order_id === '') {
    exit('Missing order ID');
}

/*
|--------------------------------------------------------------------------
| LOAD ORDER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM game_orders
    WHERE order_id=?
    LIMIT 1
");

if (!$stmt) {
    exit('Database error');
}

$stmt->bind_param("s", $order_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$order) {
    exit('Order not found');
}

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| PRODUCT
|--------------------------------------------------------------------------
*/

$product_id = (int)($order['product'] ?? 0);

$product_name = 'Game Product';
$product_image = '';

if ($product_id > 0) {

    $stmt = $conn->prepare("
        SELECT *
        FROM game_products
        WHERE id=?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param(
            "i",
            $product_id
        );

        $stmt->execute();

        $product = $stmt
            ->get_result()
            ->fetch_assoc();

        $stmt->close();

        if ($product) {

            $product_name =
                $product['title']
                ?? $product['name']
                ?? 'Game Product';

            $product_image =
                $product['image']
                ?? $product['image_url']
                ?? '';
        }
    }
}

/*
|--------------------------------------------------------------------------
| PRICE
|--------------------------------------------------------------------------
*/

$price =
    (float)(
        $order['price']
        ?? $order['amount']
        ?? 0
    );

$discount =
    (float)(
        $order['discount']
        ?? 0
    );

$total =
    $price - $discount;

if ($total < 0) {
    $total = 0;
}

/*
|--------------------------------------------------------------------------
| GAME DATA
|--------------------------------------------------------------------------
*/

$uid =
    trim(
        $order['uid'] ?? ''
    );

$open_id =
    trim(
        $order['open_id'] ?? ''
    );

$server =
    trim(
        $order['server'] ?? ''
    );

$order_status =
    strtolower(
        $order['payment_status']
        ?? 'pending'
    );

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1,maximum-scale=1"
>

<title>
<?= e($product_name) ?> - CNTECH STORE
</title>

<style>

/*
|--------------------------------------------------------------------------
| RESET
|--------------------------------------------------------------------------
*/

*{
    box-sizing:border-box;
}

html,
body{
    margin:0;
    padding:0;
}

body{

    background:
        radial-gradient(
            circle at top,
            #260606 0%,
            #090909 42%,
            #050505 100%
        );

    color:#fff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    min-height:100vh;
}

/*
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
*/

.container{

    width:100%;
    max-width:560px;

    margin:auto;

    padding:
        18px
        16px
        40px;
}

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.header{

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:
        8px
        2px
        22px;
}

.logo{

    font-size:22px;

    font-weight:900;

    letter-spacing:.5px;
}

.logo span{

    color:#ff2020;
}

.secure{

    font-size:11px;

    color:#777;

    background:#111;

    border:
        1px solid
        #252525;

    padding:
        7px
        10px;

    border-radius:20px;
}

/*
|--------------------------------------------------------------------------
| PROGRESS
|--------------------------------------------------------------------------
*/

.progress{

    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:18px;
}

.step{

    flex:1;

    height:4px;

    border-radius:10px;

    background:#252525;
}

.step.active{

    background:#e51b23;
}

/*
|--------------------------------------------------------------------------
| TITLE
|--------------------------------------------------------------------------
*/

.page-title{

    margin-bottom:18px;
}

.page-title h1{

    margin:0;

    font-size:25px;

    font-weight:900;
}

.page-title p{

    margin:
        7px
        0
        0;

    color:#777;

    font-size:13px;
}

/*
|--------------------------------------------------------------------------
| PRODUCT CARD
|--------------------------------------------------------------------------
*/

.product-card{

    background:
        linear-gradient(
            145deg,
            #171717,
            #0e0e0e
        );

    border:
        1px
        solid
        #292929;

    border-radius:20px;

    overflow:hidden;

    box-shadow:
        0 15px 40px
        rgba(0,0,0,.35);
}

/*
|--------------------------------------------------------------------------
| PRODUCT IMAGE
|--------------------------------------------------------------------------
*/

.product-image{

    width:100%;

    height:210px;

    background:
        radial-gradient(
            circle,
            #3a0808,
            #111
        );

    display:flex;

    align-items:center;

    justify-content:center;

    overflow:hidden;
}

.product-image img{

    width:100%;

    height:100%;

    object-fit:cover;
}

.game-placeholder{

    font-size:70px;

    opacity:.75;
}

/*
|--------------------------------------------------------------------------
| PRODUCT INFO
|--------------------------------------------------------------------------
*/

.product-info{

    padding:20px;
}

.product-label{

    color:#777;

    font-size:11px;

    text-transform:uppercase;

    letter-spacing:1px;

    margin-bottom:7px;
}

.product-name{

    font-size:21px;

    font-weight:900;

    line-height:1.35;
}

/*
|--------------------------------------------------------------------------
| GAME ACCOUNT
|--------------------------------------------------------------------------
*/

.account{

    margin-top:18px;

    border-top:
        1px
        solid
        #292929;

    padding-top:15px;
}

.account-title{

    color:#aaa;

    font-size:12px;

    font-weight:bold;

    margin-bottom:10px;
}

.account-row{

    display:flex;

    justify-content:space-between;

    gap:15px;

    padding:
        9px
        0;

    border-bottom:
        1px solid
        #202020;

    font-size:13px;
}

.account-row:last-child{

    border-bottom:0;
}

.account-label{

    color:#777;
}

.account-value{

    color:#fff;

    font-weight:bold;

    text-align:right;

    word-break:break-all;
}

/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/

.order-card{

    margin-top:15px;

    background:#111;

    border:
        1px
        solid
        #252525;

    border-radius:18px;

    padding:18px;
}

.order-title{

    font-size:14px;

    font-weight:800;

    margin-bottom:12px;
}

.order-id{

    color:#999;

    font-size:12px;

    word-break:break-all;
}

/*
|--------------------------------------------------------------------------
| PRICE
|--------------------------------------------------------------------------
*/

.price-box{

    margin-top:15px;

    padding:18px;

    background:
        linear-gradient(
            135deg,
            #190707,
            #110707
        );

    border:
        1px
        solid
        #431313;

    border-radius:16px;
}

.price-row{

    display:flex;

    justify-content:space-between;

    padding:6px 0;

    color:#999;

    font-size:13px;
}

.discount{

    color:#f59e0b;
}

.total{

    margin-top:8px;

    padding-top:13px;

    border-top:
        1px
        solid
        #3a1818;

    color:#fff;

    font-size:14px;

    font-weight:bold;
}

.total strong{

    color:#ff3030;

    font-size:24px;
}

/*
|--------------------------------------------------------------------------
| NOTICE
|--------------------------------------------------------------------------
*/

.notice{

    margin-top:15px;

    padding:14px;

    border-radius:13px;

    background:#121212;

    border-left:
        3px
        solid
        #e51b23;

    color:#999;

    font-size:12px;

    line-height:1.7;
}

.notice strong{

    color:#fff;
}

/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

.actions{

    margin-top:18px;
}

.pay-button{

    width:100%;

    border:0;

    padding:16px;

    border-radius:14px;

    background:
        linear-gradient(
            135deg,
            #ff2020,
            #c90000
        );

    color:#fff;

    font-size:16px;

    font-weight:900;

    cursor:pointer;

    box-shadow:
        0 10px 25px
        rgba(229,27,35,.22);

    transition:.2s;
}

.pay-button:hover{

    transform:
        translateY(-1px);

    filter:
        brightness(1.08);
}

.pay-button:active{

    transform:
        scale(.98);
}

.back-button{

    display:block;

    text-align:center;

    margin-top:13px;

    padding:13px;

    color:#777;

    text-decoration:none;

    font-size:13px;
}

/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.footer{

    text-align:center;

    color:#555;

    font-size:11px;

    line-height:1.7;

    margin-top:25px;
}

.footer strong{

    color:#aaa;
}

/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media(max-width:420px){

    .container{

        padding:
            14px
            12px
            30px;
    }

    .product-image{

        height:180px;
    }

    .product-name{

        font-size:19px;
    }

    .total strong{

        font-size:21px;
    }
}

</style>

</head>

<body>

<div class="container">

<!-- HEADER -->

<div class="header">

    <div class="logo">
        CN<span>TECH</span> STORE
    </div>

    <div class="secure">
        🔒 Secure
    </div>

</div>


<!-- PROGRESS -->

<div class="progress">

    <div class="step active"></div>

    <div class="step"></div>

    <div class="step"></div>

</div>


<!-- TITLE -->

<div class="page-title">

    <h1>
        Review Your Order
    </h1>

    <p>
        ตรวจสอบสินค้าและข้อมูลเกมก่อนชำระเงิน
    </p>

</div>


<!-- PRODUCT -->

<div class="product-card">


    <div class="product-image">

        <?php if($product_image !== ''){ ?>

            <img
                src="<?= e($product_image) ?>"
                alt="<?= e($product_name) ?>"
                onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
            >

            <div
                class="game-placeholder"
                style="display:none;"
            >
                🎮
            </div>

        <?php }else{ ?>

            <div class="game-placeholder">
                🎮
            </div>

        <?php } ?>

    </div>


    <div class="product-info">

        <div class="product-label">
            Game Product
        </div>

        <div class="product-name">
            <?= e($product_name) ?>
        </div>


        <!-- ACCOUNT -->

        <div class="account">

            <div class="account-title">
                GAME INFORMATION
            </div>


            <?php if($uid !== ''){ ?>

            <div class="account-row">

                <div class="account-label">
                    UID
                </div>

                <div class="account-value">
                    <?= e($uid) ?>
                </div>

            </div>

            <?php } ?>


            <?php if($server !== ''){ ?>

            <div class="account-row">

                <div class="account-label">
                    Server
                </div>

                <div class="account-value">
                    <?= e($server) ?>
                </div>

            </div>

            <?php } ?>


            <?php if($open_id !== ''){ ?>

            <div class="account-row">

                <div class="account-label">
                    Open ID
                </div>

                <div class="account-value">
                    <?= e($open_id) ?>
                </div>

            </div>

            <?php } ?>


            <div class="account-row">

                <div class="account-label">
                    Product ID
                </div>

                <div class="account-value">
                    <?= $product_id ?>
                </div>

            </div>

        </div>

    </div>

</div>


<!-- ORDER -->

<div class="order-card">

    <div class="order-title">
        Order Information
    </div>

    <div class="order-id">
        Order ID
        <br>
        <strong>
            <?= e($order_id) ?>
        </strong>
    </div>


    <!-- PRICE -->

    <div class="price-box">

        <div class="price-row">

            <span>
                Product Price
            </span>

            <span>
                ₭ <?= number_format($price) ?>
            </span>

        </div>


        <?php if($discount > 0){ ?>

        <div class="price-row discount">

            <span>
                Discount
            </span>

            <span>
                - ₭ <?= number_format($discount) ?>
            </span>

        </div>

        <?php } ?>


        <div class="price-row total">

            <span>
                Total
            </span>

            <strong>
                ₭ <?= number_format($total) ?>
            </strong>

        </div>

    </div>

</div>


<!-- NOTICE -->

<div class="notice">

    <strong>
        ⚠ Please check carefully
    </strong>

    <br>

    ตรวจสอบ UID, Server และสินค้าให้ถูกต้อง
    ก่อนดำเนินการชำระเงิน

</div>


<!-- ACTION -->

<div class="actions">

    <button
        class="pay-button"
        id="payButton"
        onclick="goNext()"
    >
        ดำเนินการชำระเงิน →
    </button>


    <a
        class="back-button"
        href="/"
    >
        ← กลับไป CNTECH STORE
    </a>

</div>


<!-- FOOTER -->

<div class="footer">

    <strong>
        CNTECH STORE
    </strong>

    <br>

    Computer • Mobile • Parts & Accessories

    <br>

    Secure Payment

</div>

</div>


<script>

function goNext(){

    const button =
        document.getElementById(
            'payButton'
        );

    if(button.disabled){
        return;
    }

    button.disabled = true;

    button.textContent =
        'กำลังเตรียมการชำระเงิน...';

    const params =
        new URLSearchParams({

            order_id:
                <?= json_encode($order_id) ?>,

            type:
                'game'

        });

    window.location.href =
        'game_sc.php?' +
        params.toString();

}

</script>

</body>

</html>