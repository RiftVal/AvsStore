<?php 
session_start();
require_once '../backend/conn.php';
require_once '../utils/crypto.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = encryptData($_POST['address']);
    $phone = encryptData($_POST['phone']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, address = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $address, $phone, $user_id);
    $stmt->execute();

    header('Location: profile.php');
    exit();
}

// Get user data
$stmt = $conn->prepare("SELECT name, email, address, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Decrypt personal data
$decryptedAddress = decryptData($user['address']);
$decryptedPhone = decryptData($user['phone']);

// Form bank
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            $bank_name = encryptData($_POST['bank_name']);
            $card_number = encryptData($_POST['card_number']);
            $cvv = encryptData($_POST['cvv']);
            $member_name = encryptData($_POST['member_name']);

            $stmt = $conn->prepare("INSERT INTO bank (user_id, bank_name, card_number, cvv, member_name) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $bank_name, $card_number, $cvv, $member_name);
            $stmt->execute();
            break;

        case 'delete':
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM bank WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $user_id);
            $stmt->execute();
            break;
    }

    header('Location: profile.php');
    exit();
}

// Ambil data bank user
$stmt = $conn->prepare("SELECT * FROM bank WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bankResult = $stmt->get_result();
$bank = [];
while ($row = $bankResult->fetch_assoc()) {
    $row['bank_name'] = decryptData($row['bank_name']);
    $row['card_number'] = decryptData($row['card_number']);
    $row['cvv'] = decryptData($row['cvv']);
    $row['member_name'] = decryptData($row['member_name']);
    $bank[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - Secure E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50 text-gray-800">
<?php include 'partials/header.php'; ?>

<main class="max-w-4xl mx-auto mt-6 px-4 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-bold mb-6">User Profile</h2>

        <!-- Informasi Pribadi -->
        <!-- Combined Display and Edit Form -->
        <form action="profile.php" method="POST" class="mb-8">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-600 mb-2">Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" 
                        class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-600 mb-2">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                        class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-600 mb-2">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($decryptedPhone) ?>" 
                        class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-600 mb-2">Address</label>
                    <textarea name="address" class="w-full p-2 border rounded"><?= htmlspecialchars($decryptedAddress) ?></textarea>
                </div>
            </div>
            <button type="submit" name="update_profile" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update Profile
            </button>
        </form>
    </div>

    <!-- Bank -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Bank Accounts</h2>

        <form action="profile.php" method="POST" class="mb-8">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Bank Name</label>
                    <input type="text" name="bank_name" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Card Number</label>
                    <input type="text" name="card_number" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">CVV</label>
                    <input type="text" name="cvv" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block mb-2">Member Name</label>
                    <input type="text" name="member_name" required class="w-full p-2 border rounded">
                </div>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Bank Account
            </button>
        </form>

        <!-- Tampilkan akun bank -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Your Bank Accounts</h3>
            <?php foreach ($bank as $account): ?>
                <div class="border p-4 rounded mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium"><?= htmlspecialchars($account['bank_name']) ?></p>
                            <p><?= htmlspecialchars($account['card_number']) ?></p>
                            <p><?= htmlspecialchars($account['member_name']) ?></p>
                        </div>
                        <form action="profile.php" method="POST" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $account['id'] ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
</body>
</html>

