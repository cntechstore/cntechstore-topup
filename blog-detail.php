<?php

require "config.php";
require "database.php";


if(session_status() === PHP_SESSION_NONE){

    session_start();

}


/*
========================
CNTECH STORE BLOG DETAIL
========================
*/


$id = (int)($_GET['id'] ?? 0);


if($id <= 0){

    die("Invalid Blog ID");

}



/*
========================
VIEW COUNTER
========================
*/

$conn->query("
UPDATE blogs
SET views = views + 1
WHERE id=".$id
);



/*
========================
GET ARTICLE
========================
*/

$stmt = $conn->prepare("
SELECT *
FROM blogs
WHERE id=?
AND status='published'
LIMIT 1
");


$stmt->bind_param(
"i",
$id
);


$stmt->execute();


$result =
$stmt->get_result();



if($result->num_rows <=0){

    die("Article Not Found");

}



$blog =
$result->fetch_assoc();



$title =
htmlspecialchars(
$blog['title']
);



$content =
nl2br(
htmlspecialchars(
$blog['content']
)
);



$image =
!empty($blog['image'])

?

"/admin/uploads/blogs/"
.$blog['image']

:

"/admin/uploads/no-image.png";



$author =
htmlspecialchars(
$blog['author'] ?? "CNTECH STORE"
);



$date =
date(
"d M Y",
strtotime(
$blog['created_at']
)
);



$views =
number_format(
$blog['views'] ?? 0
);



$url =
"https://cntechstore.shop/blog-detail.php?id=".$id;



?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport"
content="width=device-width,initial-scale=1.0">


<title>

<?=$title?>

- CNTECH STORE

</title>



<meta name="description"
content="<?=mb_substr(strip_tags($blog['content']),0,160)?>">



<meta property="og:title"
content="<?=$title?>">



<meta property="og:type"
content="article">



<meta property="og:image"
content="https://cntechstore.shop<?=$image?>">



<meta property="og:url"
content="<?=$url?>">



<link rel="canonical"
href="<?=$url?>">



<?php include "cdn.php"; ?>



<link rel="stylesheet"
href="style.css?v=<?=time()?>">



<link rel="stylesheet"
href="page.css?v=<?=time()?>">



<script>

(function(){

let theme =
localStorage.getItem("theme")
||
"dark";


document.documentElement.classList.toggle(
"dark",
theme==="dark"
);


})();


</script>



<style>


body{

background:#050505;

color:white;

}



/* PAGE */


.blog-detail-container{


max-width:1200px;

margin:auto;

padding:20px;

}




/* HERO */


.article-hero{


position:relative;

height:420px;

border-radius:30px;

overflow:hidden;

margin-bottom:25px;


}


.article-hero img{


width:100%;

height:100%;

object-fit:cover;


}



.article-overlay{


position:absolute;

inset:0;


display:flex;

flex-direction:column;

justify-content:flex-end;


padding:30px;


background:

linear-gradient(

transparent,

rgba(0,0,0,.9)

);


}



.article-overlay h1{


font-size:42px;

line-height:1.3;


}


.article-tag{


display:inline-flex;

background:#ff0033;

padding:8px 15px;

border-radius:20px;

font-size:13px;

width:max-content;


}



</style>


</head>



<body>





<div class="blog-detail-container">


<section class="article-hero">


<img

src="<?=$image?>"

alt="<?=$title?>"

onerror="this.src='/admin/uploads/no-image.png'"

>


<div class="article-overlay">


<div class="article-tag">

CNTECH NEWS

</div>


<h1>

<?=$title?>

</h1>


</div>


</section>

<div class="article-meta-card">


<div class="meta-item">

<i class="fa-solid fa-user"></i>

<?=$author?>

</div>



<div class="meta-item">

<i class="fa-solid fa-calendar"></i>

<?=$date?>

</div>



<div class="meta-item">

<i class="fa-solid fa-eye"></i>

<?=$views?>

 Views

</div>


</div>




<article class="article-content-card">



<?php

/*
========================
YOUTUBE VIDEO SUPPORT
========================
*/


if(!empty($blog['video_url'])){


$video =
$blog['video_url'];


$youtube_id="";



if(strpos($video,"watch?v=")!==false){


parse_str(
parse_url($video,PHP_URL_QUERY),
$query
);


$youtube_id =
$query['v'] ?? "";


}


elseif(strpos($video,"youtu.be/")!==false){


$youtube_id =
basename(
parse_url($video,PHP_URL_PATH)
);


}


elseif(strpos($video,"shorts/")!==false){


$youtube_id =
basename(
parse_url($video,PHP_URL_PATH)
);


}




if($youtube_id!=""){


?>



<div class="video-box">


<iframe

src="https://www.youtube.com/embed/<?=$youtube_id?>"

loading="lazy"

allowfullscreen

title="<?=$title?>"

></iframe>


</div>



<?php


}


}


?>





<div class="article-text">


<?= $content ?>


</div>






<!-- SHARE -->

<div class="share-card">


<h3>

<i class="fa-solid fa-share-nodes"></i>

Share Article

</h3>



<div class="share-buttons">



<a

target="_blank"

href="https://www.facebook.com/sharer/sharer.php?u=<?=$url?>"

class="facebook"

>

<i class="fab fa-facebook"></i>

Facebook

</a>




<a

href="javascript:copyArticle()"

class="copy"

>

<i class="fa-solid fa-link"></i>

Copy Link

</a>



</div>


</div>





<a href="javascript:history.back()"

class="back-button">


<i class="fa-solid fa-arrow-left"></i>

Back


</a>




</article>

<style>

/* META */


.article-meta-card{


display:flex;

gap:15px;

flex-wrap:wrap;

background:

rgba(255,255,255,.05);

backdrop-filter:blur(15px);

padding:20px;

border-radius:20px;

margin-bottom:25px;

border:

1px solid rgba(255,255,255,.08);


}



.meta-item{


padding:10px 15px;

background:#111;

border-radius:15px;

font-size:14px;


}



/* CONTENT */


.article-content-card{


background:

rgba(255,255,255,.04);


border-radius:25px;

padding:30px;

border:

1px solid rgba(255,255,255,.08);


}



.article-text{


font-size:18px;

line-height:2;

color:#ddd;


}



.article-text img{


max-width:100%;

border-radius:20px;


}



/* VIDEO */


.video-box{


margin-bottom:30px;

overflow:hidden;

border-radius:20px;


}



.video-box iframe{


width:100%;

aspect-ratio:16/9;

border:0;


}





/* SHARE */


.share-card{


margin-top:40px;

padding:25px;

background:#111;

border-radius:20px;


}



.share-buttons{


display:flex;

gap:15px;

margin-top:15px;


}



.share-buttons a{


padding:12px 20px;

border-radius:15px;

color:white;

text-decoration:none;


}



.facebook{

background:#1877f2;

}



.copy{

background:#ff0033;

}



.back-button{


display:inline-flex;

margin-top:25px;

padding:14px 25px;

background:#ff0033;

color:white;

border-radius:15px;

text-decoration:none;


}

/* COMMENTS */


.comment-section{


margin-top:40px;


}



.comment-header h2{


font-size:28px;


}





.comment-form-card,


.comment-card{


background:

rgba(255,255,255,.05);


border-radius:25px;


padding:25px;


margin-top:20px;


border:

1px solid rgba(255,255,255,.08);


}





.input-group{


display:flex;


align-items:center;


gap:12px;


background:#111;


padding:12px 15px;


border-radius:15px;


margin-bottom:15px;


}



.input-group i{


color:#ff0033;


}



.input-group input,


.input-group textarea{


width:100%;


background:none;


border:0;


outline:none;


color:white;


font-size:16px;


}





.comment-submit,


.reply-form-box button{


background:#ff0033;


color:white;


border:0;


padding:14px 25px;


border-radius:15px;


font-weight:700;


cursor:pointer;


}





.comment-user{


display:flex;


gap:15px;


align-items:center;


}





.avatar{


width:45px;


height:45px;


border-radius:50%;


background:#ff0033;


display:flex;


align-items:center;


justify-content:center;


}



.comment-text{


line-height:1.8;


color:#ddd;


margin:20px 0;


}





.comment-actions{


display:flex;


gap:10px;


}



.comment-actions button{


border:0;


background:#222;


color:white;


padding:10px 15px;


border-radius:20px;


cursor:pointer;


}



.reply-form-box{


display:none;


margin-top:20px;


padding:20px;


background:#111;


border-radius:20px;


}


.reply-form-box input,


.reply-form-box textarea{


width:100%;


padding:12px;


margin-bottom:10px;


border-radius:12px;


border:1px solid #333;


background:#000;


color:white;


}

/* REPLY */


.reply-item{


margin-top:15px;


margin-left:30px;


padding:18px;


background:#151515;


border-radius:18px;


border-left:3px solid #ff0033;


}



.reply-user{


color:#ff0033;


margin-bottom:8px;


}




.no-comment{


text-align:center;


padding:40px;


color:#aaa;


}




/* RELATED */


.related-section{


margin-top:50px;


}



.related-grid{


display:grid;


grid-template-columns:

repeat(auto-fit,minmax(250px,1fr));


gap:20px;


}





.related-card{


background:#111;


border-radius:20px;


overflow:hidden;


text-decoration:none;


color:white;


border:

1px solid rgba(255,255,255,.08);


transition:.3s;


}




.related-card:hover{


transform:translateY(-5px);


}



.related-card img{


width:100%;


height:150px;


object-fit:cover;


}



.related-card div{


padding:18px;


}



.related-card h3{


font-size:17px;


line-height:1.5;


}



.related-card span{


color:#aaa;


font-size:13px;


}






@media(max-width:600px){



.blog-detail-container{


padding:10px;


}




.article-hero{


height:260px;


}



.article-overlay h1{


font-size:25px;


}




.article-content-card{


padding:20px;


}




.article-text{


font-size:16px;


}




.comment-actions{


flex-wrap:wrap;


}




.related-grid{


grid-template-columns:1fr;


}



.reply-item{


margin-left:10px;


}



}

/* =================================
   CNTECH STORE FOOTER
================================= */

.site-footer{

background:#080808;

color:#ffffff;

padding:50px 20px 20px;

margin-top:50px;

}



.footer-container{

max-width:1200px;

margin:auto;


display:grid;

grid-template-columns:

repeat(auto-fit,minmax(220px,1fr));


gap:35px;

}



/* BRAND */

.footer-brand img{

width:75px;

height:75px;

object-fit:contain;

border-radius:18px;

margin-bottom:15px;

}



.footer-brand h3{

font-size:26px;

font-weight:800;

color:#ff0033;

margin:10px 0;

}



.footer-brand p{

color:#bdbdbd;

line-height:1.7;

font-size:15px;

}



/* MENU */

.footer-menu h4,
.footer-social h4{

font-size:18px;

color:#ff0033;

margin-bottom:20px;

font-weight:700;

}



.footer-menu a{

display:block;

text-decoration:none;

color:#ddd;

margin:12px 0;

transition:.3s;

font-size:15px;

}



.footer-menu a:hover{

color:#ff0033;

transform:translateX(5px);

}



/* SOCIAL */

.footer-social a{

display:inline-flex;

width:42px;

height:42px;

align-items:center;

justify-content:center;

border-radius:50%;


background:#151515;


color:white;

font-size:20px;

margin-right:10px;

transition:.3s;

}



.footer-social a:hover{

background:#ff0033;

transform:translateY(-5px);

}



/* BOTTOM */

.footer-bottom{

max-width:1200px;

margin:40px auto 0;


padding-top:20px;


border-top:

1px solid rgba(255,255,255,.15);


text-align:center;


color:#999;

font-size:14px;

line-height:1.8;

}



/* =================================
 MOBILE BOTTOM NAVBAR
================================= */


.mobile-navbar{

display:none;

}




@media(max-width:768px){


.site-footer{

padding-bottom:90px;

}



.footer-container{

grid-template-columns:

1fr;

text-align:center;

}



.footer-brand img{

margin:auto;

}



.footer-menu a:hover{

transform:none;

}




/* MOBILE APP NAV */


.mobile-navbar{


display:flex;


position:fixed;


bottom:0;


left:0;


right:0;


height:72px;



background:

rgba(10,10,10,.96);



backdrop-filter:

blur(15px);



border-top:

1px solid rgba(255,255,255,.15);



z-index:99999;


}



.mobile-navbar .nav-item{


flex:1;


display:flex;


flex-direction:column;


justify-content:center;


align-items:center;


gap:5px;



text-decoration:none;



color:#aaa;



font-size:12px;



}



.mobile-navbar .nav-item i{


font-size:21px;


}



.mobile-navbar .nav-item.active{


color:#ff0033;


}



.mobile-navbar .nav-item:hover{


color:#ff0033;


}



body{

padding-bottom:75px;

}


}


</style>

<section class="comment-section">


<div class="comment-header">


<h2>

<i class="fa-solid fa-comments"></i>

Comments

</h2>


</div>





<!-- ADD COMMENT -->


<div class="comment-form-card">


<form action="comment-save.php" method="POST">


<input type="hidden"

name="blog_id"

value="<?=$id?>">



<div class="input-group">


<i class="fa-solid fa-user"></i>


<input

type="text"

name="name"

placeholder="Your Name"

required

>


</div>





<div class="input-group">


<i class="fa-solid fa-envelope"></i>


<input

type="email"

name="email"

placeholder="Email (Optional)"

>


</div>





<div class="input-group textarea">


<i class="fa-solid fa-message"></i>


<textarea

name="comment"

rows="5"

placeholder="Write your comment..."

required

></textarea>


</div>




<button class="comment-submit"

type="submit">


<i class="fa-solid fa-paper-plane"></i>


Post Comment


</button>



</form>



</div>





<!-- COMMENT LIST -->


<div class="comments-list">


<?php


$stmt = $conn->prepare("

SELECT *

FROM blog_comments

WHERE blog_id=?

AND status='approved'

ORDER BY id DESC

");


$stmt->bind_param(
"i",
$id
);


$stmt->execute();


$comments =
$stmt->get_result();





if($comments->num_rows > 0){



while($c=$comments->fetch_assoc()){


$cid =
(int)$c['id'];

?>


<div class="comment-card">



<div class="comment-user">


<div class="avatar">


<i class="fa-solid fa-user"></i>


</div>



<div>


<h4>

<?=htmlspecialchars($c['name'])?>

</h4>



<small>

<i class="fa-regular fa-clock"></i>

<?=htmlspecialchars($c['created_at'])?>

</small>


</div>


</div>





<p class="comment-text">


<?=nl2br(
htmlspecialchars(
$c['comment']
)
)?>


</p>





<div class="comment-actions">



<button

onclick="commentAction(
<?=$cid?>,
'like'
)"

>


<i class="fa-solid fa-heart"></i>


<span id="like-<?=$cid?>">

<?=$c['likes'] ?? 0?>

</span>


</button>





<button

onclick="commentAction(
<?=$cid?>,
'dislike'
)"

>


<i class="fa-solid fa-thumbs-down"></i>


<span id="dislike-<?=$cid?>">

<?=$c['dislikes'] ?? 0?>

</span>


</button>





<button

onclick="showReply(
<?=$cid?>
)"

>


<i class="fa-solid fa-reply"></i>

Reply


</button>



</div>





<!-- REPLY FORM -->


<div

id="reply<?=$cid?>"

class="reply-form-box">


<form action="reply-save.php"

method="POST">


<input

type="hidden"

name="comment_id"

value="<?=$cid?>"

>



<input

name="name"

placeholder="Your name"

required

>



<textarea

name="reply"

placeholder="Write reply..."

required

></textarea>




<button type="submit">


<i class="fa-solid fa-paper-plane"></i>

Send Reply


</button>



</form>


</div>


<?php


/*
========================
REPLY LIST
========================
*/


$r = $conn->prepare("

SELECT *

FROM blog_comment_reply

WHERE comment_id=?

ORDER BY id ASC

");



$r->bind_param(
"i",
$cid
);



$r->execute();



$replys =
$r->get_result();




while($reply=$replys->fetch_assoc()){


?>


<div class="reply-item">


<div class="reply-user">


<i class="fa-solid fa-user"></i>


<b>

<?=htmlspecialchars(
$reply['name']
)?>

</b>


</div>



<p>

<?=nl2br(
htmlspecialchars(
$reply['reply']
)
)?>

</p>



</div>


<?php

}


?>


</div>


<?php

}

}else{

?>


<div class="no-comment">


<i class="fa-solid fa-comment-slash"></i>


No comments yet


</div>


<?php

}


