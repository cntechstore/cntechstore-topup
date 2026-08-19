<?php
/*
|--------------------------------------------------------------------------
| CNTECH STORE - ANNOUNCEMENT SLIDESHOW
|--------------------------------------------------------------------------
| File: announcement_popup.php
| Database: NO
| Auto Slide: 5 seconds
| Hide: 24 hours
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ANNOUNCEMENT DATA
|--------------------------------------------------------------------------
| เพิ่ม/ลบสไลด์ได้จากตรงนี้
|--------------------------------------------------------------------------
*/

$announcements = [

    [
        'image' => '/admin/uploads/announcements/announcement1.jpg',

        'title' => 'CNTECH STORE',

        'description' =>
            'ຍິນດີຕ້ອນຮັບສູ່ CNTECH STORE
            ຮ້ານຄ້າ Computer • Mobile • Technology',

        'button' => 'ເຂົ້າສູ່ຮ້ານ',

        'link' => '/'
    ],

    [

        'image' =>
            '/admin/uploads/announcements/announcement2.jpg',

        'title' =>
            'ກຳລັງພັດທະນາ',

        'description' =>
            'ລະບົບໃໝ່ຂອງ CNTECH STORE
            ກຳລັງພັດທະນາ ແລະ ປັບປຸງ',

        'button' =>
            'ຮັບຊາບ',

        'link' =>
            ''
    ],

    [

        'image' =>
            '/admin/uploads/announcements/announcement3.jpg',

        'title' =>
            '🔮 CNTECH ດູດວງ',

        'description' =>
            'ລະບົບດູດວງ 12 ລາສີ
            ເລືອກວັນເດືອນປີເກີດ
            ແລະ ເບິ່ງຄຳທຳນາຍ',

        'button' =>
            'ເບິ່ງດວງ',

        'link' =>
            '/fortune.php'
    ],

    [

        'image' =>
            '/admin/uploads/announcements/announcement4.jpg',

        'title' =>
            '🎫 ຫວຍພັດທະນາລາວ',

        'description' =>
            'ລະບົບນີ້ກຳລັງພັດທະນາ
            ຈະເປີດໃຫ້ໃຊ້ງານໃນອະນາຄົດ',

        'button' =>
            'ກຳລັງພັດທະນາ',

        'link' =>
            ''
    ]

];

?>

<?php if (!empty($announcements)): ?>

<div
    id="cnAnnouncementPopup"
    class="cn-announcement-popup"
    aria-hidden="true"
