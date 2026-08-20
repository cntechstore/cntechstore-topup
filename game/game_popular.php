<?php

require "../database.php";


$sql = "

SELECT *

FROM games

WHERE status='active'

ORDER BY play_count DESC

LIMIT 10

";


$result = $conn->query($sql);



if(!$result){


echo '

<div class="empty-box">

<i class="fa-solid fa-triangle-exclamation"></i>

Database error

</div>

';


return;

}




if($result->num_rows == 0){


echo '

<div class="empty-box">

<i class="fa-solid fa-fire"></i>

ยังไม่มีเกมยอดนิยม

</div>

';


return;

}





while($row = $result->fetch_assoc()){



$link = "game/namegame.php?id=".(int)$row['id'];



$status = $row['status'] ?? 'inactive';


$isInactive = ($status !== "active");



?>



<?php if(!$isInactive){ ?>

<a href="<?=BASE_URL?><?=$link?>"

class="game-item">


<?php } ?>



<div class="card <?= $isInactive ? 'game-disabled':'' ?>">



<div class="game-image">


<img

src="/admin/uploads/<?=htmlspecialchars($row['icon'])?>"

alt="<?=htmlspecialchars($row['name'])?>"

loading="lazy"

>



<?php if($isInactive){ ?>


<div class="game-overlay">


<i class="fa-solid fa-screwdriver-wrench"></i>


<h3>

ปิดปรับปรุง

</h3>


</div>


<?php } ?>



</div>





<h4>


<?=htmlspecialchars($row['name'])?>


</h4>





<div class="views">


<i class="fa-solid fa-fire"></i>


เข้าใช้แล้ว


<?=number_format($row['play_count'] ?? 0)?>

ครั้ง


</div>





<?php if($isInactive){ ?>


<button class="btn-disabled" disabled>


<i class="fa-solid fa-lock"></i>

ไม่สามารถใช้งานได้


</button>


<?php } ?>




</div>





<?php if(!$isInactive){ ?>


</a>


<?php } ?>



<?php

}

?>