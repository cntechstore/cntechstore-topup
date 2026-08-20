<?php
require "database.php";

$sql = "SELECT * FROM games
        WHERE status='active'
        ORDER BY last_played DESC
        LIMIT 4";

$result = $conn->query($sql);

/* ❗ กัน error ถ้า query ล้ม */
if(!$result){
    echo "<div class='empty-box'>❌ Database error</div>";
    return;
}

/* ❗ กัน null / ไม่มี table */
if($result->num_rows == 0){
    echo "<div class='empty-box'>❗ยังไม่มีข้อมูลล่าสุด❗</div>";
    return;
}

/* แสดงข้อมูล */
while($row = $result->fetch_assoc()){
?>
    <div class="game-card">

        <img src="/admin/uploads/<?= htmlspecialchars($row['icon']) ?>">

        <h3><?= htmlspecialchars($row['name']) ?></h3>

        <a href="namegame.php?id=<?= (int)$row['id'] ?>" class="btn">
            Continue
        </a>

    </div>
<?php
}
?>