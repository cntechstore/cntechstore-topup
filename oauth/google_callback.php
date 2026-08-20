<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();

require_once "../database.php";


// =========================
// GOOGLE CONFIG
// =========================

$client_id =
"374597166583-c9p74v03ndimhpicced9sns43bbonhl6.apps.googleusercontent.com";


$client_secret =
"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";


$redirect_uri =
"https://cntechstore.shop/oauth/google_callback.php";



// =========================
// CHECK CODE
// =========================

if(!isset($_GET['code'])){

    die("Google Authorization Failed");

}


$code = $_GET['code'];



// =========================
// GET ACCESS TOKEN
// =========================

$token_url =
"https://oauth2.googleapis.com/token";


$post_data = [

    "code" => $code,

    "client_id" => $client_id,

    "client_secret" => $client_secret,

    "redirect_uri" => $redirect_uri,

    "grant_type" => "authorization_code"

];


$ch = curl_init();


curl_setopt($ch,CURLOPT_URL,$token_url);

curl_setopt($ch,CURLOPT_POST,true);

curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    http_build_query($post_data)
);

curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


$response = curl_exec($ch);


curl_close($ch);



$token = json_decode(
    $response,
    true
);



if(!isset($token['access_token'])){

    die("Cannot get Google Access Token");

}



$access_token =
$token['access_token'];



// =========================
// GET GOOGLE USER DATA
// =========================


$ch = curl_init();


curl_setopt(
    $ch,
    CURLOPT_URL,
    "https://www.googleapis.com/oauth2/v2/userinfo?access_token=".$access_token
);


curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


$user_json = curl_exec($ch);


curl_close($ch);



$google =
json_decode(
    $user_json,
    true
);



if(!isset($google['email'])){

    die("Google User Data Error");

}



$google_id =
$google['id'] ?? "";


$email =
$google['email'];


$name =
$google['name'] ?? "Google User";


$avatar =
$google['picture'] ?? "";




// =========================
// CHECK USER
// =========================


$stmt = $conn->prepare("

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


$result =
$stmt->get_result();




// =========================
// EXIST USER
// =========================

if($result->num_rows > 0){


    $user =
    $result->fetch_assoc();



    $_SESSION['user_id'] =
    $user['id'];


    $_SESSION['username'] =
    $user['username'];


    $_SESSION['role'] =
    $user['role'];



}else{


// =========================
// CREATE USER
// =========================


$username =
"google_".time();



$role =
"user";



$stmt =
$conn->prepare("

INSERT INTO users

(
username,
email,
fullname,
avatar,
oauth_provider,
oauth_id,
role,
created_at

)

VALUES

(
?,
?,
?,
?,
'google',
?,
?,
NOW()

)

");



$stmt->bind_param(

"ssssss",

$username,

$email,

$name,

$avatar,

$google_id,

$role

);



if(!$stmt->execute()){

    die(
        "Create User Error: ".$conn->error
    );

}



$user_id =
$conn->insert_id;



$_SESSION['user_id'] =
$user_id;


$_SESSION['username'] =
$username;


$_SESSION['role'] =
"user";




// =========================
// WELCOME NOTIFICATION
// =========================


$title =
"Welcome to CN Tech Store";


$message =
"Your Google account has been connected successfully.";



$stmt2 =
$conn->prepare("

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



// =========================
// LOGIN SUCCESS
// =========================


header(
"Location: ../dashboard.php"
);

exit;


?>