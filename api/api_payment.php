<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);


header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../database.php";

/*
========================
READ INPUT
========================
*/
$data = json_decode(file_get_contents("php://input"), true);

$order_id = trim($data['order_id'] ?? '');
$bank     = trim($data['bank'] ?? '');
$type     = strtolower(trim($data['type'] ?? 'shop'));

if(!$order_id || !$bank){
    echo json_encode([
        "status" => "error",
        "message" => "Missing order_id or bank"
    ]);
    exit;
}

/*
========================
TABLE MAP
========================
*/
$tables = [
    "shop"    => "shop_orders",
    "game"    => "game_orders",
    "mobile"  => "mobile_orders",
    "voucher" => "voucher_orders"
];

$table = $tables[$type] ?? null;

if(!$table){
    echo json_encode([
        "status" => "error",
        "message" => "Invalid type"
    ]);
    exit;
}

/*
========================
GET ORDER
========================
*/
$stmt = $conn->prepare("
    SELECT *
    FROM {$table}
    WHERE order_id=?
    LIMIT 1
");

$stmt->bind_param("s", $order_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

if(!$order){
    echo json_encode([
        "status" => "error",
        "message" => "Order not found in {$table}"
    ]);
    exit;
}

/*
========================
GET AMOUNT SAFE
========================
*/
$amount =
    $order['amount']
    ?? $order['total']
    ?? $order['price']
    ?? 0;

$amount = floatval($amount);

if($amount <= 0){
    echo json_encode([
        "status" => "error",
        "message" => "Invalid amount"
    ]);
    exit;
}

/*
========================
TRANSACTION ID
========================
*/
$transaction_id = "TX" . date("YmdHis") . rand(1000,9999);
$expire_time = time() + 300;

/*
========================
BANK API PAYLOAD
========================
*/
$payload = [
    "merchant_id" => "CNTECH001",
    "bank" => $bank,
    "type" => $type,
    "order_id" => $order_id,
    "transaction_id" => $transaction_id,
    "amount" => $amount,
    "currency" => "LAK",
    "callback_url" => "https://cntechstore.shop/webhook.php",
    "expire" => $expire_time
];

/*
========================
CALL BANK API (REAL)
========================
*/
$bank_url = "https://bank-api.example.com/create-qr"; // เปลี่ยนจริงทีหลัง

$ch = curl_init($bank_url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer YOUR_API_KEY"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

/*
========================
LOG (IMPORTANT ON HOSTING)
========================
*/
file_put_contents(
    "payment_log.txt",
    date("Y-m-d H:i:s") . " | " . json_encode([
        "req" => $payload,
        "res" => $response,
        "http" => $http_code,
        "err" => $error
    ]) . PHP_EOL,
    FILE_APPEND
);

/*
========================
FALLBACK QR
========================
*/
$bank_result = json_decode($response, true);

if($http_code !== 200 || !$bank_result){

    $qr_string = "PAY|$transaction_id|$order_id|$amount";

}else{

    $qr_string =
        $bank_result['qr']
        ?? $bank_result['qr_url']
        ?? $bank_result['data']
        ?? "PAY|$transaction_id";
}

/*
========================
SAVE TRANSACTION
========================
*/
$stmt = $conn->prepare("
INSERT INTO payment_transactions
(order_id, order_type, transaction_id, amount, status, expire_at, bank)
VALUES (?,?,?,?,?,?,?)
");

$status = "pending";
$expire = date("Y-m-d H:i:s", $expire_time);

$stmt->bind_param(
    "ssdssss",
    $order_id,
    $type,
    $transaction_id,
    $amount,
    $status,
    $expire,
    $bank
);

$stmt->execute();

/*
========================
SESSION
========================
*/
$_SESSION['transaction_id'] = $transaction_id;
$_SESSION['order_id'] = $order_id;
$_SESSION['order_type'] = $type;
$_SESSION['amount'] = $amount;
$_SESSION['qr'] = $qr_string;

/*
========================
RESPONSE
========================
*/
echo json_encode([
    "status" => "success",
    "order_id" => $order_id,
    "type" => $type,
    "transaction_id" => $transaction_id,
    "amount" => $amount,
    "qr" => $qr_string,
    "redirect" => "payment_qr.php"
]);

exit;