<?php

session_start();

require_once "../config.php";
require_once "../database.php";

const CLIENT_ID = '1537990777828024351';
const CLIENT_SECRET = 'lLDyXurpdMWvSmvJAgeCIP5y7axyiq24';
const REDIRECT_URI = 'https://cntechstore.shop/callback/discord_callback.php';

if (empty($_GET['code'])) {
    exit('Discord Login Failed');
}

$code = trim($_GET['code']);

/* GET ACCESS TOKEN */
$ch = curl_init('https://discord.com/api/oauth2/token');

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'client_id'     => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'grant_type'    => 'authorization_code',
        'code'          => $code,
        'redirect_uri'  => REDIRECT_URI
    ])
]);

$res = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($res, true);

if ($http !== 200 || empty($data['access_token'])) {
    exit('Discord authentication failed');
}

$token = $data['access_token'];

/* GET DISCORD USER */
$ch = curl_init('https://discord.com/api/users/@me');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer '.$token
    ]
]);

$res = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$discord = json_decode($res, true);

if ($http !== 200 || empty($discord['id'])) {
    exit('Failed to get Discord user');
}

/* USER DATA */
$discordId = $discord['id'];

$username = preg_replace(
    '/[^a-zA-Z0-9_]/',
    '',
    $discord['username'] ?? 'discord'
);

$fullname = $discord['global_name']
    ?? $discord['username']
    ?? 'Discord User';

$email = $discord['email']
    ?? 'discord_'.$discordId.'@discord.local';

/* FIND USER */
$stmt = $conn->prepare(
    "SELECT id,username,role FROM users WHERE email=? LIMIT 1"
);

$stmt->bind_param('s', $email);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* CREATE USER */
if (!$user) {

    $newUsername = $username.'_'.substr($discordId, -6);

    $stmt = $conn->prepare("
        INSERT INTO users
        (username,email,fullname,role,oauth_provider,created_at)
        VALUES (?,?,?,'user','discord',NOW())
    ");

    $stmt->bind_param(
        'sss',
        $newUsername,
        $email,
        $fullname
    );

    if (!$stmt->execute()) {
        exit('Account creation failed');
    }

    $user = [
        'id' => $conn->insert_id,
        'username' => $newUsername,
        'role' => 'user'
    ];

    $stmt->close();
}

/* LOGIN */
session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'] ?? 'user';
$_SESSION['login_provider'] = 'discord';
$_SESSION['discord_id'] = $discordId;

header('Location: ../dashboard.php');
exit;