<?php  
  
header("Content-Type: application/json");  
require_once "config.php";  
  
$data = json_decode(file_get_contents("php://input"), true);  
  
$game_id = (int)($data['game_id'] ?? 0);  
$uid     = trim($data['uid'] ?? '');  
$server  = trim($data['server'] ?? '');  
  
if($uid == ""){  
    echo json_encode([  
        "success"=>false,  
        "message"=>"UID Empty"  
    ]);  
    exit;  
}  
  
  
/*  
|--------------------------------------------------------------------------  
| TEST MODE  
|--------------------------------------------------------------------------  
*/  
if(PAYMENT_MODE === "TEST"){  
  
    echo json_encode([  
        "success"=>true,  
        "nickname"=>"TEST_PLAYER_".$uid,  
        "uid"=>$uid,  
        "server"=>$server  
    ]);  
    exit;  
}  
  
  
/*  
|--------------------------------------------------------------------------  
| LIVE MODE (REAL API LAYER)  
|--------------------------------------------------------------------------  
*/  
  
$result = null;  
  
  
/*  
|--------------------------------------------------------------------------  
| 🔥 GAME ROUTER  
|--------------------------------------------------------------------------  
*/  
  
switch($game_id){  
  
    /*  
    |--------------------------------------------------------------------------  
    | ROV (Garena API / Partner API placeholder)  
    |--------------------------------------------------------------------------  
    */  
    case 1:  
  
        $result = callGameAPI([  
            "url"   => ROV_API_URL,  
            "token" => ROV_API_TOKEN,  
            "uid"   => $uid,  
            "server"=> $server  
        ]);  
  
        break;  
  
  
    /*  
    |--------------------------------------------------------------------------  
    | MLBB (Moonton / Codashop API)  
    |--------------------------------------------------------------------------  
    */  
    case 2:  
  
        $result = callGameAPI([  
            "url"   => MLBB_API_URL,  
            "token" => MLBB_API_TOKEN,  
            "uid"   => $uid,  
            "server"=> $server  
        ]);  
  
        break;  
  
  
    /*  
    |--------------------------------------------------------------------------  
    | HOK  
    |--------------------------------------------------------------------------  
    */  
    case 3:  
  
        $result = callGameAPI([  
            "url"   => HOK_API_URL,  
            "token" => HOK_API_TOKEN,  
            "uid"   => $uid,  
            "server"=> $server  
        ]);  
  
        break;  
  
  
    /*  
    |--------------------------------------------------------------------------  
    | FREE FIRE  
    |--------------------------------------------------------------------------  
    */  
    case 4:  
  
        $result = callGameAPI([  
            "url"   => FF_API_URL,  
            "token" => FF_API_TOKEN,  
            "uid"   => $uid,  
            "server"=> $server  
        ]);  
  
        break;  
  
  
    /*  
    |--------------------------------------------------------------------------  
    | PUBG  
    |--------------------------------------------------------------------------  
    */  
    case 5:  
  
        $result = callGameAPI([  
            "url"   => PUBG_API_URL,  
            "token" => PUBG_API_TOKEN,  
            "uid"   => $uid,  
            "server"=> $server  
        ]);  
  
        break;  
  
  
    default:  
  
        echo json_encode([  
            "success"=>false,  
            "message"=>"Game not supported"  
        ]);  
        exit;  
}  
  
  
/*  
|--------------------------------------------------------------------------  
| RESPONSE  
|--------------------------------------------------------------------------  
*/  
  
if(!$result){  
    echo json_encode([  
        "success"=>false,  
        "message"=>"API Error"  
    ]);  
    exit;  
}  
  
echo json_encode($result);  
exit;  
  
  
  
/*  
|--------------------------------------------------------------------------  
| 🔥 REAL API FUNCTION (READY FOR BCEL / CODASHOP / GARENA)  
|--------------------------------------------------------------------------  
*/  
  
