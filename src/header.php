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
    <title>Clonix</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav>
        <center><ul>
            <li><a href="index.php">🏠 Clonix Home</a></li>
            <li><a href="search.php">🔍 Search</a></li>
            <?php if(isset($_SESSION['username'])): ?>
                <li><a href="create_post.php">📝 New post</a></li>
                <li><a href="my_posts.php">📚 My posts</a></li>
                <li><a href="logout.php">🚪 Exit</a></li>
                <li>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></li>
            <?php else: ?>
                <li><a href="login.php">🔑 Login</a></li>
                <li><a href="register.php">📝 Register</a></li>
            <?php endif; ?>
        </center></ul>
    </nav>
</header>
