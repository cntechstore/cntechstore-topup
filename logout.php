<?php

session_start();

require_once "database.php";


if(isset($_SESSION['user_id'])){


$stmt=$conn->prepare("

UPDATE users

SET remember_token=NULL

WHERE id=?

");


$stmt->bind_param(
"i",
$_SESSION['user_id']
);


$stmt->execute();


}



setcookie(
"remember_token",
"",
time()-3600,
"/"
);



session_destroy();


header(
"Location: login.php"
);

exit;

?>