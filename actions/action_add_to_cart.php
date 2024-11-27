<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$email = $_SESSION['email'];
$stmt = $db->prepare("SELECT * FROM USER WHERE email = :email");
$stmt->bindParam(':email' , $email);
$stmt->execute();
$user = $stmt->fetch();
$username = $user['username'];


$product_id = $_POST["product_id"];

$stmt = $db->prepare("INSERT INTO SHOPPING_CART(username, itemId) VALUES(:username, :itemId)");
$stmt->bindParam(':username' , $username);
$stmt->bindParam(':itemId' , $product_id);
$stmt->execute();

header("location: ../pages/item.php?id=$product_id");
exit();