?>


</div>


</section>






<!-- RELATED ARTICLES -->


<section class="related-section">


<h2>


<i class="fa-solid fa-newspaper"></i>

Related News


</h2>



<div class="related-grid">



<?php


$related = $conn->prepare("

SELECT *

FROM blogs

WHERE status='published'

AND id != ?

ORDER BY created_at DESC

LIMIT 4

");



$related->bind_param(
"i",
$id
);



$related->execute();



$related_result =
$related->get_result();




while($r=$related_result->fetch_assoc()){


$r_img =

!empty($r['image'])

?

"/admin/uploads/blogs/".$r['image']

:

"/admin/uploads/no-image.png";


?>


<a

href="blog-detail.php?id=<?=$r['id']?>"

class="related-card"

>



<img

src="<?=$r_img?>"

onerror="this.src='/admin/uploads/no-image.png'"

>



<div>


<h3>

<?=htmlspecialchars(
$r['title']
)?>

</h3>


<span>


<i class="fa-solid fa-eye"></i>

<?=$r['views'] ?? 0?>


</span>



</div>



</a>



<?php

}


?>


</div>


</section>




</div>

<!-- =========================
CNTECH STORE FOOTER
========================= -->


<footer class="site-footer">


<div class="footer-container">


<div class="footer-brand">




<h3>
CNTECH STORE
</h3>


<p>
Computer • Mobile • Parts & Accessories
</p>


<p>
Gaming Store | Top Up | Digital Platform
</p>


</div>




<div class="footer-menu">

<h4>
Services
</h4>


<a href="/games/">
Game Top Up
</a>


<a href="/voucher/">
Voucher Store
</a>


<a href="/page/blogs-method.php">
News & Blog
</a>


<a href="/cart.php">
Cart
</a>


</div>




<div class="footer-menu">

<h4>
Support
</h4>


<a href="/page/contact.php">
Contact
</a>


<a href="/page/privacy-policy.php">
Privacy Policy
</a>


<a href="/page/terms-of-service.php">
Terms of Service
</a>


</div>




<div class="footer-social">


<h4>
Follow Us
</h4>


<a href="#">
<i class="fa-brands fa-facebook"></i>
</a>


<a href="#">
<i class="fa-brands fa-tiktok"></i>
</a>


<a href="#">
<i class="fa-brands fa-youtube"></i>
</a>


</div>


</div>




<div class="footer-bottom">

© <?=date("Y")?> CNTECH STORE

<br>

All Rights Reserved.

</div>


</footer>





<!-- MOBILE NAVBAR -->


<div class="mobile-navbar">


<a href="/" class="nav-item">


<i class="fa-solid fa-house"></i>

<span>
Home
</span>


</a>




<a href="/games/" class="nav-item">


<i class="fa-solid fa-gamepad"></i>

<span>
Games
</span>


</a>




<a href="/page/blogs-method.php" class="nav-item active">


<i class="fa-solid fa-newspaper"></i>

<span>
News
</span>


</a>




<a href="/cart.php" class="nav-item">


<i class="fa-solid fa-cart-shopping"></i>

<span>
Cart
</span>


</a>




<a href="/account.php" class="nav-item">


<i class="fa-solid fa-user"></i>

<span>
Account
</span>


</a>


</div>

<script>


/*
=====================
COPY LINK
=====================
*/


function copyArticle(){


navigator.clipboard.writeText(
window.location.href
);



alert(
"Article link copied"
);


}




/*
=====================
REPLY TOGGLE
=====================
*/


function showReply(id){


let box =

document.getElementById(
"reply"+id
);



if(box.style.display==="block"){


box.style.display="none";


}else{


box.style.display="block";


}


}





/*
=====================
COMMENT LIKE DISLIKE
=====================
*/


function commentAction(id,action){



fetch(
"comment-action.php",
{


method:"POST",


headers:{


"Content-Type":
"application/x-www-form-urlencoded"


},


body:

"comment_id="+id+
"&action="+action



}

)


.then(
response=>response.json()
)



.then(
data=>{


if(data.status==="success"){



document.getElementById(
"like-"+id
).innerHTML =
data.likes;




document.getElementById(
"dislike-"+id
).innerHTML =
data.dislikes;



}else{


alert(
data.message
);


}



}

)




.catch(
error=>{


console.log(error);


}

);



}



    </script>

</body>
</html>

