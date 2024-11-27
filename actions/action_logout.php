<?php

session_start();

$_SESSION = array();

session_destroy();

header("Location: ../pages/items_display.php");
exit();

