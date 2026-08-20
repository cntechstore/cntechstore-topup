<?php

error_reporting(0);
ini_set('display_errors', '0');

session_start();

require "../database.php";

/*
|--------------------------------------------------------------------------
| CNTECH STORE
| PAYMENT SESSION GATEWAY
|--------------------------------------------------------------------------
*/

$order_id = trim($_GET['order_id'] ?? '');
$type     = strtolower(trim($_GET['type'] ?? 'game'));

if ($order_id === '') {
    exit('Missing order ID');
}

/*
|--------------------------------------------------------------------------
| ALLOWED TABLES
|--------------------------------------------------------------------------
*/

$tables = [
    'game'    => 'game_orders',
    'mobile'  => 'mobile_orders',
    'shop'    => 'shop_orders',
    'voucher' => 'voucher_orders'
];

if (!isset($tables[$type])) {
    exit('Invalid payment type');
}

$table = $tables[$type];

/*
|--------------------------------------------------------------------------
| LOAD ORDER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM `$table`
    WHERE order_id=?
    LIMIT 1
");

if (!$stmt) {
    exit('Database error');
}

$stmt->bind_param(
    's',
    $order_id
);

$stmt->execute();

$order =
    $stmt
        ->get_result()
        ->fetch_assoc();

$stmt->close();

if (!$order) {
    exit('Order not found');
}

/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

$payment_status =
    strtolower(
        trim(
            $order['payment_status']
            ?? 'pending'
        )
    );

/*
|--------------------------------------------------------------------------
| PREVENT RE-PAYMENT
|--------------------------------------------------------------------------
*/

if (
    in_array(
        $payment_status,
        ['paid', 'success', 'completed'],
        true
    )
) {

    header(
        'Location: payment_success.php?' .
        http_build_query([
            'order_id' => $order_id,
            'type'     => $type
        ])
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| PREVENT CANCELLED / EXPIRED
|--------------------------------------------------------------------------
*/

if (
    in_array(
        $payment_status,
        ['expired', 'cancelled', 'failed'],
        true
    )
) {

    exit(
        'This order is no longer available for payment.'
    );
}

/*
|--------------------------------------------------------------------------
| GET AMOUNT
|--------------------------------------------------------------------------
*/

if (isset($order['total'])) {

    $amount =
        (float)$order['total'];

} elseif (isset($order['amount'])) {

    $amount =
        (float)$order['amount'];

} elseif (isset($order['price'])) {

    $amount =
        (float)$order['price'];

} else {

    $amount = 0;
}

if ($amount <= 0) {
    exit('Invalid payment amount');
}

/*
|--------------------------------------------------------------------------
| PAYMENT SESSION
|--------------------------------------------------------------------------
|
| payment_ajax.php ใช้ Session นี้
|
*/

$_SESSION['payment'] = [

    'order_id' =>
        $order_id,

    'type' =>
        $type,

    'amount' =>
        $amount,

    'uid' =>
        $order['uid'] ?? '',

    'server' =>
        $order['server'] ?? '',

    'open_id' =>
        $order['open_id'] ?? '',

    'product' =>
        $order['product'] ?? '',

    'email' =>
        $order['email'] ?? '',

    'created_at' =>
        time()

];

/*
|--------------------------------------------------------------------------
| FORWARD
|--------------------------------------------------------------------------
*/

$url =
    'payment_ajax.php?' .
    http_build_query([
        'order_id' => $order_id,
        'type'     => $type
    ]);

header(
    'Location: ' . $url
);

exit;