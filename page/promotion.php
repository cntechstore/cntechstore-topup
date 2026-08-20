<?php
session_start();

require_once "../config.php";
require_once "../database.php";

$sql = $conn->prepare("
SELECT *
FROM promotions
WHERE status='active'
AND (
    start_date IS NULL
    OR start_date<=NOW()
)
AND (
    end_date IS NULL
    OR end_date>=NOW()
)
ORDER BY id DESC
");

$sql->execute();

$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>

<meta charset="UTF-8">

<title>Promotion</title>

<link rel="stylesheet" href="../style.css?v=1.0.0">
<link rel="stylesheet" href="../promotion.css?v=1.0.0">
    <script src="../app.js?v=1.0"></script>
    
    <script>

function toggleDropdown(el){

const parent = el.parentElement;

parent.classList.toggle("active");

}

    </script>
    
    <style>
    
    .logo-image{

    width:120px;
    height:68px;

        }
        
    </style>
</head>

<body>

<?php include "../navbar.php"; ?>

<div class="container">

<h2> โปรโมชั่นล่าสุด</h2>

<div class="promo-grid">

<?php while($row = $result->fetch_assoc()){ ?>

<div class="promo-card">

<?php if(!empty($row['image'])){ ?>

<img 
src="../uploads/promotions/<?= htmlspecialchars($row['image']) ?>"
alt="<?= htmlspecialchars($row['title']) ?>">

<?php } ?>

<h3>

<?= htmlspecialchars($row['title']) ?>

</h3>

<p>

<?= nl2br(htmlspecialchars($row['description'])) ?>

</p>

<?php if(!empty($row['discount'])){ ?>

<div class="discount">

<?= htmlspecialchars($row['discount']) ?>

</div>

<?php } ?>

<?php if(!empty($row['coupon'])){ ?>

<div class="coupon">

Coupon :

<b>

<?= htmlspecialchars($row['coupon']) ?>

</b>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

</div>

<?php include "../footer.php"; ?>

</body>
</html>