<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.php');
    exit;
}

// Check if cart item ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid cart item ID";
    header('Location: ../frontend/cart.php');
    exit;
}

// Database connection
require_once 'conn.php';

$cart_item_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Prepare and execute delete query with user_id check for security
$sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_item_id, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Item berhasil dihapus dari keranjang";
    } else {
        $_SESSION['error'] = "Gagal menghapus item dari keranjang";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus item";
}

// Redirect back to cart page
header('Location: ../frontend/cart.php');
exit;