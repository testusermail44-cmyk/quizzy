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
$text = '';
$var1 = '';
$var2 = '';
$var3 = '';
$var4 = '';
$ic1 = 1;
$ic2 = 0;
$ic3 = 0;
$ic4 = 0;
$question = '';
$answers = '';
if (isset($_GET['id'])) {
    $question = getQuestionsById($conn, $_GET['id']);
    $answers = getAnswers($conn, $_GET['id']);
    $text = $question['question_text'];
    $var1 = $answers[0]['answer_text'];
    $var2 = $answers[1]['answer_text'];
    $var3 = $answers[2]['answer_text'];
    $var4 = $answers[3]['answer_text'];
    $ic1 = $answers[0]['is_correct'];
    $ic2 = $answers[1]['is_correct'];
    $ic3 = $answers[2]['is_correct'];
    $ic4 = $answers[3]['is_correct'];
}

if (isset($_POST['text'])) {
    if (isset($_GET['id'])) {
        $ids = [
            $answers[0]['id'],
            $answers[1]['id'],
            $answers[2]['id'],
            $answers[3]['id']
        ];
        updateQuestion($conn, $_GET['id'], $_POST['text']);
        for ($i = 1; $i < 5; $i++) {
            $success = updateAnswer($conn, $ids[$i - 1], $_POST['var'.$i], $_POST['ans'] == $i);
            $_SESSION['msg'] = $success ? 'Інформація про питання успішно оновлена!' : 'Помилка оновлення';
            $_SESSION['scs'] = $success;
        }
    } else {
        $id = createQuestion($conn, $_GET['quiz'], $_POST['text']);
        for ($i = 1; $i < 5; $i++) {
            $success = createAnswer($conn, $id, $_POST['var' . $i], $_POST['ans'] == $i);
            $_SESSION['msg'] = $success ? 'Нове питання додано!' : 'Помилка створення';
            $_SESSION['scs'] = $success;
        }
    }
    header("Location: ".$_SERVER['REQUEST_URI']);
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
        <form class="editor-plane border" method="post" action="">
            <span class="title">Форма
                <?= isset($_GET['id']) ? 'редагування' : 'створення' ?> питання
            </span>
            <span class="text">Текст питання</span>
            <input name="text" type="text" value="<?= $text ?>" required />
            <span class="text">Додайте варіанти відповіді та оберіть правильну</span>
            <div class="ans-e">
                <label class="custom-radio">
                    <input type="radio" name="ans" value="1" <?=$ic1 == 1 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                </label>
                <input class="w" name="var1" type="text" value="<?= $var1 ?>" required />
            </div>
            <div class="ans-e">
                <label class="custom-radio">
                    <input class="w" type="radio" name="ans" value="2" <?=$ic2 == 1 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                </label>
                <input class='w' name="var2" type="text" value="<?= $var2 ?>" required />
            </div>
            <div class="ans-e">
                <label class="custom-radio">
                    <input type="radio" name="ans" value="3" <?=$ic3 == 1 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                </label>
                <input class="w" name="var3" type="text" value="<?= $var3 ?>" required />
            </div>
            <div class="ans-e">
                <label class="custom-radio">
                    <input type="radio" name="ans" value="4" <?=$ic4 == 1 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                </label>
                <input class="w" name="var4" type="text" value="<?= $var4 ?>" required />
            </div>
            <button class="button" type="submit">Зберегти</button>
        </form>
        <?php
            if (isset($_SESSION['msg'])) {
                ?>
                    <div class="<?=$_SESSION['scs'] == 1 ? 'success' : 'err' ?>"><?=$_SESSION['msg']?></div>
                <?php
                unset($_SESSION['msg']);
                unset($_SESSION['scs']);
            }
        ?>
    </div>
</body>

</html>