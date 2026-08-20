<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];


// ==========================================
// WALLET
// ==========================================

$balance = 0.00;

$stmt = $conn->prepare("
    SELECT balance
    FROM cn_coins_wallet
    WHERE user_id = ?
    LIMIT 1
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($wallet = $result->fetch_assoc()) {
    $balance = (float)$wallet['balance'];
}

$stmt->close();


// ==========================================
// LIFETIME EARNED
// ==========================================

$lifetime_earned = 0.00;

$stmt = $conn->prepare("
    SELECT COALESCE(SUM(coins), 0) AS total
    FROM cn_coins_transactions
    WHERE user_id = ?
    AND coins > 0
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $lifetime_earned = (float)$row['total'];
}

$stmt->close();


// ==========================================
// LIFETIME SPENT
// ==========================================

$lifetime_spent = 0.00;

$stmt = $conn->prepare("
    SELECT COALESCE(SUM(ABS(coins)), 0) AS total
    FROM cn_coins_transactions
    WHERE user_id = ?
    AND coins < 0
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $lifetime_spent = (float)$row['total'];
}

$stmt->close();

// ==========================================
// CN COIN PACKAGES
// 1 CN Coin = 1,000 LAK
// ==========================================

$packages = [
    [
        'coins' => 10,
        'price' => 10000
    ],
    [
        'coins' => 20,
        'price' => 20000
    ],
    [
        'coins' => 50,
        'price' => 50000
    ],
    [
        'coins' => 100,
        'price' => 100000
    ],
    [
        'coins' => 200,
        'price' => 200000
    ],
    [
        'coins' => 500,
        'price' => 500000
    ],
    [
        'coins' => 1000,
        'price' => 1000000
    ]
];

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>
CN Coins - CN Tech Store
</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">


<style>

*{
    box-sizing:border-box;
}

body{

    margin:0;

    font-family:
    Arial,
    sans-serif;

    color:white;

    background:
    linear-gradient(
        135deg,
        #050505,
        #280000
    );

    min-height:100vh;

}


.navbar{

    height:65px;

    background:#090909;

    border-bottom:
    1px solid #400000;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:
    0 20px;

}


.logo{

    color:#ff0000;

    font-size:22px;

    font-weight:bold;

}


.navbar a{

    color:white;

    text-decoration:none;

    margin-left:15px;

}


.container{

    width:100%;

    max-width:900px;

    margin:auto;

    padding:25px 15px 50px;

}


.title{

    font-size:28px;

    font-weight:bold;

    margin-bottom:20px;

}


/* BALANCE */

.balance-card{

    background:
    linear-gradient(
        135deg,
        #450000,
        #130000
    );

    border:
    1px solid #ff0000;

    border-radius:22px;

    padding:30px;

    text-align:center;

    box-shadow:
    0 0 30px
    rgba(255,0,0,.2);

}


.coin-icon{

    font-size:45px;

    color:#ffd700;

}


.balance-label{

    color:#bbb;

    margin-top:10px;

}


.balance{

    font-size:42px;

    font-weight:bold;

    color:#ffd700;

    margin:8px 0;

}


.stats{

    display:flex;

    justify-content:center;

    gap:20px;

    color:#aaa;

    font-size:13px;

}


/* PACKAGES */

.section-title{

    font-size:21px;

    margin:
    30px 0 15px;

}


.packages{

    display:grid;

    grid-template-columns:
    repeat(3,1fr);

    gap:15px;

}


.package{

    background:#111;

    border:
    1px solid #333;

    border-radius:18px;

    padding:22px 15px;

    text-align:center;

}


.package:hover{

    border-color:red;

}


.package .coins{

    color:#ffd700;

    font-size:27px;

    font-weight:bold;

}


.package .price{

    color:#fff;

    margin:
    10px 0 18px;

}


.buy{

    display:block;

    background:
    linear-gradient(
        135deg,
        red,
        #900
    );

    color:white;

    text-decoration:none;

    padding:12px;

    border-radius:10px;

    font-weight:bold;

}


.history-btn{

    display:block;

    margin-top:20px;

    padding:14px;

    text-align:center;

    background:#181818;

    border:1px solid #333;

    border-radius:12px;

    color:white;

    text-decoration:none;

}


@media(max-width:700px){

    .packages{

        grid-template-columns:
        repeat(2,1fr);

    }

}


@media(max-width:450px){

    .navbar{

        padding:0 12px;

    }

    .navbar a{

        margin-left:8px;

        font-size:13px;

    }

    .container{

        padding:
        18px 10px 40px;

    }

    .balance{

        font-size:36px;

    }

    .packages{

        grid-template-columns:1fr 1fr;

        gap:10px;

    }

    .package{

        padding:
        18px 10px;

    }

}

</style>

</head>


<body>


<nav class="navbar">

<div class="logo">

<i class="fa-solid fa-coins"></i>

CN TECH STORE

</div>


<div>

<a href="dashboard.php">
<i class="fa-solid fa-house"></i>
</a>

<a href="profile.php">
<i class="fa-solid fa-user"></i>
</a>

</div>

</nav>



<div class="container">


<div class="title">

<i class="fa-solid fa-coins"></i>

CN Coins

</div>



<div class="balance-card">


<div class="coin-icon">

🪙

</div>


<div class="balance-label">

Your CN Coins Balance

</div>


<div class="balance">

<?=number_format($balance,2)?>

</div>


<div class="stats">

<span>
Earned:
<?=number_format($lifetime_earned,2)?>
</span>

<span>
Spent:
<?=number_format($lifetime_spent,2)?>
</span>

</div>


</div>



<div class="section-title">

เติม CN Coins

</div>



<div class="packages">


<?php foreach($packages as $package): ?>


<div class="package">


<div class="coins">

🪙
<?=number_format($package['coins'])?>

</div>


<div class="price">

<?=number_format($package['price'])?>

LAK

</div>


<a
class="buy"
href="coin_create.php?coins=<?=$package['coins']?>">

<i class="fa-solid fa-plus"></i>

เติม Coins

</a>


</div>


<?php endforeach; ?>


</div>



<a
class="history-btn"
href="coin_history.php">

<i class="fa-solid fa-clock-rotate-left"></i>

ประวัติ CN Coins

</a>


</div>


</body>

</html>