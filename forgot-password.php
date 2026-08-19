<?php
error_reporting(0);
session_start();

require_once "config.php";
require_once "database.php";

/* ================= CONFIG ================= */

const SITE_URL = 'https://cntechstore.shop';
const RESET_MINUTES = 30;

const EMAILJS_SERVICE = 'service_064h3l8';
const EMAILJS_TEMPLATE = 'template_mq2tlel';
const EMAILJS_PUBLIC_KEY = 'zPnQ14dGWHb6MZTr5';

/* ================= DB ================= */

if (!isset($conn) || !($conn instanceof mysqli)) {
    exit('Database error');
}

/* ================= HELPERS ================= */

function e($v){
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function msg($text,$type='error'){
    $_SESSION['forgot_msg']=$text;
    $_SESSION['forgot_type']=$type;
    header('Location: forgot-password.php');
    exit;
}

function sendResetEmail($email,$name,$url){

    $data=[
        'service_id'=>EMAILJS_SERVICE,
        'template_id'=>EMAILJS_TEMPLATE,
        'user_id'=>EMAILJS_PUBLIC_KEY,
        'template_params'=>[
            'to_email'=>$email,
            'email'=>$email,
            'name'=>$name ?: 'Customer',
            'reset_url'=>$url,
            'link'=>$url,
            'subject'=>'Reset your CNTECH STORE password'
        ]
    ];

    $ch=curl_init(
        'https://api.emailjs.com/api/v1.0/email/send'
    );

    curl_setopt_array($ch,[
        CURLOPT_POST=>true,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_HTTPHEADER=>[
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS=>json_encode($data),
        CURLOPT_TIMEOUT=>15
    ]);

    $res=curl_exec($ch);
    $code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $code===200;
}

/* ================= TABLE ================= */

$conn->query("
CREATE TABLE IF NOT EXISTS password_resets(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 email VARCHAR(255) NOT NULL,
 token_hash VARCHAR(255) NOT NULL,
 expires_at DATETIME NOT NULL,
 used TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 KEY token_hash(token_hash),
 KEY user_id(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

/* ================= RESET ================= */

$token=trim($_GET['token']??'');

/* ================= POST ================= */

if($_SERVER['REQUEST_METHOD']==='POST'){

    $action=$_POST['action']??'';

    /* ---------- REQUEST ---------- */

    if($action==='request'){

        $email=strtolower(trim($_POST['email']??''));

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            msg('Please enter a valid email address.');
        }

        $stmt=$conn->prepare("
            SELECT id,username,fullname,email
            FROM users
            WHERE email=?
            LIMIT 1
        ");

        $stmt->bind_param('s',$email);
        $stmt->execute();

        $user=$stmt->get_result()->fetch_assoc();
        $stmt->close();

        /* Same response whether account exists */

        if(!$user){
            msg(
                'If this email is registered, a password reset link has been sent.',
                'success'
            );
        }

        /* Remove old tokens */

        $stmt=$conn->prepare("
            DELETE FROM password_resets
            WHERE user_id=? OR email=? OR expires_at<NOW() OR used=1
        ");

        $uid=(int)$user['id'];

        $stmt->bind_param(
            'is',
            $uid,
            $email
        );

        $stmt->execute();
        $stmt->close();

        /* Token */

        $rawToken=bin2hex(random_bytes(32));
        $hash=hash('sha256',$rawToken);

        $expires=date(
            'Y-m-d H:i:s',
            time()+RESET_MINUTES*60
        );

        /* Save */

        $stmt=$conn->prepare("
            INSERT INTO password_resets
            (user_id,email,token_hash,expires_at)
            VALUES(?,?,?,?)
        ");

        $stmt->bind_param(
            'isss',
            $uid,
            $email,
            $hash,
            $expires
        );

        if(!$stmt->execute()){
            $stmt->close();
            msg('Unable to create reset request.');
        }

        $stmt->close();

        $url=
            SITE_URL.
            '/forgot-password.php?token='.
            urlencode($rawToken);

        $name=
            $user['fullname']??
            $user['username']??
            'Customer';

        if(!sendResetEmail($email,$name,$url)){

            $stmt=$conn->prepare("
                DELETE FROM password_resets
                WHERE token_hash=?
            ");

            $stmt->bind_param('s',$hash);
            $stmt->execute();
            $stmt->close();

            msg(
                'Unable to send email. Please contact support@cntechstore.shop.'
            );
        }

        msg(
            'If this email is registered, a password reset link has been sent.',
            'success'
        );
    }

    /* ---------- RESET PASSWORD ---------- */

    if($action==='reset'){

        $token=trim($_POST['token']??'');
        $password=$_POST['password']??'';
        $confirm=$_POST['confirm_password']??'';

        if(
            !preg_match('/^[a-f0-9]{64}$/i',$token)
        ){
            msg('Invalid or expired reset link.');
        }

        if(strlen($password)<8){
            msg('Password must be at least 8 characters.');
        }

        if($password!==$confirm){
            msg('Passwords do not match.');
        }

        $hash=hash('sha256',$token);

        $stmt=$conn->prepare("
            SELECT id,user_id
            FROM password_resets
            WHERE token_hash=?
            AND used=0
            AND expires_at>NOW()
            LIMIT 1
        ");

        $stmt->bind_param('s',$hash);
        $stmt->execute();

        $reset=$stmt->get_result()->fetch_assoc();
        $stmt->close();

        if(!$reset){
            msg('This reset link is invalid or expired.');
        }

        $passwordHash=password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $uid=(int)$reset['user_id'];

        $stmt=$conn->prepare("
            UPDATE users
            SET password=?
            WHERE id=?
            LIMIT 1
        ");

        $stmt->bind_param(
            'si',
            $passwordHash,
            $uid
        );

        if(!$stmt->execute()){
            $stmt->close();
            msg('Password reset failed.');
        }

        $stmt->close();

        /* Disable token */

        $rid=(int)$reset['id'];

        $stmt=$conn->prepare("
            UPDATE password_resets
            SET used=1
            WHERE id=?
        ");

        $stmt->bind_param('i',$rid);
        $stmt->execute();
        $stmt->close();

        header(
            'Location: login.php?password_reset=success'
        );

        exit;
    }

    msg('Invalid request.');
}

/* ================= TOKEN CHECK ================= */

$valid=false;

if($token){

    if(preg_match('/^[a-f0-9]{64}$/i',$token)){

        $hash=hash('sha256',$token);

        $stmt=$conn->prepare("
            SELECT id
            FROM password_resets
            WHERE token_hash=?
            AND used=0
            AND expires_at>NOW()
            LIMIT 1
        ");

        $stmt->bind_param('s',$hash);
        $stmt->execute();

        $valid=(bool)$stmt->get_result()->fetch_assoc();

        $stmt->close();
    }
}

/* ================= MESSAGE ================= */

$message=$_SESSION['forgot_msg']??'';
$type=$_SESSION['forgot_type']??'';

unset(
    $_SESSION['forgot_msg'],
    $_SESSION['forgot_type']
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width,initial-scale=1">

<title>
<?= $token?'Reset Password':'Forgot Password' ?>
 - CNTECH STORE
</title>

<style>
*{box-sizing:border-box}

body{
 margin:0;
 min-height:100vh;
 display:flex;
 align-items:center;
 justify-content:center;
 padding:20px;
 background:#050505;
 color:#fff;
 font-family:Arial,Helvetica,sans-serif
}

.card{
 width:100%;
 max-width:430px;
 background:#111;
 border:1px solid #292929;
 border-radius:20px;
 padding:28px 24px;
 box-shadow:0 20px 60px #000
}

.logo{
 text-align:center;
 font-size:25px;
 font-weight:900;
 margin-bottom:8px
}

.logo span{color:#ff2020}

.sub{
 text-align:center;
 color:#777;
 font-size:12px;
 margin-bottom:25px
}

h1{
 font-size:23px;
 margin:0 0 8px
}

.text{
 color:#888;
 font-size:14px;
 line-height:1.6;
 margin-bottom:22px
}

label{
 display:block;
 margin:14px 0 7px;
 color:#aaa;
 font-size:13px
}

input{
 width:100%;
 padding:13px;
 border-radius:10px;
 border:1px solid #333;
 background:#181818;
 color:#fff;
 outline:none
}

input:focus{
 border-color:#e51b23
}

button{
 width:100%;
 margin-top:18px;
 padding:14px;
 border:0;
 border-radius:11px;
 background:#e51b23;
 color:#fff;
 font-weight:bold;
 cursor:pointer
}

button:hover{
 background:#ff2020
}

.alert{
 padding:12px;
 border-radius:10px;
 margin-bottom:18px;
 font-size:13px;
 line-height:1.5
}

.success{
 background:#102515;
 color:#70d890;
 border:1px solid #245c35
}

.error{
 background:#2b1010;
 color:#ff7777;
 border:1px solid #692222
}

.back{
 display:block;
 text-align:center;
 margin-top:20px;
 color:#999;
 text-decoration:none;
 font-size:13px
}

.back:hover{color:#fff}
</style>
</head>

<body>

<div class="card">

<div class="logo">
CN<span>TECH</span> STORE
</div>

<div class="sub">
Computer • Mobile • Parts & Accessories
</div>

<?php if($message): ?>

<div class="alert <?=e($type==='success'?'success':'error')?>">
<?=e($message)?>
</div>

<?php endif; ?>


<?php if($token): ?>

<?php if($valid): ?>

<h1>Reset Password</h1>

<div class="text">
Create a new password for your CNTECH STORE account.
</div>

<form method="post">

<input type="hidden"
       name="action"
       value="reset">

<input type="hidden"
       name="token"
       value="<?=e($token)?>">

<label>New Password</label>

<input type="password"
       name="password"
       minlength="8"
       required
       autocomplete="new-password">

<label>Confirm Password</label>

<input type="password"
       name="confirm_password"
       minlength="8"
       required
       autocomplete="new-password">

<button type="submit">
Reset Password
</button>

</form>

<?php else: ?>

<h1>Link Expired</h1>

<div class="text">
This password reset link is invalid or has expired.
Please request a new reset link.
</div>

<a class="back"
   href="forgot-password.php">
Request New Link
</a>

<?php endif; ?>

<?php else: ?>

<h1>Forgot Password?</h1>

<div class="text">
Enter your account email and we will send you
a secure password reset link.
</div>

<form method="post">

<input type="hidden"
       name="action"
       value="request">

<label>Email Address</label>

<input type="email"
       name="email"
       placeholder="you@example.com"
       required
       autocomplete="email">

<button type="submit">
Send Reset Link
</button>

</form>

<a class="back"
   href="login.php">
← Back to Login
</a>

<?php endif; ?>

</div>

</body>
</html>