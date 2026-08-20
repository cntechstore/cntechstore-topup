<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="lo">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="
width=device-width,
initial-scale=1.0,
maximum-scale=1.0,
user-scalable=no,
viewport-fit=cover">


<title>
CNTECH CAMERA
</title>


<link
href="
https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">


<style>


*{

margin:0;
padding:0;
box-sizing:border-box;

-webkit-tap-highlight-color:transparent;

}



html,body{

width:100%;
height:100%;

overflow:hidden;

background:#000;

font-family:Arial,sans-serif;

user-select:none;

}




/* CAMERA */

#camera{

position:fixed;

top:0;
left:0;

width:100%;
height:100%;

object-fit:cover;

z-index:1;

}




/* HEADER */

.camera-header{


position:fixed;

top:0;
left:0;
right:0;


padding:

calc(env(safe-area-inset-top) + 15px)
15px
15px;


display:flex;

justify-content:space-between;

align-items:center;


z-index:100;


background:
linear-gradient(
rgba(0,0,0,.7),
transparent
);


}



.logo{


font-size:22px;

font-weight:bold;

color:#ff0033;


}



.icon-btn{


width:45px;

height:45px;

border-radius:50%;


border:none;


background:
rgba(0,0,0,.5);


color:white;


font-size:20px;


}





/* RIGHT MENU */


.tools{


position:fixed;


right:15px;


top:120px;


z-index:100;


display:flex;

flex-direction:column;

gap:20px;


}



.tool{


width:55px;

height:55px;


border-radius:50%;


background:
rgba(0,0,0,.45);


display:flex;

align-items:center;

justify-content:center;


color:white;


font-size:22px;


}




/* FPS */


.fps{


position:fixed;


top:90px;


left:50%;


transform:translateX(-50%);


background:
rgba(0,0,0,.5);


padding:8px 15px;


border-radius:20px;


color:white;


z-index:100;


}





</style>

</head>


<body>



<video

id="camera"

autoplay

playsinline

muted>

</video>



<div class="camera-header">


<button
class="icon-btn"
onclick="history.back()">

<i class="fa-solid fa-arrow-left"></i>

</button>



<div class="logo">

CNTECH CAMERA

</div>



<button
class="icon-btn">


<i class="fa-solid fa-bolt"></i>


</button>



</div>



<div class="fps">

4K • 60 FPS

</div>



<div class="tools">


<div class="tool">

<i class="fa-solid fa-wand-magic-sparkles"></i>

</div>


<div class="tool">

<i class="fa-solid fa-palette"></i>

</div>



<div class="tool">

<i class="fa-solid fa-sliders"></i>

</div>



</div>

<!-- ==========================
 ZOOM CONTROL
========================== -->

<div class="zoom-box">


<button onclick="setZoom(1)">
1x
</button>


<button onclick="setZoom(2)">
2x
</button>


<button onclick="setZoom(5)">
5x
</button>


<button onclick="setZoom(10)">
10x
</button>


<button onclick="setZoom(50)">
50x
</button>


</div>







<style>


.zoom-box{


position:fixed;

bottom:180px;

left:50%;

transform:translateX(-50%);


z-index:200;


display:flex;

gap:8px;


}



.zoom-box button{


border:none;


background:

rgba(0,0,0,.55);


color:white;


padding:8px 14px;


border-radius:20px;


font-size:14px;


}






/* CAMERA BOTTOM */


.camera-bottom{


position:fixed;

bottom:0;

left:0;

right:0;


padding:

20px

20px

calc(env(safe-area-inset-bottom) + 20px);


z-index:200;


background:

linear-gradient(
transparent,
rgba(0,0,0,.8)
);


}



.camera-control{


display:flex;

justify-content:center;

align-items:center;

gap:40px;


}





.gallery-btn{


width:60px;

height:60px;


border-radius:50%;


border:none;


background:#222;


color:white;


font-size:25px;


}



.record-btn{


width:90px;

height:90px;


border-radius:50%;


background:#ff0033;


border:

7px solid white;


}




.record-btn.active{


animation:

pulse 1s infinite;


}



@keyframes pulse{


50%{

transform:scale(1.1);

}


}






.flip-btn{


width:60px;

height:60px;


border-radius:50%;


border:none;


background:#222;


color:white;


font-size:25px;


}






/* NAVBAR */


.bottom-nav{


position:fixed;

left:0;

right:0;

bottom:0;


height:70px;


background:

rgba(15,15,15,.95);


display:flex;


justify-content:space-around;


align-items:center;


z-index:300;


padding-bottom:

env(safe-area-inset-bottom);


}




.nav-item{


color:#aaa;


text-decoration:none;


display:flex;


flex-direction:column;


align-items:center;


font-size:12px;


gap:5px;


}




