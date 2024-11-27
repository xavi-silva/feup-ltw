<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/login_page_style.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src = "../javascript/error_detector.js" defer></script>
    <script src="../javascript/togglemenu.js"> </script>
    <title>Ctrl+Sell | Login</title>
</head>
<div class="background-container"></div>
<?php include ('header.php')?>

    <body>
        <section id="login">
            <h1>Ctrl+<span class="sell">Sell</span></h1>
            <form action = "../actions/action_login.php" method = "post">
                <input id="email" type = "text" name = "email" placeholder="Email">
                <input id="password" type = "password" name = "password" placeholder="Password">
                <div id="error-message" style="color: red;"></div>
                <button type="submit">Sign Up</button>
            </form>
        </section>

        <section id = "noAccount">
            <h3>Don't have an account? <a href="register.php">Create one</a> </h3>
        </section>
    </body>
</html>