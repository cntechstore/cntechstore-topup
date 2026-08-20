<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "database.php";

/*
=========================================
CHECK LOGIN
=========================================
*/

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/*
=========================================
CN COINS PACKAGE
1 Coin = 1,000 LAK
=========================================
*/

$packages = [

    10   => 10000,
    20   => 20000,
    50   => 50000,
    100  => 100000,
    200  => 200000,
    250  => 250000,
    500  => 500000,
    1000 => 1000000

];

/*
=========================================
GET PACKAGE
=========================================
*/

$coins = isset($_GET['coins'])
    ? (int)$_GET['coins']
    : 0;

if (!isset($packages[$coins])) {

    die("Invalid CN Coins Package");
}

$amount = $packages[$coins];

/*
=========================================
GET USER
=========================================
*/

$stmt = $conn->prepare("
    SELECT
        id,
        username,
        email
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$user = $stmt
    ->get_result()
    ->fetch_assoc();

$stmt->close();

if (!$user) {

    die("User not found");
}

/*
=========================================
CREATE ORDER ID
=========================================
*/

$order_id =
    "COIN_"
    . date("YmdHis")
    . rand(100000,999999);

/*
=========================================
CHECK TABLE
=========================================
*/

$table_exists = false;

$check = $conn->query("
    SHOW TABLES LIKE 'coin_orders'
");

if (
    $check &&
    $check->num_rows > 0
) {

    $table_exists = true;
}

/*
=========================================
CREATE ORDER
=========================================
*/

if ($table_exists) {

    $stmt = $conn->prepare("
        INSERT INTO coin_orders
        (
            order_id,
            user_id,
            coins,
            amount,
            payment_method,
            payment_status,
            status,
            created_at
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            'bank',
            'pending',
            'pending',
            NOW()
        )
    ");

    if ($stmt) {

        $stmt->bind_param(
            "siid",
            $order_id,
            $user_id,
            $coins,
            $amount
        );

        $stmt->execute();

        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="th">
<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>
Buy CN Coins
</title>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    min-height:100vh;

    font-family:
    Arial,
    sans-serif;

    background:
    linear-gradient(
        135deg,
        #050505,
        #250000
    );

    color:white;

    padding:20px;
}

.container{

    max-width:600px;

    margin:auto;
}

.card{

    background:#111;

    border:
    1px solid
    rgba(255,0,0,.5);

    border-radius:24px;

    padding:25px;

    box-shadow:
    0 0 40px
    rgba(255,0,0,.25);
}

.logo{

    text-align:center;

    color:#ff3333;

    font-size:26px;

    font-weight:bold;

    margin-bottom:25px;
}

.order-box{

    background:#181818;

    border-radius:18px;

    padding:18px;

    margin-bottom:20px;
}

.label{

    color:#888;

    font-size:13px;

    margin-bottom:8px;
}

.order-id{

    color:#fff;

    font-size:14px;

    word-break:break-all;
}

.package-box{

    text-align:center;

    background:
    linear-gradient(
        135deg,
        #1a1a1a,
        #2d1f00
    );

    border:1px solid
    rgba(255,215,0,.25);

    border-radius:20px;

    padding:25px;

    margin-bottom:20px;
}

.coin-icon{

    font-size:55px;

    color:#ffd700;

    margin-bottom:10px;
}

.coin-amount{

    font-size:42px;

    font-weight:bold;

    color:#ffd700;
}

.coin-label{

    color:#bbb;

    margin-top:5px;
}

.price{

    margin-top:15px;

    font-size:30px;

    font-weight:bold;

    color:white;
}

.info-box{

    background:#181818;

    border-radius:18px;

    padding:15px;

    margin-bottom:20px;
}

.info-row{

    display:flex;

    align-items:center;

    gap:10px;

    padding:10px 0;

    border-bottom:1px solid #292929;
}

.info-row:last-child{

    border-bottom:none;
}

.info-icon{

    width:24px;

    color:#ff3333;
}

.info-value{

    flex:1;

    word-break:break-word;
}

.pay-btn{

    width:100%;

    border:none;

    cursor:pointer;

    padding:16px;

    border-radius:15px;

    background:
    linear-gradient(
        135deg,
        #ff0000,
        #8b0000
    );

    color:white;

    font-size:18px;

    font-weight:bold;

    transition:.25s;
}

.pay-btn:hover{

    opacity:.92;
}

.pay-btn:disabled{

    opacity:.55;

    cursor:not-allowed;
}

.back-btn{

    display:block;

    text-align:center;

    margin-top:20px;

    color:#999;

    text-decoration:none;
}

.back-btn:hover{

    color:white;
}

#message{

    display:none;

    margin-top:15px;

    padding:14px;

    border-radius:12px;

    font-size:14px;
}

.message-success{

    display:block !important;

    background:
    rgba(34,197,94,.15);

    border:1px solid #22c55e;

    color:#86efac;
}

.message-error{

    display:block !important;

    background:
    rgba(220,38,38,.15);

    border:1px solid #dc2626;

    color:#fca5a5;
}

@media(max-width:500px){

    body{

        padding:10px;
    }

    .card{

        padding:18px;
    }

    .coin-amount{

        font-size:34px;
    }

    .price{

        font-size:24px;
    }
    }
    
    </style>
    
    </head>

<body>

<div class="container">

    <div class="card">

        <!-- =====================================
             LOGO
        ====================================== -->

        <div class="logo">

            <i class="fa-solid fa-coins"></i>

            CN TECH STORE

        </div>


        <!-- =====================================
             ORDER ID
        ====================================== -->

        <div class="order-box">

            <div class="label">

                Order ID

            </div>

            <div class="order-id">

                <?=htmlspecialchars(
                    $order_id,
                    ENT_QUOTES,
                    'UTF-8'
                )?>

            </div>

        </div>


        <!-- =====================================
             CN COINS PACKAGE
        ====================================== -->

        <div class="package-box">

            <div class="coin-icon">

                <i class="fa-solid fa-coins"></i>

            </div>


            <div class="coin-amount">

                <?=number_format($coins)?>

            </div>


            <div class="coin-label">

                CN Coins

            </div>


            <div class="price">

                <?=number_format($amount)?>

                LAK

            </div>

        </div>


        <!-- =====================================
             USER INFORMATION
        ====================================== -->

        <div class="info-box">

            <div class="info-row">

                <div class="info-icon">

                    <i class="fa-solid fa-user"></i>

                </div>

                <div class="info-value">

                    <?=htmlspecialchars(
                        $user['username'],
                        ENT_QUOTES,
                        'UTF-8'
                    )?>

                </div>

            </div>


            <div class="info-row">

                <div class="info-icon">

                    <i class="fa-solid fa-envelope"></i>

                </div>

                <div class="info-value">

                    <?=htmlspecialchars(
                        $user['email'] ?? '-',
                        ENT_QUOTES,
                        'UTF-8'
                    )?>

                </div>

            </div>


            <div class="info-row">

                <div class="info-icon">

                    <i class="fa-solid fa-coins"></i>

                </div>

                <div class="info-value">

                    1 CN Coin = 1,000 LAK

                </div>

            </div>

        </div>


        <!-- =====================================
             PAYMENT BUTTON
        ====================================== -->

        <button
            type="button"
            id="payButton"
            class="pay-btn"
            onclick="payCNCoins()"
        >

            <i class="fa-solid fa-qrcode"></i>

            ชำระเงิน

        </button>


        <!-- =====================================
             PAYMENT MESSAGE
        ====================================== -->

        <div id="message"></div>


        <!-- =====================================
             BACK
        ====================================== -->

        <a
            href="coins.php"
            class="back-btn"
        >

            <i class="fa-solid fa-arrow-left"></i>

            กลับไป CN Coins

        </a>

    </div>

</div>


<!-- ==========================================
     PAYMENT INFORMATION
=========================================== -->

<script>

const COIN_ORDER_ID =
    <?=json_encode($order_id)?>;

const COIN_AMOUNT =
    <?=json_encode($amount)?>;

const COIN_QUANTITY =
    <?=json_encode($coins)?>;

</script>


<!-- ==========================================
     4/4 จะต่อ JavaScript ตรงนี้
=========================================== -->
    
    <!-- ==========================================
     CN COINS PAYMENT JAVASCRIPT
=========================================== -->

<script>

    async function payCNCoins() {

    const button =
        document.getElementById("payButton");

    const message =
        document.getElementById("message");

    button.disabled = true;

    button.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin"></i> กำลังสร้างรายการชำระเงิน...';

    message.style.display = "none";
    message.className = "";

    try {

        const response = await fetch(
            "/coin_create.process.php",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },

                credentials: "same-origin",

                body: JSON.stringify({

                    order_id:
                        <?= json_encode($order_id) ?>,

                    payment_method: "qr"

                })
            }
        );

        const text = await response.text();

        console.log(
            "coin_create.process.php:",
            text
        );

        let result;

        try {

            result = JSON.parse(text);

        } catch (e) {

            throw new Error(
                "ระบบประมวลผล Payment ส่งข้อมูลไม่ถูกต้อง"
            );

        }


        if (!result.success) {

            throw new Error(
                result.message ||
                "ไม่สามารถสร้างรายการชำระเงินได้"
            );

        }


        /*
        =================================
        REDIRECT
        =================================
        */

        if (result.redirect) {

            window.location.href =
                result.redirect;

            return;

        }


        /*
        =================================
        PAYMENT URL
        =================================
        */

        if (result.payment_url) {

            window.location.href =
                result.payment_url;

            return;

        }


        throw new Error(
            "ไม่พบ URL สำหรับชำระเงิน"
        );


    } catch (error) {

        console.error(
            "CN Coins Payment:",
            error
        );

        message.className =
            "message-error";

        message.innerHTML =
            '<i class="fa-solid fa-circle-exclamation"></i> '
            +
            escapeHtml(
                error.message
            );

        message.style.display =
            "block";


        button.disabled = false;

        button.innerHTML =
            '<i class="fa-solid fa-qrcode"></i> ชำระเงิน';

    }

}








/*
=========================================
ESCAPE HTML
=========================================
*/

function escapeHtml(text) {

    const div =
        document.createElement("div");

    div.textContent =
        String(text);

    return div.innerHTML;

}


/*
=========================================
PREVENT DOUBLE PAYMENT
=========================================
*/

let paymentStarted = false;


const originalPayCNCoins =
    payCNCoins;


payCNCoins = async function () {

    if (paymentStarted) {

        return;

    }


    paymentStarted = true;


    try {

        await originalPayCNCoins();

    } finally {

        /*
        ถ้าเกิด error
        ให้กดใหม่ได้

        แต่ถ้า redirect สำเร็จ
        หน้าเว็บจะเปลี่ยนก่อน
        */

        setTimeout(
            function () {

                paymentStarted =
                    false;

            },
            1500
        );

    }

};


</script>


<!-- ==========================================
     END PAGE
=========================================== -->

</body>

</html>