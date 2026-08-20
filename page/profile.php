<?php
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Guest';

$themeColor = "#ff0033";
?>
<!DOCTYPE html>
<html lang="lo">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<meta name="theme-color"
content="<?= $themeColor ?>">

<title>Profile - CNTECH</title>

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#0f0f0f;
color:#fff;
}

.header{

height:60px;

display:flex;
align-items:center;
justify-content:center;

background:#111;

border-bottom:
1px solid #222;

font-size:20px;
font-weight:bold;

color:#ff0033;

}

.profile{

padding:20px;

text-align:center;

}

.avatar{

width:100px;
height:100px;

border-radius:50%;

background:#ff0033;

display:flex;
align-items:center;
justify-content:center;

font-size:40px;

margin:auto;

}

.username{

margin-top:15px;

font-size:22px;
font-weight:bold;

}

.userid{

margin-top:5px;

color:#999;

}

.card{

background:#161616;

border-radius:15px;

padding:15px;

margin-top:20px;

}

.menu{

margin-top:20px;

}

.menu a{

display:flex;

justify-content:space-between;
align-items:center;

padding:15px;

background:#161616;

border-radius:12px;

margin-bottom:10px;

text-decoration:none;

color:#fff;

}

.logout{

background:#ff0033;

color:white;

border:none;

padding:14px;

width:100%;

border-radius:12px;

font-size:16px;

margin-top:20px;

}

</style>

</head>
<body>

<div class="header">
CNTECH PROFILE
</div>

<div class="profile">

<div class="avatar">
<i class="fa-solid fa-user"></i>
</div>

<div class="username">
<?= htmlspecialchars($username) ?>
</div>

<div class="userid">
UID: <?= htmlspecialchars($user_id ?? 'Guest') ?>
</div>

<div class="card">
Welcome to CNTECH STORE
</div>

<div class="menu">

<a href="orders.php">
<span>
<i class="fa-solid fa-bag-shopping"></i>
 My Orders
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

<a href="reels.php">
<span>
<i class="fa-solid fa-play"></i>
 My Reels
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

<a href="settings.php">
<span>
<i class="fa-solid fa-gear"></i>
 Settings
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

</div>

<?php if($user_id): ?>

<button
class="logout"
onclick="location.href='logout.php'">

Logout

</button>

<?php else: ?>

<button
class="logout"
onclick="location.href='login.php'">

Login

</button>

<?php endif; ?>

</div>

</body>
</html>