function callGameAPI($cfg){  
  
    // ถ้ายังไม่มี API → fallback safe mode  
    if(empty($cfg['url']) || empty($cfg['token'])){  
        return [  
            "success"=>false,  
            "message"=>"API Not Configured"  
        ];  
    }  
  
    $payload = [  
        "uid"    => $cfg['uid'],  
        "server" => $cfg['server']  
    ];  
  
    $ch = curl_init();  
  
    curl_setopt_array($ch, [  
        CURLOPT_URL => $cfg['url'],  
        CURLOPT_RETURNTRANSFER => true,  
        CURLOPT_POST => true,  
        CURLOPT_HTTPHEADER => [  
            "Authorization: Bearer ".$cfg['token'],  
            "Content-Type: application/json"  
        ],  
        CURLOPT_POSTFIELDS => json_encode($payload)  
    ]);  
  
    $response = curl_exec($ch);  
    curl_close($ch);  
  
    $data = json_decode($response, true);  
  
    // กัน API พัง  
    if(!$data){  
        return [  
            "success"=>false,  
            "message"=>"Unable to verify Player ID. Please check your ID and try again."  
        ];  
    }  
  
    /*  
    |--------------------------------------------------------------------------  
    | normalize format  
    |--------------------------------------------------------------------------  
    */  
    return [  
        "success"  => $data['success'] ?? false,  
        "nickname" => $data['nickname'] ?? '',  
        "uid"      => $cfg['uid'],  
        "server"   => $cfg['server']  
    ];  
}  
  
?>��ະລຸນາປ້ອນ Player ID",
        "invalid_uid"
    );
}


/*
|--------------------------------------------------------------------------
| LOAD GAME
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        api_type,
        status
    FROM games
    WHERE id=?
    LIMIT 1
");


if (!$stmt) {

    debugError(
        "ບໍ່ສາມາດສ້າງ Database Query",
        "database_query",
        [
            "sql_error" => $conn->error
        ]
    );
}


$stmt->bind_param(
    "i",
    $game_id
);


if (!$stmt->execute()) {

    $error = $stmt->error;

    $stmt->close();

    debugError(
        "ບໍ່ສາມາດໂຫຼດເກມ",
        "database_execute",
        [
            "sql_error" => $error
        ]
    );
}


$result = $stmt->get_result();

$game = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| GAME NOT FOUND
|--------------------------------------------------------------------------
*/

if (!$game) {

    debugError(
        "ບໍ່ພົບເກມ",
        "game_not_found",
        [
            "game_id" => $game_id
        ]
    );
}


/*
|--------------------------------------------------------------------------
| GAME STATUS
|--------------------------------------------------------------------------
*/

$game_status = strtolower(
    trim(
        (string)(
            $game["status"] ?? ""
        )
    )
);


if ($game_status !== "active") {

    apiResponse(
        false,
        "ເກມນີ້ບໍ່ເປີດໃຫ້ບໍລິການ",
        [
            "error_code" => "GAME_INACTIVE",
            "game_id" => $game_id,
            "game" => $game["name"]
        ]
    );
}


/*
|--------------------------------------------------------------------------
| API TYPE
|--------------------------------------------------------------------------
*/

$api_type = strtolower(
    trim(
        (string)(
            $game["api_type"] ??
            ""
        )
    )
);


/*
|--------------------------------------------------------------------------
| LOAD API CONFIG
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        game_id,
        provider,
        api_url,
        api_token,
        method,
        timeout,
        enabled
    FROM game_api_configs
    WHERE game_id=?
    AND enabled=1
    ORDER BY id DESC
    LIMIT 1
");


if (!$stmt) {

    debugError(
        "ບໍ່ສາມາດໂຫຼດ API Configuration",
        "api_config_query",
        [
            "sql_error" => $conn->error
        ]
    );
}


$stmt->bind_param(
    "i",
    $game_id
);


if (!$stmt->execute()) {

    $error = $stmt->error;

    $stmt->close();

    debugError(
        "ບໍ່ສາມາດອ່ານ API Configuration",
        "api_config_execute",
        [
            "sql_error" => $error
        ]
    );
}


$result = $stmt->get_result();

$api = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| API NOT CONFIGURED
|--------------------------------------------------------------------------
*/

if (!$api) {

    apiResponse(
        false,
        "ເກມນີ້ຍັງບໍ່ມີ API ກວດ Player ID",
        [
            "error_code" => "API_NOT_CONFIGURED",

            "game_id" =>
                $game_id,

            "game" =>
                $game["name"],

            "api_type" =>
                $api_type,

            "provider" =>
                null
        ]
    );
}


