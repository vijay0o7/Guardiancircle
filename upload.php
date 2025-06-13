<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "gaurdian_circle");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $target = "uploads/" . $imageName;

        if (move_uploaded_file($image['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO images (filename) VALUES (?)");
            $stmt->bind_param("s", $imageName);
            $stmt->execute();
            echo "✅ Image uploaded successfully.";
        } else {
            echo "❌ Failed to move uploaded file.";
        }
    } else {
        echo "❌ No image file received in request.";
    }
} else {
    echo "❌ Invalid request method: " . $_SERVER['REQUEST_METHOD'];
}
?>
