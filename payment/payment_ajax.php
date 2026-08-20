<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header("Content-Type: text/html; charset=utf-8");

require "../config.php";
require "../database.php";


// =====================================================
// ORDER
// =====================================================

$order_id = trim($_GET['order_id'] ?? '');

$type = strtolower(
    trim($_GET['type'] ?? 'shop')
);


if ($order_id === '') {

    die("Missing Order ID");

}


// =====================================================
// ALLOWED PAYMENT TYPES
// =====================================================

$allowed_types = [
    'shop',
    'game',
    'mobile',
    'coins'
];


if (!in_array($type, $allowed_types, true)) {

    die("Invalid Payment Type");

}


// =====================================================
// PAYMENT METHODS
// =====================================================

$banks = [];

$cards = [];


// =====================================================
// GET BANK / CARD
// =====================================================

$sql = "
    SELECT *
    FROM admin_from_bank_account
    ORDER BY type ASC, id ASC
";


$result = $conn->query($sql);


if ($result) {

    while ($row = $result->fetch_assoc()) {

        /*
        ================================================
        BANK NAME
        ================================================
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

            $bank_name =
                'UNKNOWN';

        }


        /*
        ================================================
        STATUS
        ================================================
        */

        $status = strtolower(
            trim(
                $row['status']
                ?? 'maintenance'
            )
        );


        /*
        ================================================
        TYPE
        ================================================
        */

        $payment_type = strtolower(
            trim(
                $row['type']
                ?? 'bank'
            )
        );


        /*
        ================================================
        IMAGE
        ================================================
        */

        $image = "/assets/no-image.png";


        if (
            isset($row['image']) &&
            trim($row['image']) !== ''
        ) {

            $image =
                "/admin/uploads/"
                . basename(
                    $row['image']
                );

        }


        /*
        ================================================
        PAYMENT ITEM
        ================================================
        */

        $item = [

            "id" =>
                (int)(
                    $row['id']
                    ?? 0
                ),

            "name" =>
                $bank_name,

            "image" =>
                $image,

            "status" =>
                $status,

            "type" =>
                $payment_type

        ];


        /*
        ================================================
        CARD / BANK
        ================================================
        */

        if (
            $payment_type === 'card'
        ) {

            $cards[] = $item;

        } else {

            $banks[] = $item;

        }

    }

}


// =====================================================
// CN COINS ORDER INFORMATION
// =====================================================

$coin_order = null;

if ($type === 'coins') {

    $stmt = $conn->prepare("
        SELECT
            id,
            order_id,
            user_id,
            coins,
            amount,
            payment_status,
            transaction_id
        FROM coin_orders
        WHERE order_id = ?
        LIMIT 1
    ");

    if ($stmt) {

        $stmt->bind_param(
            "s",
            $order_id
        );

        $stmt->execute();

        $result =
            $stmt->get_result();

        $coin_order =
            $result->fetch_assoc();

        $stmt->close();

    }


    /*
    ================================================
    CHECK CN COINS ORDER
    ================================================
    */

    if (!$coin_order) {

        die("CN Coins Order not found");

    }


    /*
    ================================================
    CHECK USER
    ================================================
    */

    if (
        isset($_SESSION['user_id']) &&
        (int)$_SESSION['user_id']
        !==
        (int)$coin_order['user_id']
    ) {

        http_response_code(403);

        die("Access Denied");

    }


    /*
    ================================================
    CHECK PAYMENT STATUS
    ================================================
    */

    if (
        isset($coin_order['payment_status']) &&
        $coin_order['payment_status'] === 'paid'
    ) {

        die("Order already paid");

    }


    /*
    ================================================
    COINS / AMOUNT
    ================================================
    */

    $coin_amount =
        (int)$coin_order['coins'];

    $payment_amount =
        (float)$coin_order['amount'];

} else {

    $coin_amount = 0;

    $payment_amount = 0;

}

?>


<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width,initial-scale=1.0">



<title>

Payment Gateway | CNTECH STORE

</title>




<link rel="stylesheet"

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

#250000

);



color:white;


padding-bottom:80px;



}



/* HEADER */


.app-header{


height:70px;


display:flex;


align-items:center;


justify-content:space-between;



padding:0 20px;



background:

linear-gradient(

135deg,

rgba(255,0,0,.8),

rgba(0,0,0,.9)

);



border-bottom:

2px solid #ff0000;



box-shadow:

0 10px 30px rgba(255,0,0,.25);



}



.logo{


font-size:24px;

font-weight:900;


}



.logo span{


color:#ff0000;


}



.header-icon{


width:45px;


height:45px;


border-radius:50%;



display:flex;


align-items:center;


justify-content:center;



background:#ff0000;



}



.header-icon i{


font-size:22px;


}





