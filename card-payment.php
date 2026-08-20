<?php

header("Content-Type: application/json");

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$order_id =
    $data['order_id']
    ?? '';

$card =
    strtoupper(
        $data['card']
        ?? 'VISA'
    );

if(empty($order_id)){

    echo json_encode([
        "success"=>false,
        "message"=>"Missing Order ID"
    ]);

    exit;
}

// VISA / Mastercard
echo json_encode([

    "success"=>true,

    "redirect"=>
        "stripe_checkout.php?order_id="
        . urlencode($order_id)

]);