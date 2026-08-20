<?php
while($row = $result->fetch_assoc()){

    $isMaintenance = $row['status'] === "maintenance";

    $link = $isMaintenance ? "#" : "namegame.php?id=".$row['id'];

    $disabledClass = $isMaintenance ? "disabled" : "";

    $statusText = $isMaintenance
        ? "🔴 Maintenance"
        : "🟢 Play Now";

    echo "
    <div class='game-card {$disabledClass}'>

        <img src='{$row['image']}'>

        <h3>{$row['name']}</h3>

        <p class='status'>{$statusText}</p>

        <a href='{$link}' class='btn'>
            Enter
        </a>

    </div>
    ";
}
?>