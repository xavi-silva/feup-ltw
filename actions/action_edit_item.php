<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script>alert('Access denied');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$itemId = $_GET['id'];
$name = $_POST['name'];
$category = $_POST['category'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$year = $_POST['year'];
$condition = $_POST['condition'];
$description =$_POST['description'];
$price = $_POST['price'];


$stmt=$db->prepare('SELECT categoryId FROM CATEGORY WHERE categoryName = :category');
$stmt->bindParam(':category',$category);
$stmt->execute();
$categoryRow= $stmt->fetch();
$categoryId=$categoryRow['categoryId'];

$stmt=$db->prepare('SELECT conditionId FROM CONDITION WHERE conditionName = :condition');
$stmt->bindParam(':condition',$condition);
$stmt->execute();
$conditionRow= $stmt->fetch();
$conditionId=$conditionRow['conditionId'];

if($year>=1950 && $year<=1999){
    $yearId=1;
}
else if($year>=2000 && $year<=2004){
    $yearId=2;
}
else if($year>=2005 && $year<=2009){
    $yearId=3;
}
else if($year>=2010 && $year<=2014){
    $yearId=4;
}
else if($year>=2015 && $year<=2019){
    $yearId=5;
}
else if($year>=2020 && $year<=date("Y")){
    $yearId=6;
}

if(strlen($name) != 0){
    if(preg_match ("/^[a-zA-Z0-9\s._-]+$/", $name)) {
        $stmt = $db->prepare('UPDATE ITEM SET name = :name WHERE id = :itemId');

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_item.php?error=Invalid%20name&id=".$itemId);
        exit;
    }
}

if(strlen($category) != 0){
    $stmt = $db->prepare('UPDATE ITEM SET category = :categoryId WHERE id = :itemId');
    $stmt->bindParam(':categoryId', $categoryId);
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
}


if(strlen($brand) != 0){
    if(preg_match ("/^[a-zA-Z0-9\s._-]+$/", $brand)) {
        $stmt = $db->prepare('UPDATE ITEM SET brand = :brand WHERE id = :itemId');

        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_item.php?error=Invalid%20brand&id=".$itemId);
        exit;
    }
}

if(strlen($model) != 0){
    if(preg_match ("/^[a-zA-Z0-9\s._-]+$/", $model)) {
        $stmt = $db->prepare('UPDATE ITEM SET model = :model WHERE id = :itemId');

        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_item.php?error=Invalid%20model&id=".$itemId);
        exit;
    }
}

if(strlen($year) != 0){
    if(preg_match ("/^[0-9]+$/", $year)) {
        if($year>=1950){
            if($year<=date("Y")){
                $stmt=$db->prepare('UPDATE ITEM SET releaseYear = :year WHERE id = :itemId');
                $stmt->bindParam(':year', $year);
                $stmt->bindParam(':itemId', $itemId);
                $stmt->execute();
            }
            else{
                header("Location: ../pages/edit_item.php?error=Release%20year%20cannot%20be%20above%20".date("Y")."&id=".$itemId);
                exit;
            }
        }
        else{
            header("Location: ../pages/edit_item.php?error=Release%20year%20cannot%20be%20under%201950&id=".$itemId);
            exit;
        }

    }
    else{
        header("Location: ../pages/edit_item.php?error=Invalid%year&id=".$itemId);
        exit;
    }
}
if(strlen($condition) != 0){
    $stmt = $db->prepare('UPDATE ITEM SET condition = :conditionId WHERE id = :itemId');
    $stmt->bindParam(':conditionId', $conditionId);
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
}


if(strlen($description) != 0){
    if(preg_match ("/^[a-zA-Z0-9\s._\-,:;!?()\[\]]+$/", $description)) {
        $stmt = $db->prepare('UPDATE ITEM SET details = :description WHERE id = :itemId');
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_item.php?error=Invalid%20description&id=".$itemId);
        exit;
    }

}
if(strlen($price) != 0){
    if(preg_match("/^\d+(,\d{1,2})?$/", $price)) {
        $stmt = $db->prepare('UPDATE ITEM SET price = :price WHERE id = :itemId');
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':itemId', $itemId);
        $stmt->execute();
    }
    else{
        header("Location: ../pages/edit_item.php?error=Price%20can%20only%20have%200%20or%202%20decimal%20places&id=".$itemId);
        exit;
    }
}
header("Location: ../pages/item.php?id=" . $itemId);
exit;

