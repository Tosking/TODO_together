<?php

require 'configDB.php';
$login = $_POST['login'];
$pass = $_POST['pass'];

$logcheck = $pdo->query('SELECT * FROM `user` WHERE `login` =' . $login . '');
$row = $logcheck->fetch(PDO::FETCH_OBJ);
if ($row != 0 && $row->password) {
    header('Location: /login.php');
}
else{
    session_start();
    $id = $pdo->query('SELECT `id` FROM `user` WHERE `login` =' . $login . '');
    $_SESSION['id'] = $id->fetch(PDO::FETCH_OBJ);
        header('Location: /index.php');
}