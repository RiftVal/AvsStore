import sys
import os
# Tambahkan import library ML yang Anda gunakan, contoh:
import tensorflow as tf
import cv2

def detect_money(image_path):
    # TODO: Load model ML Anda di sini
    # model = tf.keras.models.load_model('path/to/your/money_detection_model.h5')

    # TODO: Lakukan preprocessing gambar yang sesuai dengan model Anda
    # img = cv2.imread(image_path)
    # img = cv2.resize(img, (TARGET_WIDTH, TARGET_HEIGHT)) # Sesuaikan dengan input model
    # img = img / 255.0 # Normalisasi jika model Anda memerlukan

    # TODO: Lakukan prediksi menggunakan model
    # prediction = model.predict(np.expand_dims(img, axis=0))

    # Untuk demo, kita akan memberikan hasil acak seperti di PHP Anda
    import random
    if random.random() > 0.5:
        return "Asli"
    else:
        return "Palsu"

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python python_detection_script.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]
    if not os.path.exists(image_path):
        print(f"Error: Image not found at {image_path}")
        sys.exit(1)

    result = detect_money(image_path)
    print(result) # Cetak hasil deteksi ke stdout