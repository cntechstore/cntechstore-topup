<?php

require __DIR__ . '/../vendor/autoload.php';

use Bird\BirdClient;

/*
========================================
SEND EMAIL (REAL VERSION)
========================================
*/
function sendMailCustomer($to, $subject, $html)
{
    // API KEY (แนะนำย้ายไป .env จริง)
    $apiKey = "bk_us1_BrKkVQlVbZ44DwCfkK3Hj3e0yxOv5";

    try {

        $bird = new BirdClient(apiKey: $apiKey);

        $result = $bird->email->send([
            "from" => "noreply@cntechstore.com",
            "to" => [$to],
            "subject" => $subject,
            "html" => $html
        ]);

        // LOG success
        file_put_contents(
            __DIR__ . "/../logs/email.log",
            date("Y-m-d H:i:s") . " | SUCCESS | $to | $subject\n",
            FILE_APPEND
        );

        return $result;

    } catch (Exception $e) {

        // LOG error
        file_put_contents(
            __DIR__ . "/../logs/email.log",
            date("Y-m-d H:i:s") . " | ERROR | " . $e->getMessage() . "\n",
            FILE_APPEND
        );

        return false;
    }
}