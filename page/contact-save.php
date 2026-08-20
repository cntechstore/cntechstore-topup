<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../database.php";
session_start();

if(!isset($conn)){
    die("DB connection failed");
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    die("Missing data");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email");
}

$stmt = $conn->prepare("
    INSERT INTO contact (name,email,subject,message)
    VALUES (?,?,?,?)
");

if(!$stmt){
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssss", $name, $email, $subject, $message);

if(!$stmt->execute()){
    die("Execute failed: " . $stmt->error);
}

header("Location: contact-method.php?success=1");
exit;
?>