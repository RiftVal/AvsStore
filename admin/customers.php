<?php 
session_start(); 
// Add admin authentication check
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}

// Add database connection and fetch customers
require_once '../backend/conn.php';
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Management - Simple E-Commerce</title>
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
        
            <!-- Customers Content -->
            <div class="flex-1 bg-white p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Customer Management</h1>
                    <a href="add_customer.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Add New Customer
                    </a>
                </div>
                
                <!-- Customers Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['id']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['name']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['email']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['address']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['phone']; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="edit_customer.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_customer.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700" 
                                               onclick="return confirm('Are you sure you want to delete this customer?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">No customers found</td>
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