.nav-item i{


font-size:22px;


}




.nav-active{


color:#ff0033;


}



</style>







<!-- ==========================
 CAMERA CONTROL
========================== -->


<div class="camera-bottom">


<div class="camera-control">



<button

class="gallery-btn"

onclick="pickVideo()">

<i class="fa-solid fa-image"></i>

</button>





<button

id="recordBtn"

class="record-btn">

</button>





<button

class="flip-btn"

onclick="switchCamera()">

<i class="fa-solid fa-camera-rotate"></i>

</button>



</div>


</div>







<!-- FILE PICKER -->


<input

type="file"

id="videoPicker"

accept="video/*"

style="display:none">







<!-- ==========================
 TIKTOK NAVBAR
========================== -->


<div class="bottom-nav">


<a

href="../index.php"

class="nav-item">


<i class="fa-solid fa-house"></i>


<span>

Home

</span>


</a>





<a

href="reels.php"

class="nav-item">


<i class="fa-solid fa-play"></i>


<span>

Reels

</span>


</a>





<a

href="camera.php"

class="nav-item nav-active">


<i class="fa-solid fa-plus"></i>


<span>

Create

</span>


</a>





<a

href="../profile.php"

class="nav-item">


<i class="fa-solid fa-user"></i>


<span>

Profile

</span>


</a>



</div>

<script>


let stream = null;

let mediaRecorder = null;

let chunks = [];

let recording = false;


let facingMode = "environment";



const camera =
document.getElementById("camera");



const recordBtn =
document.getElementById("recordBtn");





// ============================
// START CAMERA
// ============================


async function startCamera(){


try{


if(stream){


stream
.getTracks()
.forEach(
track=>track.stop()
);


}




stream =

await navigator
.mediaDevices
.getUserMedia({


video:{


facingMode:facingMode,


width:{
ideal:1080
},


height:{
ideal:1920
}


},


audio:true



});



camera.srcObject =
stream;



}

catch(error){


console.log(error);


alert(
"Camera permission denied"
);



}



}




startCamera();







// ============================
// SWITCH CAMERA
// ============================


async function switchCamera(){



if(stream){


stream
.getTracks()
.forEach(
track=>track.stop()
);


}




facingMode =

facingMode === "environment"

?

"user"

:

"environment";




await startCamera();



}







// ============================
// RECORD VIDEO
// ============================



recordBtn.onclick=function(){



if(!recording){


startRecord();



}

else{


stopRecord();


}



};







function startRecord(){



if(!stream){


alert(
"Camera not ready"
);


return;


}



chunks=[];



mediaRecorder =

new MediaRecorder(
stream,
{


mimeType:
"video/webm"


}

);





mediaRecorder
.ondataavailable=function(e){



if(e.data.size>0){


chunks.push(e.data);


}



};







mediaRecorder
.onstop=function(){



const blob =

new Blob(
chunks,
{

type:
"video/webm"

}

);




saveVideoDB(blob);



};





mediaRecorder.start();



recording=true;


recordBtn.classList.add(
"active"
);



}





function stopRecord(){



if(
mediaRecorder &&
recording
){



mediaRecorder.stop();



recording=false;



recordBtn.classList.remove(
"active"
);



}



}






// ============================
// PICK VIDEO
// ============================



function pickVideo(){



document
.getElementById(
"videoPicker"
)
.click();



}





document

.getElementById(
"videoPicker"
)

.onchange=function(e){



let file =
e.target.files[0];



if(file){



saveVideoDB(file);



}



};







// ============================
// INDEXED DB
// NO STORAGE LIMIT
// ============================



function saveVideoDB(file){



let request =

indexedDB.open(
"CNTECH_CAMERA",
1
);




request.onupgradeneeded=function(e){



let db =
e.target.result;




if(
!db.objectStoreNames.contains(
"videos"
)
){



db.createObjectStore(
"videos"
);



}



};






request.onsuccess=function(e){



let db =
e.target.result;




let tx =

db.transaction(
"videos",
"readwrite"
);




tx.objectStore(
"videos"
)
.put(
file,
"current"
);




tx.oncomplete=function(){



window.location.href =
"edit-video.php";



};




};




}







// ============================
// ZOOM
// ============================


async function setZoom(level){



if(!stream)
return;




let track =

stream
.getVideoTracks()[0];



let capabilities =

track.getCapabilities();




if(
capabilities.zoom
){


track.applyConstraints({


advanced:[

{

zoom:
level

}

]


});


}



}

// ==================================
// CNTECH CAMERA PRO EFFECT SYSTEM
// ==================================


let currentFilter = "none";

let flashStatus = false;

let timerSecond = 0;

let currentFPS = 60;



// ================================
// FILTER
// ================================


