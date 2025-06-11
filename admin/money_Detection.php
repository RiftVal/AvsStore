<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 4) {
    header("Location: ../frontend/login.php");
    exit();
}

// Inisialisasi variabel untuk tampilan hasil upload gambar statis
$hasilDeteksiUpload = '';
$uploadedImage = '';

// Logika untuk upload gambar statis (tetap dipertahankan)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['money_image'])) {
    $image = $_FILES['money_image'];

    if ($image['error'] === UPLOAD_ERR_OK) {
        $tmpName = $image['tmp_name'];
        $fileName = uniqid('static_') . '_' . basename($image['name']);
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $uploadedImage = $targetPath;
            // Ini adalah hasil deteksi dummy. Di produksi, Anda akan memanggil model ML di sini.
            $hasilDeteksiUpload = rand(0, 1) === 1 ? "Asli" : "Palsu";
        } else {
            $hasilDeteksiUpload = "Gagal menyimpan gambar.";
        }
    } else {
        $hasilDeteksiUpload = "Terjadi kesalahan saat upload gambar.";
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
    <style>
        /* Gaya tambahan untuk video, memastikan rasio aspek dan fill */
        video {
            width: 100%;
            height: auto;
            object-fit: cover; /* Memastikan video mengisi kotak */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
<main class="flex">
    <aside class="w-64 bg-gray-800 text-white min-h-screen">
        <?php include 'partials/sidebar.php'; ?>
    </aside>

    <section class="flex-1 p-8">
        <?php include 'partials/header.php'; ?>

        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow mt-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Deteksi Uang Asli / Palsu</h1>

            <h2 class="text-2xl font-semibold text-gray-800 mb-4 mt-8">Upload Gambar untuk Deteksi</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="money_image" class="block text-sm font-medium text-gray-700 mb-1">Pilih File Gambar Uang</label>
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
                    Deteksi Dari Gambar
                </button>
            </form>

            <?php if ($hasilDeteksiUpload): ?>
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Hasil Deteksi (Upload):</h2>
                    <div class="p-4 rounded-lg text-center font-bold text-lg
                        <?= $hasilDeteksiUpload === 'Asli' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        Uang dinyatakan: <span class="underline"><?= $hasilDeteksiUpload ?></span>
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

            <hr class="my-10 border-gray-300">

            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Deteksi Real-time dengan Kamera</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tampilan Kamera</label>
                    <video id="videoStream" autoplay playsinline class="w-full max-w-sm mx-auto rounded-lg shadow-lg border bg-gray-200"></video>
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>
                <button id="captureButton" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition">
                    Ambil Gambar & Deteksi Real-time
                </button>
                <div id="realtimeDetectionResult" class="mt-4 p-4 rounded-lg text-center font-bold text-lg hidden">
                    </div>
                <img id="capturedImagePreview" src="" alt="Captured Image" class="w-full max-w-sm mx-auto rounded-lg shadow-lg border mt-4 hidden">
            </div>

        </div>
    </section>
</main>

<script>
    const video = document.getElementById('videoStream');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('captureButton');
    const realtimeDetectionResult = document.getElementById('realtimeDetectionResult');
    const capturedImagePreview = document.getElementById('capturedImagePreview');
    let stream; // Untuk menyimpan stream kamera

    // Minta akses kamera
    async function setupCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
                console.log("Kamera berhasil diakses dan diputar.");
            };
        } catch (err) {
            console.error("Error accessing camera: ", err);
            alert("Tidak dapat mengakses kamera. Pastikan browser Anda mengizinkan akses kamera.");
            // Sembunyikan elemen kamera jika gagal diakses
            video.style.display = 'none';
            captureButton.style.display = 'none';
        }
    }

    // Ambil gambar dari video stream dan kirim ke server
    captureButton.addEventListener('click', () => {
        if (!stream) {
            alert("Kamera belum siap atau tidak dapat diakses.");
            return;
        }

        // Atur ukuran canvas sesuai video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Konversi gambar ke format data URL (base64)
        const imageDataURL = canvas.toDataURL('image/jpeg', 0.9); // 0.9 adalah kualitas kompresi JPEG

        // Tampilkan preview gambar yang diambil
        capturedImagePreview.src = imageDataURL;
        capturedImagePreview.classList.remove('hidden');

        // Kirim data gambar ke server
        sendImageToServer(imageDataURL);
    });

    // Di dalam file admin/money_Detection.php

async function sendImageToServer(imageDataURL) {
    const realtimeDetectionResult = document.getElementById('realtimeDetectionResult');
    realtimeDetectionResult.classList.remove('hidden');
    realtimeDetectionResult.innerHTML = 'Mendeteksi...';
    realtimeDetectionResult.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
    realtimeDetectionResult.classList.add('bg-gray-200', 'text-gray-700');

    try {
        const response = await fetch('partials/detection_money_api.php', { // Pastikan path API benar
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ image: imageDataURL }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Bersihkan kelas warna sebelumnya
        realtimeDetectionResult.classList.remove('bg-gray-200', 'text-gray-700', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');

        if (data.status === 'success' && data.detections.length > 0) {
            let htmlResult = '<ul>';
            let isAuthentic = true;
            
            data.detections.forEach(det => {
                let authenticityClass = 'text-green-700';
                if (det.authenticity === 'Palsu') {
                    authenticityClass = 'text-red-700 font-bold';
                    isAuthentic = false;
                }
                
                let confidence = (det.confidence * 100).toFixed(2);
                htmlResult += `<li class="mb-1">Nominal: <strong>${det.nominal}</strong>, Keaslian: <span class="${authenticityClass}">${det.authenticity}</span> (Akurasi: ${confidence}%)</li>`;
            });

            htmlResult += '</ul>';
            realtimeDetectionResult.innerHTML = htmlResult;

            // Atur warna background berdasarkan hasil keseluruhan
            if (isAuthentic) {
                realtimeDetectionResult.classList.add('bg-green-100', 'text-green-800');
            } else {
                realtimeDetectionResult.classList.add('bg-red-100', 'text-red-800');
            }
            
        } else if (data.status === 'success') {
            realtimeDetectionResult.innerHTML = 'Tidak ada uang yang terdeteksi di gambar.';
            realtimeDetectionResult.classList.add('bg-yellow-100', 'text-yellow-800');
        } else {
            // Menangani error dari server atau skrip Python
            realtimeDetectionResult.innerHTML = `Error: ${data.message}`;
            realtimeDetectionResult.classList.add('bg-red-100', 'text-red-800');
        }
    } catch (error) {
        console.error('Error:', error);
        realtimeDetectionResult.innerHTML = `Terjadi kesalahan saat berkomunikasi dengan server.`;
        realtimeDetectionResult.classList.add('bg-red-100', 'text-red-800');
    }
}

    // Panggil setupCamera saat halaman dimuat
    window.addEventListener('load', setupCamera);
</script>
</body>
</html>