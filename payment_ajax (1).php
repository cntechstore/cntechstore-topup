<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type: text/html; charset=utf-8');

require_once "../config.php";
require_once "../database.php";


/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

$order_id = trim($_GET['order_id'] ?? '');
$type     = strtolower(trim($_GET['type'] ?? ''));


/*
|--------------------------------------------------------------------------
| ALLOWED TYPES
|--------------------------------------------------------------------------
*/

$allowed_types = [
    'game',
    'mobile',
    'voucher'
];


if ($order_id === '') {

    http_response_code(400);

    die("Missing Order ID");

}


if (!in_array($type, $allowed_types, true)) {

    http_response_code(400);

    die("Invalid Payment Type");

}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

$table = '';

switch ($type) {

    case 'game':

        $table = 'game_orders';

        break;

    case 'mobile':

        $table = 'mobile_orders';

        break;

    case 'voucher':

        $table = 'voucher_orders';

        break;

}


/*
|--------------------------------------------------------------------------
| FIND ORDER
|--------------------------------------------------------------------------
*/

$order = null;


$sql = "
    SELECT *
    FROM `$table`
    WHERE order_id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Database Error: " .
        htmlspecialchars($conn->error)
    );

}


$stmt->bind_param(
    "s",
    $order_id
);


$stmt->execute();


$result = $stmt->get_result();


$order = $result->fetch_assoc();


$stmt->close();


/*
|--------------------------------------------------------------------------
| ORDER NOT FOUND
|--------------------------------------------------------------------------
*/

if (!$order) {

    http_response_code(404);

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >

        <title>Order Not Found | CNTECH STORE</title>

        <style>

        *{
            box-sizing:border-box;
        }

        body{

            margin:0;

            min-height:100vh;

            display:flex;

            align-items:center;

            justify-content:center;

            padding:20px;

            font-family:Arial,sans-serif;

            color:white;

            background:
            radial-gradient(
                circle at top,
                #400000,
                #080808 60%
            );

        }

        .error-box{

            width:100%;

            max-width:450px;

            padding:35px 25px;

            text-align:center;

            border-radius:25px;

            background:
            rgba(255,255,255,.08);

            border:
            1px solid
            rgba(255,255,255,.15);

            backdrop-filter:blur(20px);

            box-shadow:
            0 20px 60px
            rgba(0,0,0,.5);

        }

        .error-icon{

            width:80px;

            height:80px;

            margin:0 auto 20px;

            border-radius:50%;

            display:flex;

            align-items:center;

            justify-content:center;

            background:#ff0000;

            font-size:35px;

        }

        h2{
            margin:0 0 10px;
        }

        p{
            color:#bbb;
            line-height:1.6;
        }

        .order{

            margin-top:20px;

            padding:12px;

            border-radius:12px;

            background:
            rgba(0,0,0,.4);

            word-break:break-all;

            font-size:13px;

        }

        .back{

            display:block;

            margin-top:20px;

            padding:14px;

            border-radius:15px;

            color:white;

            text-decoration:none;

            font-weight:bold;

            background:
            linear-gradient(
                135deg,
                #ff0000,
                #990000
            );

        }

        </style>

    </head>

    <body>

        <div class="error-box">

            <div class="error-icon">
                !
            </div>

            <h2>
                Order Not Found
            </h2>

            <p>
                ไม่พบรายการสั่งซื้อที่ต้องการชำระเงิน
            </p>

            <div class="order">

                <?=htmlspecialchars($order_id)?>

            </div>

            <a
                href="/"
                class="back"
            >
                กลับหน้าหลัก
            </a>

        </div>

    </body>

    </html>

    <?php

    exit;

}


/*
|--------------------------------------------------------------------------
| ORDER INFORMATION
|--------------------------------------------------------------------------
*/

$display_order_id =
    $order['order_id']
    ?? $order_id;


/*
|--------------------------------------------------------------------------
| AMOUNT
|--------------------------------------------------------------------------
*/

if ($type === 'mobile') {

    $amount =
        (float)(
            $order['amount']
            ?? 0
        );

} else {

    $amount =
        (float)(
            $order['total']
            ?? $order['price']
            ?? 0
        );

}


