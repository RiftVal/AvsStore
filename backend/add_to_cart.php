<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.php');
    exit;
}

// Check if the request method is POST and product_id is set
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    header('Location: ../frontend/index.php');
    exit;
}

require_once 'conn.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Validate quantity
if ($quantity < 1) {
    $quantity = 1;
}

try {
    // Check if product already exists in cart
    $check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing cart item
        $cart_item = $result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + $quantity;
        
        $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_quantity, $cart_item['id']);
        $update_stmt->execute();
    } else {
        // Insert new cart item
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insert_stmt->execute();
    }

    // Redirect back with success message
    header('Location: ../frontend/cart.php?success=1');
    exit;

} catch (Exception $e) {
    // Redirect back with error message
    header('Location: ../frontend/cart.php?error=1');
    exit;
}