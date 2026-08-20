<?php

function checkMobileOnly(){

    $userAgent = $_SERVER['HTTP_USER_AGENT'];


    $isMobile = preg_match(
        '/Android|iPhone|iPad|iPod|Mobile/i',
        $userAgent
    );


    if(!$isMobile){

        include __DIR__ . "/pc_notice.php";

        exit();

    }

}

?>