<?php
session_start();

require 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Грешка при добавяне на пост.";
        }
    } else {
        $error = "Моля, попълнете всички полета!";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Създай пост</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Създаване на нов пост</h1>
        
    </header>
    <main>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="title" placeholder="Заглавие" required style="width: 305px">
            <textarea rows ="5" cols="44" name="content" placeholder="Съдържание" required></textarea>
            <button type="submit">Публикувай</button>
        </form>
    </main>
</body>
</html>
