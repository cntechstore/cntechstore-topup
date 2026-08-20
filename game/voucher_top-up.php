<?php

require "../database.php";


$sql = "
SELECT *
FROM voucher_categories
ORDER BY id DESC
LIMIT 12
";


$result = $conn->query($sql);



if(!$result || $result->num_rows == 0){

echo "
<div class='empty-box'>
ยังไม่มีบัตรเติมเงิน
</div>
";

return;

}



while($row=$result->fetch_assoc()){



$id = (int)$row['id'];

$name = htmlspecialchars($row['name'] ?? '');


$image = !empty($row['image'])
?
"/admin/uploads/".htmlspecialchars($row['image'])
:
"/assets/no-image.png";


$status = $row['status'] ?? 'inactive';


$isInactive = ($status === 'inactive');


$link = "game/voucher_pd.php?id=".$id;



?>

<style>

.voucher-card-link{

    text-decoration:none;

    color:inherit;

    display:block;

}


.voucher-card{

    position:relative;

    background:#fff;

    border-radius:18px;

    overflow:hidden;

    box-shadow:
    0 8px 25px rgba(0,0,0,.12);

    transition:.3s;

    height:100%;

}


.voucher-card:hover{

    transform:translateY(-5px);

    box-shadow:
    0 12px 35px rgba(0,0,0,.18);

}




.voucher-disabled{

    cursor:pointer;

    opacity:.85;

}





.voucher-img{

    position:relative;

    width:100%;

    height:160px;

    overflow:hidden;

}



.voucher-img img{

    width:100%;

    height:100%;

    object-fit:cover;

    display:block;

}





.voucher-overlay{

    position:absolute;

    inset:0;

    background:
    rgba(0,0,0,.65);


    display:flex;

    flex-direction:column;

    justify-content:center;

    align-items:center;


    color:white;

    text-align:center;


    backdrop-filter:blur(2px);

}



.voucher-overlay h3{

    margin:0;

    font-size:22px;

    color:#ffd54f;

}



.voucher-overlay p{

    margin-top:8px;

    font-size:15px;

}





.voucher-info{

    padding:15px;

    text-align:center;

}



.voucher-info h3{

    margin:0;

    font-size:18px;

}



.voucher-info p{

    color:#777;

    font-size:14px;

    margin:8px 0;

}





.btn{

    display:inline-block;

    padding:10px 25px;

    border-radius:10px;

    background:

    linear-gradient(
    135deg,
    #2196f3,
    #673ab7
    );


    color:white;

    font-weight:bold;

}





.btn-disabled{

    width:100%;

    padding:10px;


    border:none;

    border-radius:10px;


    background:#9ca3af;

    color:white;

    cursor:not-allowed;

}





@media(max-width:768px){


.voucher-img{

    height:130px;

}


.voucher-info{

    padding:12px;

}



.voucher-info h3{

    font-size:16px;

}



}


</style>

<?php if($isInactive){ ?>

<div
class="voucher-card voucher-disabled"
onclick="openMaintenance(
'<?=$name?>',
'<?=$image?>',
'ระบบ Game Card นี้อยู่ระหว่างการปรับปรุง'
)"
>


<?php }else{ ?>

<a href="<?=BASE_URL?><?=$link?>" class="voucher-card-link">

<div class="voucher-card">

<?php } ?>



<div class="voucher-img">

<img 
src="<?=$image?>"
alt="<?=$name?>"
>


<?php if($isInactive){ ?>

<div class="voucher-overlay">

<h3>
 ปิดปรับปรุง
</h3>

<p>
<?=$name?>
</p>

</div>

<?php } ?>


</div>




<div class="voucher-info">


<h3>
<?=$name?>
</h3>


<p>
Game Cards & Top-up
</p>



<?php if(!$isInactive){ ?>



<?php }else{ ?>

<button 
class="btn-disabled"
disabled
>
Unavailable
</button>


<?php } ?>


</div>



<?php if($isInactive){ ?>

</div>


<?php }else{ ?>

</div>

</a>


<?php } ?>


<?php

}

?>