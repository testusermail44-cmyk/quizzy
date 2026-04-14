<?php
define('APP_INIT', true);
include 'config/init.php';
include 'config/connect.php';
include 'middleware/user.php';
include 'db/quiz.php';
include 'db/user.php';
include 'db/statistics.php';
include 'statistickHandler.php';
if (isStart()){
    endQuiz($conn);
}
$quizzes = getQuizList($conn);
$users = getUsersCount($conn);
$statistick = getStatistick($conn);
$randId = rand(0, count($quizzes));
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/header.css" />
    <title>Головна</title>
</head>

<body>
    <?php
    $path = '';
    include('header.php');
    ?>
    <div class="container">
        <div class="index border">
            <div class='title'>Ласкаво просимо до Quizzy!</div> 
            Перевір свої знання, змагайся з іншими гравцями та відкривай нові можливості.
            Проходь вікторини різної складності, заробляй бали та піднімайся в рейтингу!
            <a href='quiz/quiz.php?id=<?=$quizzes[$randId]['id']?>' class="glow-btn">ШВИДКА ГРА</a>
        </div>
        <div class="stat-card-container">
            <div class="stat-card border">
                <h3>Вікторин, що охоплюють різні теми:</h3>
                <span><?=count($quizzes)?></span>
            </div>
            <div class="stat-card border">
                <h3>Всього гравців:</h3>
                <span><?=$users['total']-1?></span>
            </div>
        </div>
        <div class="title" style="margin-top:20px">Найкращі гравці</div>
        <?=loadStat($statistick, false)?>
    </div>
</body>

</html>