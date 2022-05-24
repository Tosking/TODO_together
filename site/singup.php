<?php
$title="Форма регистрации";
require "configDB.php";
echo '
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Список дел</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<div class="container mt-4">
    <div class="row">
        <div class="col">

            <h2>Регистрация</h2>
            <form action="/singup.php" method="post">
                <input type="text" class="form-control" name="name" id="name_" placeholder="Введите имя" autocomplete="off"><br>
                <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин" autocomplete="off"><br>
                <input type="email" class="form-control" name="email" id="email" placeholder="Введите Email" autocomplete="off"><br>
                <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" autocomplete="off"><br>
                <input type="password" class="form-control" name="password_2" id="password_2" placeholder="Повторите пароль" autocomplete="off"><br>
                <button class="btn btn-success" name="do_signup" type="submit">Зарегистрировать</button>
            </form>
            <br>
            <p>Если вы зарегистрированы, тогда нажмите <a href="login.php">здесь</a>.</p>
        </div>
    </div>
</div>
';


// Создаем переменную для сбора данных от пользователя по методу POST
$data = $_POST;

// Пользователь нажимает на кнопку "Зарегистрировать" и код начинает выполняться
if(isset($data['do_signup'])) {

    // Регистрируем
    // Создаем массив для сбора ошибок
    $errors = array();

    // Проводим проверки
    // trim — удаляет пробелы (или другие символы) из начала и конца строки
    if(trim($data['login']) == '') {

        $errors[] = "Введите логин!";
    }

    if(trim($data['email']) == '') {

        $errors[] = "Введите Email";
    }


    if($data['password'] == '') {

        $errors[] = "Введите пароль";
    }

    if($data['password_2'] != $data['password']) {

        $errors[] = "Повторный пароль введен не верно!";
    }
    // функция mb_strlen - получает длину строки
    // Если логин будет меньше 5 символов и больше 90, то выйдет ошибка
    if(mb_strlen($data['login']) < 3 || mb_strlen($data['login']) > 90) {

        $errors[] = "Недопустимая длина логина";

    }

    if (mb_strlen($data['password']) < 2 || mb_strlen($data['password']) > 16){

        $errors[] = "Недопустимая длина пароля (от 2 до 16 символов)";

    }

    // проверка на правильность написания Email
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {

        $errors[] = 'Неверно введен е-mail';

    }

    // Проверка на уникальность логина
    $login = $data['login'];
    $uniqueCheckQ = $pdo->query('SELECT * FROM `user` WHERE `login` ="'.$login.'"');
    $checkResult = $uniqueCheckQ->fetch(PDO::FETCH_OBJ);
    if ($checkResult != 0){

        $errors[] = "Пользователь с таким логином существует!";
    }

    // Проверка на уникальность email

    $email = $data['email'];
    $uniqueCheck = $pdo->query('SELECT * FROM `user` WHERE `email` ="'.$email.'"');
    $checkResult = $uniqueCheck->fetch(PDO::FETCH_OBJ);
    if ($checkResult != 0){

        $errors[] = "Пользователь с таким Email существует!";
    }

    echo empty($errors);
    if(empty($errors)) {
        $password = $data['password'];
        $name = $data['name'];
        // Все проверено, регистрируем
        $sql = 'INSERT INTO user(name, login, email, password) VALUES("'.$name.'" , "'.$login.'" , "'.$email.'" , "'.$password.'")';

        $query = $pdo->prepare($sql);
        $query->execute();
        session_start();
        $_SESSION['id'] = $pdo->query('SELECT `user_id` FROM `user` WHERE `login` ="'.$login .'"')->fetch(PDO::FETCH_OBJ)->user_id;

        header('Location: /index.php');

        // Хешируем пароль
        //$user->password = password_hash($data['password'], PASSWORD_DEFAULT);


    } else {
        // array_shift() извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент.
        echo '<div style="color: red;">' . array_shift($errors). '</div><hr>';
    }
}
?>
