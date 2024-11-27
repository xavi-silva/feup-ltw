
<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
$itemId=$_GET['id'];



$stmt = $db->prepare("SELECT * FROM ITEM WHERE id = :itemId");
$stmt->bindParam(':itemId', $itemId);
$stmt->execute();
$userItem=$stmt->fetch();

if($_SESSION['username']!=$userItem['seller']){
    echo "<script>alert('You do not have the right access');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();

}

$stmt = $db->prepare("SELECT * FROM CATEGORY WHERE categoryId = :categoryId");
$stmt->bindParam(':categoryId', $userItem['category']);
$stmt->execute();
$itemCategory=$stmt->fetch();

$stmt = $db->prepare("SELECT * FROM CONDITION WHERE conditionId = :conditionId");
$stmt->bindParam(':conditionId', $userItem['condition']);
$stmt->execute();
$itemCondition=$stmt->fetch();

$stmt = $db->prepare("SELECT * FROM CATEGORY");
$stmt->execute();
$categories=$stmt->fetchAll();

$stmt = $db->prepare("SELECT * FROM CONDITION");
$stmt->execute();
$conditions=$stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src = "../javascript/error_detector.js" defer></script>
    <title>Ctrl+Sell | Edit Item</title>
</head>
<?php include ('header.php')?>
<body>
<img id="return" src="/images/x.png" style="width:30px" onclick="window.location.href='item.php?id=<?php echo $itemId; ?>'">
<div id="publish">
    <h1 id="function">Edit your item</h1>

    <form action = "../actions/action_edit_item.php?id=<?php echo $itemId ?>" method="post">
        <h2 id="title">Name</h2>
        <input id="titleInp" type = "text" name = "name" placeholder="<?php echo $userItem['name'];?>">

        <h2 id="category">Category</h2>
        <select id="categoriesInp" name="category">
            <option value="" disabled selected><?php echo htmlspecialchars($itemCategory['categoryName']) ?></option>
            <?php foreach($categories as $category): ?>
            <option value="<?php echo $category['categoryName']; ?>"><?php echo htmlspecialchars($category['categoryName']); ?></option>
            <?php endforeach; ?>
        </select>

        <h2 id="brand">Brand</h2>
        <input name="brand" type="text" placeholder="<?php echo htmlspecialchars($userItem['brand']);?>">

        <h2 id="model">Model</h2>
        <input name="model" type="text" placeholder="<?php echo htmlspecialchars($userItem['model']);?>"">

        <h2 id="year">Release year</h2>
        <input id="yearInp" name="year" type="number" placeholder="<?php echo htmlspecialchars($userItem['releaseYear']);?>">

        <h2 id="condition">Condition</h2>
        <select id="conditionInp" name="condition">
            <option value="" disabled selected><?php echo htmlspecialchars($itemCondition['conditionName']) ?></option>
            <?php foreach($conditions as $condition): if($condition['conditionId']==6)continue;?>
            <option value="<?php echo $condition['conditionName']; ?>"><?php echo htmlspecialchars($condition['conditionName']); ?></option>
            <?php endforeach; ?>
        </select>
        <h2 id="description">Description</h2>
        <input name="description" type="text" placeholder="<?php echo htmlspecialchars($userItem['details']);?>">

        <h2 id="price">Price</h2>
        <input id="price" name="price" type="text"placeholder="<?php echo htmlspecialchars($userItem['price']);?>€">
        <p></p>
        <div id="error-message" style="color: red;"></div>
        <button id="submit" type="submit">Save</button>
    </form>
</div>
</body>
</html>
