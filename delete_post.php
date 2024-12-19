<?php
session_start();
include 'includes/db.php';

// Ensure user is either an admin or the post owner
if ($_SESSION['role'] !== 'admin' && (int)$_SESSION['user_id'] !== (int)$_GET['user_id']) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the post
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: index.php");
    exit();
} else {
    echo "Invalid post ID!";
    exit();
}

?>
