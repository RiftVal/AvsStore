import sys
import os
import tensorflow as tf
import cv2
import numpy as np # Diperlukan untuk manipulasi array gambar

def detect_money(image_path):
    """
    Melakukan deteksi uang asli/palsu menggunakan model TensorFlow.

    Args:
        image_path (str): Path lengkap ke file gambar uang yang akan dideteksi.

    Returns:
        str: "Asli" jika uang terdeteksi asli, "Palsu" jika terdeteksi palsu,
             atau pesan error jika terjadi masalah.
    """
    try:
        # --- Bagian 1: Memuat Model TensorFlow ---
        # GANTI PATH INI DENGAN LOKASI MODEL ANDA YANG SEBENARNYA!
        model_path = 'PATH_TO_YOUR_MONEY_DETECTION_MODEL.h5'
        # Contoh: model_path = '/path/to/your/models/money_detector.h5'
        # Atau jika itu SavedModel directory: model_path = '/path/to/your/models/saved_model_dir'

        if not os.path.exists(model_path):
            return f"Error: Model file not found at {model_path}. Please update the model_path."

        model = tf.keras.models.load_model(model_path)
        # Pastikan model dimuat dengan benar
        if model is None:
            return "Error: Failed to load the TensorFlow model."

        # --- Bagian 2: Lakukan Preprocessing Gambar ---
        # Baca gambar menggunakan OpenCV
        img = cv2.imread(image_path)

        if img is None:
            return f"Error: Could not read image from {image_path}. Check file integrity or path."

        # Sesuaikan TARGET_WIDTH dan TARGET_HEIGHT dengan input size model Anda
        # Contoh umum: (224, 224), (150, 150), dll.
        TARGET_WIDTH = 224
        TARGET_HEIGHT = 224

        # Ubah ukuran gambar agar sesuai dengan input model
        img_resized = cv2.resize(img, (TARGET_WIDTH, TARGET_HEIGHT))

        # Normalisasi piksel ke rentang [0, 1] jika model Anda dilatih dengan normalisasi
        # Ini sangat umum untuk model neural network
        img_normalized = img_resized / 255.0

        # Tambahkan dimensi batch (model neural network biasanya mengharapkan input dalam bentuk batch)
        # Contoh: dari (H, W, C) menjadi (1, H, W, C)
        img_batch = np.expand_dims(img_normalized, axis=0)

        # --- Bagian 3: Lakukan Prediksi Menggunakan Model ---
        predictions = model.predict(img_batch)

        # --- Bagian 4: Interpretasi Hasil Prediksi ---
        # Logika interpretasi tergantung pada bagaimana model Anda dilatih:
        # - Jika model outputnya 0 atau 1 (binary classification):
        #   Misal, output > 0.5 berarti "Asli", selain itu "Palsu"
        # - Jika model outputnya berupa array probabilitas (misal [prob_palsu, prob_asli]):
        #   Gunakan np.argmax untuk mendapatkan kelas dengan probabilitas tertinggi

        # Contoh interpretasi untuk klasifikasi biner (output tunggal, probabilitas 0-1)
        # Misal, model Anda menghasilkan nilai tunggal: probabilitas Asli
        # Jika Anda yakin probabilitas 0.5 adalah ambang batas yang baik:
        threshold = 0.5 # Ambang batas keputusan

        if predictions[0][0] > threshold: # Sesuaikan indeks [0][0] jika model outputnya berbeda
            result = "Asli"
        else:
            result = "Palsu"

        # --- Atau, jika model Anda mengeluarkan probabilitas untuk beberapa kelas ---
        # Contoh: predictions = [[0.2, 0.8]] (0.2 untuk 'Palsu', 0.8 untuk 'Asli')
        # classes = ["Palsu", "Asli"] # Sesuaikan urutan kelas
        # predicted_class_index = np.argmax(predictions[0])
        # result = classes[predicted_class_index]

        return result

    except Exception as e:
        # Tangani error yang mungkin terjadi selama proses deteksi
        return f"Error during detection: {e}"

if __name__ == "__main__":
    if len(sys.argv) < 2:
        # Mengarahkan pesan penggunaan ke stderr agar tidak mengganggu output deteksi
        print("Usage: python python_detection_script.py <image_path>", file=sys.stderr)
        sys.exit(1)

    image_path = sys.argv[1]

    if not os.path.exists(image_path):
        print(f"Error: Image not found at {image_path}", file=sys.stderr)
        sys.exit(1)

    result = detect_money(image_path)
    print(result) # Cetak hasil deteksi ke stdout