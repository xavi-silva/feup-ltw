<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$conditionName = $_POST['conditionName'];
$stmt = $db->prepare("SELECT COUNT(*) FROM CONDITION WHERE conditionName = :conditionName");
$stmt->bindValue(':conditionName', $conditionName);
$stmt->execute();
$conditionExists = $stmt->fetchColumn();
if($conditionExists > 0){
    header("Location: ../pages/admin_page.php?option=condition&error=Condition%20name%20already%20exists");
    exit;
}
if(!preg_match("/^[a-zA-Z\s]+$/", $conditionName)) {
    header("Location: ../pages/admin_page.php?option=condition&error=Invalid+condition+name");
    exit;
}

$stmtMaxId = $db->prepare('SELECT MAX(conditionId) AS max_id FROM CONDITION');
$stmtMaxId->execute();
$result = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
$maxId = $result['max_id'];
$id = ($maxId !== null) ? ($maxId + 1) : 1;

$stmt = $db->prepare("INSERT INTO CONDITION (conditionId,conditionName) VALUES (:conditionId,:conditionName)");
$stmt->bindValue(':conditionId', $id);
$stmt->bindValue(':conditionName', $conditionName);
$stmt->execute();

header("Location: ../pages/admin_page.php?option=condition");
exit;
