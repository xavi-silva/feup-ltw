<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$categoryId = $_POST['categoryId'];

$aux=7;


$stmt = $db->prepare("UPDATE ITEM SET category = :category WHERE category = :categoryId");
$stmt -> bindParam(':categoryId', $categoryId);
$stmt -> bindParam(':category', $aux);
$stmt->execute();


$stmt = $db->prepare("DELETE FROM CATEGORY WHERE categoryId = :categoryId");
$stmt->bindParam(':categoryId' , $categoryId);
$stmt->execute();

header("Location: ../pages/admin_page.php?option=category");
exit;

