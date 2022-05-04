<?php
  require 'configDB.php';
  $dsn = 'mysql:host=localhost;dbname=to-do';
  $pdo = new PDO($dsn, 'root', '12345');

  $id = $_GET['id'];

  $sql = 'DELETE FROM `items` WHERE `content` = ?';
  $query = $pdo->prepare($sql);
  $query->execute([$id]);

  header('Location: /');
?>