/* CONTAINER */


.container{


width:100%;


max-width:650px;


margin:auto;


padding:15px;



}





/* GLASS BOX */


.glass{


background:

rgba(255,255,255,.08);



border:

1px solid rgba(255,255,255,.15);



backdrop-filter:

blur(15px);



border-radius:22px;



padding:20px;



margin-bottom:20px;



box-shadow:


0 10px 30px rgba(0,0,0,.35);



}





/* ORDER */


.order-title{


display:flex;


align-items:center;


gap:10px;



font-size:20px;


font-weight:bold;



}



.order-title i{


color:#ff0000;


}




.order-id{


margin-top:15px;



background:

rgba(0,0,0,.45);



padding:12px;



border-radius:12px;



font-size:14px;



word-break:break-all;



color:#fff;


}




.status-pending{


margin-top:15px;



display:inline-flex;



align-items:center;



gap:8px;



background:#422006;



color:#facc15;



padding:8px 15px;



border-radius:20px;



font-size:13px;



}





/* SECTION TITLE */


.section-title{


font-size:20px;


font-weight:bold;


margin:25px 0 15px;



}



.section-title i{


color:#ff0000;


}

/* =====================
   LOADING BUTTON
===================== */

.loading-btn{

    width:100%;

    padding:15px 20px;

    border:none;

    border-radius:18px;


    background:
    linear-gradient(
        135deg,
        #ff0000,
        #990000
    );


    color:white;


    font-size:16px;

    font-weight:800;


    display:flex;

    align-items:center;

    justify-content:center;

    gap:12px;


    box-shadow:

    0 10px 30px
    rgba(255,0,0,.35);


    cursor:not-allowed;

}



/* วงกลม Loading */

.loading-circle{


    width:22px;

    height:22px;


    border-radius:50%;


    border:

    3px solid
    rgba(255,255,255,.3);


    border-top-color:white;


    animation:

    spin 0.8s linear infinite;


}



/* Animation */

@keyframes spin{


from{

    transform:rotate(0deg);

}


to{

    transform:rotate(360deg);

}


    }

    
    /* =========================
PAYMENT GRID
========================= */

.payment-grid{

    display:grid;

    grid-template-columns:
    repeat(2,1fr);

    gap:14px;

    width:100%;

}



/* PC */

@media(min-width:900px){

.payment-grid{

    grid-template-columns:
    repeat(4,1fr);

}

}



/* =========================
PAYMENT CARD GLASS
========================= */


.payment-card{


position:relative;


overflow:hidden;


min-height:190px;



padding:15px;



border-radius:24px;



cursor:pointer;



display:flex;


flex-direction:column;


align-items:center;


justify-content:center;



color:white;



background:

linear-gradient(

145deg,

rgba(255,255,255,.12),

rgba(255,0,0,.08)

);



border:

1px solid

rgba(255,255,255,.18);



backdrop-filter:

blur(18px);



-webkit-backdrop-filter:

blur(18px);



box-shadow:

0 10px 30px

rgba(0,0,0,.45);



transition:

.3s ease;



}



/* แสงแดงด้านบน */


.payment-card::before{


content:"";


position:absolute;


top:0;


left:0;


width:100%;


height:3px;



background:

linear-gradient(

90deg,

transparent,

#ff0000,

transparent

);



}



/* Hover */


.payment-card:hover{


transform:

translateY(-8px)
scale(1.03);



border-color:#ff0000;



box-shadow:


0 15px 40px

rgba(255,0,0,.35);



}



/* Selected */


.payment-card.active{


border:

2px solid #ff0000;



background:

linear-gradient(

145deg,

rgba(255,0,0,.25),

rgba(0,0,0,.7)

);



}





/* Disabled */


.payment-card.disabled{


opacity:.45;


filter:grayscale(1);



cursor:not-allowed;


}





/* =========================
IMAGE
========================= */


.payment-image{


width:85px;


height:85px;



display:flex;


align-items:center;


justify-content:center;



margin-bottom:12px;



background:white;



border-radius:22px;



padding:10px;



box-shadow:


0 8px 20px

rgba(0,0,0,.35);



}




.payment-image img{


width:100%;


height:100%;



object-fit:contain;



border-radius:14px;



}




/* =========================
NAME
========================= */


.payment-name{


font-size:16px;



font-weight:900;



text-align:center;



margin-top:5px;



color:#ffffff;



}



/* =========================
STATUS
========================= */


.payment-status{


margin-top:10px;



padding:5px 12px;



border-radius:20px;



font-size:12px;



font-weight:bold;



display:flex;



align-items:center;



gap:6px;



background:

rgba(0,0,0,.35);



}



/* จุดสถานะ */


.payment-status i{


font-size:8px;


}



/* ONLINE */

