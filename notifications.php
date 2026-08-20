<?php

require "config.php";
require "database.php";

session_start();


if(!isset($_SESSION['user_id'])){

header("Location: login.php");
exit;

}


$user_id = $_SESSION['user_id'];


// Mark Read

if(isset($_GET['read'])){


$id = intval($_GET['read']);


$stmt=$conn->prepare("

UPDATE notifications

SET is_read=1

WHERE id=?

AND user_id=?

");


$stmt->bind_param(
"ii",
$id,
$user_id
);


$stmt->execute();


header("Location: notifications.php");

exit;

}



// Read All

if(isset($_GET['readall'])){


$stmt=$conn->prepare("

UPDATE notifications

SET is_read=1

WHERE user_id=?

");


$stmt->bind_param(
"i",
$user_id
);


$stmt->execute();


header("Location: notifications.php");

exit;

}



// Get Notifications


$stmt=$conn->prepare("

SELECT *

FROM notifications

WHERE user_id=?

ORDER BY id DESC

");


$stmt->bind_param(
"i",
$user_id
);


$stmt->execute();


$result=$stmt->get_result();



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
ແຈ້ງເຕືອນ | CN Tech Store
</title>


<link rel="icon"
href="assets/favicon.png">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">



<style>


*{

box-sizing:border-box;
font-family:Arial,sans-serif;

}



body{

margin:0;

background:#000;

color:white;

}



.header{

padding:20px;

text-align:center;

background:

linear-gradient(
180deg,
#180000,
#000
);

border-bottom:1px solid #333;

}



.logo{

font-size:26px;

font-weight:bold;

color:#ff2020;

}



.logo span{

color:#fff;

}



.container{

max-width:700px;

margin:auto;

padding:15px;

}



.top-action{

display:flex;

justify-content:flex-end;

margin-bottom:15px;

}



.read-btn{

background:#ff2020;

color:white;

padding:10px 15px;

border-radius:30px;

text-decoration:none;

font-size:14px;

}



.card{


background:

rgba(255,255,255,.08);


border:1px solid #333;


border-radius:18px;


padding:18px;


margin-bottom:15px;


backdrop-filter:blur(15px);


transition:.3s;


}



.card:hover{

transform:translateY(-3px);

}



.card.unread{


border:

1px solid #ff2020;


box-shadow:

0 0 15px rgba(255,0,0,.25);


}



.title{

font-size:18px;

font-weight:bold;

color:#ff3030;

}



.message{

margin-top:10px;

line-height:1.6;

color:#ddd;

}



.time{

margin-top:12px;

font-size:12px;

color:#888;

}



.empty{

text-align:center;

padding:60px 20px;

color:#777;

}



.empty i{

font-size:50px;

margin-bottom:15px;

}



</style>


</head>



<body>



<div class="header">


<div class="logo">

CNTECH <span>STORE</span>

</div>


<h3>

<i class="fa-solid fa-bell"></i>

ແຈ້ງເຕືອນ

</h3>


</div>




<div class="container">


<div class="top-action">

<a href="?readall=1"
class="read-btn">

<i class="fa-solid fa-check-double"></i>

ອ່ານທັງໝົດ

</a>

</div>



<?php


if($result->num_rows == 0){


echo '

<div class="empty">

<i class="fa-solid fa-bell-slash"></i>

<p>
ຍັງບໍ່ມີການແຈ້ງເຕືອນ
</p>

</div>

';


}



while($row=$result->fetch_assoc()){


$class =
$row['is_read']
?
""
:
"unread";


?>


<a href="?read=<?=$row['id']?>"
style="text-decoration:none;color:white">



<div class="card <?=$class?>">


<div class="title">

<i class="fa-solid fa-circle-info"></i>

<?=htmlspecialchars($row['title'])?>

</div>



<div class="message">

<?=nl2br(htmlspecialchars($row['message']))?>

</div>



<div class="time">

<i class="fa-regular fa-clock"></i>

<?=$row['created_at']?>


</div>


</div>


</a>



<?php

}

?>


</div>



</body>

</html>