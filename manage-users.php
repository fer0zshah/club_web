<?php
session_start();

// 1. GATEKEEPER CHECK: Secure the area
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: home.html');
    exit;
}

// 2. SUCCESS: Serve the pure HTML file
readfile(__DIR__ . '/manage-users.html');
exit;