.payment-status.online{


color:#22c55e;



border:

1px solid rgba(34,197,94,.4);



}



/* OFFLINE */

.payment-status.offline{


color:#ef4444;



border:

1px solid rgba(239,68,68,.4);



}



/* MAINTENANCE */


.payment-status.maintenance{


color:#facc15;



border:

1px solid rgba(250,204,21,.4);



}





/* =========================
SELECT BUTTON
========================= */


.select-pay{


margin-top:12px;


width:100%;



padding:9px;



border-radius:14px;



text-align:center;



font-size:13px;



font-weight:bold;



background:

linear-gradient(

135deg,

#ff0000,

#990000

);



box-shadow:


0 5px 15px

rgba(255,0,0,.3);



}





/* MOBILE SMALL */


@media(max-width:420px){


.payment-card{


min-height:165px;


padding:10px;


}



.payment-image{


width:65px;


height:65px;


}



.payment-name{


font-size:14px;


}



    }
    
    /* =========================
BACK BUTTON
========================= */

.back-button{

    width:100%;


    margin-top:20px;


    padding:15px 20px;



    border:none;



    border-radius:18px;



    cursor:pointer;



    color:white;



    font-size:16px;



    font-weight:900;



    display:flex;



    align-items:center;



    justify-content:center;



    gap:10px;




    background:

    linear-gradient(

        135deg,

        rgba(255,255,255,.15),

        rgba(0,0,0,.7)

    );



    border:

    1px solid

    rgba(255,255,255,.2);



    backdrop-filter:

    blur(15px);



    -webkit-backdrop-filter:

    blur(15px);




    box-shadow:


    0 10px 30px

    rgba(0,0,0,.45);



    transition:.3s ease;


}



/* Icon */

.back-button i{


    color:#ff0000;


    font-size:20px;


}




/* Hover */


.back-button:hover{


    transform:

    translateY(-4px);



    border-color:#ff0000;



    background:


    linear-gradient(

        135deg,

        rgba(255,0,0,.35),

        rgba(0,0,0,.8)

    );



    box-shadow:


    0 15px 35px

    rgba(255,0,0,.35);



}



/* Active */


.back-button:active{


    transform:scale(.96);


}



/* Mobile */


@media(max-width:450px){


.back-button{


    padding:14px;


    font-size:15px;


}


    }
    
    /* =========================
PAYMENT LOADING
========================= */


.payment-loading{


display:none;


position:fixed;


inset:0;



background:

rgba(0,0,0,.75);



backdrop-filter:

blur(10px);



z-index:99999;



align-items:center;


justify-content:center;



}



.loading-box{


width:90%;


max-width:350px;



padding:30px;



border-radius:25px;



text-align:center;



background:

rgba(255,255,255,.1);



border:

1px solid rgba(255,255,255,.2);



box-shadow:

0 20px 50px rgba(0,0,0,.5);



}



.loader-logo{


width:80px;


height:80px;



margin:auto;



border-radius:50%;



display:flex;


align-items:center;


justify-content:center;



background:

linear-gradient(

135deg,

#ff0000,

#990000

);



animation:

pulse 1.5s infinite;



}



.loader-logo i{


font-size:35px;


color:white;



animation:

spin 1s linear infinite;



}



.loading-box h3{


margin-top:20px;


color:white;


}



.loading-box p{


color:#ccc;


}




@keyframes spin{


from{

transform:rotate(0deg);

}

to{

transform:rotate(360deg);

}

}




@keyframes pulse{


0%{

box-shadow:

0 0 0 0 rgba(255,0,0,.6);

}


70%{

box-shadow:

0 0 0 25px rgba(255,0,0,0);

}


100%{

box-shadow:

0 0 0 0 rgba(255,0,0,0);

}


    }
    
</style>


</head>



<body>



<header class="app-header">


<div class="logo">


CNTECH

<span>

STORE

</span>


</div>



<div class="header-icon">


<i class="fa-solid fa-credit-card"></i>


</div>


</header>



<main class="container">





<!-- ORDER SUMMARY -->


<section class="glass">



<div class="order-title">


<i class="fa-solid fa-file-invoice"></i>


Payment Order


</div>



<div class="order-id">


<?=htmlspecialchars($order_id)?>

</div>



<div class="status-pending">


<i class="fa-solid fa-clock"></i>


PENDING PAYMENT


</div>



</section>





<!-- BANK TITLE -->


<div class="section-title">


<i class="fa-solid fa-building-columns"></i>


Bank Transfer


</div>



<!-- ต่อ Part 2/3 -->
    
    
<div class="payment-grid">


<?php foreach($banks as $bank): ?>


<button

class="payment-card 
<?= $bank['status']!='online' ? 'disabled':'' ?>"

onclick="

