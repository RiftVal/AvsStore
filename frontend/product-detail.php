<?php
require_once '../backend/conn.php'; // Make sure this file exists with database connection

// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prepare and execute query to get product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if product exists
if ($result->num_rows === 0) {
    header("Location: 404.php"); // Redirect to 404 page if product not found
    exit;
}

// Fetch product data
$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<!-- Rest of your HTML remains the same -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800">
    
    <?php include 'partials/header.php'; ?>

    <main class="max-w-7xl mx-auto px-4 py-8 h-[calc(82vh)]">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Gambar Produk -->
            <div>
                <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full rounded shadow">
            </div>

            <!-- Detail Produk -->
            <div>
                <h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                <p class="text-xl text-blue-600 font-semibold mb-4">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                <p class="text-gray-700 mb-6"><?= htmlspecialchars($product['description']) ?></p>

                <p class="mb-2 text-sm">Stok tersedia: <span class="font-medium"><?= $product['stock'] ?> pcs</span></p>

                <form action="../backend/add_to_cart.php" method="post" class="space-y-4">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <div>
                        <label for="quantity" class="block text-sm font-medium mb-1">Jumlah Pembelian</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?= $product['stock'] ?>" value="1"
                            class="w-24 border rounded px-2 py-1 focus:outline-none focus:ring focus:border-blue-300" required>
                    </div>

                    <div class="flex gap-4 mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Tambah ke Keranjang</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
