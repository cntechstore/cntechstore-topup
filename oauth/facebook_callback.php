<?php

session_start();

require_once "../database.php";


if(!isset($_GET['code'])){

    die("Facebook Login Failed");

}


$code = $_GET['code'];


// Facebook App ID
$app_id = "1726261521854221";


// Facebook App Secret
$app_secret = "a455938b6a5610b22cf78324ec1d5cfb";


$redirect_uri =
"https://cntechstore.shop/oauth/facebook_callback.php";



/*
=========================
GET ACCESS TOKEN
=========================
*/


$token_url =
"https://graph.facebook.com/v23.0/oauth/access_token?"
.http_build_query([

"client_id"=>$app_id,

"client_secret"=>$app_secret,

"redirect_uri"=>$redirect_uri,

"code"=>$code

]);



$response =
file_get_contents($token_url);



$data =
json_decode($response,true);



if(!isset($data['access_token'])){

    die("Cannot get Facebook Access Token");

}



$access_token =
$data['access_token'];



/*
=========================
GET USER PROFILE
=========================
*/


$user_url =
"https://graph.facebook.com/me?"
.http_build_query([

"fields"=>"id,name,email",

"access_token"=>$access_token

]);



$user_data =
file_get_contents($user_url);



$user =
json_decode($user_data,true);



if(!isset($user['email'])){


die("Facebook email permission required");


}



$email =
$user['email'];


$name =
$user['name'];



/*
=========================
CHECK USER
=========================
*/


$stmt=$conn->prepare("

SELECT *

FROM users

WHERE email=?

LIMIT 1

");


$stmt->bind_param(
"s",
$email
);


$stmt->execute();


$result=$stmt->get_result();



if($result->num_rows > 0){


$row=$result->fetch_assoc();


$_SESSION['user_id']=$row['id'];

$_SESSION['username']=$row['username'];

$_SESSION['role']=$row['role'];



}else{


$username =
"fb_".rand(10000,99999);



$password =
password_hash(
bin2hex(random_bytes(8)),
PASSWORD_DEFAULT
);



$stmt=$conn->prepare("

INSERT INTO users

(
username,
email,
fullname,
password,
role,
oauth_provider,
created_at

)

VALUES

(
?,
?,
?,
?,
'user',
'facebook',
NOW()

)

");



$stmt->bind_param(

"ssss",

$username,

$email,

$name,

$password

);



$stmt->execute();



$user_id =
$conn->insert_id;



$_SESSION['user_id']=$user_id;

$_SESSION['username']=$username;

$_SESSION['role']="user";



/*
=========================
WELCOME NOTIFICATION
=========================
*/


$title =
"Welcome to CN Tech Store";


$message =
"Your Facebook account has been connected successfully.";



$stmt2=$conn->prepare("

INSERT INTO notifications

(
user_id,
title,
message,
type,
is_read,
created_at

)

VALUES

(
?,
?,
?,
'system',
0,
NOW()

)

");



$stmt2->bind_param(

"iss",

$user_id,

$title,

$message

);


$stmt2->execute();



}



header(
"Location: ../dashboard.php"
);

exit;


?>