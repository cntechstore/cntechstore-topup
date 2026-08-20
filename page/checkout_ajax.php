<?php
session_start();
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set("display_errors",1);

require "database.php";

/*
========================
GET FORM DATA (FormData)
========================
*/
$fullname = $_POST['fullname'] ?? '';
$email    = $_POST['email'] ?? '';
$address  = $_POST['address'] ?? '';

$cart = $_SESSION['cart'] ?? [];

/*
========================
CHECK CART
========================
*/
if(empty($cart)){
    echo json_encode([
        "status" => "error",
        "message" => "Cart empty"
    ]);
    exit;
}

/*
========================
VALIDATE INPUT
========================
*/
if(!$fullname || !$email || !$address){
    echo json_encode([
        "status" => "error",
        "message" => "ข้อมูลไม่ครบ"
    ]);
    exit;
}

/*
========================
CREATE ORDER ID
========================
*/
$order_id = "ORD_" . time() . rand(1000,9999);

/*
========================
CALCULATE TOTAL + ITEMS
========================
*/
$total = 0;
$items = [];

foreach($cart as $item){

    $price = (float)$item['price'];
    $qty   = (int)$item['qty'];

    $total += $price * $qty;

    $items[] = [
        "id"    => (int)$item['id'],
        "name"  => $item['name'],
        "price" => $price,
        "qty"   => $qty
    ];
}

$items_json = json_encode($items, JSON_UNESCAPED_UNICODE);

/*
========================
PRODUCT ID (first item)
========================
*/
$product_id = (int)($cart[0]['id'] ?? 0);

/*
========================
INSERT DB
========================
*/
$stmt = $conn->prepare("
INSERT INTO shop_orders (
    order_id,
    customer_name,
    email,
    address,
    items,
    product_id,
    total,
    payment_status,
    status
)
VALUES (
    ?,?,?,?,?,?,?, 'pending','new'
)
");

$stmt->bind_param(
    "sssssid",
    $order_id,
    $fullname,
    $email,
    $address,
    $items_json,
    $product_id,
    $total
);

/*
========================
EXECUTE
========================
*/
if($stmt->execute()){

    echo json_encode([
        "status" => "success",
        "order_id" => $order_id,
        "redirect" => "payment_ajax.php?order_id=$order_id&type=shop"
    ]);

}else{

    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}