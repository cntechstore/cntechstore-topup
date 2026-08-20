<?php

session_start();


// =========================
// GOOGLE CONFIG
// =========================

$client_id =
"374597166583-c9p74v03ndimhpicced9sns43bbonhl6.apps.googleusercontent.com";


// ต้องตรงกับ Google Cloud Console
$redirect_uri =
"https://cntechstore.shop/oauth/google_callback.php";



// =========================
// GOOGLE OAUTH URL
// =========================

$params = [

    "client_id" => $client_id,

    "redirect_uri" => $redirect_uri,

    "response_type" => "code",

    "scope" => "openid email profile",

    "access_type" => "offline",

    // ให้เลือกบัญชีทุกครั้ง
    "prompt" => "select_account"

];


$url =
"https://accounts.google.com/o/oauth2/v2/auth?"
.http_build_query($params);



// =========================
// REDIRECT TO GOOGLE
// =========================

header(
    "Location: ".$url
);

exit;

?>