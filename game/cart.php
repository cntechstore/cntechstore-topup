<?php
session_start();

$total = 0;

// เพิ่มจำนวน
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty']++;
    }

    header("Location: cart.php");
    exit();
}


// ลดจำนวน
if (isset($_GET['minus'])) {
    $id = (int)$_GET['minus'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['qty']--;

        if ($_SESSION['cart'][$id]['qty'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    header("Location: cart.php");
    exit();
}


// ลบสินค้า
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];

    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit();
}


// ล้างตะกร้าทั้งหมด
if (isset($_GET['clear'])) {

    unset($_SESSION['cart']);

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>CN Tech Store - Cart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="../css/style.css">

<style>

body {
    background: #f5f6fa;
}

/* Container */
.cart-container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 15px;
}


/* Product Card */
.cart-item {
    display: flex;
    gap: 15px;
    background: #fff;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,.08);
}


.cart-item img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
}


.cart-info {
    flex: 1;
}


.cart-info h3 {
    margin-bottom: 8px;
}


.price {
    color: #ff3b30;
    font-size: 18px;
    font-weight: bold;
}


/* Quantity */
.qty-box {
    display:flex;
    align-items:center;
    gap:12px;
    margin:12px 0;
}


.qty-btn {
    width:35px;
    height:35px;

    display:flex;
    justify-content:center;
    align-items:center;

    background:#007aff;
    color:white;

    border-radius:50%;
    text-decoration:none;
    font-weight:bold;
}


.qty-btn:hover {
    opacity:.8;
}


/* Delete */
.remove-btn {
    background:#ff3b30;
    color:#fff;
    padding:8px 15px;
    border-radius:8px;
    text-decoration:none;
}


/* Total Box */
.total-box {
    background:#222;
    color:white;
    padding:20px;
    border-radius:15px;
    text-align:right;
}


.checkout-btn {
    display:inline-block;
    margin-top:10px;

    background:#28a745;
    color:white;

    padding:12px 25px;
    border-radius:10px;
    text-decoration:none;
}


.clear-btn {
    background:#555;
    color:white;

    padding:10px 15px;
    text-decoration:none;

    border-radius:8px;
    margin-right:10px;
}


/* Empty */
.empty {
    text-align:center;
    background:white;
    padding:50px;
    border-radius:15px;
}


/* Mobile */
@media(max-width:700px){

    .cart-item {
        flex-direction:column;
    }

    .cart-item img {
        width:100%;
        height:220px;
    }

    .total-box {
        text-align:center;
    }

}

</style>

</head>


<body>

<?php include "../includes/navbar.php"; ?>


<div class="cart-container">

<h1>🛒 Shopping Cart</h1>


<?php if (!empty($_SESSION['cart'])): ?>


<?php foreach($_SESSION['cart'] as $item): ?>

<?php

$subtotal = $item['price'] * $item['qty'];
$total += $subtotal;


$image = !empty($item['image'])
    ? "../uploads/" . htmlspecialchars($item['image'])
    : "../uploads/no-image.png";

?>


<div class="cart-item">


<img src="<?= $image ?>"
alt="<?= htmlspecialchars($item['name']) ?>">


<div class="cart-info">


<h3>
<?= htmlspecialchars($item['name']) ?>
</h3>


<p class="price">
฿<?= number_format($item['price'],2) ?>
</p>


<div class="qty-box">

<a class="qty-btn"
href="cart.php?minus=<?= $item['id'] ?>">
-
</a>


<strong>
<?= $item['qty'] ?>
</strong>


<a class="qty-btn"
href="cart.php?add=<?= $item['id'] ?>">
+
</a>

</div>


<p>
Subtotal:
<b>
฿<?= number_format($subtotal,2) ?>
</b>
</p>


<a class="remove-btn"
href="cart.php?remove=<?= $item['id'] ?>">
Delete
</a>


</div>


</div>


<?php endforeach; ?>


<div class="total-box">

<h2>
Total:
฿<?= number_format($total,2) ?>
</h2>


<a class="clear-btn"
href="cart.php?clear=1">
Clear Cart
</a>


<a class="checkout-btn"
href="checkout.php">
Checkout
</a>


</div>


<?php else: ?>


<div class="empty">

<h2>
🛒 Cart is Empty
</h2>

<p>
Add some products to your cart.
</p>

<a href="products.php">
Go Shopping
</a>

</div>


<?php endif; ?>


</div>


</body>
</html>