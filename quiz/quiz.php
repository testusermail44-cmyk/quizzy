<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/user.php';
include '../db/statistics.php';
requireAuth();

if (!isStart()) {
    $_SESSION['q_start'] = 1;
    $_SESSION['q_id'] = 0;
}
if (!isset($_SESSION['quiz_start_time'])) {
    $_SESSION['quiz_start_time'] = time();
}

$quiz = '';
$questions = '';
$answers = '';
if (isset($_GET['id'])) {
    $quiz = getQuiz($conn, $_GET['id']);
    $questions = getQuestions($conn, $_GET['id']);
    $answers = getAnswers($conn, $questions[$_SESSION['q_id']]['id']);
    shuffle($answers);
    $_SESSION['quiz_id'] = $_GET['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answered'])) {
    $_SESSION['q_id']++;
    $_SESSION['scores']  = $_POST['scores'];
    if ($_SESSION['q_id'] >= count($questions)) {
        $_SESSION['q_start'] = 0;
        $_SESSION['result']['scores'] = $_SESSION['scores'];
        $endTime = time();
        $time = $endTime - $_SESSION['quiz_start_time'];
        $limit = $_SESSION['q_id'] * 10;
        $_SESSION['result']['bonus'] = round(($_SESSION['result']['scores'] * 100) * max(0, ($limit - $time) / $limit));
        $_SESSION['result']['count'] = $_SESSION['q_id'];
        unset($_SESSION['quiz_start_time']);
        unset($_SESSION['q_id']);
        unset($_SESSION['scores']);
        $attempts = getAttempts($conn, $_GET['id']) + 1;
        writeNewStatistick($conn, $_SESSION['user']['id'], $_GET['id'], $_SESSION['result']['scores'], $time, $attempts);
        addScores($conn, $_SESSION['user']['id'], ($_SESSION['result']['scores'] * 100) + $_SESSION['result']['bonus']);
        header("Location: quiz_rezult.php");
        exit;
    }
    header("Location: quiz.php?id=" . $_GET['id']);
    exit;
}

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
        <div class="timer-container">
            <div id="timer">00:00</div>
        </div>
        <div class="quiz-panel">
            <div class="quiz-border"></div>
            <div class="quiz-title"><?= $quiz['title'] ?></div>
            <div class="quiz-container">
                <div class="q-btn">?</div>
                <span class="text"><?= $questions[$_SESSION['q_id']]['question_text'] ?></span>
            </div>
            <form method="post" id="quizForm">
                <div class="btns-container">
                    <?php foreach ($answers as $a): ?>
                        <button type="button" class="quiz-btn" data-correct="<?= $a['is_correct'] ?>">
                            <?= htmlspecialchars($a['answer_text']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="answered" value="1">
                <input type="hidden" id="scores" name="scores" value="<?=$_SESSION['scores'] ?? 0?>" />
            </form>
            <div class="count-panel">Питання <?= $_SESSION['q_id'] + 1 ?> / <?= count($questions) ?></div>
        </div>
    </div>
    <script>
        let scores = document.getElementById("scores");
        const quizStartTime = <?= $_SESSION['quiz_start_time'] ?>;
        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - quizStartTime;
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('timer').innerHTML = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }
        updateTimer();
        setInterval(updateTimer, 1000);
        document.querySelectorAll('.quiz-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.quiz-btn').forEach(b => b.disabled = true);
                const isCorrect = btn.dataset.correct === "1";
                btn.classList.add(isCorrect ? 'correct' : 'wrong');
                if (isCorrect) {
                    scores.value = parseInt(scores.value) + 1;
                }
                setTimeout(() => {
                    document.getElementById('quizForm').submit();
                }, 1000);

            });
        });
    </script>
</body>

</html>