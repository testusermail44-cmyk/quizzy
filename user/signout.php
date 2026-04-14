<?php
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/user.php';
include '../db/statistics.php';
session_name('quizzy');
session_start();
if (isStart()){
    endQuiz($conn);
}
$_SESSION = [];
session_destroy();
header("Location: ../index.php");
?>