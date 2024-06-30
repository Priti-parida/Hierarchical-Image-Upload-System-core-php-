<?php
class ImageProcessor {
    private $uploadDir = 'uploads/';

    public function compressImage($source, $destination, $quality) {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
            imagepng($image, $destination, $quality / 10);
        }
        imagedestroy($image);
    }

    public function cropImage($source, $destination, $x, $y, $width, $height) {
        $image = imagecreatefromjpeg($source);
        $cropped = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);
        if ($cropped !== false) {
            imagejpeg($cropped, $destination);
            imagedestroy($cropped);
        }
        imagedestroy($image);
    }

    public function rotateImage($source, $destination, $angle) {
        $image = imagecreatefromjpeg($source);
        $rotated = imagerotate($image, $angle, 0);
        imagejpeg($rotated, $destination);
        imagedestroy($rotated);
        imagedestroy($image);
    }
}
?>
