<?php

function processGame($conn,$order){

    /*
    API จริงใส่ตรงนี้
    */

    $payload=[

        "uid"=>$order['uid'],

        "server"=>$order['server'],

        "product"=>$order['product']
    ];

    file_put_contents(
        "logs/game.log",
        json_encode($payload).PHP_EOL,
        FILE_APPEND
    );

    return [
        "status"=>"success",
        "message"=>"game queued"
    ];
}