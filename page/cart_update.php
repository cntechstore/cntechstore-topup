<?php
session_start();

$key = $_POST['key'];
$change = (int)$_POST['change'];

if (isset($_SESSION['cart'][$key])) {

    $_SESSION['cart'][$key]['qty'] += $change;

    if ($_SESSION['cart'][$key]['qty'] <= 0) {
        unset($_SESSION['cart'][$key]);
    }
}