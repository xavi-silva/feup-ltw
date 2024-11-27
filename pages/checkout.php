<?php
require_once('../database/connect.php');
session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}
$db = getDatabaseConnection();

$buyer_username = $_SESSION['username'];

$stmt = $db->prepare('SELECT * FROM USER WHERE userName = :buyer_username');
$stmt->bindParam(':buyer_username', $buyer_username);
$stmt->execute();
$buyer_data = $stmt->fetch();


$stmt = $db->prepare("SELECT * from SHOPPING_CART WHERE username = :buyer_username");
$stmt->bindParam(':buyer_username', $buyer_username);
$stmt->execute();
$shopping_carts = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/checkout_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl+Sell | Checkout</title>
</head>
<body>
<?php  include('header.php'); ?>
<div class="container">
    <h2>Checkout</h2>
    <div class="checkout">
        <!-- Exibir informações do carrinho -->
        <h3>Products in cart:</h3>
        <ul>
            <?php
            $totalPrice = 0;
            foreach ($shopping_carts as $shopping_cart) {
                $stmt = $db->prepare('SELECT * FROM ITEM WHERE id = :id');
                $stmt->bindParam(':id',$shopping_cart['itemId'] );
                $stmt->execute();
                $product = $stmt->fetch();
                    echo "<li>{$product['name']} - {$product['price']}€</li>";
                    $totalPrice += $product['price'];

            }
            echo "<li>Total Price: {$totalPrice}€</li>";
            ?>
        </ul>
        <!-- Exibir informações do usuário -->
        <h3>User information:</h3>
        <?php
        // Obter informações do usuário da sessão

            echo "<p><strong>Name:</strong> {$buyer_data['realname']}</p>";
            echo "<p><strong>Email:</strong> {$buyer_data['email']}</p>";
            echo "<p><strong>Phone number:</strong> {$buyer_data['phoneNo']}</p>";
            echo "<p><strong>Address:</strong> {$buyer_data['userAddress']}, {$buyer_data['city']}, {$buyer_data['country']}</p>";
        ?>
        <form action="../actions/action_checkout.php ">
            <button type="submit">Purchase</button>
        </form>
    </div>
</div>
</body>
</html>