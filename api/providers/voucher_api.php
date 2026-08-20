<?php

function processVoucher($conn,$order){

    $payload=[

        "product"=>$order['product_id'],
        "amount"=>$order['amount']

    ];

    file_put_contents(
        "logs/voucher.log",
        json_encode($payload).PHP_EOL,
        FILE_APPEND
    );

    return [
        "status"=>"success",
        "pin"=>null
    ];
}