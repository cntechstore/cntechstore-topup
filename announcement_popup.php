<?php
/*
|--------------------------------------------------------------------------
| CNTECH STORE - ANNOUNCEMENT POPUP
|--------------------------------------------------------------------------
| ไม่ใช้ Database
| แก้ข้อความ/รูปใน $announcements ด้านล่าง
| แสดงทุกครั้งเมื่อเข้าเว็บ
| กด X = ปิด
| ติ๊ก "ไม่ต้องแสดงอีก 24 ชั่วโมง" = ซ่อน 24 ชั่วโมง
|--------------------------------------------------------------------------
*/

$announcements = [

    [
        'title' => 'CNTECH STORE',
        'description' => 'ยินดีต้อนรับเข้าสู่ CNTECH STORE',
        'image' => '/admin/uploads/announcements/announcement1.jpg',
        'link' => ''
    ],

    [
        'title' => 'ກຳລັງພັດທະນາ',
        'description' => 'ระบบใหม่ของ CNTECH STORE กำลังอยู่ระหว่างการพัฒนา',
        'image' => '/admin/uploads/announcements/announcement2.jpg',
        'link' => ''
    ],

    [
        'title' => 'CNTECH STORE',
        'description' => 'Computer • Mobile • Parts & Accessories',
        'image' => '/admin/uploads/announcements/announcement3.jpg',
        'link' => ''
    ]

];

if (empty($announcements)) {
    return;
}
?>

<!-- =========================================================
     CNTECH ANNOUNCEMENT POPUP
========================================================= -->

