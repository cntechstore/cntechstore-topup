<?php
session_start();
include "database.php";

header("Content-Type: application/json");

// =========================
// 1. รับ id แบบ GET (ตามระบบคุณ)
// =========================
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "ID Missing"
    ]);
    exit;
}

// =========================
// 2. ดึงสินค้า
// =========================
$sql = "SELECT * FROM products WHERE id=$id LIMIT 1";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Product not found"
    ]);
    exit;
}

$product = $result->fetch_assoc();

// =========================
// 3. init cart
// =========================
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// =========================
// 4. add cart (limit 10)
// =========================
if (isset($_SESSION['cart'][$id])) {

    if ($_SESSION['cart'][$id]['qty'] < 10) {
        $_SESSION['cart'][$id]['qty']++;
    }

} else {

    $_SESSION['cart'][$id] = [
        "id" => $product['id'],
        "name" => $product['name'],
        "price" => $product['price'],
        "image" => $product['image'],
        "qty" => 1
    ];
}

// =========================
// 5. count total
// =========================
$count = 0;

foreach ($_SESSION['cart'] as $item) {
    $count += $item['qty'];
}

// =========================
// 6. response
// =========================
echo json_encode([
    "success" => true,
    "count" => $count
]);