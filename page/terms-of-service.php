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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="theme-color"
content="#ff0000">
    
    <link rel="icon"
href="../uploads/favicon.png">
    
<title>
Terms of Service - CN Tech Store
</title>


<meta name="description"
content="CN Tech Store Terms of Service">


<style>

/* =========================
CNTECH MOBILE APP UI
STANDALONE PAGE
========================= */


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

color:white;

text-decoration:none;

}


.logo span{

color:#e50914;

}



.nav-right{

display:flex;

gap:15px;

align-items:center;

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


border-radius:18px;


padding:20px;


margin-bottom:15px;


border-left:

4px solid #e50914;


}



.card h2{


color:#ff3030;


font-size:18px;


}



.card p,
.card li{


color:#ddd;


line-height:1.7;


font-size:15px;


}



.card ul{


padding-left:20px;


}



/* FOOTER */


.footer{


margin-top:30px;


padding:25px 15px;


background:#080808;


border-top:

2px solid #e50914;


text-align:center;


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


<a class="logo" href="/">

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



<a class="cart"
href="/cart.php">

🛒
<?= $cart_count ?>

</a>



</div>



</header>





<main class="container">



<section class="hero">


<h1>
Terms of Service
</h1>


<p>
CN Tech Store Digital Platform
</p>


</section>





<div class="card">

<h2>
1. Agreement
</h2>


<p>

By using CN Tech Store services,
you agree to these terms and conditions.

</p>


</div>





<div class="card">

<h2>
2. Service Description
</h2>


<ul>

<li>Game Top-Up Services</li>

<li>Digital Products</li>

<li>Mobile Recharge</li>

<li>Computer & IT Products</li>

<li>Online Payment System</li>


</ul>


</div>





<div class="card">


<h2>
3. Payment Policy
</h2>


<ul>

<li>
BCEL QR / LDB QR / VISA supported
</li>


<li>
Payments are processed securely
</li>


<li>
Completed digital delivery cannot be reversed
</li>


</ul>


</div>





<div class="card">


<h2>
4. Delivery Policy
</h2>


<ul>

<li>
Automatic delivery 1-5 minutes
</li>


<li>
Manual verification up to 24 hours
</li>


<li>
API/Webhook transaction system
</li>


</ul>


</div>





<div class="card">


<h2>
5. User Responsibility
</h2>


<ul>

<li>
Provide correct UID and account information
</li>


<li>
Incorrect information is user's responsibility
</li>


<li>
Fraud may result in account restriction
</li>


</ul>


</div>





<div class="card">


<h2>
6. API Usage
</h2>


<ul>

<li>
API access for approved partners only
</li>


<li>
Unauthorized access prohibited
</li>


<li>
System abuse may terminate service
</li>


</ul>


</div>





<div class="card">


<h2>
7. Privacy
</h2>


<p>

CN Tech Store uses user information only
for service operation, payment and security.

</p>


</div>





<div class="card">


<h2>
8. Contact
</h2>


<p>

Email:
<br>

support@cntechstore.shop

</p>


</div>




</main>





<!-- FOOTER -->


<footer class="footer">


<strong>
CN TECH STORE
</strong>


<p>
Computer • Mobile • Game Top-Up
</p>


<p>

© <?= date("Y") ?>
CN Tech Store

</p>


<a href="/page/privacy-policy.php">

Privacy Policy

</a>


</footer>



</body>

</html>