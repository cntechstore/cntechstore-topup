<?php

/*
==================================
CREATE LOG DIRECTORY
==================================
*/
function writeLog($file,$data){

    $dir = __DIR__."/logs";

    if(!is_dir($dir)){
        mkdir($dir,0755,true);
    }

    file_put_contents(
        $dir."/".$file,
        date("Y-m-d H:i:s")." ".
        json_encode($data,JSON_UNESCAPED_UNICODE)
        .PHP_EOL,
        FILE_APPEND
    );
}

/*
==================================
SHOP
==================================
*/
function processShop($conn,$order){

    $items =
        json_decode(
            $order['items'] ?? '[]',
            true
        );

    if(!is_array($items)){
        return false;
    }

    foreach($items as $item){

        $pid =
            (int)($item['id'] ?? 0);

        $qty =
            (int)($item['qty'] ?? 1);

        if($pid <= 0){
            continue;
        }

        $conn->query("
            UPDATE products
            SET
                stock=stock-$qty,
                sold=sold+$qty
            WHERE id=$pid
            AND stock >= $qty
        ");
    }

    writeLog(
        "shop.log",
        [
            "order"=>$order['order_id']
        ]
    );

    return true;
}

/*
==================================
GAME TOPUP
==================================
*/
function processGame($conn,$order){

    $payload = [

        "order_id" =>
            $order['order_id'],

        "uid" =>
            $order['uid'] ?? '',

        "server" =>
            $order['server'] ?? '',

        "product" =>
            $order['product'] ?? ''

    ];

    writeLog(
        "game_api.log",
        $payload
    );

    /*
    API จริง

    require "providers/game_api.php";

    return gameTopup(
        $payload
    );
    */

    return true;
}

/*
==================================
MOBILE TOPUP
==================================
*/
function processMobile($conn,$order){

    $payload = [

        "order_id" =>
            $order['order_id'],

        "phone" =>
            $order['phone'] ?? '',

        "provider" =>
            $order['provider'] ?? '',

        "amount" =>
            $order['amount'] ?? 0

    ];

    writeLog(
        "mobile_api.log",
        $payload
    );

    /*
    API จริง

    require "providers/mobile_api.php";

    return mobileTopup(
        $payload
    );
    */

    return true;
}

/*
==================================
VOUCHER
==================================
*/
function processVoucher($conn,$order){

    /*
    API จริง
    เช่น Codashop
    Razer
    Laser
    เติมเกมภายนอก
    */

    $payload = [

        "order_id" =>
            $order['order_id'],

        "product_id" =>
            $order['product_id'] ?? 0,

        "email" =>
            $order['email'] ?? ''

    ];

    writeLog(
        "voucher_api.log",
        $payload
    );

    /*
    เมื่อได้ API จริง

    require
        "providers/voucher_api.php";

    $result =
        voucherPurchase(
            $payload
        );

    if(
        $result['status']
        =='success'
    ){

        sendEmail(
            $payload['email'],
            $result['pin']
        );

        return true;
    }

    return false;
    */

    return true;
}