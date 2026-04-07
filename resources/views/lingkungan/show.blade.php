@extends('layouts.app')

@section('title', 'Detail Dokumentasi')
@section('page-title', 'Detail Dokumentasi Kegiatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="data-card mb-4">
                <div class="data-card-header d-flex justify-content-between align-items-center">
                    <h5 class="data-card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Detail Kegiatan Lingkungan
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('lingkungan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <a href="{{ route('lingkungan.edit', $lingkungan->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="data-card-body">
                    <h4 class="fw-bold mb-3">{{ $lingkungan->judul }}</h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Jenis Kegiatan</div>
                            <div class="fw-semibold">
                                @if($lingkungan->jenis_kegiatan == 'penanaman_pohon')
                                    <span class="badge bg-success"><i class="bi bi-tree-fill me-1"></i>Penanaman Pohon</span>
                                @elseif($lingkungan->jenis_kegiatan == 'pembersihan_sampah')
                                    <span class="badge bg-info"><i class="bi bi-trash-fill me-1"></i>Pembersihan Sampah</span>
                                @else
                                    <span class="badge bg-warning"><i class="bi bi-exclamation-triangle me-1"></i>Penebangan Liar</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Desa</div>
                            <div class="fw-semibold">{{ $lingkungan->desa->nama }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Tanggal Kegiatan</div>
                            <div class="fw-semibold">{{ $lingkungan->tanggal_kegiatan->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Lokasi</div>
                            <div class="fw-semibold">{{ $lingkungan->lokasi }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Jumlah Peserta</div>
                            <div class="fw-semibold">{{ $lingkungan->jumlah_peserta }} Orang</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Penanggung Jawab</div>
                            <div class="fw-semibold">{{ $lingkungan->penanggung_jawab ?: '-' }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">Deskripsi Kegiatan</h6>
                        <p class="mb-0 bg-light p-3 rounded">{{ $lingkungan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold">Hasil Kegiatan</h6>
                        <p class="mb-0 bg-light p-3 rounded">{{ $lingkungan->hasil_kegiatan ?: 'Tidak ada keterangan hasil.' }}</p>
                    </div>
                </div>
            </div>

            @if($lingkungan->foto && count($lingkungan->foto) > 0)
                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="data-card-title">Foto Dokumentasi</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="row g-3">
                            @foreach($lingkungan->foto as $fotoPath)
                                <div class="col-md-6">
                                    <img src="{{ asset('storage/' . $fotoPath) }}" class="img-fluid rounded border w-100" alt="Foto">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
