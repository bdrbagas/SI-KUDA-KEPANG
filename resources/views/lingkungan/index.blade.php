@extends('layouts.app')

@section('title', 'Dokumentasi Lingkungan')
@section('page-title', 'Dokumentasi Kegiatan Lingkungan')

@section('content')
    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card lingkungan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $stats->get('penanaman_pohon')->total ?? 0 }}</div>
                        <div class="stat-label">Penanaman Pohon</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-tree-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card lingkungan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $stats->get('pembersihan_sampah')->total ?? 0 }}</div>
                        <div class="stat-label">Pembersihan Sampah</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-trash-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card lingkungan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $stats->get('penebangan_liar')->total ?? 0 }}</div>
                        <div class="stat-label">Penebangan Liar</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Add Button -->
    <div class="data-card mb-4">
        <div class="data-card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-12 col-md-3">
                    <label class="form-label">Desa</label>
                    <select name="desa_id" class="form-select">
                        <option value="">Semua Desa</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="penanaman_pohon" {{ request('jenis_kegiatan') == 'penanaman_pohon' ? 'selected' : '' }}>Penanaman Pohon</option>
                        <option value="pembersihan_sampah" {{ request('jenis_kegiatan') == 'pembersihan_sampah' ? 'selected' : '' }}>Pembersihan Sampah</option>
                        <option value="penebangan_liar" {{ request('jenis_kegiatan') == 'penebangan_liar' ? 'selected' : '' }}>Penebangan Liar</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary-gradient w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Button -->
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('lingkungan.create') }}" class="btn btn-primary-gradient">
            <i class="bi bi-plus-lg me-2"></i>Tambah Dokumentasi
        </a>
    </div>

    <!-- Gallery Grid -->
    <div class="row g-4">
        @forelse($dokumentasis as $dok)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="data-card h-100">
                    @if($dok->foto && count($dok->foto) > 0)
                        <img src="{{ asset('storage/' . $dok->foto[0]) }}" class="card-img-top" alt="{{ $dok->judul }}"
                            style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="data-card-body">
                        <div class="d-flex gap-2 mb-2">
                            @if($dok->jenis_kegiatan == 'penanaman_pohon')
                                <span class="badge bg-success"><i class="bi bi-tree-fill me-1"></i>Penanaman Pohon</span>
                            @elseif($dok->jenis_kegiatan == 'pembersihan_sampah')
                                <span class="badge bg-info"><i class="bi bi-trash-fill me-1"></i>Pembersihan Sampah</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Penebangan
                                    Liar</span>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-2">{{ $dok->judul }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>{{ $dok->desa->nama }} - {{ $dok->lokasi }}
                        </p>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar me-1"></i>{{ $dok->tanggal_kegiatan->format('d M Y') }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-people me-1"></i>{{ $dok->jumlah_peserta }} peserta
                        </p>
                        <p class="text-muted small mb-0">{{ Str::limit($dok->deskripsi, 100) }}</p>
                    </div>
                    <div class="data-card-body border-top pt-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('lingkungan.show', $dok) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <a href="{{ route('lingkungan.edit', $dok) }}" class="btn btn-sm btn-outline-warning flex-fill">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <form action="{{ route('lingkungan.destroy', $dok) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="data-card text-center py-5">
                    <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">Belum ada dokumentasi kegiatan</h5>
                    <p class="text-muted">Klik tombol "Tambah Dokumentasi" untuk menambahkan kegiatan baru.</p>
                    <a href="{{ route('lingkungan.create') }}" class="btn btn-primary-gradient">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Dokumentasi
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($dokumentasis->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $dokumentasis->links() }}
        </div>
    @endif
@endsection