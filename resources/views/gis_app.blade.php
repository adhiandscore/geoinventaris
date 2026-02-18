<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prototyping GIS - Inventaris</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.3.1/ol.css">

    <style>
        #map { width: 100%; height: calc(100vh - 350px); border-radius: 8px; cursor: crosshair; }
        .table-container { height: 200px; overflow-y: auto; font-size: 0.85rem; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-light" x-data="gisApp()" x-init="initMap()">

<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2 d-flex align-items-center gap-2">
                    <span class="fw-bold small border-end pe-2">Tools:</span>
                    <button @click="setMode('select')" :class="mode === 'select' ? 'btn-primary' : 'btn-outline-primary'" class="btn btn-sm">🖐️ Select</button>
                    <button @click="startNewInventaris()" class="btn btn-success btn-sm">➕ Tambah Inventaris Baru</button>

                    <div class="ms-auto">
                        <button @click="clearAll()" class="btn btn-outline-danger btn-sm">🗑️ Reset All Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div id="map" class="bg-secondary position-relative">
                    <div class="position-absolute top-0 start-0 m-2 badge bg-dark opacity-75 z-3 p-2" x-text="`Mode: ${mode.toUpperCase()}`"></div>

                    <div x-show="mode === 'polygon'" x-cloak class="position-absolute top-0 start-50 translate-middle-x mt-2 z-3">
                        <div class="alert alert-warning py-1 px-3 shadow-sm small">
                            Klik peta untuk membuat sudut area, <strong>Double-Click</strong> untuk simpan.
                        </div>
                    </div>
                </div>

                <div class="card-footer p-0 border-top-0">
                    <div class="bg-dark text-white px-3 py-1 d-flex justify-content-between align-items-center">
                        <span class="small fw-bold">Tabel Inventaris (Local Storage)</span>
                        <span class="small" x-text="`${inventaris.length} items`"></span>
                    </div>
                    <div class="table-container bg-white">
                        <table class="table table-sm table-hover table-bordered mb-0">
                            <thead class="table-light sticky-top text-center">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Nama Inventaris</th>
                                    <th>Lon (Center)</th>
                                    <th>Lat (Center)</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in inventaris" :key="item.id">
                                    <tr class="align-middle text-center">
                                        <td x-text="index + 1"></td>
                                        <td class="text-start fw-bold" x-text="item.name"></td>
                                        <td x-text="item.lon.toFixed(5)"></td>
                                        <td x-text="item.lat.toFixed(5)"></td>
                                        <td>
                                            <button @click="zoomTo(item.lon, item.lat)" class="btn btn-info btn-sm py-0 text-white">Zoom</button>
                                            <button @click="removeItem(item.id)" class="btn btn-link text-danger btn-sm py-0 ms-1 text-decoration-none">Hapus</button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="inventaris.length === 0">
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data inventaris terdaftar.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="alert alert-info py-2 small shadow-sm">
                <strong>Workflow ArcGIS Style:</strong> <br>
                1. Klik "Tambah Inventaris Baru" <br>
                2. Isi Nama & Klik Lanjut <br>
                3. Gambar Area (Polygon) di peta <br>
                4. Selesai (Auto-Save ke Storage)
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInput" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header py-2 bg-success text-white">
                <h6 class="modal-title">Inventaris Baru</h6>
            </div>
            <div class="modal-body py-3">
                <label class="small fw-bold mb-1">Beri Nama Titik/Area:</label>
                <input type="text" x-model="tempName" class="form-control form-control-sm" placeholder="Contoh: Area Perkebunan A">
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" @click="confirmNameAndDraw()" class="btn btn-primary btn-sm">Lanjut Gambar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ol@v10.3.1/dist/ol.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
function gisApp() {
    return {
        map: null,
        draw: null,
        vectorSource: new ol.source.Vector(),
        mode: 'select',
        inventaris: [],
        tempName: '',
        bootstrapModal: null,

        initMap() {
            // Load LocalStorage
            const stored = localStorage.getItem('gis_data');
            if(stored) {
                this.inventaris = JSON.parse(stored);
            }

            this.bootstrapModal = new bootstrap.Modal(document.getElementById('modalInput'));

            const vectorLayer = new ol.layer.Vector({
                source: this.vectorSource,
                style: new ol.style.Style({
                    fill: new ol.style.Fill({ color: 'rgba(40, 167, 69, 0.3)' }),
                    stroke: new ol.style.Stroke({ color: '#198754', width: 3 }),
                    image: new ol.style.Circle({
                        radius: 7,
                        fill: new ol.style.Fill({ color: '#198754' })
                    })
                })
            });

            this.map = new ol.Map({
                target: 'map',
                layers: [
                    new ol.layer.Tile({ source: new ol.source.OSM() }),
                    vectorLayer
                ],
                view: new ol.View({
                    center: ol.proj.fromLonLat([106.8456, -6.2088]),
                    zoom: 12
                })
            });

            this.renderFeaturesFromData();
        },

        startNewInventaris() {
            this.tempName = '';
            this.bootstrapModal.show();
        },

        confirmNameAndDraw() {
            if(!this.tempName) return alert('Nama wajib diisi');
            this.bootstrapModal.hide();
            this.setMode('polygon');
        },

        setMode(newMode) {
            this.mode = newMode;
            this.map.removeInteraction(this.draw);

            if (newMode === 'polygon') {
                this.draw = new ol.interaction.Draw({
                    source: this.vectorSource,
                    type: 'Polygon'
                });

                this.draw.on('drawend', (event) => {
                    const geometry = event.feature.getGeometry();
                    const centerPoint = geometry.getInteriorPoint().getCoordinates();
                    const coords = ol.proj.toLonLat(centerPoint);

                    // Simpan geometry sebagai GeoJSON String
                    const geoJSON = new ol.format.GeoJSON().writeGeometry(geometry);

                    const newItem = {
                        id: Date.now(),
                        name: this.tempName,
                        lon: coords[0],
                        lat: coords[1],
                        geoData: geoJSON
                    };

                    this.inventaris.push(newItem);
                    this.saveData();

                    // Selesai, kembali ke mode select
                    setTimeout(() => this.setMode('select'), 200);
                });

                this.map.addInteraction(this.draw);
            }
        },

        saveData() {
            localStorage.setItem('gis_data', JSON.stringify(this.inventaris));
        },

        renderFeaturesFromData() {
            const format = new ol.format.GeoJSON();
            this.inventaris.forEach(item => {
                const feature = new ol.Feature({
                    geometry: format.readGeometry(item.geoData)
                });
                this.vectorSource.addFeature(feature);
            });
        },

        removeItem(id) {
            this.inventaris = this.inventaris.filter(i => i.id !== id);
            this.saveData();
            this.vectorSource.clear();
            this.renderFeaturesFromData();
        },

        zoomTo(lon, lat) {
            this.map.getView().animate({
                center: ol.proj.fromLonLat([lon, lat]),
                zoom: 16,
                duration: 800
            });
        },

        clearAll() {
            if(confirm('Hapus semua data permanen?')) {
                localStorage.removeItem('gis_data');
                location.reload();
            }
        }
    }
}
</script>
</body>
</html>
