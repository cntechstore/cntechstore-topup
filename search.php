<?php

header("Content-Type: application/json; charset=utf-8");

require_once "database.php";

error_reporting(E_ALL);
ini_set("display_errors", 0);


/*
==================================================
INPUT
==================================================
*/

$q = trim($_GET['q'] ?? '');

if($q === ''){

    echo json_encode([
        "success" => false,
        "message" => "ກະລຸນາພິມຄຳຄົ້ນຫາ",
        "results" => []
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    exit;
}

$keyword = "%" . $q . "%";

$results = [];


/*
==================================================
GAMES
==================================================
*/

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        icon
    FROM games
    WHERE status='active'
    AND (
        name LIKE ?
    )
    ORDER BY id DESC
    LIMIT 8
");

if($stmt){

    $stmt->bind_param(
        "s",
        $keyword
    );

    $stmt->execute();

    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){

        $results[] = [

            "type" => "Game",

            "name" =>
                $row['name'],

            "price" => "",

            "price_text" => " Game",

            "image" =>
                !empty($row['icon'])
                ? "/uploads/" . $row['icon']
                : "",

            "url" =>
                "/search/game.php?id=" .
                (int)$row['id']

        ];

    }

    $stmt->close();
}


/*
==================================================
PRODUCTS
==================================================
*/

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        image,
        price
    FROM products
    WHERE status='active'
    AND (
        name LIKE ?
        OR description LIKE ?
        OR category LIKE ?
    )
    ORDER BY id DESC
    LIMIT 8
");

if($stmt){

    $stmt->bind_param(
        "sss",
        $keyword,
        $keyword,
        $keyword
    );

    $stmt->execute();

    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){

        $price =
            (float)($row['price'] ?? 0);

        $results[] = [

            "type" => "Product",

            "name" =>
                $row['name'],

            "price" =>
                $price,

            "price_text" =>
                number_format($price) . " ₭",

            "image" =>
                !empty($row['image'])
                ? "/uploads/" . $row['image']
                : "",

            "url" =>
                "/search/product.php?id=" .
                (int)$row['id']

        ];

    }

    $stmt->close();
}


/*
==================================================
VOUCHER
==================================================
*/

$check = $conn->query("
    SHOW TABLES LIKE 'voucher_cards'
");

if($check && $check->num_rows > 0){

    /*
    ----------------------------------------------
    ตรวจสอบว่ามี price column หรือไม่
    ----------------------------------------------
    */

    $columns = [];

    $columnResult = $conn->query("
        SHOW COLUMNS FROM voucher_cards
    ");

    if($columnResult){

        while($column = $columnResult->fetch_assoc()){

            $columns[] =
                strtolower($column['Field']);

        }

    }


    /*
    ----------------------------------------------
    VOUCHER PRICE COLUMN
    ----------------------------------------------
    */

    $priceColumn = null;

    foreach([
        "price",
        "selling_price",
        "amount",
        "value"
    ] as $possible){

        if(in_array(
            $possible,
            $columns,
            true
        )){

            $priceColumn =
                $possible;

            break;
        }

    }


    /*
    ----------------------------------------------
    BUILD QUERY
    ----------------------------------------------
    */

    if(
        in_array("name",$columns,true) &&
        in_array("status",$columns,true)
    ){

        $priceSQL =
            $priceColumn
            ? ", `$priceColumn` AS voucher_price"
            : ", 0 AS voucher_price";


        $sql = "
            SELECT
                id,
                name,
                image
                $priceSQL
            FROM voucher_cards
            WHERE status='active'
            AND name LIKE ?
            ORDER BY id DESC
            LIMIT 8
        ";


        $stmt =
            $conn->prepare($sql);


        if($stmt){

            $stmt->bind_param(
                "s",
                $keyword
            );

            $stmt->execute();

            $res =
                $stmt->get_result();


            while(
                $row =
                $res->fetch_assoc()
            ){

                $price =
                    (float)(
                        $row['voucher_price']
                        ?? 0
                    );


                $results[] = [

                    "type" =>
                        "Voucher",

                    "name" =>
                        $row['name'],

                    "price" =>
                        $price,

                    "price_text" =>
                        $price > 0
                        ? number_format($price)." ₭"
                        : " Voucher",

                    "image" =>
                        !empty($row['image'])
                        ? "/uploads/" .
                          $row['image']
                        : "",

                    "url" =>
                        "/search/voucher.php?id=" .
                        (int)$row['id']

                ];

            }

            $stmt->close();

        }

    }

}


/*
==================================================
LIMIT
==================================================
*/

$results =
    array_slice(
        $results,
        0,
        15
    );


/*
==================================================
RESPONSE
==================================================
*/

echo json_encode(

    [
        "success" => true,

        "count" =>
            count($results),

        "results" =>
            $results
    ],

    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES

);

exit;
?>