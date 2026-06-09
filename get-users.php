<?php
session_start();
header('Content-Type: application/json');

// Double check authorization for background requests
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Database Connection (Update with your actual database credentials)
$host = 'localhost';
$db   = 'clubweb';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Fetch all records from your user table
    $stmt = $pdo->query("SELECT id, roll_number, department, first_name, last_name, email, phone, role FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll();

    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
exit;