payBank(
'<?=htmlspecialchars($bank['name'],ENT_QUOTES)?>',
'<?=$bank['status']?>'
)

">


<div class="payment-image">


<img

src="<?=htmlspecialchars($bank['image'])?>"

alt="<?=htmlspecialchars($bank['name'])?>">


</div>



<div class="payment-name">

<?=htmlspecialchars($bank['name'])?>

</div>



<div class="payment-status <?=$bank['status']?>">


<i class="fa-solid fa-circle"></i>


<?=strtoupper($bank['status'])?>


</div>



<?php if($bank['status']=="online"){ ?>


<div class="select-pay">

<i class="fa-solid fa-check"></i>

เลือกชำระ

</div>


<?php }else{ ?>


<div class="select-pay offline-btn">

<i class="fa-solid fa-lock"></i>

ไม่พร้อมใช้งาน

</div>


<?php } ?>



</button>



<?php endforeach; ?>


</div>





<!-- CREDIT CARD -->


<div class="section-title">


<i class="fa-solid fa-credit-card"></i>


Credit Card


</div>



<div class="payment-grid">



<?php foreach($cards as $card): ?>


<button


class="payment-card

<?= $card['status']!='online' ? 'disabled':'' ?>"


onclick="

goStripe(

'<?=htmlspecialchars($card['name'],ENT_QUOTES)?>',

'<?=$card['status']?>'

)

"

>


<div class="payment-image">


<img

src="<?=htmlspecialchars($card['image'])?>"

alt="<?=htmlspecialchars($card['name'])?>">


</div>




<div class="payment-name">


<?=htmlspecialchars($card['name'])?>


</div>




<div class="payment-status <?=$card['status']?>">


<i class="fa-solid fa-circle"></i>


<?=strtoupper($card['status'])?>


</div>




<div class="select-pay">


<?php if($card['status']=="online"){ ?>


<i class="fa-solid fa-credit-card"></i>

ชำระด้วยบัตร


<?php }else{ ?>


<i class="fa-solid fa-lock"></i>

ปิดบริการ


<?php } ?>


</div>



</button>



<?php endforeach; ?>



</div>





<div id="paymentLoading" class="payment-loading">

    <div class="loading-box">

        <div class="loader-logo">

            <i class="fa-solid fa-spinner"></i>

        </div>


        <h3>
            กำลังสร้างรายการชำระเงิน
        </h3>


        <p>
            กรุณารอสักครู่...
        </p>

    </div>

    </div>





<button

class="back-button"

onclick="goBack()"

>


<i class="fa-solid fa-arrow-left"></i>


กลับ


</button>



    </main>
    
    <script>

const ORDER_ID = "<?=htmlspecialchars($order_id)?>";

const ORDER_TYPE = "<?=htmlspecialchars($type)?>";

function showPaymentLoading(){

    document.getElementById(
        "paymentLoading"
    ).style.display="flex";

}



function hidePaymentLoading(){

    document.getElementById(
        "paymentLoading"
    ).style.display="none";

}

   window.addEventListener("pagehide", function(){

    hidePaymentLoading();

});     
        
        window.addEventListener("beforeunload", function(){

    hidePaymentLoading();

});
        
/*
========================
BANK PAYMENT
========================
*/

function payBank(bank,status){


    if(status !== "online"){

        alert("ช่องทางนี้ยังไม่พร้อมใช้งาน");

        return;

    }



    const loading =
    document.getElementById("loading");



    showPaymentLoading();




    fetch("../api/api_payment.php",{


        method:"POST",


        headers:{


            "Content-Type":"application/json"


        },


        body:JSON.stringify({


            order_id:ORDER_ID,


            bank:bank,


            type:ORDER_TYPE



        })


    })



    .then(async response=>{


        let text = await response.text();


        console.log(text);



        try{


            return JSON.parse(text);


        }catch(e){


            throw new Error(
                "Payment API Error"
            );


        }


    })



    .then(data=>{



        if(data.status==="success"){



            if(data.redirect){


                window.location.href =
                data.redirect;


                return;


            }





            openQR(data);



        }else{


            alert(
                data.message ||
                "Payment Failed"
            );


        }



    })



    .catch(error=>{


        alert(error.message);



    })



    .finally(()=>{


        hidePaymentLoading();


    });



}






/*
========================
STRIPE
========================
*/


function goStripe(card,status){


    if(status !== "online"){

        alert("Credit Card unavailable");

        return;

    }


    showPaymentLoading();



    try{


        setTimeout(()=>{


            window.location.href =

            "stripe_checkout.php?order_id="

            + ORDER_ID

            + "&card="

            + encodeURIComponent(card);



        },800);



    }catch(error){


        console.error(error);



        hidePaymentLoading();



        alert(
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