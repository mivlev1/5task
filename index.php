<?php
// Сессии являются простым способом хранения информации для отдельных пользователей с уникальным идентификатором сессии.
// Это может использоваться для сохранения состояния между запросами страниц.
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Массив для временного хранения сообщений пользователю.
$messages = [];
//Массив для ошибок
$errors = [];
$trimed = [];

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
        // Удаляем куки, указывая время устаревания в прошлом.
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        // Выводим сообщение пользователю.
        $messages[] = 'Спасибо, результаты сохранены.';
        // Если в куках есть пароль, то выводим сообщение.
        if (!empty($_COOKIE['pass'])) {
            $messages[] = sprintf(
                'Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass'])
            );
        }
    }
    //Проверяем на не пустоту
    $errors['name'] = !empty($_COOKIE['error_name']);
    $errors['email'] = !empty($_COOKIE['error_email']);
    $errors['bd'] = !empty($_COOKIE['error_bd']);
    $errors['pol'] = !empty($_COOKIE['error_pol']);
    $errors['limbs'] = !empty($_COOKIE['error_limbs']);
    $errors['contract'] = !empty($_COOKIE['error_contract']);


    //Удаление cookies(через установление даты устаревания в прошедшем времени) и вывод сообщений об ошибках заполнения полей
    if ($errors['name']) {
        // Удаляем куку, указывая время устаревания в прошлом.
        setcookie('error_name', '', 100000);
        // Выводим сообщение.
        $messages[] = '<div ">Заполните имя.</div>';
    }
    if ($errors['email']) {
        setcookie('error_email', '', 100000);
        $messages[] = '<div ">Заполните почту.</div>';
    }
    if ($errors['bd']) {
        setcookie('error_bd', '', 100000);
        $messages[] = '<div ">Заполните др.</div>';
    }
    if ($errors['pol']) {
        setcookie('error_pol', '', 100000);
        $messages[] = '<div ">Заполните пол.</div>';
    }
    if ($errors['limbs']) {
        setcookie('error_limbs', '', 100000);
        $messages[] = '<div ">Заполните конечности.</div>';
    }
    if ($errors['contract']) {
        setcookie('error_contract', '', 100000);
        $messages[] = '<div ">Заполните условия.</div>';
    }

    // Складываем предыдущие значения полей в массив, если есть.
    // При этом санитизуем все данные для безопасного отображения в браузере.
    $values = array();
    $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
    $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
    $values['bd'] = empty($_COOKIE['bd_value']) ? '' : strip_tags($_COOKIE['bd_value']);
    $values['pol'] = empty($_COOKIE['pol_value']) ? '' : strip_tags($_COOKIE['pol_value']);
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : strip_tags($_COOKIE['limbs_value']);
    $values['contract'] = empty($_COOKIE['contract_value']) ? '' : strip_tags($_COOKIE['contract_value']);

    $values['superpowers'] = array();
    $values['superpowers'][0] = empty($_COOKIE['superpowers_value_0']) ? '' : $_COOKIE['superpowers_value_0'];
    $values['superpowers'][1] = empty($_COOKIE['superpowers_value_1']) ? '' : $_COOKIE['superpowers_value_1'];
    $values['superpowers'][2] = empty($_COOKIE['superpowers_value_2']) ? '' : $_COOKIE['superpowers_value_2'];
    $values['superpowers'][3] = empty($_COOKIE['superpowers_value_3']) ? '' : $_COOKIE['superpowers_value_3'];

    // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
    // ранее в сессию записан факт успешного логина.
    session_start();
    if (empty($errors) && !empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
        // загружаем данные пользователя из БД
        // и заполнить переменную $values,
        // предварительно санитизовав.
        $user = 'u41731';
        $pass = '7439940';
        $db = new PDO('mysql:host=localhost;dbname=u41731', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

        $stmt1 = $db->prepare('SELECT name, email, bd, pol, limbs FROM FORM WHERE user_id = ?');
        $stmt1->execute([$_SESSION['uid']]);
        $row = $stmt1->fetch(PDO::FETCH_ASSOC);
        $values['name'] = strip_tags($row['name']);
        $values['email'] = strip_tags($row['email']);
        $values['bd'] = strip_tags($row['bd']);
        $values['pol'] = strip_tags($row['pol']);
        $values['limbs'] = strip_tags($row['limbs']);

        $stmt2 = $db->prepare('SELECT id_sup FROM super_to_usr WHERE user_id = ?');
        $stmt2->execute([$_SESSION['uid']]);
        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $values['superpowers'][$row['id_sup']] = TRUE;
        }

        printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }

    // Включаем содержимое файла form.php.
    // В нем будут доступны переменные $messages, $errors и $values для вывода
    // сообщений, полей с ранее заполненными данными и признаками ошибок.

    include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
    // Проверяем ошибки.
    $errors = FALSE;

    //проверка корректности заполненных полей
    if ((empty($_POST['name']))) {
        setcookie('error_name', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        // Сохраняем ранее введенное в форму значение на месяц.
        setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    }


    if (!preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $_POST['email'])) {
        setcookie('error_email', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    }


    if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['bd'])) {
        setcookie('error_bd', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('bd_value', $_POST['bd'], time() + 30 * 24 * 60 * 60);
    }


    if (!preg_match('/^[MFO]$/', $_POST['pol'])) {
        setcookie('error_pol', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('pol_value', $_POST['pol'], time() + 30 * 24 * 60 * 60);
    }


    if (!preg_match('/^[2-6]$/', $_POST['limbs'])) {
        setcookie('error_limbs', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
    }


    if (!isset($_POST['contract'])) {
        setcookie('error_contract', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('contract_value', $_POST['contract'], time() + 30 * 24 * 60 * 60);
    }


    foreach ($_POST['superpowers'] as $super) {
        setcookie('superpowers_value_' . $super, 'true', time() + 30 * 24 * 60 * 60);
    }

    if ($errors) {
        // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
        header('Location: index.php');
        exit();
    } else {
        // Удаляем Cookies с признаками ошибок.
        setcookie('error_name', '', 100000);
        setcookie('error_email', '', 100000);
        setcookie('error_pol', '', 100000);
        setcookie('error_limbs', '', 100000);
        setcookie('error_bd', '', 100000);
        setcookie('error_contract', '', 100000);
    }


    // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
        // Перезаписываю данные в БД новыми данными,
        // кроме логина и пароля.
        $user = 'u41731';
        $pass = '7439940';
        $db = new PDO('mysql:host=localhost;dbname=u41731', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

        $stmt1 = $db->prepare("UPDATE FORM SET name = ?, email = ?, bd = ?, pol= ? , kon = ? WHERE user_id = ?");
        $stmt1->execute([$_POST['name'], $_POST['email'], $_POST['bd'], $_POST['pol'], $_POST['limbs'], $_SESSION['uid']]);

        $stmt2 = $db->prepare('DELETE FROM super_to_usr WHERE user_id = ?');
        $stmt2->execute([$_SESSION['uid']]);

        $stmt3 = $db->prepare("INSERT INTO super_to_usr SET user_id = ?, id_sup = ?");
        foreach ($_POST['superpowers'] as $super) $stmt3->execute([$lastId, $super]);
    } else {
        // Генерируем уникальный логин и пароль.
        //  сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
        //Сделал через uniqid()
        $id = uniqid();
        $hash = md5($id);
        $login = substr($hash, 0, 10);
        $pass = substr($hash, 10, 15);
        /*SHA-2 (Secure Hash Algorithm Version 2 — безопасный алгоритм хеширования, версия 2) —
          это название однонаправленных хеш-функций SHA-224, SHA-256, SHA-384 и SHA-512.
          Хеш-функции предназначены для создания «отпечатков» или «дайджестов» сообщений произвольной битовой длины.
          Применяются в различных приложениях или компонентах, связанных с защитой информации.*/
        $hash_pass = substr(hash("sha256", $pass), 0, 20);
        // Сохраняем в Cookies.
        setcookie('login', $login);
        setcookie('pass', $pass);

        $user = 'u41731';
        $pass_db = '7439940';
        $db = new PDO('mysql:host=localhost; dbname=u41731', $user, $pass_db, array(PDO::ATTR_PERSISTENT => true));


        $stmt1 = $db->prepare("INSERT INTO FORM SET name = ?, email = ?, bd = ?, pol= ? , kon = ?, login = ?, hash_pass = ?");
        $stmt1->execute([$_POST['name'], $_POST['email'], $_POST['bd'], $_POST['pol'], $_POST['limbs'], $login, $hash_pass]);
        $user_id = $db->lastInsertId();
        $stmt2 = $db->prepare("INSERT INTO super_to_usr SET user_id = ?, id_sup = ?");

        foreach ($_POST['superpowers'] as $super) $stmt2->execute([$user_id, $super]);
    }
    // Сохраняем куку с признаком успешного сохранения.
    setcookie('save', '1');
    // Делаем перенаправление.
    header('Location: ./');
    //Эхх
}
