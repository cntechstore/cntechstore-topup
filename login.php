<?php

session_start();

require_once "database.php";


/*
========================
AUTO LOGIN
========================
*/

if(
!isset($_SESSION['user_id'])
&& isset($_COOKIE['remember_token'])
){


$token = $_COOKIE['remember_token'];


$stmt = $conn->prepare("

SELECT *

FROM users

WHERE remember_token=?

LIMIT 1

");


$stmt->bind_param(
"s",
$token
);


$stmt->execute();


$result = $stmt->get_result();



if($result->num_rows > 0){


$user = $result->fetch_assoc();


$_SESSION['user_id'] = $user['id'];

$_SESSION['username'] = $user['username'];

$_SESSION['role'] = $user['role'];



header(
"Location: dashboard.php"
);

exit;


}


}




$message = "";




if(isset($_POST['login'])){


$username = trim(
$_POST['username']
);


$password = $_POST['password'];



if(
strlen($password) < 8
||
strlen($password) > 15
){


$message =
"Password must be 8-15 characters.";


}else{



$stmt = $conn->prepare("

SELECT *

FROM users

WHERE username=?

OR email=?

LIMIT 1

");



$stmt->bind_param(
"ss",
$username,
$username
);



$stmt->execute();


$result =
$stmt->get_result();



if($result->num_rows > 0){



$row =
$result->fetch_assoc();




if(
password_verify(
$password,
$row['password']
)
){



session_regenerate_id(true);



$_SESSION['user_id']
=
$row['id'];


$_SESSION['username']
=
$row['username'];


$_SESSION['role']
=
$row['role'];





/*
========================
REMEMBER ME
========================
*/


if(isset($_POST['remember'])){


$token =
bin2hex(
random_bytes(32)
);



$stmt2 =
$conn->prepare("

UPDATE users

SET remember_token=?

WHERE id=?

");



$stmt2->bind_param(
"si",
$token,
$row['id']
);



$stmt2->execute();




setcookie(

"remember_token",

$token,

time()+(
60*60*24*365
),

"/",

"",

true,

true

);



}



header(
"Location: dashboard.php"
);

exit;



}else{


$message =
"Password incorrect.";


}



}else{


$message =
"User not found.";


}



}


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
CN Tech Store | Login
</title>


<link rel="icon"
href="assets/favicon.png">


<link rel="preconnect"
href="https://fonts.googleapis.com">


<link rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>


<link href="
https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap
"
rel="stylesheet">


<link rel="stylesheet"
href="
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css
">


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
#450000,
#000 45%
);

padding:20px;

overflow:hidden;

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

background:#990000;

border-radius:50%;


filter:blur(150px);


bottom:-150px;


right:-100px;


opacity:.35;

}



.login-card{

position:relative;

z-index:10;

width:100%;

max-width:430px;

background:

rgba(255,255,255,.06);

backdrop-filter:blur(20px);

border:

1px solid rgba(255,0,0,.25);

border-radius:25px;

padding:40px;


box-shadow:

0 20px 60px rgba(255,0,0,.25);


color:#fff;

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
#700000
);


box-shadow:

0 0 35px rgba(255,0,0,.6);


font-size:38px;

color:#fff;

}



.login-card h1{

text-align:center;

font-size:30px;

font-weight:700;

margin-bottom:8px;

}



.login-card h1 span{

color:#ff2020;

}



.login-card p{

text-align:center;

color:#ccc;

font-size:14px;

margin-bottom:30px;

}



/* ERROR MESSAGE */

.error-box{


background:

rgba(255,0,0,.15);


border:

1px solid rgba(255,0,0,.4);


padding:14px;


border-radius:15px;


margin-bottom:20px;


display:flex;


align-items:center;


gap:10px;


color:#ffb3b3;


font-size:14px;


}



.input-group{

position:relative;

margin-bottom:20px;

}



.input-group i{


position:absolute;


left:16px;


top:50%;


transform:translateY(-50%);


color:#ff3030;


font-size:16px;


}



.input-group input{


width:100%;


padding:

15px 45px;


border-radius:15px;


border:

1px solid rgba(255,255,255,.15);


background:

rgba(255,255,255,.08);


color:white;


font-size:15px;


outline:none;


transition:.3s;


}



.input-group input::placeholder{


color:#999;


}



.input-group input:focus{


border-color:#ff2020;


box-shadow:

0 0 0 3px rgba(255,0,0,.2);


}



/* PASSWORD BUTTON */


#togglePassword{


position:absolute;


right:18px;


top:50%;


transform:translateY(-50%);


cursor:pointer;


color:#aaa;


font-size:18px;


}



#togglePassword:hover{


color:#ff2020;


}



/* OPTIONS */


.options{


display:flex;


justify-content:space-between;


align-items:center;


margin-bottom:25px;


font-size:14px;


}



.options label{


display:flex;


align-items:center;


gap:8px;


color:#ddd;


}



.options input{


accent-color:#ff0000;


}



.options a{


color:#ff3030;


text-decoration:none;


}



.options a:hover{


text-decoration:underline;


}



/* LOGIN BUTTON */


.login-btn{


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


font-weight:700;


cursor:pointer;


transition:.3s;


box-shadow:

0 10px 25px rgba(255,0,0,.3);


}



.login-btn:hover{


transform:translateY(-3px);


box-shadow:

0 15px 35px rgba(255,0,0,.5);


}



/* DIVIDER */


.divider{


display:flex;


align-items:center;


justify-content:center;


gap:15px;


margin:25px 0;


color:#888;


}



