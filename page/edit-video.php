<?php
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if(empty($user_id)){
    die("Login Required");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,
initial-scale=1.0,
maximum-scale=1.0,
user-scalable=no,
viewport-fit=cover">

<title>

CNTECH Video Editor Pro

</title>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../editor.css">

</head>

<body>

<div class="editor-container">

<!-- TOP BAR -->

<div class="editor-topbar">

<button
class="top-btn"
onclick="history.back()">

<i class="fa-solid fa-arrow-left"></i>

</button>

<div class="editor-logo">

CNTECH EDITOR

</div>

<button
class="top-btn"
id="saveDraftBtn">

<i class="fa-solid fa-floppy-disk"></i>

</button>

</div>

<!-- VIDEO AREA -->

<div class="video-wrapper">

<video

id="videoPreview"

class="video-preview"

playsinline

controls>

</video>

<div class="video-overlay">

<div
id="textLayer"
class="text-layer">

</div>

</div>

<div
class="video-loading"
id="videoLoading">

<div
class="spinner-border text-danger">

</div>

</div>

</div>

<!-- TOOLBAR -->

<div class="toolbar">

<button
class="tool-btn"
id="trimBtn">

<i class="fa-solid fa-scissors"></i>

<span>

Trim

</span>

</button>

<button
class="tool-btn"
id="musicBtn">

<i class="fa-solid fa-music"></i>

<span>

Music

</span>

</button>

<button
class="tool-btn"
id="textBtn">

<i class="fa-solid fa-font"></i>

<span>

Text

</span>

</button>

<button
class="tool-btn"
id="filterBtn">

<i class="fa-solid fa-wand-magic-sparkles"></i>

<span>

Filter

</span>

</button>

<button
class="tool-btn"
id="speedBtn">

<i class="fa-solid fa-gauge-high"></i>

<span>

Speed

</span>

</button>

<button
class="tool-btn"
id="coverBtn">

<i class="fa-solid fa-image"></i>

<span>

Cover

</span>

</button>

</div>

<!-- TIMELINE -->

<div class="timeline-container">

<div
class="timeline-header">

<span>

Timeline

</span>

<span
id="videoDuration">

00:00

</span>

</div>

<div
class="timeline-wrapper">

<div
class="trim-handle start-handle"
id="startHandle">

</div>

<div
class="timeline-track"
id="timelineTrack">

<div
class="timeline-progress"
id="timelineProgress">

</div>

</div>

<div
class="trim-handle end-handle"
id="endHandle">

</div>

</div>

</div>

<!-- BOTTOM PANEL -->

<div
class="editor-panel"
id="editorPanel">

<!-- Dynamic Content -->

</div>

<!-- NEXT BUTTON -->

<div class="publish-bar">

<button
class="next-btn"
id="nextBtn">

Next

<i class="fa-solid fa-arrow-right"></i>

</button>

</div>

</div>

<!-- MUSIC MODAL -->

<div
class="modal fade"
id="musicModal">

<div
class="modal-dialog modal-fullscreen">

<div
class="modal-content bg-dark text-white">

<div
class="modal-header">

<h5>

Select Music

</h5>

<button
class="btn-close btn-close-white"
data-bs-dismiss="modal">

</button>

</div>

<div
class="modal-body">

<div
id="musicList">

</div>

</div>

</div>

</div>

</div>

<!-- TEXT MODAL -->

<div
class="modal fade"
id="textModal">

<div
class="modal-dialog">

<div
class="modal-content bg-dark text-white">

<div
class="modal-header">

<h5>

Add Text

</h5>

</div>

<div
class="modal-body">

<input

type="text"

id="textInput"

class="form-control"

placeholder="Enter text">

</div>

<div
class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal">

Cancel

</button>

<button
class="btn btn-danger"
id="applyTextBtn">

Apply

</button>

</div>

</div>

</div>

</div>

<!-- HIDDEN INPUTS -->

<input
type="file"
id="coverInput"
accept="image/*"
hidden>

<input
type="file"
id="musicInput"
accept="audio/*"
hidden>

<!-- LIBRARIES -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

<script>

const tempVideo =

sessionStorage.getItem(
"tempVideo"
);

const videoPreview =

document.getElementById(
"videoPreview"
);

if(tempVideo){

videoPreview.src =
tempVideo;

}

</script>

<script>

/* =========================
   CNTECH EDITOR PRO
   PART 3/5
========================= */

const video =
document.getElementById(
"videoPreview"
);

const timelineProgress =
document.getElementById(
"timelineProgress"
);

const durationText =
document.getElementById(
"videoDuration"
);

const startHandle =
document.getElementById(
"startHandle"
);

const endHandle =
document.getElementById(
"endHandle"
);

const timelineTrack =
document.getElementById(
"timelineTrack"
);

let trimStart = 0;
let trimEnd = 0;

let duration = 0;

let currentSpeed = 1;

let isDraggingStart = false;
let isDraggingEnd = false;

/* =========================
FORMAT TIME
========================= */

function formatTime(sec){

if(!sec) return "00:00";

let m =
Math.floor(sec/60);

let s =
Math.floor(sec%60);

return String(m)
.padStart(2,"0")

+

":"

+

String(s)
.padStart(2,"0");

}

/* =========================
VIDEO LOADED
========================= */

video.addEventListener(
"loadedmetadata",
()=>{

duration =
video.duration;

trimEnd =
duration;

durationText.innerText =
formatTime(duration);

updateHandles();

}
);

/* =========================
PROGRESS BAR
========================= */

video.addEventListener(
"timeupdate",
()=>{

if(duration <= 0)
return;

const percent =

(video.currentTime
/
duration)

*
100;

timelineProgress
.style.width =

percent + "%";

if(video.currentTime
>= trimEnd){

video.pause();

}

}
);

/* =========================
PLAY PAUSE
========================= */

video.addEventListener(
"click",
()=>{

if(video.paused){

video.play();

}else{

video.pause();

}

}
);

/* =========================
UPDATE HANDLE UI
========================= */

function updateHandles(){

const trackWidth =

timelineTrack
.offsetWidth;

const startPercent =

(trimStart
/
duration)
*
100;

const endPercent =

(trimEnd
/
duration)
*
100;

startHandle.style.left =

startPercent + "%";

endHandle.style.left =

endPercent + "%";

}

/* =========================
TRIM START
========================= */

startHandle.addEventListener(
"touchstart",
()=>{

isDraggingStart = true;

}
);

startHandle.addEventListener(
"mousedown",
()=>{

isDraggingStart = true;

}
);

/* =========================
TRIM END
========================= */

endHandle.addEventListener(
"touchstart",
()=>{

isDraggingEnd = true;

}
);

endHandle.addEventListener(
"mousedown",
()=>{

isDraggingEnd = true;

}
);

/* =========================
MOVE
========================= */

document.addEventListener(
"touchmove",
moveHandle
);

document.addEventListener(
"mousemove",
moveHandle
);

function moveHandle(e){

if(
!isDraggingStart
&&
!isDraggingEnd
){
return;
}

const touch =

e.touches
?
e.touches[0]
:
e;

const rect =

timelineTrack
.getBoundingClientRect();

let x =

touch.clientX
-
rect.left;

if(x < 0)
x = 0;

if(x > rect.width)
x = rect.width;

const percent =

x
/
rect.width;

const seconds =

percent
*
duration;

/* START */

if(isDraggingStart){

if(seconds < trimEnd){

trimStart =
seconds;

}

}

/* END */

if(isDraggingEnd){

if(seconds > trimStart){

trimEnd =
seconds;

}

}

updateHandles();

}

/* =========================
STOP DRAG
========================= */

document.addEventListener(
"mouseup",
()=>{

isDraggingStart = false;
isDraggingEnd = false;

}
);

document.addEventListener(
"touchend",
()=>{

isDraggingStart = false;
isDraggingEnd = false;

}
);

/* =========================
SPEED
========================= */

function setSpeed(speed){

currentSpeed =
speed;

video.playbackRate =
speed;

}

window.setSpeed =
setSpeed;

/* =========================
SEEK
========================= */

timelineTrack.addEventListener(
"click",
(e)=>{

const rect =

timelineTrack
.getBoundingClientRect();

const x =

e.clientX
-
rect.left;

const percent =

x
/
rect.width;

video.currentTime =

percent
*
duration;

}
);

/* =========================
EDITOR DATA
========================= */

const editorData = {

trimStart:0,
trimEnd:0,

speed:1,

music:null,

text:null,

filter:"normal",

brightness:100,

contrast:100,

saturation:100,

cover:null

};

/* =========================
SAVE CURRENT
========================= */

function saveEditorState(){

editorData.trimStart =
trimStart;

editorData.trimEnd =
trimEnd;

editorData.speed =
currentSpeed;

sessionStorage.setItem(

"editorData",

JSON.stringify(
editorData
)

);

}

/* =========================
AUTO SAVE
========================= */

setInterval(
saveEditorState,
1000
);

/* =========================
LOAD
========================= */

const saved =

sessionStorage.getItem(
"editorData"
);

if(saved){

try{

const data =

JSON.parse(saved);

currentSpeed =
data.speed || 1;

video.playbackRate =
currentSpeed;

}
catch(e){}

}

/* =========================
NEXT BUTTON
========================= */

document
.getElementById(
"nextBtn"
)

.addEventListener(
"click",
()=>{

saveEditorState();

window.location.href =

"publish.php";

}
);

</script>

<script>

/* =========================
   CNTECH EDITOR PRO
   PART 4/5
========================= */

/* =========================
TEXT OVERLAY
========================= */

const textLayer =
document.getElementById(
"textLayer"
);

const textInput =
document.getElementById(
"textInput"
);

const applyTextBtn =
document.getElementById(
"applyTextBtn"
);

let overlayText = "";

if(applyTextBtn){

applyTextBtn.onclick = ()=>{

overlayText =
textInput.value;

textLayer.innerText =
overlayText;

editorData.text =
overlayText;

saveEditorState();

};

}

/* =========================
FILTER SYSTEM
========================= */

let brightness = 100;
let contrast = 100;
let saturation = 100;

let rotateDeg = 0;

let flipX = 1;

function applyFilter(){

video.style.filter =

`
brightness(${brightness}%)
contrast(${contrast}%)
saturate(${saturation}%)
`;

video.style.transform =

`
rotate(${rotateDeg}deg)
scaleX(${flipX})
`;

editorData.brightness =
brightness;

editorData.contrast =
contrast;

editorData.saturation =
saturation;

saveEditorState();

}

window.setBrightness =
(v)=>{

brightness = v;
applyFilter();

};

window.setContrast =
(v)=>{

contrast = v;
applyFilter();

};

window.setSaturation =
(v)=>{

saturation = v;
applyFilter();

};

/* =========================
PRESET FILTERS
========================= */

function setFilter(type){

editorData.filter =
type;

switch(type){

case "normal":

brightness=100;
contrast=100;
saturation=100;

break;

case "cinematic":

brightness=90;
contrast=120;
saturation=90;

break;

case "vivid":

brightness=110;
contrast=115;
saturation=140;

break;

case "dark":

brightness=80;
contrast=130;
saturation=80;

break;

case "warm":

brightness=105;
contrast=105;
saturation=125;

break;

}

applyFilter();

}

window.setFilter =
setFilter;

/* =========================
ROTATE
========================= */

function rotateVideo(){

rotateDeg += 90;

if(rotateDeg >= 360){

rotateDeg = 0;

}

applyFilter();

}

window.rotateVideo =
rotateVideo;

/* =========================
FLIP
========================= */

function flipVideo(){

flipX *= -1;

applyFilter();

}

window.flipVideo =
flipVideo;

/* =========================
MUSIC
========================= */

const musicLibrary = [

{
id:1,
title:"CNTECH Intro",
artist:"CNTECH"
},

{
id:2,
title:"Gaming Beat",
artist:"CNTECH"
},

{
id:3,
title:"Epic Battle",
artist:"CNTECH"
},

{
id:4,
title:"Esports Theme",
artist:"CNTECH"
}

];

let selectedMusic =
null;

function loadMusicList(){

const list =
document.getElementById(
"musicList"
);

if(!list) return;

let html = "";

musicLibrary.forEach(
music=>{

html += `

<div class="music-item">

<div class="music-info">

<h4>

${music.title}

</h4>

<p>

${music.artist}

</p>

</div>

<button
class="music-use"

onclick="selectMusic(${music.id})">

Use

</button>

</div>

`;

});

list.innerHTML =
html;

}

loadMusicList();

function selectMusic(id){

selectedMusic =
musicLibrary.find(

m=>m.id===id

);

editorData.music =
selectedMusic;

saveEditorState();

alert(
selectedMusic.title
+
" selected"
);

}

window.selectMusic =
selectMusic;

/* =========================
VOLUME
========================= */

let videoVolume =
100;

let musicVolume =
100;

function setVideoVolume(v){

videoVolume = v;

video.volume =
v / 100;

}

function setMusicVolume(v){

musicVolume = v;

}

window.setVideoVolume =
setVideoVolume;

window.setMusicVolume =
setMusicVolume;

/* =========================
COVER
========================= */

const coverInput =

document.getElementById(
"coverInput"
);

if(coverInput){

coverInput.addEventListener(
"change",
e=>{

const file =
e.target.files[0];

if(!file)
return;

editorData.cover =
file.name;

saveEditorState();

}
);

}

/* =========================
OPEN COVER
========================= */

function chooseCover(){

coverInput.click();

}

window.chooseCover =
chooseCover;

/* =========================
EDITOR PANEL
========================= */

const editorPanel =

document.getElementById(
"editorPanel"
);

function showFilters(){

editorPanel.innerHTML = `

<div class="range-box">

<label>

Brightness

</label>

<input
type="range"
min="0"
max="200"
value="${brightness}"

oninput="setBrightness(this.value)">

</div>

<div class="range-box">

<label>

Contrast

</label>

<input
type="range"
min="0"
max="200"
value="${contrast}"

oninput="setContrast(this.value)">

</div>

<div class="range-box">

<label>

Saturation

</label>

<input
type="range"
min="0"
max="200"
value="${saturation}"

oninput="setSaturation(this.value)">

</div>

<div class="filter-grid">

<div
class="filter-card"
onclick="setFilter('normal')">

<div class="filter-preview"></div>

<div class="filter-name">

Normal

</div>

</div>

<div
class="filter-card"
onclick="setFilter('cinematic')">

<div class="filter-preview"></div>

<div class="filter-name">

Cinema

</div>

</div>

<div
class="filter-card"
onclick="setFilter('vivid')">

<div class="filter-preview"></div>

<div class="filter-name">

Vivid

</div>

</div>

<div
class="filter-card"
onclick="setFilter('warm')">

<div class="filter-preview"></div>

<div class="filter-name">

Warm

</div>

</div>

</div>

<button
class="btn btn-danger mt-3"
onclick="rotateVideo()">

Rotate

</button>

<button
class="btn btn-secondary mt-3 ms-2"
onclick="flipVideo()">

Flip

</button>

`;

}

document
.getElementById(
"filterBtn"
)
.addEventListener(
"click",
showFilters
);

</script>

<script type="module">

/* =========================
   CNTECH EDITOR PRO
   PART 5/5
========================= */

import {
getStorage,
ref,
uploadBytesResumable,
getDownloadURL
}
from
"https://www.gstatic.com/firebasejs/12.17.0/firebase-storage.js";

import {
getFirestore,
collection,
addDoc,
serverTimestamp
}
from
"https://www.gstatic.com/firebasejs/12.17.0/firebase-firestore.js";

/* Firebase */

const storage =
getStorage(app);

const firestore =
getFirestore(app);

/* =========================
LOAD TEMP VIDEO
========================= */

const tempVideoUrl =
sessionStorage.getItem(
"tempVideo"
);

/* =========================
CREATE THUMBNAIL
========================= */

async function createThumbnail(){

return new Promise(
(resolve)=>{

const canvas =
document.createElement(
"canvas"
);

const ctx =
canvas.getContext(
"2d"
);

const v =
document.createElement(
"video"
);

v.src =
tempVideoUrl;

v.currentTime = 1;

v.addEventListener(
"loadeddata",
()=>{

canvas.width =
v.videoWidth;

canvas.height =
v.videoHeight;

ctx.drawImage(
v,
0,
0,
canvas.width,
canvas.height
);

canvas.toBlob(
(blob)=>{

resolve(blob);

},
"image/jpeg",
0.85
);

});

});
}

/* =========================
UPLOAD FILE
========================= */

async function uploadFile(
file,
path
){

return new Promise(
(resolve,reject)=>{

const storageRef =
ref(
storage,
path
);

const task =
uploadBytesResumable(
storageRef,
file
);

task.on(

"state_changed",

(snapshot)=>{

const percent =

(snapshot.bytesTransferred
/
snapshot.totalBytes)

*
100;

const bar =
document.getElementById(
"progressBar"
);

if(bar){

bar.style.width =
percent + "%";

}

},

reject,

async()=>{

const url =
await getDownloadURL(
task.snapshot.ref
);

resolve(url);

}

);

});

}

/* =========================
PUBLISH
========================= */

async function publishVideo(){

try{

const title =
document.getElementById(
"title"
).value.trim();

const description =
document.getElementById(
"description"
).value.trim();

const hashtags =
document.getElementById(
"hashtags"
).value.trim();

const category =
document.getElementById(
"category"
).value;

if(!title){

alert(
"Enter title"
);

return;

}

/* video file */

const response =
await fetch(
tempVideoUrl
);

const blob =
await response.blob();

const videoName =

Date.now()
+
".mp4";

/* upload video */

const videoURL =
await uploadFile(

blob,

"videos/"
+
videoName

);

/* thumbnail */

const thumbBlob =
await createThumbnail();

const thumbURL =
await uploadFile(

thumbBlob,

"covers/"
+
Date.now()
+
".jpg"

);

/* firestore */

const docRef =
await addDoc(

collection(
firestore,
"videos"
),

{

uid:userID,

title:title,

description:description,

hashtags:

hashtags
.split(" ")
.filter(
v=>v
),

category:category,

video_url:
videoURL,

thumbnail:
thumbURL,

likes:0,

comments:0,

shares:0,

views:0,

status:
"published",

music:
editorData.music
||
null,

filter:
editorData.filter
||
"normal",

brightness:
editorData.brightness
||
100,

contrast:
editorData.contrast
||
100,

saturation:
editorData.saturation
||
100,

speed:
editorData.speed
||
1,

trim_start:
editorData.trimStart
||
0,

trim_end:
editorData.trimEnd
||
0,

created_at:
serverTimestamp()

}

);

/* clear temp */

sessionStorage.removeItem(
"tempVideo"
);

sessionStorage.removeItem(
"editorData"
);

/* success */

alert(
"Upload Complete"
);

/* redirect */

window.location.href =

"video.php?id="
+
docRef.id;

}
catch(err){

console.error(
err
);

alert(
"Upload Failed"
);

}

}

/* =========================
SAVE DRAFT
========================= */

document
.getElementById(
"saveDraftBtn"
)
.addEventListener(
"click",
()=>{

localStorage.setItem(

"draft_video",

JSON.stringify({

title:
document.getElementById(
"title"
).value,

description:
document.getElementById(
"description"
).value,

hashtags:
document.getElementById(
"hashtags"
).value,

category:
document.getElementById(
"category"
).value,

editor:
editorData

})

);

alert(
"Draft Saved"
);

}
);

/* =========================
LOAD DRAFT
========================= */

const draft =

localStorage.getItem(
"draft_video"
);

if(draft){

try{

const d =
JSON.parse(
draft
);

if(d.title)
document.getElementById(
"title"
).value =
d.title;

if(d.description)
document.getElementById(
"description"
).value =
d.description;

if(d.hashtags)
document.getElementById(
"hashtags"
).value =
d.hashtags;

if(d.category)
document.getElementById(
"category"
).value =
d.category;

}
catch(e){}

}

/* =========================
NEXT BUTTON
========================= */

document
.getElementById(
"nextBtn"
)
.addEventListener(
"click",
publishVideo
);

</script>