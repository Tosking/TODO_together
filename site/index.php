<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Список дел</title>
  <link rel="stylesheet" href="css/style_index.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <header><a href ="/index.php"><img src ="logo/logo.png" width="100" height="100" alt ="logo"></a></header>
  <div class="container">


    <?php
      session_start();
      require 'configDB.php';
      echo '<ul>';
      $user = $_SESSION['id'];
      echo '
    <h1>Создание листа</h1>
    <form action="/add_list.php?user='.$user.'" method="post" class="input">
      <input type="text" name="list" id="list" placeholder="list" class="form-control" autocomplete="off">
      <button type="submit" name="createList" class="btn btn-success">Создать</button>
    </form>';

      $userlists = $pdo->query('SELECT `list` FROM `list_to_user` WHERE `user` ='.$user.'');
      echo '<div id="lists"><h1>Листы:</h1>';
      while($listid = $userlists->fetch(PDO::FETCH_OBJ)){
        $list = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$listid->list.'');
        $list = $list->fetch(PDO::FETCH_OBJ);
        echo ' <a href="/list.php?list='.$list->id.'"><button>'.$list->name.'</button></a>';
      }
      echo '</div>';
      echo '</ul>';
      $user_name = $pdo->query('SELECT `login` FROM `user` WHERE `user_id`='.$user.'')->fetch(PDO::FETCH_OBJ)->login;
      echo '
      <a href="/singup.php"> <button id="reg"> Регистрация </button></a>
      <a href="/login.php"> <button id="login"> Вход </button></a>
      <div id="name">Вы вошли как: <strong>'.$user_name.'</strong></div>
      ';
      ?>
  </div>



</body>
</html>
