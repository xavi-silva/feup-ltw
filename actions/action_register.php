<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$firstName=$_POST["firstname"];
$lastName=$_POST["lastname"];
$username=$_POST["username"];
$email=$_POST["email"];
$password=$_POST["password"];
$confirmPassword=$_POST["confirmPassword"];
$phoneNumber=$_POST["phoneNumber"];
$country=$_POST["country"];
$city=$_POST["city"];
$address=$_POST["address"];


if (strlen($firstName) == 0) {
    header("Location: ../pages/register.php?error=Insert%20first%20name");
    exit;
}
if(!preg_match("/^[a-zA-Z]+$/", $firstName)){
    header("Location: ../pages/register.php?error=Invalid%20first%20name");
    exit;
}
if (strlen($lastName) == 0) {
    header("Location: ../pages/register.php?error=Insert%20last%20name");
    exit;
}
if(!preg_match("/^[a-zA-Z]+$/", $lastName)){
    header("Location: ../pages/register.php?error=Invalid%20last%20name");
    exit;
}
if (strlen($username) < 5) {
    header("Location: ../pages/register.php?error=Username%20with%20less%20than%205%20characters");
    exit;
}
if (strlen($username) > 15) {
    header("Location: ../pages/register.php?error=Username%20with%20more%20than%2015%20characters");
    exit;
}
if(!preg_match("/^[a-zA-Z0-9_-]+$/", $username)){
    header("Location: ../pages/register.php?error=Invalid%20username%20");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../pages/register.php?error=Invalid%20email%20format");
    exit;
}

if (strlen($password) < 8) {
    header("Location: ../pages/register.php?error=Password%20with%20less%20than%208%20characters");
    exit;
}
if (!preg_match('/[A-Z]/', $password)) {
    header("Location: ../pages/register.php?error=Password%20without%20uppercase%20character");
    exit;
}

if (!preg_match('/[a-z]/', $password)) {
    header("Location: ../pages/register.php?error=Password%20without%20lowercase%20character");
    exit;
}
if (!preg_match('/[0-9]/', $password)) {
    header("Location: ../pages/register.php?error=Password%20without%20digit%20character");
    exit;
}
if (!ctype_digit($phoneNumber)){
    header("Location: ../pages/register.php?error=Phone%20number%20is%20not%20an%20integer");
    exit;
}
if (strlen($phoneNumber) != 9) {
    header("Location: ../pages/register.php?error=Phone%20number%20does%20not%20have%209%20digits");
    exit;
}
if (strlen($country) == 0) {
    header("Location: ../pages/register.php?error=Insert%20a%20country");
    exit;
}
if(!preg_match("/^[a-zA-Z\s]+$/", $country)){
    header("Location: ../pages/register.php?error=Invalid%20country%20name");
    exit;
}
if (strlen($city) == 0) {
    header("Location: ../pages/register.php?error=Insert%20a%20city");
    exit;
}
if(!preg_match("/^[a-zA-Z\s]+$/", $city)){
    header("Location: ../pages/register.php?error=Invalid%20city%20name");
    exit;
}

if (strlen($address) == 0) {
    header("Location: ../pages/register.php?error=Insert%20an%20address");
    exit;
}
if(!preg_match("/^[a-zA-Z0-9.,\s\-]+$/", $address)){
    header("Location: ../pages/register.php?error=Invalid%20address");
    exit;
}
if($password != $confirmPassword){
    header("Location: ../pages/register.php?error=Passwords%20do%20not%20match");
    exit;
}

$password_hash=password_hash($password,PASSWORD_DEFAULT);

$stmt = $db->prepare("SELECT COUNT(*) FROM USER WHERE username = :username");
$stmt->bindValue(':username', $username);
$stmt->execute();
$usernameExists = $stmt->fetchColumn();
if($usernameExists > 0){
    header("Location: ../pages/register.php?error=UsernameAlreadyExists");
    exit;
}


$stmt = $db->prepare("SELECT COUNT(*) FROM USER WHERE email = :email");
$stmt->bindValue(':email', $email);
$stmt->execute();
$emailExists = $stmt->fetchColumn();
if($emailExists > 0){
    header("Location: ../pages/register.php?error=EmailAlreadyExists");
    exit;
}
$stmt = $db->prepare("SELECT COUNT(*) FROM USER WHERE phoneNo = :phoneNumber");
$stmt->bindValue(':phoneNumber', $phoneNumber);
$stmt->execute();
$phoneNumberExists = $stmt->fetchColumn();
if($phoneNumberExists > 0){
    header("Location: ../pages/register.php?error=UsernameAlreadyExists");
    exit;
}
$name = $firstName . " " . $lastName;


$stmt = $db->prepare('INSERT INTO USER (realname, username, pw, email,phoneNo,userAddress,country,city)
                       VALUES (:realname, :username, :pw, :email,:phoneNo,:userAddress,:country,:city)');
$stmt->bindParam(':realname', $name);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':pw', $password_hash);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':phoneNo', $phoneNumber);
$stmt->bindParam(':userAddress', $address);
$stmt->bindParam(':country', $country);
$stmt->bindParam(':city', $city);


$stmt->execute();

header("Location: ../pages/login.php");

?>


