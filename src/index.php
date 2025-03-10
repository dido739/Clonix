<?php
session_start();
include 'header.php';


require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

$query = "SELECT posts.id, posts.title, posts.content, posts.votes, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$result = $conn->query($query);
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
    <h1>Welcome to Clonix</h1>
    
</header>

    <main>
        <h2>Latest posts</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <p>Author: <?php echo htmlspecialchars($row['username']); ?></p>
                
    Votes: <?php

$user_vote = null;
if (isset($_SESSION['user_id'])) {
    $check_user_vote = $conn->prepare("SELECT vote_type FROM votes WHERE user_id = ? AND post_id = ?");
    $check_user_vote->bind_param("ii", $_SESSION['user_id'], $row['id']);
    $check_user_vote->execute();
    $result_vote = $check_user_vote->get_result();
    if ($result_vote->num_rows > 0) {
        $vote_data = $result_vote->fetch_assoc();
        $user_vote = $vote_data['vote_type']; 
    }
}
?>

<p>
    Votes: <?php echo $row['votes']; ?>
    <a href="vote.php?post_id=<?php echo $row['id']; ?>&type=up"
       class="<?php echo ($user_vote === 'up') ? 'vote-up active' : 'vote-up'; ?>">⬆️</a>
    <a href="vote.php?post_id=<?php echo $row['id']; ?>&type=down"
       class="<?php echo ($user_vote === 'down') ? 'vote-down active' : 'vote-down'; ?>">⬇️</a>
</p>

</p>
</a></p>
            </div>
        <?php endwhile; ?>
    </main>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="error">
        <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']); 
        ?>
    </div>
<?php endif; ?>

</body>
</html>
