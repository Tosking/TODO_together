<?php
    require 'configDB.php';

    $sub_id = $_GET['sub_id'];
    $list = $_GET['list'];

    $sql = 'DELETE FROM `sub_content` WHERE `id` ='.$sub_id;
    $query = $pdo->prepare($sql);
    $query->execute();
    header('Location: /list.php?list='.$list.'');

?>
