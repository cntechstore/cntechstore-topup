<?php

require "config.php";
require "database.php";


if(session_status() === PHP_SESSION_NONE){

    session_start();

}



/*
=========================
COMMENT REPLY SAVE
CN TECH STORE
=========================
*/


if($_SERVER['REQUEST_METHOD'] !== 'POST'){


    header("Location: /");

    exit;

}




$comment_id = (int)($_POST['comment_id'] ?? 0);


$name = trim($_POST['name'] ?? "");


$reply = trim($_POST['reply'] ?? "");




/*
=========================
VALIDATE
=========================
*/


if($comment_id <= 0){


    die("Invalid Comment");


}



if(empty($name) || empty($reply)){


    echo "

    <script>

    alert('Please fill all fields');

    history.back();

    </script>

    ";


    exit;

}




/*
=========================
SAVE REPLY
=========================
*/


$stmt = $conn->prepare("

INSERT INTO blog_comment_reply

(

comment_id,

name,

reply

)

VALUES

(

?,

?,

?

)

");



$stmt->bind_param(

"iss",

$comment_id,

$name,

$reply

);





if($stmt->execute()){



echo "

<script>


alert('Reply submitted successfully');


history.back();


</script>


";



}else{


echo "

<script>


alert('Cannot save reply');


history.back();


</script>


";


}



$stmt->close();

$conn->close();


?>