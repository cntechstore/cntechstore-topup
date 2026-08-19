<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../config.php";
require_once "../database.php";

/*
|--------------------------------------------------------------------------
| CNTECH STORE
| Manual Payment / Upload Slip
|--------------------------------------------------------------------------
| IMPORTANT
|
| Payment expiration is stored permanently in:
| payment_transactions.expire_at
|
| Therefore:
| - Refresh page       = same countdown
| - Close browser      = same countdown
| - Open page again    = same countdown
| - Never reset timer
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| TABLES
|--------------------------------------------------------------------------
*/

$tables = [

    'game' =>
        'game_orders',

    'voucher' =>
        'voucher_orders',

    'mobile' =>
        'mobile_orders'

];


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
| INPUT
|--------------------------------------------------------------------------
*/

$order_id =
    trim(
        $_GET['order_id']
        ?? ''
    );

$type =
    strtolower(
        trim(
            $_GET['type']
            ?? ''
        )
    );

$bank_id =
    (int)(
        $_GET['bank_id']
        ?? 0
    );

$transaction_id =
    trim(
        $_GET['transaction_id']
        ?? ''
    );


/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

if ($order_id === '') {

    exit('Missing Order ID');

}


if (!isset($tables[$type])) {

    exit('Invalid Payment Type');

}


if ($bank_id <= 0) {

    exit('Invalid Bank');

}


if ($transaction_id === '') {

    exit('Missing Transaction ID');

}


$table =
    $tables[$type];


/*
|--------------------------------------------------------------------------
| FIND ORDER
|--------------------------------------------------------------------------
*/

$stmt =
    $conn->prepare("
        SELECT *
        FROM `$table`
        WHERE order_id = ?
        LIMIT 1
    ");


if (!$stmt) {

    exit(
        'Database error: ' .
        $conn->error
    );

}


$stmt->bind_param(
    "s",
    $order_id
);


$stmt->execute();


$result =
    $stmt->get_result();


$order =
    $result->fetch_assoc();


$stmt->close();


if (!$order) {

    exit('Order not found');

}


/*
|--------------------------------------------------------------------------
| ORDER STATUS
|--------------------------------------------------------------------------
*/

$payment_status =
    strtolower(
        trim(
            $order['payment_status']
            ?? 'pending'
        )
    );


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

if (
    $payment_status === 'paid'
) {

    exit('Order already paid');

}


/*
|--------------------------------------------------------------------------
| ALREADY CANCELLED
|--------------------------------------------------------------------------
*/

if (
    in_array(
        $order_status,
        [
            'cancelled',
            'canceled',
            'expired'
        ],
        true
    )
) {

    exit(
        'Order has already been cancelled'
    );

}


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
            ?? $order['amount']
            ?? 0
        );

}


if ($amount <= 0) {

    exit(
        'Invalid order amount'
    );

}


/*
|--------------------------------------------------------------------------
| PRODUCT DETAILS
|--------------------------------------------------------------------------
*/

$product_name =
    $order['product_name']
    ?? $order['product']
    ?? $order['title']
    ?? $order['name']
    ?? '';


$product_detail =
    $order['product_detail']
    ?? $order['description']
    ?? $order['details']
    ?? '';


$quantity =
    (int)(
        $order['quantity']
        ?? 1
    );


$customer_name =
    $order['customer_name']
    ?? $order['name']
    ?? $order['username']
    ?? '';


$customer_email =
    $order['email']
    ?? $order['customer_email']
    ?? $order['user_email']
    ?? '';


/*
|--------------------------------------------------------------------------
| PAYMENT TRANSACTION
|--------------------------------------------------------------------------
|
| payment_transactions structure from your database:
|
| id
| order_id
| transaction_id
| amount
| qr_text
| status
| expire_at
| created_at
|
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| We DO NOT create a new 120 second timer on every page load.
|
| First load:
|     create transaction with expire_at = NOW() + 120 seconds
|
| Next loads:
|     read existing expire_at
|
|--------------------------------------------------------------------------
*/


$expire_time = 0;

$transaction_status = 'pending';

$transaction_db_id = 0;


/*
|--------------------------------------------------------------------------
| FIND EXISTING TRANSACTION
|--------------------------------------------------------------------------
|
| We identify the payment by transaction_id.
|
*/

