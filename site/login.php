<?php
$title="Форма авторизации";
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
            <h2>Авторизация</h2>
            <form action="session.php" method="post">
                <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин" required><br>
                <input type="password" class="form-control" name="password" id="pass" placeholder="Введите пароль" required><br>
                <button class="btn btn-success" name="do_login" type="submit">Авторизоваться</button>
            </form>
            <br>
            <p>Если вы еще не зарегистрированы, тогда нажмите <a href="/singup.php">здесь</a>.</p>
            <p>Вернуться на <a href="index.php">главную</a>.</p>
        </div>
    </div>
</div>
'
?>