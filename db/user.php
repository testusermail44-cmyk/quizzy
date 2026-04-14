<?php
function addUser($conn, $username, $surname, $email, $password)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (userExists($conn, $email)) {
        return "exists";
    }
    $stmt = $conn->prepare("INSERT INTO users (username, surname, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $surname, $email, $hash);
    return $stmt->execute();
}

function userExists($conn, $email)
{
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function loginUser($conn, $email, $password)
{
    $stmt = $conn->prepare("SELECT id, username, surname, password_hash, admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        return false;
    }
    $id = $username = $surname = $hash = $admin = null;
    $stmt->bind_result($id, $username, $surname, $hash, $admin);
    $stmt->fetch();
    if (!password_verify($password, $hash)) {
        return false;
    }
    return [
        "id" => $id,
        "username" => $username,
        "surname" => $surname,
        "email" => $email,
        "admin" => $admin
    ];
}

function authUser($user)
{
    $_SESSION['user']['id'] = $user['id'];
    $_SESSION['user']['username'] = $user['username'];
    $_SESSION['user']['surname'] = $user['surname'];
    $_SESSION['user']['email'] = $user['email'];
    $_SESSION['user']['admin'] = $user['admin'];
}

function getScores($conn, $id)
{
    $stmt = $conn->prepare("SELECT scores FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function addScores($conn, $id, $scores)
{
    $oldScore = getScores($conn, $id);
    $newScore = $oldScore['scores'] + $scores;
    $stmt = $conn->prepare('UPDATE users SET scores = ? WHERE id = ?');
    $stmt->bind_param('ii', $newScore, $id);
    $stmt->execute();
}

function updateUser($conn, $userId, $name, $surname, $password = null)
{
    if ($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("
            UPDATE users 
            SET username = ?, surname = ?, password_hash = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sssi", $name, $surname, $hash, $userId);
    } else {
        $stmt = $conn->prepare("
            UPDATE users 
            SET username = ?, surname = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $name, $surname, $userId);
    }
    return $stmt->execute();
}

function getUsersCount($conn){
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>