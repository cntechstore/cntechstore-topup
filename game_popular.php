<?php
require "database.php";

$sql = "SELECT * FROM games
        WHERE status='active'
        ORDER BY views DESC
        LIMIT 4";

$result = $conn->query($sql);

if($result->num_rows == 0){

    echo "
    <div class='empty-box'>
        🔥 ยังไม่มีเกมยอดนิยม 🔥
    </div>
    ";

    return;
}

while($row = $result->fetch_assoc()){

echo "
<div class='game-card'>

    <img src='/admin/uploads/{$row['icon']}'>

    <h3>{$row['name']}</h3>

    <p>🔥 {$row['views']} views</p>

    <a href='namegame.php?id={$row['id']}' class='btn'>
        Play Now
    </a>

</div>
";
}
?>