<?php

require "database.php";
require "topup_api.php";
require "mobile_api.php";

if($type=="game"){
    topupGame($order_id);
}

if($type=="mobile"){
    topupMobile($order_id);
}

$payload = @file_get_contents("php://input");
$data = json_decode($payload, true);

if(!$data){
    http_response_code(400);
    exit;
}

/*
====================================
GET EVENT
====================================
*/
$event = $data['type'] ?? '';

if($event != "checkout.session.completed"){
    http_response_code(200);
    exit;
}

/*
====================================
GET SESSION
====================================
*/
$session = $data['data']['object'];

$order_id =
    $session['metadata']['order_id']
    ?? '';

$order_type =
    $session['metadata']['order_type']
    ?? '';

if(!$order_id){
    exit;
}

/*
====================================
SHOP
====================================
*/
if($order_type == "shop"){

    $sql = "
        UPDATE shop_orders
        SET
            payment_status='paid',
            payment_method='card',
            gateway='stripe'
        WHERE order_id=?
    ";
}

/*
====================================
GAME
====================================
*/
else if($order_type == "game"){

    $sql = "
        UPDATE game_orders
        SET
            payment_status='paid',
            payment_method='card',
            gateway='stripe'
        WHERE order_id=?
    ";
}

/*
====================================
MOBILE
====================================
*/
else{

    $sql = "
        UPDATE mobile_orders
        SET
            status='paid'
        WHERE order_id=?
    ";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$order_id);
$stmt->execute();

/*
====================================
AUTO TOPUP
====================================
*/
if($order_type == "game"){

    require "topup_api.php";

    topupGame($order_id);
}

if($order_type == "mobile"){

    require "mobile_api.php";

    topupMobile($order_id);
}

http_response_code(200);
echo "OK";