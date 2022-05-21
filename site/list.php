<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Todolist</title>
  <link rel="icon" href="logo/logo.png">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <header><a href ="/index.php"><img src ="logo/logo.png" width="100" height="100" alt ="logo"></a></header>
  <div class="container">
    <?php
      session_start();
      require 'configDB.php';
      echo '<ul>';
      $list = $_GET['list'];
      $query = $pdo->query('SELECT * FROM `items` WHERE `list` ='.$list.'');
      $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$list.'');
      $name = $listrow->fetch(PDO::FETCH_OBJ);
      $user = $_SESSION['id'];
      echo '<h1>'.$name->name.'</h1>
    <form action="/add.php?list='.$list.'" method="post" class="input">
      <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
      <button type="submit" name="sendTask" class="btn btn-success">Создать</button>
    </form>';
      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$row->list.'');
        $list_id = $listrow->fetch(PDO::FETCH_OBJ);
        $sub_cont = $pdo->query('SELECT * FROM `sub_content` WHERE `item` ='.$row->item.'');
        echo '<li><a href="/delete.php?id='.$row->item.'&list='.$row->list.'"><button class="delete">Удалить</button></a>';
        echo '<b>'.$row->content.'</b>';

        while($sub_content = $sub_cont->fetch(PDO::FETCH_OBJ)){
            echo '<div class="sub_content">• '.$sub_content->content.'</div>';
        }

        echo'
        <form action="/add_subcont.php?list='.$list.'&item='.$row->item.'" method="post" class="sub">
        <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
        <button type="submit" name="sendTask" class="btn btn-success" id="subbut">Создать подзадачу</button>
        </form>
        </li>';
      }
      $listrow = $pdo->query('SELECT `list` FROM `list_to_user` WHERE `user` ='.$user.'');
      echo '<h4> <a href = "delete_list.php?id='.$list.'"><button id = "delete">Удалить лист</button></h4></a>';
      echo '<div id="lists">';
      echo '<h1><b>Листы</b></h1>';
      while($listid = $listrow->fetch(PDO::FETCH_OBJ)) {
          $list = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$listid->list.'');
          $list = $list->fetch(PDO::FETCH_OBJ);
          echo '<a href="/list.php?list='.$list->id.'"><button id ="listes">'.$list->name.'</button></a>';
      }
      echo '</div>';
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