function changeFilter(){


const filters=[

"none",

"contrast(120%)",

"brightness(120%)",

"saturate(150%)",

"hue-rotate(90deg)",

"grayscale(100%)"

];



let index =
filters.indexOf(
currentFilter
);



index++;



if(index >= filters.length){

index=0;

}



currentFilter =
filters[index];



camera.style.filter =
currentFilter;



}






// ================================
// BEAUTY MODE
// ================================


function beautyMode(){



if(
camera.style.filter
){

camera.style.filter +=
" blur(0.5px)";

}

else{

camera.style.filter =
"saturate(130%) blur(0.5px)";

}



}








// ================================
// FLASH
// ================================


async function toggleFlash(){



if(!stream)
return;




let track =

stream
.getVideoTracks()[0];



let capabilities =

track.getCapabilities();



if(
capabilities.torch
){



flashStatus =
!flashStatus;



await track.applyConstraints({

advanced:[

{

torch:
flashStatus

}

]

});



}

else{


alert(
"Flash not supported"
);


}



}








// ================================
// FPS MODE
// ================================


async function changeFPS(fps){



currentFPS=fps;



if(!stream)
return;



let track =

stream
.getVideoTracks()[0];



try{


await track.applyConstraints({

frameRate:{
ideal:fps
}

});



document
.querySelector(".fps")
.innerHTML =

"1080P • "
+
fps
+
" FPS";



}

catch(e){

console.log(e);

}



}







// ================================
// TIMER RECORD
// ================================


function setTimer(sec){


timerSecond=sec;



}






async function startRecordWithTimer(){



if(timerSecond>0){



for(
let i=timerSecond;
i>0;
i--
){



console.log(i);


await new Promise(

resolve=>

setTimeout(
resolve,
1000
)

);



}



}



startRecord();



}









// ================================
// PINCH ZOOM
// ================================


let startDistance = 0;

let zoomValue = 1;



camera.addEventListener(

"touchstart",

(e)=>{


if(
e.touches.length===2
){



startDistance =

getDistance(
e.touches[0],
e.touches[1]
);



}



}

);






camera.addEventListener(

"touchmove",

(e)=>{


if(
e.touches.length===2
){



let distance =

getDistance(
e.touches[0],
e.touches[1]
);



if(
distance > startDistance
){


zoomValue +=0.5;


}

else{


zoomValue -=0.5;


}



if(
zoomValue <1
)
zoomValue=1;



if(
zoomValue>50
)
zoomValue=50;




setZoom(
zoomValue
);



}



}

);







function getDistance(a,b){


return Math.sqrt(

Math.pow(
a.clientX-b.clientX,
2
)

+

Math.pow(
a.clientY-b.clientY,
2
)


);


}








// ================================
// RESOLUTION
// ================================


async function setQuality(type){



if(!stream)
return;



let track =

stream
.getVideoTracks()[0];



let config={};



switch(type){


case "720":

config={

width:1280,

height:720

};

break;



case "1080":

config={

width:1920,

height:1080

};

break;




case "4K":

config={

width:3840,

height:2160

};

break;



}




try{


await track.applyConstraints(
config
);



}


catch(e){


console.log(e);


}



}



// ==================================
// CNTECH CAMERA FINISH SYSTEM
// ==================================


// ปิดกล้องก่อนออกหน้า

function stopCamera(){


if(stream){


stream
.getTracks()
.forEach(
track=>{

track.stop();

}

);


stream=null;


}


}




// ก่อนเปลี่ยนหน้า edit

async function goEdit(){



stopCamera();



window.location.href =
"edit-video.php";



}





// ==================================
// INDEXED DB WITH INFO
// ==================================


function saveVideoDB(file){



let request =

indexedDB.open(
"CNTECH_CAMERA",
1
);



request.onupgradeneeded=function(e){


let db=e.target.result;



if(
!db.objectStoreNames.contains(
"videos"
)
){


db.createObjectStore(
"videos"
);


}


};





request.onsuccess=function(e){



let db =
e.target.result;



let transaction =

db.transaction(
"videos",
"readwrite"
);



let store =

transaction.objectStore(
"videos"
);




store.put(

{

file:file,

name:file.name || 
"camera-video.webm",


type:file.type,


size:file.size,


created:
Date.now(),


user_id:
"<?= $user_id ?>"


},


"current"

);





transaction.oncomplete=function(){



stopCamera();



window.location.href =
"edit-video.php";



};



};



}








// ==================================
// CLEAN CAMERA WHEN LEAVE
// ==================================


window.addEventListener(

"beforeunload",

()=>{


stopCamera();


}

);







// ==================================
// ERROR HANDLER
// ==================================


window.onerror=function(

message,

source,

line

){


console.log(
"CNTECH CAMERA ERROR",
message,
line
);


};

</script>


</body>


</html>