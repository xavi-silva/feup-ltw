<?php
  function getDatabaseConnection() : PDO{
    $db = new PDO('sqlite:' . __DIR__ . '/ctrlsell.db');
    $db->exec("PRAGMA foreign_keys = ON;");
    return $db;
  }
?>
