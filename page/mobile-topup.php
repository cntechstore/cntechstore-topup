<?php

require "../config.php";
require "../database.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}


/*
====================================
CNTECH STORE MOBILE TOPUP APP UI
PART 1/4

- Load Provider
- Amount System
- Glass Mobile UI
====================================
*/


/*
====================================
LOAD PROVIDERS
====================================
*/

$providers = [];


$sql = "

SELECT *

FROM mobile_providers

WHERE status='active'

ORDER BY id ASC

";


$result = $conn->query($sql);



if($result){

    while($row = $result->fetch_assoc()){

        $providers[] = $row;

    }

}



/*
====================================
AMOUNT PACKAGE
====================================
*/

$amount = [

    5000,

    10000,

    20000,

    30000,

    50000,

    100000

];



/*
====================================
PAGE
====================================
*/

$pageTitle =
"Mobile Top-up";

?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width,initial-scale=1.0">



<title>

<?= $pageTitle ?>

- CNTECH STORE

</title>




<!-- STYLE -->

<link
rel="stylesheet"
href="../style.css?v=<?=time()?>"
>


<link
rel="stylesheet"
href="../page.css?v=<?=time()?>"
>



<!-- FONT AWESOME -->

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>





<script>

(function(){


const theme =
localStorage.getItem("theme")
||
"dark";


document.documentElement
.classList
.toggle(
"dark",
theme==="dark"
);



})();

</script>




</head>



<body>



<div class="mobile-app-container">





<!-- =========================
HEADER
========================= -->


<section class="topup-header">



<div class="logo-glow">

<i class="fa-solid fa-mobile-screen-button"></i>

</div>



<h1>

Mobile Top-up

</h1>



<p>

เติมเงินมือถือผ่าน CNTECH STORE

</p>



</section>







<!-- =========================
NETWORK PROVIDER
========================= -->


<div class="glass-card">



<h2>

<i class="fa-solid fa-signal"></i>

เลือกเครือข่าย

</h2>




<div class="provider-grid">



<?php foreach($providers as $p){ ?>



<?php


$logo =
trim(
$p['logo'] ?? ''
);



if(empty($logo)){


$logo =
"/admin/uploads/providers/no-image.png";


}
else{


if(
strpos($logo,"http") !== 0
){

$logo =
"/admin/"
.
ltrim(
$logo,
"/"
);


}


}



?>





<div
class="provider-card"
data-code="<?= htmlspecialchars($p['code']) ?>"
onclick="selectProvider(
'<?= htmlspecialchars($p['code']) ?>',
'<?= htmlspecialchars($p['name']) ?>',
this
)">


<img

src="<?=htmlspecialchars($logo)?>"

alt="<?=htmlspecialchars($p['name'])?>"

onerror="
this.src='/admin/uploads/providers/no-image.png'
"

>




<h3>

<?=htmlspecialchars($p['name'])?>

</h3>




<p>

Mobile Network

</p>



</div>





<?php } ?>



</div>





<input

type="hidden"

id="provider"

name="provider"

>



</div>







<!-- =========================
AMOUNT SELECT
========================= -->


<div class="glass-card">



<h2>

<i class="fa-solid fa-coins"></i>

เลือกจำนวนเงิน

</h2>





<div class="amount-grid">



<?php foreach($amount as $money){ ?>



<button

type="button"

class="amount-btn"

onclick="
selectAmount(
<?=$money?>,
this
)
"

>


<?=number_format($money)?>

₭



</button>



<?php } ?>



</div>





<div class="custom-amount">



<label>

หรือใส่จำนวนเอง

</label>




<input

type="number"

id="customAmount"

name="amount"

min="5000"

placeholder="ขั้นต่ำ 5,000 LAK"



>




<small>

สามารถเติมมากกว่า 100,000 LAK ได้

</small>




</div>





    </div>
    
    <!-- =========================
PHONE INFORMATION
========================= -->


<div class="glass-card">



<h2>

<i class="fa-solid fa-phone"></i>

ข้อมูลลูกค้า

</h2>





<form

id="topupForm"

method="POST"

action="../api/mobile_topup_process.php"

>




<input

type="hidden"

name="provider"

