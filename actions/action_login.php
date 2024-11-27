<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}

session_start();
require_once('../database/connect.php');
$db = getDatabaseConnection();
$email=$_POST["email"];
$password=$_POST["password"];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../pages/login.php?error=Invalid%20email%20format");
    exit;
}

if (strlen($password) < 8) {
    header("Location: ../pages/login.php?error=Password%20less%20than%208%20characters");
    exit;
}

if (!preg_match('/[A-Z]/', $password)) {
    header("Location: ../pages/login.php?error=Password%20without%20uppercase%20character");
    exit;
}
if (!preg_match('/[a-z]/', $password)) {
    header("Location: ../pages/login.php?error=Password%20without%20lowercase%20character");
    exit;
}
if (!preg_match('/[0-9]/', $password)) {
    header("Location: ../pages/login.php?error=Password%20without%20digit%20character");
    exit;
}



$stmt = $db->prepare("SELECT pw FROM USER WHERE email = :email");
$stmt->bindValue(':email', $email);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);


if ($result) {
    $hashed_password = $result['pw'];
    if (password_verify($password, $hashed_password)) {
        $stmt = $db->prepare('SELECT * FROM USER WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        $username = $user['username'];
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        header("Location: ../pages/items_display.php");
        exit;
    } else {
        header("Location: ../pages/login.php?error=Wrong_password");
        exit;
    }
} else {
    header("Location: ../pages/login.php?error=Email_not_found");
    exit;
}


