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
<meta name="impact-site-verification"
      content="a467f24b-d5e9-40cf-80dc-2cbf5def5a43">
<title>Return Policy - CN Tech Store</title>

<?php include "../cdn.php"; ?>
<link rel="stylesheet" href="../style.css?v=1.0.0">
    <link rel="stylesheet" href="../page.css?v=1.0">
    
     <!-- THEME INIT -->  <script>  
(function () {  
    const theme = localStorage.getItem("theme") || "light";  
    document.documentElement.classList.toggle("dark", theme === "dark");  
})();  
</script>  
    
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

    <!-- HERO -->
    <div class="hero">
        <h1>Return Policy</h1>
        <p>CN Tech Store - Digital Top-up & Payment System</p>
    </div>

    <!-- POLICY -->
    <div class="box">
        <h3> Overview</h3>
        <p>
            Due to the nature of digital products (game top-ups, credits, and services),
            all purchases are generally non-refundable once processed.
        </p>
    </div>

    <div class="box">
        <h3> Eligible Refund Cases</h3>
        <ul>
            <li>Payment deducted but top-up not delivered</li>
            <li>System error or duplicate transaction</li>
            <li>Incorrect product delivery caused by system</li>
        </ul>
    </div>

    <div class="box">
        <h3> Non-Refundable Cases</h3>
        <ul>
            <li>Wrong Player ID submitted by customer</li>
            <li>Successful delivery of in-game items</li>
            <li>Change of mind after payment</li>
        </ul>
    </div>

    <div class="box">
        <h3>⏱ Refund Processing Time</h3>
        <ul>
            <li>1 - 3 business days (if approved)</li>
            <li>Refund via original payment method (BCEL / LDB / Card)</li>
        </ul>
    </div>

    <div class="box">
        <h3> Contact Support</h3>
        <p>
            Email: support@cntechstore.shop <br>
            Response time: 24 - 48 hours
        </p>
    </div>

</div>
<?php include "../footer.php"; ?>
     <script src="../app.js?v=1.0"></script> 
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
</body>
</html>