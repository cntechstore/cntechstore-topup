<?php
require "config.php";
require "database.php";
session_start();

/*
=========================
BLOGS PAGE
=========================
- รองรับ DEV / LIVE
- พร้อมต่อ SEO และ Blog Detail
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Blogs - CN Tech Store</title>

<link rel="stylesheet" href="style.css?v=<?= time() ?>">
<link rel="stylesheet" href="page.css?v=<?= time() ?>">

<script>
(function () {
    const theme = localStorage.getItem("theme") || "light";
    document.documentElement.classList.toggle("dark", theme === "dark");
})();
</script>

</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">

    <!-- HERO -->
    <section class="hero">
        <h1>Blogs & News</h1>
        <p>
            CN Tech Store - News, Payment Gateway,
            Game Top-up and Platform Updates
        </p>
    </section>

    <!-- BLOG LIST -->
    <div class="box">

        <h2>Latest Articles</h2>

        <div class="blog-list">

        <?php

        $sql = "
            SELECT *
            FROM blogs
            ORDER BY id DESC
            LIMIT 6
        ";

        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                $title = htmlspecialchars($row['title']);
                $content = htmlspecialchars(
                    mb_substr(
                        strip_tags($row['content']),
                        0,
                        150,
                        "UTF-8"
                    )
                );

                $created =
                    $row['created_at']
                    ??
                    date("Y-m-d");

                echo '

                <div class="blog-card">

                    <div class="blog-date">
                        '.$created.'
                    </div>

                    <h3>'.$title.'</h3>

                    <p>
                        '.$content.'...
                    </p>

                    <a
                        class="read-more"
                        href="blog-detail.php?id='
                        .$row['id'].
                        '"
                    >
                        Read More
                    </a>

                </div>

                ';
            }

        }else{

            echo '
            <div class="empty">
                No blogs available
            </div>
            ';
        }

        ?>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>

<script src="app.js?v=<?= time() ?>"></script>

</body>
</html>