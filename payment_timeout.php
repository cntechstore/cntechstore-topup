<?php
error_reporting(0);
ini_set('display_errors', '0');

require_once "../database.php";

/*
=========================================================
 CNTECH STORE
 PAYMENT TIMEOUT
 - Expire Order
 - Expire Transaction
 - EmailJS REST
 - email_sent = 1 = ห้ามส่งซ้ำ
=========================================================
*/

/* ========================================================
   CONFIG
======================================================== */

$TABLES = [
    'game'    => 'game_orders',
    'voucher' => 'voucher_orders',
    'mobile'  => 'mobile_orders'
];

/* EmailJS */
$EMAILJS_SERVICE  = 'service_064h3l8';
$EMAILJS_TEMPLATE = 'template_z6eel19';
$EMAILJS_PUBLIC   = 'zPnQ14dGWHb6MZTr5';

/*
 * ใส่ Private Key ของ EmailJS
 * ห้ามใส่ใน JavaScript
 */
$EMAILJS_PRIVATE = '4ZtigZ9sIpdCrny28axfM';


/* ========================================================
   HELPERS
======================================================== */

function h($v)
{
    return htmlspecialchars(
        (string)$v,
        ENT_QUOTES,
        'UTF-8'
    );
}

function json_response($data, $code = 200)
{
    http_response_code($code);

    header(
        'Content-Type: application/json; charset=utf-8'
    );

    echo json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


/* ========================================================
   INPUT
======================================================== */

$order_id = trim(
    $_REQUEST['order_id'] ?? ''
);

$type = strtolower(
    trim($_REQUEST['type'] ?? '')
);

$tx_id = trim(
    $_REQUEST['transaction_id'] ?? ''
);

$api =
    isset($_REQUEST['api']) ||
    $_SERVER['REQUEST_METHOD'] === 'POST';


/* ========================================================
   VALIDATE
======================================================== */

if (
    $order_id === '' ||
    !isset($TABLES[$type])
) {

    if ($api) {

        json_response([
            'success' => false,
            'message' => 'Invalid request'
        ], 400);

    }

    exit('Invalid Request');
}

$table = $TABLES[$type];


/* ========================================================
   FIND ORDER
======================================================== */

$stmt = $conn->prepare(
    "SELECT *
     FROM `$table`
     WHERE order_id = ?
     LIMIT 1"
);

if (!$stmt) {

    if ($api) {

        json_response([
            'success' => false,
            'message' => 'Database error'
        ], 500);

    }

    exit('Database error');
}

$stmt->bind_param(
    "s",
    $order_id
);

$stmt->execute();

$order =
    $stmt
        ->get_result()
        ->fetch_assoc();

$stmt->close();


if (!$order) {

    if ($api) {

        json_response([
            'success' => false,
            'message' => 'Order not found'
        ], 404);

    }

    exit('Order not found');
}


/* ========================================================
   ORDER DATA
======================================================== */

$amount = (float)(
    $order['total']
    ?? $order['amount']
    ?? $order['price']
    ?? 0
);

$email = trim(
    $order['email']
    ?? $order['customer_email']
    ?? $order['user_email']
    ?? ''
);

$name = trim(
    $order['customer_name']
    ?? $order['name']
    ?? $order['username']
    ?? 'Customer'
);

$payment_status = strtolower(
    trim(
        $order['payment_status']
        ?? 'pending'
    )
);


/* ========================================================
   NEVER TOUCH PAID ORDER
======================================================== */

if ($payment_status === 'paid') {

    if ($api) {

        json_response([
            'success' => false,
            'message' => 'Order already paid',
            'status' => 'paid',
            'order_id' => $order_id
        ]);
    }

    exit('Order already paid');
}


/* ========================================================
   FIND TRANSACTION
======================================================== */

$transaction = null;


/* Specific transaction */

if ($tx_id !== '') {

    $stmt = $conn->prepare(
        "SELECT *
         FROM payment_transactions
         WHERE order_id = ?
         AND transaction_id = ?
         LIMIT 1"
    );

    if ($stmt) {

        $stmt->bind_param(
            "ss",
            $order_id,
            $tx_id
        );

        $stmt->execute();

        $transaction =
            $stmt
                ->get_result()
                ->fetch_assoc();

        $stmt->close();
    }
}


/* Latest transaction */

if (!$transaction) {

    $stmt = $conn->prepare(
        "SELECT *
         FROM payment_transactions
         WHERE order_id = ?
         ORDER BY id DESC
         LIMIT 1"
    );

    if ($stmt) {

        $stmt->bind_param(
            "s",
            $order_id
        );

        $stmt->execute();

        $transaction =
            $stmt
                ->get_result()
                ->fetch_assoc();

        $stmt->close();
    }
}


/* ========================================================
   TRANSACTION REQUIRED
======================================================== */

if (!$transaction) {

    if ($api) {

        json_response([
            'success' => false,
            'message' => 'Payment transaction not found',
            'order_id' => $order_id
        ], 404);

    }

    exit('Payment transaction not found');
}


/* ========================================================
   TRANSACTION DATA
======================================================== */

$tx_id =
    $transaction['transaction_id'];

$tx_status = strtolower(
    trim(
        $transaction['status']
        ?? 'pending'
    )
);

$email_sent = (int)(
    $transaction['email_sent']
    ?? 0
);


/* ========================================================
   NEVER EXPIRE PAID TRANSACTION
======================================================== */

if ($tx_status === 'paid') {

    if ($api) {

        json_response([
            'success' => false,
            'message' => 'Transaction already paid',
            'status' => 'paid',
            'order_id' => $order_id,
            'transaction_id' => $tx_id
        ]);
    }

    exit('Transaction already paid');
}


/* ========================================================
   EXPIRE ORDER
======================================================== */

$stmt = $conn->prepare(
    "UPDATE `$table`
     SET payment_status = 'expired'
     WHERE order_id = ?
     AND payment_status <> 'paid'"
);

if ($stmt) {

    $stmt->bind_param(
        "s",
        $order_id
    );

    $stmt->execute();

    $stmt->close();
}


/* ========================================================
   CANCEL STATUS
======================================================== */

/* status */

$stmt = $conn->prepare(
    "UPDATE `$table`
     SET status = 'cancelled'
     WHERE order_id = ?
     AND payment_status = 'expired'"
);

if ($stmt) {

    $stmt->bind_param(
        "s",
        $order_id
    );

    @$stmt->execute();

    $stmt->close();
}


/* order_status */

$stmt = $conn->prepare(
    "UPDATE `$table`
     SET order_status = 'cancelled'
     WHERE order_id = ?
     AND payment_status = 'expired'"
);

if ($stmt) {

    $stmt->bind_param(
        "s",
        $order_id
    );

    @$stmt->execute();

    $stmt->close();
}


/* ========================================================
   EXPIRE TRANSACTION
======================================================== */

$stmt = $conn->prepare(
    "UPDATE payment_transactions
     SET status = 'expired'
     WHERE order_id = ?
     AND transaction_id = ?
     AND status <> 'paid'"
);

if ($stmt) {

    $stmt->bind_param(
        "ss",
        $order_id,
        $tx_id
    );

    $stmt->execute();

    $stmt->close();
}


/* ========================================================
   EMAIL
======================================================== */

$email_ok = false;
$email_error = '';

/*
---------------------------------------------------------
ส่งเฉพาะเมื่อ email_sent = 0
---------------------------------------------------------
*/

if (
    $email_sent === 0 &&
    filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {

    /*
    -----------------------------------------------------
    ตรวจ Private Key
    -----------------------------------------------------
    */

    if (
        $EMAILJS_PRIVATE === '' ||
        $EMAILJS_PRIVATE === 'YOUR_PRIVATE_KEY'
    ) {

        $email_error =
            'EmailJS Private Key is not configured';

    } else {

        /*
        -------------------------------------------------
        EMAIL PARAMS
        -------------------------------------------------
        */

        $params = [

            'customer_email' =>
                $email,

            'customer_name' =>
                $name,

            'order_id' =>
                $order_id,

            'type' =>
                strtoupper($type),

            'transaction_id' =>
                $tx_id,

            'amount' =>
                number_format(
                    $amount,
                    2
                ) . ' LAK',

            'status' =>
                'CANCELLED - PAYMENT TIMEOUT',

            'date' =>
                date(
                    'd/m/Y H:i:s'
                )
        ];


        /*
        -------------------------------------------------
        EMAILJS PAYLOAD
        -------------------------------------------------
        */

        $payload = [

            'service_id' =>
                $EMAILJS_SERVICE,

            'template_id' =>
                $EMAILJS_TEMPLATE,

            'user_id' =>
                $EMAILJS_PUBLIC,

            'accessToken' =>
                $EMAILJS_PRIVATE,

            'template_params' =>
                $params
        ];


        /*
        -------------------------------------------------
        SEND
        -------------------------------------------------
        */

        $ch = curl_init(
            'https://api.emailjs.com/api/v1.0/email/send'
        );

        curl_setopt_array(
            $ch,
            [

                CURLOPT_POST =>
                    true,

                CURLOPT_RETURNTRANSFER =>
                    true,

                CURLOPT_CONNECTTIMEOUT =>
                    10,

                CURLOPT_TIMEOUT =>
                    20,

                CURLOPT_HTTPHEADER =>
                    [
                        'Content-Type: application/json'
                    ],

                CURLOPT_POSTFIELDS =>
                    json_encode(
                        $payload,
                        JSON_UNESCAPED_UNICODE
                    )
            ]
        );


        $response =
            curl_exec($ch);

        $http =
            curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );

        $curl_error =
            curl_error($ch);

        curl_close($ch);


        /*
        -------------------------------------------------
        SUCCESS
        -------------------------------------------------
        */

        if (
            $http >= 200 &&
            $http < 300
        ) {

            $email_ok = true;

            /*
            -------------------------------------------------
            LOCK
            -------------------------------------------------

            สำเร็จเท่านั้น → email_sent = 1

            รอบต่อไปจะไม่ส่งซ้ำ
            */

            $stmt = $conn->prepare(
                "UPDATE payment_transactions
                 SET email_sent = 1
                 WHERE order_id = ?
                 AND transaction_id = ?
                 AND email_sent = 0"
            );

            if ($stmt) {

                $stmt->bind_param(
                    "ss",
                    $order_id,
                    $tx_id
                );

                $stmt->execute();

                $stmt->close();
            }

            $email_sent = 1;

        } else {

            /*
            -------------------------------------------------
            FAILED
            -------------------------------------------------

            email_sent ยังเป็น 0
            เพื่อให้ Cron retry ได้
            */

            $email_error =
                $curl_error
                ?: 'EmailJS HTTP ' . $http;

            if (
                !$curl_error &&
                $response
            ) {

                $email_error .=
                    ' - ' . trim($response);
            }
        }
    }
}


/* ========================================================
   API RESPONSE
======================================================== */

if ($api) {

    json_response([

        'success' =>
            true,

        'status' =>
            'expired',

        'order_id' =>
            $order_id,

        'transaction_id' =>
            $tx_id,

        'email_sent' =>
            $email_sent,

        'email_error' =>
            $email_error

    ]);
}


/* ========================================================
   PAGE
======================================================== */

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width,initial-scale=1"
>

<title>
Payment Expired - CNTECH STORE
</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    min-height:100vh;
    padding:15px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:#050505;

    color:#fff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;
}

