<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Список дел</title>
  <link rel="icon" href="logo/logo.png">
  <link rel="stylesheet" href="css/style_index.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <header><a href ="/index.php"><img src ="logo/logo.png" width="100" height="100" alt ="logo"></a></header>
  <div class="container">


    <?php
      session_start();
      if($_SESSION['id'] == null || empty($_SESSION)){
        header("Location: /login.php");
      }
      require 'configDB.php';
      echo '<ul>';
      $user = $_SESSION['id'];
      echo '
    <h1>Создание листа</h1>
    <form action="/add_list.php?user='.$user.'" method="post" class="input">
      <input type="text" name="list" id="list" placeholder="Название листа" class="form-control" autocomplete="off">
      <button type="submit" name="createList" class="btn btn-success">Создать</button>
    </form>';
      $invites = $pdo->query('SELECT * FROM `list_to_user` WHERE `user` ='.$user.' AND `access` =0');
      if($invites->rowCount() != 0){
         echo '<div id="invites"><h2>Приглашения:</h2>';
         while($row = $invites->fetch(PDO::FETCH_OBJ)){
           $listname = $pdo->query('SELECT `name` FROM `list` WHERE `id` ='.$row->list.'')->fetch(PDO::FETCH_OBJ)->name;
           echo '<a href="accept.php?list='.$row->list.'&user='.$user.'"><button class="invite">'.$listname.'</button></a>';
         }
         echo '</div>';
      }
            $userlists = $pdo->query('SELECT *  FROM `list_to_user` WHERE `user` ='.$user.' AND `access` <> 0');
      if($userlists->rowCount() != 0){
        echo '<div id="lists"><h1>Листы:</h1>';
        while($listid = $userlists->fetch(PDO::FETCH_OBJ)){
          $accesslist = $pdo->query('SELECT `access` FROM `list_to_user` WHERE `user` ='.$user.' AND `list` ='.$listid->list.'')->fetch(PDO::FETCH_OBJ)->access;
          if($accesslist != 0){
            $list = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$listid->list.'');
            $list = $list->fetch(PDO::FETCH_OBJ);
            echo ' <a href="/list.php?list='.$list->id.'"><button>'.$list->name.'</button></a>';
          }
        }
        echo '</div>';
      }
      echo '</ul>';
      $user_name = $pdo->query('SELECT `login` FROM `user` WHERE `user_id`='.$user.'')->fetch(PDO::FETCH_OBJ)->login;
      echo '
      <a href="/logout.php"> <button id="quit"> Выход </button></a>
      <div id="name">Вы вошли как: <strong>'.$user_name.'</strong></div>
      ';
      ?>
  </div>



</body>
</html>
