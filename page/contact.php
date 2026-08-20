<?php

require "../config.php";
require "../database.php";

session_start();

?>

<!DOCTYPE html>
<html lang="lo">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<meta name="theme-color"
content="#ff0000">


<title>
ຕິດຕໍ່ CN Tech Store
</title>


<link rel="icon"
href="../assets/favicon.png">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


<style>


*{

margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;

}



html{

background:#000;

}



body{

background:#000;

color:#fff;

min-height:100vh;

}



/* HEADER */


.header{

position:sticky;

top:0;

z-index:999;

padding:18px;

text-align:center;

background:

rgba(0,0,0,.75);

backdrop-filter:blur(15px);

border-bottom:

1px solid rgba(255,0,0,.25);

}



.logo{

font-size:26px;

font-weight:900;

color:#ff2020;

}



.logo span{

color:#fff;

}




/* CONTAINER */


.container{

width:100%;

max-width:700px;

margin:auto;

padding:15px;

}




/* HERO */


.hero{

text-align:center;

padding:35px 10px;

}



.hero h1{


font-size:32px;


font-weight:900;


background:

linear-gradient(
135deg,
#ff0000,
#ffffff
);


-webkit-background-clip:text;


color:transparent;


}



.hero p{


margin-top:10px;

color:#aaa;

font-size:15px;

}



.online{


display:inline-block;

margin-top:15px;

padding:8px 18px;


border-radius:30px;


background:

rgba(0,255,80,.15);


color:#4ade80;


border:

1px solid rgba(74,222,128,.3);


font-size:13px;

}



/* CARD */


.card{


background:

rgba(255,255,255,.07);


backdrop-filter:

blur(15px);



-webkit-backdrop-filter:

blur(15px);



border:

1px solid rgba(255,0,0,.25);



border-radius:22px;



padding:22px;



margin-bottom:18px;



box-shadow:

0 10px 30px rgba(255,0,0,.12);


}



.card h2{


font-size:20px;


margin-bottom:18px;


color:#ff3030;


    }
    
    /* COMPANY INFO */


.company-item{

display:flex;

align-items:center;

gap:15px;

padding:14px 0;

border-bottom:

1px solid rgba(255,255,255,.1);

}



.company-item:last-child{

border:none;

}



.company-item i{


width:42px;

height:42px;


display:flex;

align-items:center;

justify-content:center;



border-radius:50%;



background:

rgba(255,0,0,.15);



color:#ff3030;



font-size:18px;


}



.company-item span{

color:#ddd;

font-size:15px;

}



.email-box{


margin-top:15px;


padding:15px;



border-radius:15px;



background:

rgba(255,0,0,.12);



border:

1px solid rgba(255,0,0,.3);



color:#ff5050;



font-weight:bold;



text-align:center;



}





/* FORM */


.form-group{

margin-bottom:15px;

}



.form-group label{


display:block;


margin-bottom:8px;


font-size:14px;


color:#ccc;


}



.form-control{


width:100%;


padding:15px;



border-radius:14px;



border:

1px solid #333;



background:

rgba(0,0,0,.5);



color:#fff;



font-size:15px;



outline:none;



}



.form-control::placeholder{


color:#777;


}



.form-control:focus{


border-color:#ff2020;



box-shadow:

0 0 0 3px rgba(255,0,0,.15);



}




select.form-control{


appearance:none;


}




textarea{


height:140px;


resize:none;


}




.send-btn{


width:100%;



padding:16px;



border:none;



border-radius:15px;



background:

linear-gradient(

135deg,

#ff0000,

#990000

);



color:white;



font-size:17px;



font-weight:bold;



cursor:pointer;



transition:.3s;



}



.send-btn:hover{


transform:translateY(-3px);


box-shadow:

0 10px 25px rgba(255,0,0,.3);


}




#errorBox{


margin-top:15px;


}



/* STATUS */


.status-item{


display:flex;


justify-content:space-between;


align-items:center;



padding:12px 0;



border-bottom:

1px solid rgba(255,255,255,.1);


}



.status-item:last-child{

border:none;

}



.badge{


padding:6px 12px;



border-radius:20px;



font-size:12px;



font-weight:bold;


}



.online-badge{


background:

rgba(74,222,128,.15);



color:#4ade80;



border:

1px solid rgba(74,222,128,.3);


}



/* FOOTER */


.footer{


text-align:center;



padding:25px;



color:#777;



font-size:13px;



}


