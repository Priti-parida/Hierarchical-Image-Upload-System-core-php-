<?php
// require_once 'imageProcessor.php'; 

class ImageUpload {
    private $db;
    private $uploadDir = 'uploads/';
    private $imageProcessor; 

    public function __construct($db) {
        $this->db = $db;
        // $this->imageProcessor = new ImageProcessor(); 

    }

    public function uploadImage($userId, $file) {
        $fileName = basename($file['name']);
        $targetFilePath = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            // $this->processImage($targetFilePath);

            $stmt = $this->db->prepare("INSERT INTO images (user_id, file_path) VALUES (?, ?)");
            return $stmt->execute([$userId, $targetFilePath]);
        }
        return false;
    }
    // image processing not woking as gd is not wokring in my system 
    // private function processImage($filePath) {
    //     $fileName = basename($filePath);
    //     $compressedFilePath = $this->uploadDir . 'compressed_' . $fileName;
    //     $croppedFilePath = $this->uploadDir . 'cropped_' . $fileName;
    //     $rotatedFilePath = $this->uploadDir . 'rotated_' . $fileName;

    //     if ($this->imageProcessor instanceof ImageProcessor) {
    //         $this->imageProcessor->compressImage($filePath, $compressedFilePath, 75);
    //         $this->imageProcessor->cropImage($filePath, $croppedFilePath, 0, 0, 200, 200);
    //         $this->imageProcessor->rotateImage($filePath, $rotatedFilePath, 90);
    //     } else {
    //         throw new Exception("ImageProcessor not initialized correctly.");
    //     }
    // }

    public function getImages($userId) {
        $stmt = $this->db->prepare("SELECT * FROM images WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImagesForManager($userId) {
        $stmt = $this->db->prepare("
            SELECT images.* FROM images
            JOIN users ON images.user_id = users.id
            WHERE users.manager_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImagesVisibleToUser($userId) {
        $stmt = $this->db->prepare("
            SELECT images.*, users.username AS uploader_username
            FROM images
            LEFT JOIN users ON images.user_id = users.id
            WHERE images.user_id = :userId
            OR images.user_id IN (SELECT id FROM users WHERE manager_id = :userId)
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
