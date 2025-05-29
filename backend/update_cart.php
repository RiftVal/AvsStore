<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.php');
    exit;
}

// Check if form data is submitted
if (!isset($_POST['items']) || !is_array($_POST['items'])) {
    header('Location: ../frontend/cart.php');
    exit;
}

require_once '../backend/conn.php';

try {
    // Prepare update statement
    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);

    $user_id = $_SESSION['user_id'];

    // Update each cart item
    foreach ($_POST['items'] as $item) {
        if (!isset($item['id']) || !isset($item['quantity'])) {
            continue;
        }

        $quantity = max(1, (int)$item['quantity']); // Ensure quantity is at least 1
        $cart_id = (int)$item['id'];

        $update_stmt->bind_param("iii", $quantity, $cart_id, $user_id);
        $update_stmt->execute();
    }

    header('Location: ../frontend/cart.php?success=1');
    exit;

} catch (Exception $e) {
    header('Location: ../frontend/cart.php?error=1');
    exit;
}