@media(max-width:600px){


.hero h1{

font-size:28px;

}



.card{

padding:18px;

}



    }
    
    </style>

</head>


<body>


<!-- HEADER -->

<div class="header">

<div class="logo">

CNTECH <span>STORE</span>

</div>

</div>



<div class="container">



<!-- HERO -->

<div class="hero">


<h1>

<i class="fa-solid fa-headset"></i>

ຕິດຕໍ່ CN Tech Store

</h1>


<p>

ຝ່າຍບໍລິການ ແລະ ຕິດຕໍ່ທຸລະກິດ

</p>



<div class="online">

<i class="fa-solid fa-circle"></i>

 Online Support

</div>



</div>





<!-- COMPANY INFORMATION -->


<div class="card">


<h2>

<i class="fa-solid fa-building"></i>

 ຂໍ້ມູນບໍລິສັດ

</h2>




<div class="company-item">


<i class="fa-solid fa-store"></i>


<span>

<b>

CN Tech Store

</b>

<br>

Digital Product & Game Top-up Platform


</span>


</div>





<div class="company-item">


<i class="fa-solid fa-globe"></i>


<span>

https://cntechstore.shop

</span>


</div>






<div class="company-item">


<i class="fa-solid fa-envelope"></i>


<span>

support@cntechstore.shop

</span>


</div>






<div class="company-item">


<i class="fa-solid fa-clock"></i>


<span>

ບໍລິການອອນລາຍ 09:00 - 22:00

</span>


</div>






<div class="email-box">


<i class="fa-solid fa-paper-plane"></i>


support@cntechstore.shop


</div>



</div>








<!-- CONTACT FORM -->


<div class="card">


<h2>


<i class="fa-solid fa-message"></i>

 ສົ່ງຂໍ້ຄວາມ


</h2>





<form

id="contactForm"

method="POST"

action="contact-save.php"

>




<div class="form-group">


<label>

ຊື່ - ນາມສະກຸນ

</label>


<input

class="form-control"

type="text"

name="name"

placeholder="ປ້ອນຊື່ຂອງທ່ານ"

required>


</div>





<div class="form-group">


<label>

Email

</label>


<input

class="form-control"

type="email"

name="email"

placeholder="example@gmail.com"

required>


</div>






<div class="form-group">


<label>

ຫົວຂໍ້

</label>



<select

class="form-control"

name="subject">


<option>

Customer Support

</option>


<option>

ບັນຫາການສັ່ງຊື້

</option>



<option>

ບັນຫາເຕີມເກມ

</option>



<option>

ສະໝັກ Reseller

</option>



<option>

ຕິດຕໍ່ທຸລະກິດ

</option>


</select>


</div>





<div class="form-group">


<label>

ລາຍລະອຽດ

</label>



<textarea

class="form-control"

name="message"

placeholder="ຂຽນຂໍ້ຄວາມ..."

required></textarea>



</div>




<button

class="send-btn"

type="submit">


<i class="fa-solid fa-paper-plane"></i>


 ສົ່ງຂໍ້ຄວາມ



</button>




<div id="errorBox"></div>




</form>



    </div>
    
    </div>


<footer class="footer">

<div class="logo">

CNTECH <span>STORE</span>

</div>

<p>
© <?=date("Y")?> CN Tech Store
<br>
Digital Product & Game Top-up Platform
</p>

</footer>



<script>


// FORM VALIDATION

document
.getElementById("contactForm")
.addEventListener(
"submit",
function(e){


let inputs =
this.querySelectorAll(
"input, textarea"
);


let error = false;



inputs.forEach(function(input){


if(input.value.trim()==""){


input.style.border =
"2px solid #ff2020";


error = true;


}else{


input.style.border =
"1px solid #333";


}



});



if(error){


e.preventDefault();



document
.getElementById("errorBox")
.innerHTML = `

<div class="error">

<i class="fa-solid fa-triangle-exclamation"></i>

ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບ

</div>

`;



}



});




// GLASS CARD ANIMATION


const cards =
document.querySelectorAll(".card");


const observer =
new IntersectionObserver(
(entries)=>{


entries.forEach(
(entry)=>{


if(entry.isIntersecting){


entry.target.classList.add(
"show"
);


}



});


},
{
threshold:.1
}
);



cards.forEach(card=>{

observer.observe(card);

});



</script>



</body>

</html>