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
if (isset($_SESSION['user'])){
    if ($_SESSION['user']['admin'] != 1)
        header("Location: index.php"); 
}
$success = 0;
$name = '';
$description = '';
$level = 1;
$quiz = null;
if (isset($_GET['id'])) {
    $quiz = getQuiz($conn, $_GET['id']);
    $name = $quiz['title'];
    $description = $quiz['description'];
    $level = $quiz['level'];
}
if (isset($_POST['name'])) {
    $title = $_POST['name'];
    $description = $_POST['description'];
    $level = $_POST['level'];
    if (isset($_GET['id'])) {
        $success = updateQuiz($conn, $title, $description, $level, $_GET['id']);
        $_SESSION['msg'] = $success ? 'Інформація про вікторину успішно оновлена!' : 'Помилка оновлення';
        $_SESSION['scs'] = $success;
    } else {
        $success = createQuiz($conn, $title, $description, $level);
        $_SESSION['msg'] = $success ? 'Нову вікторину додано! Додайте до неї питання, щоб вона почала відображатись у списку вікторин' : 'Помилка створення';
        $_SESSION['scs'] = $success;
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
            <span class="title">Форма <?= isset($_GET['id']) ? 'редагування' : 'створення' ?> вікторини</span>
            <span class="text">Назва вікторини</span>
            <input name="name" type="text" value="<?= $name ?>" required />
            <span class="text">Опис вікторини</span>
            <textarea required name="description"><?= $description ?></textarea>
            <span class="text">Рівень складності вікторини</span>
            <div class="radio-container">
                <label class="custom-radio">
                    <input type="radio" name="level" value="1" <?= $level == 1 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                    Легко
                </label>
                <label class="custom-radio">
                    <input type="radio" name="level" value="2" <?= $level == 2 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                    Нормально
                </label>
                <label class="custom-radio">
                    <input type="radio" name="level" value="3" <?= $level == 3 ? 'checked' : '' ?>>
                    <span class="radio-mark"></span>
                    Важко
                </label>
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