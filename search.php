<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

echo "SEARCH START<br>";

require_once __DIR__ . "/database.php";

echo "DB OK<br>";

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    exit;
}

$keyword = "%{$q}%";
$found = false;

/*
=================================
GAMES
=================================
*/
$sql = "
SELECT id, name
FROM games
WHERE name LIKE ?
ORDER BY play_count DESC
LIMIT 5
";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("s", $keyword);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $found = true;

        echo '
        <a class="search-item"
        href="/game/namegame.php?id=' . (int)$row['id'] . '">
            🎮 ' . htmlspecialchars($row['name']) . '
        </a>';
    }

    $stmt->close();
}


/*
=================================
VOUCHERS
=================================
*/
$sql = "
SELECT id, name
FROM voucher_categories
WHERE name LIKE ?
ORDER BY id DESC
LIMIT 5
";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("s", $keyword);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $found = true;

        echo '
        <a class="search-item"
        href="/game/voucher_pd.php?id=' . (int)$row['id'] . '">
            🎫 ' . htmlspecialchars($row['name']) . '
        </a>';
    }

    $stmt->close();
}


/*
=================================
PRODUCTS
=================================
*/
$sql = "
SELECT id, name
FROM products
WHERE name LIKE ?
ORDER BY id DESC
LIMIT 5
";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("s", $keyword);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $found = true;

        echo '
        <a class="search-item"
        href="/view-product.php?id=' . (int)$row['id'] . '">
            🛒 ' . htmlspecialchars($row['name']) . '
        </a>';
    }

    $stmt->close();
}


/*
=================================
BLOGS
=================================
*/
$sql = "
SELECT id, title
FROM blogs
WHERE status = 'published'
AND title LIKE ?
ORDER BY id DESC
LIMIT 5
";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("s", $keyword);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $found = true;

        echo '
        <a class="search-item"
        href="/blog-detail.php?id=' . (int)$row['id'] . '">
            📰 ' . htmlspecialchars($row['title']) . '
        </a>';
    }

    $stmt->close();
}


/*
=================================
NO RESULT
=================================
*/
if (!$found) {

    echo '
    <div class="search-empty">
        ບໍ່ພົບຂໍ້ມູນ
    </div>';
}