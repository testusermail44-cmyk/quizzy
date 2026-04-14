<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/user.php';
include '../db/statistics.php';
if (isStart()){
    endQuiz($conn);
}
if (isset($_SESSION['user'])){
    if ($_SESSION['user']['admin'] != 1)
        header("Location: index.php"); 
}

$quiz = getQuizList($conn);

if (isset($_GET['del'])) {
    $success = deleteQuiz($conn, $_GET['del']);
    $_SESSION['msg'] = $success ? 'Вікторину видалено!' : 'Помилка видалення';
    $_SESSION['scs'] = $success;
    header("Location: editor.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="stylesheet" type="text/css" href="../css/header.css" />
    <title>Редактор</title>
</head>

<body>
    <?php
    $path = '../';
    include('../header.php');
    ?>
    <div class="container">
        <div class="editor-container">
            <?php
                if (isset($_SESSION['msg'])) {
                    ?>
                        <div class="<?=$_SESSION['scs'] == 1 ? 'success' : 'err' ?>"><?=$_SESSION['msg']?></div>
                    <?php
                    unset($_SESSION['msg']);
                    unset($_SESSION['scs']);
                }
            ?>
            <div class="new-quiz">
                Всього вікторин: <?=count($quiz)?>
                <a class="button" href="quizEditor.php">Додати вікторину</a>
            </div>
            <?php
                $count = 1;
                foreach($quiz as $q){
                    ?>
                    <div class="editor-item">
                        <div>
                            <span class="number"><?=$count?></span>
                            <a style="color:white;" href="questions.php?id=<?=$q['id']?>"><?=$q['title']?></a>
                        </div>
                        <div class="btn-cont">
                            <a class="button" href="quizEditor.php?id=<?=$q['id']?>">Редагувати</a>
                            <a class="button" href="?del=<?=$q['id']?>">Видалити</a>
                        </div>
                    </div>
                    <?php
                    $count++;
                }
            ?>
        </div>
    </div>
</body>

</html>