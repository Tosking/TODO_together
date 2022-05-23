<?php
  $dsn = 'mysql:host=localhost;dbname=todolist';
    $pdo = new PDO($dsn, 'root', '12345');
/*try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    }
 catch(PDOException $e)
    {
        echo 'Подключение не удалось: '.$e->getMessage();
    }
*/
 ?>