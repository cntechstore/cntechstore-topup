<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header("Content-Type: application/json; charset=utf-8");

require "config.php";
require "database.php";


/*
==================================================
CHECK LOGIN
==================================================
*/

if (!isset($_SESSION['user_id'])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "กรุณาเข้าสู่ระบบก่อน"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


$user_id = (int)$_SESSION['user_id'];


/*
==================================================
GET INPUT
==================================================
*/

$input = json_decode(
    file_get_contents("php://input"),
    true
);

if (!is_array($input)) {
    $input = $_POST;
}


$order_id = trim(
    $input['order_id'] ?? ''
);

$payment_method = strtolower(
    trim(
        $input['payment_method'] ?? 'qr'
    )
);


/*
==================================================
VALIDATE
==================================================
*/

if ($order_id === '') {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "ไม่พบ Order ID"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


if ($payment_method !== 'qr') {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "รองรับเฉพาะ QR Payment"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
GET CN COINS ORDER
==================================================
*/

$stmt = $conn->prepare("
    SELECT
        id,
        order_id,
        user_id,
        coins,
        amount,
        payment_status,
        transaction_id
    FROM coin_orders
    WHERE order_id = ?
    LIMIT 1
");


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database Error"
    ], JSON_UNESCAPED_UNICODE);

    exit;
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
==================================================
ORDER NOT FOUND
==================================================
*/

if (!$order) {

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "ไม่พบ CN Coins Order"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
CHECK OWNER
==================================================
*/

if (
    (int)$order['user_id']
    !==
    $user_id
) {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "คุณไม่มีสิทธิ์ชำระ Order นี้"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
CHECK PAYMENT STATUS
==================================================
*/

if (
    strtolower(
        trim(
            $order['payment_status'] ?? ''
        )
    ) === 'paid'
) {

    echo json_encode([
        "success" => false,
        "message" => "Order นี้ชำระเงินแล้ว"
    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
PREPARE DATA FOR PAYMENT API
==================================================
*/

$payment_data = [

    "action" =>
        "create_coin_payment",

    "order_id" =>
        $order['order_id'],

    "payment_method" =>
        "qr",

    "user_id" =>
        $user_id,

    "coins" =>
        (int)$order['coins'],

    "amount" =>
        (float)$order['amount']

];


/*
==================================================
CALL PAYMENT AJAX
==================================================
*/

$payment_url =
    "https://cntechstore.shop/api/payment.ajax.php";


$ch = curl_init(
    $payment_url
);


curl_setopt_array($ch, [

    CURLOPT_POST =>
        true,

    CURLOPT_RETURNTRANSFER =>
        true,

    CURLOPT_HTTPHEADER => [

        "Content-Type: application/json",

        "Accept: application/json"

    ],

    CURLOPT_POSTFIELDS =>
        json_encode(
            $payment_data,
            JSON_UNESCAPED_UNICODE
        ),

    CURLOPT_TIMEOUT =>
        30,

    CURLOPT_CONNECTTIMEOUT =>
        10

]);


$response = curl_exec($ch);

$curl_error = curl_error($ch);

$http_code =
    curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );


curl_close($ch);


/*
==================================================
CURL ERROR
==================================================
*/

if ($response === false || $curl_error !== '') {

    http_response_code(502);

    echo json_encode([

        "success" => false,

        "message" =>
            "ไม่สามารถเชื่อมต่อ Payment API",

        "error" =>
            $curl_error

    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
PARSE PAYMENT RESPONSE
==================================================
*/

$payment_result =
    json_decode(
        $response,
        true
    );


if (!is_array($payment_result)) {

    http_response_code(502);

    echo json_encode([

        "success" => false,

        "message" =>
            "Payment API ส่งข้อมูลไม่ถูกต้อง",

        "http_code" =>
            $http_code,

        "response" =>
            $response

    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
PAYMENT FAILED
==================================================
*/

if (
    !isset($payment_result['success'])
    ||
    !$payment_result['success']
) {

    http_response_code(
        $http_code >= 400
            ? $http_code
            : 400
    );

    echo json_encode([

        "success" => false,

        "message" =>
            $payment_result['message']
            ??
            "ไม่สามารถสร้างรายการ Payment ได้",

        "data" =>
            $payment_result

    ], JSON_UNESCAPED_UNICODE);

    exit;
}


/*
==================================================
SUCCESS
==================================================
*/

echo json_encode([

    "success" => true,

    "message" =>
        "สร้างรายการชำระเงินสำเร็จ",

    "order_id" =>
        $order['order_id'],

    "coins" =>
        (int)$order['coins'],

    "amount" =>
        (float)$order['amount'],

    "payment" =>
        $payment_result

], JSON_UNESCAPED_UNICODE);

exit;