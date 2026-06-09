<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Fetch the 3 most recent workshops including the new status field
$query = "SELECT id, title, description, start_time, end_time, location, mentor_name, materials_link, status,
          DATE_FORMAT(start_time, '%d %b') as formatted_date,
          DATE_FORMAT(start_time, '%h:%i %p') as start_clock,
          DATE_FORMAT(end_time, '%h:%i %p') as end_clock
          FROM workshops 
          ORDER BY start_time DESC LIMIT 3";

$result = $connection->query($query);
$workshops = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $workshops[] = $row;
    }
}

echo json_encode($workshops);
exit;
?>