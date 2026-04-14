<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/user.php';
include '../db/statistics.php';
requireAuth();
if (isStart()) {
    endQuiz($conn);
}
$name = $_POST['username'] ?? $_SESSION['user']['username'];
$sname = $_POST['surname'] ?? $_SESSION['user']['surname'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['username']);
    $surname = trim($_POST['surname']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password !== '' && $password !== $confirm) {
        $error = "Паролі не співпадають";
    } else {
        updateUser($conn, $_SESSION['user']['id'], $name, $surname, $password !== '' ? $password : null);
        $_SESSION['user']['username'] = $name;
        $_SESSION['user']['surname'] = $surname;
    }
    header("Location: settings.php");
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
    <title>Налаштування</title>
</head>

<body>
    <?php
    $path = '../';
    include('../header.php');
    ?>
    <div class="container">
        <form method="post" class="setting-container">
            <h2>Налаштування профілю</h2>
            <label>Імʼя користувача</label>
            <input type="text" name="username" value="<?= htmlspecialchars($name) ?>" required>
            <label>Прізвище</label>
            <input type="text" name="surname" value="<?= htmlspecialchars($sname) ?>" required>
            <label>Новий пароль</label>
            <input type="password" name="password" placeholder="">
            <label>Підтвердити пароль</label>
            <input type="password" name="confirm">
            <?php
            if ($error != '') {
                ?>
                <div class="error"><?= $error ?></div>
                <?php
            }
            ?>
            <button type="submit" class="button">Зберегти</button>
        </form>
    </div>
</body>

</html>