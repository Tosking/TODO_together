<?php
require 'configDB.php';
$list = $_GET['list'];
$user = $_GET['user'];

$sql = 'UPDATE `list_to_user` SET `access` = 1 WHERE `list` ='.$list.' AND `user` ='.$user.'';
$pdo->prepare($sql)->execute();
header('Location: /index.php');
?>
