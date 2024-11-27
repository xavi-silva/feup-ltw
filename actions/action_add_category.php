<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();

$categoryName = $_POST['categoryName'];
$stmt = $db->prepare("SELECT COUNT(*) FROM CATEGORY WHERE categoryName = :categoryName");
$stmt->bindValue(':categoryName', $categoryName);
$stmt->execute();
$categoryExists = $stmt->fetchColumn();
if($categoryExists > 0){
    header("Location: ../pages/admin_page.php?option=category&error=Category%20name%20already%20exists");
    exit;
}
if(!preg_match("/^[a-zA-Z\s]+$/", $categoryName)) {
    header("Location: ../pages/admin_page.php?option=category&error=Invalid+category+name");
    exit;
}

$stmtMaxId = $db->prepare('SELECT MAX(categoryId) AS max_id FROM CATEGORY');
$stmtMaxId->execute();
$result = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
$maxId = $result['max_id'];
$id = ($maxId !== null) ? ($maxId + 1) : 1;

$stmt = $db->prepare("INSERT INTO CATEGORY (categoryId,categoryName) VALUES (:categoryId,:categoryName)");
$stmt->bindValue(':categoryId', $id);
$stmt->bindValue(':categoryName', $categoryName);
$stmt->execute();

header("Location: ../pages/admin_page.php?option=category");
exit;