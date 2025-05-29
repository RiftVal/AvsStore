<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['transaction_id'] ?? null;
    $status = $_POST['status'] ?? '';

    if ($id && in_array($status, ['pending', 'processed', 'completed', 'cancelled'])) {
        $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo "Prepare failed: " . $conn->error;
            exit;
        }

        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Execute failed: " . $stmt->error;
        }
    } else {
        http_response_code(400);
        echo "Invalid input";
    }
} else {
    http_response_code(405);
    echo "Invalid request method";
}
