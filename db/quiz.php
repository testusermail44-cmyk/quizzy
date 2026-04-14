<?php

function getQuizList($conn)
{
    $stmt = $conn->prepare("
    SELECT q.id, q.title, q.description, q.level, COUNT(DISTINCT qs.id) as questions, MAX(s.attempt_number) as attempts, MAX(s.score) as score, MIN(s.completed_time) as besttime 
    FROM quizzes as q
    LEFT JOIN questions as qs ON q.id = qs.quiz_id
    LEFT JOIN statistics as s ON q.id = s.quiz_id AND s.user_id = ?
    GROUP BY q.id;");
    $stmt->bind_param("i", $_SESSION['user']['id']);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getQuiz($conn, $id)
{
    $stmt = $conn->prepare("
    SELECT quiz.title, quiz.description, quiz.level
    FROM quizzes as quiz
    WHERE quiz.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getQuestions($conn, $id)
{
    $stmt = $conn->prepare("
    SELECT q.id, q.question_text
    FROM questions as q
    WHERE q.quiz_id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getQuestionsById($conn, $id)
{
    $stmt = $conn->prepare("
    SELECT q.id, q.question_text
    FROM questions as q
    WHERE q.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getAnswers($conn, $id)
{
    $stmt = $conn->prepare("
    SELECT id, answer_text, is_correct
    FROM answers
    WHERE question_id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function createQuiz($conn, $title, $description, $level)
{
    $stmt = $conn->prepare("INSERT INTO quizzes (title, description, level) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $level);
    return $stmt->execute();
}

function updateQuiz($conn, $title, $description, $level, $id)
{
    $stmt = $conn->prepare("UPDATE quizzes SET title = ?, description = ?, level = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $description, $level, $id);
    return $stmt->execute();
}

function deleteQuiz($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

function createQuestion($conn, $quiz, $text)
{
    $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
    $stmt->bind_param("is", $quiz, $text);
    $stmt->execute();
    return $conn->insert_id;
}

function updateQuestion($conn, $id, $text)
{
    $stmt = $conn->prepare("UPDATE questions SET question_text = ? WHERE id = ?");
    $stmt->bind_param("si", $text, $id);
    return $stmt->execute();
}

function deleteQuestions($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

function createAnswer($conn, $id, $text, $isCorrect)
{
    $stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $id, $text, $isCorrect);
    return $stmt->execute();
}

function updateAnswer($conn, $id, $text, $isCorrect)
{
    $stmt = $conn->prepare("UPDATE answers SET answer_text = ?, is_correct = ? WHERE id = ?");
    $stmt->bind_param("sii", $text, $isCorrect, $id);
    return $stmt->execute();
}
?>