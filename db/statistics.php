<?php 
function getAttempts($conn, $id) {
    $stmt = $conn->prepare("SELECT MAX(attempt_number) as max FROM statistics WHERE quiz_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user']['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result ? (int)$result['max'] : 0;
}

function writeNewStatistick($conn, $userId, $quizId, $score, $time, $attempts){
    $stmt = $conn->prepare("INSERT INTO statistics (user_id, quiz_id, score, completed_time, attempt_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiii", $userId, $quizId, $score, $time, $attempts);
    $stmt->execute();
}

function getStatistick($conn){
    $stmt = $conn->prepare("SELECT u.username, u.surname, u.scores FROM users as u WHERE u.admin != 1 ORDER BY u.scores DESC");
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getUserStatistick($conn, $id){
    $stmt = $conn->prepare("
        SELECT 
        COUNT(DISTINCT quiz_id) AS quiz_count,
        SUM(max_attempt) AS total_attempts
        FROM (
            SELECT 
                quiz_id,
                MAX(attempt_number) AS max_attempt
            FROM statistics
            WHERE user_id = ?
            GROUP BY quiz_id
        ) t;"
        );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>