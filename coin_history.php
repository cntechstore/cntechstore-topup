<?php

session_start();

require_once "database.php";


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}


$user_id = (int)$_SESSION['user_id'];



$stmt = $conn->prepare("

SELECT *

FROM cn_coin_transactions

WHERE user_id=?

ORDER BY id DESC

LIMIT 100

");


$stmt->bind_param("i",$user_id);

$stmt->execute();

$result =
$stmt->get_result();


?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>
CN Coins History
</title>


<style>

*{
box-sizing:border-box;
}

body{

margin:0;

background:
linear-gradient(
135deg,
#050505,
#250000
);

color:white;

font-family:Arial;

padding:20px;

}


.container{

max-width:800px;

margin:auto;

}


h1{

color:red;

}


.card{

background:#111;

border:1px solid #333;

border-radius:15px;

padding:16px;

margin-bottom:10px;

}


.deposit{

color:#22c55e;

}


.purchase{

color:#ef4444;

}


.refund{

color:#3b82f6;

}


.admin{

color:#ffd700;

}


.amount{

font-size:20px;

font-weight:bold;

}


.date{

color:#888;

font-size:12px;

margin-top:5px;

}


.back{

display:block;

padding:14px;

background:red;

color:white;

text-decoration:none;

text-align:center;

border-radius:12px;

margin-bottom:20px;

}


</style>

</head>


<body>


<div class="container">


<a
class="back"
href="coins.php">

← CN Coins

</a>


<h1>

🪙 CN Coins History

</h1>



<?php if($result->num_rows === 0): ?>


<div class="card">

ยังไม่มีรายการ

</div>


<?php endif; ?>



<?php while($row=$result->fetch_assoc()): ?>


<?php

$type =
strtolower($row['type']);

$class =
in_array(
    $type,
    ['deposit','refund','admin']
)
? $type
: 'purchase';


$sign =
in_array(
    $type,
    ['deposit','refund','admin']
)
? '+'
: '-';

?>


<div class="card">


<div>

<strong>

<?=htmlspecialchars(
$row['description']
?? ucfirst($type)
)?>

</strong>

</div>


<div class="<?=$class?> amount">

<?=$sign?>

<?=number_format(
abs($row['amount']),
2
)?>

Coins

</div>


<div>

Balance:

<?=number_format(
$row['balance_after'],
2
)?>

</div>


<div class="date">

<?=htmlspecialchars(
$row['created_at']
)?>

</div>


</div>


<?php endwhile; ?>


</div>


</body>

</html>