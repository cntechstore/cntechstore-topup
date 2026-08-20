<?php

session_start();


$app_id = "1726261521854221"; 
// ใส่ App ID จริงของ Facebook App


$redirect =
"https://cntechstore.shop/oauth/facebook_callback.php";


$url =
"https://www.facebook.com/v20.0/dialog/oauth?"
.http_build_query([

"client_id" => $app_id,

"redirect_uri" => $redirect,

"scope" => "email,public_profile",

"response_type" => "code"

]);


header(
"Location: ".$url
);

exit;

?>