/*
|--------------------------------------------------------------------------
| API SETTINGS
|--------------------------------------------------------------------------
*/

$api_id = (int)(
    $api["id"] ?? 0
);

$provider = trim(
    (string)(
        $api["provider"] ??
        "custom"
    )
);

$api_url = trim(
    (string)(
        $api["api_url"] ??
        ""
    )
);

$api_token = trim(
    (string)(
        $api["api_token"] ??
        ""
    )
);

$method = strtoupper(
    trim(
        (string)(
            $api["method"] ??
            "POST"
        )
    )
);

$timeout = (int)(
    $api["timeout"] ??
    15
);


if ($timeout <= 0) {
    $timeout = 15;
}


/*
|--------------------------------------------------------------------------
| API URL VALIDATION
|--------------------------------------------------------------------------
*/

if ($api_url === "") {

    debugError(
        "API URL ຍັງບໍ່ໄດ້ຕັ້ງຄ່າ",
        "api_url_empty",
        [
            "game_id" =>
                $game_id,

            "game" =>
                $game["name"],

            "provider" =>
                $provider
        ]
    );
}


/*
|--------------------------------------------------------------------------
| API URL VALID
|--------------------------------------------------------------------------
*/

if (
    !filter_var(
        $api_url,
        FILTER_VALIDATE_URL
    )
) {

    debugError(
        "API URL ບໍ່ຖືກຕ້ອງ",
        "api_url_invalid",
        [
            "api_url" =>
                $DEBUG
                ? $api_url
                : "[hidden]"
        ]
    );
}


/*
|--------------------------------------------------------------------------
| METHOD
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $method,
        [
            "GET",
            "POST"
        ],
        true
    )
) {

    debugError(
        "API Method ບໍ່ຮອງຮັບ",
        "method_invalid",
        [
            "method" =>
                $method
        ]
    );
}


/*
|--------------------------------------------------------------------------
| PAYLOAD
|--------------------------------------------------------------------------
*/

$payload = [

    "game_id" =>
        $game_id,

    "uid" =>
        $uid,

    "player_id" =>
        $uid,

    "server" =>
        $server
];


/*
|--------------------------------------------------------------------------
| HEADERS
|--------------------------------------------------------------------------
*/

$headers = [

    "Accept: application/json",

    "Content-Type: application/json",

    "User-Agent: CNTECH-STORE/1.0",

    "X-CNTECH-REQUEST-ID: " .
        $request_id

];


/*
|--------------------------------------------------------------------------
| TOKEN
|--------------------------------------------------------------------------
*/

if ($api_token !== "") {

    $headers[] =
        "Authorization: Bearer " .
        $api_token;
}


/*
|--------------------------------------------------------------------------
| CURL CHECK
|--------------------------------------------------------------------------
*/

if (!function_exists("curl_init")) {

    debugError(
        "Server ບໍ່ເປີດ PHP cURL",
        "curl_missing"
    );
}


/*
|--------------------------------------------------------------------------
| CURL INIT
|--------------------------------------------------------------------------
*/

$curl = curl_init();


if (!$curl) {

    debugError(
        "ບໍ່ສາມາດເລີ່ມ cURL",
        "curl_init"
    );
}


/*
|--------------------------------------------------------------------------
| REQUEST URL
|--------------------------------------------------------------------------
*/

$request_url = $api_url;


/*
|--------------------------------------------------------------------------
| GET REQUEST
|--------------------------------------------------------------------------
*/

if ($method === "GET") {

    $query = http_build_query(
        $payload
    );

    $request_url .=
        (
            strpos(
                $request_url,
                "?"
            ) === false
            ? "?"
            : "&"
        ) .
        $query;
}


/*
|--------------------------------------------------------------------------
| CURL OPTIONS
|--------------------------------------------------------------------------
*/

$options = [

    CURLOPT_URL =>
        $request_url,

    CURLOPT_RETURNTRANSFER =>
        true,

    CURLOPT_HEADER =>
        false,

    CURLOPT_FOLLOWLOCATION =>
        false,

    CURLOPT_CONNECTTIMEOUT =>
        8,

    CURLOPT_TIMEOUT =>
        $timeout,

    CURLOPT_HTTPHEADER =>
        $headers,

    CURLOPT_USERAGENT =>
        "CNTECH-STORE/1.0"

];


