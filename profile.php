<?php
session_start();
require_once "database.php";

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Fetch User Data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user session exists but user is not found in database
if (!$user) {
    session_destroy(); 
    header("Location: login.php"); 
    exit;
}

// 3. Fetch Wallet Balance (CN Coins)
$cn_coins = 0;
$stmt = $conn->prepare("SELECT balance FROM cn_coins_wallet WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $user['id']); 
$stmt->execute();
$result = $stmt->get_result();

if ($wallet = $result->fetch_assoc()) { 
    $cn_coins = $wallet['balance']; 
}

// Initialize messages for the view
$success = ""; 
$error = "";
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<meta name="theme-color"
content="#ff0000">

<title>

My Profile | CN Tech Store

</title>

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

min-height:100vh;

color:white;

}

.container{

max-width:1000px;

margin:auto;

padding:20px;

}

.profile-header{

background:

rgba(255,255,255,.08);

border:

1px solid rgba(255,0,0,.25);

backdrop-filter:

blur(20px);

border-radius:30px;

padding:30px;

display:flex;

align-items:center;

gap:25px;

margin-top:25px;

}

.avatar{

width:130px;

height:130px;

border-radius:50%;

object-fit:cover;

border:4px solid #ff0000;

box-shadow:

0 0 25px rgba(255,0,0,.6);

}

.user-box h1{

font-size:28px;

margin-bottom:8px;

}

.user-box p{

color:#ccc;

margin:5px 0;

}

.form-card{

background:

rgba(255,255,255,.07);

border:

1px solid rgba(255,0,0,.2);

border-radius:25px;

padding:25px;

margin-top:25px;

}

.form-group{

margin-bottom:15px;

}

.form-group label{

display:block;

margin-bottom:8px;

}

.form-control{

width:100%;

padding:14px;

border-radius:14px;

border:

1px solid rgba(255,255,255,.15);

background:

rgba(255,255,255,.08);

color:white;

outline:none;

}

.form-control:focus{

border-color:#ff0000;

box-shadow:

0 0 0 3px rgba(255,0,0,.25);

}

    
.oauth-grid{

display:grid;

grid-template-columns:
repeat(3,1fr);

gap:20px;

}



.oauth-card{

background:

rgba(255,255,255,.05);

border:

1px solid rgba(255,255,255,.08);

border-radius:20px;

padding:20px;

text-align:center;

}



.oauth-card i{

font-size:40px;

margin-bottom:10px;

}



.google{

color:#ea4335;

}



.facebook{

color:#1877f2;

}



.discord{

color:#5865f2;

}



.ref-box{

background:

rgba(255,0,0,.15);

border:

1px solid rgba(255,0,0,.3);

padding:18px;

border-radius:15px;

font-size:24px;

font-weight:700;

text-align:center;

letter-spacing:2px;

margin-bottom:15px;

}



.coin-box{

font-size:40px;

font-weight:700;

color:#ffd700;

text-align:center;

text-shadow:

0 0 20px gold;

}



@media(max-width:700px){

.oauth-grid{

grid-template-columns:1fr;

}

    }
    
    .save-btn{

width:100%;

padding:15px;

border:none;

border-radius:15px;

background:

linear-gradient(
135deg,
#ff0000,
#700000
);

color:white;

font-size:15px;

font-weight:600;

cursor:pointer;

transition:.3s;

}

.save-btn:hover{

transform:translateY(-2px);

box-shadow:

0 0 25px rgba(255,0,0,.5);

    }
    
    .stats-grid{

display:grid;

grid-template-columns:
repeat(3,1fr);

gap:20px;

}



.stats-item{

background:

rgba(255,255,255,.05);

padding:20px;

border-radius:20px;

text-align:center;

}



.stats-item span{

display:block;

color:#aaa;

margin-bottom:10px;

}



.stats-item h3{

font-size:18px;

}



.danger-card{

border:

1px solid rgba(255,50,50,.35);

}



.delete-btn{

display:block;

width:100%;

text-align:center;

padding:15px;

border-radius:15px;

text-decoration:none;

background:

linear-gradient(
135deg,
#ff0000,
#660000
);

color:white;

font-weight:600;

transition:.3s;

}



.delete-btn:hover{

transform:translateY(-2px);

box-shadow:

0 0 25px rgba(255,0,0,.5);

    }
    
    .mobile-nav{

display:none;

}



