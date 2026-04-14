<?php
define('APP_INIT', true);
include '../config/init.php';
include '../config/connect.php';
include '../middleware/user.php';
include '../db/quiz.php';
include '../db/user.php';
include '../db/statistics.php';
if (isStart()) {
    endQuiz($conn);
}
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['admin'] != 1)
        header("Location: index.php");
}

$questions = getQuestions($conn, $_GET['id']);

if (isset($_GET['del'])) {
    $success = deleteQuestions($conn, $_GET['del']);
    $_SESSION['msg'] = $success ? 'Питання видалено!' : 'Помилка видалення';
    $_SESSION['scs'] = $success;
    header("Location: questions.php?id=".$_GET['id']);
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
                <div class="<?= $_SESSION['scs'] == 1 ? 'success' : 'err' ?>">
                    <?= $_SESSION['msg'] ?>
                </div>
                <?php
                unset($_SESSION['msg']);
                unset($_SESSION['scs']);
            }
            ?>
            <div class="new-quiz">
                Всього питань:
                <?= count($questions) ?>
                <a class="button" href="questionEditor.php?quiz=<?=$_GET['id']?>">Додати питання</a>
            </div>
            <?php
            $count = 1;
            foreach ($questions as $q) {
                ?>
                <div class="editor-item">
                    <div>
                        <span class="number">
                            <?= $count ?>
                        </span>
                        <span style="color:white;">
                            <?= $q['question_text'] ?>
                        </span>
                    </div>
                    <div class="btn-cont">
                        <a class="button" href="questionEditor.php?quiz=<?=$_GET['id']?>&id=<?= $q['id'] ?>">Редагувати</a>
                        <a class="button" href="?id=<?=$_GET['id']?>&del=<?= $q['id'] ?>">Видалити</a>
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