/*
|--------------------------------------------------------------------------
| POST
|--------------------------------------------------------------------------
*/

if ($method === "POST") {

    $json_payload = json_encode(
        $payload,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );


    if ($json_payload === false) {

        curl_close($curl);

        debugError(
            "ບໍ່ສາມາດສ້າງ JSON",
            "json_encode"
        );
    }


    $options[
        CURLOPT_POST
    ] = true;


    $options[
        CURLOPT_POSTFIELDS
    ] = $json_payload;
}


/*
|--------------------------------------------------------------------------
| SET CURL
|--------------------------------------------------------------------------
*/

curl_setopt_array(
    $curl,
    $options
);


/*
|--------------------------------------------------------------------------
| EXECUTE
|--------------------------------------------------------------------------
*/

$api_response = curl_exec(
    $curl
);


/*
|--------------------------------------------------------------------------
| CURL INFORMATION
|--------------------------------------------------------------------------
*/

$curl_errno = curl_errno(
    $curl
);

$curl_error = curl_error(
    $curl
);

$http_code = curl_getinfo(
    $curl,
    CURLINFO_HTTP_CODE
);

$content_type = curl_getinfo(
    $curl,
    CURLINFO_CONTENT_TYPE
);

$total_time = curl_getinfo(
    $curl,
    CURLINFO_TOTAL_TIME
);


curl_close($curl);


/*
|--------------------------------------------------------------------------
| CURL ERROR
|--------------------------------------------------------------------------
*/

if (
    $api_response === false
) {

    verifyLog(
        "CURL ERROR " .
        $curl_errno .
        " " .
        $curl_error
    );


    debugError(
        "ບໍ່ສາມາດເຊື່ອມຕໍ່ API",
        "curl_error",
        [

            "provider" =>
                $provider,

            "http_code" =>
                $http_code,

            "curl_errno" =>
                $curl_errno,

            "curl_error" =>
                $curl_error,

            "time" =>
                $total_time

        ]
    );
}


/*
|--------------------------------------------------------------------------
| EMPTY RESPONSE
|--------------------------------------------------------------------------
*/

if (
    $api_response === null ||
    trim(
        (string)$api_response
    ) === ""
) {

    debugError(
        "API ບໍ່ສົ່ງຂໍ້ມູນກັບຄືນ",
        "empty_response",
        [

            "provider" =>
                $provider,

            "http_code" =>
                $http_code,

            "content_type" =>
                $content_type,

            "time" =>
                $total_time

        ]
    );
}


/*
|--------------------------------------------------------------------------
| HTTP ERROR
|--------------------------------------------------------------------------
*/

if (
    $http_code < 200 ||
    $http_code >= 300
) {

    verifyLog(
        "HTTP ERROR " .
        $http_code .
        " PROVIDER=" .
        $provider
    );


    $debug_response =
        $DEBUG
        ? mb_substr(
            (string)$api_response,
            0,
            1000
        )
        : "[hidden]";


    debugError(
        "API ຕອບກັບ HTTP " .
        $http_code,
        "http_error",
        [

            "provider" =>
                $provider,

            "http_code" =>
                $http_code,

            "content_type" =>
                $content_type,

            "response" =>
                $debug_response,

            "time" =>
                $total_time

        ]
    );
}


/*
|--------------------------------------------------------------------------
| JSON DECODE
|--------------------------------------------------------------------------
*/

$api_data = json_decode(
    $api_response,
    true
);


if (
    json_last_error() !== JSON_ERROR_NONE
) {

    verifyLog(
        "INVALID JSON: " .
        json_last_error_msg()
    );


    debugError(
        "API ສົ່ງຂໍ້ມູນບໍ່ແມ່ນ JSON",
        "invalid_api_json",
        [

            "provider" =>
                $provider,

            "http_code" =>
                $http_code,

            "json_error" =>
                json_last_error_msg(),

            "response" =>
                mb_substr(
                    (string)$api_response,
                    0,
                    1000
                )

        ]
    );
}


/*
|--------------------------------------------------------------------------
| RESPONSE MUST ARRAY
|--------------------------------------------------------------------------
*/

