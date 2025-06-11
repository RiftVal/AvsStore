<?php
// Aktifkan error reporting untuk debugging. HARAP DINONAKTIFKAN DI LINGKUNGAN PRODUKSI!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Gunakan E_ALL & ~E_DEPRECATED jika Anda ingin mengabaikan peringatan deprecated

// Hanya kirim header Content-Type sekali dan pastikan tidak ada output sebelum ini
header('Content-Type: application/json');

// Pastikan hanya menerima permintaan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Periksa apakah data gambar diterima
if (!isset($data['image'])) {
    echo json_encode(['status' => 'error', 'message' => 'No image data received.']);
    exit();
}

$imageData = $data['image'];
// Hapus bagian 'data:image/jpeg;base64,' dari data URL
$imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
$imageData = base64_decode($imageData);

$targetDir = __DIR__ . "/../uploads/"; // Gunakan __DIR__ untuk path yang lebih robust
// Pastikan folder uploads ada dan writable. Beri izin yang sesuai, contoh: 0775 atau 0777 (untuk testing)
if (!is_dir($targetDir)) {
    // Attempt to create the directory
    if (!mkdir($targetDir, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create uploads directory. Check permissions.']);
        exit();
    }
}

$fileName = uniqid('camera_') . '.jpeg';
$targetPath = $targetDir . $fileName;

// Simpan gambar yang diunggah
if (file_put_contents($targetPath, $imageData)) {
    // Panggil script Python untuk deteksi
    // SESUAIKAN PATH INI DENGAN LOKASI SCRIPT PYTHON ANDA DI SERVER
    // Contoh: C:\laragon\www\AvsStore\admin\backend\python_detection_script.py
    $pythonScriptPath = __DIR__ . '/backend/python_detection_script.py'; // Sesuaikan jika lokasi python_detection_script.py tidak di dalam 'backend'

    // PASTIKAN PYTHON_EXECUTABLE_PATH SESUAI DENGAN INSTALLASI PYTHON ANDA (misal: /usr/bin/python3, C:\Python\Python39\python.exe)
    // Gunakan '2>&1' untuk menangkap stdout dan stderr dari Python
    $command = escapeshellcmd('python3 ' . $pythonScriptPath . ' ' . $targetPath . ' 2>&1');

    // Eksekusi perintah Python
    $output = shell_exec($command);

    // Perbaikan: Pastikan $output adalah string sebelum trim()
    // Ini mengatasi peringatan Deprecated: trim(): Passing null to parameter #1
    $detectionResult = trim((string)($output ?? '')); // Jika $output null, gunakan string kosong

    // Opsional: Hapus gambar setelah deteksi selesai untuk menghemat ruang
    // Pastikan Anda hanya melakukan ini jika Anda yakin deteksi sudah selesai
    // unlink($targetPath);

    // Validasi hasil dari script Python
    // Pastikan skrip Python hanya mengembalikan "Asli" atau "Palsu"
    if ($detectionResult === "Asli" || $detectionResult === "Palsu") {
        echo json_encode(['status' => 'success', 'result' => $detectionResult]);
    } else {
        // Jika script Python tidak mengembalikan "Asli" atau "Palsu",
        // sertakan output mentah dari Python untuk debugging
        echo json_encode(['status' => 'error', 'message' => 'Python script returned unexpected result or error: ' . $detectionResult]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save image from camera. Check directory permissions for ' . $targetDir]);
}
?>