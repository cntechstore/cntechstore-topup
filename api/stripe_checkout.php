<?php

error_reporting(E_ALL);
ini_set("display_errors",1);

session_start();
require "../database.php";

/* ======================
   1. ORDER ID
====================== */
$order_id = trim($_GET['order_id'] ?? '');

if($order_id == ''){
    die("Missing Order ID");
}

/* ======================
   2. FIND ORDER
====================== */
$order = null;
$type = null;
$order_table = null;

$tables = [

    "shop" => "shop_orders",

    "game" => "game_orders",

    "mobile" => "mobile_orders",

    "voucher" => "voucher_orders"

];

foreach($tables as $t => $table){

    $stmt = $conn->prepare("
        SELECT *
        FROM {$table}
        WHERE order_id=?
        LIMIT 1
    ");

    if(!$stmt){
        die($conn->error);
    }

    $stmt->bind_param(
        "s",
        $order_id
    );

    $stmt->execute();

    $result =
        $stmt
        ->get_result();

    if($result->num_rows){

        $order =
            $result
            ->fetch_assoc();

        $type = $t;

        $order_table = $table;

        break;
    }

    $stmt->close();
}

if(!$order){

    echo "<pre>";
    echo "Order not found\n";
    echo "ORDER ID = ".$order_id;
    echo "</pre>";
    exit;
}

/* ======================
   3. TOTAL
====================== */
$total = (float)(
    $order['total']
    ?? $order['price']
    ?? $order['amount']
    ?? 0
);

if($total <= 0){

    die(
        "Invalid amount : ".
        $total
    );
}

/* ======================
   4. STRIPE KEY
====================== */
$secret_key =
"sk_test_51TlB2DHr05VL47Oo4acYz6YKdXgHILIX2XUV7UxqBCNVWUyNb3FGT3yS4xkBz3k3EW8kTI67aiihiGn72AhrMMTX00jwBKbHzh";

/* ======================
   5. CONVERT LAK -> USD
====================== */
$exchange_rate = 22000;

$usd =
    max(
        $total /
        $exchange_rate,
        0.50
    );

$amount =
    intval(
        $usd * 100
    );

/* ======================
   6. STRIPE DATA
====================== */
$data = [

    "payment_method_types[]" =>
        "card",

    "mode" =>
        "payment",

    "success_url" =>

        "https://cntechstore.shop/payment/payment_success.php"
        .
        "?order_id=".$order_id
        .
        "&type=".$type,

    "cancel_url" =>

        "https://cntechstore.shop/payment/payment_failed.php"
        .
        "?order_id=".$order_id
        .
        "&type=".$type,

    "line_items[0][price_data][currency]" =>
        "usd",

    "line_items[0][price_data][product_data][name]" =>

        strtoupper($type)
        ." ORDER #"
        .$order_id,

    "line_items[0][price_data][unit_amount]" =>
        $amount,

    "line_items[0][quantity]" =>
        1
];

/* ======================
   7. CREATE SESSION
====================== */
$ch = curl_init();

curl_setopt_array(
    $ch,
    [

        CURLOPT_URL =>
            "https://api.stripe.com/v1/checkout/sessions",

        CURLOPT_RETURNTRANSFER =>
            true,

        CURLOPT_USERPWD =>
            $secret_key.":",

        CURLOPT_POST =>
            true,

        CURLOPT_POSTFIELDS =>
            http_build_query($data)

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

$result =
    json_decode(
        $response,
        true
    );

/* ======================
   8. ERROR
====================== */
if(
    !isset(
        $result['url']
    )
){

    echo "<pre>";

    echo "HTTP : ".$http."\n\n";

    print_r(
        $result
    );

    echo "\n\n";

    echo $response;

    echo "</pre>";

    exit;
}

/* ======================
   9. UPDATE ORDER
====================== */
$stmt =
    $conn->prepare("
        UPDATE {$order_table}
        SET
            gateway='stripe',
            payment_method='card',
            payment_status='pending'
        WHERE order_id=?
    ");

if($stmt){

    $stmt->bind_param(
        "s",
        $order_id
    );

    $stmt->execute();

    $stmt->close();
}

/* ======================
   10. REDIRECT
====================== */
header(
    "Location: ".
    $result['url']
);

exit;
?>