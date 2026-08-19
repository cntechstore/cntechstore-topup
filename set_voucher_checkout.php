<?php
session_start();

header(
    "Content-Type: application/json; charset=utf-8"
);

require "../database.php";

/*
========================
READ JSON
========================
*/
$data =
    json_decode(
        file_get_contents(
            "php://input"
        ),
        true
    );

$category_id =
    intval(
        $data['category_id']
        ?? 0
    );

$product_id =
    intval(
        $data['product_id']
        ?? 0
    );

$price =
    floatval(
        $data['price']
        ?? 0
    );

$email =
    trim(
        $data['email']
        ?? ''
    );

/*
========================
VALIDATE
========================
*/
if(
    !$category_id ||
    !$product_id ||
    !$price ||
    !$email
){

    echo json_encode([
        "success"=>false,
        "message"=>
            "Missing data"
    ]);

    exit;
}

/*
========================
GET PRODUCT
========================
*/
$stmt =
$conn->prepare("
SELECT
    vp.*,
    vc.name
        AS category_name
FROM
    voucher_cards vp
LEFT JOIN
    voucher_categories vc
ON
    vp.category_id =
    vc.id
WHERE
    vp.id=?
AND
    vp.status='active'
LIMIT 1
");

$stmt->bind_param(
    "i",
    $product_id
);

$stmt->execute();

$product =
    $stmt
    ->get_result()
    ->fetch_assoc();

if(!$product){

    echo json_encode([
        "success"=>false,
        "message"=>
            "Product not found"
    ]);

    exit;
}

/*
========================
PRICE CHECK
========================
*/
$price =
    floatval(
        $product['price']
    );

/*
========================
ORDER ID
========================
*/
$order_id =
    "ORD_"
    .
    time()
    .
    rand(
        10000,
        99999
    );

/*
========================
CREATE ORDER
========================
*/
$stmt =
$conn->prepare("
INSERT INTO
voucher_orders
(
    order_id,
    category_id,
    product_id,
    email,
    total,
    payment_status,
    order_status,
    created_at
)
VALUES
(
    ?, ?, ?, ?,
    ?,
    'pending',
    'pending',
    NOW()
)
");

$stmt->bind_param(
    "siisd",
    $order_id,
    $category_id,
    $product_id,
    $email,
    $price
);

if(
    !$stmt->execute()
){

    echo json_encode([
        "success"=>false,
        "message"=>
            $conn->error
    ]);

    exit;
}

/*
========================
SESSION
========================
*/
$_SESSION[
    'voucher_checkout'
] = [

    "order_id" =>
        $order_id,

    "category_id" =>
        $category_id,

    "product_id" =>
        $product_id,

    "email" =>
        $email,

    "total" =>
        $price,

    "type" =>
        "voucher"
];

/*
========================
RETURN
========================
*/
echo json_encode([

    "success" => true,

    "order_id" =>
        $order_id,

    "payment_url" =>
        "../api/payment_ajax.php"
        .
        "?order_id="
        .
        $order_id
        .
        "&type=voucher"
]);

exit;
?>