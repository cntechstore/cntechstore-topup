<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


require "../config.php";
require "../database.php";


if(session_status() === PHP_SESSION_NONE){

    session_start();

}


/*
=========================
USER SESSION
=========================
*/

$user_id = $_SESSION['user_id'] ?? 0;

$username =
$_SESSION['username']
?? "Guest";


$email =
$_SESSION['email']
?? "ยังไม่ได้เข้าสู่ระบบ";

if(!empty($_SESSION['user_id'])){

$user_id =
(int)$_SESSION['user_id'];

}


if($user_id > 0){


    $stmt = $conn->prepare("
        SELECT *
        FROM users
        WHERE id=?
        LIMIT 1
    ");


    if($stmt){


        $stmt->bind_param(
            "i",
            $user_id
        );


        $stmt->execute();


        $user =
        $stmt->get_result()
        ->fetch_assoc();



        if($user){


            $username =
            $user['username']
            ?? "User";


            $email =
            $user['email']
            ?? "-";


        }


    }


}


?>


<!DOCTYPE html>

<html lang="th">

<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width,initial-scale=1.0">



<title>

Account | CNTECH STORE

</title>



<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">





<style>


*{

box-sizing:border-box;

}



body{


margin:0;


background:#050505;


color:white;


font-family:

Arial,
sans-serif;


padding-bottom:90px;


}





/* =====================
CONTAINER
===================== */


.account-container{


max-width:650px;


margin:auto;


padding:15px;


}






/* =====================
HEADER PROFILE
===================== */


.profile-card{


background:

linear-gradient(

135deg,

#000,

#850000

);



border-radius:25px;



padding:25px;



display:flex;



align-items:center;



gap:20px;




border:

1px solid

rgba(255,0,0,.5);




box-shadow:


0 15px 40px

rgba(255,0,0,.25);





}





.avatar{


width:85px;


height:85px;



border-radius:50%;



background:white;



display:flex;



align-items:center;



justify-content:center;



font-size:38px;



color:#e50914;



box-shadow:


0 0 25px

rgba(255,0,0,.5);



}





.profile-info h1{


margin:0;


font-size:24px;



font-weight:900;


}





.profile-info p{


margin:8px 0 0;


color:#ddd;


font-size:14px;


}





.badge{


display:inline-flex;



margin-top:10px;



padding:5px 12px;



border-radius:20px;



background:#ff0000;



font-size:12px;



font-weight:bold;



}





/* =====================
GLASS MENU
===================== */


.account-section{


margin-top:25px;


}





.section-title{


font-size:20px;



font-weight:900;



margin-bottom:15px;


}



.section-title i{


color:#ff0000;


}





.menu-card{


display:flex;



align-items:center;



gap:18px;



padding:18px;



margin-bottom:14px;



border-radius:20px;



text-decoration:none;



color:white;




background:

rgba(255,255,255,.08);




border:

1px solid

rgba(255,255,255,.15);




backdrop-filter:

blur(15px);




transition:.3s;



}





.menu-card i{


font-size:25px;



width:35px;



color:#ff0000;


}





.menu-card span{


font-size:16px;



font-weight:bold;


}





.menu-card:hover{


transform:

translateX(8px);



border-color:#ff0000;



}




/* LOGOUT */


.logout{


background:

rgba(255,0,0,.18);


}



.logout i{


color:white;


}




@media(max-width:450px){



.profile-card{


padding:20px;


}



.avatar{


width:70px;

height:70px;


font-size:30px;


}



.profile-info h1{


font-size:20px;


}



}



</style>


</head>


<body>




<div class="account-container">



<!-- PROFILE -->

<div class="profile-card">


<div class="avatar">


<i class="fa-solid fa-user"></i>


</div>



<div class="profile-info">


<h1>

<?=htmlspecialchars($username)?>

</h1>



<p>

<i class="fa-solid fa-envelope"></i>

<?=htmlspecialchars($email)?>

</p>



<div class="badge">


<i class="fa-solid fa-crown"></i>

CNTECH MEMBER


</div>



</div>


</div>





<!-- MENU START -->

<div class="account-section">


<div class="section-title">


<i class="fa-solid fa-user-circle"></i>

Account


    </div>
    
    <!-- ACCOUNT MENU -->


<a href="orders.php" class="menu-card">

<i class="fa-solid fa-box-open"></i>

<span>

คำสั่งซื้อของฉัน

</span>

</a>



<a href="game/history.php" class="menu-card">

<i class="fa-solid fa-gamepad"></i>

<span>

ประวัติเติมเกม

</span>

</a>



<a href="voucher/history.php" class="menu-card">

<i class="fa-solid fa-ticket"></i>

<span>

ประวัติ Voucher

</span>

</a>



<a href="payment/history.php" class="menu-card">

<i class="fa-solid fa-credit-card"></i>

<span>

ประวัติการชำระเงิน

</span>

</a>



<a href="settings.php" class="menu-card">

<i class="fa-solid fa-gear"></i>

<span>

ตั้งค่าบัญชี

</span>

</a>



</div>





<?php

/*
=========================
STATISTICS
=========================
*/


$totalOrders = 0;
$totalGame = 0;
$totalVoucher = 0;



if($user_id > 0){



/*
SHOP ORDERS
*/

if($conn->query("
SHOW TABLES LIKE 'shop_orders'
")->num_rows > 0){


$stmt = $conn->prepare("

SELECT COUNT(*) total

FROM shop_orders

WHERE user_id=?

");


if($stmt){

$stmt->bind_param(
"i",
$user_id
);


$stmt->execute();


$row =
$stmt->get_result()
->fetch_assoc();


$totalOrders =
$row['total'] ?? 0;


}


}



/*
GAME ORDERS
*/


if($conn->query("
SHOW TABLES LIKE 'game_orders'
")->num_rows > 0){



$stmt = $conn->prepare("

SELECT COUNT(*) total

FROM game_orders

WHERE user_id=?

");


if($stmt){


$stmt->bind_param(
"i",
$user_id
);



$stmt->execute();



$row =
$stmt->get_result()
->fetch_assoc();



$totalGame =
$row['total'] ?? 0;


}



}



/*
VOUCHER ORDERS
*/


if($conn->query("
SHOW TABLES LIKE 'voucher_orders'
")->num_rows > 0){



$stmt = $conn->prepare("

SELECT COUNT(*) total

FROM voucher_orders

WHERE user_id=?

");



if($stmt){


$stmt->bind_param(
"i",
$user_id
);



$stmt->execute();



$row =
$stmt->get_result()
->fetch_assoc();



$totalVoucher =
$row['total'] ?? 0;


}


}



}



?>






<!-- STATISTICS -->


<div class="account-section">


<div class="section-title">


<i class="fa-solid fa-chart-line"></i>

Overview


</div>





<div class="stats-grid">



<div class="stat-card">


<i class="fa-solid fa-cart-shopping"></i>


<div>


<b>

<?=number_format($totalOrders)?>

</b>


<span>

Orders

</span>


</div>


</div>






<div class="stat-card">


<i class="fa-solid fa-gamepad"></i>


<div>


<b>

<?=number_format($totalGame)?>

</b>


<span>

Game Topup

</span>


</div>


</div>






<div class="stat-card">


<i class="fa-solid fa-ticket"></i>


<div>


<b>

<?=number_format($totalVoucher)?>

</b>


<span>

Voucher

</span>


</div>


</div>





</div>


</div>





<style>


/* =====================
STAT CARD
===================== */


.stats-grid{


display:grid;


grid-template-columns:

repeat(3,1fr);


gap:12px;


}





.stat-card{


background:

rgba(255,255,255,.08);



border:

1px solid

rgba(255,255,255,.15);



border-radius:18px;



padding:15px;



display:flex;



align-items:center;



gap:10px;



backdrop-filter:

blur(15px);



}





.stat-card i{


font-size:25px;



color:#ff0000;


}





.stat-card b{


display:block;



font-size:20px;


}





.stat-card span{


font-size:12px;



color:#ccc;


}





@media(max-width:450px){


.stats-grid{


grid-template-columns:

repeat(3,1fr);


}



.stat-card{


padding:10px;



display:block;



text-align:center;


}



.stat-card i{


font-size:22px;


}



.stat-card b{


font-size:17px;


}



}


    </style>
    
    <!-- LOGOUT -->

<div class="account-section">


<div class="section-title">

<i class="fa-solid fa-shield-halved"></i>

Security

</div>



<a href="logout.php" 
class="menu-card logout">


<i class="fa-solid fa-right-from-bracket"></i>


<span>

ออกจากระบบ

</span>


</a>


</div>





</div>




<!-- =====================
BOTTOM NAV
===================== -->


<nav class="bottom-nav">



<a href="/">

<i class="fa-solid fa-house"></i>

<span>

Home

</span>

</a>





<a href="/game/game_topup.php">

<i class="fa-solid fa-gamepad"></i>

<span>

Games

</span>

</a>





<a href="/cart.php">

<i class="fa-solid fa-cart-shopping"></i>

<span>

Cart

</span>

</a>





<a href="/account.php"
class="active">


<i class="fa-solid fa-user"></i>

<span>

Account

</span>


</a>



</nav>





<style>


/* =====================
BOTTOM NAV
===================== */


.bottom-nav{


position:fixed;


bottom:0;


left:0;


right:0;



height:75px;



background:

rgba(0,0,0,.85);



backdrop-filter:

blur(15px);




border-top:

1px solid

rgba(255,0,0,.5);




display:flex;



justify-content:space-around;



align-items:center;



z-index:999;



}





.bottom-nav a{


color:#aaa;



text-decoration:none;



font-size:12px;



text-align:center;



transition:.3s;



}





.bottom-nav i{


display:block;



font-size:23px;



margin-bottom:5px;



}





.bottom-nav a.active{


color:#ff0000;


}




.bottom-nav a:hover{


color:#fff;


}





/* =====================
DESKTOP
===================== */


@media(min-width:900px){


.bottom-nav{


max-width:650px;


left:50%;


transform:translateX(-50%);



border-radius:25px 25px 0 0;


}



}




</style>





<script>


/*
=====================
CLICK EFFECT
=====================
*/


document
.querySelectorAll(".menu-card")
.forEach(card=>{


card.addEventListener(
"click",
function(){


this.style.transform=
"scale(.96)";



setTimeout(()=>{


this.style.transform="";


},150);



});


});




/*
=====================
CONFIRM LOGOUT
=====================
*/


const logout =
document.querySelector(".logout");



if(logout){


logout.addEventListener(
"click",
function(e){


let ok =
confirm(
"ต้องการออกจากระบบหรือไม่?"
);



if(!ok){

e.preventDefault();

}



});


}



</script>



</body>

</html>