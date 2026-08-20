<?php  
session_start();  
  
$cart = $_SESSION['cart'] ?? [];  
$total = 0;  
?>  <style>  
.checkout-box{  
    background:#fff;  
    padding:25px;  
    border-radius:16px;  
    box-shadow:0 4px 20px rgba(0,0,0,.08);  
    max-width:900px;  
    margin:auto;  
}  
  
.checkout-title{  
    font-size:28px;  
    font-weight:700;  
    margin-bottom:20px;  
}  
  
.checkout-form input{  
    width:100%;  
    padding:14px;  
    margin-bottom:12px;  
    border:1px solid #ddd;  
    border-radius:10px;  
    font-size:15px;  
    outline:none;  
}  
  
.checkout-form input:focus{  
    border-color:#2563eb;  
}  
  
.checkout-summary{  
    margin-top:20px;  
}  
  
.checkout-summary h4{  
    margin-bottom:15px;  
    font-size:20px;  
}  
  
.checkout-item{  
    display:flex;  
    align-items:center;  
    gap:15px;  
    padding:15px 0;  
    border-bottom:1px solid #eee;  
}  
  
.checkout-img{  
    width:80px;  
    height:80px;  
    object-fit:cover;  
    border-radius:10px;  
    border:1px solid #ddd;  
    flex-shrink:0;  
}  
  
.checkout-info{  
    flex:1;  
    min-width:0;  
}  
  
.checkout-name{  
    font-size:16px;  
    font-weight:600;  
    margin-bottom:5px;  
  
    overflow:hidden;  
    text-overflow:ellipsis;  
    white-space:nowrap;  
}  
  
.checkout-price{  
    color:#2563eb;  
    font-weight:700;  
}  
  
.checkout-qty{  
    min-width:50px;  
    text-align:right;  
    font-weight:bold;  
}  
  
.checkout-total{  
    margin-top:20px;  
    text-align:right;  
    font-size:24px;  
    font-weight:700;  
}  
  
.btn-next{  
    width:100%;  
    margin-top:20px;  
    padding:16px;  
    border:none;  
    border-radius:10px;  
    background:#2563eb;  
    color:#fff;  
    font-size:16px;  
    font-weight:bold;  
    cursor:pointer;  
}  
  
.btn-next:hover{  
    opacity:.9;  
}  
  
.empty-cart{  
    text-align:center;  
    padding:50px;  
    font-size:18px;  
}  
  
@media(max-width:768px){  
  
    .checkout-box{  
        padding:15px;  
    }  
  
    .checkout-item{  
        gap:10px;  
    }  
  
    .checkout-img{  
        width:65px;  
        height:65px;  
    }  
  
    .checkout-name{  
        font-size:14px;  
    }  
  
    .checkout-price{  
        font-size:14px;  
    }  
  
    .checkout-qty{  
        min-width:35px;  
        font-size:14px;  
    }  
  
    .checkout-total{  
        font-size:20px;  
    }  
}  
</style>  <div class="checkout-box">  <h3 class="checkout-title">  
    🧾 Checkout  
</h3>  <?php if(empty($cart)){ ?>  <div class="empty-cart">  
    Cart is empty  
</div>  <?php } else { ?>  <form  
    class="checkout-form"  
    onsubmit="event.preventDefault(); goPayment();">  <input  
    type="text"  
    name="fullname"  
    placeholder="Full Name"  
    required>  

<input  
    type="email"  
    name="email"  
    placeholder="Email Address"  
    required>  

<input  
    type="text"  
    name="address"  
    placeholder="Shipping Address"  
    required>  

<hr>  

<div class="checkout-summary">  

    <h4>Order Summary</h4>  

    <?php foreach($cart as $item): ?>  

    <?php  
    $price = (float)$item['price'];  
    $qty = (int)$item['qty'];  

    $subtotal = $price * $qty;  
    $total += $subtotal;  

    $image = !empty($item['image'])  
        ? "/admin/uploads/".$item['image']  
        : "/admin/uploads/no-image.png";  
    ?>  

    <div class="checkout-item">  

        <img  
            src="<?= htmlspecialchars($image) ?>"  
            class="checkout-img"  
            alt="<?= htmlspecialchars($item['name']) ?>"  
            loading="lazy">  

        <div class="checkout-info">  

            <div class="checkout-name">  
                <?= htmlspecialchars($item['name']) ?>  
            </div>  

            <div class="checkout-price">  
                ₭ <?= number_format($price,2) ?>  
            </div>  

        </div>  

        <div class="checkout-qty">  
            x<?= $qty ?>  
        </div>  

    </div>  

    <?php endforeach; ?>  

    <div class="checkout-total">  
        Total:  
        ₭ <?= number_format($total,2) ?>  
    </div>  

</div>  

<button  
    type="submit"  
    class="btn-next">  

    ไปชำระเงิน  

</button>

</form>  <?php } ?>  </div>  

<script>

   async function goPayment() {

    const form = document.querySelector(".checkout-form");

    if (!form) {
        alert("Form not found");
        return;
    }

    const fullname = form.querySelector('[name="fullname"]')?.value || '';
    const email    = form.querySelector('[name="email"]')?.value || '';
    const address  = form.querySelector('[name="address"]')?.value || '';

    console.log({ fullname, email, address });

    if (!fullname || !email || !address) {
        alert("กรุณากรอกข้อมูลให้ครบ");
        return;
    }

    const res = await fetch("checkout_ajax.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            fullname: fullname,
            email: email,
            address: address
        })
    });

    const data = await res.json();

    console.log(data);

    if (data.status === "success") {

        location.href =
            "payment_ajax.php?order_id=" +
            data.order_id +
            "&type=shop";

    } else {

        alert(data.message);
    }
   };
    
</script>