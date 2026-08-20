<?php

session_start();


// ลบ Remember Me Cookie
if(isset($_COOKIE['remember_token'])){

    setcookie(
        "remember_token",
        "",
        time() - 3600,
        "/",
        "",
        true,
        true
    );

}


// ล้าง Session ทั้งหมด

$_SESSION = [];


// ทำลาย Session

if(ini_get("session.use_cookies")){

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );

}


session_destroy();


// กลับหน้า Login

header(
"Location: ../login.php?logout=success"
);

exit;

?>