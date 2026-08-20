<?php

$order_id =
    $_POST["order_id"];

$status =
    $_POST["status"];

if(
    $status
    ==
    "SUCCESS"
){

    include
    "topup_gateway.php";

}