.card{
    width:100%;
    max-width:460px;

    padding:28px 20px;

    background:#111;

    border:1px solid #292929;

    border-radius:22px;

    text-align:center;

    box-shadow:
        0 20px 60px #000;
}

.logo{
    font-size:25px;
    font-weight:900;
}

.logo span{
    color:#ff2020;
}

.sub{
    margin:5px 0 25px;

    color:#777;

    font-size:12px;
}

.icon{
    width:75px;
    height:75px;

    margin:auto;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:50%;

    background:#350909;

    border:1px solid #8c1e1e;

    color:#ff3030;

    font-size:34px;
}

h1{
    margin:18px 0 7px;

    font-size:25px;
}

.red{
    color:#ff3030;

    font-weight:bold;
}

.text{
    margin:14px 0 20px;

    color:#999;

    line-height:1.7;

    font-size:14px;
}

.details{
    background:#181818;

    border:1px solid #292929;

    border-radius:15px;

    padding:8px 15px;

    text-align:left;
}

.row{
    display:flex;

    justify-content:space-between;

    gap:10px;

    padding:11px 0;

    border-bottom:1px solid #292929;
}

.row:last-child{
    border:0;
}

.label{
    color:#888;

    font-size:13px;
}

.value{
    max-width:65%;

    text-align:right;

    font-size:13px;

    font-weight:bold;

    word-break:break-word;
}

