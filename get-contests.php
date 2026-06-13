<?php
session_start();
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Authorization check — only admins can fetch full contest list
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$query = "SELECT id, title, description, start_time, end_time, location, platform_link, status, contest_type,
          DATE_FORMAT(start_time, '%d %b %Y, %h:%i %p') as formatted_start,
          DATE_FORMAT(end_time, '%d %b %Y, %h:%i %p') as formatted_end
          FROM contests 
          ORDER BY start_time DESC";

$result = $connection->query($query);
$contests = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contests[] = $row;
    }
}

echo json_encode($contests);
exit;
