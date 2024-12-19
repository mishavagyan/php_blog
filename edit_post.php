<?php
// Start session and include db.php
session_start();
include 'includes/db.php';

// Check if the post ID is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the post from the database
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    // If post not found
    if (!$post) {
        echo "Post not found!";
        exit();
    }

    // Now check if the user has permission to edit this post
    // Admins can edit any post, but regular users can only edit their own posts
    if ($_SESSION['role'] !== 'admin' && (int)$_SESSION['user_id'] !== (int)$post['user_id']) {
        header("Location: index.php");
        exit();
    }

    // If the form is submitted, update the post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];

        // Prepare the update query based on the user's role
        if ($_SESSION['role'] === 'admin') {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->bind_param("ssi", $title, $content, $id);
        } else {
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ssii", $title, $content, $id, $_SESSION['user_id']);
        }

        $stmt->execute();
        header("Location: index.php");
        exit();
    }
} else {
    echo "No post ID provided!";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">

        <h1>Edit Post</h1>
        <form action="edit_post.php?id=<?= $post['id']; ?>&user_id=<?= $post['user_id']; ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']); ?>" required><br><br>

            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($post['content']); ?></textarea><br><br>

            <input type="submit" value="Save Changes">
        </form>

        <a href="index.php">Back to Blog</a>
    </div>
</body>
</html>
