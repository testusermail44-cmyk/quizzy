<?php
define('APP_INIT', true);
include 'config/init.php';
$_SESSION['show_welcome'] = true;
if (!$_SESSION['show_welcome']) {
    header('Location: index.php');
}
$_SESSION['show_welcome'] = false;
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/header.css" />
    <link rel="stylesheet" type="text/css" href="css/wellcome.css" />
    <title>Вітаємо <?= $_SESSION['user']['username'] ?>!</title>
</head>

<body>
    <?php
    $path = '';
    include('header.php');
    ?>
    <div class="container">
        <div class="wellcome">
            <div class="wellcome-container">
                <div class="wellcome-border"></div>
                <h1>Ласкаво просимо, <?= $_SESSION['user']['username'] ?> <?= $_SESSION['user']['surname'] ?>!</h1>
                <p>Ви успішно зареєструвалися на сайт вікторин!</p>
            </div>
        </div>
    </div>
</body>

</html>