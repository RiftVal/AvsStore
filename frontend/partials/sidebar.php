<?php
require_once '../backend/conn.php';

$current_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$query = "SELECT * FROM categories ORDER BY name";
$result = mysqli_query($conn, $query);
?>

<aside class="w-64 bg-white shadow-md p-4 rounded hidden md:block">
    <h2 class="text-lg font-semibold mb-3">Kategori</h2>
    <ul class="space-y-2">
        <li>
            <a href="index.php" 
               class="block <?php echo ($current_category === 0) ? 'text-blue-600 font-semibold' : 'hover:text-blue-600'; ?>">
                Semua Produk
            </a>
        </li>
        <?php while ($category = mysqli_fetch_assoc($result)) : ?>
            <li>
                <a href="?category=<?php echo $category['id']; ?>" 
                   class="block <?php echo ($current_category === (int)$category['id']) ? 'text-blue-600 font-semibold' : 'hover:text-blue-600'; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</aside>
