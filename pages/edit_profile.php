<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$email = $_SESSION['email'];
$stmt = $db->prepare("SELECT * FROM USER WHERE email = :email");
$stmt->bindParam(':email' , $email);
$stmt->execute();
$user = $stmt->fetch();
$name = $user['realname'];
$username = $user['username'];


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src = "../javascript/error_detector.js" defer></script>
    <script src="../javascript/togglemenu.js"> </script>
    <title>Ctrl+Sell |Edit Profile</title>
</head>
<div class="background-container"></div>
<?php include ('header.php')?>
<body>
<img id="return" src="/images/x.png" style="width:30px" onclick="window.location.href='items_display.php'">
<div id="publish">
    <h1>Account Settings</h1>
    <form action = "../actions/action_edit_profile.php" method = "post">
        <h2>Name</h2> <input id="name" type = "text" name = "name" placeholder="<?php echo $name; ?>">
        <h2>Username</h2> <input id="username" type = "text" name = "username" placeholder="<?php echo $username; ?>">
        <h2>Email</h2> <input id="email" type = "text" name = "email" placeholder="<?php echo $email; ?>">
        <h2>Old Password</h2> <input id="password" type = "password" name = "password">
        <h2>New password </h2><input id="newPassword" type = "password" name = "newPassword">
        <h2>Confirm new password</h2> <input id="confirmNewPassword" type = "password" name = "confirmNewPassword">
        <h2>Country</h2> <input id="name" type = "text" name = "country" placeholder="<?php echo $user['country']; ?>">
        <h2>City</h2> <input id="name" type = "text" name = "city" placeholder="<?php echo $user['city']; ?>">
        <h2>Address</h2> <input id="name" type = "text" name = "address" placeholder="<?php echo $user['userAddress']; ?>">

        <div id="error-message" style="color: red;"></div>
        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>



