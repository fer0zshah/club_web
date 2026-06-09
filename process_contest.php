<?php
session_start();
require_once __DIR__ . '/db.php';

// Enforce structured JSON return communication layout
header('Content-Type: application/json');

// 1. GATEKEEPER CHECK: Guard access by returning JSON errors instead of HTML terminal text
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized entry attempt.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. CAPTURE DATA FROM POST PAYLOAD
    $title         = trim($_POST['title'] ?? '');
    $contest_type  = trim($_POST['contest_type'] ?? '');
    $location      = trim($_POST['location'] ?? '');
    $start_time    = trim($_POST['start_time'] ?? '');
    $end_time      = trim($_POST['end_time'] ?? '');
    $platform_link = trim($_POST['platform_link'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $status        = trim($_POST['status'] ?? 'upcoming');
    $created_by    = $_SESSION['user_id'];

    // 3. ESSENTIAL VALIDATION CHECKS
    if (empty($title) || empty($contest_type) || empty($location) || empty($start_time) || empty($end_time) || empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all mandatory fields.']);
        exit;
    }

    // Convert datetime-local format (YYYY-MM-DDTHH:MM) to MySQL DATETIME format (YYYY-MM-DD HH:MM:SS)
    $start_time = str_replace('T', ' ', $start_time) . ':00';
    $end_time   = str_replace('T', ' ', $end_time) . ':00';
    
    // Optional parameter formatting
    $platform_link = !empty($platform_link) ? $platform_link : null;

    // 4. SAVE DATA SAFELY USING PREPARED STATEMENTS
    $query = "INSERT INTO contests (title, description, start_time, end_time, location, platform_link, status, contest_type, created_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssssssi", $title, $description, $start_time, $end_time, $location, $platform_link, $status, $contest_type, $created_by);

    if ($stmt->execute()) {
        // Return a successful data block back to the JavaScript event handler
        echo json_encode([
            'status' => 'success', 
            'message' => '🚀 Contest published successfully and added live to the homepage grid! Feel free to create another one.'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database persistence failure: ' . $stmt->error]);
    }
    exit;
}