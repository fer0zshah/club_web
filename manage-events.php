<?php
session_start();

// GATEKEEPER CHECK: Only admin-level users can access this page
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: home.html');
    exit;
}

// Serve the pure HTML file
readfile(__DIR__ . '/manage-events.html');
exit;
