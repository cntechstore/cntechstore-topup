<?php

require "config.php";
require "database.php";

if(session_status() === PHP_SESSION_NONE){
    session_start();
}


/*
=========================
BLOG COMMENT SAVE
CN TECH STORE
=========================
*/


if($_SERVER['REQUEST_METHOD'] !== 'POST'){

    header("Location: /");
    exit;

}



$blog_id = (int)($_POST['blog_id'] ?? 0);

$name = trim($_POST['name'] ?? '');

$email = trim($_POST['email'] ?? '');

$comment = trim($_POST['comment'] ?? '');




/*
=========================
VALIDATE
=========================
*/


if($blog_id <= 0){

    die("Invalid Blog");

}


if(empty($name) || empty($comment)){

    die("Please fill required fields");

}



/*
=========================
SAVE COMMENT
=========================
*/


$stmt = $conn->prepare("

INSERT INTO blog_comments

(
blog_id,
name,
email,
comment,
status
)

VALUES

(
?,
?,
?,
?,
'approved'
)

");



$stmt->bind_param(

"isss",

$blog_id,
$name,
$email,
$comment

);



if($stmt->execute()){


    echo "

    <script>

    alert('ส่งคอมเม้นสำเร็จ ขอบคุณ ที่ มีส่วนร่วม');

    window.location.href='blog-detail.php?id=$blog_id';

    </script>

    ";



}else{


    echo "

    <script>

    alert('Cannot save comment');

    history.back();

    </script>

    ";


}



$stmt->close();

$conn->close();


?>