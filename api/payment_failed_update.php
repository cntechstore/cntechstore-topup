<?php

require "../database.php";


$order_id =
$_POST['order_id'] ?? '';

$type =
$_POST['type'] ?? '';



if($order_id=="" || $type==""){

echo json_encode([
"success"=>false
]);

exit;

}




$table="";



switch($type){


case "game":

$table="game_orders";

break;


case "voucher":

$table="voucher_orders";

break;


case "mobile":

$table="mobile_orders";

break;


case "shop":

$table="shop_orders";

break;


default:

echo json_encode([
"success"=>false
]);

exit;


}




$sql="
UPDATE $table

SET

payment_status='failed',

status='failed'

WHERE order_id=?

";



$stmt=$conn->prepare($sql);


$stmt->bind_param(
"s",
$order_id
);


$stmt->execute();



echo json_encode([

"success"=>true

]);