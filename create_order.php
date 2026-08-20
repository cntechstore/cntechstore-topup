<?php
session_start();
require "database.php";

$data = json_decode(file_get_contents("php://input"), true);

// 🔥 ใช้ cart จาก SESSION เท่านั้น
$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    echo json_encode([
        "success"=>false,
        "message"=>"Cart empty"
    ]);
    exit;
}

$order_id = "ORD_" . time() . rand(1000,9999);

$total = 0;

foreach($cart as $item){
    $total += $item['price'] * $item['qty'];
}

// save order
$stmt = $conn->prepare("
    INSERT INTO shop_orders
    (order_id,total,payment_status,created_at)
    VALUES (?,?,'pending',NOW())
");

$stmt->bind_param("sd",$order_id,$total);
$stmt->execute();

echo json_encode([
    "success"=>true,
    "order_id"=>$order_id
]);