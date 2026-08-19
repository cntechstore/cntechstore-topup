<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once "../config.php";
require_once "../database.php";


/*
|--------------------------------------------------------------------------
| RESPONSE HELPER
|--------------------------------------------------------------------------
*/

function response_json($status, $message, $data = [])
{
    echo json_encode(
        array_merge(
            [
                'status'  => $status,
                'message' => $message
            ],
            $data
        ),
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

$order_id = trim($_GET['order_id'] ?? '');
$type     = strtolower(trim($_GET['type'] ?? ''));


if ($order_id === '') {

    response_json(
        'error',
        'Missing Order ID'
    );

}


if (!in_array($type, [
    'game',
    'mobile',
    'voucher'
], true)) {

    response_json(
        'error',
        'Invalid Payment Type'
    );

}


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/

$table = '';

switch ($type) {

    case 'game':

        $table = 'game_orders';

        break;


    case 'mobile':

        $table = 'mobile_orders';

        break;


    case 'voucher':

        $table = 'voucher_orders';

        break;

}


/*
|--------------------------------------------------------------------------
| FIND ORDER
|--------------------------------------------------------------------------
*/

$order = null;


/*
|--------------------------------------------------------------------------
| GAME / VOUCHER
|--------------------------------------------------------------------------
*/

if (
    $type === 'game' ||
    $type === 'voucher'
) {

    $sql = "
        SELECT *
        FROM `$table`
        WHERE order_id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        response_json(
            'error',
            'Database prepare error',
            [
                'error' => $conn->error
            ]
        );

    }


    $stmt->bind_param(
        "s",
        $order_id
    );


    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        response_json(
            'error',
            'Database execute error',
            [
                'error' => $error
            ]
        );

    }


    $result = $stmt->get_result();

    $order = $result->fetch_assoc();

    $stmt->close();

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

if ($type === 'mobile') {

    $sql = "
        SELECT *
        FROM mobile_orders
        WHERE order_id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        response_json(
            'error',
            'Database prepare error',
            [
                'error' => $conn->error
            ]
        );

    }


    $stmt->bind_param(
        "s",
        $order_id
    );


    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        response_json(
            'error',
            'Database execute error',
            [
                'error' => $error
            ]
        );

    }


    $result = $stmt->get_result();

    $order = $result->fetch_assoc();

    $stmt->close();

}


/*
|--------------------------------------------------------------------------
| ORDER NOT FOUND
|--------------------------------------------------------------------------
*/

if (!$order) {

    response_json(
        'error',
        'Order not found',
        [
            'order_id' => $order_id,
            'type'     => $type,
            'table'    => $table
        ]
    );

}


/*
|--------------------------------------------------------------------------
| ORDER ID
|--------------------------------------------------------------------------
*/

$display_order_id =
    trim(
        $order['order_id']
        ?? $order_id
    );


/*
|--------------------------------------------------------------------------
| AMOUNT
|--------------------------------------------------------------------------
*/

if ($type === 'mobile') {

    $amount =
        (float)(
            $order['amount']
            ?? 0
        );

} else {

    $amount =
        (float)(
            $order['total']
            ?? $order['price']
            ?? 0
        );

}


/*
|--------------------------------------------------------------------------
| CURRENCY
|--------------------------------------------------------------------------
*/

$currency = 'LAK';


/*
|--------------------------------------------------------------------------
| PAYMENT STATUS
|--------------------------------------------------------------------------
*/

$payment_status =
    strtolower(
        trim(
            $order['payment_status']
            ?? 'pending'
        )
    );


/*
|--------------------------------------------------------------------------
| ALREADY PAID
|--------------------------------------------------------------------------
*/

if ($payment_status === 'paid') {

    response_json(
        'error',
        'Order already paid',
        [
            'order_id'       => $display_order_id,
            'type'           => $type,
            'amount'         => $amount,
            'currency'       => $currency,
            'payment_status' => $payment_status
        ]
    );

}


/*
|--------------------------------------------------------------------------
| CANCELLED
|--------------------------------------------------------------------------
*/

$order_status =
    strtolower(
        trim(
            $order['status']
            ?? $order['order_status']
            ?? 'pending'
        )
    );


if ($order_status === 'cancelled') {

    response_json(
        'error',
        'Order has been cancelled',
        [
            'order_id' => $display_order_id,
            'type'     => $type
        ]
    );

}


/*
|--------------------------------------------------------------------------
| BANK ACCOUNTS
|--------------------------------------------------------------------------
*/

$banks = [];

