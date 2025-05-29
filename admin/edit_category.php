<?php
require_once '../backend/conn.php';

// Validasi ID
if (!isset($_GET['id'])) {
    die("ID kategori tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data kategori berdasarkan ID
$sql = "SELECT * FROM categories WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Kategori tidak ditemukan.");
}

$category = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 h-screen">
    <main class="flex mx-auto h-[calc(100vh)]">
        <!-- Sidebar Navigation -->
        <div class="w-64 min-h-screen bg-gray-800">
            <?php include 'partials/sidebar.php'; ?>    
        </div>

        <div class="flex-1">
            <?php include 'partials/header.php'; ?>

            <div class="flex-1 bg-white p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Category</h1>
                    <a href="categories.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Categories
                    </a>
                </div>

                <!-- Edit Category Form -->
                <form action="backend/action_edit_category.php" method="POST" class="bg-gray-100 p-6 rounded-lg max-w-xl">
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($category['name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Update Category</button>
                        <a href="categories.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
