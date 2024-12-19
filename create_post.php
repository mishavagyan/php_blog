<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id']; // Store the current user's ID

    // Insert the post into the database
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $content, $user_id);
    $stmt->execute();
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">

        <h1>Create a New Post</h1>
        <form action="create_post.php" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br><br>

            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea><br><br>

            <input type="submit" value="Submit">
        </form>
        
        <a href="index.php">Back to Blog</a>
    </div>
</body>
</html>
