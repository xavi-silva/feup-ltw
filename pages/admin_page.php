<?php

session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('You are not logged in');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/login.php'; }, 100);</script>";
    exit();
}

require_once('../database/connect.php');
$db = getDatabaseConnection();
$stmt= $db->prepare("SELECT * FROM USER WHERE username = :username");
$stmt->bindValue(':username', $_SESSION['username']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$user['isAdmin']){
    echo "<script>alert('You do not have the right access');</script>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/items_display.php'; }, 100);</script>";
    exit();
}

$stmt = $db->prepare("SELECT * FROM USER");
$stmt->execute();
$users=$stmt->fetchAll();
$stmt = $db->prepare("SELECT * FROM CATEGORY");
$stmt->execute();
$categories=$stmt->fetchAll();

$option = $_GET['option'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../javascript/error_detector.js" defer></script>
    <title>Ctrl+Sell | Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<?php include('header.php'); ?>
<div class="option-bar">
    <a href="?option=users" class="option" onclick="showContent('users'); return false;">Add Admin</a>
    <a href="?option=category" class="option" onclick="showContent('category'); return false;">Categories</a>
    <a href="?option=condition" class="option" onclick="showContent('condition'); return false;">Conditions</a>
</div>
<div id="main-container">
    <div id="content">
        <h2>Admin View</h2>
        <p>Select what you want to manage.</p>
        <div id="error-message"></div>
    </div>
</div>
<script>
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    function showContent(option, error = '') {
        const content = document.getElementById('content');
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_content.php?option=' + option, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                content.innerHTML = xhr.responseText;
                content.classList.add('visible');
                if (error) {
                    const errorMessageElement = document.getElementById('error-message');
                    errorMessageElement.textContent = decodeURIComponent(error);
                    errorMessageElement.style.color = 'red';
                }
            }
        };
        xhr.send();

        const url = new URL(window.location);
        url.searchParams.set('option', option);
        if (error) {
            url.searchParams.set('error', error);
        } else {
            url.searchParams.delete('error');
        }
        history.pushState({}, '', url);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const option = params.get('option') || '';
        const error = params.get('error') || '';
        if (option) {
            showContent(option, error);
        } else {
            document.getElementById('content').classList.add('visible'); // Show initial content if no option
        }

        if (error) {
            const errorMessageElement = document.getElementById('error-message');
            errorMessageElement.textContent = error;
            errorMessageElement.style.color = 'red';
        }
    });

    window.addEventListener('popstate', function () {
        const params = new URLSearchParams(window.location.search);
        const option = params.get('option') || '';
        const error = params.get('error') || '';
        if (option) {
            showContent(option, error);
        }
    });
</script>
</body>
</html>