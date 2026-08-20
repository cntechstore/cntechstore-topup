<?php

function processMobile($conn,$order){

    $payload=[

        "phone"=>$order['phone'],
        "provider"=>$order['provider'],
        "amount"=>$order['amount']

    ];

    file_put_contents(
        "logs/mobile.log",
        json_encode($payload).PHP_EOL,
        FILE_APPEND
    );

    return [
        "status"=>"success"
    ];
}