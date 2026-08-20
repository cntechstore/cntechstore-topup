<?php
session_start();
header("Content-Type: application/json");

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if(!$data){
    echo json_encode([
        "success"=>false,
        "message"=>"Invalid JSON"
    ]);
    exit;
}

/*
========================
VALIDATE CHECKOUT
========================
*/
if(!isset($_SESSION['checkout'])){
    echo json_encode([
        "success"=>false,
        "message"=>"Session expired"
    ]);
    exit;
}

$checkout = $_SESSION['checkout'];

/*
========================
CREATE ORDER
========================
*/
$order_id = "ORD_" . time() . "_" . rand(1000,9999);

/*
เก็บ order ลง session
*/
$_SESSION['order'] = [
    "order_id" => $order_id,
    "checkout" => $checkout,
    "method" => $data['method'] ?? "BCEL",
    "created_at" => time()
];

echo json_encode([
    "success"=>true,
    "order_id"=>$order_id,
    "payment_url"=>"payment2.php?order_id=".$order_id
]);