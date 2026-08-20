<?php
include("database.php");

$order_id = intval($_GET["order_id"]);

$stmt = $conn->prepare("SELECT payment_status FROM orders WHERE id=?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

if($result && $result["payment_status"] === "paid"){
    echo "PAID";
} else {
    echo "PENDING";
}
?>