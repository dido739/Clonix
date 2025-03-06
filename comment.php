<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Трябва да влезете, за да коментирате!";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $content);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
}
?>
