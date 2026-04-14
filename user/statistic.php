<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/user.php';
include '../db/statistics.php';
include '../statistickHandler.php';
requireAuth();
if (isStart()) {
    endQuiz($conn);
}

$statistick = getStatistick($conn);
$userStatistic = getUserStatistick($conn, $_SESSION['user']['id']);
$userScores = getScores($conn, $_SESSION['user']['id'])['scores'];
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
    <link rel="stylesheet" href="../css/font-awesome.css">
    <title>Статистика</title>
</head>

<body>
    <?php
    $path = '../';
    include('../header.php');
    ?>
    <div class="container">
        <?=loadStat($statistick)?>
        <div class="user-stat">
            <div class="user-stat-card">
                <div class="trophy"></div>
                <span>Ваш рахунок</span>
                <span><?=$userScores?></span>             
            </div>
            <div class="user-stat-card">
                <div class="count"></div>
                <span>Зіграних вікторин</span>
                <span><?=$userStatistic['quiz_count']?></span>
            </div>
            <div class="user-stat-card">
                <div class="attempt"></div>
                <span>Кількість спроб</span>
                <span><?=$userStatistic['total_attempts']?></span>
            </div>
        </div>
    </div>
</body>

</html>