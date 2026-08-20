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
            "message"=>"Invalid API response"
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

?>