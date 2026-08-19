<?php

session_start();


$client_id="1537990777828024351";


$redirect=
"https://cntechstore.shop/callback/discord_callback.php";



$url=
"https://discord.com/oauth2/authorize?"
.http_build_query([

"client_id"=>$client_id,

"redirect_uri"=>$redirect,

"response_type"=>"code",

"scope"=>"identify email"

]);


header(
"Location: ".$url
);


exit;

?>