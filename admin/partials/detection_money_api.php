// detect_money_api.php
<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['image'])) {
    echo json_encode(['status' => 'error', 'message' => 'No image data received.']);
    exit();
}

$imageData = $data['image'];
$imageData = str_replace('data:image/jpeg;base64,', '', $imageData); // Hapus header Data URL
$imageData = base64_decode($imageData);

$targetDir = "../uploads/"; // Pastikan folder uploads ada dan writable
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = uniqid('camera_') . '.jpeg';
$targetPath = $targetDir . $fileName;

if (file_put_contents($targetPath, $imageData)) {
    // Panggil script Python untuk deteksi
    // SESUAIKAN PATH INI DENGAN LOKASI SCRIPT PYTHON ANDA DI SERVER
    $pythonScriptPath = __DIR__ . '/../backend/python_detection_script.py'; // Contoh path relatif
    // PASTIKAN PYTHON_EXECUTABLE_PATH SESUAI DENGAN INSTALLASI PYTHON ANDA
    $command = escapeshellcmd('python3 ' . $pythonScriptPath . ' ' . $targetPath);

    // Eksekusi perintah Python
    $output = shell_exec($command);
    $detectionResult = trim($output); // Ambil output dari script Python

    // Opsional: Hapus gambar setelah deteksi selesai untuk menghemat ruang
    // unlink($targetPath);

    // Validasi hasil dari script Python
    if ($detectionResult === "Asli" || $detectionResult === "Palsu") {
        echo json_encode(['status' => 'success', 'result' => $detectionResult]);
    } else {
        // Jika script Python tidak mengembalikan "Asli" atau "Palsu"
        echo json_encode(['status' => 'error', 'message' => 'Python script returned unexpected result: ' . $detectionResult]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save image from camera.']);
}
?>