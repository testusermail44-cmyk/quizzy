<header>
    <a href="<?= $path ?>index.php" class="gamepad-logo">
        <img class="logo" src="<?= $path ?>images/gamepad.svg" />
        <span class="cap-let">Q</span>
        <span class="logo-word">uizzy</span>
    </a>
    <div class="buttons-header">
        <?php
        if (isset($_SESSION['user']['id'])) {
            ?>
            <a class='header-link-button' href="<?= $path ?>quiz/quiz_list.php">Вікторини</a>
            <a class='header-link-button' href="<?= $path ?>user/statistic.php">Статистика</a>
            <?php
            if ($_SESSION['user']['admin']) {
            ?>
                <a class="header-link-button" href="<?= $path ?>admin/editor.php">Редактор</a>
            <?php
            }
            ?>
            <a class='header-link-button' href="<?= $path ?>user/settings.php">Налаштування</a>
            <a class='header-link-button' href="<?= $path ?>user/signout.php">Вихід</a>
            <?php
        } else {
            ?>
            <a href="<?=$path?>user/signin.php" class="button">Увійти</a>
            <?php
        }
        ?>
    </div>
</header>