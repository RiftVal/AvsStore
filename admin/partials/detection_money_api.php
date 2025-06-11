// admin/partials/detection_money_api.php
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
$imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
$imageData = base64_decode($imageData);

// Pastikan folder uploads ada dan dapat ditulis
$targetDir = __DIR__ . "/../../uploads/"; 
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = 'camera_' . uniqid() . '.jpeg';
$targetPath = $targetDir . $fileName;

if (file_put_contents($targetPath, $imageData)) {
    // Panggil script Python
    // Pastikan path ke python dan skrip sudah benar
    $pythonScriptPath = __DIR__ . '/../backend/uploads/python_detection_script.py';
    $command = escapeshellcmd('python3 ' . $pythonScriptPath . ' ' . escapeshellarg($targetPath));

    // Eksekusi perintah dan tangkap outputnya
    $output = shell_exec($command);

    // Hapus gambar setelah deteksi untuk menghemat ruang
    // unlink($targetPath);

    // Output dari Python adalah JSON, jadi kita langsung teruskan saja
    // Tidak perlu decode dan encode ulang jika tidak ada modifikasi
    if ($output) {
        // Set header ke application/json karena outputnya memang JSON
        echo $output;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute Python script or script returned no output.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save image from camera.']);
}
?>