id="providerInput"

>


<input
type="hidden"
name="amount"
id="amount"

>



<div class="input-box">



<label>

เบอร์โทรศัพท์

</label>




<input

type="tel"

id="phone"

name="phone"

placeholder="020xxxxxxxxx"

maxlength="13"

inputmode="numeric"

autocomplete="tel"

required

>




<div

id="phoneStatus"

class="phone-status"

></div>



</div>







<!-- =========================
PRODUCT DETAIL
========================= -->


<div class="product-detail">



<h3>

<i class="fa-solid fa-receipt"></i>

รายละเอียดสินค้า

</h3>





<div class="detail-row">


<span>

เครือข่าย

</span>


<strong id="detailNetwork">

-

</strong>


</div>





<div class="detail-row">


<span>

บริการ

</span>


<strong>

Mobile Top-up

</strong>


</div>





<div class="detail-row">


<span>

จำนวน

</span>


<strong>

<span id="detailAmount">

0

</span>

LAK

</strong>


</div>





<div class="detail-row">


<span>

หมายเลข

</span>


<strong id="detailPhone">

-

</strong>


</div>






<div class="ready-status">


<i class="fa-solid fa-circle-check"></i>


พร้อมชำระเงิน



</div>




</div>








<button

type="submit"

class="continue-btn"

>


<i class="fa-solid fa-arrow-right"></i>


ดำเนินการต่อ



</button>





</form>



</div>






<!-- =========================
SERVICE INFORMATION
========================= -->


<div class="glass-card info-card">



<h2>

<i class="fa-solid fa-shield-halved"></i>

บริการของเรา

</h2>




<ul>


<li>

<i class="fa-solid fa-clock"></i>

บริการ 24/7


</li>



<li>

<i class="fa-solid fa-qrcode"></i>

BCEL QR / LDB QR


</li>




<li>

<i class="fa-solid fa-bolt"></i>

เติมเงินอัตโนมัติหลังชำระ


</li>




<li>

<i class="fa-solid fa-lock"></i>

ระบบตรวจสอบความถูกต้อง


</li>



</ul>




</div>






    </div>
    
    
    <style>
        
        /*
====================================
CNTECH STORE MOBILE TOPUP
GLASS APP CSS
PART 3/4
====================================
*/


*{
    box-sizing:border-box;
}



body{

    margin:0;

    padding-bottom:90px;

    font-family:
        Inter,
        Arial,
        sans-serif;

    color:white;


    background:

    radial-gradient(
        circle at top,
        #550018,
        #080808 55%
    );

}



.mobile-app-container{

    width:100%;

    max-width:650px;

    margin:auto;

    padding:15px;

}



/*
========================
HEADER
========================
*/


.topup-header{


    text-align:center;


    padding:40px 20px;


    border-radius:30px;


    background:


    linear-gradient(
        135deg,
        rgba(255,0,50,.95),
        rgba(80,0,20,.85)
    );



    box-shadow:

    0 0 40px
    rgba(255,0,50,.6);



    margin-bottom:20px;


}




.logo-glow{


    width:75px;

    height:75px;


    margin:auto;


    display:flex;


    align-items:center;


    justify-content:center;


    border-radius:50%;



    background:

    rgba(255,255,255,.15);



    font-size:35px;



    box-shadow:


    0 0 30px
    #ff0033;


}





.topup-header h1{


    margin:15px 0 5px;


    font-size:34px;


}



.topup-header p{


    opacity:.85;

}




/*
========================
GLASS CARD
========================
*/


.glass-card{


    margin-top:20px;


    padding:20px;


    border-radius:25px;



    background:


    rgba(255,255,255,.08);



    border:


    1px solid
    rgba(255,255,255,.18);



    backdrop-filter:

    blur(20px);



    -webkit-backdrop-filter:

    blur(20px);



    box-shadow:


    0 20px 50px
    rgba(0,0,0,.5);


}




.glass-card h2{


    margin-top:0;


    font-size:20px;


}





/*
========================
PROVIDER
========================
*/


.provider-grid{


    display:grid;


    grid-template-columns:

    repeat(2,1fr);


    gap:15px;


}



