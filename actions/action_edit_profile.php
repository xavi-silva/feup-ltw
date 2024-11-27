<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$email = $_SESSION['email'];
$stmt = $db->prepare("SELECT * FROM USER WHERE email = :email");
$stmt->bindParam(':email' , $email);
$stmt->execute();
$user = $stmt->fetch();
$oldPassword = $user['pw'];
$name=$_POST["name"];
$username = $_POST["username"];
$newEmail=$_POST["email"];
$password=$_POST["password"];
$newPassword=$_POST["newPassword"];
$confirmNewPassword=$_POST["confirmNewPassword"];
$country=$_POST["country"];
$city=$_POST["city"];
$address=$_POST["address"];

$stmt = $db->prepare("SELECT COUNT(*) FROM USER WHERE email = :email");
$stmt->bindValue(':email', $newEmail);
$stmt->execute();
$newEmailExists = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM USER WHERE username = :username");
$stmt->bindValue(':username', $username);
$stmt->execute();
$usernameExists = $stmt->fetchColumn();



if(strlen($name)!=0){
    if(preg_match ("/^[a-zA-Z\s]+$/", $name)) {
        $stmt = $db->prepare('UPDATE USER SET realname = :name WHERE email = :email');

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);

        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_profile.php?error=Invalid%20name");
        exit;
    }
}

if(strlen($username)!=0){
    if(strlen($username)>=5 && strlen($username)<=15) {
        if (preg_match("/^[a-zA-Z0-9_-]+$/", $username)) {

            if ($usernameExists > 0) {
                header("Location: ../pages/edit_profile.php?error=Username%20already%20exists");
                exit;
            } else {
                $stmt = $db->prepare('UPDATE USER SET username = :username WHERE email = :email');
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $_SESSION['username'] = $username;
            }
        }
        else{
            header("Location: ../pages/edit_profile.php?error=Invalid%20username");
            exit;
        }

    }
    else{
        header("Location: ../pages/edit_profile.php?error=Invalid%20username%20size");
        exit;
    }
}



if(strlen($newPassword)!=0){
    if($newPassword==$confirmNewPassword){
        if(password_verify($password, $oldPassword)){
            if(validatePassword($newPassword)){
                $password_hash=password_hash($newPassword,PASSWORD_DEFAULT);
                $stmt = $db->prepare('UPDATE USER SET pw = :password_hash WHERE email = :email');
                $stmt->bindParam(':password_hash', $password_hash);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
            }
            else{
                header("Location: ../pages/edit_profile.php?error=Weak%20password");
                exit;
            }
        }
        else{
            header("Location: ../pages/edit_profile.php?error=Wrong%20password");
            exit;
        }

    }
    else{
        header("Location: ../pages/edit_profile.php?error=Passwords%20do%20not%20match");
        exit;
    }
}

if(strlen($country)!=0){
    if(preg_match("/^[a-zA-Z\s]+$/", $country)){
        $stmt = $db->prepare('UPDATE USER SET country = :country WHERE email = :email');
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_profile.php?error=Invalid%20country");
        exit;
    }
}
if(strlen($city)!=0){
    if(preg_match("/^[a-zA-Z\s]+$/", $city)){
        $stmt = $db->prepare('UPDATE USER SET city = :city WHERE email = :email');
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
    else {
        header("Location: ../pages/edit_profile.php?error=Invalid%20city");
        exit;
    }
}
if(strlen($address)!=0){
    if(preg_match("/^[a-zA-Z0-9.,\s\-]+$/", $address)){
        $stmt = $db->prepare('UPDATE USER SET userAddress = :address WHERE email = :email');
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_profile.php?error=Invalid%20address");
        exit;
    }
}

if(strlen($newEmail)!=0){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if($newEmailExists>0) {
            header("Location: ../pages/edit_profile.php?error=Email%20already%20exists");
            exit;
        }
        else{
            $stmt = $db->prepare('UPDATE USER SET email = :newEmail WHERE email = :email');
            $stmt->bindParam(':newEmail', $newEmail);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $_SESSION['email'] = $newEmail;
        }
    }
    else{
        header("Location: ../pages/edit_profile.php?Error=invalid_email");
        exit;
    }

}

header("Location: ../pages/items_display.php");
exit;

function validatePassword($password): bool
{
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/';
    if (preg_match($pattern, $password)) {
        return true;
    } else {
        return false;
    }
}