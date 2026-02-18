// Hapus semua baris 'import' jika menggunakan CDN di HTML

// 1. Setup Mock Data
const clientPoints = [
    { name: "Main Office", coords: [106.8272, -6.1751] }, // Jakarta
    { name: "Branch A", coords: [107.6191, -6.9175] }    // Bandung
];

// Gunakan prefix 'ol.' untuk semua class
const features = clientPoints.map(p => {
    return new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat(p.coords)),
        name: p.name
    });
});

// 2. Initialize Map
const map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        }),
        new ol.layer.Vector({
            source: new ol.source.Vector({ features: features }),
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    anchor: [0.5, 1],
                    // Link icon yang valid
                    src: 'https://openlayers.org',
                    scale: 1
                })
            })
        })
    ],
    view: new ol.View({
        center: ol.proj.fromLonLat([106.8272, -6.1751]),
        zoom: 7
    })
});

// 3. Click Event
map.on('click', (evt) => {
    const feature = map.forEachFeatureAtPixel(evt.pixel, (f) => f);
    if (feature) {
        // Karena pakai Bootstrap, nanti ganti alert ini dengan modal/bottom sheet
        alert("Client View: " + feature.get('name'));
    }
});
