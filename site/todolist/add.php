<?php
  $task = $_POST['task'];
  if($task == '') {
    echo 'Введите само задание';
    exit();
  }

  require 'configDB.php';
  $sql = 'INSERT INTO items(content) VALUES(:task)';
  $query = $pdo->prepare($sql);
  $query->execute(['task' => $task]);

  header('Location: /');

