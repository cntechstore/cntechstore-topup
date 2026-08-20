<?php

require_once "database.php";

$sql = "
SELECT *
FROM website_settings
LIMIT 1
";

$result = $conn->query($sql);

if(!$result || $result->num_rows == 0){
    return;
}

$site = $result->fetch_assoc();

$status =
    $site['site_status']
    ?? 'online';

if($status == 'online'){
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width,initial-scale=1">

<title>
<?= htmlspecialchars($site['title']) ?>
</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial;
    background:#0f172a;
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:90%;
    max-width:600px;
    background:#1e293b;
    padding:40px;
    border-radius:20px;
    text-align:center;
}

.icon{
    font-size:70px;
    margin-bottom:20px;
}

h1{
    margin:10px 0;
}

p{
    color:#cbd5e1;
}

</style>

</head>
<body>

<div class="box">

<?php
if($status=="maintenance"){
?>
<div class="icon">🔧</div>
<h1>Website Under Maintenance</h1>
<?php
}else{
?>
<div class="icon">🚧</div>
<h1>Website Under Development</h1>
<?php
}
?>

<p>
<?= nl2br(
htmlspecialchars(
$site['message']
)
) ?>
</p>

</div>

</body>
</html>

<?php
exit;
?>