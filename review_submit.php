<?php
session_start();
require "database.php";

error_reporting(E_ALL);
ini_set("display_errors",1);

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: index.php");
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);
$rating     = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if($comment === ''){
    $comment = "No comment";
}

if($product_id <= 0){
    die("Invalid Product");
}

if($rating < 1 || $rating > 5){
    die("Invalid Rating");
}

/*
========================
CREATE TABLE IF NOT EXISTS
========================
*/
$conn->query("
CREATE TABLE IF NOT EXISTS product_reviews(
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    username VARCHAR(100),
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
");

/*
========================
SAVE REVIEW
========================
*/
$stmt = $conn->prepare("
    INSERT INTO product_reviews
    (
        product_id,
        username,
        rating,
        comment
    )
    VALUES
    (?,?,?,?)
");

$username =
    $_SESSION['user']['username']
    ?? 'Guest';

$stmt->bind_param(
    "isis",
    $product_id,
    $username,
    $rating,
    $comment
);

$stmt->execute();

/*
========================
CALCULATE NEW RATING
========================
*/
$stmt = $conn->prepare("
    SELECT
        AVG(rating) avg_rating,
        COUNT(*) total_review
    FROM product_reviews
    WHERE product_id=?
");

$stmt->bind_param(
    "i",
    $product_id
);

$stmt->execute();

$data =
    $stmt
    ->get_result()
    ->fetch_assoc();

$avg =
    round(
        $data['avg_rating'],
        1
    );

$total =
    (int)$data['total_review'];

/*
========================
UPDATE PRODUCT
========================
*/
$stmt = $conn->prepare("
    UPDATE products
    SET
        rating=?,
        reviews=?
    WHERE id=?
");

$stmt->bind_param(
    "dii",
    $avg,
    $total,
    $product_id
);

$stmt->execute();

/*
========================
BACK TO PRODUCT
========================
*/
header(
    "Location: view-product.php?id=".$product_id
);
exit;
?>