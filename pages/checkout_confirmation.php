<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl+Sell | Purchase confirmation</title>
    <link rel="stylesheet" href="/css/checkout_style.css">

</head>
<body>

<?php include ('header.php')?>
<div class="confirmation-container">
    <h1>Purchase Completed</h1>
    <p>Your purchase has been processed successfully. Thanks for shopping with us!</p>
    <button onclick=window.location.href='items_display.php'> Return to main page</button>
</div>
</body>
</html>
