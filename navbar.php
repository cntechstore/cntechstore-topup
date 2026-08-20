<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "feature.php";
$cartCount = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['qty'];
    }
}
?>

<?php

require_once "device_check.php";


if(isPC()){

    include "pc_notice.php";

}

?>
<nav class="nav">



<nav>

<div class="logo">

<a href="<?=BASE_URL?>index.php">

<img 
src="<?=BASE_URL?>assets/lgo.png"
alt="CN Tech Store"
class="logo-image">

</a>

    </div>
 
    
    <div class="nav-right">

   

    <!-- Cart -->
    <?php if(isPC()){ ?>

<a href="javascript:void(0)"
   class="cart-icon"
   onclick="openPCNotice()">

    <i class="fa-solid fa-cart-shopping"></i>

    <span class="cart-count">
        <?= $cartCount ?>
    </span>

</a>


<?php }else{ ?>


<a href="javascript:void(0)"
   class="cart-icon"
   onclick="openCart()">

    <i class="fa-solid fa-cart-shopping"></i>

    <span class="cart-count">
        <?= $cartCount ?>
    </span>

</a>


<?php } ?>

   

        
        
    <!-- Menu -->
    <button class="menu-toggle"
            onclick="openMenu()">
        <i class="fa-solid fa-bars"></i>
    </button>

</div>

<!-- Search Popup -->

<!-- MOBILE SEARCH BOX -->
<div class="search-popup" id="searchPopup">
    <div class="search-box-popup">
        <input
            type="search"
            id="searchInput"
            placeholder="ຄົ້ນຫາສິນຄ້າ..."
            onkeyup="searchProducts(this.value)"
        >
        <button onclick="closeSearch()"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div id="searchResults" class="search-results"></div>
    </div>
    

  <!-- CART DRAWER -->
<div class="cart-drawer" id="cartDrawer">

    <div class="cart-header">
        <h3><i class="fa-solid fa-cart-shopping"></i> Shopping Cart</h3>
        <button onclick="closeCart()"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <div class="cart-body" id="cartBody">
        <!-- AJAX LOAD HERE -->
    </div>

</div>

<!-- OVERLAY -->
    <div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
    
  

</nav>



<!-- MENU -->
<ul class="nav-links" id="navLinks">
    
    
    <li><a href="<?=BASE_URL?>index.php">  
        <i class="fa-solid fa-house"></i>  ໜ້າຫຼັກ
        </a>  </li>

<li class="dropdown">

<a href="javascript:void(0)"
onclick="toggleDropdown(this)">

<i class="fa-brands fa-product-hunt"></i>

ຜະລິດຕະພັນ

<i class="fa-solid fa-chevron-down arrow"></i>

</a>

<ul class="dropdown-menu">

    <li><a href="<?=BASE_URL?>page/products.php"><i class="fa-brands fa-dropbox"></i>ສິນຄ້າ</a></li>

<?php $game_on = isFeatureEnabled('game_topup'); ?>  <li>  
    <a href="<?=BASE_URL?>page/<?= $game_on ? 'game_topup.php' : 'javascript:void(0)' ?>"  
       class="beta-link <?= !$game_on ? 'disabled-link' : '' ?>">  <i class="fa-solid fa-gamepad"></i>  
    ເຕີມເກມອອນລາຍ 

    <?php if($game_on): ?>  
        <span class="beta-badge">ເບຕ້າ</span>  
    <?php else: ?>  
          
    <?php endif; ?>  

</a>  
    </li>


    <?php $mobile_on = isFeatureEnabled('mobile_topup'); ?>  <li>  
    <a href="<?=BASE_URL?>page/<?= $mobile_on ? 'mobile_topup.php' : 'javascript:void(0)' ?>"  
       class="beta-link <?= !$mobile_on ? 'disabled-link' : '' ?>">  <i class="fa-solid fa-mobile"></i>  
    ເຕີມເງີນ ມູນຄ່າໂທ

    <?php if($mobile_on): ?>  
        <span class="beta-badge">ເບຕ້າ</span>  
    <?php else: ?>  
          
    <?php endif; ?>  

