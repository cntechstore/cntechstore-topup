<?php

require_once __DIR__ . "/database.php";


$host = $_SERVER['HTTP_HOST'];


/*
========================
ลบ www
========================
*/
$host = str_replace(
    "www.",
    "",
    $host
);


/*
========================
ตรวจสอบ Site Control
========================
*/

$stmt = $conn->prepare("
SELECT *
FROM site_control
WHERE domain=?
LIMIT 1
");


$stmt->bind_param(
    "s",
    $host
);


$stmt->execute();


$result = $stmt->get_result();


if($result->num_rows == 0){

    // ไม่มีข้อมูล ให้เข้าเว็บปกติ
    return;

}


$site = $result->fetch_assoc();


$status = $site['status'];


/*
========================
ONLINE
========================
*/

if($status=="online"){

   

    return;
}


/*
========================
MAINTENANCE
========================
*/

if($status=="maintenance"){

http_response_code(503);

?>

<!DOCTYPE html>
<html lang="lo">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">


<title>
<?= htmlspecialchars($site['message']) ?>
</title>


<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- Font Awesome -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<style>

body{

margin:0;
height:100vh;

display:flex;
align-items:center;
justify-content:center;

font-family:
'Segoe UI',
Arial,
sans-serif;


background:
linear-gradient(
135deg,
#0f172a,
#1e3a8a
);


}


/* Card */

.maintenance-box{

width:90%;
max-width:600px;

background:
rgba(255,255,255,0.1);

backdrop-filter:
blur(15px);


border-radius:25px;

padding:45px 30px;

text-align:center;

color:white;


box-shadow:
0 20px 50px
rgba(0,0,0,.4);


animation:
fadeIn .8s ease;


}


/* Icon */

.icon-box{

width:100px;
height:100px;

margin:auto;

border-radius:50%;


display:flex;

align-items:center;
justify-content:center;


background:#f59e0b;


font-size:45px;


animation:
pulse 2s infinite;


}



/* Title */

h1{

margin-top:25px;

font-size:32px;

font-weight:700;

}


/* Message */

.message{

margin-top:20px;

font-size:18px;

color:#e5e7eb;

}



/* Button */

.btn-home{

margin-top:30px;

border-radius:50px;

padding:
12px 30px;


}



/* Animation */


@keyframes fadeIn{

from{

opacity:0;
transform:
translateY(30px);

}

to{

opacity:1;
transform:
translateY(0);

}

}


@keyframes pulse{

0%{

transform:
scale(1);

}

50%{

transform:
scale(1.1);

}

100%{

transform:
scale(1);

}

}


</style>


</head>


<body>



<div class="maintenance-box">


<div class="icon-box">

<i class="fa-solid fa-screwdriver-wrench"></i>

</div>



<h1>

Website Maintenance

</h1>



<div class="message">

<?= nl2br(
htmlspecialchars(
$site['message']
)
) ?>

</div>



<button
class="btn btn-warning btn-home"
onclick="location.reload()">

<i class="fa-solid fa-rotate-right"></i>

 ລອງໃໝ່

</button>


</div>



<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>


<?php

exit;

}



/*
========================
LOCK
========================
*/

if($status=="locked"){

http_response_code(403);

exit("Website Locked");

}


?>