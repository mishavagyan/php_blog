<?php
// session_start();
// // register.php
// include 'includes/db.php';

// if (isset($_SESSION['user_id'])) {
//     header("Location: index.php");
//     exit();
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $username = $_POST['username'];
//     $email = $_POST['email'];
//     $password = $_POST['password'];
//     $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

//     // Check if the username or email already exists
//     $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
//     $stmt->bind_param("ss", $username, $email);
//     $stmt->execute();
//     $stmt->store_result();

//     if ($stmt->num_rows > 0) {
//         // get old passowrd
//         $oldPasswordHash = $conn->prepare("SELECT password FROM users WHERE username = ? AND email = ?");
//         $oldPasswordHash->bind_param("ss", $username, $email);
//         $oldPasswordHash->execute();
//         $oldPasswordHash->store_result();
//         $oldPasswordHash->bind_result($oldPass);
//         $oldPasswordHash->fetch();
//         if(password_verify($password, $oldPass)) {
//             $error = "New password can't be the same as the old one. Please try again.";
//         } else {
//             // $error = "Username or email already exists. Please choose a different one.";
//             $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
//             $updateStmt->bind_param("sss", $passwordHash, $username, $email);
//             if($updateStmt->execute()) {
//                 $success = "Password updated successfully. You can now log in with your new password.";
//             } else {
//                 $error = "An error occurred while updating the password. Please try again.";
//             }
//         }
//     } else {
//         // Insert user into the database
//         $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
//         $stmt->bind_param("sss", $username, $email, $passwordHash);
//         if ($stmt->execute()) {
//             header("Location: login.php");
//             exit();
//         } else {
//             $error = "Error: " . $stmt->error;
//         }
//     }
// }




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
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
            // get old passowrd
        if(password_verify($password, $row['password'])) {
            $error = "New password can't be the same as the old one. Please try again.";
        } else {
            // $error = "Username or email already exists. Please choose a different one.";
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
            $updateStmt->bind_param("sss", $passwordHash, $username, $email);
            if($updateStmt->execute()) {
                $success = "Password updated successfully. You can now log in with your new password.";
            } else {
                $error = "An error occurred while updating the password. Please try again.";
            }
        }
    } else {
        $error = "No user found with the given username and email.";
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
        <h1>Change password</h1>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <form action="forgot.php" method="post" id="registrationForm">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">New password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="captcha">Captcha:</label>

            <span id="registerNum1"></span>
            <span>+</span>
            <span id="registerNum2"></span><br>
            <input type="text" id="registerCaptcha" name="captcha" required><br><br>

            <input type="submit" value="Confirm">
        </form>

        <a href="login.php">Already have an account? Login here</a>
        <a href="register.php">Don't have an account? Register here</a>
    </div>
    <script src="./assets/css/js/app.js"></script>
</body>
</html>
