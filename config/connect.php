<?php
    $pass = getenv('DB_PASS');
    $conn = new mysqli("mysql-33ac47be-testusermail44-ffa1.a.aivencloud.com:25072", "avnadmin", $pass, "quizzy");
    if (!$conn || $conn->connect_errno) {
        die("DB error: " . $conn->connect_error);
    }
?>