/*
|--------------------------------------------------------------------------
| CURRENCY
|--------------------------------------------------------------------------
*/

$currency = 'LAK';


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

$payment_status =
    strtolower(
        trim(
            $order['payment_status']
            ?? 'pending'
        )
    );


/*
|--------------------------------------------------------------------------
| ORDER STATUS
|--------------------------------------------------------------------------
*/

$order_status =
    strtolower(
        trim(
            $order['status']
            ?? $order['order_status']
            ?? 'pending'
        )
    );


/*
|--------------------------------------------------------------------------
| ALREADY PAID
|--------------------------------------------------------------------------
*/

if ($payment_status === 'paid') {

    $page_status = 'paid';

} elseif ($order_status === 'cancelled') {

    $page_status = 'cancelled';

} else {

    $page_status = 'pending';

}


/*
|--------------------------------------------------------------------------
| BANK ACCOUNTS
|--------------------------------------------------------------------------
*/

$banks = [];


$result = $conn->query("
    SELECT *
    FROM admin_from_bank_account
    ORDER BY id ASC
");


if ($result) {

    while ($row = $result->fetch_assoc()) {

        $bank_name = '';

        if (
            !empty($row['bank_name'])
        ) {

            $bank_name =
                trim($row['bank_name']);

        } elseif (
            !empty($row['name'])
        ) {

            $bank_name =
                trim($row['name']);

        } else {

            $bank_name = 'Bank';

        }


        $bank_status =
            strtolower(
                trim(
                    $row['status']
                    ?? 'maintenance'
                )
            );


        $bank_type =
            strtolower(
                trim(
                    $row['type']
                    ?? 'bank'
                )
            );


        $image =
            "/assets/no-image.png";


        if (
            !empty($row['image'])
        ) {

            $image =
                "/admin/uploads/" .
                basename(
                    $row['image']
                );

        }


        $banks[] = [

            'id' =>
                (int)$row['id'],

            'name' =>
                $bank_name,

            'status' =>
                $bank_status,

            'type' =>
                $bank_type,

            'image' =>
                $image

        ];

    }

}


/*
|--------------------------------------------------------------------------
| TRANSACTION
|--------------------------------------------------------------------------
*/

$transaction_id =
    'MAN_' .
    date('YmdHis') .
    '_' .
    strtoupper(
        bin2hex(
            random_bytes(4)
        )
    );


/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/

$_SESSION['manual_payment'] = [

    'transaction_id' =>
        $transaction_id,

    'order_id' =>
        $display_order_id,

    'type' =>
        $type,

    'table' =>
        $table,

    'amount' =>
        $amount,

    'currency' =>
        $currency,

    'created_at' =>
        time()

];

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Payment Gateway | CNTECH STORE
</title>


<style>

*{
    box-sizing:border-box;
}

body{

    margin:0;

    min-height:100vh;

    font-family:
    Arial,
    sans-serif;

    color:white;

    background:

    radial-gradient(
        circle at top,
        #450000 0%,
        #120000 35%,
        #050505 75%
    );

    padding-bottom:50px;

}


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

.header{

    height:70px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 20px;

    background:
    rgba(0,0,0,.7);

    border-bottom:
    1px solid
    rgba(255,0,0,.5);

    backdrop-filter:blur(15px);

}


.logo{

    font-size:24px;

    font-weight:900;

}


.logo span{

    color:#ff0000;

}


.card-icon{

    width:45px;

    height:45px;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:
    linear-gradient(
        135deg,
        #ff0000,
        #990000
    );

    font-size:21px;

}


/*
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
*/

.container{

    width:100%;

    max-width:650px;

    margin:auto;

    padding:20px 15px;

}


/*
|--------------------------------------------------------------------------
| GLASS
|--------------------------------------------------------------------------
*/

.glass{

    background:
    rgba(255,255,255,.07);

    border:
    1px solid
    rgba(255,255,255,.15);

    border-radius:24px;

    padding:22px;

    margin-bottom:20px;

    backdrop-filter:blur(18px);

    box-shadow:
    0 15px 45px
    rgba(0,0,0,.4);

}


/*
|--------------------------------------------------------------------------
| ORDER
|--------------------------------------------------------------------------
*/

.order-title{

    font-size:20px;

    font-weight:900;

    margin-bottom:15px;

}


.order-id{

    padding:13px;

    border-radius:13px;

    background:
    rgba(0,0,0,.45);

    font-size:13px;

    word-break:break-all;

    color:#ddd;

}


.amount{

    margin-top:20px;

    text-align:center;

}


.amount-label{

    color:#aaa;

    font-size:14px;

}


.amount-value{

    margin-top:7px;

    font-size:35px;

    font-weight:900;

    color:#ff2020;

}


.currency{

    font-size:15px;

    color:white;

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

.status{

    display:inline-flex;

    margin-top:15px;

    padding:8px 15px;

    border-radius:20px;

    font-size:13px;

    font-weight:bold;

}


.status.pending{

    color:#facc15;

    background:#422006;

}


.status.paid{

    color:#22c55e;

    background:#052e16;

}


.status.cancelled{

    color:#ef4444;

    background:#450a0a;

}


/*
|--------------------------------------------------------------------------
| SECTION
|--------------------------------------------------------------------------
*/

.section-title{

    margin:25px 0 15px;

    font-size:20px;

    font-weight:900;

}


/*
|--------------------------------------------------------------------------
| PAYMENT GRID
|--------------------------------------------------------------------------
*/

.payment-grid{

    display:grid;

    grid-template-columns:
    repeat(2,1fr);

    gap:14px;

}


@media(min-width:700px){

    .payment-grid{

        grid-template-columns:
        repeat(3,1fr);

    }

}


/*
|--------------------------------------------------------------------------
| PAYMENT CARD
|--------------------------------------------------------------------------
*/

.payment-card{

    position:relative;

    min-height:185px;

    padding:15px;

    border-radius:22px;

    display:flex;

    flex-direction:column;

    align-items:center;

    justify-content:center;

    text-align:center;

    background:
    linear-gradient(
        145deg,
        rgba(255,255,255,.11),
        rgba(255,0,0,.06)
    );

    border:
    1px solid
    rgba(255,255,255,.16);

    transition:.25s;

}


.payment-card.online{

    cursor:pointer;

}


.payment-card.online:hover{

    transform:
    translateY(-5px);

    border-color:#ff0000;

    box-shadow:
    0 15px 35px
    rgba(255,0,0,.3);

}


.payment-card.disabled{

    opacity:.45;

    filter:grayscale(.7);

}


/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

.payment-image{

    width:75px;

    height:75px;

    padding:10px;

    margin-bottom:12px;

    border-radius:18px;

    background:white;

}


.payment-image img{

    width:100%;

    height:100%;

    object-fit:contain;

    border-radius:10px;

}


.payment-name{

    font-size:14px;

    font-weight:900;

}


.payment-status{

    margin-top:9px;

    padding:5px 10px;

    border-radius:15px;

    font-size:10px;

    font-weight:bold;

}


.payment-status.online{

    color:#22c55e;

    background:#052e16;

}


.payment-status.maintenance{

    color:#facc15;

    background:#422006;

}


.payment-status.offline{

    color:#ef4444;

    background:#450a0a;

}


/*
|--------------------------------------------------------------------------
| TRANSACTION
|--------------------------------------------------------------------------
*/

.transaction{

    margin-top:20px;

    padding:14px;

    border-radius:15px;

    background:
    rgba(0,0,0,.35);

}


.transaction small{

    display:block;

    color:#888;

    margin-bottom:6px;

}


.transaction strong{

    font-size:13px;

    word-break:break-all;

}


/*
|--------------------------------------------------------------------------
| BACK
|--------------------------------------------------------------------------
*/

.back{

    width:100%;

    margin-top:20px;

    padding:15px;

    border-radius:17px;

    border:
    1px solid
    rgba(255,255,255,.15);

    background:
    rgba(255,255,255,.08);

    color:white;

    font-weight:900;

    font-size:15px;

    cursor:pointer;

}


/*
|--------------------------------------------------------------------------
| PAID / CANCELLED
|--------------------------------------------------------------------------
*/

.notice{

    text-align:center;

    padding:20px;

    border-radius:18px;

    background:
    rgba(0,0,0,.3);

    margin-top:20px;

}


</style>

</head>


<body>


<header class="header">

    <div class="logo">

        CNTECH
        <span>STORE</span>

    </div>

    <div class="card-icon">

        💳

    </div>

</header>


<main class="container">


<!-- ORDER -->

<section class="glass">


    <div class="order-title">

        Payment Order

    </div>


    <div class="order-id">

        <?=htmlspecialchars(
            $display_order_id
        )?>

    </div>


    <div class="amount">

        <div class="amount-label">

            Amount to Pay

        </div>


        <div class="amount-value">

            <?=number_format(
                $amount,
                2
            )?>

            <span class="currency">

                <?=htmlspecialchars(
                    $currency
                )?>

            </span>

        </div>


        <div class="status <?=$page_status?>">

            <?php

            if ($page_status === 'paid') {

                echo '✓ PAID';

            } elseif (
                $page_status === 'cancelled'
            ) {

                echo '✕ CANCELLED';

            } else {

                echo '● PENDING PAYMENT';

            }

            ?>

        </div>

    </div>


    <div class="transaction">

        <small>
            Transaction ID
        </small>

        <strong>
            <?=htmlspecialchars(
                $transaction_id
            )?>
        </strong>

    </div>


</section>



<?php if ($page_status === 'pending'): ?>


<div class="section-title">

    Payment Method

</div>


<div class="payment-grid">


<?php foreach ($banks as $bank): ?>


<?php

$is_online =
    $bank['status'] === 'online';

?>


<div
    class="payment-card
    <?=$is_online ? 'online' : 'disabled'?>"
    onclick="
        <?php
        if ($is_online) {
            echo "selectPayment(" .
                 (int)$bank['id'] .
                 ")";
        }
        ?>
    "
>


    <div class="payment-image">

        <img
            src="<?=htmlspecialchars(
                $bank['image']
            )?>"
            alt="<?=htmlspecialchars(
                $bank['name']
            )?>"
        >

    </div>


    <div class="payment-name">

        <?=htmlspecialchars(
            $bank['name']
        )?>

    </div>


    <div
        class="payment-status
        <?=htmlspecialchars(
            $bank['status']
        )?>"
    >

        <?=strtoupper(
            htmlspecialchars(
                $bank['status']
            )
        )?>

    </div>