@media(max-width:700px){

.stats-grid{

grid-template-columns:1fr;

}



.mobile-nav{

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

1px solid rgba(255,0,0,.25);

display:flex;

justify-content:space-around;

align-items:center;

z-index:999;

}



.mobile-nav a{

color:white;

text-decoration:none;

font-size:12px;

text-align:center;

}



.mobile-nav i{

display:block;

font-size:22px;

margin-bottom:5px;

color:#ff0000;

}



.mobile-nav .active{

color:#ff4444;

}

    }
    
    .coin-box {
    display: flex;
    align-items: center;
    gap: 14px;

    background: linear-gradient(135deg, #151515, #300000);

    border: 1px solid #ff2222;

    border-radius: 16px;

    padding: 16px 18px;

    color: #ffd700;

    box-shadow:
        0 0 20px rgba(255, 0, 0, .2);
}

.coin-box > i {
    font-size: 30px;
    color: #ffd700;
}

.coin-info {
    flex: 1;
}

.coin-balance {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
}

.coin-info span {
    display: block;
    color: #aaa;
    font-size: 13px;
    margin-top: 3px;
}

.coin-topup-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 10px 15px;

    background: linear-gradient(
        135deg,
        #ff0000,
        #900000
    );

    color: #fff;

    text-decoration: none;

    border-radius: 10px;

    font-size: 14px;
    font-weight: bold;

    white-space: nowrap;
}

.coin-topup-btn:hover {
    background: linear-gradient(
        135deg,
        #ff3333,
        #b00000
    );
}

@media (max-width: 500px) {

    .coin-box {
        flex-wrap: wrap;
    }

    .coin-topup-btn {
        width: 100%;
        justify-content: center;
    }

    }
    
    </style>
    
    <body>


<div class="container">



<div class="profile-header">



<img

class="avatar"

src="uploads/avatar/<?=htmlspecialchars($user['avatar'] ?? 'default.png')?>"

onerror="this.src='uploads/avatar/default.png';"

>



<div class="user-box">


<h1>

<?=htmlspecialchars($user['fullname'])?>

</h1>



<p>

<i class="fa-solid fa-user"></i>

@<?=htmlspecialchars($user['username'])?>

</p>



<p>

<i class="fa-solid fa-envelope"></i>

<?=htmlspecialchars($user['email'])?>

</p>



<p>

<i class="fa-solid fa-shield"></i>

Role :

<?=strtoupper($user['role'])?>

</p>



<p>

<i class="fa-solid fa-right-to-bracket"></i>

Provider :

<?=htmlspecialchars($user['oauth_provider'] ?? 'Local')?>

</p>



</div>


</div>





<?php if(!empty($success)){ ?>

<div class="form-card">

<span style="color:#00ff88;">

<?=$success?>

</span>

</div>

<?php } ?>



<?php if(!empty($error)){ ?>

<div class="form-card">

<span style="color:#ff5555;">

<?=$error?>

</span>

</div>

<?php } ?>







<form

action="profile_update.php"

method="post"

enctype="multipart/form-data"

>


<div class="form-card">


<h2>

<i class="fa-solid fa-user-pen"></i>

Personal Information

</h2>


<br>





<div class="form-group">


<label>

Full Name

</label>


<input

type="text"

name="fullname"

class="form-control"

value="<?=htmlspecialchars($user['fullname'])?>"

required

>


</div>






<div class="form-group">


<label>

Email

</label>


<input

type="email"

name="email"

class="form-control"

value="<?=htmlspecialchars($user['email'])?>"

required

>


</div>






<div class="form-group">


<label>

Gender

</label>


<select

name="gender"

class="form-control"

>


<option value="Male"

<?=$user['gender']=="Male"?"selected":""?>

>

Male

</option>



<option value="Female"

<?=$user['gender']=="Female"?"selected":""?>

>

Female

</option>



<option value="Other"

<?=$user['gender']=="Other"?"selected":""?>

>

Other

</option>


</select>


</div>







<div class="form-group">


<label>

Birthday

</label>


<input

type="date"

name="birthday"

class="form-control"

value="<?=htmlspecialchars($user['birthday'])?>"

>


</div>







<div class="form-group">


<label>

Profile Image

</label>


<input

type="file"

name="avatar"

class="form-control"

accept="image/*"

>


</div>






<button

type="submit"

name="save_profile"

class="save-btn"

>

<i class="fa-solid fa-floppy-disk"></i>

Save Profile

</button>



    </div>
    
    


<!-- =========================
SECURITY SETTINGS
========================= -->


<div class="form-card">


<h2>

<i class="fa-solid fa-lock"></i>

Security Settings

</h2>

<br>



<div class="form-group">


<label>

Current Password

</label>


<input

type="password"

name="current_password"

class="form-control"

placeholder="Current Password"

>


</div>





<div class="form-group">


<label>

New Password

</label>


<input

type="password"

name="new_password"

class="form-control"

