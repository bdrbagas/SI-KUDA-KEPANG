@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #mapStunting {
            height: 500px;
            width: 100%;
            border-radius: 16px;
            z-index: 10;
        }
        .map-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .info.legend {
            background: white;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-size: 13px;
            color: #475569;
            line-height: 1.8;
        }
        .info.legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 10px;
            opacity: 0.9;
            border-radius: 50%;
            margin-top: 2px;
        }
    </style>
@endpush

@section('content')
<div class="hero-section">
    <div class="container">
        <h1 class="hero-title">SI KUDA KEPANG</h1>
        <p class="hero-subtitle">
            Sistem Informasi Kumpulan Data terpadu untuk Kecamatan Pangalengan. 
            Menyajikan transparansi data untuk pelayanan masyarakat yang lebih baik.
        </p>
    </div>
</div>

<div class="container mb-5">
    <!-- Statistik Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $totalDesa }}</div>
                        <div class="stat-label">Total Desa</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card stunting">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalStunting) }}</div>
                        <div class="stat-label">Total Balita Stunting</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card kemiskinan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalKeluargaMiskin) }}</div>
                        <div class="stat-label">Keluarga Miskin (Desil 1-5)</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card lingkungan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalKegiatan) }}</div>
                        <div class="stat-label">Kegiatan Lingkungan</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-tree-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portal Cards -->
    <div class="row g-4 mb-5">
        <!-- Portal Stunting -->
        <div class="col-12 col-lg-4">
            <div class="data-card h-100">
                <div class="data-card-header" style="background: var(--stunting-gradient);">
                    <h5 class="data-card-title text-white mb-0">
                        <i class="bi bi-heart-pulse-fill me-2"></i>Portal Stunting
                    </h5>
                </div>
                <div class="data-card-body">
                    <p class="text-muted mb-3">Kelola dan pantau sebaran stunting, data sasaran, persentase ibu hamil KEK, dan serapan anggaran penanganan di 13 desa.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Peta Sebaran Anak Stunting (GIS)
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Agregat dan Data BNBA Terpadu
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Data Ibu Hamil KEK per Desa
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Transparansi Anggaran Penanganan
                        </li>
                    </ul>
                    <a href="{{ route('stunting.agregat') }}" class="btn btn-primary-gradient w-100">
                        Masuk Portal <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Portal Kemiskinan -->
        <div class="col-12 col-lg-4">
            <div class="data-card h-100">
                <div class="data-card-header" style="background: var(--kemiskinan-gradient);">
                    <h5 class="data-card-title text-white mb-0">
                        <i class="bi bi-wallet2 me-2"></i>Portal Kemiskinan
                    </h5>
                </div>
                <div class="data-card-body">
                    <p class="text-muted mb-3">Sistem terintegrasi untuk memantau desil kemiskinan, jumlah pengangguran, dan status realisasi program bansos di wilayah Pangalengan.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Rangkuman Desil Kemiskinan (1-5)
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Persentase Pengangguran per Desa
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Realisasi Bansos PKH dan Sembako
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Tingkat Pendaftaran AK-1
                        </li>
                    </ul>
                    <a href="{{ route('kemiskinan.index') }}" class="btn btn-primary-gradient w-100">
                        Masuk Portal <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Portal Lingkungan -->
        <div class="col-12 col-lg-4">
            <div class="data-card h-100">
                <div class="data-card-header" style="background: var(--lingkungan-gradient);">
                    <h5 class="data-card-title text-white mb-0">
                        <i class="bi bi-tree-fill me-2"></i>Portal Lingkungan
                    </h5>
                </div>
                <div class="data-card-body">
                    <p class="text-muted mb-3">Galeri dokumentasi aksi nyata perbaikan lingkungan: mulai dari penanaman pohon hingga pencegahan penebangan liar di kecamatan.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Aksi Penanaman Pohon Massal
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Galeri Pembersihan Sampah
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Dokumentasi Penebangan Liar
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Liputan Mitigasi Lingkungan Lainnya
                        </li>
                    </ul>
                    <a href="{{ route('lingkungan.index') }}" class="btn btn-primary-gradient w-100">
                        Masuk Portal <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Peta GIS -->
    <div class="row">
        <div class="col-12">
            <div class="map-wrapper">
                <h4 class="fw-bold text-dark mb-4 text-center">
                    <i class="bi bi-map-fill text-danger me-2"></i> Peta Persebaran Stunting Kecamatan Pangalengan
                </h4>
                <div id="mapStunting"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data GIS dari Controller
            const gisData = @json($gisData);
            
            // Inisialisasi Peta (Pusat di Kecamatan Pangalengan)
            const map = L.map('mapStunting').setView([-7.1944, 107.5621], 12);
            
            // Tambahkan Tile Layer (OpenStreetMap CartoDB Positron for cleaner look)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            // Fungsi Penentuan Warna Berdasarkan Jumlah Kasus Stunting
            function getColor(val) {
                if (val === 0) return '#28a745'; // 0 kasus: Hijau (Aman)
                if (val >= 100) return '#dc3545'; // >= 100 kasus: Merah (Sangat Tinggi)
                if (val >= 50) return '#fd7e14'; // 50 - 99 kasus: Oranye (Tinggi)
                if (val >= 20) return '#ffc107'; // 20 - 49 kasus: Kuning (Sedang)
                return '#28a745'; // < 20 kasus: Hijau (Rendah)
            }

            // Render Marker Peta (CircleMarkers) 
            gisData.forEach(desa => {
                const color = getColor(desa.stunting_count); // Warna sekarang berdasarkan jumlah kasus absolute
                const radius = desa.stunting_count > 0 ? 150 + (desa.stunting_count * 3) : 100; // Skala radius berdasarkan jumlah anak stunting
                
                const circle = L.circle([desa.lat, desa.lng], {
                    color: '#fff', // Border putih
                    weight: 1,
                    fillColor: color,
                    fillOpacity: 0.75,
                    radius: radius,
                    className: 'bubble-marker'
                }).addTo(map);
                
                const dataDetail = desa.balita_count > 0 
                     ? `${desa.stunting_count} dari ${desa.balita_count} Anak` 
                     : `Belum ada data sasaran`;

                circle.bindTooltip(`
                    <div style="font-size: 13px;">
                        <strong>Desa ${desa.nama}</strong><hr style="margin: 4px 0;">
                        Kasus: <strong>${dataDetail}</strong><br>
                        Persentase: <strong style="color: ${color}; font-size: 15px;">${desa.stunting.toFixed(1)}%</strong>
                    </div>
                `, { direction: 'top', className: 'text-center' });
            });

            // Legenda Warna dengan batas jumlah absolut
            const legend = L.control({position: 'bottomright'});
            legend.onAdd = function (map) {
                const div = L.DomUtil.create('div', 'info legend');
                const grades = [
                    { label: 'Rendah (< 20 kasus)', color: '#28a745' },
                    { label: 'Sedang (20 - 49 kasus)', color: '#ffc107' },
                    { label: 'Tinggi (50 - 99 kasus)', color: '#fd7e14' },
                    { label: 'Sangat Tinggi (>= 100 kasus)', color: '#dc3545' }
                ];
                
                let labels = ['<strong>Tingkat Stunting</strong><br>'];
                for (let i = 0; i < grades.length; i++) {
                    labels.push(
                        '<i style="background:' + grades[i].color + '"></i> ' + grades[i].label
                    );
                }
                div.innerHTML = labels.join('<br>');
                return div;
            };
            legend.addTo(map);
        });
    </script>
@endpush