$stmt =
    $conn->prepare("
        SELECT
            id,
            order_id,
            transaction_id,
            amount,
            status,
            expire_at,
            created_at
        FROM payment_transactions
        WHERE transaction_id = ?
        AND order_id = ?
        LIMIT 1
    ");


if (!$stmt) {

    exit(
        'Payment transaction database error: ' .
        $conn->error
    );

}


$stmt->bind_param(
    "ss",
    $transaction_id,
    $order_id
);


$stmt->execute();


$transaction =
    $stmt
        ->get_result()
        ->fetch_assoc();


$stmt->close();


/*
|--------------------------------------------------------------------------
| EXISTING TRANSACTION
|--------------------------------------------------------------------------
*/

if ($transaction) {

    $transaction_db_id =
        (int)(
            $transaction['id']
            ?? 0
        );


    $transaction_status =
        strtolower(
            trim(
                $transaction['status']
                ?? 'pending'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION ALREADY PAID
    |--------------------------------------------------------------------------
    */

    if (
        $transaction_status === 'paid'
    ) {

        header(
            "Location: payment_success.php?" .
            http_build_query([
                'order_id' =>
                    $order_id
            ])
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION ALREADY EXPIRED
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            $transaction_status,
            [
                'expired',
                'cancelled',
                'canceled'
            ],
            true
        )
    ) {

        header(
            "Location: payment_timeout.php?" .
            http_build_query([
                'order_id' =>
                    $order_id,

                'type' =>
                    $type,

                'transaction_id' =>
                    $transaction_id,

                'result' =>
                    'expired'
            ])
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | READ PERMANENT EXPIRE TIME
    |--------------------------------------------------------------------------
    */

    if (
        !empty(
            $transaction['expire_at']
        )
    ) {

        $expire_time =
            strtotime(
                $transaction['expire_at']
            );

    }

}


/*
|--------------------------------------------------------------------------
| CREATE TRANSACTION IF NOT EXISTS
|--------------------------------------------------------------------------
|
| Only the FIRST request gets a new 120-second expiration.
|
*/

if (!$transaction) {

    /*
    |--------------------------------------------------------------------------
    | SERVER TIME
    |--------------------------------------------------------------------------
    */

    $expire_time =
        time() + 300;


    /*
    |--------------------------------------------------------------------------
    | DATETIME
    |--------------------------------------------------------------------------
    */

    $expire_datetime =
        date(
            'Y-m-d H:i:s',
            $expire_time
        );


    /*
    |--------------------------------------------------------------------------
    | INSERT TRANSACTION
    |--------------------------------------------------------------------------
    */

    $stmt =
        $conn->prepare("
            INSERT INTO payment_transactions
            (
                order_id,
                transaction_id,
                amount,
                status,
                expire_at,
                created_at
            )
            VALUES
            (
                ?,
                ?,
                ?,
                'pending',
                ?,
                NOW()
            )
        ");


    if (!$stmt) {

        exit(
            'Unable to create payment transaction: ' .
            $conn->error
        );

    }


    $stmt->bind_param(
        "ssds",
        $order_id,
        $transaction_id,
        $amount,
        $expire_datetime
    );


    if (!$stmt->execute()) {

        /*
        |--------------------------------------------------------------------------
        | POSSIBLE DUPLICATE
        |--------------------------------------------------------------------------
        |
        | Another request may have created the transaction
        | at exactly the same time.
        |
        */

        $stmt->close();


        $stmt =
            $conn->prepare("
                SELECT
                    id,
                    order_id,
                    transaction_id,
                    amount,
                    status,
                    expire_at,
                    created_at
                FROM payment_transactions
                WHERE transaction_id = ?
                AND order_id = ?
                LIMIT 1
            ");


        if (!$stmt) {

            exit(
                'Payment transaction error'
            );

        }


        $stmt->bind_param(
            "ss",
            $transaction_id,
            $order_id
        );


        $stmt->execute();


        $transaction =
            $stmt
                ->get_result()
                ->fetch_assoc();


        $stmt->close();


        if (!$transaction) {

            exit(
                'Unable to create payment transaction'
            );

        }


        $transaction_db_id =
            (int)(
                $transaction['id']
                ?? 0
            );


        $transaction_status =
            strtolower(
                trim(
                    $transaction['status']
                    ?? 'pending'
                )
            );


        $expire_time =
            strtotime(
                $transaction['expire_at']
            );

    } else {

        $transaction_db_id =
            $conn->insert_id;


        $transaction_status =
            'pending';

    }


    if (
        $stmt &&
        $stmt instanceof mysqli_stmt
    ) {

        @$stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| FINAL VALIDATION OF EXPIRE TIME
|--------------------------------------------------------------------------
*/

if ($expire_time <= 0) {

    exit(
        'Invalid payment expiration time'
    );

}


/*
|--------------------------------------------------------------------------
| ALREADY EXPIRED
|--------------------------------------------------------------------------
|
| DO NOT create a new timer.
|
*/

if (
    $expire_time <= time()
) {

    header(
        "Location: payment_timeout.php?" .
        http_build_query([
            'order_id' =>
                $order_id,

            'type' =>
                $type,

            'transaction_id' =>
                $transaction_id,

            'result' =>
                'expired'
        ])
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| FIND BANK
|--------------------------------------------------------------------------
*/

$stmt =
    $conn->prepare("
        SELECT *
        FROM admin_from_bank_account
        WHERE id = ?
        LIMIT 1
    ");


if (!$stmt) {

    exit(
        'Payment method database error'
    );

}


$stmt->bind_param(
    "i",
    $bank_id
);


$stmt->execute();


$result =
    $stmt->get_result();


$bank =
    $result->fetch_assoc();


$stmt->close();


if (!$bank) {

    exit(
        'Payment method not found'
    );

}


/*
|--------------------------------------------------------------------------
| BANK INFORMATION
|--------------------------------------------------------------------------
*/

$bank_name =
    trim(
        $bank['bank_name']
        ?? $bank['name']
        ?? 'Payment'
    );


$account_name =
    trim(
        $bank['account_name']
        ?? ''
    );


$account_number =
    trim(
        $bank['account_number']
        ?? ''
    );


$bank_status =
    strtolower(
        trim(
            $bank['status']
            ?? 'maintenance'
        )
    );


$bank_type =
    strtolower(
        trim(
            $bank['type']
            ?? 'bank'
        )
    );


/*
|--------------------------------------------------------------------------
| BANK IMAGE
|--------------------------------------------------------------------------
*/

$bank_image =
    "/assets/no-image.png";


if (!empty($bank['image'])) {

    $bank_image =
        "/admin/uploads/" .
        basename(
            $bank['image']
        );

}


/*
|--------------------------------------------------------------------------
| QR IMAGE
|--------------------------------------------------------------------------
*/

$qr_image = '';


if (!empty($bank['qr_image'])) {

    $qr_image =
        "/admin/uploads/" .
        basename(
            $bank['qr_image']
        );

}


/*
|--------------------------------------------------------------------------
| CARD PAYMENT
|--------------------------------------------------------------------------
*/

if ($bank_type === 'card') {

    header(
        "Location: /stripe_checkout.php?" .
        http_build_query([
            'order_id' =>
                $order_id,

            'type' =>
                $type,

            'bank_id' =>
                $bank_id,

            'transaction_id' =>
                $transaction_id
        ])
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| BANK STATUS
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $bank_status,
        [
            'online',
            'active',
            'enabled',
            'available'
        ],
        true
    )
) {

    exit(
        'Payment method is currently unavailable'
    );

}


/*
|--------------------------------------------------------------------------
| QR TITLE
|--------------------------------------------------------------------------
*/

if ($bank_id === 1) {

    $qr_title =
        'Scan with LAP Net BCEL';

} elseif ($bank_id === 2) {

    $qr_title =
        'Scan with LAP Net LDB';

} else {

    $qr_title =
        'Scan QR to Pay';

}


/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/

$_SESSION['manual_payment'] = [

    'order_id' =>
        $order_id,

    'type' =>
        $type,

    'table' =>
        $table,

    'bank_id' =>
        $bank_id,

    'bank_name' =>
        $bank_name,

    'transaction_id' =>
        $transaction_id,

    'amount' =>
        $amount,

    'currency' =>
        'LAK',

    'expires_at' =>
        $expire_time,

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

<meta
    name="theme-color"
    content="#050505"
>

<title>
Payment - CNTECH STORE
</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    min-height: 100vh;

    background: #050505;

    color: #ffffff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}

.container {

    width: 100%;

    max-width: 580px;

    margin: auto;

    padding: 12px;

}

.card {

    background: #111111;

    border:
        1px solid #292929;

    border-radius: 22px;

    padding: 20px;

    box-shadow:
        0 20px 60px
        rgba(0,0,0,.55);

}

.logo {

    text-align: center;

    font-size: 25px;

    font-weight: 900;

}

.logo span {

    color: #ff2020;

}

.subtitle {

    text-align: center;

    color: #888;

    margin-top: 5px;

    margin-bottom: 18px;

}


/* TIMER */

.timer {

    background:
        linear-gradient(
            135deg,
            #280707,
            #150505
        );

    border:
        1px solid #7d1515;

    border-radius: 16px;

    padding: 13px;

    text-align: center;

    margin-bottom: 17px;

}

.timer-title {

    color: #aaa;

    font-size: 12px;

}

.countdown {

    color: #ff3030;

    font-size: 32px;

    font-weight: 900;

    margin-top: 3px;

}

.timer.warning {

    border-color: #ff2020;

}

.timer.warning .countdown {

    color: #ff2020;

    animation:
        pulse .8s infinite;

}

.timer.expired {

    border-color: #ff2020;

}

.timer.expired .countdown {

    color: #ffffff;

}


/* BANK */

.bank {

    text-align: center;

    background: #181818;

    border-radius: 17px;

    padding: 18px;

}

.bank img {

    width: 82px;

    height: 82px;

    object-fit: contain;

    background: #ffffff;

    border-radius: 13px;

    padding: 7px;

}

.bank-name {

    margin-top: 10px;

    font-size: 20px;

    font-weight: 800;

}

.status {

    display: inline-block;

    margin-top: 7px;

    padding: 5px 13px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: 800;

}

.status.online {

    background: #153c24;

    color: #63e28b;

}


/* QR */

.qr-title {

    text-align: center;

    font-weight: 800;

    margin: 20px 0 10px;

}

.qr-box {

    background: #ffffff;

    border-radius: 17px;

    padding: 13px;

    text-align: center;

}

.qr-box img {

    width: 100%;

    max-width: 320px;

    display: block;

    margin: auto;

}

.no-qr {

    color: #777;

    padding: 45px 10px;

}


/* PRODUCT */

.product {

    margin-top: 15px;

    padding: 15px;

    border-radius: 14px;

    background: #191919;

    border-left:
        3px solid #ff2020;

}

.product-title {

    font-size: 16px;

    font-weight: 800;

    margin-bottom: 7px;

}

.product-detail {

    color: #aaa;

    font-size: 13px;

    line-height: 1.6;

}

.product-price {

    margin-top: 10px;

    color: #ff3030;

    font-size: 19px;

    font-weight: 900;

}


/* DETAILS */

.details {

    margin-top: 17px;

    background: #181818;

    border-radius: 15px;

    padding: 14px;

}

.row {

    display: flex;

    justify-content: space-between;

    gap: 15px;

    padding: 11px 0;

    border-bottom:
        1px solid #292929;

}

.row:last-child {

    border-bottom: 0;

}

.label {

    color: #888;

}

.value {

    text-align: right;

    font-weight: 700;

    max-width: 65%;

    word-break: break-word;

}

.amount {

    color: #ff3030;

    font-size: 21px;

}


/* NOTICE */

.notice {

    margin-top: 15px;

    background: #191919;

    border-radius: 12px;

    padding: 13px;

    color: #aaa;

    font-size: 13px;

    line-height: 1.6;

}

.notice strong {

    color: #ffffff;

}


/* EXPIRED */

.expired-box {

    display: none;

    margin-top: 14px;

    padding: 15px;

    background: #2a0808;

    border:
        1px solid #8c1e1e;

    color: #ff6b6b;

    border-radius: 12px;

    text-align: center;

    font-weight: 800;

}


/* UPLOAD */

.upload {

    margin-top: 19px;

}

.upload-title {

    font-size: 18px;

    font-weight: 800;

    margin-bottom: 9px;

}

input[type="file"] {

    width: 100%;

    padding: 12px;

    color: #ddd;

    background: #181818;

    border:
        1px dashed #555;

    border-radius: 12px;

}

button {

    width: 100%;

    margin-top: 14px;

    padding: 15px;

    border: 0;

    border-radius: 13px;

    background: #e51b23;

    color: #fff;

    font-size: 16px;

    font-weight: 800;

    cursor: pointer;

}

button:hover {

    background: #ff252d;

}

button:disabled {

    background: #555;

    cursor: not-allowed;

}


/* BACK */

.back {

    display: block;

    text-align: center;

    margin-top: 16px;

    color: #888;

    text-decoration: none;

}

.back:hover {

    color: #fff;

}


/* PULSE */

@keyframes pulse {

    0% {
        opacity: 1;
    }

    50% {
        opacity: .45;
    }

    100% {
        opacity: 1;
    }

}


/* MOBILE */

@media(max-width:480px) {

    .container {

        padding: 8px;

    }

    .card {

        padding: 15px;

        border-radius: 18px;

    }

    .logo {

        font-size: 22px;

    }

    .row {

        align-items: flex-start;

    }

    .value {

        max-width: 62%;

    }

}

.error-box{
    margin:15px 0;
    padding:14px 16px;
    background:#350909;
    border:1px solid #ff2020;
    color:#ff5555;
    border-radius:12px;
    font-weight:700;
    text-align:center;
}

</style>

</head>

<body>

<?php if (!empty($_GET['error'])): ?>
<div class="error-box">
    <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
</div>
<?php endif; ?>

<div class="container">

<div class="card">


<div class="logo">

CN<span>TECH</span> STORE

</div>


<div class="subtitle">

Manual Payment

</div>


<!-- TIMER -->

<div
    class="timer"
    id="timerBox"
>

<div class="timer-title">

Payment expires in

</div>

<div
    class="countdown"
    id="countdown"
>
--:--
</div>

</div>


<!-- EXPIRED -->

<div
    class="expired-box"
    id="expiredBox"
>

<strong>
หมดเวลาชำระเงิน
</strong>

<br><br>

กำลังยกเลิกรายการ...

</div>


<!-- BANK -->

<div class="bank">

<img
    src="<?=e($bank_image)?>"
    alt="<?=e($bank_name)?>"
>

<div class="bank-name">

<?=e($bank_name)?>

</div>

<div class="status online">

ONLINE

</div>

</div>


<!-- QR -->

<div class="qr-title">

<?=e($qr_title)?>

</div>


<div class="qr-box">

<?php if ($qr_image !== ''): ?>

<img
    src="<?=e($qr_image)?>"
    alt="Payment QR"
>

<?php else: ?>

<div class="no-qr">

QR Code is not configured.

</div>

<?php endif; ?>

</div>


<!-- PRODUCT -->

<?php if (
    $product_name !== ''
    ||
    $product_detail !== ''
): ?>

<div class="product">

<div class="product-title">

<?=e(
    $product_name !== ''
        ? $product_name
        : 'Order Details'
)?>

</div>


<?php if ($product_detail !== ''): ?>

<div class="product-detail">

<?=nl2br(
    e($product_detail)
)?>

</div>

<?php endif; ?>


<?php if ($quantity > 0): ?>

<div class="product-detail">

Quantity:
<?=e($quantity)?>

</div>

<?php endif; ?>


<div class="product-price">

<?=number_format(
    $amount,
    2
)?> LAK

</div>

</div>

<?php endif; ?>


<!-- PAYMENT DETAILS -->

<div class="details">


<?php if ($account_name !== ''): ?>

<div class="row">

<div class="label">

Account Name

</div>

<div class="value">

<?=e($account_name)?>

</div>

</div>

<?php endif; ?>


<?php if ($account_number !== ''): ?>

<div class="row">

<div class="label">

Account Number

</div>

<div class="value">

<?=e($account_number)?>

</div>

</div>

<?php endif; ?>


<div class="row">

<div class="label">

Order ID

</div>

<div class="value">

<?=e($order_id)?>

</div>

</div>


<div class="row">

<div class="label">

Product Type

</div>

<div class="value">

<?=e(
    strtoupper($type)
)?>

</div>

</div>


<div class="row">

<div class="label">

Amount

</div>

<div class="value amount">

<?=number_format(
    $amount,
    2
)?> LAK

</div>

</div>


<div class="row">

<div class="label">

Transaction

</div>

<div class="value">

<?=e(
    $transaction_id
)?>

</div>

</div>


</div>


<!-- NOTICE -->

<div class="notice">

<strong>
Important
</strong>

<br><br>

Please pay the exact amount shown above.

<br>

Upload your payment slip before the countdown reaches zero.

<br><br>

If the timer reaches 00:00, the order will be automatically cancelled.

</div>


<!-- UPLOAD -->

<div class="upload">

<div class="upload-title">

Upload Payment Slip

</div>


<form
    id="slipForm"
    action="upload_slip_process.php"
    method="POST"
    enctype="multipart/form-data"
>


<input
    type="hidden"
    name="order_id"
    value="<?=e($order_id)?>"
>


<input
    type="hidden"
    name="type"
    value="<?=e($type)?>"
>


<input
    type="hidden"
    name="bank_id"
    value="<?=e($bank_id)?>"
>


<input
    type="hidden"
    name="transaction_id"
    value="<?=e($transaction_id)?>"
>


<input
    type="file"
    id="slip"
    name="slip"
    accept="image/jpeg,image/png,image/webp"
    required
>


<button
    type="submit"
    id="submitBtn"
>

✓ Submit Payment Slip

</button>


</form>

</div>


<a
    href="/"
    class="back"
>

← Back to CNTECH STORE

</a>


</div>

</div>

<script>
/* CNTECH STORE - PAYMENT COUNTDOWN 5 MINUTES */

const expireAt = <?=((int)$expire_time)*1000?>;
const orderId = <?=json_encode($order_id)?>;
const type = <?=json_encode($type)?>;
const transactionId = <?=json_encode($transaction_id)?>;

const countdown = document.getElementById('countdown');
const timerBox = document.getElementById('timerBox');
const expiredBox = document.getElementById('expiredBox');
const slip = document.getElementById('slip');
const submitBtn = document.getElementById('submitBtn');
const slipForm = document.getElementById('slipForm');

let expired = false;
let sending = false;

function showTime(sec){
    sec=Math.max(0,sec);
    return String(Math.floor(sec/60)).padStart(2,'0')+':'+
           String(sec%60).padStart(2,'0');
}

function updateCountdown(){
    if(expired)return;

    const left=expireAt-Date.now();
    const sec=Math.max(0,Math.floor(left/1000));

    if(countdown)
        countdown.textContent=showTime(sec);

    if(timerBox){
        timerBox.classList.toggle(
            'warning',
            sec<=30 && sec>0
        );
    }

    if(left<=0)
        expirePayment();
}

function disableForm(){
    if(slip) slip.disabled=true;

    if(submitBtn){
        submitBtn.disabled=true;
        submitBtn.textContent='Payment Expired';
    }
}

function showExpired(message='กำลังยกเลิกรายการ...'){
    if(timerBox)
        timerBox.classList.add('expired');

    if(expiredBox){
        expiredBox.style.display='block';
        expiredBox.innerHTML=
            '<strong>หมดเวลาชำระเงิน</strong><br><br>'+
            escapeHtml(message);
    }
}

async function expirePayment(){

    if(expired || sending)return;

    expired=true;
    sending=true;

    if(countdown)
        countdown.textContent='00:00';

    disableForm();
    showExpired();

    try{

        const r=await fetch(
            'payment_timeout.php',
            {
                method:'POST',
                headers:{
                    'Content-Type':
                        'application/x-www-form-urlencoded',
                    'X-Requested-With':
                        'XMLHttpRequest'
                },
                body:new URLSearchParams({
                    order_id:orderId,
                    type:type,
                    transaction_id:transactionId
                })
            }
        );

        const text=await r.text();

        let data=null;

        try{
            data=JSON.parse(text);
        }catch(e){
            console.error(
                'payment_timeout.php:',
                text
            );
        }

        /* PAID */
        if(data && data.status==='paid'){
            location.href=
                'payment_success.php?'+
                new URLSearchParams({
                    order_id:orderId
                });
            return;
        }

        /* EXPIRED */
        if(
            r.ok &&
            data &&
            (
                data.success===true ||
                data.status==='expired' ||
                data.status==='cancelled'
            )
        ){
            location.href=
                'payment_timeout.php?'+
                new URLSearchParams({
                    order_id:orderId,
                    type:type,
                    transaction_id:transactionId,
                    result:'expired'
                });
            return;
        }

        showExpired(
            data?.message ||
            'ไม่สามารถยกเลิกรายการได้'
        );

    }catch(e){

        console.error(e);

        showExpired(
            'ไม่สามารถเชื่อมต่อระบบได้ กรุณาลองใหม่'
        );
    }
}

function escapeHtml(v){
    return String(v)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#039;');
}

/* SUBMIT */
if(slipForm){

    slipForm.addEventListener(
        'submit',
        function(e){

            if(Date.now()>=expireAt){

                e.preventDefault();
                expirePayment();
                return;
            }

            if(submitBtn){
                submitBtn.disabled=true;
                submitBtn.textContent='Uploading...';
            }
        }
    );
}

/* REFRESH / BACK TO TAB */
document.addEventListener(
    'visibilitychange',
    ()=>{
        if(!document.hidden)
            updateCountdown();
    }
);

window.addEventListener(
    'focus',
    updateCountdown
);

window.addEventListener(
    'pageshow',
    updateCountdown
);

/* START */
updateCountdown();

const timer=setInterval(
    ()=>{
        updateCountdown();

        if(expired)
            clearInterval(timer);
    },
    400
);
</script>

</body>

</html>