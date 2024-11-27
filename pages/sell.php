<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}
require_once('../database/connect.php');
$db = getDatabaseConnection();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src = "../javascript/error_detector.js" defer></script>
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../javascript/previewImage.js" defer></script>
    <title>Ctrl+Sell | Sell</title>
</head>
<body>
<?php include ('header.php')?>


<img id="return" src="/images/x.png" style="width:30px" onclick="window.location.href='items_display.php'">
<div id="publish">
    <h1 id="function">Publish an Announcement</h1>

<form action="../actions/action_sell_page.php" method="post" enctype="multipart/form-data">

<h2 id="title">Name</h2>

<input id="titleInp" name="titleInp" type="text"  >

<h2 id="category">Category</h2>

<select id="categoriesInp" name="Categories" >
<option value="" disabled selected>Select a category</option>
    <?php foreach($categories as $category): ?>
        <option value="<?php echo $category['categoryName']; ?>"><?php echo $category['categoryName']; ?></option>
    <?php endforeach; ?>
</select>

<h2 id="brand">Brand</h2>

<input name="brandInp" type="text"  >

<h2 id="model">Model</h2>

<input name="ModelInp" type="text"  >

<h2 id="year">Release year</h2>
<input id="yearInp" name="yearInp" type="number"  >

<h2 id="condition">Condition</h2>

<select id="conditionInp" name="Condition" >
<option value="" disabled selected>Select a condition</option>
    <?php foreach($conditions as $condition): if($condition['conditionId']==6)continue;?>
        <option value="<?php echo $condition['conditionName']; ?>"><?php echo $condition['conditionName']; ?></option>
    <?php endforeach; ?>
</select>

<h2 id="images">Images</h2>

<input id='imagesInp' name="imagesInp[]" type="file" title="Please provide an image for your ad" multiple required>
<div id="imagePreview"></div>

<h2 id="description">Description</h2>

<input name="descriptionInp" type="text" >

<h2 id="price">Price</h2>
<input id="priceInp" name="priceInp" type="text" >

<p></p>
<div id="error-message" style="color: red;"></div>
<button type="submit">Save</button>
</form>
</div>

</body>
</html>