.provider-card{


    padding:15px;


    text-align:center;


    border-radius:20px;



    cursor:pointer;



    background:


    rgba(255,255,255,.07);



    border:


    1px solid
    rgba(255,255,255,.15);



    transition:.3s;


}





.provider-card img{


    width:65px;


    height:65px;


    object-fit:contain;


}



.provider-card h3{


    margin:10px 0 5px;


    font-size:16px;


}



.provider-card p{


    margin:0;


    opacity:.6;


    font-size:12px;


}





.provider-card:hover,


.provider-card.active{


    transform:

    translateY(-5px);



    background:


    rgba(255,0,51,.25);



    border-color:

    #ff0033;



    box-shadow:


    0 0 30px
    rgba(255,0,51,.7);


}





/*
========================
AMOUNT
========================
*/


.amount-grid{


    display:grid;


    grid-template-columns:

    repeat(3,1fr);


    gap:12px;


}





.amount-btn{


    padding:15px 5px;


    border-radius:15px;



    border:


    1px solid
    #ff0033;



    background:


    rgba(255,255,255,.08);



    color:white;



    font-weight:bold;


    cursor:pointer;



    transition:.3s;


}





.amount-btn.active,


.amount-btn:hover{


    background:

    #ff0033;



    box-shadow:


    0 0 20px
    #ff0033;


}






.custom-amount input,


.input-box input{


    width:100%;


    padding:16px;


    margin-top:10px;


    border-radius:15px;



    background:


    rgba(0,0,0,.45);



    color:white;



    border:


    1px solid
    rgba(255,255,255,.3);



    font-size:16px;


}



.custom-amount small{


    display:block;


    margin-top:10px;


    opacity:.7;


}




/*
========================
PRODUCT DETAIL
========================
*/


.product-detail{


    margin-top:20px;


    padding:20px;



    border-radius:20px;



    background:


    rgba(0,0,0,.35);



    border:


    1px solid
    rgba(255,255,255,.15);



}



.product-detail h3{


    margin-top:0;


    color:#ff0033;


}




.detail-row{


    display:flex;


    justify-content:space-between;


    padding:10px 0;


    border-bottom:


    1px solid
    rgba(255,255,255,.1);


}



.detail-row strong{


    color:white;


}



.ready-status{


    margin-top:15px;


    color:#4ade80;


    font-weight:bold;


}





.phone-status{


    margin-top:10px;


    font-size:14px;


}





/*
========================
BUTTON
========================
*/


.continue-btn{


    width:100%;


    margin-top:25px;


    padding:18px;



    border:none;


    border-radius:18px;



    color:white;



    font-size:18px;


    font-weight:bold;



    cursor:pointer;




    background:


    linear-gradient(

        135deg,

        #ff0033,

        #990020

    );




    box-shadow:


    0 0 35px
    rgba(255,0,51,.7);


}






/*
========================
INFO
========================
*/


.info-card ul{


    padding-left:0;


    list-style:none;


}



.info-card li{


    padding:12px 0;


    border-bottom:


    1px solid
    rgba(255,255,255,.1);


}




/*
========================
FOOTER DESKTOP
========================
*/


.site-footer{


    margin-top:40px;


    padding:40px 20px;



    background:


    linear-gradient(

        180deg,

        #080808,

        #260008

    );



    color:white;


}



.footer-container{


    max-width:1200px;


    margin:auto;



    display:grid;


    grid-template-columns:

    repeat(4,1fr);



    gap:30px;


}




.footer-brand img{


    width:70px;


}



.footer-brand h3{


    color:#ff0033;


}



.footer-menu a{


    display:block;


    color:#ddd;


    text-decoration:none;


    padding:8px 0;


}



.footer-social a{


    font-size:25px;


    color:white;


    margin-right:15px;


}




.footer-bottom{


    text-align:center;


    margin-top:30px;


    padding-top:20px;


    border-top:


    1px solid
    rgba(255,255,255,.2);


        }
        
        .mobile-navbar{
display:none;
}


