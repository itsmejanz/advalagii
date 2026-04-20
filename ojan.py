import streamlit as st
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder

# =========================
# LOAD DATA
# =========================
file_path = "Filedata Data Jumlah Penderita DBD Hasil Penyelidikan Epidemologi Di Provinsi DKI Jakarta.csv"
df = pd.read_csv(file_path)

df.columns = df.columns.str.lower().str.replace(" ", "_")
df = df.fillna(0)

# =========================
# BUAT TARGET ZONA
# =========================
df['zona'] = df['penderita_dbd'].apply(
    lambda x: "MERAH" if x > 5 else "HIJAU"
)

# =========================
# ENCODING
# =========================
le_kota = LabelEncoder()
le_kecamatan = LabelEncoder()

df['kota_enc'] = le_kota.fit_transform(df['kota_administrasi'])
df['kecamatan_enc'] = le_kecamatan.fit_transform(df['kecamatan'])

# =========================
# TRAIN MODEL
# =========================
X = df[['bulan', 'kota_enc', 'kecamatan_enc']]
y = df['zona']

model = RandomForestClassifier()
model.fit(X, y)

# =========================
# UI STREAMLIT
# =========================
st.title("🦟 Deteksi Zona DBD Jakarta")

st.write("Cek apakah wilayah kamu termasuk zona merah atau hijau")

# input user
kota = st.selectbox("Pilih Kota", df['kota_administrasi'].unique())
kecamatan = st.selectbox("Pilih Kecamatan", df['kecamatan'].unique())
bulan = st.slider("Bulan", 1, 12, 1)

# tombol cek
if st.button("Cek Zona"):
    try:
        kota_enc = le_kota.transform([kota])[0]
        kec_enc = le_kecamatan.transform([kecamatan])[0]

        pred = model.predict([[bulan, kota_enc, kec_enc]])[0]

        if pred == "MERAH":
            st.error(f"🚨 {kecamatan} termasuk ZONA MERAH")
        else:
            st.success(f"✅ {kecamatan} termasuk ZONA HIJAU")

    except:
        st.warning("Data tidak ditemukan!")