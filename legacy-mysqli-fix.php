<?php

$dsn = 'mysql:host=localhost;dbname=purchase_module;charset=utf8mb4';
$username = 'root';
$password = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === null || $id === false) {
    http_response_code(400);
    echo 'Invalid id provided.';
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'purchase_module');
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo 'Database connection failed: ' . htmlspecialchars($mysqli->connect_error, ENT_QUOTES, 'UTF-8');
    exit;
}

$statement = $mysqli->prepare('SELECT name FROM users WHERE id = ?');
if ($statement === false) {
    http_response_code(500);
    echo 'Failed to prepare SQL statement.';
    $mysqli->close();
    exit;
}

$statement->bind_param('i', $id);
if (! $statement->execute()) {
    http_response_code(500);
    echo 'Query execution failed.';
    $statement->close();
    $mysqli->close();
    exit;
}

$result = $statement->get_result();
if ($result === false) {
    http_response_code(500);
    echo 'Failed to retrieve query result.';
    $statement->close();
    $mysqli->close();
    exit;
}

if ($result->num_rows === 0) {
    http_response_code(404);
    echo 'User not found.';
} else {
    while ($row = $result->fetch_assoc()) {
        echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
    }
}

$statement->close();
$mysqli->close();
