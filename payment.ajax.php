<?php

/*
=========================================================
CN TECH STORE
PAYMENT AJAX API
CN COINS ONLY
=========================================================

FLOW

coin_create.php
        ↓
coin_create.process.php
        ↓
/api/payment_ajax.php
        ↓
/payment.php?order_id=XXX&type=coins

รองรับ:
- JSON POST
- application/x-www-form-urlencoded
- CN Coins เท่านั้น
- create_coin_payment
- ตรวจสอบ login
- ตรวจสอบ order
- ตรวจสอบ owner
- ตรวจสอบยอดเงิน
- ป้องกันจ่ายซ้ำ
- สร้าง transaction_id
- ส่ง redirect กลับ
=========================================================
*/

error_reporting(E_ALL);
ini_set('display_errors', '0');

session_start();

header(
    'Content-Type: application/json; charset=utf-8'
);


// =====================================================
// LOAD DATABASE
// =====================================================

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/database.php';


// =====================================================
// JSON RESPONSE
// =====================================================

function response_json(
    bool $success,
    string $message = '',
    array $extra = [],
    int $httpCode = 200
) {

    http_response_code($httpCode);

    $response = array_merge(

        [
            'success' => $success,
            'message' => $message
        ],

        $extra

    );

    echo json_encode(

        $response,

        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES

    );

    exit;

}


// =====================================================
// ONLY POST
// =====================================================

if (
    $_SERVER['REQUEST_METHOD']
    !==
    'POST'
) {

    response_json(

        false,

        'Invalid Request Method',

        [],

        405

    );

}


// =====================================================
// GET LOGIN USER
// =====================================================

if (
    !isset($_SESSION['user_id'])
) {

    response_json(

        false,

        'กรุณาเข้าสู่ระบบก่อนชำระเงิน',

        [],

        401

    );

}

$session_user_id =
    (int)$_SESSION['user_id'];


// =====================================================
// READ REQUEST
// =====================================================

$raw =
    file_get_contents(
        'php://input'
    );

$request = [];


// -----------------------------------------------------
// JSON
// -----------------------------------------------------

if (
    $raw !== ''
) {

    $json =
        json_decode(
            $raw,
            true
        );

    if (
        is_array($json)
    ) {

        $request = $json;

    }

}


// -----------------------------------------------------
// FALLBACK POST
// -----------------------------------------------------

if (
    empty($request)
    &&
    !empty($_POST)
) {

    $request = $_POST;

}


// =====================================================
// ACTION
// =====================================================

$action =
    trim(
        (string)(
            $request['action']
            ?? ''
        )
    );


// =====================================================
// CN COINS ONLY
// =====================================================

if (
    $action
    !==
    'create_coin_payment'
) {

    response_json(

        false,

        'Invalid CN Coins Payment Action',

        [

            'allowed_action' =>
                'create_coin_payment'

        ],

        400

    );

}


// =====================================================
// ORDER ID
// =====================================================

$order_id =
    trim(
        (string)(
            $request['order_id']
            ?? ''
        )
    );


if (
    $order_id === ''
) {

    response_json(

        false,

        'Missing Order ID',

        [],

        400

    );

}


// =====================================================
// ORDER ID FORMAT
// =====================================================

if (
    !preg_match(
        '/^COIN_[0-9]{14}[0-9]{6}$/',
        $order_id
    )
) {

    response_json(

        false,

        'Invalid CN Coins Order ID',

        [],

        400

    );

}


// =====================================================
// PAYMENT METHOD
// =====================================================

$payment_method =
    strtolower(
        trim(
            (string)(
                $request['payment_method']
                ?? 'qr'
            )
        )
    );


if (
    $payment_method
    !==
    'qr'
) {

    response_json(

        false,

        'CN Coins รองรับการชำระผ่าน QR เท่านั้น',

        [],

        400

    );

}


// =====================================================
// GET COIN ORDER
// =====================================================

