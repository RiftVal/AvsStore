<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}

$hasilDeteksi = '';
$uploadedImage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['money_image'])) {
    $image = $_FILES['money_image'];

    if ($image['error'] === UPLOAD_ERR_OK) {
        $tmpName = $image['tmp_name'];
        $fileName = uniqid() . '_' . basename($image['name']);
        $targetDir = "../uploads/";
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $uploadedImage = $targetPath;
            $hasilDeteksi = rand(0, 1) === 1 ? "Asli" : "Palsu";
        } else {
            $hasilDeteksi = "Gagal menyimpan gambar.";
        }
    } else {
        $hasilDeteksi = "Terjadi kesalahan saat upload gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Deteksi Uang Asli/Palsu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen font-sans">
<main class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen">
        <?php include 'partials/sidebar.php'; ?>
    </aside>

    <!-- Main Content -->
    <section class="flex-1 p-8">
        <?php include 'partials/header.php'; ?>

        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Deteksi Uang Asli / Palsu</h1>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="money_image" class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Uang</label>
                    <input 
                        type="file" 
                        name="money_image" 
                        id="money_image" 
                        accept="image/*" 
                        required 
                        class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition"
                >
                    Deteksi Sekarang
                </button>
            </form>

            <?php if ($hasilDeteksi): ?>
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Hasil Deteksi:</h2>

                    <div class="p-4 rounded-lg text-center font-bold text-lg 
                        <?= $hasilDeteksi === 'Asli' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        Uang dinyatakan: <span class="underline"><?= $hasilDeteksi ?></span>
                    </div>

                    <?php if ($uploadedImage): ?>
                        <div class="mt-6">
                            <p class="mb-2 text-gray-600">Gambar yang diunggah:</p>
                            <img 
                                src="<?= $uploadedImage ?>" 
                                alt="Gambar uang" 
                                class="w-full max-w-sm rounded-lg shadow-lg mx-auto border"
                            >
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>
