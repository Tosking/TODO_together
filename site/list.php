<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Todolist</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <header><a href ="/index.php"><img src ="logo/logo.png" width="200" height="70" alt ="logo"></a></header>
  <div class="container">
    <?php
      require 'configDB.php';
      echo '<ul>';
      $list = $_GET['list'];
      $query = $pdo->query('SELECT * FROM `items` WHERE `list` ='.$list.'');
      $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$list.'');
      $name = $listrow->fetch(PDO::FETCH_OBJ);
      $user = 1;
      echo '<h1>Лист:'.$name->name.'</h1>
    <form action="/add.php?list='.$list.'" method="post" class="input">
      <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
      <button type="submit" name="sendTask" class="btn btn-success">Создать</button>
    </form>';
      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$row->list.'');
        $list_id = $listrow->fetch(PDO::FETCH_OBJ);
        $sub_cont = $pdo->query('SELECT * FROM `sub_content` WHERE `item` ='.$row->item.'');

        echo '<li><b>'.$row->content.'</b>';

        while($sub_content = $sub_cont->fetch(PDO::FETCH_OBJ)){
            echo '<div>'.$sub_content->content.'</div>';
        }

        echo'
        <form action="/add_subcont.php?list='.$list.'&item='.$row->item.'" method="post" class="input">
        <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
        <button type="submit" name="sendTask" class="btn btn-success">Создать подзадачу</button>
        </form>
        <a href="/delete.php?id='.$row->item.'&list='.$row->list.'"><button class="delete">Удалить</button></a></li>';
      }
      $listrow = $pdo->query('SELECT * FROM `list`');
      echo '<h4> <a href = "delete_list.php?id='.$list.'"><button id = "delete">Удалить лист</button></h4></a>';
      echo '<div id="lists">';
      echo '<h1><b>Листы</b></h1>';
      while($list = $listrow->fetch(PDO::FETCH_OBJ)) {
          echo '<a href="/list.php?list='.$list->id.'"><button id ="listes">'.$list->name.'</button></a>';
      }
      echo '</div>';
      echo '</ul>';
    ?>
  </div>
</body>
</html>
