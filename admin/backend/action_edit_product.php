<?php
require_once 'conn.php';

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Ambil dan validasi data dari form
$id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$name = trim($_POST['name']);
$category_id = intval($_POST['category_id']);
$price = floatval($_POST['price']);
$stock = intval($_POST['stock']);
$description = trim($_POST['description']);

// Validasi minimal
if (empty($name) || $category_id <= 0 || $price < 0 || $stock < 0) {
    die("Invalid input data.");
}

// Query untuk mendapatkan nama file lama (jika ada)
$query = "SELECT image FROM products WHERE id = $id";
$result = $conn->query($query);
if ($result->num_rows === 0) {
    die("Product not found.");
}
$existing = $result->fetch_assoc();
$currentImage = $existing['image'];

$imageName = $currentImage;

// Cek apakah ada gambar baru di-upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $fileTmp = $_FILES['image']['tmp_name'];
    $fileName = basename($_FILES['image']['name']);
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array(strtolower($fileExt), $allowedExts)) {
        die("Invalid image file type.");
    }

    // Buat nama file unik
    $newFileName = uniqid('img_', true) . '.' . $fileExt;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        // Hapus file lama jika ada
        if (!empty($currentImage) && file_exists($uploadDir . $currentImage)) {
            unlink($uploadDir . $currentImage);
        }
        $imageName = $newFileName;
    } else {
        die("Failed to upload image.");
    }
}

// Update ke database
$updateSql = "UPDATE products 
              SET name = ?, category_id = ?, price = ?, stock = ?, description = ?, image = ?
              WHERE id = ?";

$stmt = $conn->prepare($updateSql);
$stmt->bind_param("siddssi", $name, $category_id, $price, $stock, $description, $imageName, $id);

if ($stmt->execute()) {
    header("Location: ../products.php?success=1");
    exit();
} else {
    echo "Failed to update product: " . $stmt->error;
}

$stmt->close();
$conn->close();
