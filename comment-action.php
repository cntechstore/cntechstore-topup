<?php

require "config.php";
require "database.php";

header("Content-Type: application/json");


$comment_id = (int)($_POST['comment_id'] ?? 0);

$action = $_POST['action'] ?? "";


$ip = $_SERVER['REMOTE_ADDR'];



if($comment_id <=0){

echo json_encode([
"status"=>"error",
"message"=>"Invalid comment"
]);

exit;

}



if(!in_array($action,['like','dislike'])){

echo json_encode([
"status"=>"error",
"message"=>"Invalid action"
]);

exit;

}




// ตรวจว่ากดแล้วหรือยัง

$check=$conn->prepare("

SELECT id

FROM blog_comment_votes

WHERE comment_id=?

AND ip_address=?

");



$check->bind_param(
"is",
$comment_id,
$ip
);


$check->execute();


$result=$check->get_result();



if($result->num_rows > 0){


echo json_encode([

"status"=>"error",

"message"=>"คุณกดไปแล้ว"

]);


exit;


}





// บันทึกการกด

$save=$conn->prepare("

INSERT INTO blog_comment_votes

(
comment_id,
ip_address,
vote_type
)

VALUES(?,?,?)

");


$save->bind_param(

"iss",

$comment_id,

$ip,

$action

);



$save->execute();




// เพิ่มคะแนน

if($action=="like"){


$conn->query("

UPDATE blog_comments

SET likes=likes+1

WHERE id=$comment_id

");


}else{


$conn->query("

UPDATE blog_comments

SET dislikes=dislikes+1

WHERE id=$comment_id

");


}




$get=$conn->prepare("

SELECT likes,dislikes

FROM blog_comments

WHERE id=?

");


$get->bind_param(
"i",
$comment_id
);


$get->execute();


$data=$get->get_result()->fetch_assoc();



echo json_encode([

"status"=>"success",

"likes"=>$data['likes'],

"dislikes"=>$data['dislikes']

]);


?>