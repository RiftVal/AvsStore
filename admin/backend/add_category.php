<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            header("Location: ../categories.php?success=1");
            exit;
        } else {
            echo "Gagal menambahkan kategori.";
        }
    } else {
        echo "Nama kategori tidak boleh kosong.";
    }
} else {
    header("Location: ../categories.php");
    exit;
}
?>
