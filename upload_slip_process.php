<?php
error_reporting(0);
ini_set('display_errors','0');

session_start();

require_once "../config.php";
require_once "../database.php";

/*
|--------------------------------------------------------------------------
| CNTECH STORE
| UPLOAD SLIP PROCESS
| OCR BCEL / LDB
|--------------------------------------------------------------------------
*/

const OCR_KEY = 'K86925528788957';
const OCR_URL = 'https://api.ocr.space/parse/image';
const MAX_SIZE = 5242880;

$id   = trim($_POST['order_id'] ?? '');
$type = strtolower(trim($_POST['type'] ?? ''));
$tx   = trim($_POST['transaction_id'] ?? '');
$bank_id = (int)($_POST['bank_id'] ?? 0);

$back = 'upload_slip.php';




/*
|--------------------------------------------------------------------------
| ERROR -> BACK TO UPLOAD SLIP
|--------------------------------------------------------------------------
*/

function fail($m){
    global $id,$type,$tx,$bank_id,$back;

    header('Location: '.$back.'?'.http_build_query([
        'order_id'       => $id,
        'type'           => $type,
        'bank_id'        => $bank_id,
        'transaction_id' => $tx,
        'error'          => $m
    ]));

    exit;
}


/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    fail(
        'Invalid request. Please upload your payment slip from the payment page.'
    );
}


$tables = [
    'game'    => 'game_orders',
    'voucher' => 'voucher_orders',
    'mobile'  => 'mobile_orders'
];


if ($id === '')
    fail('Missing Order ID');


if (!isset($tables[$type]))
    fail('Invalid Payment Type');


if ($tx === '')
    fail('Missing Transaction ID');


if (empty($_FILES['slip']))
    fail('Please upload your payment slip.');


/*
|--------------------------------------------------------------------------
| FILE
|--------------------------------------------------------------------------
*/

$file = $_FILES['slip'];


if (($file['error'] ?? 9) !== UPLOAD_ERR_OK)
    fail('Payment slip upload failed.');


if (
    $file['size'] < 1 ||
    $file['size'] > MAX_SIZE
) {
    fail('Payment slip must not exceed 5MB.');
}


/*
|--------------------------------------------------------------------------
| MIME CHECK
|--------------------------------------------------------------------------
*/

$finfo = finfo_open(FILEINFO_MIME_TYPE);

$mime = finfo_file(
    $finfo,
    $file['tmp_name']
);

finfo_close($finfo);


$extensions = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];


if (!isset($extensions[$mime])) {

    fail(
        'Only JPG, PNG and WEBP payment slips are allowed.'
    );
}


$ext = $extensions[$mime];


/*
|--------------------------------------------------------------------------
| LOAD ORDER
|--------------------------------------------------------------------------
*/

$table = $tables[$type];


$stmt = $conn->prepare(
    "SELECT *
     FROM `$table`
     WHERE order_id=?
     LIMIT 1"
);


if (!$stmt)
    fail('Database error.');


$stmt->bind_param(
    's',
    $id
);

$stmt->execute();


$order = $stmt
    ->get_result()
    ->fetch_assoc();


$stmt->close();


if (!$order)
    fail('Order not found.');


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

$payment_status = strtolower(
    trim(
        $order['payment_status'] ?? 'pending'
    )
);


if (
    in_array(
        $payment_status,
        ['paid','success','completed'],
        true
    )
) {

    fail(
        'Order already paid.'
    );
}


/*
|--------------------------------------------------------------------------
| ORDER STATUS
|--------------------------------------------------------------------------
*/

$order_status = strtolower(
    trim(
        $order['status']
        ?? $order['order_status']
        ?? 'pending'
    )
);


if (
    in_array(
        $order_status,
        ['cancelled','canceled'],
        true
    )
) {

    fail(
        'Order has been cancelled.'
    );
}


/*
|--------------------------------------------------------------------------
| REAL ORDER AMOUNT
|--------------------------------------------------------------------------
*/

if ($type === 'mobile') {

    $order_amount = (float)(
        $order['amount'] ?? 0
    );

} elseif ($type === 'game') {

    $order_amount = (float)(
        $order['price']
        ?? $order['total']
        ?? 0
    );

} else {

    $order_amount = (float)(
        $order['total']
        ?? $order['amount']
        ?? 0
    );
}


if ($order_amount < 1) {

    fail(
        'Invalid order amount.'
    );
}


/*
|--------------------------------------------------------------------------
| BASIC HELPERS
|--------------------------------------------------------------------------
*/

