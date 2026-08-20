<?php
session_start();

header("Content-Type: application/json");

require "../config.php";
require "../database.php";


$data = json_decode(
    file_get_contents("php://input"),
    true
);


if(!$data){

    echo json_encode([
        "success"=>false,
        "message"=>"No data"
    ]);

    exit;

}


/*
=========================
CREATE ORDER ID
=========================
*/

$order_id = "ORD_" . time() . rand(1000,9999);



/*
=========================
SAFE DATA
=========================
*/

$uid = trim($data['uid'] ?? '');

$open_id = trim($data['open_id'] ?? '');

$server = trim($data['server'] ?? '');

$product = (int)($data['product'] ?? 0);

$price = (float)($data['price'] ?? 0);

$email = trim($data['email'] ?? '');



/*
=========================
INSERT INTO GAME ORDERS
=========================
*/


$stmt = $conn->prepare("

INSERT INTO game_orders

(
order_id,
uid,
open_id,
server,
product,
price,
email,
order_type,
status
)

VALUES

(?,?,?,?,?,?,?,'game','pending')

");



$stmt->bind_param(

"ssssids",

$order_id,

$uid,

$open_id,

$server,

$product,

$price,

$email

);



if(!$stmt->execute()){


    echo json_encode([

        "success"=>false,

        "message"=>$stmt->error

    ]);

    exit;

}



/*
=========================
RETURN PAYMENT URL
=========================
*/


echo json_encode([

    "success"=>true,

    "payment_url"=>BASE_URL .
    "payment/payment2.php?order_id=" .
    $order_id

]);