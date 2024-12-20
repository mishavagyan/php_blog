<?php
// login.php
ini_set('session.cookie_lifetime', 0); // Session lasts until browser closes
ini_set('session.cookie_path', '/'); // Make the session available site-wide
ini_set('session.cookie_samesite', 'Lax'); // Prevent cross-site issues
ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // Use secure cookies over HTTPS
session_start();

session_start();
include 'includes/db.php';
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Start session and store user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post" id="loginForm">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="captcha">Captcha:</label>

            <span id="loginNum1"></span>
            <span>+</span>
            <span id="loginNum2"></span><br>
            <input type="text" id="loginCaptcha" name="captcha" required><br><br>

            <!-- <input type="text" id="captcha" name="captcha" required><br><br> -->

            <input type="submit" value="Login">
        </form>

        <a href="register.php">Don't have an account? Register here</a>
        <a href="forgot.php">Forgot password</a>
    </div>
    <script src="./assets/css/js/app.js"></script>
</body>
</html>
