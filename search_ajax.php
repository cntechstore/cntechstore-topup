<?php
require "database.php";

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM products 
        WHERE name LIKE '%$keyword%' 
        LIMIT 10";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){

    $data[] = [
        "id"=>$row['id'],
        "name"=>$row['name'],
        "price"=>$row['price'],
        "image"=>$row['image']
    ];

}

header("Content-Type: application/json");
echo json_encode($data);