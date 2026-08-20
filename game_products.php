<?php
require "database.php";

$sql = "SELECT * FROM game_products
        ORDER BY id DESC
        LIMIT 8";

$result = $conn->query($sql);

if($result->num_rows == 0){

    echo "
    <div class='empty-box'>
        ❌ ยังไม่มีผลิตภัณฑ์ ❌
    </div>
    ";

    return;
}

while($row = $result->fetch_assoc()){

$isOff = $row['status'] == 'maintenance';

$link = $isOff ? "#" : "namegame.php?id=".$row['game_id'];

$class = $isOff ? "disabled" : "";

echo "
<div class='game-card {$class}'>

    <img src='/admin/uploads/{$row['image']}'>

    <h3>{$row['name']}</h3>

    <p>-{$row['discount']}%</p>

    <h4>{$row['price']} ₭</h4>

    <a href='{$link}' class='btn'>
        Top Up
    </a>

</div>
";
}
?>