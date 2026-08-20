<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


require "../config.php";
require "../database.php";

session_start();



/*
=================================
CHECK LOGIN
=================================
*/

if(!isset($_SESSION['user_id'])){

    die("Please login first");

}


$user_id = $_SESSION['user_id'];



/*
=================================
GET DATA
=================================
*/

$provider = $_POST['provider'] ?? '';

$phone = trim($_POST['phone'] ?? '');

$amount = (float)($_POST['amount'] ?? 0);



if(!$provider || !$phone || $amount <= 0){

    die("Invalid request");

}




/*
=================================
CREATE ORDER ID
=================================
*/

$order_id =

"MT"

.date("YmdHis")

.rand(1000,9999);





$status = "pending";



/*
=================================
INSERT MOBILE ORDER
=================================
*/


$stmt = $conn->prepare("

INSERT INTO mobile_orders

(
user_id,
order_id,
provider,
phone,
amount,
status,
created_at
)

VALUES

(?,?,?,?,?,?,NOW())

");



$stmt->bind_param(

"isssds",

$user_id,

$order_id,

$provider,

$phone,

$amount,

$status

);



if(!$stmt->execute()){


die(

"Order Error : "

.$stmt->error

);


}





/*
=================================
SAVE SESSION
=================================
*/


$_SESSION['mobile_topup']=[


"order_id"=>$order_id,

"provider"=>$provider,

"phone"=>$phone,

"amount"=>$amount,

"status"=>"pending"


];







/*
=================================
CREATE NOTIFICATION
=================================
*/


$title = "📱 ສ້າງ Order ເຕີມເງິນມືຖື";


$message = "

ສ້າງ Order ສຳເລັດ

Order ID:
$order_id


Provider:
$provider


ເບີໂທ:
$phone


ຈຳນວນ:
₭ ".number_format($amount)."


ສະຖານະ:
Pending

";


$type="mobile_topup";


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







/*
=================================
REDIRECT PAYMENT PAGE
=================================
*/


header(

"Location: mobile_payment.php?order=".$order_id

);


exit;


?>