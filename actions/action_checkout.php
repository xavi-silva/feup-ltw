<?php
session_start();

require_once('../database/connect.php');
$db = getDatabaseConnection();
$buyer_username=$_SESSION['username'];

$stmt = $db->prepare("SELECT * from SHOPPING_CART WHERE username = :buyer_username");
$stmt->bindParam(':buyer_username', $buyer_username);
$stmt->execute();
$shopping_carts = $stmt->fetchAll();

$stmtMaxId = $db->prepare('SELECT MAX(purchaseId) AS max_id FROM PURCHASE');
$stmtMaxId->execute();
$result = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
$maxId = $result['max_id'];
$purchaseId = ($maxId !== null) ? ($maxId + 1) : 1;

foreach ($shopping_carts as $shopping_cart) {
    $productId=$shopping_cart['itemId'];
    $stmt = $db->prepare("UPDATE ITEM SET sold = true WHERE id = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $stmt = $db->prepare("DELETE FROM SHOPPING_CART WHERE itemId = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();

    $stmt = $db->prepare("INSERT INTO PURCHASE (purchaseId,buyer,item,purchaseDate) VALUES (:id,:buyer,:item,:purchaseDate)");
    $stmt->bindValue(':id', $purchaseId);
    $stmt->bindValue(':buyer', $buyer_username);
    $stmt->bindValue(':item', $shopping_cart['itemId']);
    $stmt->bindValue(':purchaseDate', date('Y-m-d'));
    $stmt->execute();

    $purchaseId = $purchaseId + 1;




}

header('Location: ../pages/checkout_confirmation.php');
exit;