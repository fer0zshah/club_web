<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'clubweb';

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die('Database connection failed: ' . $connection->connect_error);
}

$connection->set_charset('utf8mb4');