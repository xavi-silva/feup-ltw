<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
$category = $_POST["category"];
$condition = $_POST["condition"];
$maxPrice = $_POST["price"];

require_once('../database/connect.php');
$db = getDatabaseConnection();
$url = "cat=$category&condition=$condition&price=$maxPrice";
header("Location:../pages/items_diplay.php?$url");
exit;



