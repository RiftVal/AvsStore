# admin/backend/uploads/python_detection_script.py
import sys
import os
import json
from ultralytics import YOLO

def detect_money(image_path):
    # --- Path ini akan otomatis mencari model 'best.pt' ---
    model_path = os.path.join(os.path.dirname(__file__), '..', 'ml_model', 'best.pt')
    # ----------------------------------------------------

    try:
        # Muat model YOLOv8
        model = YOLO(model_path)
    except Exception as e:
        return json.dumps({"status": "error", "message": f"Failed to load model: {e}", "detections": []})

    # Lakukan prediksi
    try:
        results = model(image_path, conf=0.6) # conf adalah confidence threshold, bisa disesuaikan
    except Exception as e:
        return json.dumps({"status": "error", "message": f"Prediction failed: {e}", "detections": []})

    detections = []
    # Proses hasil deteksi
    for r in results:
        for box in r.boxes:
            class_id = int(box.cls[0])
            class_name = model.names[class_id]
            confidence = float(box.conf[0])
            
            # Logika sederhana untuk menentukan keaslian dari nama kelas
            if "palsu" in class_name.lower():
                authenticity = "Palsu"
                nominal = class_name.replace("_palsu", "").upper()
            else:
                authenticity = "Asli"
                nominal = class_name.upper()

            detections.append({
                "nominal": nominal,
                "authenticity": authenticity,
                "confidence": confidence
            })

    # Siapkan hasil akhir dalam format JSON
    if detections:
        final_result = {
            "status": "success",
            "message": f"{len(detections)} object(s) detected.",
            "detections": detections
        }
    else:
        final_result = {
            "status": "success",
            "message": "No money detected.",
            "detections": []
        }
        
    return json.dumps(final_result, indent=4)

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"status": "error", "message": "No image path provided."}))
        sys.exit(1)
        
    image_path_arg = sys.argv[1]
    if not os.path.exists(image_path_arg):
        print(json.dumps({"status": "error", "message": f"Image not found at {image_path_arg}"}))
        sys.exit(1)

    # Instal ultralytics jika belum ada
    try:
        import ultralytics
    except ImportError:
        print(json.dumps({"status": "error", "message": "Ultralytics library not found. Please run 'pip install ultralytics'."}))
        sys.exit(1)
        
    result_json = detect_money(image_path_arg)
    print(result_json)