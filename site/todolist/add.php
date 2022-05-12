<?php
  $task = $_POST['task'];
  $list = $_GET['list'];
  if($task == '') {
    echo 'Введите само задание';
    exit();
  }

  require 'configDB.php';
  $sql = 'INSERT INTO items(list, content, is_completed) VALUES('.$list.' ,"'.$task.'" , 0)';
  $query = $pdo->prepare($sql);
  $query->execute();

  header('Location: /index.php?list='.$list.'');

