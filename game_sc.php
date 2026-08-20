<?php
session_start();
require "database.php";

$order_id = $_GET['order_id'] ?? '';
$type = $_GET['type'] ?? 'game';

if(!$order_id){
    die("Missing order");
}

$tables = [
    "game" => "game_orders",
    "mobile" => "mobile_orders",
    "shop" => "shop_orders",
    "voucher" => "voucher_orders"
];

$table = $tables[$type] ?? null;
if(!$table) die("Invalid type");

$stmt = $conn->prepare("SELECT * FROM {$table} WHERE order_id=?");
$stmt->bind_param("s",$order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if(!$order) die("Order not found");

$amount =
    $order['amount']
    ?? $order['price']
    ?? $order['total']
    ?? 0;

$amount = floatval($amount);

if($amount <= 0) die("Invalid amount");

/*
SESSION (ส่งไป payment.ajax.php)
*/
$_SESSION['payment'] = [
    'order_id' => $order_id,
    'type' => $type,
    'amount' => $amount,
    'uid' => $order['uid'] ?? '',
    'server' => $order['server'] ?? ''
];

/*
FORWARD
*/
header("Location: payment_ajax.php?order_id=$order_id&type=$type");
exit;