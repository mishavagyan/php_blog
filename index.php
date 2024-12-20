<?php
session_start();
include 'includes/db.php';

// Fetch all posts, ordered by creation date
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">    
        <h1>Welcome to My Blog</h1>

        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">Logout</a> | <a href="changePassword.php">Change password</a></p>
            <a href="create_post.php">Create New Post</a>
        <?php else: ?>
                <a href="login.php">Login</a> | <a href="register.php">Register</a>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while($row = $result->fetch_assoc()): ?>
                <li class="post">
                    <h2><?= htmlspecialchars($row['title']); ?></h2>
                    <p><?= substr(htmlspecialchars($row['content']), 0, 200); ?>...</p>
                    <?php if ($_SESSION['role'] === 'admin' || (int)$_SESSION['user_id'] === (int)$row['user_id']): ?>
                        <div class="post-actions">
                            <a href="edit_post.php?id=<?= $row['id']; ?>&user_id=<?= $row['user_id']; ?>" style="color:white;">Edit</a> | 
                            <a href="delete_post.php?id=<?= $row['id']; ?>&user_id=<?= $row['user_id']; ?>" style="color:white;">Delete</a>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
        <?php else: ?>
        <p>No posts yet. Create one!</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
