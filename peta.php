<?php
session_start();
include "ojan.php";
$id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id='$id'")->fetch_assoc();
if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dengue Smart Map</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

<style>
body { margin:0; }
#map { height:100vh; }

.ui {
    position:absolute;
    top:10px;
    left:10px;
    z-index:1000;
    background:white;
    padding:10px;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="ui">
    <b>DENGUE AI MAP</b><br>
    <button onclick="locate()">📍 Lokasi Saya</button>
</div>

<div id="userInfo" style="
position:absolute;
bottom:20px;
left:10px;
z-index:1000;
background:white;
padding:10px;
border-radius:10px;
font-size:12px;
max-width:200px;
">
📍 Mendeteksi lokasi...
</div>

<div id="map"></div>

<script>

let map = L.map('map').setView([-6.2,106.8],11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
.addTo(map);

let choroplethLayer, heatLayer, userMarker;
let kecData = {}; // ✅ GLOBAL FIX


// ==========================
// WARNA ZONA
// ==========================
function getColor(val){
    return val > 200 ? "red" :
           val > 100 ? "orange" :
                       "green";
}

// ==========================
// AMBIL NAMA KECAMATAN
// ==========================
function getKecamatanName(props){
    return props.kecamatan || props.name || props.NAMOBJ || "";
}

// ==========================
// LOAD DATA
// ==========================
async function load(){

    if(choroplethLayer) map.removeLayer(choroplethLayer);
    if(heatLayer) map.removeLayer(heatLayer);

    let res = await fetch("https://ws.jakarta.go.id/gateway/DataPortalSatuDataJakarta/1.0/satudata?kategori=dataset&tipe=detail&url=data-surveilans-penyakit");
    let json = await res.json();

    let data = json.data;

    let filtered = data.filter(d =>
        d.jenis_penyakit === "Demam Berdarah" &&
        d.periode_data === "2025"
    );

    // ==========================
    // AGREGASI DATA
    // ==========================
    kecData = {};
    let lastZona = "";
let lastNotifTime = 0;

    filtered.forEach(d=>{
        let kec = (d.kecamatan || "").toUpperCase();

        if(!kecData[kec]) kecData[kec] = 0;
        kecData[kec] += parseInt(d.jumlah || 0);
    });

    // ==========================
    // LOAD GEOJSON
    // ==========================
    let geo = await fetch("https://raw.githubusercontent.com/SakifAbdillah/jakartaKecamatanGeoJSON/refs/heads/master/kecamatan.geojson");
    let geojson = await geo.json();

    // ==========================
    // CHOROPLETH
    // ==========================
    choroplethLayer = L.geoJSON(geojson, {

        style: feature => {
            let rawName = getKecamatanName(feature.properties);
            let kec = rawName.toUpperCase();
           let val = kecData[kec] || 0;

let zona = getZonaStatus(val);

let now = Date.now();

if(zona !== lastZona || now - lastNotifTime > 10000){

    showZonaNotification(zona, rawName, val);

    lastZona = zona;
    lastNotifTime = now;
}
            return {
                fillColor: getColor(val),
                weight:1,
                color:"#fff",
                fillOpacity:0.7
            };
        },

        onEachFeature: (feature, layer)=>{
            let rawName = getKecamatanName(feature.properties);
            let kec = rawName.toUpperCase();
            let val = kecData[kec] || 0;

            layer.bindPopup(`
                <b>${rawName}</b><br>
                Kasus DBD: ${val}
            `);
        }

    }).addTo(map);

    // ==========================
    // HEATMAP
    // ==========================
    let heat = [];

    choroplethLayer.eachLayer(layer=>{
        let center = layer.getBounds().getCenter();

        let rawName = getKecamatanName(layer.feature.properties);
        let kec = rawName.toUpperCase();
        let val = kecData[kec] || 0;

        if(val > 0){
            heat.push([center.lat, center.lng, val]);
        }
    });

    heatLayer = L.heatLayer(heat, {
        radius: 25,
        blur: 15,
        maxZoom: 12
    }).addTo(map);
}

// ==========================
// GEOLOCATION + SAVE + ZONA
// ==========================
function locate(auto = false){

    navigator.geolocation.getCurrentPosition(pos=>{

        let lat = pos.coords.latitude;
        let lng = pos.coords.longitude;

        // ==========================
        // SIMPAN KE DATABASE
        // ==========================
       fetch("save_location.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `lat=${lat}&lng=${lng}`
})
.then(res => res.text())
.then(res => {
    console.log("SAVE RESPONSE:", res);
});
        // ==========================
        // MARKER USER
        // ==========================
       if(userMarker) map.removeLayer(userMarker);

let myAvatar = "<?= isset($user['avatar']) ? $user['avatar'] : '' ?>";

userMarker = L.marker([lat,lng], {
    icon: createAvatarIcon(myAvatar)
})
.addTo(map)
.bindPopup("📍 Kamu di sini")
.openPopup();
        map.setView([lat,lng],14);

        // ==========================
        // CEK ZONA
        // ==========================
        let found = false;

        choroplethLayer.eachLayer(layer=>{
            if(layer.getBounds().contains([lat,lng])){

                let rawName = getKecamatanName(layer.feature.properties);
                let kec = rawName.toUpperCase();
                let val = kecData[kec] || 0;

                document.getElementById("userInfo").innerHTML = `
                    <b>📍 Lokasi Kamu</b><br>
                    ${rawName}<br>
                    Kasus DBD: <b>${val}</b>
                `;

                found = true;
            }
        });

        if(!found){
            document.getElementById("userInfo").innerHTML =
                "📍 Lokasi tidak dalam data wilayah";
        }

    }, ()=>{
        if(!auto){
            alert("GPS gagal");
        }
    });
}

function createAvatarIcon(url){

    let avatar = url && url !== "" 
        ? url 
        : "https://via.placeholder.com/50";

    return L.divIcon({
        html: `
            <div style="
                width:50px;
                height:50px;
                border-radius:50%;
                overflow:hidden;
                border:3px solid white;
                box-shadow:0 2px 6px rgba(0,0,0,0.3);
            ">
                <img src="${avatar}" style="width:100%; height:100%; object-fit:cover;">
            </div>
        `,
        className: "",
        iconSize: [50,50],
        iconAnchor: [25,50]
    });
}

function getZonaStatus(val){
    if(val > 200) return "bahaya";
    if(val > 100) return "waspada";
    return "aman";
}

function showZonaNotification(zona, kec, val){

    let message = "";
    let bg = "";

    if(zona === "bahaya"){
        message = `🚨 BAHAYA!\n${kec}\nKasus DBD: ${val}`;
        bg = "#ff4d4d";
    }else if(zona === "waspada"){
        message = `⚠️ WASPADA\n${kec}\nKasus DBD: ${val}`;
        bg = "#ffa500";
    }else{
        message = `✅ Aman\n${kec}`;
        bg = "#4CAF50";
    }

    let notif = document.createElement("div");
    notif.innerHTML = message;

    notif.style.position = "fixed";
    notif.style.top = "20px";
    notif.style.left = "50%";
    notif.style.transform = "translateX(-50%)";
    notif.style.background = bg;
    notif.style.color = "white";
    notif.style.padding = "15px";
    notif.style.borderRadius = "15px";
    notif.style.zIndex = "9999";
    notif.style.textAlign = "center";
    notif.style.boxShadow = "0 4px 10px rgba(0,0,0,0.3)";

    document.body.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 4000);
}

let storyMarkers = [];

async function loadStories(){

    let res = await fetch("get_stories.php");
    let data = await res.json();

    // hapus lama
    storyMarkers.forEach(m => map.removeLayer(m));
    storyMarkers = [];

    data.forEach(s => {

        if(!s.lat || !s.lng) return;

        let marker = L.marker([s.lat, s.lng])
        .addTo(map)
        .bindPopup(`
            <b>${s.nama}</b><br>
            <img src="${s.image}" width="150"><br>
            📍 Story Lokasi
        `);

        storyMarkers.push(marker);
    });
}

// auto refresh
setInterval(loadStories, 5000);
loadStories();

// ==========================
// INIT
// ==========================
load();

setTimeout(() => {
    locate(true);
    
}, 1500);

</script>

</body>
</html>