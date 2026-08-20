<?php
session_start();

$order_id = $_POST['order_id'] ?? '';
$amount   = $_POST['amount'] ?? 0;
$provider = $_POST['provider'] ?? '';

if(!$order_id){
    die("Order not found");
}

/*
เก็บ session
*/
$_SESSION['payment'] = [
    'order_id' => $order_id,
    'amount'   => $amount,
    'provider' => $provider,
    'type'     => 'mobile'
];

/*
ส่งไป payment gateway
*/
header(
    "Location: payment_ajax.php?order_id="
    . urlencode($order_id)
    . "&type=mobile"
);
exit;
?>