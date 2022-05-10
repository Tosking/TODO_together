<?php
  require 'configDB.php';
  $dsn = 'mysql:host=localhost;dbname=todolist';
  $pdo = new PDO($dsn, 'taskavan', '8252');

  $id = $_GET['id'];
  $list = $_GET['list'];

  $sql = 'DELETE FROM `items` WHERE `item` ='.$id.'AND WHERE `list` ='.$list;
  $query = $pdo->prepare($sql);
  $query->execute();

  header('Location: /');
?>
