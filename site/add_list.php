<?php
    $list = $_POST['list'];
    $user = $_GET['user'];
    if($list == '') {
        header('Location: /');
    }

    require 'configDB.php';
    $sql = 'INSERT INTO list(name) VALUES("'.$list.'")';
    $query = $pdo->prepare($sql);
    $query->execute();
    $list = $pdo->query('SELECT MAX(id) AS "id" FROM list WHERE name ="'.$list.'"');
    $id = $list->fetch(PDO::FETCH_OBJ);
    $sql = 'INSERT INTO list_to_user(user, list, access) VALUES('.$user.', '.$id->id.', 3)';
    echo $sql;
    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: /index.php?list='.$id->id.'');

