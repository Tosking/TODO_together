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
      $user = $_SESSION['id'];
      if($_SESSION['id'] == null || empty($_SESSION)){
        header("Location: /login.php");
      }
      require 'configDB.php';
      echo '<ul>';
      $list = $_GET['list'];
      $access = $pdo->query('SELECT `access` FROM `list_to_user` WHERE `user` ='.$user.' AND `list` ='.$list.'')->fetch(PDO::FETCH_OBJ)->access;
      $query = $pdo->query('SELECT * FROM `items` WHERE `list` ='.$list.'');
      $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$list.'');
      $name = $listrow->fetch(PDO::FETCH_OBJ);
      echo '<h1>'.$name->name.'</h1>
    <form action="/add.php?list='.$list.'" method="post" class="input">
      <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
      <button type="submit" name="sendTask" class="btn btn-success">Создать</button>
    </form>
      <div id="memlist"><div id="members"><h2>Участники:</h2>
        ';
      $memberrow = $pdo->query('SELECT * FROM `list_to_user` WHERE `list` = '.$list.' AND `access` <> 0');
      $num = 1;
        while($member = $memberrow->fetch(PDO::FETCH_OBJ)){
          $member_name = $pdo->query('SELECT * FROM `user` WHERE `user_id` ='.$member->user.'');
          $member_name = $member_name->fetch(PDO::FETCH_OBJ)->name;
          echo '<div class="member">'.$num.'. '.$member_name.'';
          if($member->access == 3){
            echo '<div class="access">  Создатель</div>';
          }
          if($member->access == 2){
            echo '<div class="access">  Админ';
            if ($access)
            echo '</div>';
          }
          if($member->access == 1){
            echo '<div class="access">  Участник</div>';
          }

          echo '</div>';

          $num++;
        }
        echo '</div>';
        if($access >= 2){
          echo'<div id="invite"><form action="/sendrequest.php?list='.$list.'" method="post">
          <input type="text" name="login" size = "15" id="login" placeholder="Введите логин" autocomplete="off">
          <button id = "addmembers"  name="sendTask" type ="submit" class="btn btn-success">Пригласить</button> </form></div>';
        }
        echo'
      </div>';


      while($row = $query->fetch(PDO::FETCH_OBJ)) {
        $listrow = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$row->list.'');
        $list_id = $listrow->fetch(PDO::FETCH_OBJ);
        $sub_cont = $pdo->query('SELECT * FROM `sub_content` WHERE `item` ='.$row->item.'');
        echo '<li><a href="/delete.php?id='.$row->item.'&list='.$row->list.'"><button class="delete">Удалить</button></a>';
        echo '<b>'.$row->content.'</b>';

        while($sub_content = $sub_cont->fetch(PDO::FETCH_OBJ)){
            echo '<div class="sub_content"><a class="adelete" href="delete_subcont.php?sub_id='.$sub_content->id.'&list='.$list.'">• '.$sub_content->content.'</a></div>';
        }

        echo'
        <form action="/add_subcont.php?list='.$list.'&item='.$row->item.'" method="post" class="sub">
        <input type="text" name="task" id="task" placeholder="Нужно сделать.." class="form-control" autocomplete="off">
        <button type="submit" name="sendTask" class="btn btn-success" id="subbut">Создать подзадачу</button>
        </form>
        </li>';
      }
      $listrow = $pdo->query('SELECT `list` FROM `list_to_user` WHERE `user` ='.$user.'');

      if($access == '3'){
        echo '<h4> <a href = "delete_list.php?id='.$list.'"><button id = "delete">Удалить лист</button></h4></a>';
      }
      echo '<div id="lists">';
      echo '<h1><b>Листы</b></h1>';
      while($listid = $listrow->fetch(PDO::FETCH_OBJ)) {
          $accesslist = $pdo->query('SELECT `access` FROM `list_to_user` WHERE `user` ='.$user.' AND `list` ='.$listid->list.'')->fetch(PDO::FETCH_OBJ)->access;
          if($accesslist != 0){
            $blist = $pdo->query('SELECT * FROM `list` WHERE `id` ='.$listid->list.'');
            $blist = $blist->fetch(PDO::FETCH_OBJ);
            echo '<a href="/list.php?list='.$blist->id.'"><button id ="listes">'.$blist->name.'</button></a>';
          }
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