<div id="cnAnnouncementPopup">

    <div class="cn-announcement-box">

        <!-- CLOSE -->

        <button
            type="button"
            id="cnAnnouncementClose"
            class="cn-announcement-close"
            aria-label="Close">

            <i class="fa-solid fa-xmark"></i>

        </button>


        <!-- SLIDER -->

        <div class="cn-announcement-slider">

            <div
                id="cnAnnouncementTrack"
                class="cn-announcement-track">

                <?php foreach ($announcements as $index => $item): ?>

                    <div class="cn-announcement-slide">

                        <img
                            src="<?= htmlspecialchars(
                                $item['image'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            alt="<?= htmlspecialchars(
                                $item['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            class="cn-announcement-image"
                            loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
                        >


                        <!-- DARK GRADIENT -->

                        <div class="cn-announcement-gradient"></div>


                        <!-- CONTENT -->

                        <div class="cn-announcement-content">

                            <div class="cn-announcement-title">

                                <?= htmlspecialchars(
                                    $item['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </div>


                            <div class="cn-announcement-description">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $item['description'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                ) ?>

                            </div>


                            <?php if (!empty($item['link'])): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        $item['link'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="cn-announcement-button">

                                    <i class="fa-solid fa-arrow-right"></i>

                                    ອ່ານເພີ່ມ

                                </a>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>


        <!-- PREV -->

        <?php if (count($announcements) > 1): ?>

            <button
                type="button"
                id="cnAnnouncementPrev"
                class="cn-announcement-nav cn-prev">

                <i class="fa-solid fa-chevron-left"></i>

            </button>


            <!-- NEXT -->

            <button
                type="button"
                id="cnAnnouncementNext"
                class="cn-announcement-nav cn-next">

                <i class="fa-solid fa-chevron-right"></i>

            </button>


            <!-- DOTS -->

            <div
                id="cnAnnouncementDots"
                class="cn-announcement-dots">

                <?php foreach ($announcements as $i => $item): ?>

                    <button
                        type="button"
                        class="cn-announcement-dot <?= $i === 0 ? 'active' : '' ?>"
                        data-index="<?= $i ?>"
                        aria-label="Slide <?= $i + 1 ?>">
                    </button>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <!-- FOOTER -->

        <div class="cn-announcement-footer">

            <label>

                <input
                    type="checkbox"
                    id="cnAnnouncementHide24"
                >

                <span>
                    ບໍ່ຕ້ອງສະແດງອີກ 24 ຊົ່ວໂມງ
                </span>

            </label>

        </div>

    </div>

</div>


<style>

/* =========================================================
   OVERLAY
========================================================= */

#cnAnnouncementPopup{

    position:fixed;

    inset:0;

    width:100%;
    height:100%;

    display:flex;

    align-items:center;
    justify-content:center;

    padding:15px;

    background:rgba(0,0,0,.72);

    backdrop-filter:blur(8px);
    -webkit-backdrop-filter:blur(8px);

    z-index:999999;

}


/* =========================================================
   BOX
========================================================= */

.cn-announcement-box{

    position:relative;

    width:420px;

    max-width:100%;

    overflow:hidden;

    background:#050505;

    border:1px solid #3a2020;

    border-radius:22px;

    box-shadow:
        0 30px 100px rgba(0,0,0,.8),
        0 0 40px rgba(255,32,32,.10);

}


/* =========================================================
   SLIDER
========================================================= */

.cn-announcement-slider{

    width:100%;

    overflow:hidden;

}


.cn-announcement-track{

    display:flex;

    width:100%;

    transition:
        transform .45s cubic-bezier(.4,0,.2,1);

}


.cn-announcement-slide{

    position:relative;

    flex:0 0 100%;

    width:100%;

    aspect-ratio:9 / 16;

    overflow:hidden;

    background:#080808;

}


.cn-announcement-image{

    position:absolute;

    inset:0;

    width:100%;
    height:100%;

    object-fit:cover;

    display:block;

}


/* =========================================================
   GRADIENT
========================================================= */

.cn-announcement-gradient{

    position:absolute;

    inset:0;

    background:

        linear-gradient(
            to bottom,
            rgba(0,0,0,.05) 20%,
            rgba(0,0,0,.15) 40%,
            rgba(0,0,0,.92) 100%
        );

    pointer-events:none;

}


/* =========================================================
   CONTENT
========================================================= */

.cn-announcement-content{

    position:absolute;

    left:0;
    right:0;
    bottom:0;

    padding:25px 22px 75px;

    color:#fff;

}


.cn-announcement-title{

    margin-bottom:8px;

    font-size:24px;

    font-weight:900;

    line-height:1.3;

}


.cn-announcement-title::first-letter{

    color:#ff2020;

}


.cn-announcement-description{

    color:#d0d0d0;

    font-size:14px;

    line-height:1.7;

}


.cn-announcement-button{

    display:inline-flex;

    align-items:center;

    gap:8px;

    margin-top:15px;

    padding:10px 18px;

    border-radius:11px;

    background:#e51b23;

    color:#fff;

    text-decoration:none;

    font-size:13px;

    font-weight:800;

}


.cn-announcement-button:hover{

    background:#ff2020;

}


/* =========================================================
   CLOSE
========================================================= */

.cn-announcement-close{

    position:absolute;

    top:12px;
    right:12px;

    z-index:20;

    width:40px;
    height:40px;

    border:1px solid rgba(255,255,255,.15);

    border-radius:50%;

    background:rgba(0,0,0,.65);

    color:#fff;

    display:flex;

    align-items:center;
    justify-content:center;

    font-size:18px;

    cursor:pointer;

    backdrop-filter:blur(8px);

    transition:.2s;

}


.cn-announcement-close:hover{

    background:#e51b23;

    border-color:#ff2020;

}


/* =========================================================
   NAVIGATION
========================================================= */

.cn-announcement-nav{

    position:absolute;

    top:50%;

    transform:translateY(-50%);

    z-index:10;

    width:38px;
    height:38px;

    border:0;

    border-radius:50%;

    background:rgba(0,0,0,.55);

    color:#fff;

    cursor:pointer;

    backdrop-filter:blur(5px);

}


.cn-announcement-nav:hover{

    background:#e51b23;

}


.cn-prev{

    left:10px;

}


.cn-next{

    right:10px;

}


/* =========================================================
   DOTS
========================================================= */

.cn-announcement-dots{

    position:absolute;

    left:50%;

    bottom:62px;

    transform:translateX(-50%);

    z-index:15;

    display:flex;

    gap:7px;

}


.cn-announcement-dot{

    width:8px;
    height:8px;

    padding:0;

    border:0;

    border-radius:50%;

    background:#777;

    opacity:.7;

    cursor:pointer;

    transition:.2s;

}


.cn-announcement-dot.active{

    width:22px;

    border-radius:8px;

    background:#ff2020;

    opacity:1;

}


/* =========================================================
   FOOTER
========================================================= */

.cn-announcement-footer{

    position:absolute;

    left:0;
    right:0;

    bottom:0;

    z-index:20;

    padding:11px 15px;

    background:rgba(0,0,0,.88);

    border-top:1px solid rgba(255,255,255,.08);

}


.cn-announcement-footer label{

    display:flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    color:#999;

    font-size:11px;

    cursor:pointer;

}


.cn-announcement-footer input{

    width:15px;
    height:15px;

    accent-color:#e51b23;

}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width:600px){

    #cnAnnouncementPopup{

        padding:10px;

    }


    .cn-announcement-box{

        width:100%;

        max-width:390px;

        border-radius:18px;

    }


    .cn-announcement-slide{

        aspect-ratio:9 / 16;

    }


    .cn-announcement-content{

        padding:
            20px
            18px
            70px;

    }


    .cn-announcement-title{

        font-size:20px;

    }


    .cn-announcement-description{

        font-size:13px;

    }


    .cn-announcement-nav{

        width:34px;
        height:34px;

    }


    .cn-announcement-close{

        width:36px;
        height:36px;

    }

}


/* =========================================================
   SMALL MOBILE
========================================================= */

@media(max-width:360px){

    .cn-announcement-box{

        border-radius:15px;

    }


    .cn-announcement-title{

        font-size:18px;

    }


    .cn-announcement-description{

        font-size:12px;

    }

}

</style>


<script>

(function(){

    "use strict";


    /* =====================================================
       ELEMENTS
    ===================================================== */

    const popup =
        document.getElementById(
            "cnAnnouncementPopup"
        );

    if(!popup) return;


    const track =
        document.getElementById(
            "cnAnnouncementTrack"
        );


    const slides =
        document.querySelectorAll(
            ".cn-announcement-slide"
        );


    const dots =
        document.querySelectorAll(
            ".cn-announcement-dot"
        );


    const close =
        document.getElementById(
            "cnAnnouncementClose"
        );


    const hide24 =
        document.getElementById(
            "cnAnnouncementHide24"
        );


    const prev =
        document.getElementById(
            "cnAnnouncementPrev"
        );


    const next =
        document.getElementById(
            "cnAnnouncementNext"
        );


    /* =====================================================
       24 HOURS
    ===================================================== */

    const hideUntil =
        localStorage.getItem(
            "cntech_announcement_hide"
        );


    if(
        hideUntil &&
        Date.now() < Number(hideUntil)
    ){

        popup.remove();

        return;

    }


    /* =====================================================
       SLIDER
    ===================================================== */

    let current = 0;

    let timer = null;


    function showSlide(index){

        if(!slides.length) return;


        if(index < 0){

            index =
                slides.length - 1;

        }


        if(index >= slides.length){

            index = 0;

        }


        current = index;


        track.style.transform =
            "translateX(-" +
            (current * 100) +
            "%)";


        dots.forEach(
            function(dot,i){

                dot.classList.toggle(
                    "active",
                    i === current
                );

            }
        );

    }


    function startAuto(){

        if(slides.length <= 1) return;


        stopAuto();


        timer =
            setInterval(
                function(){

                    showSlide(
                        current + 1
                    );

                },
                5000
            );

    }


    function stopAuto(){

        if(timer){

            clearInterval(timer);

            timer = null;

        }

    }


    /* =====================================================
       NEXT / PREV
    ===================================================== */

    if(next){

        next.addEventListener(
            "click",
            function(){

                showSlide(
                    current + 1
                );

                startAuto();

            }
        );

    }


    if(prev){

        prev.addEventListener(
            "click",
            function(){

                showSlide(
                    current - 1
                );

                startAuto();

            }
        );

    }


    /* =====================================================
       DOTS
    ===================================================== */

    dots.forEach(
        function(dot,index){

            dot.addEventListener(
                "click",
                function(){

                    showSlide(index);

                    startAuto();

                }
            );

        }
    );


    /* =====================================================
       CLOSE
    ===================================================== */

    close.addEventListener(
        "click",
        function(){

            if(hide24 && hide24.checked){

                localStorage.setItem(
                    "cntech_announcement_hide",
                    String(
                        Date.now() +
                        24 * 60 * 60 * 1000
                    )
                );

            }

            stopAuto();

            popup.remove();

        }
    );


    /* =====================================================
       CLICK OUTSIDE
    ===================================================== */

    popup.addEventListener(
        "click",
        function(event){

            if(event.target === popup){

                if(hide24 && hide24.checked){

                    localStorage.setItem(
                        "cntech_announcement_hide",
                        String(
                            Date.now() +
                            24 * 60 * 60 * 1000
                        )
                    );

                }

                stopAuto();

                popup.remove();

            }

        }
    );


    /* =====================================================
       SWIPE MOBILE
    ===================================================== */

    let startX = 0;

    let endX = 0;


    popup.addEventListener(
        "touchstart",
        function(event){

            startX =
                event.changedTouches[0].screenX;

        },
        {
            passive:true
        }
    );


    popup.addEventListener(
        "touchend",
        function(event){

            endX =
                event.changedTouches[0].screenX;


            const distance =
                endX - startX;


            if(Math.abs(distance) < 50){

                return;

            }


            if(distance < 0){

                showSlide(
                    current + 1
                );

            }else{

                showSlide(
                    current - 1
                );

            }


            startAuto();

        },
        {
            passive:true
        }
    );


    /* =====================================================
       START
    ===================================================== */

    showSlide(0);

    startAuto();


})();

</script>