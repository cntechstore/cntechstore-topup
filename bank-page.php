<?php

require_once "config.php";
require_once "payment_config.php";

$bank =
    strtoupper(
        $_GET['bank'] ?? ''
    );

if(
    !isset(
        $PAYMENT_BANK
        [PAYMENT_MODE]
        [$bank]
    )
){

    echo json_encode([
        "success"=>false,
        "message"=>"Bank not found"
    ]);

    exit;
}

echo json_encode([

    "success"=>true,

    "bank"=>$bank,

    "mode"=>PAYMENT_MODE,

    "redirect"=>
        $PAYMENT_BANK
        [PAYMENT_MODE]
        [$bank]['url']

]);