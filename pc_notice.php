<?php
?>

<div id="pcBlockScreen" class="pc-block-screen">

    <div class="pc-block-box">

        <div class="pc-block-icon">
            <i class="fa-solid fa-mobile-screen"></i>
        </div>


        <h2>
            Mobile Only
        </h2>


        <p>
            เว็บไซต์นี้รองรับเฉพาะโทรศัพท์มือถือเท่านั้น
            <br><br>
            กรุณาเปิดผ่าน Smartphone
        </p>


        <div class="pc-device-info">
            📱 Android / iPhone
        </div>


    </div>

</div>


<style>

/* =========================
 PC BLOCK FULL SCREEN
========================= */

.pc-block-screen{

    position:fixed;

    inset:0;


    width:100%;

    height:100%;


    background:
    rgba(0,0,0,.85);


    backdrop-filter:blur(20px);


    display:none;


    justify-content:center;

    align-items:center;


    z-index:99999999;


    pointer-events:auto;

}



/* BOX */

.pc-block-box{


    width:380px;

    max-width:90%;


    padding:35px 25px;


    background:#fff;


    border-radius:25px;


    text-align:center;


    box-shadow:

    0 20px 80px rgba(0,0,0,.5);


    animation:blockShow .4s ease;


}



/* ICON */

.pc-block-icon{


    width:90px;

    height:90px;


    margin:auto;


    border-radius:50%;


    display:flex;


    align-items:center;

    justify-content:center;


    background:#2563eb;


    color:white;


    font-size:45px;


}



.pc-block-box h2{


    margin-top:20px;


    font-size:28px;


    color:#111827;


}



.pc-block-box p{


    margin-top:15px;


    color:#6b7280;


    line-height:1.6;


    font-size:15px;


}



.pc-device-info{


    margin-top:20px;


    padding:12px;


    border-radius:12px;


    background:#eff6ff;


    color:#2563eb;


    font-weight:bold;


}



@keyframes blockShow{


from{

transform:scale(.7);

opacity:0;

}


to{

transform:scale(1);

opacity:1;

}


}


/* MOBILE ซ่อน */

@media(max-width:768px){


.pc-block-screen{

    display:none !important;

}


}


</style>


<script>

(function(){


function checkDevice(){


const isMobile =
/Android|iPhone|iPad|iPod|Mobile/i
.test(
navigator.userAgent
);



const block =
document.getElementById(
"pcBlockScreen"
);



if(!isMobile && block){


block.style.display="flex";


// ล็อก scroll

document.body.style.overflow="hidden";


}


}



checkDevice();


})();


</script>