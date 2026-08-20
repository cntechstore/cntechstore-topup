<?php

date_default_timezone_set("Asia/Vientiane");

require "../database.php";


$sql = "

SELECT *

FROM games

WHERE status='active'

ORDER BY 

CASE 
WHEN last_played IS NULL THEN 1
ELSE 0
END,

last_played DESC,

play_count DESC

LIMIT 4

";


$result = $conn->query($sql);



if(!$result){


echo '

<div class="empty-box">

<i class="fa-solid fa-triangle-exclamation"></i>

Database error

</div>';

return;


}



if($result->num_rows == 0){


echo '

<div class="empty-box">

<i class="fa-solid fa-gamepad"></i>

ยังไม่มีเกม

</div>';

return;


}




while($row=$result->fetch_assoc()){


$link = "game/namegame.php?id=".(int)$row['id'];

?>


<a href="<?=BASE_URL?><?=$link?>"
class="game-item">



<div class="card">


<img

src="/admin/uploads/<?=htmlspecialchars($row['icon'])?>"

alt="<?=htmlspecialchars($row['name'])?>"

loading="lazy"

>



<h4>

<?=htmlspecialchars($row['name'])?>

</h4>



<div class="recent-time">


<i class="fa-regular fa-clock"></i>


<?php

if(!empty($row['last_played'])){


echo date(
"d/m/Y H:i",
strtotime($row['last_played'])
);


}else{


echo "ยังไม่เคยเล่น";


}


?>


</div>



</div>


</a>


<?php

}

?>