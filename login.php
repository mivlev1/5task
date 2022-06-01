<!DOCTYPE html>
<html lang="ru">


<head>
    <meta charset="utf-8"/>
    <title>5task</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="style-login.css" rel="stylesheet">
</head>

<body>
<div class="container-lg px-0">

    <div class="main  row mx-auto">
        <section id="form">
            <h2>Авторизация</h2>
            <?php

            /**
             * Файл login.php для не авторизованного пользователя выводит форму логина.
             * При отправке формы проверяет логин/пароль и создает сессию,
             * записывает в нее логин и id пользователя.
             * После авторизации пользователь перенаправляется на главную страницу
             * для изменения ранее введенных данных.
             **/

            // Отправляем браузеру правильную кодировку,
            // файл login.php должен быть в кодировке UTF-8 без BOM.
            header('Content-Type: text/html; charset=UTF-8');

            // Начинаем сессию.
            session_start();

            // В суперглобальном массиве $_SESSION хранятся переменные сессии.
            // Будем сохранять туда логин после успешной авторизации.
            if (!empty($_SESSION['login'])) {
                // Если есть логин в сессии, то пользователь уже авторизован.
                // TODO: Сделать выход (окончание сессии вызовом session_destroy()
                //при нажатии на кнопку Выход).
                // Делаем перенаправление на форму.
                session_destroy();
                header('Location: ./');
            }

            // В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
            // и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if (!empty($_GET['nologin']))
                    print("<div>Пользователя с таким логином не существует</div>");
                if (!empty($_GET['wrongpass']))
                    print("<div>Неверный пароль!</div>");


                ?>

                <div class="login-page">
                    <div class="form">
                        <form class="login-form" action="" method="post">
                            <input class="input-field" name="login"  placeholder="логин"/>
                            <input class="input-field" name="pass" placeholder="пароль"/>
                            <input class="gradient-button" type="submit" value="войти">
                        </form>
                    </div>
                </div>


                <?php
            }
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
//Не работает why? Опять проблема с таблицами ? Не понимаю...
            else {
                $user = 'u41731';
                $pass = '7439940';
                $db = new PDO('mysql:host=localhost; dbname=u41731', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

                $stmt1 = $db->prepare('SELECT  user_id, hash_pass FROM FORM WHERE login = ?');
                $stmt1->execute([$_POST['login']]);

                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                if (!$row) {
                    header('Location: ?nologin=1');
                    exit();
                }

                $pass_hash = substr(hash("sha256", $_POST['pass']), 0, 20);
                if ($row['hash_pass'] != $pass_hash) {
                    header('Location: ?wrongpass=1');
                    exit();
                }
                // Если все ок, то авторизуем пользователя.
                $_SESSION['login'] = $_POST['login'];
                // Записываем ID пользователя.
                $_SESSION['uid'] = $row['user_id'];

                // Делаем перенаправление.
                header('Location: ./');
            }
            ?>

        </section>
    </div>
</body>
</html>