$sql = "
    SELECT *
    FROM admin_from_bank_account
    ORDER BY id ASC
";

$result = $conn->query($sql);


if ($result) {

    while ($row = $result->fetch_assoc()) {

        /*
        |--------------------------------------------------------------------------
        | NAME
        |--------------------------------------------------------------------------
        */

        $bank_name = '';

        if (
            isset($row['bank_name']) &&
            trim($row['bank_name']) !== ''
        ) {

            $bank_name =
                trim($row['bank_name']);

        } elseif (
            isset($row['name']) &&
            trim($row['name']) !== ''
        ) {

            $bank_name =
                trim($row['name']);

        } else {

            $bank_name = 'Bank';

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $bank_status =
            strtolower(
                trim(
                    $row['status']
                    ?? 'maintenance'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | TYPE
        |--------------------------------------------------------------------------
        */

        $bank_type =
            strtolower(
                trim(
                    $row['type']
                    ?? 'bank'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        $image = "/assets/no-image.png";


        if (
            isset($row['image']) &&
            trim($row['image']) !== ''
        ) {

            $image =
                "/admin/uploads/" .
                basename(
                    $row['image']
                );

        }


        /*
        |--------------------------------------------------------------------------
        | BANK DATA
        |--------------------------------------------------------------------------
        */

        $banks[] = [

            'id' =>
                (int)(
                    $row['id']
                    ?? 0
                ),

            'name' =>
                $bank_name,

            'status' =>
                $bank_status,

            'type' =>
                $bank_type,

            'image' =>
                $image

        ];

    }

}


/*
|--------------------------------------------------------------------------
| TRANSACTION ID
|--------------------------------------------------------------------------
*/

$transaction_id =
    'MAN_' .
    date('YmdHis') .
    '_' .
    strtoupper(
        bin2hex(
            random_bytes(4)
        )
    );


/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/

$_SESSION['manual_payment'] = [

    'transaction_id' =>
        $transaction_id,

    'order_id' =>
        $display_order_id,

    'type' =>
        $type,

    'table' =>
        $table,

    'amount' =>
        $amount,

    'currency' =>
        $currency,

    'created_at' =>
        time()

];


/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/

response_json(
    'success',
    'Order found',
    [

        'order_id' =>
            $display_order_id,

        'type' =>
            $type,

        'table' =>
            $table,

        'amount' =>
            $amount,

        'currency' =>
            $currency,

        'payment_status' =>
            $payment_status,

        'status' =>
            $order_status,

        'transaction_id' =>
            $transaction_id,

        'banks' =>
            $banks

    ]
);

?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>
Manual Payment | CNTECH STORE
</title>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


<style>

*{
    box-sizing:border-box;
}

body{

    margin:0;

    min-height:100vh;

    font-family:
    Arial,
    sans-serif;

    background:
    linear-gradient(
        135deg,
        #050505,
        #260000
    );

    color:white;

    padding-bottom:40px;
}


.header{

    height:65px;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 18px;

    background:#080808;

    border-bottom:
    2px solid #e00000;

    position:sticky;

    top:0;

    z-index:100;

}


.logo{

    font-size:21px;

    font-weight:900;

}


.logo span{

    color:#ff2020;

}


.header-icon{

    width:42px;

    height:42px;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:50%;

    background:#e00000;

}


.container{

    width:100%;

    max-width:600px;

    margin:auto;

    padding:15px;

}


.card{

    background:
    rgba(255,255,255,.08);

    border:
    1px solid
    rgba(255,255,255,.14);

    border-radius:22px;

    padding:20px;

    margin-bottom:15px;

    backdrop-filter:blur(15px);

    box-shadow:
    0 15px 40px
    rgba(0,0,0,.4);

}


.title{

    font-size:20px;

    font-weight:900;

    margin-bottom:15px;

}


.title i{

    color:#ff2020;

}


.order-id{

    background:#050505;

    padding:13px;

    border-radius:12px;

    word-break:break-all;

    font-size:13px;

}


.amount{

    font-size:32px;

    font-weight:900;

    color:#ff3030;

    margin-top:12px;

}


.type{

    display:inline-block;

    margin-top:10px;

    padding:7px 13px;

    border-radius:20px;

    background:#330000;

    color:#ff5555;

    font-size:13px;

}


.section-title{

    font-size:18px;

    font-weight:900;

    margin:20px 0 12px;

}


.bank-grid{

    display:grid;

    grid-template-columns:
    repeat(2,1fr);

    gap:12px;

}


.bank{

    border:1px solid
    rgba(255,255,255,.12);

    background:
    rgba(255,255,255,.07);

    color:white;

    border-radius:18px;

    padding:15px 10px;

    text-align:center;

    cursor:pointer;

    transition:.2s;

}


.bank:hover{

    border-color:#ff2020;

    transform:translateY(-2px);

}


.bank.active{

    border:
    2px solid #ff2020;

    background:
    rgba(255,0,0,.18);

}


.bank img{

    width:65px;

    height:65px;

    object-fit:contain;

    background:white;

    border-radius:15px;

    padding:7px;

}


.bank-name{

    margin-top:8px;

    font-weight:800;

}


.status{

    margin-top:6px;

    font-size:11px;

}


.online{

    color:#22c55e;

}


.offline{

    color:#ef4444;

}


.qr-box{

    display:none;

    text-align:center;

}


.qr-box.show{

    display:block;

}


.qr-box img{

    width:240px;

    max-width:100%;

    background:white;

    padding:10px;

    border-radius:15px;

}


.pay-info{

    margin-top:15px;

    background:#050505;

    padding:15px;

    border-radius:15px;

    text-align:left;

}


.info-row{

    display:flex;

    justify-content:space-between;

    padding:7px 0;

    border-bottom:
    1px solid #222;

}


.info-row:last-child{

    border-bottom:0;

}


.upload{

    margin-top:15px;

}


.upload input{

    width:100%;

    padding:13px;

    border-radius:12px;

    background:#111;

    color:white;

    border:1px solid #333;

}


.submit{

    width:100%;

    border:0;

    padding:15px;

    margin-top:15px;

    border-radius:15px;

    background:
    linear-gradient(
        135deg,
        #ff2020,
        #a00000
    );

    color:white;

    font-size:16px;

    font-weight:900;

}


.back{

    display:block;

    text-align:center;

    padding:14px;

    border-radius:15px;

    background:#171717;

    color:white;

    text-decoration:none;

    margin-top:12px;

}


@media(max-width:420px){

    .bank-grid{

        grid-template-columns:1fr 1fr;

    }

    .amount{

        font-size:28px;

    }

}

</style>

</head>


<body>


<header class="header">

    <div class="logo">
        CNTECH <span>STORE</span>
    </div>

    <div class="header-icon">
        <i class="fa-solid fa-credit-card"></i>
    </div>

</header>


<main class="container">


<!-- ORDER -->

<section class="card">

    <div class="title">

        <i class="fa-solid fa-file-invoice"></i>

        รายการชำระเงิน

    </div>


    <div class="order-id">

        Order ID:

        <?=htmlspecialchars($display_order_id)?>

    </div>


    <div class="amount">

        <?=number_format($amount,2)?>

        <?=htmlspecialchars($currency)?>

    </div>


    <div class="type">

        <?=strtoupper(htmlspecialchars($type))?>

    </div>

</section>


<!-- BANK -->

<section class="card">

    <div class="title">

        <i class="fa-solid fa-building-columns"></i>

        เลือกธนาคาร

    </div>


    <div class="bank-grid">


<?php foreach($banks as $bank): ?>


    <div
        class="bank
        <?=$bank['status'] !== 'online'
            ? 'disabled'
            : ''?>"

        onclick="

        selectBank(

        <?=$bank['id']?>,

        '<?=htmlspecialchars(
            $bank['name'],
            ENT_QUOTES
        )?>',

        '<?=$bank['status']?>'

        )

        "
    >


        <img
        src="<?=htmlspecialchars(
            $bank['image']
        )?>">


        <div class="bank-name">

            <?=htmlspecialchars(
                $bank['name']
            )?>

        </div>


        <div
        class="status
        <?=$bank['status']==='online'
            ? 'online'
            : 'offline'?>">

            <i class="fa-solid
            fa-circle"></i>

            <?=strtoupper(
                htmlspecialchars(
                    $bank['status']
                )
            )?>

        </div>


    </div>


<?php endforeach; ?>


    </div>

</section>


<!-- QR -->

<section
class="card qr-box"
id="qrBox">


    <div class="title">

        <i class="fa-solid fa-qrcode"></i>

        ชำระเงิน

    </div>


    <img
    id="qrImage"
    src=""
    alt="Payment QR">


    <div class="pay-info">

        <div class="info-row">

            <span>ธนาคาร</span>

            <strong id="selectedBank">
                -
            </strong>

        </div>


        <div class="info-row">

            <span>ยอดชำระ</span>

            <strong>
                <?=number_format(
                    $amount,
                    2
                )?>
                <?=htmlspecialchars(
                    $currency
                )?>
            </strong>

        </div>


        <div class="info-row">

            <span>Transaction</span>

            <strong>

                <?=htmlspecialchars(
                    $transaction_id
                )?>

            </strong>

        </div>

    </div>


    <div class="upload">

        <label>
            อัปโหลดสลิปหลังจากโอนเงิน
        </label>

        <input
        type="file"
        id="slip"
        accept="image/*">


    </div>


    <button
    class="submit"
    onclick="submitSlip()">

        <i class="fa-solid fa-paper-plane"></i>

        ยืนยันการโอนเงิน

    </button>


</section>


<a
href="javascript:history.back()"
class="back">

    <i class="fa-solid fa-arrow-left"></i>

    กลับ

</a>


</main>


<script>

const ORDER_ID =
<?=json_encode($display_order_id)?>;

const ORDER_TYPE =
<?=json_encode($type)?>;

const AMOUNT =
<?=json_encode($amount)?>;

const CURRENCY =
<?=json_encode($currency)?>;

let selectedBank = '';

let selectedBankId = 0;


function selectBank(
    id,
    name,
    status
){

    if(status !== 'online'){

        alert(
            'ธนาคารนี้ยังไม่พร้อมใช้งาน'
        );

        return;

    }


    selectedBankId = id;

    selectedBank = name;


    document
    .querySelectorAll('.bank')
    .forEach(
        el =>
        el.classList.remove('active')
    );


    event.currentTarget
    .classList.add('active');


    document
    .getElementById(
        'selectedBank'
    )
    .innerText =
    name;


    /*
    | QR สำหรับทดสอบ
    | เปลี่ยนเป็น QR จริงของคุณ
    */

    const qrData =
        'CNTECHSTORE|' +
        ORDER_ID +
        '|' +
        AMOUNT +
        '|' +
        CURRENCY;


    document
    .getElementById('qrImage')
    .src =
    'https://api.qrserver.com/v1/create-qr-code/' +
    '?size=300x300&data=' +
    encodeURIComponent(qrData);


    document
    .getElementById('qrBox')
    .classList.add('show');


    document
    .getElementById('qrBox')
    .scrollIntoView({
        behavior:'smooth'
    });

}


function submitSlip(){

    const file =
        document.getElementById(
            'slip'
        ).files[0];


    if(!selectedBank){

        alert(
            'กรุณาเลือกธนาคาร'
        );

        return;

    }


    if(!file){

        alert(
            'กรุณาอัปโหลดสลิป'
        );

        return;

    }


    /*
    | ตอนนี้ยังไม่ยิง Top-up API
    |
    | ส่งให้ Admin ตรวจสอบก่อน
    */

    alert(
        'รับสลิปแล้ว กรุณารอ Admin ตรวจสอบการชำระเงิน'
    );

}

</script>


</body>
</html>lert(
            "Stripe Payment Error"
        );


    }



}






/*
========================
QR MODAL
========================
*/


function openQR(data){



    document.getElementById(
        "qrModal"
    ).style.display="flex";



    document.getElementById(
        "paymentQR"
    ).src =

    "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data="

    +

    encodeURIComponent(
        data.qr
    );




    document.getElementById(
        "paymentAmount"
    ).innerHTML =


    "₭ "

    +

    Number(
        data.amount
    ).toLocaleString();





    document.getElementById(
        "transactionId"
    ).innerHTML =

    data.transaction_id;




    startCountdown();



}





/*
========================
COUNTDOWN
========================
*/


let timer;


function startCountdown(){



let sec = 120;



clearInterval(timer);



timer=setInterval(()=>{


    sec--;


    let el =
    document.getElementById(
        "countdown"
    );


    if(el){


        el.innerHTML =
        sec+"s";


    }



    if(sec<=0){


        clearInterval(timer);


        alert(
        "QR หมดอายุ"
        );


        location.reload();


    }



},1000);



}






/*
========================
CHECK PAYMENT
========================
*/


function checkPayment(){



fetch(
"../api/check_payment.php?order_id="
+ORDER_ID
)



.then(r=>r.json())



.then(data=>{


    if(data.status==="paid"){


        window.location.href =

        "../payment/success.php?order_id="

        +ORDER_ID;



    }



});



}



setInterval(
checkPayment,
5000
);





/*
========================
BACK
========================
*/


function goBack(){



if(document.referrer){


history.back();



}else{


window.location.href="/";


}



}



    </script>
    
    </body>
    
</html>