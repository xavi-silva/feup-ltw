<?php
    session_start();
    require_once('../database/connect.php');
    $db = getDatabaseConnection();
    $email = $_SESSION['email'];
    $stmt = $db->prepare("SELECT * FROM USER WHERE email = :email");
    $stmt->bindParam(':email' , $email);
    $stmt->execute();
    $user = $stmt->fetch();
    $username = $user['username'];
    $isUserAdmin = $user['isAdmin'];
    $itemId = $_GET['id'];


    $stmt = $db->prepare("SELECT * FROM ITEM WHERE id = :itemId");
    $stmt->bindParam(':itemId' , $itemId);
    $stmt->execute();
    $item = $stmt->fetch();
    $seller = $item['seller'];
    $isItemSold = $item['sold'];


    $stmt = $db->prepare("SELECT COUNT(*) FROM SHOPPING_CART WHERE username = :username and itemId = :itemId");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':itemId', $itemId);
    $stmt->execute();
    $itemInCart = $stmt->fetchColumn();


    $stmt = $db->prepare(
        'SELECT * FROM PHOTO
        JOIN ITEM ON PHOTO.itemId = ITEM.id 
        JOIN USER ON seller = username
        JOIN CATEGORY ON category = categoryId
        JOIN CONDITION ON condition = conditionId
        --JOIN RELEASE_YEAR ON startYear < releaseYear and releaseYear < endYear
        WHERE ITEM.id = :itemId');
    $stmt->bindParam(':itemId', $itemId);
    $stmt->execute();
    
    $images = array();

    $item = $stmt->fetch();
    $images[] = $item['img'];
    
    while ($aux = $stmt->fetch()) {
        $images[] = $aux['img'];
    }

    echo '<script>';
    echo 'const images = ' . json_encode($images) . ';'; // Pass $images array to JavaScript
    echo 'console.log(images);'; // Log the images array to the console
    echo '</script>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/item_page_style.css">
    <link rel="stylesheet" href="/css/item_page_layout.css">
    <link rel="stylesheet" href="/css/item_page_responsive.css">
    <script src="../javascript/dateformat.js"> </script>
    <script src="../javascript/switchimage.js"> </script>
    <script src="../javascript/previouspage.js"> </script>
    <title>Ctrl+Sell | <?php echo $item['name']?> </title>
    
</head>

<body>
    <div id="image">
        <img id="product" src=<?php echo $images[0]?>>
        <img id="right" src="/images/arrow_right.png" style="width:50px" onclick="changeImage(1)">
        <img id="left" src="/images/arrow_left.png" style="width:50px" onclick="changeImage(-1)">
        <img id="return" src="/images/x.png" style="width:30px" onclick=window.location.href='items_display.php'>
    </div>
    <div id="details">
        <h1 id="itemName"> <?php echo $item['name']?></h1>
        <h2 id="itemPrice"><?php echo $item['price']?>€</h2>
        <article id="information">
            <ul id="tags">
                <li><span class="label">Brand</span><?php echo $item['brand']?></li>
                <li><span class="label">Model</span><?php echo $item['model']?></li>
                <li><span class="label">Condition</span><?php echo $item['conditionName']?></li>
                <li><span class="label">Location</span><?php echo $item['userAddress']?></li>
                <!-- <li><span class="label">Payment Options</span><?php echo $item['conditionName']?></li> -->
                <li><span class="label">Uploaded</span><?php echo '<script>document.write(getTimeDifference("' . $item['postTimestamp'] . '"));</script>'; ?></li>
                <li><span class="label">Released</span><?php echo $item['releaseYear']?></li>
            </ul>
        </article>
        <article id="text">
            <?php echo $item['details']?>
        </article>
        <article id="bottom">
            <?php if($username!=$seller){ ?>
                <?php if(isset($_SESSION['email'])){
                            if($itemInCart==0 && !$isItemSold){?>
                                <form  action="../actions/action_add_to_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $itemId;?>">
                                    <button class="detailsButton" type="submit" id="addToCart">Add to cart</button>
                                </form>
                            <?php } else if($itemInCart!=0 && !$isItemSold){ ?>
                                <form  action="../actions/action_remove_from_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $itemId;?>">
                                    <button class="detailsButton" type="submit" id="removeFromCart">Remove from cart</button>
                                </form>
                            <?php } ?>
                <?php }else{?>
                    <button class="detailsButton" id="addToCart" onclick=window.location.href='login.php'>Add to cart</button>
                <?php } ?>
            <?php } ?>

            <?php if($username!=$seller && isset($_SESSION['email'])){ ?>
            <button class="detailsButton" id="askAQuestion" onclick=window.location.href='chat.php?seller=<?php echo $item['seller'];?>'> Ask a question</button>
            <?php } ?>

            <?php if(($seller==$username) && (!$isItemSold)){?>
                <form  action="edit_item.php?id=<?php echo $itemId;?>" method="post">
                    <button class="detailsButton" id="editItem">Edit Item</button>
                </form>

            <?php } ?>

            <?php if(($seller==$username || $isUserAdmin) && (!$isItemSold)){?>
            <form action="../actions/action_remove_item.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $itemId;?>">
                <button class="detailsButton" type="submit" id="deleteItem" onclick="return confirm('Are you sure you want to remove this item?')"> Delete Item </button>
            </form>
            <?php } ?>


        </article>
    </div>

</body>
</html>

