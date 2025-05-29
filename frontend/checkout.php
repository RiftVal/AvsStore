<?php
session_start();
require_once '../backend/conn.php';
require_once '../utils/crypto.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$query = "SELECT c.*, p.name, p.price 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_all(MYSQLI_ASSOC);

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$bank_query = "SELECT * FROM bank WHERE user_id = ?";
$bank_stmt = $conn->prepare($bank_query);
$bank_stmt->bind_param("i", $user_id);
$bank_stmt->execute();
$bank_result = $bank_stmt->get_result();
$banks = $bank_result->fetch_all(MYSQLI_ASSOC);

// QRIS content
$qris_content = "PAYMENT|MERCHANT:DemoStore|AMOUNT:$total|NOTE:CheckoutOrder-$user_id";
$qris_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qris_content);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function toggleQRIS() {
            const qrisBox = document.getElementById('qris-box');
            const selectedPayment = document.querySelector('input[name="payment"]:checked');
            if (selectedPayment && selectedPayment.value === 'qris') {
                qrisBox.classList.remove('hidden');
            } else {
                qrisBox.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="payment"]');
            radios.forEach(radio => radio.addEventListener('change', toggleQRIS));
        });
    </script>
</head>
<body class="bg-gray-50 text-gray-800">
<?php include 'partials/header.php'; ?>

<main class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    <?php if (empty($cart)): ?>
        <p class="text-gray-600">Keranjang Anda kosong. <a href="index.php" class="text-blue-600 hover:underline">Belanja sekarang</a>.</p>
    <?php else: ?>
        <form action="../backend/process_checkout.php" method="post" class="space-y-6">
            <!-- Informasi Pengiriman -->
            <div class="bg-white shadow rounded p-4">
                <h2 class="font-semibold mb-4 text-lg">Informasi Pengiriman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input name="name" type="text" placeholder="Nama Lengkap" required class="border px-4 py-2 rounded w-full">
                    <input name="phone" type="text" placeholder="Nomor HP" required class="border px-4 py-2 rounded w-full">
                    <input name="email" type="email" placeholder="Email" class="border px-4 py-2 rounded w-full">
                    <input name="postal" type="text" placeholder="Kode Pos" class="border px-4 py-2 rounded w-full">
                </div>
                <textarea name="address" rows="3" placeholder="Alamat Lengkap" required class="mt-4 w-full border px-4 py-2 rounded"></textarea>
            </div>

            <!-- Metode Pembayaran -->
            <div class="bg-white shadow rounded p-4">
                <h2 class="font-semibold mb-4 text-lg">Metode Pembayaran</h2>
                <?php foreach ($banks as $bank): ?>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="radio" name="payment" value="<?= htmlspecialchars($bank['id']) ?>" required>
                        <span><?= htmlspecialchars(decryptData($bank['bank_name'])) ?> - <?= htmlspecialchars(decryptData($bank['card_number'])) ?> - CVV (<?= htmlspecialchars(decryptData($bank['cvv'])) ?>)</span>
                    </label>
                <?php endforeach; ?>
                <label class="flex items-center gap-2 mt-2">
                    <input type="radio" name="payment" value="cod">
                    <span>Bayar di Tempat (COD)</span>
                </label>
                <label class="flex items-center gap-2 mt-2">
                    <input type="radio" name="payment" value="qris">
                    <span>Pembayaran QRIS</span>
                </label>
            </div>

            <!-- QRIS Box -->
            <div id="qris-box" class="bg-white shadow rounded p-4 hidden">
                <h2 class="font-semibold mb-4 text-lg">Scan QRIS</h2>
                <p class="text-sm mb-2 text-gray-600">Silakan scan menggunakan aplikasi e-wallet seperti GoPay, OVO, DANA, dll.</p>
                <div class="flex justify-center mt-4">
                    <img src="<?= $qris_url ?>" alt="QRIS Code" class="border rounded p-2 bg-white shadow">
                </div>
                <p class="text-center text-xs text-gray-500 mt-2">QR ini hanya simulasi. Pembayaran akan dicek manual oleh admin.</p>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="bg-white shadow rounded p-4">
                <h2 class="font-semibold mb-4 text-lg">Ringkasan Pesanan</h2>
                <ul class="space-y-2">
                    <?php foreach ($cart as $item): ?>
                        <li class="flex justify-between text-sm">
                            <span><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?></span>
                            <span>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="border-t mt-4 pt-4 flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded text-lg font-semibold">
                Buat Pesanan
            </button>
        </form>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php'; ?>
</body>
</html>
