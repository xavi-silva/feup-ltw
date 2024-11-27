<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$conditionId = $_POST['conditionId'];
$aux=6;
$stmt = $db->prepare("UPDATE ITEM SET condition = :condition WHERE condition = :conditionId");
$stmt -> bindParam(':conditionId', $conditionId);
$stmt -> bindParam(':condition', $aux);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM CONDITION WHERE conditionId = :conditionId");
$stmt->bindParam(':conditionId' , $conditionId);
$stmt->execute();

header("Location: ../pages/admin_page.php?option=condition");
exit;