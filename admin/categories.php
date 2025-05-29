<?php 
session_start();

// Cek apakah user adalah admin dengan ID 4
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}

// Add database connection and fetch categories
require_once '../backend/conn.php';
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories Management - Simple E-Commerce</title>
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
        
            <!-- Categories Content -->
            <div class="flex-1 bg-white p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Categories Management</h1>
                    <a href="javascript:void(0)" onclick="toggleAddForm()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Add New Category</a>
            </div>
                        

                <!-- Add Category Form -->
            <div id="addCategoryForm" class="hidden mb-6 bg-gray-100 p-4 rounded shadow">
                <form action="backend/add_category.php" method="POST" class="flex items-center space-x-4">
                    <input type="text" name="name" required placeholder="Category Name" class="px-4 py-2 border rounded w-1/3">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="fas fa-save mr-1"></i>Save
                    </button>
                    <button type="button" onclick="toggleAddForm()" class="text-gray-500 hover:text-gray-700">
                        Cancel
                    </button>
                </form>
            </div>

                
                <!-- Categories Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['name']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="backend/delete_categories.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" 
                                               onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No categories found</td>
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
<!-- Add this script at the bottom of the page, before </body> -->
<script>
function toggleAddForm() {
    const form = document.getElementById('addCategoryForm');
    form.classList.toggle('hidden');
}
</script>

</html>


