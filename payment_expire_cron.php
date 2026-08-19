<?php
error_reporting(0);
ini_set('display_errors', '0');

require_once "../database.php";

/*
|--------------------------------------------------------------------------
| CNTECH STORE
| PAYMENT EXPIRE CRON
|--------------------------------------------------------------------------
*/

$tables = [
    'game'    => 'game_orders',
    'voucher' => 'voucher_orders',
    'mobile'  => 'mobile_orders'
];

/* EmailJS */
$EMAILJS_SERVICE  = 'service_064h3l8';
$EMAILJS_TEMPLATE = 'template_z6eel19';
$EMAILJS_PUBLIC   = 'zPnQ14dGWHb6MZTr5';
$EMAILJS_PRIVATE  = '4ZtigZ9sIpdCrny28axfM';

/* =========================================================
   LOCK CRON
   ========================================================= */

$lock = $conn->query(
    "SELECT GET_LOCK('cntech_payment_expire', 1) AS l"
);

if (!$lock || (int)$lock->fetch_assoc()['l'] !== 1) {
    exit('BUSY');
}

/* =========================================================
   FIND EXPIRED TRANSACTIONS
   ========================================================= */

$sql = "
    SELECT *
    FROM payment_transactions
    WHERE status = 'pending'
      AND expire_at IS NOT NULL
      AND expire_at <= NOW()
    ORDER BY id ASC
    LIMIT 20
";

$result = $conn->query($sql);

if (!$result) {
    $conn->query(
        "SELECT RELEASE_LOCK('cntech_payment_expire')"
    );
    exit('DB ERROR');
}

$count = 0;

while ($tx = $result->fetch_assoc()) {

    $id       = (int)$tx['id'];
    $order_id = trim($tx['order_id']);
    $tx_id    = trim($tx['transaction_id']);
    $amount   = (float)$tx['amount'];

    /* -----------------------------------------------------
       GET ORDER
       ----------------------------------------------------- */

    $order = null;
    $table = null;

    foreach ($tables as $t) {

        $stmt = $conn->prepare(
            "SELECT *
             FROM `$t`
             WHERE order_id = ?
             LIMIT 1"
        );

        if (!$stmt) {
            continue;
        }

        $stmt->bind_param("s", $order_id);
        $stmt->execute();

        $found = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($found) {
            $order = $found;
            $table = $t;
            break;
        }
    }

    if (!$order || !$table) {

        /* ไม่มี order ก็ปิด transaction */
        $stmt = $conn->prepare(
            "UPDATE payment_transactions
             SET status='expired'
             WHERE id=? AND status='pending'"
        );

        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        continue;
    }

    /* -----------------------------------------------------
       ถ้าจ่ายแล้ว ห้ามยกเลิก
       ----------------------------------------------------- */

    if (
        strtolower(
            trim($order['payment_status'] ?? '')
        ) === 'paid'
    ) {
        continue;
    }

    /* -----------------------------------------------------
       EMAIL
       ----------------------------------------------------- */

    $email = trim(
        $order['email']
        ?? $order['customer_email']
        ?? $order['user_email']
        ?? ''
    );

    $name = trim(
        $order['customer_name']
        ?? $order['name']
        ?? $order['username']
        ?? 'Customer'
    );

    /*
    |--------------------------------------------------------------------------
    | ตรวจ email_sent ก่อน
    |--------------------------------------------------------------------------
    */

    $sent = (int)($tx['email_sent'] ?? 0);

    /* -----------------------------------------------------
       EXPIRE TRANSACTION
       ----------------------------------------------------- */

    $stmt = $conn->prepare(
        "UPDATE payment_transactions
         SET status='expired'
         WHERE id=?
           AND status='pending'"
    );

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    /* -----------------------------------------------------
       CANCEL ORDER
       ----------------------------------------------------- */

    $stmt = $conn->prepare(
        "UPDATE `$table`
         SET payment_status='expired'
         WHERE order_id=?
           AND payment_status<>'paid'"
    );

    if ($stmt) {
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $stmt->close();
    }

    /* status */
    $stmt = $conn->prepare(
        "UPDATE `$table`
         SET status='cancelled'
         WHERE order_id=?"
    );

    if ($stmt) {
        $stmt->bind_param("s", $order_id);
        @$stmt->execute();
        $stmt->close();
    }

    /* order_status */
    $stmt = $conn->prepare(
        "UPDATE `$table`
         SET order_status='cancelled'
         WHERE order_id=?"
    );

    if ($stmt) {
        $stmt->bind_param("s", $order_id);
        @$stmt->execute();
        $stmt->close();
    }

    /* -----------------------------------------------------
       SEND EMAIL
       ----------------------------------------------------- */

    if (
        !$sent &&
        filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {

        $params = [
            'customer_email' => $email,
            'customer_name' => $name,
            'order_id' => $order_id,
            'type' => 'PAYMENT',
            'transaction_id' => $tx_id,
            'amount' => number_format($amount, 2) . ' LAK',
            'status' => 'CANCELLED - PAYMENT TIMEOUT',
            'date' => date('d/m/Y H:i:s')
        ];

        $payload = [
            'service_id' => $EMAILJS_SERVICE,
            'template_id' => $EMAILJS_TEMPLATE,
            'user_id' => $EMAILJS_PUBLIC,
            'template_params' => $params
        ];

        if ($EMAILJS_PRIVATE !== '') {
            $payload['accessToken'] = $EMAILJS_PRIVATE;
        }

        $ch = curl_init(
            'https://api.emailjs.com/api/v1.0/email/send'
        );

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE
            )
        ]);

        $response = curl_exec($ch);

        $http = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        curl_close($ch);

        /* -------------------------------------------------
           EMAIL SUCCESS
           ------------------------------------------------- */

        if ($http >= 200 && $http < 300) {

            $stmt = $conn->prepare(
                "UPDATE payment_transactions
                 SET email_sent=1
                 WHERE id=?
                   AND email_sent=0"
            );

            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    $count++;
}

/* =========================================================
   RELEASE LOCK
   ========================================================= */

$conn->query(
    "SELECT RELEASE_LOCK('cntech_payment_expire')"
);

/* =========================================================
   CRON RESULT
   ========================================================= */

header(
    'Content-Type: application/json; charset=utf-8'
);

echo json_encode([
    'success' => true,
    'expired' => $count,
    'time' => date('Y-m-d H:i:s')
], JSON_UNESCAPED_UNICODE);
?>