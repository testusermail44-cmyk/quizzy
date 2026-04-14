<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/user.php';
include '../db/statistics.php';
isLogin();
if (isStart()){
    endQuiz($conn);
}
$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';
$error = '';
if (isset($_POST['name'])) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Невірний формат email";
    } elseif (userExists($conn, $email)) {
        $error = "Користувач з таким email вже існує";
    } elseif (mb_strlen($password) < 8) {
        $error = "Пароль повинен складатися мінімум з 8 символів";
    } elseif ($password != $confirm) {
        $error = "Паролі повинні співпадати";
    } else {
        $res = addUser($conn, $name, $surname, $email, $password);
        if ($res) {
            $user = loginUser($conn, $email, $password);
            authUser($user);
            $_SESSION['show_welcome'] = true;
            header("Location: ../wellcome.php");
            exit;
        } else {
            $error = "Помилка при реєстрації. Спробуйте ще раз";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
    <link rel="stylesheet" type="text/css" href="../css/auth.css" />
    <title>Реєстрація</title>
</head>

<body>
    <?php
    $path = '../';
    ?>
    <div class="container" style="height:100%">
        <div class='auth'>
            <div class="left-side">
                <img src="../images/background.png" class="auth-img" />
                <div class="auth-logo">
                    <img class="logo" src="<?= $path ?>images/gamepad.svg" />
                    <span class="cap-let">Q</span>
                    <span class="logo-word">uizzy</span>
                </div>
                <div class="info-text">Приєднуйся та перевір свої знання у захоплюючих вікторинах!</div>
            </div>
            <form class="form-container auth-f" method="post" action="signup.php">
                <span class="title">Реєстрація</span>
                <input type="text" name="email" placeholder="Email" title="Email користувача" required
                    value="<?= $email ?>" />
                <input type="text" name="name" placeholder="Ім'я" title="Ім'я користувача" required
                    value="<?= $name ?>" />
                <input type="text" name="surname" placeholder="Прізвище" title="Прізвище користувача" required
                    value="<?= $surname ?>" />
                <input type="password" name="password" placeholder="Пароль"
                    title="Пароль користувача повинен складати щонайменше 8 символів" required />
                <input type="password" name="confirm" placeholder="Повторіть пароль" title="Повторіть пароль"
                    required />
                <button type="submit" class="button">Зареєструватись</button>
                <?php
                if ($error != '') {
                    ?>
                    <div class="e"><?= $error ?></div>
                    <?php
                }
                ?>
                <a class="link" href="signin.php">Маєте обліковий запис? Увійти</a>
            </form>
        </div>

    </div>
</body>

</html>