<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p class='empty'> Cart is empty</p>";
    exit;
}

$total = 0;

foreach ($_SESSION['cart'] as $key => $item) {

    $name  = htmlspecialchars($item['name']);
    $price = (float)$item['price'];
    $qty   = (int)$item['qty'];
    $image = !empty($item['image'])
    ? "/admin/uploads/" . $item['image']
    : "/admin/uploads/no-image.png";

    $subtotal = $price * $qty;
    $total += $subtotal;

    echo "
    <div class='cart-item'>

        <img src='{$image}'
     class='cart-img'
     alt='{$name}'
     loading='lazy'>

        <div class='cart-info'>
            <p class='cart-name'>{$name}</p>
            <p class='cart-price'>₭ " . number_format($price, 2) . "</p>

            <div class='qty-box'>

                <button onclick='updateQty(\"{$key}\", -1)'>-</button>

                <span>{$qty}</span>

                <button onclick='updateQty(\"{$key}\", 1)'>+</button>

            </div>

            <p class='subtotal'>
                Subtotal: ₭ " . number_format($subtotal, 2) . "
            </p>
        </div>

        <button class='remove-btn' onclick='removeItem(\"{$key}\")'>✕</button>

    </div>
    ";
}

echo "
<div class='cart-footer'>

    <h3>Total: ₭ " . number_format($total, 2) . "</h3>

    <button type='button' class='btn-next' onclick='goCheckout()'>
        ดำเนินการต่อ
    </button>

</div>
";
?>