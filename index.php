<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=image_processers', 'root', '');

require 'user.php';
require 'imageUpload.php';
require 'imageProcessor.php';

$user = new User($db);
$imageUpload = new ImageUpload($db);
$imageProcessor = new ImageProcessor();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $loggedInUser = $user->login($username, $password);
        if ($loggedInUser) {
            $_SESSION['user_id'] = $loggedInUser['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } elseif (isset($_POST['upload'])) {
        $userId = $_SESSION['user_id'];
        $file = $_FILES['file'];
        $imageUpload->uploadImage($userId, $file);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Image Upload System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form id="loginForm" action="" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
