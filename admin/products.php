<?php 
session_start(); 
// Add admin authentication check
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}


// Add database connection and fetch products
require_once '../backend/conn.php';
$sql = "SELECT products.*, categories.name as category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Management - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 h-screen">
    <main class="flex mx-auto h-[calc(88vh)]">
        <!-- Sidebar Navigation -->
        <div class="w-64 min-h-screen bg-gray-800">
            <?php include 'partials/sidebar.php'; ?>    
        </div>

        <div class="flex-1">
            <?php include 'partials/header.php'; ?>
        
            <!-- Products Content -->
            <div class="flex-1 bg-white p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Products Management</h1>
                    <a href="add_product.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Add New Product
                    </a>
                </div>

                <div id="addProductForm" class="hidden mb-6 bg-gray-100 p-6 rounded-lg">
    <h2 class="text-xl font-semibold mb-4">Add New Product</h2>
    <form action="backend/process_add_product.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
        <div class="col-span-1">
            <label class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div class="col-span-1">
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <?php
                $cat_sql = "SELECT * FROM categories";
                $cat_result = $conn->query($cat_sql);
                while($cat = $cat_result->fetch_assoc()) {
                    echo "<option value='".$cat['id']."'>".$cat['name']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" step="0.01" name="price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-gray-700">Stock</label>
            <input type="number" name="stock" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700">Product Image</label>
            <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
        </div>

        <div class="col-span-2 flex gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Save Product</button>
            <button type="button" onclick="toggleAddForm()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</button>
        </div>
    </form>
</div>
                
                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['name']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp. <?php echo $row['price']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['stock']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['category_name']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="backend/delete_product.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" 
                                               onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>

<!-- Add this script at the bottom of the page, before </body> -->
<script>
function toggleAddForm() {
    const form = document.getElementById('addProductForm');
    form.classList.toggle('hidden');
}

// Modify the "Add New Product" button to toggle the form
document.querySelector('a[href="add_product.php"]').setAttribute('href', 'javascript:void(0)');
document.querySelector('a[href="javascript:void(0)"]').setAttribute('onclick', 'toggleAddForm()');
</script>

