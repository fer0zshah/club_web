<?php

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.html');
    exit;
}

$eventType = trim($_POST['event_type'] ?? '');
$title = trim($_POST['title'] ?? '');
$startTime = trim($_POST['start_time'] ?? '');
$endTime = trim($_POST['end_time'] ?? '');
$location = trim($_POST['location'] ?? '');
$category = trim($_POST['category'] ?? '');
$hostName = trim($_POST['host_name'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($eventType === '' || $title === '' || $startTime === '' || $endTime === '' || $location === '' || $category === '' || $hostName === '' || $description === '') {
    exit('All event fields are required. <a href="admin.html">Go back</a>');
}

$startTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $startTime)));
$endTime = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $endTime)));

$statement = $connection->prepare('INSERT INTO events (event_type, title, start_time, end_time, location, category, host_name, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$statement->bind_param('ssssssss', $eventType, $title, $startTime, $endTime, $location, $category, $hostName, $description);

if ($statement->execute()) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Saved</title><style>body{font-family:Arial,sans-serif;background:#0f172a;color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}.card{background:#111827;padding:32px;border-radius:16px;max-width:520px;width:calc(100% - 32px);box-shadow:0 20px 50px rgba(0,0,0,.35)}a{color:#93c5fd}</style></head><body><div class="card"><h1>Saved successfully</h1><p>The new ' . htmlspecialchars($eventType, ENT_QUOTES, 'UTF-8') . ' was stored in MySQL.</p><p><a href="admin.html">Create another event</a></p></div></body></html>';
    exit;
}

exit('Failed to save event: ' . htmlspecialchars($connection->error, ENT_QUOTES, 'UTF-8'));