@media(max-width:768px){

.site-footer{
display:none;
}


.mobile-navbar{

display:flex;

position:fixed;

bottom:0;

left:0;

right:0;

height:75px;

background:

rgba(10,10,10,.85);


backdrop-filter:
blur(20px);


border-top:

1px solid
rgba(255,0,51,.4);


z-index:9999;


}


.nav-item{

flex:1;

display:flex;

flex-direction:column;

align-items:center;

justify-content:center;

color:#aaa;

font-size:12px;

text-decoration:none;

gap:5px;

}



.nav-item i{

font-size:22px;

}



.nav-item.active{

color:#ff0033;


text-shadow:

0 0 15px #ff0033;

}

}


@media(min-width:769px){

.mobile-navbar{

display:none;

}

        }
        
    </style>
    
    <!-- =========================
CNTECH STORE FOOTER
========================= -->

<footer class="site-footer">


<div class="footer-container">


<div class="footer-brand">


<img 
src="/logo.png"
alt="CNTECH STORE">


<h3>
CNTECH STORE
</h3>


<p>
Computer • Mobile • Parts & Accessories
</p>


<p>
Gaming Store | Top Up | Digital Platform
</p>


</div>




<div class="footer-menu">

<h4>
Services
</h4>


<a href="/games/">
<i class="fa-solid fa-gamepad"></i>
Game Top Up
</a>


<a href="/voucher/">
<i class="fa-solid fa-ticket"></i>
Voucher
</a>


<a href="/page/blogs-method.php">
<i class="fa-solid fa-newspaper"></i>
News
</a>


<a href="/cart.php">
<i class="fa-solid fa-cart-shopping"></i>
Cart
</a>


</div>





<div class="footer-menu">

<h4>
Support
</h4>


<a href="/page/contact.php">

<i class="fa-solid fa-headset"></i>

Contact

</a>



<a href="/page/privacy-policy.php">

<i class="fa-solid fa-shield"></i>

Privacy

</a>



<a href="/page/terms-of-service.php">

<i class="fa-solid fa-file-contract"></i>

Terms

</a>



</div>





<div class="footer-social">


<h4>
Follow
</h4>


<a href="#">
<i class="fa-brands fa-facebook"></i>
</a>


<a href="#">
<i class="fa-brands fa-tiktok"></i>
</a>


<a href="#">
<i class="fa-brands fa-youtube"></i>
</a>



</div>



</div>




<div class="footer-bottom">


© <?=date("Y")?> CNTECH STORE

<br>

All Rights Reserved.


</div>


</footer>







<!-- =========================
MOBILE APP NAVBAR
========================= -->


<div class="mobile-navbar">



<a href="/"
class="nav-item">


<i class="fa-solid fa-house"></i>

<span>
Home
</span>


</a>





<a href="/games/"
class="nav-item">


<i class="fa-solid fa-gamepad"></i>

<span>
Games
</span>


</a>





<a href="/mobile/mobile_topup.php"
class="nav-item active">


<i class="fa-solid fa-mobile-screen-button"></i>

<span>
Top Up
</span>


</a>





<a href="/cart.php"
class="nav-item">


<i class="fa-solid fa-cart-shopping"></i>

<span>
Cart
</span>


</a>





<a href="/account.php"
class="nav-item">


<i class="fa-solid fa-user"></i>

<span>
Account
</span>


</a>




</div>







<script>

/*
====================================
CNTECH STORE MOBILE TOPUP JS
PART 4/4
====================================
*/


const providerInput =
document.getElementById(
"providerInput"
);


const providerBox =
document.getElementById(
"provider"
);


const amountInput =
document.getElementById(
"amount"
);

const customAmount =
document.getElementById("customAmount");
    
    const amountHidden =
document.getElementById("amount");
    
    
const phoneInput =
document.getElementById(
"phone"
);


const phoneStatus =
document.getElementById(
"phoneStatus"
);


const detailNetwork =
document.getElementById(
"detailNetwork"
);


const detailAmount =
document.getElementById(
"detailAmount"
);


const detailPhone =
document.getElementById(
"detailPhone"
);





/*
====================================
LAOS NETWORK
====================================
*/







/*
====================================
SELECT PROVIDER
====================================
*/


