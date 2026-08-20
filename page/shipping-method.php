<?php  
require "../config.php";  
require "../database.php";  
session_start();  
  
/*  
=========================  
PRODUCTS PAGE  
=========================  
- รองรับ DEV / LIVE  
- พร้อมต่อ AJAX filter ในอนาคต  
*/  
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Shipping & Delivery Methods</title>

<?php include "../cdn.php"; ?>
<link rel="stylesheet" href="../style.css?v=1.0.0">
    <link rel="stylesheet" href="../page.css?v=1.0.0">
    
     <!-- THEME INIT -->  <script>  
(function () {  
    const theme = localStorage.getItem("theme") || "light";  
    document.documentElement.classList.toggle("dark", theme === "dark");  
})();  
</script>  
    
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9543860279937476"
            crossorigin="anonymous"></script>
    
    <style>
    .logo-image{

    width:120px;
    height:68px;

        }
    
    </style>
</head>

<body>
<?php include "../navbar.php"; ?>
<div class="container">

    <section class="hero">
        <h1>Delivery Methods</h1>
        <p>Digital Product & Top-up Delivery System</p>
    </section>

    <!-- IMPORTANT NOTICE -->
    <div class="box">

        <h3> Digital Delivery Only</h3>

        <p>
            This platform provides <b>digital goods and game top-up services only</b>.
            No physical shipping is involved.
        </p>

    </div>

    <!-- DELIVERY SPEED -->
    <div class="box">

        <h3> Processing Time</h3>

        <ul>
            <li>Instant processing: 1–3 minutes</li>
            <li>Maximum delay: 10 minutes</li>
            <li>Rare manual review: up to 24 hours</li>
        </ul>

    </div>

    <!-- DELIVERY METHOD -->
    <div class="box">

        <h3> Delivery Method</h3>

        <ul>
            <li>Auto API Top-up System</li>
            <li>Game account credit injection</li>
            <li>Real-time verification via webhook</li>
            <li>Email confirmation after success</li>
        </ul>

    </div>

    <!-- SECURITY -->
    <div class="box">

        <h3> Security & Verification</h3>

        <p>
            All transactions are verified through secure payment gateway systems
            and API-based confirmation channels.
        </p>

        <ul>
            <li>Webhook confirmation supported</li>
            <li>Anti-fraud detection system</li>
            <li>Transaction logging enabled</li>
        </ul>

    </div>

</div>
<?php include "../footer.php"; ?>
     <script src="../app.js?v=1.0"></script> 
</body>
</html>