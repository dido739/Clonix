<?php
require 'db.php';
$error = ""; // Променлива за грешки

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Паролите не съвпадат!";
        } else {
            // Проверка дали потребителското име вече съществува
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Потребителското име вече е заето!";
            } else {
                // Хеширане на паролата
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashed_password);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Грешка при регистрация!";
                }
            }
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
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Регистрация</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="register.php">
        <label for="username">Потребителско име:</label>
        <input type="text" name="username" required style="width: 305px">
        <br>
        <label for="password">Парола:</label>
        <input type="password" name="password" required style="width: 305px">
        <br>
        <label for="confirm_password">Потвърди паролата:</label>
        <input type="password" name="confirm_password" required style="width: 305px">
        <br>
        <button type="submit">Регистрация</button>
    </form>
    <p>Вече имате акаунт? <a href="login.php">Влезте тук</a></p>
</body>
</html>
