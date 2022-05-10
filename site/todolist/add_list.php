<?php
    $list = $_POST['list'];
    if($list == '') {
        $list = 0;
    }

    require 'configDB.php';
    $sql = 'INSERT INTO list(name) VALUES('.$list.')';
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: /');

