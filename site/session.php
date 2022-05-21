<?php

require 'configDB.php';
$login = $_POST['login'];
$pass = $_POST['pass'];

$logcheck = $pdo->query('SELECT * FROM `user` WHERE `login` ="' . $login . '"');
$row = $logcheck->fetch(PDO::FETCH_OBJ);
if (1 && ($row->password != $pass)) {
    header('Location: /login.php');
}
else{
    session_start();
    $id = $pdo->query('SELECT `user_id` FROM `user` WHERE `login` ="' . $login . '"');
    $_SESSION['id'] = $id->fetch(PDO::FETCH_OBJ)->user_id;
        header('Location: /index.php');
}
