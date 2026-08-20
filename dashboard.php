<?php

session_start();

require_once "database.php";


/*
========================
CHECK LOGIN
========================
*/

if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit;

}


$user_id = $_SESSION['user_id'];



/*
========================
GET USER DATA
========================
*/

$stmt = $conn->prepare("

SELECT *

FROM users

WHERE id=?

LIMIT 1

");


$stmt->bind_param(
"i",
$user_id
);


$stmt->execute();


$result = $stmt->get_result();


$user = $result->fetch_assoc();



if(!$user){

    session_destroy();

    header("Location: login.php");

    exit;

}



/*
========================
GET NOTIFICATION
========================
*/


$stmt2 = $conn->prepare("

SELECT *

FROM notifications

WHERE user_id=?

ORDER BY id DESC

LIMIT 5

");


$stmt2->bind_param(
"i",
$user_id
);


$stmt2->execute();


$notifications =
$stmt2->get_result();



?>

<!DOCTYPE html>

<html lang="lo">

<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width,initial-scale=1.0">


<meta name="theme-color"
content="#ff0000">



<title>

CN Tech Store Dashboard

</title>



<link rel="icon"
href="assets/favicon.png">



<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">



<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"

rel="stylesheet">



<style>


*{

margin:0;

padding:0;

box-sizing:border-box;

font-family:'Poppins',sans-serif;

}



body{


min-height:100vh;


background:


radial-gradient(

circle at top left,

#550000,

transparent 35%

),


radial-gradient(

circle at bottom right,

#330000,

transparent 35%

),


#000;



color:white;


padding-bottom:80px;


}



/*
========================
NAVBAR
========================
*/


.navbar{


height:75px;


display:flex;


align-items:center;


justify-content:space-between;



padding:0 25px;



background:

rgba(0,0,0,.75);



backdrop-filter:

blur(20px);



border-bottom:

1px solid rgba(255,0,0,.3);



position:sticky;


top:0;


z-index:100;



}




.logo{


font-size:22px;


font-weight:700;


color:white;


}



.logo i{


color:#ff0000;


margin-right:8px;


}




.nav-right{


display:flex;


align-items:center;


gap:20px;


}



.nav-right a{


color:white;


text-decoration:none;


font-size:18px;


}



.notification{


position:relative;


}



.badge{


position:absolute;


top:-8px;


right:-8px;


background:#ff0000;


color:white;


font-size:11px;


width:18px;


height:18px;


border-radius:50%;


display:flex;


align-items:center;


justify-content:center;


}



.menu-btn{


cursor:pointer;


font-size:22px;


    }
    
    /*
========================
CONTAINER
========================
*/

.container{

max-width:1200px;

margin:30px auto;

padding:20px;

}



/*
========================
PROFILE CARD
========================
*/


.profile-card{


background:

rgba(255,255,255,.08);



border:

1px solid rgba(255,0,0,.25);



backdrop-filter:

blur(20px);



border-radius:30px;



padding:35px;



display:flex;



align-items:center;



gap:30px;



box-shadow:

0 20px 60px rgba(255,0,0,.25);



}



.avatar{


width:130px;


height:130px;


border-radius:50%;


object-fit:cover;



border:

5px solid #ff0000;



box-shadow:


0 0 35px rgba(255,0,0,.7);



}



.user-info h1{


font-size:30px;


margin-bottom:8px;


}



.user-info p{


color:#ccc;


margin:8px 0;


}



.user-info i{


color:#ff2020;


width:25px;


}




.role{


display:inline-block;


padding:6px 18px;


border-radius:20px;


background:


linear-gradient(

135deg,

#ff0000,

#700000

);



font-size:13px;


margin-top:10px;


}




/*
========================
STAT CARDS
========================
*/


.stats{


display:grid;


grid-template-columns:

repeat(3,1fr);



gap:20px;


margin-top:30px;


}



.stat-card{


background:


rgba(255,255,255,.07);



border:

1px solid rgba(255,0,0,.2);



border-radius:25px;



padding:25px;



box-shadow:


0 15px 40px rgba(0,0,0,.5);



}



.stat-card i{


font-size:35px;


color:#ff0000;


margin-bottom:15px;


}



.stat-title{


color:#aaa;


font-size:14px;


}



.stat-number{


font-size:32px;


font-weight:700;


margin-top:10px;


}



.coin{


color:#ffd700;


text-shadow:


0 0 15px gold;


}




.referral{


color:#ff5555;


font-size:22px;


font-weight:bold;


}



/*
========================
QUICK MENU
========================
*/


.quick-menu{


margin-top:30px;


display:grid;


grid-template-columns:

repeat(4,1fr);



gap:20px;


}



.quick-item{


background:


rgba(255,255,255,.08);



border:

1px solid rgba(255,0,0,.25);



padding:25px;



border-radius:25px;



text-align:center;



text-decoration:none;



color:white;



transition:.3s;



}



.quick-item:hover{


transform:

translateY(-8px);



background:

rgba(255,0,0,.15);



box-shadow:

0 15px 40px rgba(255,0,0,.3);



}



.quick-item i{


font-size:35px;


color:#ff0000;


margin-bottom:15px;


}



.quick-item span{


display:block;


font-size:14px;


}



/*
========================
RESPONSIVE
========================
*/


@media(max-width:800px){


.profile-card{


flex-direction:column;


text-align:center;


}



.stats{


grid-template-columns:1fr;


}



.quick-menu{


grid-template-columns:

repeat(2,1fr);


}


}



@media(max-width:450px){



.quick-menu{


grid-template-columns:1fr;


}



}



</style>


    </head>
    
    <body>


<!-- ========================
NAVBAR
======================== -->


<nav class="navbar">


<div class="logo">

<i class="fa-solid fa-store"></i>

CN Tech Store

</div>



<div class="nav-right">


<a href="notifications.php"
class="notification">


<i class="fa-solid fa-bell"></i>


<?php

$count =
$notifications->num_rows;


if($count > 0){

?>

<span class="badge">

<?=$count?>

</span>

<?php

}

?>


</a>




<a href="logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

</a>



</div>


</nav>





<div class="container">





<!-- ========================
PROFILE
======================== -->


<div class="profile-card">



<img

class="avatar"

src="uploads/avatar/<?=htmlspecialchars($user['avatar'])?>"

onerror="this.src='uploads/avatar/default.png';"

>



<div class="user-info">



<h1>

<?=htmlspecialchars($user['fullname'])?>

</h1>




<p>

<i class="fa-solid fa-user"></i>

@

<?=htmlspecialchars($user['username'])?>

</p>




<p>

<i class="fa-solid fa-envelope"></i>

<?=htmlspecialchars($user['email'])?>

</p>




<p>

<i class="fa-solid fa-calendar"></i>

<?=$user['birthday']?>



<?php if(!empty($user['age'])){ ?>

(

<?=$user['age']?>

years

)

<?php } ?>


</p>




<span class="role">


<i class="fa-solid fa-shield"></i>

<?=strtoupper($user['role'])?>


</span>




</div>



</div>







<!-- ========================
STAT BOX
======================== -->


<div class="stats">





<div class="stat-card">


<i class="fa-solid fa-coins"></i>


<div class="stat-title">

CN Coins

</div>


<div class="stat-number coin">

<?=number_format($user['cn_coins'])?>

</div>


</div>







<div class="stat-card">


<i class="fa-solid fa-share-nodes"></i>


<div class="stat-title">

Referral Code

</div>


<div class="stat-number referral">

<?=$user['referral_code']?>

</div>


</div>







<div class="stat-card">


<i class="fa-solid fa-calendar-check"></i>


<div class="stat-title">

Member Since

</div>


<div class="stat-number">


<?=date(
"Y",
strtotime($user['created_at'])
)?>

</div>


</div>






</div>









<!-- ========================
QUICK MENU
======================== -->



<div class="quick-menu">





<a href="topup.php"
class="quick-item">


<i class="fa-solid fa-gamepad"></i>


<span>

เติมเกมออนไลน์

</span>


</a>







<a href="shop.php"
class="quick-item">


<i class="fa-solid fa-cart-shopping"></i>


<span>

CN Store

</span>


</a>







<a href="orders.php"
class="quick-item">


<i class="fa-solid fa-box"></i>


<span>

My Orders

</span>


</a>







<a href="profile.php"
class="quick-item">


<i class="fa-solid fa-user-gear"></i>


<span>

Account

</span>


</a>






    </div>
    
    



<!-- ========================
NOTIFICATIONS
======================== -->


<div style="margin-top:35px;">


<h2>

<i class="fa-solid fa-bell"></i>

Notifications

</h2>



<div class="stats">


<?php if($notifications->num_rows > 0){ ?>



<?php while($noti = $notifications->fetch_assoc()){ ?>



<div class="stat-card">


<i class="fa-solid fa-circle-info"></i>



<div class="stat-title">


<?=htmlspecialchars($noti['title'])?>


</div>



<p style="color:#ccc;margin-top:10px;">


<?=htmlspecialchars($noti['message'])?>


</p>



<small style="color:#777;">


<?=$noti['created_at']?>


</small>



</div>



<?php } ?>



<?php }else{ ?>



<div class="stat-card">


<i class="fa-solid fa-inbox"></i>


<p>

No notifications

</p>


</div>



<?php } ?>



</div>



</div>







<!-- ========================
ACCOUNT INFO
======================== -->


<div style="margin-top:35px;">


<div class="stat-card">


<h3>

<i class="fa-solid fa-id-card"></i>

Account Information

</h3>



<p style="margin-top:15px;color:#ccc;">


Provider :

<b>

<?=

$user['oauth_provider'] ?? "Local"

?>

</b>


</p>



<p style="color:#ccc;">


Status :

<b style="color:#00ff88;">


Active


</b>


</p>



</div>


</div>







</div>






<!-- ========================
MOBILE MENU
======================== -->


<div class="mobile-menu">



<a href="dashboard.php">


<i class="fa-solid fa-house"></i>

Home


</a>



<a href="topup.php">


<i class="fa-solid fa-gamepad"></i>

Game


</a>



<a href="shop.php">


<i class="fa-solid fa-cart-shopping"></i>

Shop


</a>



<a href="profile.php">


<i class="fa-solid fa-user"></i>

Me


</a>



</div>








<style>


.mobile-menu{


display:none;



}



@media(max-width:700px){


.mobile-menu{


position:fixed;


bottom:0;


left:0;


right:0;



height:70px;



background:

rgba(0,0,0,.85);



backdrop-filter:

blur(20px);



border-top:

1px solid rgba(255,0,0,.3);



display:flex;



justify-content:space-around;



align-items:center;



z-index:999;



}



.mobile-menu a{


color:white;


text-decoration:none;


font-size:12px;


text-align:center;


}



.mobile-menu i{


display:block;


font-size:22px;


color:#ff0000;


margin-bottom:5px;


}



}



</style>







<script>


/*
========================
AUTO HIDE ALERT
========================
*/


function logoutConfirm(){


return confirm(
"Logout from CN Tech Store?"
);


}



/*
========================
COPY REFERRAL
========================
*/


function copyReferral(){


navigator.clipboard.writeText(

"<?=$user['referral_code']?>"

);



alert(
"Referral copied!"
);


}



</script>







</body>


</html>