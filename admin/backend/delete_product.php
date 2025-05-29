<?php
require_once '../backend/conn.php';

// Pastikan parameter ID disediakan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']);

// Ambil informasi gambar terlebih dahulu
$get_sql = "SELECT image FROM products WHERE id = ?";
$stmt_get = $conn->prepare($get_sql);
$stmt_get->bind_param("i", $product_id);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$image_path = '../uploads/' . $product['image'];

// Hapus gambar jika ada
if (!empty($product['image']) && file_exists($image_path)) {
    unlink($image_path);
}

// Hapus produk dari database
$delete_sql = "DELETE FROM products WHERE id = ?";
$stmt_delete = $conn->prepare($delete_sql);
$stmt_delete->bind_param("i", $product_id);

if ($stmt_delete->execute()) {
    header("Location: ../products.php?deleted=1");
    exit();
} else {
    echo "Failed to delete product: " . $stmt_delete->error;
}

// Tutup koneksi
$stmt_get->close();
$stmt_delete->close();
$conn->close();
