<?php
// Assuming you have a database connection file
require_once '../backend/conn.php';

// Get selected category from URL parameter
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Query to fetch products from database
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id";

// Add category filter if category is selected
if ($category_id > 0) {
    $query .= " WHERE p.category_id = " . $category_id;
}

$result = mysqli_query($conn, $query);

// Fetch categories for filter
$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
?>

<section class="flex-1">
    <h2 class="text-xl font-semibold mb-4">Katalog Produk</h2>
    
    <!-- Category Filter -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Filter by Category:</label>
        <div class="flex gap-2">
            <a href="?category=0" 
               class="px-3 py-1 rounded <?= $category_id == 0 ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">
                All
            </a>
            <?php while($category = mysqli_fetch_assoc($categories_result)): ?>
            <a href="?category=<?= $category['id'] ?>" 
               class="px-3 py-1 rounded <?= $category_id == $category['id'] ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">
                <?= htmlspecialchars($category['name']) ?>
            </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php while($product = mysqli_fetch_assoc($result)): ?>
        <div class="bg-white rounded shadow p-4">
            <a href="product-detail.php?id=<?= $product['id'] ?>" class="block">
            <div class="aspect-square bg-gray-200 mb-2">
                <img 
                    src="../../admin/backend/uploads/<?= !empty($product['image']) ? $product['image'] : 'https://placehold.co/400x400?text=No+Image' ?>" 
                    alt="<?= htmlspecialchars($product['name']) ?>"
                    class="w-full h-full object-cover"
                >
            </div>
            <h3 class="text-sm font-medium"><?= htmlspecialchars($product['name']) ?></h3>
            <p class="text-gray-500 text-xs"><?= htmlspecialchars($product['category_name']) ?></p>
            <p class="text-blue-600 font-semibold">Rp <?= number_format($product['price']) ?></p>
            </a>
            <form action="../backend/add_to_cart.php" method="POST" class="mt-2">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" class="w-full bg-blue-600 text-white text-sm py-1.5 rounded hover:bg-blue-700">
                    Tambah ke Keranjang
                </button>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</section>
