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
    $stmt = $db->prepare('SELECT * FROM USER WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    $itemType = $_GET['type'];
    switch($itemType){
        case 'listed':
            $query = ('SELECT * FROM ITEM 
                    JOIN PHOTO ON PHOTO.itemId = ITEM.id 
                    JOIN CATEGORY ON ITEM.category = CATEGORY.categoryId
                    WHERE ITEM.sold = false and item.seller=:username');
            break;
        case 'sold':
            $query = ('SELECT * FROM ITEM 
                    JOIN PHOTO ON PHOTO.itemId = ITEM.id 
                    JOIN CATEGORY ON ITEM.category = CATEGORY.categoryId JOIN PURCHASE ON PURCHASE.item = ITEM.id
                    WHERE ITEM.sold = true and item.seller=:username');
            break;
        case 'purchased':
            $query = ('SELECT * FROM ITEM 
                    JOIN PHOTO ON PHOTO.itemId = ITEM.id 
                    JOIN CATEGORY ON ITEM.category = CATEGORY.categoryId
                    JOIN PURCHASE ON PURCHASE.item = ITEM.id WHERE purchase.buyer=:username');
            break;
    }
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $user['username']);
    $stmt->execute();
    $items = $stmt->fetchAll();

    // Items with multiple photos would appear <number of photos> times
    $uniqueItems = array();
    foreach ($items as $item) {
        if (!isset($uniqueItems[$item['id']])) {
            $uniqueItems[$item['id']] = $item;
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/listed_items_style.css">
    <link rel="stylesheet" href="/css/header.css">
    <script src="../javascript/dateformat.js"> </script>
    <script src="../javascript/togglemenu.js"> </script>
    <title>Ctrl+Sell | My Products</title>
</head>
<body>
<?php include ('header.php')?>

<div class="option-bar">
    <a href="listed_items.php?type=purchased" class="option" id="<?php echo ($itemType == 'purchased') ? 'selected' : ''; ?>">Purchased</a>
    <a href="listed_items.php?type=listed" class="option" id="<?php echo ($itemType == 'listed') ? 'selected' : ''; ?>">Listed</a>
    <a href="listed_items.php?type=sold" class="option" id="<?php echo ($itemType == 'sold') ? 'selected' : ''; ?>">Sold</a>
</div>

<?php foreach ($uniqueItems as $item): ?>
        <a href="item.php?id=<?php echo $item['id']; ?>" class="card">
                <img src=<?php echo $item['img'];?> >


            <div class="name">
                <p> <?php echo $item['name'];?> </p>
            </div>
            
            <div id="price">
                <p> <?php echo $item['price'],"€";?> </p>
            </div>

            <div class="uploaded">
                <?php if(($itemType=='purchased')||($itemType=='listed')){?>
                    <p> Uploaded <?php echo '<script>document.write(getTimeDifference("' . $item['postTimestamp'] . '"));</script>'; ?></p>
                <?php }else if($itemType=='sold'){
                    $stmt = $db->prepare('SELECT * FROM USER WHERE username=:username');
                    $stmt->bindParam(':username', $item['buyer']);
                    $stmt->execute();
                    $buyer = $stmt->fetch(); ?>
                    <h4> Buyer: <?php echo $item['buyer'];?> </h4>
                    <h4> Email: <?php echo $buyer['email'];?> </h4>
                    <h4>Phone number: <?php echo $buyer['phoneNo']?></h4>
                    <h4>Country: <?php echo $buyer['country']?></h4>
                    <h4>City: <?php echo $buyer['city']?></h4>
                    <h4>Address: <?php echo $buyer['userAddress']?></h4>


                <?php } ?>

            </div>
            
            
        </a>
    <?php endforeach; ?>
</body>
</html>