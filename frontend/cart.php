<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
require_once '../backend/conn.php'; // Assuming this now returns mysqli connection

// Fetch cart items from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id, c.quantity, c.product_id, 
               p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart = $result->fetch_all(MYSQLI_ASSOC);

    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} catch (Exception $e) {
    // Handle error appropriately
    die("Error fetching cart items: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800">

<?php include 'partials/header.php'; ?>

<main class="max-w-5xl mx-auto px-4 py-8 h-[calc(82vh)]">
    <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

    <?php if (count($cart) === 0): ?>
        <p class="text-gray-600">Keranjang Anda kosong. <a href="index.php" class="text-blue-600 hover:underline">Belanja sekarang</a>.</p>
    <?php else: ?>
        <form action="../backend/update_cart.php" method="post">
            <div class="space-y-4">
                <?php foreach ($cart as $index => $item): ?>
                    <div class="flex items-center gap-4 bg-white shadow rounded p-4">
                        <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" class="w-20 h-20 rounded object-cover">
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold"><?= htmlspecialchars($item['name']) ?></h2>
                            <p class="text-sm text-gray-600">Harga: Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                            <p class="text-sm text-gray-600">Subtotal: Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="items[<?= $index ?>][id]" value="<?= $item['id'] ?>">
                            <input type="number" name="items[<?= $index ?>][quantity]" min="1" value="<?= $item['quantity'] ?>"
                                   class="w-16 border rounded px-2 py-1">
                            <a href="../backend/remove_from_cart.php?id=<?= $item['id'] ?>" 
                               class="text-red-500 hover:underline text-sm">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 text-sm">
                        Perbarui Keranjang
                    </button>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>
                    <a href="checkout.php" class="mt-2 inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 text-sm">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php'; ?>
</body>
</html>