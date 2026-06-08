<?php

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$rollNumber = trim($_POST['roll_number'] ?? '');
$department = trim($_POST['department'] ?? '');
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if ($rollNumber === '' || $department === '' || $firstName === '' || $lastName === '' || $email === '' || $password === '' || $confirmPassword === '') {
    header('Location: register.html?status=error&msg=' . urlencode('All required fields must be filled in.'));
    exit;
}

if ($password !== $confirmPassword) {
    header('Location: register.html?status=error&msg=' . urlencode('Passwords do not match.'));
    exit;
}

$checkUser = $connection->prepare('SELECT id FROM users WHERE roll_number = ? OR email = ? LIMIT 1');
$checkUser->bind_param('ss', $rollNumber, $email);
$checkUser->execute();
$result = $checkUser->get_result();

if ($result && $result->num_rows > 0) {
    header('Location: register.html?status=error&msg=' . urlencode('An account with that roll number or email already exists.'));
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$statement = $connection->prepare('INSERT INTO users (roll_number, department, first_name, last_name, email, phone, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)');
$statement->bind_param('sssssss', $rollNumber, $department, $firstName, $lastName, $email, $phone, $passwordHash);

if ($statement->execute()) {
    header('Location: register.html?status=success');
    exit;
}

header('Location: register.html?status=error&msg=' . urlencode('Registration failed: ' . $connection->error));
exit;