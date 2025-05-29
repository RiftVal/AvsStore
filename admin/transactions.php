<?php 
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}

require_once '../backend/conn.php';
require_once '../utils/crypto.php'; // fungsi decryptData()

// Gabungkan tabel users dan banks untuk ambil nama user & nama bank
$sql = "SELECT t.*, u.name AS nama_user, b.bank_name 
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        JOIN bank b ON t.bank_id = b.id
        ORDER BY t.date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions Management - Simple E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 h-screen">
<main class="flex mx-auto h-[calc(88vh)]">
    <div class="w-64 min-h-screen bg-gray-800">
        <?php include 'partials/sidebar.php'; ?>
    </div>
    <div class="flex-1">
        <?php include 'partials/header.php'; ?>
        <div class="flex-1 bg-white p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Transactions Management</h1>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4"><?php echo $row['id']; ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['nama_user']); ?></td>
                                    <td class="px-6 py-4"><?php echo date('d M Y H:i', strtotime($row['date'])); ?></td>
                                    <td class="px-6 py-4">
                                        <?php 
                                            $bankName = decryptData($row['bank_name']); 
                                            echo htmlspecialchars($bankName); 
                                        ?>
                                    </td>
                                    <td class="px-6 py-4">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                                    <td class="px-6 py-4">
                                        <select class="status-dropdown px-2 py-1 text-sm rounded border border-gray-300"
                                            data-id="<?php echo $row['id']; ?>">
                                            <?php 
                                            $statuses = ['pending', 'processed', 'completed', 'cancelled'];
                                            foreach ($statuses as $status) {
                                                $selected = $row['status'] == $status ? 'selected' : '';
                                                echo "<option value='$status' $selected>$status</option>";
                                            }
                                            ?>
                                        </select>
                                        <span id="status-msg-<?php echo $row['id']; ?>" class="ml-2 text-sm text-green-600 hidden">✔️</span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">No transactions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>

<script>
$(document).ready(function() {
    function updateDropdownColor(dropdown) {
        const status = $(dropdown).val();
        $(dropdown).removeClass("bg-yellow-100 bg-blue-100 bg-green-100 bg-red-100 text-yellow-800 text-blue-800 text-green-800 text-red-800");

        switch(status) {
            case "pending":
                $(dropdown).addClass("bg-yellow-100 text-yellow-800");
                break;
            case "processed":
                $(dropdown).addClass("bg-blue-100 text-blue-800");
                break;
            case "completed":
                $(dropdown).addClass("bg-green-100 text-green-800");
                break;
            case "cancelled":
                $(dropdown).addClass("bg-red-100 text-red-800");
                break;
        }
    }

    $(".status-dropdown").each(function() {
        updateDropdownColor(this);
    });

    $(".status-dropdown").change(function() {
        const status = $(this).val();
        const id = $(this).data("id");

        updateDropdownColor(this);

        $.ajax({
            url: "backend/update_status.php",
            type: "POST",
            data: {
                transaction_id: id,
                status: status
            },
            success: function(response) {
                const msg = $("#status-msg-" + id);
                msg.removeClass("hidden");
                setTimeout(() => msg.addClass("hidden"), 1500);
            },
            error: function(xhr) {
                alert("Failed to update status: " + xhr.responseText);
            }
        });
    });
});
</script>
</body>
</html>
