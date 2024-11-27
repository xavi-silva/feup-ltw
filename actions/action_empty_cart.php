<?php
session_start();
$referer = $_SERVER['HTTP_REFERER'];
require_once('../database/connect.php');
$db = getDatabaseConnection();
$email = $_SESSION['email'];
$stmt = $db->prepare("SELECT * FROM USER WHERE email = :email");
$stmt->bindParam(':email' , $email);
$stmt->execute();
$user = $stmt->fetch();
$username = $user['username'];

$stmt = $db->prepare("DELETE FROM SHOPPING_CART WHERE username = :username");
$stmt->bindParam(':username' , $username);
$stmt->execute();

header("Location: $referer");
exit();