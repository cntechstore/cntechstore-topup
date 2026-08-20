<?php

/*
|--------------------------------------------------------------------------
| PAYMENT GATEWAY CONFIG
|--------------------------------------------------------------------------
*/

define('BCEL_MODE', 'sandbox'); // sandbox | live
define('LDB_MODE',  'sandbox');
define('CARD_MODE', 'sandbox');

define('BCEL_API_URL', '');
define('BCEL_API_KEY', '');

define('LDB_API_URL', '');
define('LDB_API_KEY', '');

define('CARD_API_URL', '');
define('CARD_SECRET', '');


/*
|--------------------------------------------------------------------------
| MAIN ROUTER
|--------------------------------------------------------------------------
*/

function createPayment($data){

    $method   = strtoupper($data['method'] ?? '');
    $order_id = $data['order_id'] ?? '';
    $amount   = (float)($data['amount'] ?? 0);
    $uid      = $data['uid'] ?? '';

    if($amount <= 0)
        return fail("Invalid amount");

    if(empty($order_id))
        return fail("Invalid order");

    if(empty($uid))
        return fail("Missing UID");

    switch($method){

        case "BCEL":
            return createBCELPayment(
                $order_id,
                $amount,
                $uid
            );

        case "LDB":
            return createLDBPayment(
                $order_id,
                $amount,
                $uid
            );

        case "VISA":
        case "MASTERCARD":
            return createCardPayment(
                $method,
                $order_id,
                $amount,
                $uid
            );

        default:
            return fail(
                "Unsupported payment"
            );
    }
}


/*
|--------------------------------------------------------------------------
| BCEL
|--------------------------------------------------------------------------
*/

function createBCELPayment(
    $order_id,
    $amount,
    $uid
){

    // ถ้ายังไม่มี API จริง
    if(
        BCEL_MODE=="sandbox"
        ||
        BCEL_API_URL==""
    ){

        return success([

            "provider" =>
                "BCEL",

            "type" =>
                "QR",

            "qr_url" =>
                generateMockQR(
                    "BCEL",
                    $order_id,
                    $amount
                ),

            "status" =>
                "pending"

        ]);
    }

    // TODO:
    // ใส่ BCEL API จริงตรงนี้
}


/*
|--------------------------------------------------------------------------
| LDB
|--------------------------------------------------------------------------
*/

function createLDBPayment(
    $order_id,
    $amount,
    $uid
){

    if(
        LDB_MODE=="sandbox"
        ||
        LDB_API_URL==""
    ){

        return success([

            "provider" =>
                "LDB",

            "type" =>
                "QR",

            "qr_url" =>
                generateMockQR(
                    "LDB",
                    $order_id,
                    $amount
                ),

            "status" =>
                "pending"

        ]);
    }

    // TODO:
    // ใส่ LDB API จริงตรงนี้
}


/*
|--------------------------------------------------------------------------
| VISA / MASTER
|--------------------------------------------------------------------------
*/

function createCardPayment(
    $method,
    $order_id,
    $amount,
    $uid
){

    if(
        CARD_MODE=="sandbox"
        ||
        CARD_API_URL==""
    ){

        return success([

            "provider" =>
                $method,

            "type" =>
                "REDIRECT",

            "app_link" =>
                "https://example.com/pay?" .
                http_build_query([
                    "order" =>
                        $order_id,
                    "amount" =>
                        $amount
                ]),

            "status" =>
                "pending"

        ]);
    }

    // TODO:
    // ใส่ Stripe / Omise / 2C2P
}


/*
|--------------------------------------------------------------------------
| MOCK QR
|--------------------------------------------------------------------------
*/

function generateMockQR(
    $bank,
    $order_id,
    $amount
){

    return
        "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data="
        . urlencode(
            $bank
            ."|"
            .$order_id
            ."|"
            .$amount
        );
}


// ==========================
// REAL BCEL QR
// ==========================

function generateQR($bank,$order_id,$amount){

    $payload = [
        "merchant_id" => BCEL_MERCHANT_ID,
        "order_id"    => $order_id,
        "amount"      => $amount,
        "currency"    => "LAK"
    ];

    $ch = curl_init();

    curl_setopt_array($ch,[
        CURLOPT_URL            => BCEL_API_URL."/payment/qr",
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer ".BCEL_TOKEN,
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response,true);

    return $result['qr_image'];
}
/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function success($data=[]){

    return array_merge(
        [
            "success"=>true
        ],
        $data
    );
}

function fail($message){

    return [

        "success"=>false,

        "message"=>$message

    ];
}
?>