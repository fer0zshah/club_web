<?php
session_start();
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

// 1. GATEKEEPER: Only admins can update status
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

// 2. READ JSON payload from JavaScript fetch
$inputData = json_decode(file_get_contents('php://input'), true);
$type      = isset($inputData['type']) ? trim($inputData['type']) : '';
$id        = isset($inputData['id']) ? intval($inputData['id']) : 0;
$newStatus = isset($inputData['status']) ? trim($inputData['status']) : '';

// 3. VALIDATE inputs
$allowedTypes    = ['contest', 'workshop'];
$allowedStatuses = ['upcoming', 'ongoing', 'completed'];

if (!in_array($type, $allowedTypes) || $id <= 0 || !in_array($newStatus, $allowedStatuses)) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters provided.']);
    exit;
}

// 4. MAP type to database table name (safe — no user input in table name)
$table = ($type === 'contest') ? 'contests' : 'workshops';

// 5. UPDATE the status in the correct table
$statement = $connection->prepare("UPDATE $table SET status = ? WHERE id = ?");
$statement->bind_param('si', $newStatus, $id);

if ($statement->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database update failed: ' . $statement->error]);
}
exit;
