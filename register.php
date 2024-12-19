<?php
session_start();
// register.php
include 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Check if the username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username or email already exists. Please choose a different one.";
    } else {
        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="post" id="registrationForm">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="captcha">Captcha:</label>

            <span id="registerNum1"></span>
            <span>+</span>
            <span id="registerNum2"></span><br>
            <input type="text" id="registerCaptcha" name="captcha" required><br><br>

            <input type="submit" value="Register">
        </form>

        <a href="login.php">Already have an account? Login here</a>
    </div>
    <script src="./assets/css/js/app.js"></script>
</body>
</html>
