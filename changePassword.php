<?php

session_start();
include 'includes/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $password = $_POST["password"];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // echo $passwordHash;
    // die();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
        if(password_verify($password, $row["password"])) {
            $error = "New password can't be the same as the old one. Please try again.";
        } else {
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $passwordHash, $username);
            if($updateStmt->execute()) {
                $success = "Password updated successfully. You can now log in with your new password.";
            } else {
                $error = "An error occurred while updating the password. Please try again.";
            }
        }
    } else {
        $error = "Something is wrong.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="changePassword.php" method="post" id="changeForm">

            <label for="password">New password:</label>
            <input type="password" id="password" name="password" required><br><br>


            <label for="captcha">Captcha:</label>

            <span id="changeNum1"></span>
            <span>+</span>
            <span id="changeNum2"></span><br>
            <input type="text" id="changeCaptcha" name="captcha" required><br><br>

            <!-- <input type="text" id="captcha" name="captcha" required><br><br> -->

            <input type="submit" value="Confirm">
        </form>

        <a href="index.php">Back to blog</a>
    </div>
    <script src="./assets/css/js/app.js"></script>
</body>
</html>
