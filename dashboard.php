<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$db = new PDO('mysql:host=localhost;dbname=image_processers', 'root', '');
require 'imageUpload.php';

$imageUpload = new ImageUpload($db);

$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    $file = $_FILES['file'];

    if ($imageUpload->uploadImage($userId, $file)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Failed to upload image.";
    }
}

$visibleImages = $imageUpload->getImagesVisibleToUser($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Image Upload System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="navbar">
        
        <div class="user-info">
            <p>Welcome, <?php echo htmlspecialchars($user['username']); ?></p>
            <form action="logout.php" method="post">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="header">
            <h2>Dashboard</h2>
        </div>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="file" required>
            </div>
            <button type="submit" name="upload" class="upload-btn">Upload</button>
        </form>

        <h3>Images Visible to You</h3>
        <div class="image-gallery">
            <?php foreach ($visibleImages as $image): ?>
                <div class="image-item">
                    <img src="<?php echo $image['file_path']; ?>" alt="Image">
                    <p>Uploaded by: <?php echo $image['uploader_username']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

