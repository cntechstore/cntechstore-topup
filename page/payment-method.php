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
<meta name="description"
content="CN Tech Store ให้บริการเติมเกมออนไลน์ เติมเงินมือถือ ขายคอมพิวเตอร์ อุปกรณ์ไอที และ Game Cards">

<meta name="keywords"
content="CN Tech Store, Game Topup, เติมเกม, Mobile Legends, Free Fire, PUBG, Computer">

<link rel="canonical"
href="https://cntechstore.shop/">
<title> - CN Tech Store</title>

<link rel="stylesheet" href="../style.css?v=1.0.0">
    <link rel="stylesheet" href="../page.css?v=1.0.0">

<!-- THEME INIT -->
<script>
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

    <section class="hero">
        <h1>Payment Methods</h1>
        <p>Secure Payment Gateway for Digital Top-up Services</p>
    </section>

    <!-- TRUST INFO -->
    <div class="box">

        <h3> Secure Payment Processing</h3>
        <p>
            All transactions are encrypted and processed through secure banking and partner APIs.
            We do not store sensitive banking information.
        </p>

    </div>

    <!-- BCEL -->
    <div class="box">

        <h3> BCEL QR Payment</h3>
        <p>Official QR payment supported via BCEL banking system</p>

        <ul>
            <li>Instant QR generation</li>
            <li>Real-time payment confirmation</li>
            <li>Supported currency: LAK</li>
        </ul>

    </div>

    <!-- LDB -->
    <div class="box">

        <h3> LDB QR Payment</h3>
        <p>Lao Development Bank secure QR payment system</p>

        <ul>
            <li>Fast processing</li>
            <li>Secure banking integration</li>
            <li>Auto verification supported</li>
        </ul>

    </div>

    <!-- CARD -->
    <div class="box">

        <h3> VISA / MASTERCARD</h3>
        <p>International card payment gateway for global users</p>

        <ul>
            <li>Visa / MasterCard supported</li>
            <li>3D Secure protection</li>
            <li>Global currency support</li>
        </ul>

    </div>

    <!-- API SECTION (สำคัญมากสำหรับขอ API) -->
    <div class="box">

        <h3> API Integration</h3>
        <p>
            Our system supports partner API integration for:
        </p>

        <ul>
            <li>Game Top-up Providers</li>
            <li>Mobile Payment Gateways</li>
            <li>E-commerce Checkout Systems</li>
        </ul>

        <p>
            API documentation available upon request.
        </p>

    </div>

    </div>

<?php include "../footer.php"; ?>

<script src="../app.js?v=1.0"></script>

<!-- OPTIONAL: filter logic later -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".cat-btn");
    const products = document.querySelectorAll(".product-card");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {

            const cat = btn.dataset.cat;

            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            products.forEach(p => {

                const pcat = p.dataset.category;

                if (cat === "all" || pcat === cat) {
                    p.style.display = "block";
                    p.style.opacity = "1";
                    p.style.transform = "scale(1)";
                } else {
                    p.style.opacity = "0";
                    p.style.transform = "scale(0.9)";
                    setTimeout(() => {
                        p.style.display = "none";
                    }, 200);
                }

            });

        });
    });

});
    
    function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

    }
    
</script>

</body>
</html>