function cleanNumber($value)
{
    $value = strtoupper(
        trim(
            (string)$value
        )
    );

    $value = str_replace(
        [
            'LAK',
            'KIP',
            '₭',
            'ກີບ',
            ' ',
            '_'
        ],
        '',
        $value
    );

    $value = preg_replace(
        '/[^0-9.,]/',
        '',
        $value
    );


    if ($value === '')
        return null;


    /*
    | 30,000
    | 30.000
    */

    if (
        preg_match(
            '/^\d{1,3}([,.]\d{3})+$/',
            $value
        )
    ) {

        return (float)preg_replace(
            '/[,.]/',
            '',
            $value
        );
    }


    /*
    | 30000.00
    */

    return (float)str_replace(
        ',',
        '',
        $value
    );
}


/*
|--------------------------------------------------------------------------
| OCR FUNCTION
|--------------------------------------------------------------------------
*/

function readOCR($file)
{
    $curl = curl_init(
        OCR_URL
    );


    curl_setopt_array(
        $curl,
        [

            CURLOPT_POST => true,

            CURLOPT_POSTFIELDS => [

                'apikey' => OCR_KEY,

                'language' => 'auto',

                'OCREngine' => '2',

                'scale' => 'true',

                'isOverlayRequired' => 'false',

                'file' => new CURLFile(
                    $file['tmp_name'],
                    $file['type'],
                    $file['name']
                )
            ],

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_TIMEOUT => 45,

            CURLOPT_CONNECTTIMEOUT => 15,

            CURLOPT_SSL_VERIFYPEER => true
        ]
    );


    $response = curl_exec(
        $curl
    );


    curl_close(
        $curl
    );


    if (!$response)
        return '';


    $json = json_decode(
        $response,
        true
    );


    if (
        !is_array($json) ||
        !empty(
            $json['IsErroredOnProcessing']
        )
    ) {

        return '';
    }


    $text = '';


    foreach (
        $json['ParsedResults'] ?? []
        as $result
    ) {

        $text .= "\n";

        $text .= (
            $result['ParsedText']
            ?? ''
        );
    }


    return trim(
        $text
    );
}


/*
|--------------------------------------------------------------------------
| OCR
|--------------------------------------------------------------------------
*/

$ocr_text = readOCR(
    $file
);


if ($ocr_text === '') {

    fail(
        'Could not detect payment information on the slip. Please upload a clearer slip.'
    );
}


/*
|--------------------------------------------------------------------------
| NORMALIZE OCR
|--------------------------------------------------------------------------
*/

$ocr_text = trim(
    preg_replace(
        '/[ \t]+/',
        ' ',
        $ocr_text
    )
);


/*
|--------------------------------------------------------------------------
| PART 2
|--------------------------------------------------------------------------
|
| ตรวจ:
| 1. Amount
| 2. Transaction ID
| 3. BCEL / LDB
| 4. Date / Time
|
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| PART 2 - OCR PAYMENT VALIDATION
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| NORMALIZED TEXT
|--------------------------------------------------------------------------
*/

$ocr_lower = strtolower($ocr_text);

$ocr_clean = preg_replace(
    '/[^a-z0-9]/i',
    '',
    $ocr_lower
);


/*
|--------------------------------------------------------------------------
| 1. PAYMENT AMOUNT
|--------------------------------------------------------------------------
*/

$candidates = [];


/*
| 30,000 LAK
| 30.000 LAK
| 30000 LAK
*/

$patterns = [

    '/(?:LAK|KIP|ກີບ|₭)\s*[:\-]?\s*([0-9][0-9,. ]*)/iu',

    '/([0-9][0-9,. ]*)\s*(?:LAK|KIP|ກີບ|₭)/iu',

    '/(?:TOTAL|AMOUNT|PAYMENT|ຍອດ|ຈຳນວນ|ຈໍານວນ|ຈຳນວນເງິນ|ຍອດເງິນ)\s*[:\-]?\s*([0-9][0-9,. ]*)/iu'
];


foreach ($patterns as $pattern) {

    preg_match_all(
        $pattern,
        $ocr_text,
        $matches
    );


    foreach (
        $matches[1] ?? []
        as $value
    ) {

        $number = cleanNumber(
            $value
        );


        if (
            $number !== null &&
            $number >= 1
        ) {

            $candidates[] = $number;
        }
    }
}


/*
|--------------------------------------------------------------------------
| CHECK AMOUNT
|--------------------------------------------------------------------------
*/

$slip_amount = null;


foreach (
    array_unique($candidates)
    as $number
) {

    /*
    | Exact amount
    */

    if (
        abs(
            $number - $order_amount
        ) < 0.01
    ) {

        $slip_amount = $number;

        break;
    }
}


