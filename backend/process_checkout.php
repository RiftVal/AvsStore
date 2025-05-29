<?php
session_start();
require_once 'conn.php';
require_once '../utils/crypto.php'; // fungsi encrypt/decrypt

// Validate if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.php');
    exit;
}

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontend/checkout.php');
    exit;
}

// Get form data
$user_id = $_SESSION['user_id'];
$bank_id = $_POST['payment'] ?? '';
$recipient_name = encryptData($_POST['name']) ?? '';
$recipient_email = encryptData($_POST['email']) ?? '';
$recipient_address = encryptData($_POST['address']) ?? '';
$recipient_postal_code = encryptData($_POST['postal']) ?? '';
$recipient_phone = encryptData($_POST['phone']) ?? '';

// Basic validation
if (empty($bank_id)) {
    $_SESSION['error'] = 'Metode pembayaran wajib dipilih';
    header('Location: ../frontend/checkout.php');
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Get cart items
    $cart_query = "SELECT c.*, p.price 
                   FROM cart c 
                   JOIN products p ON c.product_id = p.id 
                   WHERE c.user_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    $cart_items = $cart_result->fetch_all(MYSQLI_ASSOC);

    // Calculate total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert transaction
    $trans_stmt = $conn->prepare("INSERT INTO transactions (user_id, date, status, total, bank_id, recipient_name, recipient_email, recipient_address, recipient_postal_code, recipient_phone) VALUES (?, NOW(), 'pending', ?, ?, ?, ?, ?, ?, ?)");
    $trans_stmt->bind_param("idisssss", $user_id, $total, $bank_id, $recipient_name, $recipient_email, $recipient_address, $recipient_postal_code, $recipient_phone);
    $trans_stmt->execute();
    $transaction_id = $conn->insert_id;

    // Insert transaction details
    $detail_stmt = $conn->prepare("INSERT INTO transaction_details (transaction_id, product_id, total, price) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $detail_stmt->bind_param("iiid", $transaction_id, $item['product_id'], $item['quantity'], $item['price']);
        $detail_stmt->execute();
    }

    // Clear cart
    $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear_cart->bind_param("i", $user_id);
    $clear_cart->execute();

    // Commit transaction
    $conn->commit();

    // Redirect to success page
    $_SESSION['success'] = 'Pesanan berhasil dibuat!';
    header('Location: ../frontend/order-success.php?order_id=' . $transaction_id);
    exit;

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error'] = 'Terjadi kesalahan. Silakan coba lagi.';
    header('Location: ../frontend/checkout.php');
    exit;
}
