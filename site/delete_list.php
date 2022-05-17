<?php
    require 'configDB.php';

    $name = $_GET['name'];
    $id = $_GET['id'];

    $sql = 'DELETE FROM `list` WHERE `id` ='.$id;
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: /index.php?list='.$id.'');
