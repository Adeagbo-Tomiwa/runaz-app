<?php
$host = "localhost";
$user = "root";
$pass = ""; // your phpMyAdmin password
$db   = "runaz";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
