<?php

session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$rollNumber = trim($_POST['roll_number'] ?? '');
$password = $_POST['password'] ?? '';

if ($rollNumber === '' || $password === '') {
    header('Location: login.html?status=error&msg=' . urlencode('Roll number and password are required.'));
    exit;
}

// CHANGES HERE: Added 'role' to the SELECT statement
$statement = $connection->prepare('SELECT id, first_name, last_name, email, password_hash, role FROM users WHERE roll_number = ? LIMIT 1');
$statement->bind_param('s', $rollNumber);
$statement->execute();
$result = $statement->get_result();
$user = $result ? $result->fetch_assoc() : null;

if (!$user || !password_verify($password, $user['password_hash'])) {
    header('Location: login.html?status=error&msg=' . urlencode('Invalid roll number or password.'));
    exit;
} 

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role']; // Save role in session for page protection later

$adminRoles = ['admin', 'President', 'VP', 'GS', 'Treasurer']; // Define which roles are considered admin-level
// CHANGES HERE: Cleaned up and checking the 'role' value directly
if (in_array($user['role'], $adminRoles)) {
    // User is an admin, redirect to admin dashboard
    $_SESSION['is_admin'] = true;
    header('Location: admin.php'); 
} else {
    // Regular user, redirect to home page
    $_SESSION['is_admin'] = false;
    header('Location: home.php');
}
exit;