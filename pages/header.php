<?php
require_once('../database/connect.php');
$db = getDatabaseConnection();
$email = $_SESSION['email'];
$stmt = $db->prepare('SELECT * FROM USER WHERE email = :email');
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch();
$isUserAdmin=$user['isAdmin'];

$stmt = $db->prepare("SELECT COUNT(*) FROM SHOPPING_CART WHERE username = :username");
$stmt->bindValue(':username', $user['username']);
$stmt->execute();
$shoppingCartExists = $stmt->fetchColumn();

$stmt = $db->prepare(
    'SELECT ITEM.name, ITEM.price, img, ITEM.id FROM SHOPPING_CART AS SC
                JOIN ITEM ON ITEM.id = SC.itemId
                JOIN PHOTO ON ITEM.id = PHOTO.itemId
                WHERE username = :user');

$stmt->bindParam(':user', $user['username']);
$stmt->execute();
$cartItems = $stmt->fetchAll();

$cartUniqueItems = array();
foreach ($cartItems as $item) {
    if (!isset($cartUniqueItemsuniqueItems[$item['id']])) {
        $cartUniqueItems[$item['id']] = $item;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="../javascript/togglemenu.js"> </script>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body class="header-body">
<header class="top-bar">
    <div class="logo">
        <a href="items_display.php">
            <img src="/images/logo.png" alt="Your Logo">
        </a>
    </div>
    <div class="ctrlsell">
        <h1>
            Ctrl+<span class="sell">Sell</span>
        </h1>
    </div>
    <div id="icons">
        <div class="filter-icon" onclick="show('filters')">
            <i class="fas fa-filter"></i>
        </div>

        <?php if (isset($_SESSION['email'])) {?>
            <div class="user-icon" onclick="show('profile')">
                <i class="fas fa-user"></i>
            </div>
        <?php } else {?>
            <a href="login.php" class="user-icon" >
                <i class="fas fa-user"></i>
            </a>
        <?php }?>

        <?php if (isset($_SESSION['email'])) {?>
            <div class="cart-icon" onclick="show('shopping-cart')">
                <i class="fa fa-shopping-cart"></i>
            </div>
        <?php } else {?>
            <a href="login.php" class="cart-icon" >
                <i class="fa fa-shopping-cart"></i>
            </a>
        <?php }?>

        <div id="shopping-cart" class="shopping-cart">
            <button class="close-btn" onclick="hide('shopping-cart')">X</button>
            <h1> Shopping cart</h1>
            <?php foreach ($cartUniqueItems as $item): ?>
                <a href="item.php?id=<?php echo $item['id']; ?>" class="cart-items">
                    <img src=<?php echo $item['img'];?> >

                    <div id="cart-name">
                        <p> <?php echo $item['name'];?> </p>
                    </div>

                    <div id="cart-price">
                        <p> <?php echo $item['price'],"€";?> </p>
                    </div>
                </a>
            <?php endforeach; ?>


            <?php if ($shoppingCartExists>0){?>
            <div class="cart-buttons">
                <form action="checkout.php">
                    <button class="buy-now-btn">Buy Now</button>
                </form>
                <form action="../actions/action_empty_cart.php">
                    <button class="empty-cart-btn">Empty Cart</button>
                </form>
            </div>
            <?php }else{ ?>
                <h2> <?php echo "Your cart is empty";?> </h2>
                <?php } ?>

        </div>

        <div id="profile" class="profile">
            <button class="close-btn" onclick="hide('profile')">X</button>
            <h1><?php echo $user['realname'];?></h1>
            <h2><?php echo $user['username'];?></h2>
            <h2><?php echo $user['email'];?></h2>

            <ul id="profile-list">
                <li><a href="listed_items.php?type=purchased">Purchase history</a></li>
                <li><a href="listed_items.php?type=listed">Listed items</a></li>
                <li><a href="listed_items.php?type=sold">Sold items</a></li>
                <li><a href="conversations.php?user=<?php echo $user['username'];?>">Messages</a></li>
                <?php if($isUserAdmin){?>
                <li><a href="admin_page.php">Admin panel</a></li>
                <?php } ?>
                <li><a href="edit_profile.php">Edit account details</a></li>
                <li><a href="../actions/action_logout.php" onclick="return confirm('Are you sure you want to log out?');">Log out</a></li>
            </ul>
        </div>

        <div id="filters" class="filters">
            <button class="close-btn" onclick="hide('filters')">X</button>
            <h1> Filter</h1>
        </div>
    </div>
    <?php if(isset($_SESSION['email'])){?>
    <div class="sell-button">
        <a href="sell.php">
            <button>Sell now</button>
        </a>
    </div>
    <?php } else { ?>
        <div class="sell-button">
        <a href="login.php">
            <button>Sell now</button>
        </a>
    </div>
    <?php } ?>
</header>
</body>
</html>