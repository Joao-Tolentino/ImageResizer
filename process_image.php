<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed.");
    }

    $resizeFactor = max(0.1, min(3.0, floatval($_POST['resize'] ?? 1)));

    $uploadedFile = $_FILES['image']['tmp_name'];
    $outputFile = 'processed_image.png';

    try {
        // Load image
        $img = new Imagick($uploadedFile);

        // Resize
        $width = $img->getImageWidth();
        $height = $img->getImageHeight();
        $newWidth = intval($width * $resizeFactor);
        $newHeight = intval($height * $resizeFactor);

        $img->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);

        // Save as PNG
        $img->setImageFormat('png');
        $img->writeImage($outputFile);

        echo "Image resized! <a href='$outputFile' target='_blank'>View</a>";

        $img->clear();
        $img->destroy();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} else {
    echo "No image uploaded.";
}
?>