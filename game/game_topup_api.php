<?php

/*
=================================
GAME / VOUCHER TOPUP API GATEWAY
=================================
*/

error_reporting(E_ALL);
ini_set("display_errors",1);

require_once "database.php";

/*
=================================
MAIN FUNCTION
=================================
*/

function processTopup($order_id){

    global $conn;

    $stmt = $conn->prepare("
        SELECT
            vo.*,
            vc.name,
            vc.provider,
            vc.gateway_product_id
        FROM voucher_orders vo

        LEFT JOIN voucher_cards vc
        ON vc.id=vo.product_id

        WHERE vo.order_id=?
        LIMIT 1
    ");

    $stmt->bind_param(
        "s",
        $order_id
    );

    $stmt->execute();

    $order =
        $stmt
        ->get_result()
        ->fetch_assoc();

    if(!$order){

        return [
            "success"=>false,
            "message"=>"Order not found"
        ];
    }

    /*
    UPDATE PROCESSING
    */

    $stmt = $conn->prepare("
        UPDATE voucher_orders
        SET status='processing'
        WHERE order_id=?
    ");

    $stmt->bind_param(
        "s",
        $order_id
    );

    $stmt->execute();

    /*
    SELECT PROVIDER
    */

    switch(
        strtolower(
            $order['provider']
        )
    ){

        case "codashop":
            return providerCodashop($order);

        case "razer":
            return providerRazer($order);

        case "termgame":
            return providerTermgame($order);

        case "moogold":
            return providerMoogold($order);

        default:

            updateFailed(
                $order_id,
                "Provider not found"
            );

            return [
                "success"=>false
            ];
    }
}

/*
=================================
CODASHOP
=================================
*/

function providerCodashop($order){

    /*
    TODO:
    API จริงในอนาคต
    */

    return fakeSuccess(
        $order,
        "CODA-".rand(100000,999999)
    );
}

/*
=================================
RAZER
=================================
*/

function providerRazer($order){

    /*
    TODO:
    API จริงในอนาคต
    */

    return fakeSuccess(
        $order,
        "RAZER-".rand(100000,999999)
    );
}

/*
=================================
TERMGAME
=================================
*/

function providerTermgame($order){

    /*
    TODO:
    API จริงในอนาคต
    */

    return fakeSuccess(
        $order,
        "TERM-".rand(100000,999999)
    );
}

/*
=================================
MOOGOLD
=================================
*/

function providerMoogold($order){

    /*
    TODO:
    API จริงในอนาคต
    */

    return fakeSuccess(
        $order,
        "MOO-".rand(100000,999999)
    );
}

/*
=================================
REAL CURL FUNCTION
=================================
*/

function callProviderAPI(
    $url,
    $headers=[],
    $post=[]
){

    $ch = curl_init();

    curl_setopt_array(
        $ch,
        [

            CURLOPT_URL=>$url,

            CURLOPT_RETURNTRANSFER=>true,

            CURLOPT_TIMEOUT=>30,

            CURLOPT_POST=>true,

            CURLOPT_POSTFIELDS=>
                json_encode(
                    $post
                ),

            CURLOPT_HTTPHEADER=>
                $headers
        ]
    );

    $response =
        curl_exec($ch);

    $http =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

    curl_close($ch);

    return [

        "http"=>$http,

        "response"=>$response
    ];
}

/*
=================================
SUCCESS
=================================
*/

function fakeSuccess(
    $order,
    $pin
){

    global $conn;

    $stmt =
        $conn->prepare("
        UPDATE voucher_orders

        SET
            status='completed',
            pin_code=?,
            api_response=?

        WHERE order_id=?
    ");

    $json =
        json_encode([
            "pin"=>$pin
        ]);

    $stmt->bind_param(
        "sss",
        $pin,
        $json,
        $order['order_id']
    );

    $stmt->execute();

    return [
        "success"=>true,
        "pin"=>$pin
    ];
}

/*
=================================
FAILED
=================================
*/

function updateFailed(
    $order_id,
    $msg
){

    global $conn;

    $stmt =
        $conn->prepare("
        UPDATE voucher_orders

        SET
            status='failed',
            api_response=?

        WHERE order_id=?
    ");

    $stmt->bind_param(
        "ss",
        $msg,
        $order_id
    );

    $stmt->execute();
}