<?php
   require 'configDB.php';
   $login = $_POST['login'];
   $list = $_GET['list'];
   $query =$pdo->query('SELECT * FROM  `user` WHERE `login`="'.$login.'"');
   $row =$query->fetch(PDO::FETCH_OBJ);
   echo $row->login;
   if($query==0)
   {
       //header('Location: /list.php?list='.$list.'');
   }
   else
   {
       $name = 'INSERT INTO list_to_user(user,list,access) VALUES('.$row->user_id.','.$list.',0)';
       $query = $pdo->prepare($name);
       $query->execute();
       //header('Location: /list.php?list='.$list.'');
   }