$stmt =
    $conn->prepare("

        SELECT

            id,
            order_id,
            user_id,
            coins,
            amount,
            payment_method,
            payment_status,
            status,
            transaction_id,
            created_at

        FROM coin_orders

        WHERE order_id = ?

        LIMIT 1

    ");


if (!$stmt) {

    response_json(

        false,

        'Database Error',

        [],

        500

    );

}


$stmt->bind_param(
    's',
    $order_id
);


if (
    !$stmt->execute()
) {

    $stmt->close();

    response_json(

        false,

        'ไม่สามารถอ่านรายการ CN Coins ได้',

        [],

        500

    );

}


$result =
    $stmt->get_result();


$order =
    $result->fetch_assoc();


$stmt->close();


// =====================================================
// ORDER NOT FOUND
// =====================================================

if (!$order) {

    response_json(

        false,

        'ไม่พบรายการ CN Coins',

        [],

        404

    );

}


// =====================================================
// OWNER CHECK
// =====================================================

$order_user_id =
    (int)$order['user_id'];


if (
    $order_user_id
    !==
    $session_user_id
) {

    response_json(

        false,

        'คุณไม่มีสิทธิ์ชำระรายการนี้',

        [],

        403

    );

}


// =====================================================
// COINS
// =====================================================

$coins =
    (int)$order['coins'];


if (
    $coins <= 0
) {

    response_json(

        false,

        'จำนวน CN Coins ไม่ถูกต้อง',

        [],

        400

    );

}


// =====================================================
// AMOUNT
// =====================================================

$amount =
    (int)$order['amount'];


if (
    $amount <= 0
) {

    response_json(

        false,

        'จำนวนเงินไม่ถูกต้อง',

        [],

        400

    );

}


// =====================================================
// PRICE VALIDATION
//
// 1 CN Coin = 1,000 LAK
// =====================================================

$expected_amount =
    $coins * 1000;


if (
    $amount
    !==
    $expected_amount
) {

    response_json(

        false,

        'ยอดเงิน CN Coins ไม่ถูกต้อง',

        [

            'coins' =>
                $coins,

            'amount' =>
                $amount,

            'expected_amount' =>
                $expected_amount

        ],

        400

    );

}


// =====================================================
// CHECK PAID
// =====================================================

$payment_status =
    strtolower(
        trim(
            (string)(
                $order['payment_status']
                ?? ''
            )
        )
    );


if (
    $payment_status
    ===
    'paid'
) {

    response_json(

        false,

        'รายการนี้ชำระเงินแล้ว',

        [

            'order_id' =>
                $order_id

        ],

        400

    );

}


// =====================================================
// CHECK EXISTING TRANSACTION
// =====================================================

$existing_transaction =
    trim(
        (string)(
            $order['transaction_id']
            ?? ''
        )
    );


/*
---------------------------------------------------------
ถ้ามี transaction เดิม
ให้ใช้ transaction เดิมแทนการสร้างซ้ำ
---------------------------------------------------------
*/

if (
    $existing_transaction !== ''
) {

    $transaction_id =
        $existing_transaction;

} else {

    /*
    =====================================================
    CREATE TRANSACTION ID
    =====================================================
    */

    $transaction_id =

        'CN'
        . date('YmdHis')
        . random_int(
            1000,
            9999
        );

}


// =====================================================
// UPDATE ORDER
// =====================================================

$stmt =
    $conn->prepare("

        UPDATE coin_orders

        SET

            payment_method = 'qr',

            payment_status = 'pending',

            status = 'pending',

            transaction_id = ?

        WHERE

            order_id = ?

            AND user_id = ?

            AND payment_status <> 'paid'

        LIMIT 1

    ");


if (!$stmt) {

    response_json(

        false,

        'ไม่สามารถเตรียมข้อมูล Payment ได้',

        [],

        500

    );

}


$stmt->bind_param(

    'ssi',

    $transaction_id,

    $order_id,

    $session_user_id

);


if (
    !$stmt->execute()
) {

    $error =
        $stmt->error;

    $stmt->close();

    response_json(

        false,

        'ไม่สามารถสร้างรายการ Payment ได้',

        [

            'error' =>
                $error

        ],

        500

    );

}


$stmt->close();


// =====================================================
// CHECK UPDATE RESULT
// =====================================================

/*
ถ้า transaction เดิมไม่มีปัญหา
แต่ UPDATE ไม่พบ row อาจเกิดจาก order ถูกเปลี่ยนสถานะ
*/

if (
    isset($stmt)
) {

    // intentionally empty

}


// =====================================================
// PAYMENT PAGE
// =====================================================

/*
---------------------------------------------------------
เปลี่ยนตรงนี้ได้ หากหน้า Gateway ของคุณชื่อไฟล์อื่น
---------------------------------------------------------
*/

$payment_url =

    '/payment.php'
    . '?order_id='
    . rawurlencode(
        $order_id
    )
    . '&type=coins';


// =====================================================
// FINAL RESPONSE
// =====================================================

response_json(

    true,

    'สร้างรายการชำระเงิน CN Coins สำเร็จ',

    [

        'order_id' =>
            $order_id,

        'transaction_id' =>
            $transaction_id,

        'coins' =>
            $coins,

        'amount' =>
            $amount,

        'payment_method' =>
            'qr',

        'payment_status' =>
            'pending',

        'redirect' =>
            $payment_url,

        'payment_url' =>
            $payment_url,

        'data' => [

            'order_id' =>
                $order_id,

            'transaction_id' =>
                $transaction_id,

            'coins' =>
                $coins,

            'amount' =>
                $amount,

            'payment_url' =>
                $payment_url,

            'redirect' =>
                $payment_url

        ]

    ],

    200

);