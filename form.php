<!DOCTYPE html>
<html lang="ru">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5task</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
<div class="container-lg px-0">
    <div class="main row mx-auto">
        <?php
        if (!empty($messages)) {
            print('<div id="messages">');
            // Выводим все сообщения.
            foreach ($messages as $message) {
                print($message);
            }
            print('</div>');
        }
        // Далее выводим форму отмечая элементы с ошибками классом error
        // и задавая начальные значения элементов ранее сохраненными.
        ?>

        <div id="user_form" class="cal-12 mx-auto">
            <?php
            if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
                print('<h3 id="form"> ФОРМА<br/>(режим редактирования) </h3>');
            else
                print('<h3 id="form"> ФОРМА </h3>');
            ?>
            <form action="." method="POST">

                <div class="group">
                    <input name="name" type="text" class="textInput" <?php if ($errors['name']) {
                        print 'class="error"';
                    } ?> value="<?php print $values['name']; ?>" required>
                    <span class="bar"></span>
                    <label class="textInputLabel">Имя</label>
                </div>
                <div class="group">
                    <input name="email" type="email" class="textInput" <?php if ($errors['email']) {
                        print 'class="error"';
                    } ?> value="<?php print $values['email']; ?>" required>
                    <span class="bar"></span>
                    <label class="textInputLabel">Email</label>
                </div>
                <div class="group">
                    <input name="bd" type="date" class="textInput" <?php if ($errors['bd']) {
                        print 'class="error"';
                    } ?> value="<?php print $values['bd']; ?>" required>
                    <span class="bar"></span>
                </div>
                <label class="selectLabel">Пол:</label>
                <br>
                <label class="labelRadio" for="rdo1">
                    <input type="radio" id="rdo1" name="pol" value="M" <?php if ($values['pol'] == 'M') {
                        print 'checked';
                    } ?>>
                    <span class="rdo"></span>
                    <span>Мужской</span>
                </label>
                <label class="labelRadio" for="rdo2">
                    <input type="radio" id="rdo2" name="pol" value="F" <?php if ($values['pol'] == 'F') {
                        print 'checked';
                    } ?>>
                    <span class="rdo"></span>
                    <span>Женcкий</span>
                </label>


                <br>
                <label class="selectLabel">Выберите количество конечностей:</label>
                <br>
                <label class="labelRadio" for="rdo3">
                    <input type="radio" id="rdo3" name="limbs" value="3" <?php if ($values['limbs'] == '3') {
                        print 'checked';
                    } ?>>
                    <span class="rdo"></span>
                    <span>3</span>
                </label>
                <label class="labelRadio" for="rdo4">
                    <input type="radio" id="rdo4" name="limbs" value="4" <?php if ($values['limbs'] == '4') {
                        print 'checked';
                    } ?>>
                    <span class="rdo"></span>
                    <span>4</span>
                </label>

                <br>

                <label class="selectLabel" for="multi-select">Выберите сверхспособности:</label>
                <div class="select select--multiple">
                    <select name="superpowers[]" id="multi-select" multiple>
                        <option value="1" <?php if ($values['superpowers']['0']) {
                            print 'selected';
                        } ?>>Бессмертие</option>
                        <option value="2" <?php if ($values['superpowers']['1']) {
                            print 'selected';
                        } ?>>Видеть сквозь стены</option>
                        <option value="3" <?php if ($values['superpowers']['2']) {
                            print 'selected';
                        } ?>>Левитация</option>
                    </select>
                    <span class="focus"></span>
                </div>
                <br>
                <div class="input_checkbox_list">
                    <div class="checkbox_item">
                        <input name="contract" type="checkbox" <?php if ($errors['contract']) {
                            print 'class="error"';
                        } ?> id="check2" class="input-checkbox filled-in">
                        <label for="check2">С условиями ознакомлен</label>
                    </div>
                </div>


                <br>
                <div class="submitStyle">
                    <input type="submit" value="Отправить">
                </div>
            </form>
        </div>
    </div>
    <nav id="navi">
        <ul>
            <li>
                <?php
                if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
                    print('<a href="./?quit=1" class = "gradient-button" title = "Log out">Выйти</a>');
                else
                    print('<a href="login.php" class = "gradient-button"  title = "Log in">Войти</a>');
                ?></li>
        </ul>
    </nav>

</div>
<a id="end"></a>
</body>

</html>