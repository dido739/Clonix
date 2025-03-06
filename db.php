<?php
$host = 'localhost';
$dbname = 'reddit_clone';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Свързването с базата данни е неуспешно: " . $conn->connect_error);
}
?>
