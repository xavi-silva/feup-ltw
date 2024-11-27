<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
session_start();
require_once('../database/connect.php');
$db = getDatabaseConnection();

$email = $_SESSION['email'];
$stmt = $db->prepare('SELECT * FROM USER WHERE email = :email');
$stmt->bindParam(':email' , $email);
$stmt->execute();
$user = $stmt->fetch();
$seller = $user['username'];

$category=$_POST["Categories"];
$stmt=$db->prepare('SELECT categoryId FROM CATEGORY WHERE categoryName = :category');
$stmt->bindParam(':category',$category);
$stmt->execute();
$categoryRow= $stmt->fetch(PDO::FETCH_ASSOC);
$categoryId=$categoryRow['categoryId'];

$condition=$_POST["Condition"];
$stmt=$db->prepare('SELECT conditionId FROM CONDITION WHERE conditionName = :condition');
$stmt->bindParam(':condition',$condition);
$stmt->execute();
$conditionRow= $stmt->fetch(PDO::FETCH_ASSOC);
$conditionId=$conditionRow['conditionId'];

$name=$_POST["titleInp"];
$details=$_POST["descriptionInp"];
$postTimestamp=date('Y-m-d H:i:s');
$price=$_POST["priceInp"];
$brand=$_POST["brandInp"];
$model=$_POST["ModelInp"];
$releaseYear=$_POST["yearInp"];

if(strlen($name)==0){
    header("Location: ../pages/sell.php?error=Item%20name%20not%20provided");
    exit;
}
if(!preg_match ("/^[a-zA-Z0-9\s._-]+$/", $name)){
    header("Location: ../pages/sell.php?error=Invalid%20item%20name");
    exit;
}
if(strlen($category)==0){
    header("Location: ../pages/sell.php?error=Item%20category%20not%20provided");
    exit;
}
if(strlen($brand)==0){
    header("Location: ../pages/sell.php?error=Item%20brand%20not%20provided");
    exit;
}
if(!preg_match ("/^[a-zA-Z0-9\s._-]+$/", $brand)){
    header("Location: ../pages/sell.php?error=Invalid%20item%20brand");
    exit;
}

if(strlen($model)==0){
    header("Location: ../pages/sell.php?error=Item%20model%20not%20provided");
    exit;
}
if(!preg_match ("/^[a-zA-Z0-9\s._-]+$/", $model)){
    header("Location: ../pages/sell.php?error=Invalid%20item%20model");
    exit;
}
if(strlen($releaseYear)==0){
    header("Location: ../pages/sell.php?error=Item%20release%20year%20not%20provided");
    exit;
}
if(!preg_match ("/^[0-9]+$/", $releaseYear)){
    header("Location: ../pages/sell.php?error=Invalid%20item%20release%20year");
    exit;
}
if($releaseYear<1950){
    header("Location: ../pages/sell.php?error=Item%20release%20year%20cannot%20be%20under%201950");
    exit;
}
if($releaseYear>date('Y')){
    header("Location: ../pages/sell.php?error=Item%20release%20year%20cannot%20be%20above%20".date("Y"));
    exit;
}
if(strlen($condition)==0){
    header("Location: ../pages/sell.php?error=Item%20condition%20not%20provided");
    exit;
}


if(strlen($details)==0){
    header("Location: ../pages/sell.php?error=Item%20description%20not%20provided");
    exit;
}
if(!preg_match ("/^[a-zA-Z0-9\s._\-,:;!?()\[\]]+$/", $details)){
    header("Location: ../pages/sell.php?error=Invalid%20item%20description");
    exit;
}

if(strlen($price)==0){
    header("Location: ../pages/sell.php?error=Item%20price%20not%20provided");
    exit;
}

if(!preg_match("/^\d+(,\d{1,2})?$/", $price)){
    header("Location: ../pages/sell.php?error=Invalid%20item%20price");
    exit;
}









$stmtMaxId = $db->prepare('SELECT MAX(id) AS max_id FROM ITEM');
$stmtMaxId->execute();
$result = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
$maxId = $result['max_id'];
$id = ($maxId !== null) ? ($maxId + 1) : 1;
$sold = 0;

$stmt = $db->prepare('INSERT INTO ITEM (id,name, details, seller,postTimestamp,price,brand,model,category,releaseYear,condition,sold)
                       VALUES (:id,:name, :details, :seller,:postTimestamp,:price,:brand,:model,:category,:releaseYear,:condition,:sold)');
$stmt->bindParam(':id',$id);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':details', $details);
$stmt->bindParam(':seller', $seller);
$stmt->bindParam(':postTimestamp', $postTimestamp);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':brand', $brand);
$stmt->bindParam(':model', $model);
$stmt->bindParam(':category', $categoryId);
$stmt->bindParam(':releaseYear', $releaseYear);
$stmt->bindParam(':condition', $conditionId);
$stmt->bindParam(':sold',$sold);
$stmt->execute();

$itemId = $db->lastInsertId();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['imagesInp'])) {
    $photos = $_FILES['imagesInp'];
    $numFiles = count($photos['name']);

    for ($i = 0; $i < $numFiles; $i++) {
        $photoName = '../product_images/' . uniqid() . '_' . basename($photos['name'][$i]); // Guarantee a unique name
        $photoPath = '../product_images/' . $photoName;

        if (move_uploaded_file($photos['tmp_name'][$i], $photoPath)) {

            $stmtMaxPhotoId = $db->prepare('SELECT MAX(photoId) AS max_photo_id FROM PHOTO');
            $stmtMaxPhotoId->execute();
            $resultPhotoId = $stmtMaxPhotoId->fetch(PDO::FETCH_ASSOC);
            $maxPhotoId = $resultPhotoId['max_photo_id'];
            $nextPhotoId = ($maxPhotoId !== null) ? ($maxPhotoId + 1) : 1;

            $stmtPhoto = $db->prepare('INSERT INTO PHOTO (photoId, itemId, img) VALUES (:photoId, :itemId, :img)');
            $stmtPhoto->bindParam(':photoId', $nextPhotoId);
            $stmtPhoto->bindParam(':itemId', $itemId);
            $stmtPhoto->bindParam(':img', $photoName);
            $stmtPhoto->execute();
        } else {
            echo "Error uploading file: " . $photos['name'][$i] . "<br>";
        }
    }
} else {
    echo "No files uploaded.";
}
header("Location: ../pages/items_display.php");
exit;
?>