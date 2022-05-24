 
<?php
    require 'configDB.php';
    $user = $_GET['user'];
    $list = $_GET['list'];
    $sql = 'DELETE FROM `list_to_user` WHERE `user` ='.$user.' AND `list` ='.$list.'';
    $pdo->prepare($sql)->execute();
    header('Location: /list.php?list='.$list.'');
?>
