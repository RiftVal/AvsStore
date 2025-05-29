<?php 
session_start(); 
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}

require_once '../backend/conn.php'; // pastikan path koneksi benar

// Query untuk mendapatkan total produk
$result_products = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $result_products ? $result_products->fetch_assoc()['total'] : 0;

// Query untuk mendapatkan total transaksi
$result_orders = $conn->query("SELECT COUNT(*) as total FROM transactions");
$total_orders = $result_orders ? $result_orders->fetch_assoc()['total'] : 0;

// Query untuk mendapatkan total user (optional: filter user non-admin)
$result_users = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $result_users ? $result_users->fetch_assoc()['total'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800 h-screen">
    <main class="flex mx-auto h-[calc(88vh)]">
        <!-- Sidebar Navigation -->
        <div class="w-64 min-h-screen bg-gray-800">
            <?php include 'partials/sidebar.php'; ?>    
        </div>

        <div class="flex-1">
        <?php include 'partials/header.php'; ?>
        
        <!-- Admin Dashboard Content -->
        <div class="flex-1 bg-white p-6">
            <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
            
            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="font-bold">Total Products</h3>
                    <p class="text-2xl"><?php echo $total_products; ?></p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="font-bold">Total Orders</h3>
                    <p class="text-2xl"><?php echo $total_orders; ?></p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h3 class="font-bold">Total Users</h3>
                    <p class="text-2xl"><?php echo $total_users; ?></p>
                </div>
            </div>
            
            <!-- Recent Activities -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Recent Activities</h2>
                <!-- Add your recent activities content here -->
                <p class="text-gray-500 italic">Coming soon...</p>
            </div>
        </div>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