.divider::before,
.divider::after{


content:"";


height:1px;


background:#333;


flex:1;


}



/* SOCIAL BUTTON */


.social{


display:grid;


grid-template-columns:

repeat(3,1fr);


gap:12px;


}



.social a{


height:45px;


display:flex;


align-items:center;


justify-content:center;


border-radius:12px;


background:

rgba(255,255,255,.08);


border:

1px solid #333;


color:white;


font-size:18px;


transition:.3s;


}



.social a:hover{


background:#ff0000;


transform:translateY(-3px);


}

    /* =========================
SOCIAL LOGIN
========================= */

.social-login{

display:flex;

gap:12px;

margin-top:25px;

}

.social-login a{

flex:1;

height:55px;

display:flex;

align-items:center;

justify-content:center;

gap:10px;

text-decoration:none;

color:#fff;

font-weight:600;

font-size:14px;

border-radius:16px;

background:rgba(255,255,255,.06);

border:1px solid rgba(255,255,255,.12);

backdrop-filter:blur(12px);

transition:.3s;

position:relative;

overflow:hidden;

}

/* Glow Effect */

.social-login a::before{

content:"";

position:absolute;

top:0;

left:-120%;

width:100%;

height:100%;

background:

linear-gradient(
90deg,
transparent,
rgba(255,255,255,.15),
transparent
);

transition:.6s;

}

.social-login a:hover::before{

left:120%;

}

.social-login a:hover{

transform:translateY(-3px);

}

/* ICON */

.social-login i{

font-size:22px;

}

/* GOOGLE */

.social-google{

border-color:rgba(255,255,255,.15);

}

.social-google:hover{

background:

linear-gradient(
135deg,
#ea4335,
#c5221f
);

box-shadow:

0 0 25px rgba(234,67,53,.5);

}

/* FACEBOOK */

.social-facebook{

border-color:rgba(66,103,178,.35);

}

.social-facebook:hover{

background:

linear-gradient(
135deg,
#1877f2,
#0d5ed7
);

box-shadow:

0 0 25px rgba(24,119,242,.5);

}

/* DISCORD */

.social-discord{

border-color:rgba(88,101,242,.35);

}

.social-discord:hover{

background:

linear-gradient(
135deg,
#5865f2,
#404eed
);

box-shadow:

0 0 25px rgba(88,101,242,.5);

}

/* MOBILE */

@media(max-width:600px){

.social-login{

flex-direction:column;

}

.social-login a{

height:52px;

}

    }
    

/* REGISTER */


.register{


text-align:center;


margin-top:25px;


color:#aaa;


font-size:14px;


}



.register a{


color:#ff2020;


font-weight:bold;


text-decoration:none;


}



.register a:hover{


text-decoration:underline;


}




@media(max-width:480px){


.login-card{


padding:25px;


}



.login-card h1{


font-size:25px;


}



.logo{


width:75px;


height:75px;


font-size:30px;


}


    }
    
    </style>

</head>


<body>



<div class="login-card">



<?php if($message!=""){ ?>


<div class="error-box">

<i class="fa-solid fa-circle-exclamation"></i>

<span>

<?=htmlspecialchars($message)?>

</span>


</div>


<?php } ?>





<div class="logo">

<i class="fa-solid fa-gamepad"></i>

</div>





<h1>

CN Tech <span>Store</span>

</h1>



<p>

Login to your account

</p>







<form method="POST">





<div class="input-group">


<i class="fa-solid fa-user"></i>


<input

type="text"

name="username"

placeholder="Username or Email"

required>


</div>







<div class="input-group">


<i class="fa-solid fa-lock"></i>


<input

type="password"

id="password"

name="password"

placeholder="Password (8-15 characters)"

minlength="8"

maxlength="15"

required>



<span id="togglePassword">


<i class="fa-solid fa-eye"></i>


</span>



</div>







<div class="options">



<label>


<input

type="checkbox"

name="remember">


Remember Me


</label>





<a href="forgot-password.php">

Forgot Password?

</a>



</div>









<button

type="submit"

name="login"

class="login-btn">


<i class="fa-solid fa-right-to-bracket"></i>


Login



</button>





</form>









<div class="divider">


<span>

OR

</span>


</div>








<div class="social-login">

<a
href="oauth/google.php"
class="social-google">

<i class="fab fa-google"></i>

Google

</a>

<a
href="oauth/facebook.php"
class="social-facebook">

<i class="fab fa-facebook-f"></i>

Facebook

</a>

<a
href="oauth/discord.php"
class="social-discord">

<i class="fab fa-discord"></i>

Discord

</a>

    </div>








<div class="register">


Don't have an account?


<a href="register.php">

Create Account

</a>



</div>





    </div>
    
    <script>


const passwordInput = 
document.getElementById("password");


const togglePassword =
document.getElementById("togglePassword");



togglePassword.addEventListener(
"click",
function(){



if(passwordInput.type === "password"){



passwordInput.type = "text";



togglePassword.innerHTML =

'<i class="fa-solid fa-eye-slash"></i>';



}else{



passwordInput.type = "password";



togglePassword.innerHTML =

'<i class="fa-solid fa-eye"></i>';



}



});





/*
========================
AUTO HIDE ERROR
========================
*/


const errorBox =
document.querySelector(".error-box");



if(errorBox){


setTimeout(()=>{


errorBox.style.opacity="0";


errorBox.style.transition=".5s";



},5000);



}



</script>




</body>

</html>