</a>  
    </li>


    <li><a href="<?=BASE_URL?>page/reels.php"><i class="fa-solid fa-film"></i>Ctectk reels video</a></li>
    

    <li><a href="<?=BASE_URL?>page/promotion.php"><i class="fa-solid fa-tag"></i>ໂປໂມຊັນ</a></li>

</ul>

    </li>





<li><a href="<?=BASE_URL?>page/shipping-method.php">  
    <i class="fa-solid fa-bag-shopping"></i>   ການຈັດສົ່ງ 
    </a>  </li>

    
    <li class="dropdown">

<a href="javascript:void(0)"
onclick="toggleDropdown(this)">

<i class="fa-solid fa-circle-info"></i>

ກ່ຽວກັບພວກເຮົາ

<i class="fa-solid fa-chevron-down arrow"></i>

</a>

<ul class="dropdown-menu">

    <li><a href="<?=BASE_URL?>page/about-us.php"><i class="fa-solid fa-layer-group"></i>ກຽວກັບພົວເຮົາ</a></li>

    <li><a href="<?=BASE_URL?>page/blogs-method.php"><i class="fa-solid fa-calendar-days"></i>ບົດຄວາມ ແລະ ຂ່າວສານ </a></li>

    <li><a href="<?=BASE_URL?>page/privacy-policy.php"><i class="fa-solid fa-feather"></i>ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ</a></li>

    <li><a href="<?=BASE_URL?>page/terms-of-service.php"><i class="fa-solid fa-users"></i>ເງື່ອນໄຂການໃຊ້ບໍລິການ</a></li>

    <li><a href="<?=BASE_URL?>page/return_policy.php"><i class="fa-solid fa-people-pulling"></i>ນະໂຍບາຍການຄືນເງິນ</a></li>

    <li><a href="<?=BASE_URL?>page/cookie-policy.php"><i class="fa-solid fa-palette"></i>ນະໂຍບາຍຄຸກກີ້</a></li>

    <li><a href="<?=BASE_URL?>page/faq.php"><i class="fa-solid fa-star"></i>ຄຳຖາມທີ່ພົບເລື້ອຍ</a></li>

</ul>

    </li>
    
    
<li><a href="<?=BASE_URL?>page/payment-method.php">  
    <i class="fa-solid fa-coins"></i>   ວິທີການຊຳລະເງິນ  
    </a>  </li>





<li><a href="<?=BASE_URL?>page/privacy-policy.php">  
    <i class="fa-solid fa-circle-exclamation"></i>  ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ
    </a>  </li>

<li class="dropdown">

<a href="javascript:void(0)"
onclick="toggleDropdown(this)">

<i class="fa-solid fa-headset"></i>

ຕິດຕໍ່ພວກເຮົາ

<i class="fa-solid fa-chevron-down arrow"></i>

</a>

<ul class="dropdown-menu">

    <li><a href="<?=BASE_URL?>page/contact-method.php"><i class="fa-solid fa-headset"></i>ຕິດຕໍ່ພວກເຮົາ</a></li>

    <li><a href="<?=BASE_URL?>page/support.php"><i class="fa-solid fa-hand-holding-droplet"></i>ທີມງານຊ່ວຍເຫຼືອ</a></li>

    <li><a href="<?=BASE_URL?>page/help-center.php"><i class="fa-solid fa-headphones"></i>ສູນຊ່ວຍເຫຼືອ</a></li>

    <li><a href="<?=BASE_URL?>page/faq.php"><i class="fa-solid fa-fire"></i>ຄຳຖາມທີ່ພົບເລື້ອຍໆ</a></li>

</ul>

    </li>

  
    <br>
    <hr>
    <br>
<!-- Theme -->

<button id="themeBtn"

class="toggleTheme"

onclick="toggleTheme()">

<i class="fa-solid fa-circle-half-stroke"></i>

    </button>
    
</ul>


    
</nav>



<!-- OVERLAY -->
<div class="overlay" id="overlay" onclick="closeMenu()"></div>

<script>

function openPCNotice(){

    const popup =
    document.getElementById(
        "pcNoticePopup"
    );


    if(popup){

        popup.style.display="flex";

    }

}
    
</script>

<script>
function toggleUserMenu() {
    document
        .getElementById("userMenu")
        .classList.toggle("show");
}
</script>
