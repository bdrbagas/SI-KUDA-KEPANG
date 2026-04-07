@extends('layouts.app')

@section('title', 'Tambah Dokumentasi')
@section('page-title', 'Tambah Dokumentasi Kegiatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="data-card">
                <div class="data-card-header">
                    <h5 class="data-card-title">
                        <i class="bi bi-plus-circle me-2"></i>Form Dokumentasi Kegiatan Lingkungan
                    </h5>
                </div>
                <div class="data-card-body">
                    <form action="{{ route('lingkungan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Desa <span class="text-danger">*</span></label>
                                <select name="desa_id" class="form-select" required>
                                    <option value="">Pilih Desa</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>
                                            {{ $desa->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" class="form-select" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="penanaman_pohon" {{ old('jenis_kegiatan') == 'penanaman_pohon' ? 'selected' : '' }}>🌳 Penanaman Pohon</option>
                                    <option value="pembersihan_sampah" {{ old('jenis_kegiatan') == 'pembersihan_sampah' ? 'selected' : '' }}>🧹 Pembersihan Sampah</option>
                                    <option value="penebangan_liar" {{ old('jenis_kegiatan') == 'penebangan_liar' ? 'selected' : '' }}>⚠️ Penebangan Pohon Liar</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required
                                placeholder="Contoh: Penanaman 100 Pohon di Desa Pangalengan">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                placeholder="Jelaskan kegiatan secara singkat...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kegiatan" class="form-control"
                                    value="{{ old('tanggal_kegiatan') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" required
                                    placeholder="Contoh: Jl. Raya Pangalengan KM 5">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_peserta" class="form-control"
                                    value="{{ old('jumlah_peserta', 0) }}" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" class="form-control"
                                    value="{{ old('penanggung_jawab') }}" placeholder="Nama koordinator kegiatan">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hasil Kegiatan</label>
                            <textarea name="hasil_kegiatan" class="form-control" rows="2"
                                placeholder="Jelaskan hasil yang dicapai...">{{ old('hasil_kegiatan') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Dokumentasi</label>
                            <input type="file" name="foto[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Bisa upload beberapa foto sekaligus. Maksimal 5MB per foto. Format:
                                JPG, PNG</small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('lingkungan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-gradient">
                                <i class="bi bi-check-lg me-1"></i>Simpan Dokumentasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection