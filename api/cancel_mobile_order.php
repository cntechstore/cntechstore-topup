<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require "../config.php";
require "../database.php";

session_start();


$order_id = $_GET['order'] ?? '';

if(!$order_id){

    die("Missing Order ID");

}



$user_id = $_SESSION['user_id'] ?? 0;



// ==========================
// FIND ORDER
// ==========================

$stmt=$conn->prepare("

SELECT *

FROM mobile_orders

WHERE order_id=?

LIMIT 1

");


$stmt->bind_param(
"s",
$order_id
);


$stmt->execute();


$order=$stmt->get_result()->fetch_assoc();



if(!$order){

    die("Order not found : ".$order_id);

}



// ==========================
// CHECK USER
// ==========================

if(
$order['user_id'] != 0 &&
$order['user_id'] != $user_id
){

    die("Permission denied");

}




// ==========================
// UPDATE CANCEL
// ==========================


$status="cancelled";


$update=$conn->prepare("

UPDATE mobile_orders

SET 

status=?,

payment_status='cancelled'

WHERE order_id=?

");


$update->bind_param(

"ss",

$status,

$order_id

);


$update->execute();





// ==========================
// NOTIFICATION
// ==========================


$title="❌ ຍົກເລີກ Order";


$message="

Order ID:
$order_id


Provider:
".$order['provider']."


Phone:
".$order['phone']."


Amount:
₭ ".number_format($order['amount'])."


Status:
Cancelled

";


$type="mobile_cancel";


$is_read=0;



$notify=$conn->prepare("

INSERT INTO notifications

(
user_id,
title,
message,
type,
is_read,
created_at
)

VALUES

(?,?,?,?,?,NOW())

");



$notify->bind_param(

"isssi",

$user_id,

$title,

$message,

$type,

$is_read

);



$notify->execute();




// ==========================
// REDIRECT
// ==========================

header(
"Location: ../notifications.php"
);

exit;

?>