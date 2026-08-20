<?php

function isPC(){

    $agent = $_SERVER['HTTP_USER_AGENT'];


    $mobile = preg_match(
        '/Android|iPhone|iPad|Mobile/i',
        $agent
    );


    return !$mobile;

}

?>