<?php
  $task = $_POST['task'];
  $list = $_GET['list'];
  if($task == '') {
    header('Location: /list.php?list='.$list.'');
  }
  else {
    require 'configDB.php';
    $sql = 'INSERT INTO items(list, content, is_completed) VALUES('.$list.' ,"'.$task.'" , 0)';
    $query = $pdo->prepare($sql);
    $query->execute();
  }

  header('Location: /list.php?list='.$list.'');

