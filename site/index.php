<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Список дел</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="container">


    <?php
      require 'configDB.php';
      echo '<ul>';
      $list = $_GET['list'];
      $query = $pdo->query('SELECT * FROM `items` WHERE `list` ='.$list.'');
      $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$list.'');
      $name = $listrow->fetch(PDO::FETCH_OBJ);
      echo '<h1>List:'.$name->name.'</h1>
    <form action="/add.php?list='.$list.'" method="post" class="input">
      <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
      <button type="submit" name="sendTask" class="btn btn-success">Отправить</button>
    </form>
    <h1>Создание листа</h1>
    <form action="/add_list.php" method="post" class="input">
      <input type="text" name="list" id="list" placeholder="list" class="form-control" autocomplete="off">
      <button type="submit" name="createList" class="btn btn-success">Создать</button>
    </form>';
      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$row->list.'');
        $list = $listrow->fetch(PDO::FETCH_OBJ);
        echo '<li><div class="list">List: '.$list->name.'</div><b>'.$row->content.'</b><a href="/delete.php?id='.$row->item.'&list='.$row->list.'"><button class="delete">Удалить</button></a></li>';
      }
      $listrow = $pdo->query('SELECT * FROM `list`');
      echo '<div id="lists">';
      while($list = $listrow->fetch(PDO::FETCH_OBJ)){
        echo ' <a href="/index.php?list='.$list->id.'"><button>'.$list->name.'</button></a>';
      }
      echo '</div>';
      echo '</ul>';
    ?>
  </div>
</body>
</html>
