<?php  
require "config.php";  
require "database.php";  
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

<title>Privacy Policy - CN Tech Store</title>

<?php include "cdn.php"; ?>
<link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="page.css?v=<?= time() ?>">
    
     <!-- THEME INIT -->  <script>  
(function () {  
    const theme = localStorage.getItem("theme") || "light";  
    document.documentElement.classList.toggle("dark", theme === "dark");  
})();  
</script>  
    
    <style>
.container{
    max-width:900px;
    margin:auto;
    padding:20px;
}

.hero{
    text-align:center;
    padding:30px 0;
}

.hero h1{
    font-size:32px;
    color:#22c55e;
}

.box{
    background:var(--code);
    padding:20px;
    margin:15px 0;
    border-radius:12px;
    color:var(--text,#fff);
}

input, textarea{
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    background:#22c55e;
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    cursor:pointer;
}

#errorBox{
    margin-top:10px;
    font-weight:bold;
}
    </style>
</head>

<body>
<?php include "navbar.php"; ?>
<div class="container">

    <!-- HERO -->
    <div class="hero">
        <h1>Contact Us</h1>
        <p>CN Tech Store Support Team</p>
    </div>

    <!-- CONTACT INFO -->
    <div class="box">
        <h3>📍 Company Information</h3>
        <p>
            CN Tech Store<br>
            Digital Payment & Game Top-up Service<br>
            GPS: 19.449422, 103.233565
        </p>
    </div>

    <!-- FORM -->
    <div class="box">

        <h3> Send Message</h3>

        <form id="contactForm" method="POST" action="contact-save.php">

            <input name="name" placeholder="Your Name" class="name">
            <input name="email" placeholder="Your Email">
            <input name="subject" placeholder="Subject">
            <textarea name="message" placeholder="Message" rows="5"></textarea>

            <button type="submit">Send Message</button>

            <div id="errorBox"></div>

        </form>

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
        { el: this.name, label: "Name" },
        { el: this.email, label: "Email" },
        { el: this.subject, label: "Subject" },
        { el: this.message, label: "Message" }
    ];

    fields.forEach(f => {

        const value = f.el.value.trim();

        if(value === ""){
            f.el.style.border = "2px solid red";
            valid = false;
            if(!error) error = "Please fill " + f.label;
        } else {
            f.el.style.border = "2px solid #22c55e";
        }

    });

    if(!valid){
        e.preventDefault();
        document.getElementById("errorBox").innerHTML =
            "<p style='color:red'>" + error + "</p>";
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