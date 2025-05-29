<?php
require_once '../backend/conn.php';

if (!isset($_GET['id'])) {
    die("ID produk tidak ditemukan.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Produk tidak ditemukan.");
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 h-screen">
    <main class="flex mx-auto h-[calc(100vh)]">
        <div class="w-64 min-h-screen bg-gray-800">
            <?php include 'partials/sidebar.php'; ?>    
        </div>

        <div class="flex-1">
            <?php include 'partials/header.php'; ?>
            <div class="flex-1 bg-white p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Product</h1>
                    <a href="produk.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Products
                    </a>
                </div>

                <!-- Edit Product Form -->
                <form action="backend/action_edit_product.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4 bg-gray-100 p-6 rounded-lg">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <?php
                            $cat_sql = "SELECT * FROM categories";
                            $cat_result = $conn->query($cat_sql);
                            while ($cat = $cat_result->fetch_assoc()) {
                                $selected = ($cat['id'] == $product['category_id']) ? 'selected' : '';
                                echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" name="price" required value="<?php echo $product['price']; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" name="stock" required value="<?php echo $product['stock']; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Current Image</label>
                        <?php if (!empty($product['image'])): ?>
                            <img src="../uploads/<?php echo $product['image']; ?>" alt="Current Image" class="w-32 h-auto mb-2">
                        <?php else: ?>
                            <p class="text-sm italic text-gray-500">No image uploaded.</p>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
                    </div>

                    <div class="col-span-2 flex gap-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Update Product</button>
                        <a href="produk.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
