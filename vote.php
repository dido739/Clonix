<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to vote on posts.";
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_GET['post_id']);
$type = $_GET['type']; 


$check_vote = $conn->prepare("SELECT vote_type FROM votes WHERE user_id = ? AND post_id = ?");
$check_vote->bind_param("ii", $user_id, $post_id);
$check_vote->execute();
$result = $check_vote->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $existing_vote = $row['vote_type'];

    if ($existing_vote == $type) {
        
        $delete_vote = $conn->prepare("DELETE FROM votes WHERE user_id = ? AND post_id = ?");
        $delete_vote->bind_param("ii", $user_id, $post_id);
        $delete_vote->execute();
    } else {
     
        $update_vote = $conn->prepare("UPDATE votes SET vote_type = ? WHERE user_id = ? AND post_id = ?");
        $update_vote->bind_param("sii", $type, $user_id, $post_id);
        $update_vote->execute();
    }
} else {

    $insert_vote = $conn->prepare("INSERT INTO votes (user_id, post_id, vote_type) VALUES (?, ?, ?)");
    $insert_vote->bind_param("iis", $user_id, $post_id, $type);
    $insert_vote->execute();
}


$update_votes = $conn->prepare("
    UPDATE posts 
    SET votes = (
        SELECT COALESCE(SUM(CASE WHEN vote_type = 'up' THEN 1 WHEN vote_type = 'down' THEN -1 ELSE 0 END), 0) 
        FROM votes WHERE post_id = ?
    ) 
    WHERE id = ?
");
$update_votes->bind_param("ii", $post_id, $post_id);
$update_votes->execute();

header("Location: index.php");
exit();
?>
