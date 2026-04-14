<?php
    $conn = new mysqli("localhost", "root", "", "quizzy");
    if (!$conn || $conn->connect_errno) {
        die("DB error: " . $conn->connect_error);
    }
?>