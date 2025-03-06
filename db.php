<?php
$host = 'localhost';
$dbname = 'clonix';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error in connecting: " . $conn->connect_error);
}
?>
