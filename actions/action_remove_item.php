<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$product_id = $_POST["product_id"];

$stmt = $db->prepare("DELETE FROM PHOTO WHERE itemId = :product_id");
$stmt->bindParam(':product_id' , $product_id);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM ITEM WHERE id = :product_id");
$stmt->bindParam(':product_id' , $product_id);
$stmt->execute();

header("location: ../pages/items_display.php");
exit();