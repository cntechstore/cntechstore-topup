<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$order_id       = trim($_GET['order_id'] ?? '');
$type           = strtolower(trim($_GET['type'] ?? ''));
$bank_id        = (int)($_GET['bank_id'] ?? 0);
$transaction_id = trim($_GET['transaction_id'] ?? '');

if ($order_id === '' || $type === '' || $bank_id <= 0 || $transaction_id === '') {
    die('Invalid payment request');
}

/*
|--------------------------------------------------------------------------
| SAVE PAYMENT SESSION
|--------------------------------------------------------------------------
*/

$_SESSION['manual_payment'] = [
    'order_id'       => $order_id,
    'type'           => $type,
    'bank_id'        => $bank_id,
    'transaction_id' => $transaction_id
];

/*
|--------------------------------------------------------------------------
| REDIRECT TO UPLOAD SLIP
|--------------------------------------------------------------------------
*/

header(
    'Location: /api/upload_slip.php?' .
    http_build_query([
        'order_id'       => $order_id,
        'type'           => $type,
        'bank_id'        => $bank_id,
        'transaction_id' => $transaction_id
    ])
);

exit;