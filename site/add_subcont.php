 <?php
    $task = $_POST['task'];
    $item = $_GET['item'];
    $list = $_GET['list'];
    if($task == '') {
        echo 'Введите само задание';
        exit();
    }

    require 'configDB.php';

    $sql = 'INSERT INTO sub_content(item, content) VALUES('.$item.' ,"'.$task.'")';

    $query = $pdo->prepare($sql);
    $query->execute();

    header('Location: /list.php?list='.$list.'');
