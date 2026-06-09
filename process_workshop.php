<?php
session_start();
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized request']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title            = trim($_POST['title'] ?? '');
    $mentor_name      = trim($_POST['mentor_name'] ?? '');
    $location         = trim($_POST['location'] ?? '');
    $start_time       = trim($_POST['start_time'] ?? '');
    $end_time         = trim($_POST['end_time'] ?? '');
    $materials_link   = trim($_POST['materials_link'] ?? '');
    $max_participants = trim($_POST['max_participants'] ?? '');
    $description      = trim($_POST['description'] ?? '');
    $created_by       = $_SESSION['user_id'];

    if (empty($title) || empty($mentor_name) || empty($location) || empty($start_time) || empty($end_time) || empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit;
    }

    $start_time = str_replace('T', ' ', $start_time) . ':00';
    $end_time   = str_replace('T', ' ', $end_time) . ':00';
    $materials_link   = !empty($materials_link) ? $materials_link : null;
    $max_participants = !empty($max_participants) ? (int)$max_participants : null;

    $query = "INSERT INTO workshops (title, description, start_time, end_time, location, mentor_name, materials_link, max_participants, created_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssssssii", $title, $description, $start_time, $end_time, $location, $mentor_name, $materials_link, $max_participants, $created_by);

    if ($stmt->execute()) {
        // Return JSON confirmation instead of executing header() redirect
        echo json_encode(['status' => 'success', 'message' => '🎉 Workshop scheduled and synced successfully! You can add another one.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    exit;
}