if ($slip_amount === null) {

    fail(
        'The amount on the payment slip does not match the order amount. Please upload the correct payment slip.'
    );
}


/*
|--------------------------------------------------------------------------
| 2. TRANSACTION ID
|--------------------------------------------------------------------------
*/

$tx_clean = preg_replace(
    '/[^a-z0-9]/i',
    '',
    strtolower($tx)
);


$transaction_ok = false;


/*
| Full Transaction ID
*/

if (
    $tx_clean !== '' &&
    strpos(
        $ocr_clean,
        $tx_clean
    ) !== false
) {

    $transaction_ok = true;
}


/*
|--------------------------------------------------------------------------
| OCR อาจอ่าน "_" "-" หรือช่องว่างผิด
|--------------------------------------------------------------------------
*/

if (!$transaction_ok) {

    $parts = preg_split(
        '/[_\-\s]+/',
        $tx
    );


    foreach (
        $parts as $part
    ) {

        $part = preg_replace(
            '/[^a-z0-9]/i',
            '',
            strtolower($part)
        );


        if (
            strlen($part) >= 6 &&
            strpos(
                $ocr_clean,
                $part
            ) !== false
        ) {

            $transaction_ok = true;

            break;
        }
    }
}


if (!$transaction_ok) {

    fail(
        'Could not verify the transaction ID on the payment slip. Please upload the correct payment slip.'
    );
}


/*
|--------------------------------------------------------------------------
| 3. BANK
|--------------------------------------------------------------------------
|
| ไม่ใช้ bank_id
| ไม่ขึ้น Invalid Bank
|
| ตรวจจาก OCR โดยตรง
|--------------------------------------------------------------------------
*/

$bank = '';


if (
    strpos(
        $ocr_lower,
        'bcel'
    ) !== false ||

    strpos(
        $ocr_lower,
        'bc el'
    ) !== false ||

    strpos(
        $ocr_lower,
        'ບີເຊ'
    ) !== false ||

    strpos(
        $ocr_lower,
        'ທະນາຄານການຄ້າ'
    ) !== false
) {

    $bank = 'BCEL';
}


if (
    $bank === '' &&

    (
        strpos(
            $ocr_lower,
            'ldb'
        ) !== false ||

        strpos(
            $ocr_lower,
            'lao development'
        ) !== false ||

        strpos(
            $ocr_lower,
            'ລັດທະນາ'
        ) !== false ||

        strpos(
            $ocr_lower,
            'ທະນາຄານລັດ'
        ) !== false
    )
) {

    $bank = 'LDB';
}


if ($bank === '') {

    fail(
        'Could not verify the bank on the payment slip. Please upload a valid BCEL or LDB payment slip.'
    );
}


/*
|--------------------------------------------------------------------------
| 4. DATE
|--------------------------------------------------------------------------
*/

$date_ok = false;

$slip_date = '';

$now = time();


/*
| YYYY-MM-DD
| YYYY/MM/DD
*/

preg_match_all(
    '/\b(20\d{2})[-\/](\d{1,2})[-\/](\d{1,2})\b/',
    $ocr_text,
    $dates1
);


foreach (
    $dates1[0] ?? []
    as $date
) {

    if (
        preg_match(
            '/^(20\d{2})[-\/](\d{1,2})[-\/](\d{1,2})$/',
            $date,
            $m
        )
    ) {

        $ts = mktime(
            0,
            0,
            0,
            (int)$m[2],
            (int)$m[3],
            (int)$m[1]
        );


        if (
            $ts !== false &&
            $ts <= strtotime(
                '+1 day',
                $now
            ) &&
            $ts >= strtotime(
                '-7 days',
                $now
            )
        ) {

            $date_ok = true;

            $slip_date = $date;

            break;
        }
    }
}


/*
|--------------------------------------------------------------------------
| DD-MM-YYYY
| DD/MM/YYYY
|--------------------------------------------------------------------------
*/

