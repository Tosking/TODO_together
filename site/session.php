<?php
session_start();
require 'configDB.php';
$login = $_POST['login'];
$pass = $_POST['pass'];

$row = $pdo->query('SELECT * FROM `user` WHERE `login` =' . $login . '');
if ($row != '') {
    header('Location: /login.php');
}






}