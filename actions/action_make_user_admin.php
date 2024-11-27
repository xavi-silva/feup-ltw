<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$username = $_POST['username'];
$stmt = $db->prepare("UPDATE USER SET isAdmin = true WHERE username = :username");
$stmt->bindValue(':username', $username);
$stmt->execute();

header("Location: ../pages/admin_page.php?option=users");
exit;
