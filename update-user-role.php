<?php
session_start();
require_once __DIR__ . '/db.php'; // Integrates your default $connection variable

header('Content-Type: application/json');

// 1. GATEKEEPER CHECK: Ensure only authorized admins can run updates
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

// 2. READ THE DATA: Collect JSON payload from JavaScript Fetch
$inputData = json_decode(file_get_contents('php://input'), true);
$userId = isset($inputData['id']) ? intval($inputData['id']) : 0;
$newRole = isset($inputData['role']) ? trim($inputData['role']) : '';

// 3. VALIDATE THE SELECTION
// Inside update-user-role.php, update your allowed array:
$allowedRoles = ['student', 'admin', 'President', 'VP', 'GS', 'Treasurer'];

if ($userId <= 0 || !in_array($newRole, $allowedRoles)) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters provided.']);
    exit;
}

// 4. UPDATE DATABASE: Modify the user's privilege status row
$statement = $connection->prepare('UPDATE users SET role = ? WHERE id = ?');
$statement->bind_param('si', $newRole, $userId);

if ($statement->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database execution failed.']);
}
exit;