<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/user.php';
include '../db/statistics.php';
isLogin();
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$error = '';
if (isStart()){
    endQuiz($conn);
}
if (isset($_POST['email'])) {
    $user = loginUser($conn, $email, $password);
    if ($user) {
        authUser($user);
        header('Location: ../index.php');
    }
    else {
        $error = 'Пароль або email неправильний!';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
       <link rel="stylesheet" type="text/css" href="../css/auth.css" />
    <title>Вхід</title>
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
            <form class="form-container auth" method="post" action="signin.php">
                <span class="title">Вхід</span>
                <input type="text" name="email" placeholder="Ваш email" title="Email користувача" required value="<?= $email ?>" />
                <input type="password" name="password"placeholder="Пароль" title="Пароль користувача" required />
                <button type="submit" class="button">Увійти</button>
                <?php 
                    if ($error != '') {
                        ?>
                            <div class="e"><?= $error ?></div>
                        <?php
                    }
                ?>
                <a class="link" href="signup.php">Не маєте облікового запису? Зареєструватись</a>
            </form>
         </div>
    </div>
</body>

</html>