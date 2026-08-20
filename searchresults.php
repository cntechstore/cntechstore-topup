<?php
require "config.php";
require "database.php";

/*
=========================
MAINTENANCE GATE
=========================
*/
$isMaintenance =
    (SITE_MODE === "LIVE" &&
     SITE_STATUS === "MAINTENANCE");

if($isMaintenance){
    include "maintenance.php";
    exit();
}

session_start();

error_reporting(E_ALL);
ini_set("display_errors",1);

/*
=========================
SEARCH
=========================
*/
$keyword = $_GET['id'] ?? '';
$page = (int)($_GET['page'] ?? 1);

if($page < 1){
    $page = 1;
}

$limit  = 8;
$offset = ($page - 1) * $limit;

/*
=========================
COUNT
=========================
*/
$count = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM products
    WHERE name LIKE CONCAT('%', ?, '%')
");

$count->bind_param("s",$keyword);
$count->execute();

$total =
    $count
        ->get_result()
        ->fetch_assoc()['total'];

$total_pages = max(
    1,
    ceil($total / $limit)
);

/*
=========================
PRODUCTS
=========================
*/
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE name LIKE CONCAT('%', ?, '%')
    ORDER BY id DESC
    LIMIT ? OFFSET ?
");

$stmt->bind_param(
    "sii",
    $keyword,
    $limit,
    $offset
);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    CN Tech Store - Search
</title>

<link rel="stylesheet"
      href="style.css?v=<?= time() ?>">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;700&display=swap"
      rel="stylesheet">

<script>
(function(){
    const theme =
        localStorage.getItem("theme")
        || "light";

    document.documentElement
        .classList
        .toggle(
            "dark",
            theme==="dark"
        );
})();
</script>

<style>

.container{
    max-width:1200px;
    margin:auto;
    padding:20px;
}

.hero-search{
    background:
        linear-gradient(
            135deg,
            #6c5ce7,
            #0984e3
        );

    color:#fff;
    text-align:center;
    padding:40px 20px;
    border-radius:15px;
    margin-bottom:20px;
}

.search-box{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
    margin:20px 0;
}

.product-grid{
    display:grid;
    grid-template-columns:
        repeat(4,1fr);
    gap:15px;
}

@media(max-width:768px){
    .product-grid{
        grid-template-columns:
            repeat(2,1fr);
    }
}

.product-card{
    background:#fff;
    border-radius:12px;
    padding:10px;
    text-align:center;
    box-shadow:
        0 3px 10px
        rgba(0,0,0,.08);
}

.product-card img{
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:10px;
}

.product-card a{
    display:inline-block;
    margin-top:10px;
}

.pagination{
    margin-top:30px;
    text-align:center;
}

.pagination a{
    display:inline-block;
    padding:8px 12px;
    margin:2px;
    border-radius:8px;
    background:#eee;
    text-decoration:none;
}

.pagination .active{
    background:#6c5ce7;
    color:#fff;
}

.no-result{
    text-align:center;
    padding:50px;
}

</style>

</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">

    <!-- HERO -->
    <div class="hero-search">

        <h1>
            <i class="fa-solid fa-store"></i>
            CN Tech Store
        </h1>

        <p>
            Computer • Gaming • Mobile Top-up
        </p>

        <h4>
            <i class="fa-solid fa-magnifying-glass"></i>
            ผลการค้นหา :
            <?= htmlspecialchars($keyword) ?>
        </h4>

    </div>

    <!-- SEARCH -->
    <input
        type="text"
        class="search-box"
        placeholder="Search products..."
        onkeyup="searchProducts(this.value)"
    >

    <div id="searchResults"></div>

    <!-- PRODUCTS -->
    <div class="product-grid">

    <?php if($result && $result->num_rows > 0){ ?>

        <?php while($row = $result->fetch_assoc()){ ?>

            <div class="product-card">

                <img
                    src="<?= htmlspecialchars($row['image']) ?>"
                >

                <h5>
                    <?= htmlspecialchars($row['name']) ?>
                </h5>

                <p>
                    ₭ <?= number_format($row['price']) ?>
                </p>

                <a
                    class="btn btn-primary"
                    href="view-product.php?id=<?= $row['id'] ?>"
                >
                    View
                </a>

            </div>

        <?php } ?>

    <?php }else{ ?>

        <div class="no-result">

            <h3>
                <i class="fa-solid fa-circle-info"></i>
                No products found
            </h3>

        </div>

    <?php } ?>

    </div>

    <!-- PAGINATION -->
    <div class="pagination">

        <?php for($i=1;$i<=$total_pages;$i++){ ?>

            <a
                href="searchresults.php?id=<?= urlencode($keyword) ?>&page=<?= $i ?>"
                class="<?= ($i==$page?'active':'') ?>"
            >
                <?= $i ?>
            </a>

        <?php } ?>

    </div>

</div>

<?php include "footer.php"; ?>

<script src="app.js?v=<?= time() ?>"></script>

</body>
</html>