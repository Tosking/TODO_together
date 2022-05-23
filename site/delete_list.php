<?php
    require 'configDB.php';

    $id = $_GET['id'];

    $sql = 'DELETE FROM `list_to_user` WHERE `list` ='.$id;
    $query = $pdo->prepare($sql);
    $query->execute();

    $sql = 'DELETE FROM `list` WHERE `id` ='.$id;
    $query = $pdo->prepare($sql);
    $query->execute();



    header('Location: /index.php?list='.$id.'');
