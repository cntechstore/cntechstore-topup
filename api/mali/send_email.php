<?php

require __DIR__.'/../../vendor/autoload.php';

use Bird\BirdClient;

function sendMailCustomer(
    $to,
    $subject,
    $html
){

    try{

        $bird=
        new BirdClient(
            apiKey:
            getenv(
                'bk_us1_BrKkVQlVbZ44DwCfkK3Hj3e0yxOv5'
            )
        );

        return $bird
            ->email
            ->send([

                "from"=>
                "noreply@cntechstore.com",

                "to"=>[$to],

                "subject"=>$subject,

                "html"=>$html
            ]);

    }catch(Exception $e){

        file_put_contents(

            "logs/email.log",

            $e->getMessage()
            .PHP_EOL,

            FILE_APPEND
        );
    }

    return false;
}