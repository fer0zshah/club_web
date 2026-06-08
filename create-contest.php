<?php
session_start();

// 1. GATEKEEPER CHECK: If not logged in OR not an admin, boot them to home.html
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: home.html');
    exit;
}

// 2. SUCCESS: Read and display the separate HTML file
// This keeps your HTML completely free of PHP boilerplate!
readfile(__DIR__ . '/create-contest.html');
exit;