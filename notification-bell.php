<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();

}


$count = 0;


if(
isset($_SESSION['user_id']) &&
isset($conn)
){


$user_id = intval($_SESSION['user_id']);


$stmt = $conn->prepare("

SELECT COUNT(*) AS total

FROM notifications

WHERE user_id = ?

AND is_read = 0

");


if($stmt){


$stmt->bind_param(
"i",
$user_id
);


$stmt->execute();


$result = $stmt->get_result();


$data = $result->fetch_assoc();


$count = intval($data['total'] ?? 0);


$stmt->close();


}


}



?>


<style>


.notification-box{


position:relative;

display:inline-flex;

align-items:center;

justify-content:center;

margin:0 8px;


}



.notification-btn{


width:44px;

height:44px;


border-radius:50%;


display:flex;


align-items:center;


justify-content:center;



background:

rgba(255,255,255,.08);



border:

1px solid rgba(255,255,255,.15);



color:#fff;


font-size:20px;


text-decoration:none;



backdrop-filter:blur(12px);



transition:.3s;


}



.notification-btn:hover{


background:#ff2020;


box-shadow:

0 0 15px rgba(255,0,0,.5);


transform:translateY(-2px);


}




.notification-count{


position:absolute;


top:-6px;


right:-6px;



min-width:20px;


height:20px;



padding:0 5px;



display:flex;


align-items:center;


justify-content:center;



background:#ff2020;



color:#fff;



font-size:11px;



font-weight:bold;



border-radius:50px;



border:

2px solid #000;



}




.notification-count.large{


font-size:10px;


}




</style>





<div class="notification-box">



<a href="/notifications.php"

class="notification-btn"

title="ແຈ້ງເຕືອນ">


<i class="fa-solid fa-bell"></i>



<?php if($count > 0): ?>


<span class="notification-count <?=($count>99?'large':'')?>">


<?=($count>99?'99+':$count)?>


</span>


<?php endif; ?>



</a>



</div>