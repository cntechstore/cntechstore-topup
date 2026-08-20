<?php

require "../config.php";
require "../database.php";

session_start();


$cart_count = 0;

if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
    $cart_count = count($_SESSION['cart']);
}


?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<meta name="theme-color"
content="#ff0000">
    
<title>
Privacy Policy - CN Tech Store
</title>


<meta name="description"
content="CN Tech Store Privacy Policy explains how we collect, use and protect user information.">

<link rel="icon"
href="../uploads/favicon.png">
    
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

}



/* NAVBAR */


.navbar{


height:70px;


position:sticky;


top:0;


z-index:100;


display:flex;


align-items:center;


justify-content:space-between;


padding:0 16px;



background:

linear-gradient(
135deg,
#000000,
#450000
);



border-bottom:

2px solid #e50914;


}



.logo{


font-size:20px;


font-weight:bold;


text-decoration:none;


color:white;


}


.logo span{


color:#e50914;


}



.nav-right{


display:flex;


align-items:center;


gap:15px;


}



.nav-right a{


color:white;


text-decoration:none;


font-size:14px;


}



.cart{


background:#e50914;


padding:8px 12px;


border-radius:20px;


}



/* CONTENT */


.container{


max-width:650px;


margin:auto;


padding:15px;


}



.hero{


background:


linear-gradient(
135deg,
#e50914,
#700000
);



padding:25px;


border-radius:20px;


text-align:center;


margin-bottom:20px;


}



.hero h1{


margin:0;


font-size:28px;


}



.card{


background:#111;


padding:20px;


margin-bottom:15px;


border-radius:18px;


border-left:

4px solid #e50914;


}



.card h2{


font-size:18px;


color:#ff3030;


}



.card p,
.card li{


color:#ddd;


font-size:15px;


line-height:1.7;


}



.card ul{


padding-left:20px;


}



/* FOOTER */


.footer{


margin-top:30px;


padding:25px 15px;


text-align:center;


background:#080808;


border-top:

2px solid #e50914;


color:#aaa;


}



.footer strong{


color:white;


}



.footer a{


color:#ff3030;


text-decoration:none;


}



@media(max-width:600px){


.nav-right a{


display:none;


}


.logo{


font-size:18px;


}


}


</style>


</head>



<body>



<!-- NAVBAR -->


<header class="navbar">


<a href="/"
class="logo">

CNTECH
<span>
STORE
</span>


</a>



<div class="nav-right">


<a href="/">
Home
</a>


<a href="/products.php">
Products
</a>


<a href="/games.php">
Games
</a>


<a href="/cart.php"
class="cart">

🛒
<?= $cart_count ?>

</a>


</div>



</header>







<main class="container">





<section class="hero">


<h1>
Privacy Policy
</h1>


<p>
CN Tech Store User Data Protection
</p>


</section>






<div class="card">


<h2>
1. Introduction
</h2>


<p>

CN Tech Store respects user privacy and is committed
to protecting personal information when using our services.

</p>


</div>






<div class="card">


<h2>
2. Information We Collect
</h2>


<ul>

<li>
Account information
</li>


<li>
Contact information
</li>


<li>
Transaction information
</li>


<li>
Game UID and server information provided by users
</li>


<li>
Payment transaction details
</li>


</ul>


</div>






<div class="card">


<h2>
3. How We Use Information
</h2>


<ul>


<li>
Process game top-up orders
</li>


<li>
Verify payments
</li>


<li>
Provide customer support
</li>


<li>
Improve website services
</li>


<li>
Prevent fraud and unauthorized activity
</li>


</ul>


</div>






<div class="card">


<h2>
4. Payment Information
</h2>


<p>

Payment information is processed through secure payment
channels. CN Tech Store does not store complete banking
passwords or sensitive payment credentials.

</p>


</div>






<div class="card">


<h2>
5. TikTok Integration
</h2>


<p>

CNTECH REELS uses TikTok Developer products such as
TikTok Login Kit and Display API.

Users authorize access through TikTok.

We only access permitted information such as:

</p>


<ul>

<li>
TikTok profile information
</li>


<li>
Public video information
</li>


</ul>


<p>

CN Tech Store does not collect unauthorized TikTok data
or share user information with third parties.

</p>


</div>






<div class="card">


<h2>
6. Data Security
</h2>


<p>

We apply security measures to protect user information,
transactions and system access.

</p>


</div>






<div class="card">


<h2>
7. Cookies
</h2>


<p>

CN Tech Store may use cookies or local storage to improve
website functions such as login sessions, preferences
and shopping cart features.

</p>


</div>






<div class="card">


<h2>
8. Third Party Services
</h2>


<p>

Our platform may connect with external services including
payment providers, APIs and social platforms.

Each service follows its own privacy policy.

</p>


</div>






<div class="card">


<h2>
9. User Rights
</h2>


<ul>

<li>
Request information about stored data
</li>


<li>
Request account support
</li>


<li>
Request removal of personal information when applicable
</li>


</ul>


</div>






<div class="card">


<h2>
10. Contact
</h2>


<p>

For privacy questions:

<br><br>

Email:
<br>

support@cntechstore.shop

</p>


</div>





</main>







<footer class="footer">


<strong>
CN TECH STORE
</strong>


<p>
Computer • Mobile • Game Top-Up
</p>


<p>
© <?= date("Y") ?> CN Tech Store
</p>


<a href="/page/terms-of-service.php">
Terms of Service
</a>


</footer>





</body>

</html>