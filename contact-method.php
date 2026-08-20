<?php
require "config.php";
require "database.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Contact Method - CN Tech Store</title>

<?php include "cdn.php"; ?>
<link rel="stylesheet" href="style.css?v=<?= time() ?>">
<link rel="stylesheet" href="page.css?v=<?= time() ?>">

<script>
(function () {
    const theme = localStorage.getItem("theme") || "light";
    document.documentElement.classList.toggle("dark", theme === "dark");
})();
</script>

<style>
.container { max-width: 1000px; margin:auto; padding:20px; }
.box { background:var(--code); padding:20px; margin:15px 0; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1);}
.grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
input, textarea { width:100%; padding:10px; margin:8px 0; }
button { padding:10px 15px; background:#2ecc71; border:0; color:var(--text); cursor:pointer; }
iframe { width:100%; height:300px; border:0; border-radius:10px; }
</style>

</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">

<!-- HEADER -->
<div class="box">
    <h2>📞 Contact & Company Information</h2>
    <p>CN Tech Store - Payment & Top-up Gateway Platform </p>
</div>

<!-- COMPANY INFO -->
<div class="box">
    <h3>🏢 Company Information</h3>
    <p><b>Company:</b> CN Tech Store</p>
    <p><b>Email:</b> support@cntechstore.shop</p>
    <p><b>Service:</b> Game Top-up / Payment Gateway / Digital Products</p>
</div>

<!-- GPS LOCATION -->
<div class="box">
    <h2>📍 Our Location</h2>

    <p>
        Latitude: 19.449422<br>
        Longitude: 103.233565
    </p>

    <a href="https://www.google.com/maps?q=19.449422,103.233565" target="_blank">
        🔗 Open in Google Maps
    </a>

    <br><br>

    <iframe
        width="100%"
        height="300"
        style="border:0;border-radius:10px"
        loading="lazy"
        allowfullscreen
        src="https://www.google.com/maps?q=19.449422,103.233565&output=embed">
    </iframe>
    </div>

<!-- PAYMENT SUPPORT -->
<div class="box">
    <h3>💳 Supported Payment Methods</h3>
    <ul>
        <li>BCEL QR Payment</li>
        <li>LDB QR Payment</li>
        <li>VISA / MasterCard</li>
        <li>Auto Top-up API System</li>
    </ul>
</div>

<!-- CONTACT FORM -->
<div class="box">
    <h3>✉️ Contact Form</h3>

    <form  id="contactForm" method="POST" action="contact-save.php">

        <input name="name" placeholder="Full Name" >
        <input name="email" placeholder="Email" >
        <input name="subject" placeholder="Subject">
        <textarea name="message" placeholder="Message" rows="5"></textarea>

        <button type="submit">Send Message</button>
        <div id="errorBox"></div>
    </form>
    
</div>

<!-- API STATUS (future proof) -->
<div class="box">
    <h3>⚙️ System Status</h3>
    <p>Payment API: <b>READY FOR INTEGRATION</b></p>
    <p>Top-up API: <b>READY FOR GARENA / CODA / MOONTON</b></p>
    <p>Webhook System: <b>ACTIVE ( )</b></p>
</div>

</div>
<?php include "support-widget.php"; ?>
<?php include "footer.php"; ?>
    <script src="app.js?v=<?= time() ?>"></script>
    
    <script>
document.getElementById("contactForm").addEventListener("submit", function(e){

    let valid = true;
    let error = "";

    const fields = [
        { el: this.name, label: "ชื่อ" },
        { el: this.email, label: "อีเมล" },
        { el: this.subject, label: "หัวข้อ" },
        { el: this.message, label: "ข้อความ" }
    ];

    // reset + check realtime style
    fields.forEach(f => {

        const value = f.el.value.trim();

        if(value === ""){
            f.el.style.border = "2px solid red";
            valid = false;
            if(!error) error = "กรุณากรอก " + f.label;
        } else {
            f.el.style.border = "2px solid #2ecc71"; // เขียว
        }
    });

    if(!valid){
        e.preventDefault();

        document.getElementById("errorBox").innerHTML =
            "<p style='color:red;font-weight:bold'>" + error + "</p>";
    } else {
        document.getElementById("errorBox").innerHTML =
            "<p style='color:green;font-weight:bold'>ส่งข้อมูลสำเร็จ</p>";
    }

});
        
        

const supportBtn =
    document.getElementById(
        "supportBtn"
    );

const supportMenu =
    document.getElementById(
        "supportMenu"
    );

supportBtn.addEventListener(
    "click",
    ()=>{

        supportMenu
            .classList
            .toggle(
                "active"
            );

    }
);

// ปิดเมื่อกดข้างนอก

document.addEventListener(
    "click",
    function(e){

        if(
            !e.target.closest(
                ".support-widget"
            )
        ){

            supportMenu
                .classList
                .remove(
                    "active"
                );
        }
    }
);

    
    </script>
    
</body>
</html>