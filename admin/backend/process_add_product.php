<?php
require_once '../backend/conn.php';
// require_once '../utils/crypto.php'; 

function tambah_produk($conn) {
    // Ambil data dari form
    $name        = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $description = $_POST['description'];
    
    // Tangani upload gambar
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validasi file
        $valid_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $valid_types)) {
            die("Format gambar tidak didukung.");
        }

        // Pindahkan file yang diupload
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            die("Gagal mengunggah gambar.");
        }

        // Simpan nama file untuk database
        $image_name = basename($target_file);
    }

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siddss", $name, $category_id, $price, $stock, $description, $image_name);

    if ($stmt->execute()) {
        echo "Produk berhasil ditambahkan.";
        header("Location: ../products.php");
    } else {
        echo "Gagal menambahkan produk: " . $stmt->error;
    }

    $stmt->close();
}

tambah_produk($conn);
?>
