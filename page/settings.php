<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

$themeColor = "#ff6600";
?>
<!DOCTYPE html>
<html lang="lo">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<meta name="theme-color"
content="<?= $themeColor ?>">

<title>Settings - CNTECH</title>

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
justify-content:space-between;
padding:0 15px;
background:#111;
border-bottom:1px solid #222;
}

.header a{
color:#fff;
text-decoration:none;
font-size:20px;
}

.title{
font-size:18px;
font-weight:bold;
color:#ff6600;
}

.container{
padding:15px;
}

.card{
background:#161616;
border-radius:15px;
padding:15px;
margin-bottom:15px;
}

.card h3{
margin-bottom:10px;
}

.item{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px;
background:#1c1c1c;
border-radius:12px;
margin-bottom:10px;
text-decoration:none;
color:#fff;
}

.item i{
color:#999;
}

.danger{
background:#2b1111;
border:1px solid #ff3333;
}

.delete-btn{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:#ff3333;
color:white;
font-size:16px;
font-weight:bold;
cursor:pointer;
}

.logout-btn{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:#ff6600;
color:white;
font-size:16px;
font-weight:bold;
cursor:pointer;
margin-top:10px;
}

</style>

</head>
<body>

<div class="header">

<a href="profile.php">
<i class="fa-solid fa-arrow-left"></i>
</a>

<div class="title">
CNTECH SETTINGS
</div>

<div></div>

</div>

<div class="container">

<div class="card">

<h3>Account</h3>

<div>
Username: <?= htmlspecialchars($username) ?>
</div>

<div>
UID: <?= htmlspecialchars($user_id) ?>
</div>

</div>

<a href="edit-profile.php" class="item">
<span>
<i class="fa-solid fa-user-pen"></i>
 Edit Profile
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

<a href="change-password.php" class="item">
<span>
<i class="fa-solid fa-lock"></i>
 Change Password
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

<a href="privacy.php" class="item">
<span>
<i class="fa-solid fa-shield"></i>
 Privacy
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

<a href="notifications.php" class="item">
<span>
<i class="fa-solid fa-bell"></i>
 Notifications
</span>
<i class="fa-solid fa-chevron-right"></i>
</a>

<div class="card danger">

<h3>Danger Zone</h3>

<p style="color:#aaa;margin-bottom:15px;">
Deleting your account is permanent and cannot be undone.
</p>

<button
class="delete-btn"
onclick="deleteAccount()">
Delete Account
</button>

</div>

<button
class="logout-btn"
onclick="location.href='logout.php'">
Logout
</button>

</div>

<script>

function deleteAccount(){

if(
confirm(
"Delete your account permanently?"
)
){

window.location.href=
"delete-account.php";

}

}

</script>

</body>
</html>