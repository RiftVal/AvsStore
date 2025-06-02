<?php 
session_start(); 
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Add database connection here
require_once '../backend/conn.php';

// // Fetch transactions for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$transactions = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// // Rest of the HTML code remains the same...
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction History - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

    <?php include 'partials/header.php'; ?>

    <main class="container mx-auto px-4 py-8 h-[calc(82vh)]">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Transaction History</h2>
            
            <?php if (empty($transactions)): ?>
                <p class="text-gray-600">No transactions found.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left">Transaction ID</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <!-- <th class="px-6 py-3 text-left">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">#<?php echo htmlspecialchars($transaction['id']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($transaction['created_at']))); ?></td>
                                <td class="px-6 py-4">Rp <?php echo number_format($transaction['total'], 0, ',', '.'); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-sm 
                                        <?php echo $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($transaction['status'])); ?>
                                    </span>
                                </td>
                                <!-- <td class="px-6 py-4">
                                    <a href="transaction_detail.php?id=<?php echo $transaction['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-800">View Details</a>
                                </td> -->
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>

</body>
</html>
