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
  <div class="container">


    <?php
      require 'configDB.php';
      echo '<ul>';
      $user = 1;
      echo '
    <h1>Создание листа</h1>
    <form action="/add_list.php?user='.$user.'" method="post" class="input">
      <input type="text" name="list" id="list" placeholder="list" class="form-control" autocomplete="off">
      <button type="submit" name="createList" class="btn btn-success">Создать</button>
    </form>';
      $listrow = $pdo->query('SELECT * FROM `list`');
      echo '<div id="lists"><h1>Листы:</h1>';
      while($list = $listrow->fetch(PDO::FETCH_OBJ)){
        echo ' <a href="/list.php?list='.$list->id.'"><button>'.$list->name.'</button></a>';
      }
      echo '</div>';
      echo '</ul>';
    ?>
  </div>
</body>
</html>
