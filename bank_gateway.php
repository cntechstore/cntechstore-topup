<?php

/**
 * CN Tech Store
 * BANK GATEWAY LAYER
 *
 * MODE:
 * DEV  = ใช้ QR จำลอง
 * LIVE = เชื่อม Bank API จริง
 */

// ===========================
// PAYMENT MODE
// ===========================

define("PAYMENT_MODE", "LIVE");


// ===========================
// CREATE BANK PAYMENT
// ===========================

function createBankPayment($order_id, $bank, $amount)
{

    // ---------------------------
    // DEV MODE
    // ---------------------------
    if (PAYMENT_MODE === "DEV") {

        return [
            "success" => true,
            "status" => "MOCK",
            "bank" => $bank,
            "amount" => $amount,
            "currency" => "LAK",

            // ไปหน้า QR ของระบบเรา
            "payment_url" =>
                "bank_qr.php?order_id=" . $order_id
        ];
    }


    // ---------------------------
    // LIVE MODE
    // ---------------------------
    if (PAYMENT_MODE === "LIVE") {


        // ข้อมูลส่งให้ธนาคาร
        $payload = [
            "merchant_id" =>
                "YOUR_MERCHANT_ID",

            "order_id" =>
                $order_id,

            "amount" =>
                $amount,

            "currency" =>
                "LAK",

            "callback_url" =>
                "https://cntechstore.com/webhook_bank.php"
        ];


        // URL API จริงของธนาคาร
        $api_url =
            "https://BANK_API_URL/payment";


        $ch = curl_init($api_url);


        curl_setopt_array($ch, [

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS =>
                json_encode($payload),

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CONNECTTIMEOUT => 5,

            CURLOPT_TIMEOUT => 10,

            CURLOPT_HTTPHEADER => [

                "Content-Type: application/json",

                "Authorization: Bearer YOUR_SECRET_KEY"

            ]

        ]);


        $response = curl_exec($ch);


        // CURL ERROR
        if (curl_errno($ch)) {

            $error = curl_error($ch);

            curl_close($ch);


            return [

                "success" => false,

                "message" =>
                    "Bank connection failed: " . $error

            ];
        }


        curl_close($ch);


        // Decode JSON
        $data = json_decode($response, true);


        if (!$data) {

            return [

                "success" => false,

                "message" =>
                    "Invalid Bank API response"

            ];
        }


        return $data;
    }


    // ---------------------------
    // INVALID MODE
    // ---------------------------
    return [

        "success" => false,

        "message" => "Invalid Payment Mode"

    ];
}

?>