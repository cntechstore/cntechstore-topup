<?php
if (session_status() === PHP_SESSION_NONE) {

    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);

    if (
        !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'on'
    ) {
        ini_set('session.cookie_secure', 1);
    }

    session_start();
}

/*
|--------------------------------------------------------------------------
| CNTECH STORE - OCR CONFIG
|--------------------------------------------------------------------------
*/

define(
    'OCR_SPACE_API_KEY',
    'K86925528788957'
);

define(
    'OCR_SPACE_ENDPOINT',
    'https://api.ocr.space/parse/image'
);

define(
    'OCR_ENGINE',
    '2'
);

/*
================================================
CN TECH STORE CONFIG
Version : v1.6.0 PRODUCTION
================================================
*/

if (
    basename($_SERVER['PHP_SELF']) === 'config.php'
) {
    http_response_code(403);
    exit('Forbidden');
}

require_once __DIR__ . "/security.php";
require_once __DIR__ . "/env.php";
require_once __DIR__ . "/site_guard.php";
/*
================================================
TIMEZONE
================================================
*/

date_default_timezone_set("Asia/Vientiane");

/*
================================================
PC / MOBILE CHECK
================================================
*/

require_once __DIR__ . "/pc_check.php";

/*
checkMobileOnly();
*/

$currentURL =
"https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

/*
================================================
ENVIRONMENT
================================================
*/

define("SITE_MODE", "LIVE");
// LIVE / DEV

define(
    "SITE_STATUS",
    SITE_MODE === "LIVE"
        ? "ONLINE"
        : "DEV"
);

define(
    "PAYMENT_MODE",
    SITE_MODE === "LIVE"
        ? "LIVE"
        : "TEST"
);



/*
================================================
HTTPS FORCE
================================================
*/

if (
    SITE_MODE === "LIVE"
) {

    $https =
    (!empty($_SERVER['HTTPS']) 
    && $_SERVER['HTTPS'] !== 'off')
    ||
    (
        isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
        &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
    );


    if(!$https){

        header(
            "Location: https://" .
            $_SERVER['HTTP_HOST'] .
            $_SERVER['REQUEST_URI']
        );

        exit;

    }

}

/*
================================================
BASE URL
================================================
*/

define("BASE_URL", "/");

/*
================================================
SITE URL
================================================
*/

define(
    "SITE_URL",
    SITE_MODE === "LIVE"
        ? "https://cntechstore.shop"
        : "http://localhost"
);

/*
================================================
SITE INFO
================================================
*/

define("APP_NAME", "CN Tech Store");
define("APP_VERSION", "v1.6.0");
define("ASSET_VERSION", "1.6.0");
define("APP_DOMAIN", "cntechstore.shop");
define("SITE_EMAIL", "support@cntechstore.shop");
define("SITE_COUNTRY", "Lao PDR");

/*
================================================
DATABASE
================================================
*/

define("DB_HOST", $_ENV['DB_HOST'] ?? '');
define("DB_NAME", $_ENV['DB_NAME'] ?? '');
define("DB_USER", $_ENV['DB_USER'] ?? '');
define("DB_PASS", $_ENV['DB_PASS'] ?? '');
define("DB_CHARSET", "utf8mb4");
define("DB_COLLATE", "utf8mb4_unicode_ci");

/*
================================================
PAYMENT GATEWAY
================================================
*/

define(
    "BCEL_API_URL",
    $_ENV['BCEL_API_URL']
        ?? 'https://api-bcel.com/v1/payment/create'
);

define(
    "BCEL_TOKEN",
    $_ENV['BCEL_TOKEN']
        ?? ''
);

define(
    "LDB_API_URL",
    $_ENV['LDB_API_URL']
        ?? 'https://api-ldb.com/payment'
);

define(
    "LDB_TOKEN",
    $_ENV['LDB_TOKEN']
        ?? ''
);

define(
    "VISA_API_URL",
    $_ENV['VISA_API_URL']
        ?? 'https://api-visa-gateway.com/pay'
);

define(
    "VISA_TOKEN",
    $_ENV['VISA_TOKEN']
        ?? ''
);

/*
================================================
GAME API
================================================
*/

define(
    "GAME_API_MODE",
    SITE_MODE === "LIVE"
        ? "LIVE"
        : "TEST"
);

define(
    "MLBB_API_URL",
    $_ENV['MLBB_API_URL']
        ?? ''
);

define(
    "MLBB_API_TOKEN",
    $_ENV['MLBB_API_TOKEN']
        ?? ''
);

define(
    "HOK_API_URL",
    $_ENV['HOK_API_URL']
        ?? ''
);

define(
    "HOK_API_TOKEN",
    $_ENV['HOK_API_TOKEN']
        ?? ''
);

define(
    "ROV_API_URL",
    $_ENV['ROV_API_URL']
        ?? ''
);

define(
    "ROV_API_TOKEN",
    $_ENV['ROV_API_TOKEN']
        ?? ''
);

/*
================================================
CODASHOP API
================================================
*/

define(
    "CODA_API_URL",
    $_ENV['CODA_API_URL']
        ?? ''
);

define(
    "CODA_CLIENT_ID",
    $_ENV['CODA_CLIENT_ID']
        ?? ''
);

define(
    "CODA_CLIENT_SECRET",
    $_ENV['CODA_CLIENT_SECRET']
        ?? ''
);

/*
================================================
STRIPE
================================================
*/

define(
    "STRIPE_MODE",
    SITE_MODE === "LIVE"
        ? "live"
        : "test"
);

define(
    "STRIPE_SECRET_KEY",
    $_ENV['STRIPE_SECRET_KEY']
        ?? ''
);

/*
================================================
ORDER SECURITY
================================================
*/

define(
    "ORDER_SECRET",
    $_ENV['ORDER_SECRET']
        ?? 'CHANGE_ME'
);

/*
================================================
SESSION SECURITY
================================================
*/


/*
================================================
DEBUG
================================================
*/

if (SITE_MODE === "DEV") {

    error_reporting(E_ALL);

    ini_set(
        "display_errors",
        1
    );

} else {

    error_reporting(0);

    ini_set(
        "display_errors",
        0
    );

}