</div>


<?php endforeach; ?>


</div>


<div class="notice">

    เลือกช่องทางการชำระเงินที่ต้องการ

    <br>

    ระบบจะแสดงรายละเอียดการชำระเงินในขั้นตอนถัดไป

</div>


<?php elseif ($page_status === 'paid'): ?>


<div class="notice">

    ✓ รายการนี้ได้รับการชำระเงินแล้ว

</div>


<?php else: ?>


<div class="notice">

    ✕ รายการนี้ถูกยกเลิกแล้ว

</div>


<?php endif; ?>


<button
    class="back"
    onclick="history.back()"
>

    ← กลับ

</button>


</main>



<script>

const ORDER_ID =
    <?=json_encode(
        $display_order_id
    )?>;


const ORDER_TYPE =
    <?=json_encode(
        $type
    )?>;


const TRANSACTION_ID =
    <?=json_encode(
        $transaction_id
    )?>;


function selectPayment(bankId)
{

    const url =
        "payment_confirm.php" +
        "?order_id=" +
        encodeURIComponent(
            ORDER_ID
        ) +
        "&type=" +
        encodeURIComponent(
            ORDER_TYPE
        ) +
        "&bank_id=" +
        encodeURIComponent(
            bankId
        ) +
        "&transaction_id=" +
        encodeURIComponent(
            TRANSACTION_ID
        );


    window.location.href = url;

}

</script>


</body>

</html>