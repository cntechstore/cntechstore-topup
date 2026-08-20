<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/database.php";

/*
========================
READ REQUEST
========================
*/
$raw = file_get_contents("php://input");

file_put_contents(
    __DIR__ . "/webhook_log.txt",
    date("Y-m-d H:i:s") . PHP_EOL .
    $raw . PHP_EOL .
    "--------------------------------" . PHP_EOL,
    FILE_APPEND
);

$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON"
    ]);
    exit;
}

/*
========================
GET DATA
========================
*/
$transaction_id = trim($data['transaction_id'] ?? '');
$order_id       = trim($data['order_id'] ?? '');
$status         = strtolower(trim($data['status'] ?? ''));

if ($transaction_id === '' || $order_id === '') {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing transaction_id or order_id"
    ]);
    exit;
}

/*
========================
UPDATE PAYMENT
========================
*/
$stmt = $conn->prepare("
UPDATE payment_transactions
SET
status=?,
updated_at=NOW()
WHERE transaction_id=?
");

$stmt->bind_param(
    "ss",
    $status,
    $transaction_id
);

$stmt->execute();

/*
========================
UPDATE ORDER
========================
*/
if ($status === "paid" || $status === "success") {

    $stmt = $conn->prepare("
UPDATE shop_orders
SET
payment_status='paid',
order_status='completed',
paid_at=NOW()
WHERE order_id=?
");

    $stmt->bind_param("s", $order_id);
    $stmt->execute();
}

/*
========================
SUCCESS
========================
*/
http_response_code(200);

echo json_encode([
    "status" => "ok"
]);