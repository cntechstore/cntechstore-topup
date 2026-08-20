

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
/*
=================================
REAL PAYMENT WEBHOOK
=================================

BCEL
LDB
VISA
MASTER

จะ POST callback มาที่นี่
*/

$data =
    json_decode(
        file_get_contents(
            "php://input"
        ),
        true
    );

$order_id =
    $data['order_id']
    ?? '';

$status =
    $data['status']
    ?? '';

$transaction =
    $data['transaction_id']
    ?? '';

if(
    !$order_id
){
    http_response_code(400);
    exit;
}

/*
=========================
VERIFY SIGNATURE
=========================

ใส่ BCEL/LDB/VISA
signature verify ตรงนี้
*/

$stmt = $conn->prepare("
    UPDATE mobile_orders
    SET
        status=?,
        transaction_id=?
    WHERE order_id=?
");

$stmt->bind_param(
    "sss",
    $status,
    $transaction,
    $order_id
);

$stmt->execute();

/*
=========================
AUTO TOPUP API
=========================
*/

if(
    $status == "success"
){

    /*
    callMobileAPI([
        provider,
        phone,
        amount
    ]);
    */

}

echo "OK";