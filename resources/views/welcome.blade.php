<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoInventaris - Solusi Pemetaan Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root { --primary-color: #198754; --dark-bg: #121212; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                        url('https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            color: white;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-geo-alt-fill me-2"></i>GeoInventaris</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-success rounded-pill px-4" href="/gis-app">Buka Peta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section d-flex align-items-center">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-3">Kelola Aset Spasial Anda Lebih Mudah</h1>
                    <p class="lead mb-4">Platform prototyping GIS berbasis web untuk inventarisasi lahan, pemetaan area, dan manajemen database koordinat secara real-time.</p>
                    <div class="d-grid d-sm-flex justify-content-center justify-content-lg-start gap-3">
                        <a href="/gis-app" class="btn btn-success btn-lg px-5 rounded-pill shadow">Mulai Sekarang</a>
                        <a href="/gis-features" class="btn btn-outline-light btn-lg px-5 rounded-pill">Pelajari Fitur</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kenapa Menggunakan GeoInventaris?</h2>
                <p class="text-muted">Dibangun khusus untuk kecepatan prototyping aplikasi ArcGIS user.</p>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-4 card border-0 h-100 shadow-sm">
                        <i class="bi bi-pencil-square feature-icon"></i>
                        <h4 class="fw-bold">Digitasi Cepat</h4>
                        <p class="text-muted small">Gambar area polygon dan titik inventaris langsung di atas peta dasar OpenStreetMap.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 card border-0 h-100 shadow-sm">
                        <i class="bi bi-database-fill-check feature-icon"></i>
                        <h4 class="fw-bold">Local Storage</h4>
                        <p class="text-muted small">Data tersimpan aman di browser Anda. Tidak perlu takut kehilangan data saat halaman dimuat ulang.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 card border-0 h-100 shadow-sm">
                        <i class="bi bi-phone feature-icon"></i>
                        <h4 class="fw-bold">Mobile Ready</h4>
                        <p class="text-muted small">Tampilan yang responsif memungkinkan Anda melakukan pemetaan langsung di lapangan melalui smartphone.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="small mb-0 opacity-50">&copy; 2026 GeoInventaris - Developer Laravel GIS Prototype.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
