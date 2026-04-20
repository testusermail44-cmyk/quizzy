<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
requireAuth();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/quiz.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
    <title>Вікторина</title>
</head>

<body>
    <?php
    $path = '../';
    include('../header.php');
    ?>
    <div class="container">
        <div class="quiz-panel res">
            <div class="quiz-border"></div>
            <div class="quiz-title">Ваш результат</div>
            <div class="quiz-container res">
                <span class="text res"><?=$_SESSION['result']['scores']?> з <?=$_SESSION['result']['count']?> питань</span>
            </div>
            <div class="congratulation quiz-title">
                <span class="text res">Вам нараховано <?=$_SESSION['result']['scores']  * 100?> балів</span>
                <?php if ($_SESSION['result']['bonus'] > 0) { ?>
                <span class="text res" style="color:red">+</span>
                <span class="text res"></span>бонус за швидкість <?=$_SESSION['result']['bonus']?></span>
                <?php }?>   
                <div class="scores-panel">
                    <img src="../images/complete.png" class="complete" />
                    <div class="res-score" style="<?=$_SESSION['result']['scores']  * 100 + $_SESSION['result']['bonus'] == 0 ? 'color:red; font-size:60px;top: 65px;' : '' ?>"><?=$_SESSION['result']['scores']  * 100 + $_SESSION['result']['bonus']?></div>
                </div>
            </div>
        </div>
</body>

</html>