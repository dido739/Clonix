<?php
require 'db.php';
include 'header.php';

$query = "";
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = trim($_GET['query']);

    if (!empty($query)) {
        $stmt = $conn->prepare("SELECT posts.id, posts.title, posts.content, posts.votes, users.username 
                                FROM posts 
                                JOIN users ON posts.user_id = users.id 
                                WHERE posts.title LIKE ? OR posts.content LIKE ? 
                                ORDER BY posts.created_at DESC");
        $searchTerm = "%$query%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}
?>

<main>
    <h1>Търсене</h1>
    <form action="search.php" method="GET">
        <input type="text" name="query" placeholder="Въведете дума за търсене..." required style="width: 305px">
        <button type="submit">Търси</button>
    </form>

    <?php if (!empty($query)): ?>
        <h2>Резултати за "<?php echo htmlspecialchars($query); ?>"</h2>
        <?php if ($results->num_rows > 0): ?>
            <?php while ($row = $results->fetch_assoc()): ?>
                <div class="post">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <p>Автор: <?php echo htmlspecialchars($row['username']); ?></p>
                    <p>Гласове: <?php echo $row['votes']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Няма намерени резултати.</p>
        <?php endif; ?>
    <?php endif; ?>
</main>
</body>
</html>
