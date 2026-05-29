import sys
import json
import os
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image


# ==============================
# CONFIGURATION
# ==============================

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

MODEL_PATH = os.path.join(BASE_DIR, "skin_cancer_model.h5")

IMG_SIZE = (224, 224)

CLASS_NAMES = [
    "benign",
    "malignant"
]


# ==============================
# LOAD MODEL
# ==============================

model = load_model(MODEL_PATH)


# ==============================
# PREDICTION FUNCTION
# ==============================

def predict_image(image_path):
    img = image.load_img(image_path, target_size=IMG_SIZE)

    img_array = image.img_to_array(img)
    img_array = img_array / 255.0
    img_array = np.expand_dims(img_array, axis=0)

    prediction = model.predict(img_array, verbose=0)

    # Cas 1 : modèle binaire avec sortie sigmoid : [[0.87]]
    if prediction.shape[-1] == 1:
        prob_malignant = float(prediction[0][0])

        if prob_malignant >= 0.5:
            predicted_class = 1
            confidence = prob_malignant
        else:
            predicted_class = 0
            confidence = 1.0 - prob_malignant

    # Cas 2 : modèle softmax avec deux sorties : [[0.15, 0.85]]
    else:
        predicted_class = int(np.argmax(prediction[0]))
        confidence = float(np.max(prediction[0]))

    result = {
        "maladie": CLASS_NAMES[predicted_class],
        "probabilite": round(confidence * 100, 2)
    }

    return result


# ==============================
# MAIN
# ==============================

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({
            "error": "Aucune image fournie."
        }))
        sys.exit(1)

    image_path = sys.argv[1]

    if not os.path.exists(image_path):
        print(json.dumps({
            "error": "Image introuvable.",
            "path": image_path
        }))
        sys.exit(1)

    try:
        result = predict_image(image_path)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "error": str(e)
        }))