placeholder="New Password"

>


</div>






<div class="form-group">


<label>

Confirm New Password

</label>


<input

type="password"

name="confirm_password"

class="form-control"

placeholder="Confirm Password"

>


</div>





<button

type="submit"

name="change_password"

class="save-btn"

>

<i class="fa-solid fa-key"></i>

Change Password

</button>


</div>







<!-- =========================
CONNECTED ACCOUNTS
========================= -->


<div class="form-card">


<h2>

<i class="fa-solid fa-link"></i>

Connected Accounts

</h2>

<br>



<div class="oauth-grid">



<div class="oauth-card">


<i class="fa-brands fa-google google"></i>


<h3>

Google

</h3>


<p>

<?=($user['oauth_provider'] ?? '')=="google"
?
"Connected"
:
"Not Connected"?>

</p>


</div>





<div class="oauth-card">


<i class="fa-brands fa-facebook facebook"></i>


<h3>

Facebook

</h3>


<p>

<?=($user['oauth_provider'] ?? '')=="facebook"
?
"Connected"
:
"Not Connected"?>

</p>


</div>





<div class="oauth-card">


<i class="fa-brands fa-discord discord"></i>


<h3>

Discord

</h3>


<p>

<?=($user['oauth_provider'] ?? '')=="discord"
?
"Connected"
:
"Not Connected"?>

</p>


</div>



</div>


</div>








<!-- =========================
REFERRAL
========================= -->


<div class="form-card">


<h2>

<i class="fa-solid fa-share-nodes"></i>

Referral Program

</h2>

<br>



<div class="ref-box">


<?=$user['referral_code'] ?? '-'?>


</div>



<button

type="button"

onclick="copyReferral()"

class="save-btn"

>

<i class="fa-solid fa-copy"></i>

Copy Referral Code

</button>



</div>








<!-- =========================
CN COINS
========================= -->


<div class="form-card">


<h2>

<i class="fa-solid fa-coins"></i>

CN Coins

</h2>

<br>



<div class="coin-box">

    <i class="fa-solid fa-coins"></i>

    <div class="coin-info">

        <div class="coin-balance">
            <?=number_format((float)$cn_coins)?>
        </div>

        <span>CN Coins</span>

    </div>

    <a href="coins.php" class="coin-topup-btn">
        <i class="fa-solid fa-plus"></i>
        เติม CN Coins
    </a>

    </div>



<p style="margin-top:15px;color:#ccc;">


Use CN Coins for promotions,
discounts and future rewards.


</p>



    </div>
    
    <!-- =========================
ACCOUNT STATISTICS
========================= -->

<div class="form-card">


<h2>

<i class="fa-solid fa-chart-line"></i>

Account Statistics

</h2>

<br>


<div class="stats-grid">


<div class="stats-item">

<span>

Member Since

</span>

<h3>

<?=date(
"d M Y",
strtotime($user['created_at'])
)?>

</h3>

</div>



<div class="stats-item">

<span>

Account Status

</span>

<h3 style="color:#00ff88;">

<?=htmlspecialchars(
$user['status'] ?? 'Active'
)?>

</h3>

</div>



<div class="stats-item">

<span>

Login Method

</span>

<h3>

<?=ucfirst(
$user['oauth_provider'] ?? 'Local'
)?>

</h3>

</div>



</div>


</div>







<!-- =========================
DANGER ZONE
========================= -->

<div class="form-card danger-card">


<h2>

<i class="fa-solid fa-triangle-exclamation"></i>

Danger Zone

</h2>

<br>


<p>

Deleting your account is permanent
and cannot be undone.

</p>

<br>


<a

href="delete_account.php"

class="delete-btn"

onclick="return confirmDelete();"

>

<i class="fa-solid fa-trash"></i>

Delete Account

</a>


</div>





</form>

        </div>
        
        <!-- =========================
MOBILE NAVIGATION
========================= -->

<div class="mobile-nav">


<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

<span>Home</span>

</a>



<a href="orders.php">

<i class="fa-solid fa-box"></i>

<span>Orders</span>

</a>



<a href="profile.php"
class="active">

<i class="fa-solid fa-user"></i>

<span>Profile</span>

</a>



<a href="logout.php">

<i class="fa-solid fa-right-from-bracket"></i>

<span>Logout</span>

</a>


        </div>
        
        
        <script>

function copyReferral(){

navigator.clipboard.writeText(

"<?=$user['referral_code'] ?? ''?>"

);

alert(
"Referral Code Copied"
);

}



function confirmDelete(){

return confirm(

"Are you sure you want to delete your account permanently?"

);

}

        </script>
        
        </body>
    </html>