.amount{
    color:#ff3030;
}

.notice{
    margin-top:18px;

    padding:13px;

    border-radius:12px;

    background:#19100f;

    border:1px solid #38201e;

    color:#aaa;

    font-size:12px;

    line-height:1.6;
}

.mail{
    margin-top:13px;

    font-size:12px;

    line-height:1.5;
}

.ok{
    color:#36d67a;
}

.err{
    color:#ff5555;
}

.btn{
    display:block;

    margin-top:20px;

    padding:14px;

    border-radius:13px;

    background:#e51b23;

    color:#fff;

    text-decoration:none;

    font-weight:bold;
}

</style>

</head>

<body>

<div class="card">

<div class="logo">
CN<span>TECH</span> STORE
</div>

<div class="sub">
Computer • Mobile • Parts & Accessories
</div>

<div class="icon">
✕
</div>

<h1>
Payment Expired
</h1>

<div class="red">
ລາຍການຖືກຍົກເລີກ
</div>

<div class="text">
Your payment time has expired.
<br>
The order was automatically cancelled.
</div>

<div class="details">

<div class="row">

<div class="label">
Order ID
</div>

<div class="value">
<?=h($order_id)?>
</div>

</div>

<div class="row">

<div class="label">
Product Type
</div>

<div class="value">
<?=h(strtoupper($type))?>
</div>

</div>

<div class="row">

<div class="label">
Transaction
</div>

<div class="value">
<?=h($tx_id)?>
</div>

</div>

<div class="row">

<div class="label">
Amount
</div>

<div class="value amount">
<?=number_format($amount,2)?> LAK
</div>

</div>

</div>

<div class="notice">

Payment was not completed before the deadline.
<br>
Please create a new order if you still wish to purchase.

</div>

<?php if ($email_sent === 1): ?>

<div class="mail ok">

✓ Payment notification sent

</div>

<?php elseif ($email_error !== ''): ?>

<div class="mail err">

✕ Payment notification could not be sent.

</div>

<?php elseif (!filter_var($email,FILTER_VALIDATE_EMAIL)): ?>

<div class="mail err">

✕ Customer email is invalid or unavailable.

</div>

<?php else: ?>

<div class="mail">

Payment notification is being processed.

</div>

<?php endif; ?>

<a
    href="/"
    class="btn"
>
← Back to CNTECH STORE
</a>

</div>

</body>

</html>