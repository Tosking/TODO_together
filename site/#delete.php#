<?php
  require 'configDB.php';

  $id = $_GET['id'];
  $list = $_GET['list'];

  $sql = 'DELETE FROM `items` WHERE `item` ='.$id;
  $query = $pdo->prepare($sql);
  $query->execute();

  header('Location: /list.php?list='.$list.'');
?>
