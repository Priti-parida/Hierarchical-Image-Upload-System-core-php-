<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=image_processers', 'root', '');

require 'user.php';

$user = new User($db);

$managers = $user->getAllManagers(); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role']; 
        $managerId = $_POST['manager_id']??''; 
        if ($user->register($username, $password, $role, $managerId)) {
            header('Location: index.php');
            exit();
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Image Upload System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="register-container">
    <div class="register-form">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form id="registerForm" action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="Manager">Manager</option>
                    <option value="Subordinate">Subordinate</option>
                </select>
            </div>
            <?php if (!empty($managers)): ?>
                <div class="form-group">
                    <label for="manager_id">Select Manager</label>
                    <select id="manager_id" name="manager_id" required>
                        <option value="">Select Manager</option>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?php echo $manager['id']; ?>"><?php echo $manager['username']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