function selectProvider(
code,
name,
element
){


document
.querySelectorAll(
".provider-card"
)
.forEach(card=>{

card.classList.remove(
"active"
);

});



element.classList.add(
"active"
);



providerInput.value =
code;


providerBox.value =
code;


detailNetwork.innerHTML =
name;



validatePhone();



}







/*
====================================
SELECT AMOUNT
====================================
*/


function selectAmount(
money,
button
){


document
.querySelectorAll(
".amount-btn"
)
.forEach(btn=>{

btn.classList.remove(
"active"
);

});



button.classList.add(
"active"
);



amountHidden.value =
money;

customAmount.value = money;

updateAmount();


}






/*
====================================
AMOUNT UPDATE
====================================
*/


amountHidden.addEventListener(
"input",
updateAmount
);



function updateAmount(){


let value =
Number(
amountHidden.value
)
||0;



detailAmount.innerHTML =

value.toLocaleString();


}




customAmount.addEventListener(
"input",
function(){

let value =
parseInt(this.value) || 0;

amountHidden.value = value;

detailAmount.innerText =
value.toLocaleString("en-US");

}
);


/*
====================================
CLEAN PHONE
====================================
*/


function cleanPhone(value){


let phone =
value
.replace(/\D/g,'');



if(
phone.startsWith("856")
){

phone =
"0"+
phone.substring(4);

}



return phone;


}







/*
====================================
PHONE VALIDATE
====================================
*/


function validatePhone(){

    let phone =
        cleanPhone(
            phoneInput.value
        );

    phoneInput.value =
        phone.substring(0,11);

    document.getElementById(
        "detailPhone"
    ).innerText =
        phone || "-";

    if(phone.length === 0){

        phoneStatus.innerHTML = "";

        document.getElementById(
            "detailNetwork"
        ).innerText = "-";

        return false;
    }

    if(phone.length !== 11){

        phoneStatus.innerHTML =
            "❌ เบอร์ต้องมี 11 หลัก";

        phoneStatus.style.color =
            "#ff4444";

        return false;
    }

    // ใช้ 4 หลักแรก
    let prefix =
        phone.substring(0,4);

    const networks = {

        "0202":"ETL",
        "0209":"Unitel",
        "0205":"Lao Telecom",
        "0207":"TPlus",
        "0302":"ETL",
        "0309":"Unitel",
        "0305":"Lao Telecom",
        "0307":"TPlus"

    };

    let networkName =
        networks[prefix];

    if(!networkName){

        phoneStatus.innerHTML =
            "❌ รองรับเฉพาะเครือข่ายในประเทศลาว";

        phoneStatus.style.color =
            "#ff4444";

        return false;
    }

    phoneStatus.innerHTML =
        "✔ " +
        networkName +
        " เบอร์ถูกต้อง";

    phoneStatus.style.color =
        "#4ade80";

    document.getElementById(
        "detailNetwork"
    ).innerText =
        networkName;

    // map ชื่อเครือข่าย -> provider code
    const networkProvider = {

        "Unitel":"UNITEL",
        "TPlus":"TPLUS",
        "Lao Telecom":"LAOTEL",
        "ETL":"ETL"

    };

    if(networkProvider[networkName]){

        providerInput.value =
            networkProvider[networkName];

        // ไฮไลท์การ์ดอัตโนมัติ
        document
        .querySelectorAll(
            ".provider-card"
        )
        .forEach(card=>{

            card.classList.remove(
                "active"
            );

            if(
                card.dataset.code ===
                networkProvider[networkName]
            ){
                card.classList.add(
                    "active"
                );
            }

        });
    }

    return true;
}







phoneInput.addEventListener(
"input",
validatePhone
);








/*
====================================
FORM CHECK
====================================
*/


document
.getElementById(
"topupForm"
)
.addEventListener(
"submit",
function(e){



let amount =
Number(
amountInput.value
);



if(!providerInput.value){


e.preventDefault();


alert(
"กรุณาเลือกเครือข่าย"
);


return;


}





if(amount < 5000){


e.preventDefault();


alert(
"ขั้นต่ำ 5,000 LAK"
);


return;


}





if(!validatePhone()){


e.preventDefault();


alert(
"กรุณาตรวจสอบเบอร์โทร"
);


return;


}



});





updateAmount();



</script>


</body>

</html>