if (!is_array($api_data)) {

    debugError(
        "API Response ບໍ່ຖືກຮູບແບບ",
        "invalid_api_response"
    );
}


/*
|--------------------------------------------------------------------------
| FIND SUCCESS
|--------------------------------------------------------------------------
*/

$success = false;


/*
|--------------------------------------------------------------------------
| COMMON SUCCESS FIELDS
|--------------------------------------------------------------------------
*/

if (
    isset(
        $api_data["success"]
    )
) {

    $success =
        $api_data["success"];
}

elseif (
    isset(
        $api_data["verified"]
    )
) {

    $success =
        $api_data["verified"];
}

elseif (
    isset(
        $api_data["valid"]
    )
) {

    $success =
        $api_data["valid"];
}

elseif (
    isset(
        $api_data["status"]
    )
) {

    $success =
        $api_data["status"];
}


/*
|--------------------------------------------------------------------------
| NORMALIZE BOOLEAN
|--------------------------------------------------------------------------
*/

if (
    is_string($success)
) {

    $success =
        in_array(
            strtolower(
                trim($success)
            ),
            [

                "1",
                "true",
                "success",
                "successful",
                "ok",
                "valid",
                "verified",
                "yes"

            ],
            true
        );
}

else {

    $success =
        (bool)$success;
}


/*
|--------------------------------------------------------------------------
| NICKNAME
|--------------------------------------------------------------------------
*/

$nickname =
    $api_data["nickname"]
    ?? $api_data["name"]
    ?? $api_data["player_name"]
    ?? $api_data["username"]
    ?? $api_data["player"]["name"]
    ?? $api_data["data"]["nickname"]
    ?? $api_data["data"]["name"]
    ?? $api_data["data"]["player_name"]
    ?? "";


/*
|--------------------------------------------------------------------------
| UID
|--------------------------------------------------------------------------
*/

$return_uid =
    $api_data["uid"]
    ?? $api_data["player_id"]
    ?? $api_data["playerId"]
    ?? $api_data["data"]["uid"]
    ?? $api_data["data"]["player_id"]
    ?? $uid;


/*
|--------------------------------------------------------------------------
| SERVER
|--------------------------------------------------------------------------
*/

$return_server =
    $api_data["server"]
    ?? $api_data["zone"]
    ?? $api_data["server_id"]
    ?? $api_data["data"]["server"]
    ?? $api_data["data"]["zone"]
    ?? $server;


/*
|--------------------------------------------------------------------------
| MESSAGE
|--------------------------------------------------------------------------
*/

$api_message =
    $api_data["message"]
    ?? $api_data["msg"]
    ?? $api_data["error"]
    ?? $api_data["description"]
    ?? $api_data["data"]["message"]
    ?? "";


/*
|--------------------------------------------------------------------------
| API FAILED
|--------------------------------------------------------------------------
*/

if (!$success) {

    verifyLog(
        "PLAYER VERIFY FAILED " .
        "GAME=" .
        $game_id .
        " PROVIDER=" .
        $provider
    );


    $extra = [

        "error_code" =>
            "PLAYER_VERIFY_FAILED",

        "game_id" =>
            $game_id,

        "game" =>
            $game["name"],

        "provider" =>
            $provider,

        "uid" =>
            $uid,

        "server" =>
            $server

    ];


    if ($DEBUG) {

        $extra["debug"] = [

            "stage" =>
                "api_response",

            "http_code" =>
                $http_code,

            "api_response" =>
                mb_substr(
                    (string)$api_response,
                    0,
                    1000
                )

        ];
    }


    apiResponse(
        false,

        $api_message !== ""
            ? $api_message
            : "ບໍ່ສາມາດຢືນຢັນ Player ID",

        $extra
    );
}


/*
|--------------------------------------------------------------------------
| SUCCESS
|--------------------------------------------------------------------------
*/

apiResponse(
    true,
    "ກວດສອບ Player ID ສຳເລັດ",
    [

        "game_id" =>
            $game_id,

        "game" =>
            $game["name"],

        "provider" =>
            $provider,

        "nickname" =>
            $nickname,

        "uid" =>
            $return_uid,

        "server" =>
            $return_server

    ]
);

?>