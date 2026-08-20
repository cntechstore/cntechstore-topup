<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


session_start();


require_once "database.php";



$message = "";

$message_type = "";





/*
=========================
REGISTER PROCESS
=========================
*/


if(isset($_POST['register'])){



$username = trim($_POST['username'] ?? '');

$email = trim($_POST['email'] ?? '');

$fullname = trim($_POST['fullname'] ?? '');

$gender = $_POST['gender'] ?? '';

$birthday = $_POST['birthday'] ?? '';

$password = $_POST['password'] ?? '';





/*
=========================
VALIDATE PASSWORD
=========================
*/


if(strlen($password) < 8 || strlen($password) > 15){


$message =
"Password must be 8-15 characters.";


$message_type="error";



}else{





/*
=========================
CALCULATE AGE
=========================
*/


$age = 0;


if(!empty($birthday)){


$birth = new DateTime($birthday);


$today = new DateTime();


$age =
$today->diff($birth)->y;


}







/*
=========================
CHECK USER EXIST
=========================
*/


$stmt=$conn->prepare("

SELECT id

FROM users

WHERE username=?

OR email=?

LIMIT 1

");



$stmt->bind_param(
"ss",
$username,
$email
);



$stmt->execute();


$result=$stmt->get_result();






if($result->num_rows > 0){



$message =
"Username or Email already exists.";


$message_type="error";



}else{







/*
=========================
CREATE REFERRAL CODE
=========================
*/


$referral_code =

"CN".

strtoupper(

substr(

bin2hex(random_bytes(4)),

0,

8

)

);







/*
=========================
PASSWORD HASH
=========================
*/


$password_hash =

password_hash(

$password,

PASSWORD_DEFAULT

);








/*
=========================
CREATE USER
=========================
*/


$stmt=$conn->prepare("


INSERT INTO users

(

username,

email,

fullname,

gender,

birthday,

age,

password,

role,

provider,

referral_code,

cn_coins,

created_at


)

VALUES

(

?,?,?,?,?,?,?,

'user',

'local',

?,

0,

NOW()

)


");





$stmt->bind_param(

"ssssisss",

$username,

$email,

fullname,

$gender,

$birthday,

$age,

$password_hash,

$referral_code

);





if($stmt->execute()){



$message =

"Register success! Please login.";


$message_type="success";



}else{



$message =

"Database Error : ".$conn->error;


$message_type="error";



}



}



}



}



?>


<!DOCTYPE html>

<html lang="lo">

<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width,initial-scale=1.0">


<meta name="theme-color"
content="#ff0000">



<title>

Register | CN Tech Store

</title>



<link rel="icon"
href="assets/favicon.png">





<link rel="preconnect"
href="https://fonts.googleapis.com">


<link rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>



<link href="
https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">





<link rel="stylesheet"

href="
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">


</head>

<body>



<style>


*{

margin:0;

padding:0;

box-sizing:border-box;

font-family:'Poppins',sans-serif;

}






body{


min-height:100vh;


display:flex;


justify-content:center;


align-items:center;



background:



radial-gradient(

circle at top left,

#550000,

transparent 35%

),



radial-gradient(

circle at bottom right,

#990000,

transparent 35%

),



#000;



padding:20px;


overflow-x:hidden;


}






body::before{


content:"";


position:absolute;


width:400px;


height:400px;



background:#ff0000;



border-radius:50%;



filter:blur(150px);



top:-150px;



left:-150px;



opacity:.35;


}







body::after{


content:"";


position:absolute;


width:350px;


height:350px;



background:#700000;



border-radius:50%;



filter:blur(150px);



bottom:-150px;



right:-120px;



opacity:.35;


}










.register-card{


position:relative;


z-index:10;



width:100%;



max-width:450px;




background:


rgba(255,255,255,.07);





border:



1px solid rgba(255,0,0,.25);





backdrop-filter:



blur(20px);






border-radius:28px;






padding:35px;






box-shadow:



0 20px 60px rgba(255,0,0,.3);






color:white;


}










.logo{


width:90px;


height:90px;



margin:0 auto 20px;






display:flex;



align-items:center;



justify-content:center;






border-radius:50%;






background:



linear-gradient(

135deg,

#ff0000,

#660000

);






box-shadow:



0 0 35px rgba(255,0,0,.6);






font-size:38px;



color:white;


}









.register-card h1{


text-align:center;



font-size:28px;



font-weight:700;



margin-bottom:8px;


}






.register-card h1 span{


color:#ff2020;


}








.register-card p{


text-align:center;



font-size:14px;



color:#ccc;



margin-bottom:25px;


}









.form-group{


position:relative;



margin-bottom:15px;


}







.form-group i{


position:absolute;



left:15px;



top:50%;



transform:translateY(-50%);



color:#ff3030;



}







.form-control{


width:100%;



padding:14px 45px;



border-radius:14px;



border:



1px solid rgba(255,255,255,.15);





background:



rgba(255,255,255,.08);






color:white;





font-size:14px;



outline:none;


}





.form-control::placeholder{


color:#999;


}








.form-control:focus{


border-color:#ff2020;



box-shadow:



0 0 0 3px rgba(255,0,0,.2);


    }
    
    .form-control:focus{

border-color:#ff0000;

background:

rgba(255,0,0,.08);


color:#fff;


box-shadow:

0 0 0 3px rgba(255,0,0,.25),

0 0 20px rgba(255,0,0,.35),

0 0 40px rgba(255,0,0,.15);


transform:translateY(-2px);


transition:.3s ease;


}




.form-control{


transition:

all .3s ease;


}





.form-control:hover{


border-color:

rgba(255,0,0,.6);


background:

rgba(255,255,255,.12);


}







.form-control:focus + i{


color:#ff0000;


text-shadow:


0 0 10px #ff0000;


}








/* PASSWORD TOGGLE */


.password-toggle{


position:absolute;


right:15px;


top:50%;


transform:translateY(-50%);


color:#999;


cursor:pointer;


transition:.3s;


}



.password-toggle:hover{


color:#ff0000;


text-shadow:


0 0 10px #ff0000;


}








/* SELECT */


select.form-control{


cursor:pointer;


}



select.form-control option{


background:#111;


color:white;


}








/* BUTTON */


.register-btn{


width:100%;


padding:16px;


border:none;


border-radius:16px;


margin-top:10px;


background:


linear-gradient(

135deg,

#ff0000,

#990000

);



color:white;


font-size:17px;


font-weight:700;


cursor:pointer;


transition:.3s;


box-shadow:


0 10px 25px rgba(255,0,0,.25);


}




.register-btn:hover{


transform:translateY(-3px);


box-shadow:


0 15px 35px rgba(255,0,0,.5);


}





.register-btn:active{


transform:scale(.97);


}






/* SOCIAL LOGIN */


.divider{


display:flex;


align-items:center;


gap:10px;


margin:25px 0;


color:#888;


font-size:13px;


}



.divider::before,
.divider::after{


content:"";


height:1px;


background:#333;


flex:1;


}





.social-login{


display:grid;


grid-template-columns:

repeat(3,1fr);


gap:12px;


}





.social-btn{


height:50px;


border-radius:14px;


background:#111;


border:1px solid #333;


display:flex;


align-items:center;


justify-content:center;


font-size:22px;


color:white;


text-decoration:none;


transition:.3s;


}



.social-btn:hover{


background:#ff0000;


border-color:#ff0000;


transform:translateY(-3px);


box-shadow:


0 10px 20px rgba(255,0,0,.3);


}





/* LOGIN LINK */


.login-link{


text-align:center;


margin-top:25px;


font-size:14px;


color:#aaa;


}



.login-link a{


color:#ff3030;


font-weight:bold;


text-decoration:none;


}



.login-link a:hover{


text-shadow:


0 0 10px #ff0000;


}





@media(max-width:480px){


.register-card{


padding:25px;


}



.register-card h1{


font-size:24px;


}



.logo{


width:75px;


height:75px;


font-size:30px;


}


    }
    
    </style>


<div class="register-card">


<div class="logo">

<i class="fa-solid fa-user-plus"></i>

</div>



<h1>

CN TECH <span>STORE</span>

</h1>



<p>

ສ້າງບັນຊີໃໝ່ ເພື່ອໃຊ້ງານບໍລິການ CN Tech Store

</p>



<?php if($message!=""){ ?>

<div class="message 
<?= strpos($message,'success')!==false ? 'success':'error' ?>">

<?= htmlspecialchars($message) ?>

</div>

<?php } ?>





<form method="POST">



<div class="form-group">

<i class="fa-solid fa-user"></i>

<input

class="form-control"

type="text"

name="username"

placeholder="Username"

required>

</div>






<div class="form-group">

<i class="fa-solid fa-envelope"></i>

<input

class="form-control"

type="email"

name="email"

placeholder="Email Address"

required>

</div>






<div class="form-group">

<i class="fa-solid fa-id-card"></i>

<input

class="form-control"

type="text"

name="fullname"

placeholder="Full Name"

required>

</div>







<div class="form-group">

<i class="fa-solid fa-venus-mars"></i>


<select

class="form-control"

name="gender"

required>


<option value="">

Gender

</option>


<option value="Male">

Male

</option>


<option value="Female">

Female

</option>


<option value="Other">

Other

</option>


</select>


</div>







<div class="form-group">


<i class="fa-solid fa-calendar"></i>


<input

class="form-control"

type="date"

name="birthday"

required>


</div>







<div class="form-group">


<i class="fa-solid fa-lock"></i>


<input

class="form-control"

type="password"

id="password"

name="password"

placeholder="Password 8-15 characters"

maxlength="15"

required>



<span

class="password-toggle"

id="togglePassword">


<i class="fa-solid fa-eye"></i>


</span>


</div>








<button

class="register-btn"

name="register"

type="submit">


<i class="fa-solid fa-user-plus"></i>

Create Account


</button>



</form>








<div class="divider">

OR REGISTER WITH

</div>








<div class="social-login">


<a

href="oauth/google.php"

class="social-btn">


<i class="fa-brands fa-google"></i>


</a>





<a

href="oauth/facebook.php"

class="social-btn">


<i class="fa-brands fa-facebook"></i>


</a>






<a

href="oauth/discord.php"

class="social-btn">


<i class="fa-brands fa-discord"></i>


</a>



</div>







<div class="login-link">


ມີບັນຊີແລ້ວ?


<a href="login.php">

Login

</a>


</div>




</div>







<script>


const password =

document.getElementById("password");


const toggle =

document.getElementById("togglePassword");




toggle.onclick=function(){


if(password.type==="password"){


password.type="text";


toggle.innerHTML=

'<i class="fa-solid fa-eye-slash"></i>';



}else{


password.type="password";


toggle.innerHTML=

'<i class="fa-solid fa-eye"></i>';



}


};





</script>



</body>

    </html>
    