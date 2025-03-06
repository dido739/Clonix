<?php
require 'db.php';
session_start();
$error = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $id;
                header("Location: index.php");
                exit();
            } else {
                $error = "Wrong password!";
            }
        } else {
            $error = "User not found!";
        }
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOgin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required style="width: 305px">
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required style="width: 305px">
        <br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have account <a href="register.php">Register</a></p>
</body>
</html>
