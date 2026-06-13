<?php
session_start();
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Authorization check — only admins can fetch full workshop list
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$query = "SELECT id, title, description, start_time, end_time, location, mentor_name, materials_link, max_participants,
          DATE_FORMAT(start_time, '%d %b %Y, %h:%i %p') as formatted_start,
          DATE_FORMAT(end_time, '%d %b %Y, %h:%i %p') as formatted_end
          FROM workshops 
          ORDER BY start_time DESC";

$result = $connection->query($query);
$workshops = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Compute status dynamically from timestamps
        $now = new DateTime();
        $start = new DateTime($row['start_time']);
        $end = new DateTime($row['end_time']);

        if ($now < $start) {
            $row['status'] = 'upcoming';
        } elseif ($now >= $start && $now <= $end) {
            $row['status'] = 'ongoing';
        } else {
            $row['status'] = 'completed';
        }

        $workshops[] = $row;
    }
}

echo json_encode($workshops);
exit;
