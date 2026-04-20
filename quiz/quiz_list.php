<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/statistics.php';
include '../db/user.php';
requireAuth();
if (isStart()){
    endQuiz($conn);
}
$quizzes = getQuizList($conn);  
$scores = getScores($conn, $_SESSION['user']['id'])['scores'];
$level = (int)($scores / 1000);
function createQuizCard($id, $name, $desription, $count, $attempts, $difficulty, $bestTime, $bestAnswear)
{
    $minutes = floor($bestTime / 60);
    $seconds = $bestTime % 60;
    $bestTime = sprintf('%02d:%02d', $minutes, $seconds);
    ?>
    <div class="card">
        <div class="card-name"><?= $name ?></div>
        <div class="card-description"><?= $desription ?></div>
        <div class="sep"></div>
        <div class="card-info">
            <div class="card-info-panel"><?= 'Запитань: ' . $count ?></div>
            <div class="card-info-panel"><?= 'Спроб: ' . ($attempts ?? '0') ?></div>
            <div class="card-info-panel"><?= $difficulty == 1 ? 'Легко' : ($difficulty == 2 ? 'Нормально' : 'Важко') ?></div>
        </div>
        <div class="sep"></div>
        <div class="card-info u">
            <div class="card-user-info">
                <div class="card-info-text"><?= 'Кращий час ' . ($bestTime ?? '00:00') ?></div>
                <div class="card-info-text"><?= 'Кращий результат ' . ($bestAnswear ?? '0') . ' / ' . $count ?></div>
            </div>
        </div>
        <a class="quiz-btn -card" href="quiz.php?id=<?=$id?>">Старт!</a>
    </div>
    <?php
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
    <link rel="stylesheet" type="text/css" href="../css/quiz.css" />
    <title>Вікторини</title>
</head>

<body>
    <?php
    $path = '../';
    include('../header.php');
    ?>
    <div class="container">
        <div class="user-stats">
            <div class="">Ваш рахунок</div>
            <div class="progress">
                <div id="bar" class="progress-bar" style="width: <?=($scores - ($level * 1000)) * 100 / 1000?>%;"></div>
                <div class='icon'>🏆</div>
                <div class="level"><?=(int)($scores / 1000)?></div>
            </div>
            <div class="progress-small-text"><?=1000-($scores - ($level * 1000))?> до наступного рівня</div>
        </div>
        <div class="quizzes-container">
            <?php
            foreach ($quizzes as $q) {
                if ($q['questions'] == 0)
                    continue;
                createQuizCard($q['id'], $q['title'], $q['description'], $q['questions'], $q['attempts'], $q['level'], $q['besttime'], $q['score']);
            }
            ?>
        </div>
    </div>
</body>

</html>