if (!$date_ok) {

    preg_match_all(
        '/\b(\d{1,2})[-\/](\d{1,2})[-\/](20\d{2})\b/',
        $ocr_text,
        $dates2
    );


    foreach (
        $dates2[0] ?? []
        as $date
    ) {

        if (
            preg_match(
                '/^(\d{1,2})[-\/](\d{1,2})[-\/](20\d{2})$/',
                $date,
                $m
            )
        ) {

            $ts = mktime(
                0,
                0,
                0,
                (int)$m[2],
                (int)$m[1],
                (int)$m[3]
            );


            if (
                $ts !== false &&
                $ts <= strtotime(
                    '+1 day',
                    $now
                ) &&
                $ts >= strtotime(
                    '-7 days',
                    $now
                )
            ) {

                $date_ok = true;

                $slip_date = $date;

                break;
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| DATE FAILED
|--------------------------------------------------------------------------
*/

if (!$date_ok) {

    fail(
        'Could not verify the payment date on the slip. Please upload a valid recent payment slip.'
    );
}


/*
|--------------------------------------------------------------------------
| 5. TIME
|--------------------------------------------------------------------------
*/

$slip_time = '';


if (
    preg_match(
        '/\b([01]?[0-9]|2[0-3])[:.]([0-5][0-9])(?:[:.]([0-5][0-9]))?\b/',
        $ocr_text,
        $tm
    )
) {

    $slip_time =
        str_pad(
            $tm[1],
            2,
            '0',
            STR_PAD_LEFT
        )
        . ':'
        .
        str_pad(
            $tm[2],
            2,
            '0',
            STR_PAD_LEFT
        );


    if (isset($tm[3])) {

        $slip_time .=
            ':' .
            str_pad(
                $tm[3],
                2,
                '0',
                STR_PAD_LEFT
            );
    }
}


/*
|--------------------------------------------------------------------------
| TIME ไม่บังคับ
|--------------------------------------------------------------------------
|
| บางสลิป OCR อ่านเวลาไม่ได้
| แต่ต้องมีวันที่
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| VALIDATION RESULT
|--------------------------------------------------------------------------
*/

$checks = [

    'amount'      => true,

    'transaction' => true,

    'bank'        => true,

    'date'        => true,

    'time'        => ($slip_time !== '')
];


/*
|--------------------------------------------------------------------------
| PAYMENT DATA
|--------------------------------------------------------------------------
*/

$payment_data = [

    'source' => 'manual_payment',

    'type' => $type,

    'bank' => $bank,

    'transaction_id' => $tx,

    'order_amount' => $order_amount,

    'slip_amount' => $slip_amount,

    'currency' => 'LAK',

    'slip_date' => $slip_date,

    'slip_time' => $slip_time,

    'uploaded_at' => date(
        'Y-m-d H:i:s'
    ),

    'checks' => $checks,

    'ocr' => substr(
        $ocr_text,
        0,
        10000
    )
];


/*
|--------------------------------------------------------------------------
| JSON
|--------------------------------------------------------------------------
*/

$payment_json = json_encode(

    $payment_data,

    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
);


if (!$payment_json) {

    fail(
        'Unable to process payment information.'
    );
}


/*
|--------------------------------------------------------------------------
| PART 3
|--------------------------------------------------------------------------
|
| SAVE IMAGE
| SAVE payment_transactions
| UPDATE ORDER
| REDIRECT payment_review.php
|
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PART 3 - SAVE + PAYMENT REVIEW
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| SAVE SLIP
|--------------------------------------------------------------------------
*/

$dir = __DIR__ . '/uploads/slips/';


if (
    !is_dir($dir) &&
    !mkdir($dir, 0755, true)
) {

    fail(
        'Cannot create upload directory.'
    );
}


/*
|--------------------------------------------------------------------------
| SAFE FILE NAME
|--------------------------------------------------------------------------
*/

$safe_id = preg_replace(
    '/[^A-Za-z0-9_-]/',
    '_',
    $id
);


$file_name =
    $safe_id .
    '_' .
    time() .
    '_' .
    bin2hex(
        random_bytes(4)
    ) .
    '.' .
    $ext;


$file_path = $dir . $file_name;


if (
    !move_uploaded_file(
        $file['tmp_name'],
        $file_path
    )
) {

    fail(
        'Cannot save payment slip.'
    );
}


/*
|--------------------------------------------------------------------------
| PUBLIC SLIP URL
|--------------------------------------------------------------------------
*/

$slip_url =
    '/api/uploads/slips/' .
    $file_name;


/*
|--------------------------------------------------------------------------
| ADD SLIP URL TO PAYMENT DATA
|--------------------------------------------------------------------------
*/

$payment_data['slip'] = $slip_url;


$payment_json = json_encode(

    $payment_data,

    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
);


if (!$payment_json) {

    @unlink($file_path);

    fail(
        'Unable to create payment data.'
    );
}


/*
|--------------------------------------------------------------------------
| FIND EXISTING TRANSACTION
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id,status
     FROM payment_transactions
     WHERE order_id=?
     AND transaction_id=?
     ORDER BY id DESC
     LIMIT 1"
);


if (!$stmt) {

    @unlink($file_path);

    fail(
        'Transaction database error.'
    );
}


$stmt->bind_param(
    'ss',
    $id,
    $tx
);


$stmt->execute();


$old = $stmt
    ->get_result()
    ->fetch_assoc();


$stmt->close();


/*
|--------------------------------------------------------------------------
| DO NOT MODIFY PAID TRANSACTION
|--------------------------------------------------------------------------
*/

if (
    $old &&
    in_array(
        strtolower(
            $old['status'] ?? ''
        ),
        ['paid'],
        true
    )
) {

    @unlink($file_path);

    fail(
        'This transaction has already been paid.'
    );
}


/*
|--------------------------------------------------------------------------
| UPDATE EXISTING TRANSACTION
|--------------------------------------------------------------------------
*/

if ($old) {

    $transaction_id_db =
        (int)$old['id'];


    $stmt = $conn->prepare(
        "UPDATE payment_transactions
         SET
            amount=?,
            qr_text=?,
            status='pending',
            email_sent=0
         WHERE id=?"
    );


    if (!$stmt) {

        @unlink($file_path);

        fail(
            'Transaction update error.'
        );
    }


    $stmt->bind_param(
        'dsi',
        $slip_amount,
        $payment_json,
        $transaction_id_db
    );


/*
|--------------------------------------------------------------------------
| CREATE NEW TRANSACTION
|--------------------------------------------------------------------------
*/

} else {

    $expire_at = date(
        'Y-m-d H:i:s',
        strtotime('+15 minutes')
    );


    $stmt = $conn->prepare(
        "INSERT INTO payment_transactions
        (
            order_id,
            transaction_id,
            amount,
            qr_text,
            status,
            expire_at,
            created_at,
            email_sent
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            'pending',
            ?,
            NOW(),
            0
        )"
    );


    if (!$stmt) {

        @unlink($file_path);

        fail(
            'Transaction insert error.'
        );
    }


    $stmt->bind_param(
        'ssdss',
        $id,
        $tx,
        $slip_amount,
        $payment_json,
        $expire_at
    );
}


/*
|--------------------------------------------------------------------------
| EXECUTE TRANSACTION
|--------------------------------------------------------------------------
*/

if (!$stmt->execute()) {

    $error = $stmt->error;

    $stmt->close();

    @unlink($file_path);

    fail(
        'Transaction save failed: ' .
        $error
    );
}


$stmt->close();


/*
|--------------------------------------------------------------------------
| UPDATE ORDER
|--------------------------------------------------------------------------
|
| ใช้เฉพาะ column ที่มีจริงจาก DB ของคุณ
|
|--------------------------------------------------------------------------
*/

if ($type === 'voucher') {

    /*
    | voucher_orders
    */

    $stmt = $conn->prepare(
        "UPDATE voucher_orders
         SET
            payment_status='pending',
            status='pending',
            transaction_id=?,
            gateway='manual',
            payment_method=?,
            api_response=?,
            fulfillment_status='pending'
         WHERE order_id=?"
    );


    if ($stmt) {

        $method = $bank;

        $stmt->bind_param(
            'ssss',
            $tx,
            $method,
            $payment_json,
            $id
        );

        $stmt->execute();

        $stmt->close();
    }


} elseif ($type === 'game') {

    /*
    | game_orders
    */

    $stmt = $conn->prepare(
        "UPDATE game_orders
         SET
            payment_status='pending',
            payment_method=?,
            gateway='manual',
            coda_response=?
         WHERE order_id=?"
    );


    if ($stmt) {

        $method = $bank;

        $stmt->bind_param(
            'sss',
            $method,
            $payment_json,
            $id
        );

        $stmt->execute();

        $stmt->close();
    }


} elseif ($type === 'mobile') {

    /*
    | mobile_orders
    */

    $stmt = $conn->prepare(
        "UPDATE mobile_orders
         SET
            payment_status='pending',
            payment_method=?,
            gateway='manual',
            api_response=?,
            transaction_id=?
         WHERE order_id=?"
    );


    if ($stmt) {

        $method = $bank;

        $stmt->bind_param(
            'ssss',
            $method,
            $payment_json,
            $tx,
            $id
        );

        $stmt->execute();

        $stmt->close();
    }
}


/*
|--------------------------------------------------------------------------
| CLEAR SESSION
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION['manual_payment']
);


/*
|--------------------------------------------------------------------------
| SEND TO PAYMENT REVIEW
|--------------------------------------------------------------------------
*/

header(
    'Location: api/payment_review.php?' .
    http_build_query([
        'order_id'       => $id,
        'type'           => $type,
        'transaction_id' => $tx
    ])
);


exit;

?>