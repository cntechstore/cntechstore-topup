<?php

$host = "sql204.byetcluster.com";
$user = "if0_42248147";
$password = "rrSJbXGF06";
$dbname = "if042248147_wp741";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $dbname
);

if ($conn->connect_error) {
    die(
        "Database Error: "
        . $conn->connect_error
    );
}

/*
=========================
UTF8MB4
=========================
*/

$conn->set_charset("utf8mb4");

$conn->query("
SET NAMES utf8mb4
");

$conn->query("
SET CHARACTER SET utf8mb4
");

$conn->query("
SET time_zone = '+07:00'
");
?>