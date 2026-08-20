<?php
require "../config.php";
require "../database.php";
session_start();

error_reporting(E_ALL);
ini_set("display_errors",1);

$id = (int)($_GET['id'] ?? 0);

if($id <= 0){
    die("Invalid Product");
}

/* =====================
   GET PRODUCT
===================== */
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id=?
    LIMIT 1
");

$stmt->bind_param("i",$id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if(!$product){
    die("Product not found");
}

/* =====================
   VIEW +1
===================== */
$conn->query("
    UPDATE products
    SET views = views + 1
    WHERE id=$id
");

/* =====================
   DATA
===================== */
$name      = htmlspecialchars($product['name']);
$desc      = htmlspecialchars($product['description']);
$price     = (float)$product['price'];
$stock     = (int)($product['stock'] ?? 0);
$views     = (int)($product['views'] ?? 0) + 1;
$sold      = (int)($product['sold'] ?? 0);
$rating    = (float)($product['rating'] ?? 0);
$reviews   = (int)($product['reviews'] ?? 0);
$discount  = (int)($product['discount'] ?? 0);

$finalPrice = $price;

if($discount > 0){
    $finalPrice =
        $price - ($price * $discount / 100);
}

$out = ($stock <= 0);

$low = (
    $stock > 0 &&
    $stock <= 2
);

$image =
    !empty($product['image'])
    ? "/admin/uploads/".$product['image']
    : "/admin/uploads/no-image.png";
?>

<!DOCTYPE html>
<html lang="th">
<head>

<meta charset="UTF-8">
<meta
    name="viewport"
    content="width=device-width,initial-scale=1.0">

<title><?= $name ?> | CN Tech Store</title>

<?php include "cdn.php"; ?>

<link
    rel="stylesheet"
    href="style.css?v=<?= time() ?>">

<style>

.product-container{
    max-width:1200px;
    margin:auto;
    padding:30px 15px;
}

.product-box{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 25px rgba(0,0,0,.08);
}

.product-image img{
    width:100%;
    border-radius:15px;
    object-fit:cover;
}

.product-title{
    font-size:32px;
    margin-bottom:10px;
}

.product-rating{
    color:#f59e0b;
    margin-bottom:15px;
}

.product-price{
    font-size:32px;
    color:#16a34a;
    font-weight:bold;
}

.old-price{
    color:#999;
    text-decoration:line-through;
    margin-top:10px;
}

.discount{
    color:red;
    font-weight:bold;
}

.product-stats{
    margin:20px 0;
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.product-stats div{
    background:#f3f4f6;
    padding:8px 15px;
    border-radius:8px;
}

.product-description{
    line-height:1.8;
    margin:20px 0;
}

.stock-low{
    background:#f59e0b;
    color:#fff;
    padding:8px 15px;
    border-radius:8px;
    display:inline-block;
    margin-bottom:15px;
}

.stock-out{
    background:#dc2626;
    color:#fff;
    padding:8px 15px;
    border-radius:8px;
    display:inline-block;
    margin-bottom:15px;
}

.buy-btn{
    width:100%;
    border:0;
    background:#6d28d9;
    color:#fff;
    padding:15px;
    border-radius:10px;
    cursor:pointer;
    font-size:18px;
}

.buy-btn:hover{
    opacity:.9;
}

.buy-btn:disabled{
    background:#999;
    cursor:not-allowed;
}

    .product-rating{
    display:flex;
    align-items:center;
    gap:4px;
    margin-bottom:15px;
}

.product-rating i{
    color:#f59e0b;
    font-size:18px;
}

.rating-text{
    color:#666;
    margin-left:10px;
}

.rating-box{
    margin:20px 0;
    padding:15px;
    background:#f8fafc;
    border-radius:10px;
}

.rate-stars{
    margin-top:10px;
}

.rate-stars i{
    font-size:30px;
    color:#ccc;
    cursor:pointer;
    transition:.2s;
}

.rate-stars i:hover,
.rate-stars i.active{
    color:#f59e0b;
    transform:scale(1.1);
    }
    
    .product-rating{
    margin:15px 0;
    font-size:18px;
}

.product-rating .fa-star,
.product-rating .fa-star-half-stroke{
    color:#f59e0b;
}

.product-rating .fa-regular{
    color:#d1d5db;
}

.review-box{
    margin-top:30px;
    padding:20px;
    background:#f8fafc;
    border-radius:10px;
}

.review-stars{
    margin:15px 0;
}

.review-stars i{
    font-size:28px;
    color:#d1d5db;
    cursor:pointer;
    transition:.2s;
}

.review-stars i.active{
    color:#f59e0b;
}

.review-box textarea{
    width:100%;
    height:120px;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    resize:none;
}

.review-btn{
    margin-top:15px;
    background:#2563eb;
    color:#fff;
    border:0;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
}

.review-btn:hover{
    opacity:.9;
    }
@media(max-width:768px){

    .product-box{
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<!-- NAVBAR -->
<?php include "navbar.php"; ?>

<div class="product-container">

    <div class="product-box">

        <!-- IMAGE -->
        <div class="product-image">

            <img
                src="<?= $image ?>"
                alt="<?= $name ?>">

        </div>

        <!-- INFO -->
        <div>

            <h1 class="product-title">
                <?= $name ?>
            </h1>

            <div class="product-rating">

<?php
$full = floor($rating);
$half = ($rating - $full) >= 0.5;
$empty = 5 - $full - ($half ? 1 : 0);

for($i=0;$i<$full;$i++){
    echo '<i class="fa-solid fa-star"></i>';
}

if($half){
    echo '<i class="fa-solid fa-star-half-stroke"></i>';
}

for($i=0;$i<$empty;$i++){
    echo '<i class="fa-regular fa-star"></i>';
}
?>

<span class="rating-text">
    <?= number_format($rating,1) ?>
    (<?= number_format($reviews) ?> รีวิว)
</span>

            </div>

            <?php if($out){ ?>

                <div class="stock-out">
                    ❌ สินค้าหมด
                </div>

            <?php } elseif($low){ ?>

                <div class="stock-low">
                    ⚠ สินค้าใกล้หมด เหลือ <?= $stock ?> ชิ้น
                </div>

            <?php } ?>

            <?php if($discount > 0){ ?>

                <div class="old-price">
                    ₭ <?= number_format($price,2) ?>
                </div>

                <div class="product-price">
                    ₭ <?= number_format($finalPrice,2) ?>

                    <span class="discount">
                        -<?= $discount ?>%
                    </span>
                </div>

            <?php }else{ ?>

                <div class="product-price">
                    ₭ <?= number_format($price,2) ?>
                </div>

            <?php } ?>

            <div class="product-stats">

                <div>
                    👁 <?= number_format($views) ?>
                </div>

                <div>
                    🛒 <?= number_format($sold) ?>
                </div>

                <div>
                    📦 <?= number_format($stock) ?>
                </div>

                <div>
                    SKU #<?= $id ?>
                </div>

            </div>

            <div class="review-box">

    <h3>
        <i class="fa-solid fa-star"></i>
        ให้คะแนนสินค้า
    </h3>

    <form action="review_submit.php" method="POST">

        <input
            type="hidden"
            name="product_id"
            value="<?= $id ?>">

        <input
            type="hidden"
            id="rating"
            name="rating"
            value="5">

        <div class="review-stars">

            <i class="fa-solid fa-star active"
               data-rate="1"></i>

            <i class="fa-solid fa-star active"
               data-rate="2"></i>

            <i class="fa-solid fa-star active"
               data-rate="3"></i>

            <i class="fa-solid fa-star active"
               data-rate="4"></i>

            <i class="fa-solid fa-star active"
               data-rate="5"></i>

        </div>

        <textarea
            name="comment"
            placeholder="เขียนรีวิวสินค้า..."
            required></textarea>

        <button
            type="submit"
            class="review-btn">

            <i class="fa-solid fa-paper-plane"></i>
            ส่งรีวิว

        </button>

    </form>

            </div>

            </div>
            
            <div class="product-description">
                <?= nl2br($desc) ?>
            </div>

            <?php if(!$out){ ?>

            <form
                action="cart.php"
                method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $id ?>">

                <button
    class="add-cart"
    data-id="<?= $id ?>">
    <i class="fa-solid fa-cart-arrow-down"></i>
    Add to Cart
                </button>

            </form>

            <?php }else{ ?>

                <button
                    class="buy-btn"
                    disabled>

                    Out Of Stock

                </button>

            <?php } ?>

        </div>

    </div>

</div>

<!-- FOOTER -->
<?php include "footer.php"; ?>

<script src="app.js?v=<?= time() ?>"></script>

    <script>

document.querySelectorAll('.review-stars i')
.forEach(star=>{

    star.onclick=function(){

        let rate=this.dataset.rate;

        document.getElementById(
            'rating'
        ).value=rate;

        document
        .querySelectorAll(
            '.review-stars i'
        )
        .forEach((s,index)=>{

            if(index<rate)
                s.classList.add('active');
            else
                s.classList.remove('active');

        });

    };

});

    </script>
</body>
</html>