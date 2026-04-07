@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="data-card mb-4">
        <div class="data-card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-auto">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select" onchange="this.form.submit()">
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $tahunSekarang == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Total Desa Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $totalDesa }}</div>
                        <div class="stat-label">Total Desa</div>
                    </div>
                    <div class="icon-wrapper" style="background: var(--primary-gradient);">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stunting Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card stunting">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalStunting) }}</div>
                        <div class="stat-label">Kasus Stunting</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('stunting.agregat') }}" class="text-decoration-none small">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Kemiskinan Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card kemiskinan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalKeluargaMiskin) }}</div>
                        <div class="stat-label">Keluarga Miskin</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('kemiskinan.index') }}" class="text-decoration-none small">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Lingkungan Card -->
        <div class="col-12 col-sm-6 col-xl-3">
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
                <div class="mt-3">
                    <a href="{{ route('lingkungan.index') }}" class="text-decoration-none small">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Portal Cards -->
    <div class="row g-4 mb-4">
        <!-- Portal Stunting -->
        <div class="col-12 col-lg-4">
            <div class="data-card h-100">
                <div class="data-card-header" style="background: var(--stunting-gradient);">
                    <h5 class="data-card-title text-white mb-0">
                        <i class="bi bi-heart-pulse-fill me-2"></i>Portal Stunting
                    </h5>
                </div>
                <div class="data-card-body">
                    <p class="text-muted mb-3">Kelola data stunting, ibu hamil KEK, dan anggaran penanganan stunting di 13
                        desa.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Data Agregat Penduduk
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Data Stunting & KEK
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Anggaran (Desa, Puskesmas, CSR)
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Import Data Excel
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
                    <p class="text-muted mb-3">Kelola data kemiskinan, pengangguran, dan program bantuan sosial di 13 desa.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Desil Kemiskinan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Data Pengangguran
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Realisasi PKH & Sembako
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Data AK-1
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
                    <p class="text-muted mb-3">Dokumentasi kegiatan lingkungan: penanaman pohon, pembersihan sampah, dll.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Penanaman Pohon
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Pembersihan Sampah
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Penebangan Pohon Liar
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Upload Foto Dokumentasi
                        </li>
                    </ul>
                    <a href="{{ route('lingkungan.index') }}" class="btn btn-primary-gradient w-100">
                        Masuk Portal <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Desa Overview -->
    <div class="row">
        <div class="col-12">
            <div class="data-card">
                <div class="data-card-header">
                    <h5 class="data-card-title">
                        <i class="bi bi-geo-alt me-2"></i>Overview 13 Desa Kecamatan Pangalengan Tahun {{ $tahunSekarang }}
                    </h5>
                </div>
                <div class="data-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Desa</th>
                                    <th class="text-center">Data Stunting</th>
                                    <th class="text-center">Data Kemiskinan</th>
                                    <th class="text-center">Dokumentasi Lingkungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($desaStats as $index => $desa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $desa->nama }}</strong>
                                            <br><small class="text-muted">{{ $desa->kode }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill badge-stunting">
                                                {{ $desa->data_stuntings_count }} data
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill badge-kemiskinan">
                                                {{ $desa->desil_kemiskinans_count }} data
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill badge-lingkungan">
                                                {{ $desa->dokumentasi_lingkungans_count }} kegiatan
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada data desa. Silakan jalankan seeder
                                                terlebih dahulu.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection