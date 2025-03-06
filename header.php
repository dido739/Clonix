<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реддит Клонинг</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
        <center><ul>
            <li><a href="index.php">🏠 Clonix Начало</a></li>
            <li><a href="search.php">🔍 Търсене</a></li>
            <?php if(isset($_SESSION['username'])): ?>
                <li><a href="create_post.php">📝 Нов пост</a></li>
                <li><a href="logout.php">🚪 Изход</a></li>
                <li>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></li>
            <?php else: ?>
                <li><a href="login.php">🔑 Вход</a></li>
                <li><a href="register.php">📝 Регистрация</a></li>
            <?php endif; ?>
        </center></ul>
    </nav>
</header>
