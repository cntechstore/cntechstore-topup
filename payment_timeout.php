<?php

error_reporting(0);
require_once "../database.php";

/* =========================================================
   CONFIG
========================================================= */

$tables = [
    'game'    => 'game_orders',
    'voucher' => 'voucher_orders',
    'mobile'  => 'mobile_orders'
];

$EMAILJS_SERVICE  = 'service_064h3l8';
$EMAILJS_TEMPLATE = 'template_d6377sd';
$EMAILJS_PUBLIC   = 'ne4nyDYk-JHf-ufsn';

/* =========================================================
   INPUT
========================================================= */

$order_id = trim($_REQUEST['order_id'] ?? '');

$type = strtolower(
    trim($_REQUEST['type'] ?? '')
);

$transaction_id = trim(
    $_REQUEST['transaction_id'] ?? ''
);

$result = strtolower(
    trim($_GET['result'] ?? '')
);

if ($order_id === '') {
    exit('Missing Order ID');
}

/* =========================================================
   FIND TRANSACTION FIRST
========================================================= */

if ($transaction_id === '') {

    $stmt = $conn->prepare("
        SELECT transaction_id
        FROM payment_transactions
        WHERE order_id = ?
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->bind_param("s", $order_id);
    $stmt->execute();

    $r = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    $transaction_id = $r['transaction_id'] ?? '';
}

if ($transaction_id === '') {
    exit('Transaction not found');
}

/* =========================================================
   GET TRANSACTION
========================================================= */

$stmt = $conn->prepare("
    SELECT *
    FROM payment_transactions
    WHERE order_id = ?
      AND transaction_id = ?
    LIMIT 1
");

$stmt->bind_param(
    "ss",
    $order_id,
    $transaction_id
);

$stmt->execute();

$tx = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$tx) {
    exit('Transaction not found');
}

/* =========================================================
   IMPORTANT:
   CHECK REAL EXPIRATION FROM DATABASE
========================================================= */

$expire_at = strtotime(
    $tx['expire_at'] ?? ''
);

if (!$expire_at || $expire_at > time()) {

    http_response_code(400);

    exit('Payment has not expired');
}

/* =========================================================
   ALREADY PAID
========================================================= */

if (
    strtolower(
        $tx['status'] ?? ''
    ) === 'paid'
) {
    exit('Payment already paid');
}

/* =========================================================
   FIND ORDER
========================================================= */

if (!isset($tables[$type])) {

    $possible = [
        'game_orders',
        'voucher_orders',
        'mobile_orders'
    ];

    foreach ($possible as $t) {

        $stmt = $conn->prepare("
            SELECT *
            FROM `$t`
            WHERE order_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $order_id);
        $stmt->execute();

        $test = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        if ($test) {

            $type = str_replace(
                '_orders',
                '',
                $t
            );

            break;
        }
    }
}

if (!isset($tables[$type])) {
    exit('Invalid order type');
}

$table = $tables[$type];

$stmt = $conn->prepare("
    SELECT *
    FROM `$table`
    WHERE order_id = ?
    LIMIT 1
");

$stmt->bind_param("s", $order_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$order) {
    exit('Order not found');
}

/* =========================================================
   PAID CHECK
========================================================= */

$payment_status = strtolower(
    trim(
        $order['payment_status'] ?? 'pending'
    )
);

if ($payment_status === 'paid') {
    exit('Order already paid');
}

/* =========================================================
   ORDER DATA
========================================================= */

$amount = (float)(
    $order['total']
    ?? $order['amount']
    ?? $order['price']
    ?? $tx['amount']
    ?? 0
);

$customer_name = trim(
    $order['customer_name']
    ?? $order['name']
    ?? $order['username']
    ?? 'Customer'
);

$customer_email = trim(
    $order['email']
    ?? $order['customer_email']
    ?? $order['user_email']
    ?? ''
);

/* =========================================================
   LOCK TRANSACTION
   ป้องกัน Cron + Browser ส่งพร้อมกัน
========================================================= */

$stmt = $conn->prepare("
    UPDATE payment_transactions
    SET status = 'expired'
    WHERE order_id = ?
      AND transaction_id = ?
      AND status = 'pending'
");

$stmt->bind_param(
    "ss",
    $order_id,
    $transaction_id
);

$stmt->execute();

$locked = ($stmt->affected_rows === 1);

$stmt->close();

/*
   ถ้า affected_rows = 0
   แปลว่า Cron/Browser อื่นจัดการไปแล้ว
*/

if (!$locked) {

    if ($result === 'expired') {
        showPage(
            $order_id,
            $type,
            $transaction_id,
            $amount,
            $customer_email,
            false
        );
    }

    exit('Payment already expired');
}

/* =========================================================
   CANCEL ORDER
========================================================= */

$stmt = $conn->prepare("
    UPDATE `$table`
    SET payment_status = 'expired'
    WHERE order_id = ?
      AND payment_status <> 'paid'
");

$stmt->bind_param("s", $order_id);
$stmt->execute();
$stmt->close();

/* status */

$stmt = $conn->prepare("
    UPDATE `$table`
    SET status = 'cancelled'
    WHERE order_id = ?
");

if ($stmt) {
    $stmt->bind_param("s", $order_id);
    @$stmt->execute();
    $stmt->close();
}

/* order_status */

$stmt = $conn->prepare("
    UPDATE `$table`
    SET order_status = 'cancelled'
    WHERE order_id = ?
");

if ($stmt) {
    $stmt->bind_param("s", $order_id);
    @$stmt->execute();
    $stmt->close();
}

/* =========================================================
   EMAIL LOCK
========================================================= */

$email_sent = false;

$stmt = $conn->prepare("
    UPDATE payment_transactions
    SET email_sent = 2
    WHERE order_id = ?
      AND transaction_id = ?
      AND email_sent = 0
");

$stmt->bind_param(
    "ss",
    $order_id,
    $transaction_id
);

$stmt->execute();

$can_send = ($stmt->affected_rows === 1);

$stmt->close();

/*
email_sent:

0 = ยังไม่ส่ง
1 = ส่งสำเร็จ
2 = กำลังส่ง
*/

/* =========================================================
   SEND EMAILJS
========================================================= */

if (
    $can_send &&
    filter_var(
        $customer_email,
        FILTER_VALIDATE_EMAIL
    )
) {

    $params = [

        'customer_email' =>
            $customer_email,

        'customer_name' =>
            $customer_name,

        'order_id' =>
            $order_id,

        'type' =>
            strtoupper($type),

        'transaction_id' =>
            $transaction_id,

        'amount' =>
            number_format(
                $amount,
                2
            ) . ' LAK',

        'status' =>
            'CANCELLED - PAYMENT TIMEOUT',

        'date' =>
            date('d/m/Y H:i:s')
    ];

    $payload = [

        'service_id' =>
            $EMAILJS_SERVICE,

        'template_id' =>
            $EMAILJS_TEMPLATE,

        'user_id' =>
            $EMAILJS_PUBLIC,

        'template_params' =>
            $params
    ];

    $ch = curl_init(
        'https://api.emailjs.com/api/v1.0/email/send'
    );

    curl_setopt_array($ch, [

        CURLOPT_POST =>
            true,

        CURLOPT_RETURNTRANSFER =>
            true,

        CURLOPT_TIMEOUT =>
            20,

        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],

        CURLOPT_POSTFIELDS =>
            json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE
            )
    ]);

    curl_exec($ch);

    $http = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    if ($http >= 200 && $http < 300) {

        $email_sent = true;

        $stmt = $conn->prepare("
            UPDATE payment_transactions
            SET email_sent = 1
            WHERE order_id = ?
              AND transaction_id = ?
        ");

        $stmt->bind_param(
            "ss",
            $order_id,
            $transaction_id
        );

        $stmt->execute();
        $stmt->close();

    } else {

        /*
        ส่งไม่สำเร็จ
        อนุญาตให้ Cron รอบถัดไปลองใหม่
        */

        $stmt = $conn->prepare("
            UPDATE payment_transactions
            SET email_sent = 0
            WHERE order_id = ?
              AND transaction_id = ?
        ");

        $stmt->bind_param(
            "ss",
            $order_id,
            $transaction_id
        );

        $stmt->execute();
        $stmt->close();
    }
}

/* =========================================================
   API RESPONSE
========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header(
        'Content-Type: application/json; charset=utf-8'
    );

    echo json_encode([
        'success' => true,
        'message' => 'Payment expired',
        'status' => 'expired',
        'order_id' => $order_id,
        'transaction_id' => $transaction_id,
        'email_sent' => $email_sent ? 1 : 0
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

/* =========================================================
   PAGE
========================================================= */

if ($result !== 'expired') {
    exit('Invalid Request');
}

showPage(
    $order_id,
    $type,
    $transaction_id,
    $amount,
    $customer_email,
    $email_sent
);

/* =========================================================
   PAGE FUNCTION
========================================================= */

function showPage(
    $order_id,
    $type,
    $transaction_id,
    $amount,
    $email,
    $email_sent
) {
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width,initial-scale=1"
>

<title>Payment Expired - CNTECH STORE</title>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<style>

*{
    box-sizing:border-box
}

body{
    margin:0;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:16px;
    background:#050505;
    color:#fff;
    font-family:Arial,sans-serif
}

.card{
    width:100%;
    max-width:460px;
    padding:28px 20px;
    background:#111;
    border:1px solid #292929;
    border-radius:24px;
    text-align:center;
    box-shadow:0 20px 60px #000
}

.logo{
    font-size:25px;
    font-weight:900
}

.logo span{
    color:#ff2020
}

.tagline{
    margin:5px 0 25px;
    color:#777;
    font-size:12px
}

.icon{
    width:76px;
    height:76px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background:#350909;
    border:1px solid #8c1e1e;
    color:#ff3030;
    font-size:34px
}

h1{
    margin:18px 0 7px
}

.red{
    color:#ff3030;
    font-weight:bold
}

.text{
    margin:14px 0 20px;
    color:#999;
    line-height:1.7;
    font-size:14px
}

.details{
    padding:12px 15px;
    background:#181818;
    border-radius:15px;
    text-align:left
}

.row{
    display:flex;
    justify-content:space-between;
    gap:15px;
    padding:11px 0;
    border-bottom:1px solid #292929
}

.row:last-child{
    border:0
}

.label{
    color:#888;
    font-size:13px
}

.value{
    max-width:65%;
    text-align:right;
    font-size:13px;
    font-weight:bold;
    word-break:break-word
}

.amount{
    color:#ff3030
}

.notice{
    margin-top:18px;
    padding:13px;
    border-radius:12px;
    background:#19100f;
    border:1px solid #38201e;
    color:#aaa;
    font-size:12px;
    line-height:1.6
}

.email{
    margin-top:13px;
    color:#36d67a;
    font-size:12px
}

.btn{
    display:block;
    margin-top:20px;
    padding:14px;
    border-radius:13px;
    background:#e51b23;
    color:#fff;
    text-decoration:none;
    font-weight:bold
}

</style>
</head>

<body>

<div class="card">

<div class="logo">
CN<span>TECH</span> STORE
</div>

<div class="tagline">
Computer • Mobile • Parts & Accessories
</div>

<div class="icon">
<i class="fa-solid fa-xmark"></i>
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
<div class="label">Order ID</div>
<div class="value">
<?=e($order_id)?>
</div>
</div>

<div class="row">
<div class="label">Product Type</div>
<div class="value">
<?=e(strtoupper($type))?>
</div>
</div>

<div class="row">
<div class="label">Transaction</div>
<div class="value">
<?=e($transaction_id)?>
</div>
</div>

<div class="row">
<div class="label">Amount</div>
<div class="value amount">
<?=number_format($amount,2)?> LAK
</div>
</div>

</div>

<div class="notice">
<i class="fa-solid fa-circle-info"></i>
Payment was not completed before the deadline.
Please create a new order if you still wish to purchase.
</div>

<?php if ($email_sent || $email): ?>

<div class="email">

<i class="fa-solid fa-circle-check"></i>

Payment notification sent

</div>

<?php endif; ?>

<a href="/" class="btn">
<i class="fa-solid fa-arrow-left"></i>
&nbsp; Back to CNTECH STORE
</a>

</div>

</body>
</html>
<?php
}

function e($v)
{
    return htmlspecialchars(
        (string)$v,
        ENT_QUOTES,
        'UTF-8'
    );
}