<?php
function requireAuth()
{
    if (!isset($_SESSION['user']['id'])) {
        header("Location: ../user/signin.php");
        exit;
    }
}

function isLogin()
{
    if (isset($_SESSION['user']['id'])) {
        header("Location: ../index.php");
        exit;
    }
}

function isStart()
{
    if (isset($_SESSION['q_start'])) {
        return $_SESSION['q_start'] == 1;
    }
}

function endQuiz($conn)
{
    $_SESSION['q_start'] = 0;
    $_SESSION['result']['scores'] = $_SESSION['scores'] ?? 0;
    $endTime = time();
    $time = $endTime - $_SESSION['quiz_start_time'];
    $limit = $_SESSION['q_id'] * 10;
    if ($limit > 0) {
        $bonus = ($_SESSION['result']['scores'] * 100) * max(0, ($limit - $time) / $limit);
    } else {
        $bonus = 0;
    }
    $_SESSION['result']['bonus'] = round($bonus);

    $_SESSION['result']['count'] = $_SESSION['q_id'];
    unset($_SESSION['quiz_start_time']);
    unset($_SESSION['q_id']);
    unset($_SESSION['scores']);
    $attempts = getAttempts($conn, $_SESSION['quiz_id']) + 1;
    writeNewStatistick($conn, $_SESSION['user']['id'], $_SESSION['quiz_id'], $_SESSION['result']['scores'], $time, $attempts);
    addScores($conn, $_SESSION['user']['id'], ($_SESSION['result']['scores'] * 100) + $_SESSION['result']['bonus']);
    unset($_SESSION['quiz_id']);
}
?>