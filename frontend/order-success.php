<?php
$orderId = $_GET['order_id'] ?? 'XXXXXX';

// Database connection
require_once '../backend/conn.php';
require_once '../utils/crypto.php'; // fungsi encrypt/decrypt
session_start();

// Fetch order details from database
$sql = "SELECT * FROM transactions WHERE id = ?";
    
$stmt = $conn->prepare($sql);
$stmt->execute([$orderId]);
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// If order not found, redirect to home
if (!$order) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-green-50 text-gray-800">
<?php include 'partials/header.php'; ?>

<main class="max-w-3xl mx-auto p-8 text-center h-[calc(82vh)]">
    <h1 class="text-2xl font-bold text-green-700">Pesanan Anda Berhasil!</h1>
    <div class="mt-4 space-y-2">
    <p>ID Pesanan: <strong>#<?= htmlspecialchars($order['id']) ?></strong></p>
    <p>Nama Customer: <strong><?= htmlspecialchars(decryptData($order['recipient_name'])) ?></strong></p>
    <p>Total Pembayaran: <strong>Rp <?= number_format($order['total'], 0, ',', '.') ?></strong></p>
    <p>Status: <strong><?= htmlspecialchars($order['status']) ?></strong></p>
    <p>Tanggal Order: <strong><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></strong></p>
    </div>
    <a href="index.php" class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
    Kembali ke Beranda
    </a>
</main>

<?php include 'partials/footer.php'; ?>
</body>
</html>
