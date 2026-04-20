from flask import Flask, request, jsonify
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder

app = Flask(__name__)

# =========================
# LOAD DATA & TRAIN MODEL
# =========================
df = pd.read_csv("Filedata Data Jumlah Penderita DBD Hasil Penyelidikan Epidemologi Di Provinsi DKI Jakarta.csv")

df.columns = df.columns.str.lower().str.replace(" ", "_")
df = df.fillna(0)

df['zona'] = df['penderita_dbd'].apply(
    lambda x: "MERAH" if x > 5 else "HIJAU"
)

le_kota = LabelEncoder()
le_kecamatan = LabelEncoder()

df['kota_enc'] = le_kota.fit_transform(df['kota_administrasi'])
df['kecamatan_enc'] = le_kecamatan.fit_transform(df['kecamatan'])

X = df[['bulan', 'kota_enc', 'kecamatan_enc']]
y = df['zona']

model = RandomForestClassifier()
model.fit(X, y)

# =========================
# API ENDPOINT
# =========================
@app.route('/predict', methods=['POST'])
def predict():
    data = request.json

    try:
        kota = data['kota'].upper()
        kecamatan = data['kecamatan'].upper()
        bulan = int(data['bulan'])

        kota_enc = le_kota.transform([kota])[0]
        kec_enc = le_kecamatan.transform([kecamatan])[0]

        pred = model.predict([[bulan, kota_enc, kec_enc]])[0]

        return jsonify({
            "status": "success",
            "zona": pred
        })

    except Exception as e:
        return jsonify({
            "status": "error",
            "message": str(e)
        })

# =========================
# RUN SERVER
# =========================
if __name__ == '__main__':
    app.run(debug=True)