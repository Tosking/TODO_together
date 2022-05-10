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
    <h1>Список дел</h1>
    <form action="/add.php" method="post">
      <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control">
      <input type="text" name="list" id="list" placeholder="list" class="form-control">
      <button type="submit" name="sendTask" class="btn btn-success">Отправить</button>
    </form>
    <h1>Создание листа</h1>
    <form action="/add_list.php" method="post">
      <input type="text" name="list" id="list" placeholder="list" class="form-control">
      <button type="submit" name="createList" class="btn btn-success">Создать</button>
    </form>

    <?php
      require 'configDB.php';
      echo '<ul>';
      $query = $pdo->query('SELECT * FROM `items` ORDER BY `list` DESC');
      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        echo '<li><h3>List:'.$row->list.'</h3><b>'.$row->content.'</b><a href="/delete.php?id='.$row->item.'&list='.$row->list.'"><button>Удалить</button></a></li>';
      }
      echo '</ul>';
    ?>
  </div>
</body>
</html>
