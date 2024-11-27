<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/register_page_style.css">
    <link rel="stylesheet" href="../css/header.css">
    <script src = "../javascript/error_detector.js" defer></script>
    <script src="../javascript/togglemenu.js"> </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ctrl+Sell | Register</title>
</head>
<body>
<?php include ('header.php')?>

    <div class="background-container"></div>
    <section id = "signUp">
        <h1>Welcome</h1>
        <form action="../actions/action_register.php" method = "post">
            First name <input id = "firstname" type = "text" name = "firstname">
            Last name <input id="lastname" type = "text" name = "lastname">
            Username <input id="username" type = "text" name = "username">
            Email <input id="email" type="text" name = "email" >
            Password <input id="password" type = "password" name = "password">
            Confirm password<input id="confirmPassword" type = "password" name = "confirmPassword">
            Phone number<input id="phoneNumber" type = "number" name = "phoneNumber">
            Country <input id="country" type="text" name="country">
            City <input id="city" type="text" name="city">
            Address <input id="address" type="text" name="address">
            <div id="error-message" style="color: red;"></div>
            <button type="submit">Create my account</button>
        </form>
    </section>
</body>
</html>