>

    <!-- BACKDROP -->

    <div
        class="cn-announcement-backdrop"
        onclick="cnCloseAnnouncement()"
    ></div>


    <!-- BOX -->

    <div
        class="cn-announcement-box"
        role="dialog"
        aria-modal="true"
    >

        <!-- CLOSE -->

        <button
            type="button"
            id="cnAnnouncementClose"
            class="cn-announcement-close"
            aria-label="Close"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>


        <!-- SLIDER -->

        <div class="cn-announcement-slider">

            <div
                id="cnAnnouncementTrack"
                class="cn-announcement-track"
            >

                <?php foreach ($announcements as $item): ?>

                <div class="cn-announcement-slide">

                    <!-- IMAGE -->

                    <div class="cn-announcement-image-box">

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
                            loading="eager"
                            onerror="
                                this.onerror=null;
                                this.src='/assets/no-image.png';
                            "
                        >

                    </div>


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
                            class="cn-announcement-button"
                        >

                            <?= htmlspecialchars(
                                $item['button'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                            <i class="fa-solid fa-arrow-right"></i>

                        </a>

                        <?php else: ?>

                        <button
                            type="button"
                            class="cn-announcement-button"
                            onclick="cnCloseAnnouncement()"
                        >

                            <?= htmlspecialchars(
                                $item['button'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                            <i class="fa-solid fa-check"></i>

                        </button>

                        <?php endif; ?>

                    </div>

                </div>

                <?php endforeach; ?>

            </div>

        </div>


        <!-- PREVIOUS -->

        <?php if (count($announcements) > 1): ?>

        <button
            type="button"
            id="cnAnnouncementPrev"
            class="cn-announcement-arrow cn-announcement-prev"
            aria-label="Previous"
        >

            <i class="fa-solid fa-chevron-left"></i>

        </button>


        <!-- NEXT -->

        <button
            type="button"
            id="cnAnnouncementNext"
            class="cn-announcement-arrow cn-announcement-next"
            aria-label="Next"
        >

            <i class="fa-solid fa-chevron-right"></i>

        </button>

        <?php endif; ?>


        <!-- DOTS -->

        <?php if (count($announcements) > 1): ?>

        <div
            id="cnAnnouncementDots"
            class="cn-announcement-dots"
        >

            <?php foreach ($announcements as $i => $item): ?>

            <button
                type="button"
                class="
                    cn-announcement-dot
                    <?= $i === 0 ? 'active' : '' ?>
                "
                data-index="<?= $i ?>"
                aria-label="Slide <?= $i + 1 ?>"
            ></button>

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
                    ບໍ່ສະແດງອີກ 24 ຊົ່ວໂມງ
                </span>

            </label>

        </div>

    </div>

</div>


<style>

/*
|--------------------------------------------------------------------------
| OVERLAY
|--------------------------------------------------------------------------
*/

.cn-announcement-popup{

    position:fixed;

    inset:0;

    z-index:999999;

    display:none;

    align-items:center;

    justify-content:center;

    padding:15px;

}


/*
|--------------------------------------------------------------------------
| SHOW
|--------------------------------------------------------------------------
*/

.cn-announcement-popup.show{

    display:flex;

}


/*
|--------------------------------------------------------------------------
| BACKDROP
|--------------------------------------------------------------------------
*/

.cn-announcement-backdrop{

    position:absolute;

    inset:0;

    background:
        rgba(0,0,0,.72);

    backdrop-filter:
        blur(8px);

    -webkit-backdrop-filter:
        blur(8px);

}


/*
|--------------------------------------------------------------------------
| BOX
|--------------------------------------------------------------------------
*/

.cn-announcement-box{

    position:relative;

    z-index:2;

    width:420px;

    max-width:100%;

    overflow:hidden;

    background:#090909;

    border:1px solid
        rgba(255,32,32,.35);

    border-radius:24px;

    box-shadow:

        0 25px 80px
        rgba(0,0,0,.75),

        0 0 40px
        rgba(255,32,32,.12);

    animation:
        cnAnnouncementIn
        .3s ease;

}


@keyframes cnAnnouncementIn{

    from{

        opacity:0;

        transform:
            scale(.92)
            translateY(20px);

    }

    to{

        opacity:1;

        transform:
            scale(1)
            translateY(0);

    }

}


/*
|--------------------------------------------------------------------------
| SLIDER
|--------------------------------------------------------------------------
*/

.cn-announcement-slider{

    position:relative;

    width:100%;

    overflow:hidden;

}


.cn-announcement-track{

    display:flex;

    width:100%;

    transition:
        transform .45s
        cubic-bezier(.4,0,.2,1);

}


.cn-announcement-slide{

    position:relative;

    min-width:100%;

    width:100%;

    aspect-ratio:9 / 16;

    overflow:hidden;

}


/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

.cn-announcement-image-box{

    position:absolute;

    inset:0;

    background:#080808;

}


.cn-announcement-image{

    width:100%;

    height:100%;

    display:block;

    object-fit:cover;

}


/*
|--------------------------------------------------------------------------
| DARK GRADIENT
|--------------------------------------------------------------------------
*/

.cn-announcement-slide::after{

    content:"";

    position:absolute;

    inset:0;

    pointer-events:none;

    background:

        linear-gradient(
            to bottom,
            rgba(0,0,0,.05),
            transparent 35%,
            rgba(0,0,0,.92) 100%
        );

}


/*
|--------------------------------------------------------------------------
| CONTENT
|--------------------------------------------------------------------------
*/

.cn-announcement-content{

    position:absolute;

    left:0;

    right:0;

    bottom:0;

    z-index:3;

    padding:28px 22px 65px;

    color:#fff;

}


.cn-announcement-title{

    margin-bottom:8px;

    color:#fff;

    font-size:24px;

    font-weight:900;

    line-height:1.3;

}


.cn-announcement-description{

    color:#ddd;

    font-size:13px;

    line-height:1.7;

}


/*
|--------------------------------------------------------------------------
| BUTTON
|--------------------------------------------------------------------------
*/

.cn-announcement-button{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:8px;

    margin-top:15px;

    min-height:42px;

    padding:10px 17px;

    border:0;

    border-radius:11px;

    background:
        linear-gradient(
            135deg,
            #e51b23,
            #ff3030
        );

    color:#fff;

    text-decoration:none;

    font-size:13px;

    font-weight:900;

    cursor:pointer;

    box-shadow:
        0 7px 22px
        rgba(255,32,32,.2);

}


/*
|--------------------------------------------------------------------------
| CLOSE
|--------------------------------------------------------------------------
*/

.cn-announcement-close{

    position:absolute;

    top:12px;

    right:12px;

    z-index:20;

    width:38px;

    height:38px;

    display:flex;

    align-items:center;

    justify-content:center;

    border:1px solid
        rgba(255,255,255,.15);

    border-radius:11px;

    background:
        rgba(0,0,0,.55);

    color:#fff;

    font-size:17px;

    cursor:pointer;

    backdrop-filter:blur(8px);

}


.cn-announcement-close:hover{

    background:#e51b23;

    border-color:#e51b23;

}


/*
|--------------------------------------------------------------------------
| ARROWS
|--------------------------------------------------------------------------
*/

.cn-announcement-arrow{

    position:absolute;

    top:45%;

    z-index:10;

    width:38px;

    height:38px;

    display:flex;

    align-items:center;

    justify-content:center;

    border:1px solid
        rgba(255,255,255,.18);

    border-radius:50%;

    background:
        rgba(0,0,0,.55);

    color:#fff;

    cursor:pointer;

    backdrop-filter:blur(6px);

}


.cn-announcement-arrow:hover{

    background:#e51b23;

}


.cn-announcement-prev{

    left:10px;

}


.cn-announcement-next{

    right:10px;

}


/*
|--------------------------------------------------------------------------
| DOTS
|--------------------------------------------------------------------------
*/

.cn-announcement-dots{

    position:absolute;

    left:50%;

    bottom:52px;

    z-index:10;

    transform:
        translateX(-50%);

    display:flex;

    align-items:center;

    gap:7px;

}


.cn-announcement-dot{

    width:7px;

    height:7px;

    padding:0;

    border:0;

    border-radius:50%;

    background:#fff;

    opacity:.4;

    cursor:pointer;

    transition:.2s;

}


.cn-announcement-dot.active{

    width:22px;

    border-radius:5px;

    background:#ff2020;

    opacity:1;

}


/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

.cn-announcement-footer{

    position:relative;

    z-index:10;

    display:flex;

    align-items:center;

    justify-content:center;

    min-height:48px;

    padding:10px;

    background:
        rgba(10,10,10,.96);

    border-top:1px solid #222;

}


.cn-announcement-footer label{

    display:flex;

    align-items:center;

    gap:8px;

    color:#888;

    font-size:11px;

    cursor:pointer;

}


.cn-announcement-footer input{

    width:16px;

    height:16px;

    accent-color:#e51b23;

}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media(max-width:600px){

    .cn-announcement-popup{

        padding:0;

    }


    .cn-announcement-box{

        width:100%;

        max-width:390px;

        border-radius:22px;

    }


    .cn-announcement-slide{

        aspect-ratio:9 / 16;

    }


    .cn-announcement-title{

        font-size:21px;

    }


    .cn-announcement-description{

        font-size:12px;

    }


    .cn-announcement-content{

        padding:
            22px
            18px
            60px;

    }

}


/*
|--------------------------------------------------------------------------
| SMALL PHONE
|--------------------------------------------------------------------------
*/

@media(max-height:650px){

    .cn-announcement-box{

        max-height:94vh;

    }


    .cn-announcement-slide{

        aspect-ratio:auto;

        height:calc(94vh - 48px);

    }

}


/*
|--------------------------------------------------------------------------
| REDUCED MOTION
|--------------------------------------------------------------------------
*/

@media(prefers-reduced-motion:reduce){

    .cn-announcement-track{

        transition:none;

    }

}

</style>


<script>

(function(){

    /*
    |--------------------------------------------------------------------------
    | CONFIG
    |--------------------------------------------------------------------------
    */

    const STORAGE_KEY =
        'cntech_announcement_hide';

    const HIDE_TIME =
        24 * 60 * 60 * 1000;

    const AUTO_TIME =
        5000;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const popup =
        document.getElementById(
            'cnAnnouncementPopup'
        );

    if(!popup) return;


    const track =
        document.getElementById(
            'cnAnnouncementTrack'
        );


    const slides =
        document.querySelectorAll(
            '.cn-announcement-slide'
        );


    const dots =
        document.querySelectorAll(
            '.cn-announcement-dot'
        );


    const prev =
        document.getElementById(
            'cnAnnouncementPrev'
        );


    const next =
        document.getElementById(
            'cnAnnouncementNext'
        );


    const close =
        document.getElementById(
            'cnAnnouncementClose'
        );


    const hide24 =
        document.getElementById(
            'cnAnnouncementHide24'
        );


    let index = 0;

    let timer = null;


    /*
    |--------------------------------------------------------------------------
    | CHECK 24 HOURS
    |--------------------------------------------------------------------------
    */

    function canShow(){

        try{

            const value =
                localStorage.getItem(
                    STORAGE_KEY
                );

            if(!value){

                return true;

            }


            const expire =
                parseInt(
                    value,
                    10
                );


            if(
                isNaN(expire) ||
                Date.now() >= expire
            ){

                localStorage.removeItem(
                    STORAGE_KEY
                );

                return true;

            }


            return false;

        }catch(e){

            return true;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    function openPopup(){

        if(!canShow()){

            popup.remove();

            return;

        }


        popup.classList.add('show');

        popup.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';


        startAuto();

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    window.cnCloseAnnouncement =
        function(){

            if(
                hide24 &&
                hide24.checked
            ){

                try{

                    localStorage.setItem(

                        STORAGE_KEY,

                        String(
                            Date.now() +
                            HIDE_TIME
                        )

                    );

                }catch(e){}

            }


            stopAuto();


            popup.classList.remove(
                'show'
            );


            popup.setAttribute(
                'aria-hidden',
                'true'
            );


            document.body.style.overflow =
                '';


            setTimeout(
                function(){

                    popup.remove();

                },
                250
);

        };


    /*
    |--------------------------------------------------------------------------
    | SLIDE
    |--------------------------------------------------------------------------
    */

    function showSlide(i){

        if(!slides.length) return;


        if(i < 0){

            i =
                slides.length - 1;

        }


        if(i >= slides.length){

            i = 0;

        }


        index = i;


        track.style.transform =
            'translateX(-' +
            (index * 100) +
            '%)';


        dots.forEach(
            function(dot, n){

                dot.classList.toggle(
                    'active',
                    n === index
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NEXT
    |--------------------------------------------------------------------------
    */

    function nextSlide(){

        showSlide(
            index + 1
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PREVIOUS
    |--------------------------------------------------------------------------
    */

    function previousSlide(){

        showSlide(
            index - 1
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AUTO PLAY
    |--------------------------------------------------------------------------
    */

    function startAuto(){

        if(slides.length <= 1){

            return;

        }


        stopAuto();


        timer =
            setInterval(
                nextSlide,
                AUTO_TIME
            );

    }


    function stopAuto(){

        if(timer){

            clearInterval(
                timer
            );

            timer = null;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BUTTONS
    |--------------------------------------------------------------------------
    */

    if(next){

        next.onclick =
            function(){

                nextSlide();

                startAuto();

            };

    }


    if(prev){

        prev.onclick =
            function(){

                previousSlide();

                startAuto();

            };

    }


    if(close){

        close.onclick =
            function(){

                cnCloseAnnouncement();

            };

    }


    /*
    |--------------------------------------------------------------------------
    | DOTS
    |--------------------------------------------------------------------------
    */

    dots.forEach(
        function(dot){

            dot.onclick =
                function(){

                    const i =
                        parseInt(
                            this.dataset.index,
                            10
                        );

                    showSlide(i);

                    startAuto();

                };

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KEYBOARD
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function(e){

            if(
                !popup.classList.contains(
                    'show'
                )
            ){

                return;

            }


            if(e.key === 'Escape'){

                cnCloseAnnouncement();

            }


            if(e.key === 'ArrowRight'){

                nextSlide();

                startAuto();

            }


            if(e.key === 'ArrowLeft'){

                previousSlide();

                startAuto();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SWIPE MOBILE
    |--------------------------------------------------------------------------
    */

    let touchStartX = 0;

    let touchEndX = 0;


    popup.addEventListener(
        'touchstart',
        function(e){

            touchStartX =
                e.changedTouches[0].screenX;

        },
        {
            passive:true
        }
    );


    popup.addEventListener(
        'touchend',
        function(e){

            touchEndX =
                e.changedTouches[0].screenX;


            const distance =
                touchEndX -
                touchStartX;


            if(Math.abs(distance) < 50){

                return;

            }


            if(distance < 0){

                nextSlide();

            }else{

                previousSlide();

            }


            startAuto();

        },
        {
            passive:true
        }
    );


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function(){

            setTimeout(
                openPopup,
                300
            );

        }
    );

})();

</script>

<?php endif; ?>