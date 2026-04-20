<?php
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $conn = new mysqli($host, $user, $pass, "quizzy");
    if (!$conn || $conn->connect_errno) {
        die("DB error: " . $conn->connect_error);
    }
?>
