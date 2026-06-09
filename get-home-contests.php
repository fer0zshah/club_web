<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Fetch the 3 most recent contests with proper clock time formatting
$query = "SELECT id, title, description, start_time, end_time, location, platform_link, status, contest_type,
          DATE_FORMAT(start_time, '%d %b') as formatted_date,
          DATE_FORMAT(start_time, '%h:%i %p') as start_clock,
          DATE_FORMAT(end_time, '%h:%i %p') as end_clock
          FROM contests 
          ORDER BY start_time DESC LIMIT 3";

$result = $connection->query($query);
$contests = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contests[] = $row;
    }
}

echo json_encode($contests);
exit;
?>