@extends('layouts.app')

@section('title', 'Edit Dokumentasi')
@section('page-title', 'Edit Dokumentasi Kegiatan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="data-card">
                <div class="data-card-header">
                    <h5 class="data-card-title">
                        <i class="bi bi-pencil-square me-2"></i>Form Edit Dokumentasi Lingkungan
                    </h5>
                </div>
                <div class="data-card-body">
                    <form action="{{ route('lingkungan.update', $lingkungan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Desa <span class="text-danger">*</span></label>
                                <select name="desa_id" class="form-select" required>
                                    <option value="">Pilih Desa</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ old('desa_id', $lingkungan->desa_id) == $desa->id ? 'selected' : '' }}>
                                            {{ $desa->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" class="form-select" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="penanaman_pohon" {{ old('jenis_kegiatan', $lingkungan->jenis_kegiatan) == 'penanaman_pohon' ? 'selected' : '' }}>🌳 Penanaman Pohon</option>
                                    <option value="pembersihan_sampah" {{ old('jenis_kegiatan', $lingkungan->jenis_kegiatan) == 'pembersihan_sampah' ? 'selected' : '' }}>🧹 Pembersihan Sampah</option>
                                    <option value="penebangan_liar" {{ old('jenis_kegiatan', $lingkungan->jenis_kegiatan) == 'penebangan_liar' ? 'selected' : '' }}>⚠️ Penebangan Pohon Liar</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" value="{{ old('judul', $lingkungan->judul) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $lingkungan->deskripsi) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kegiatan" class="form-control"
                                    value="{{ old('tanggal_kegiatan', $lingkungan->tanggal_kegiatan ? $lingkungan->tanggal_kegiatan->format('Y-m-d') : '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $lingkungan->lokasi) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_peserta" class="form-control"
                                    value="{{ old('jumlah_peserta', $lingkungan->jumlah_peserta) }}" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" class="form-control"
                                    value="{{ old('penanggung_jawab', $lingkungan->penanggung_jawab) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hasil Kegiatan</label>
                            <textarea name="hasil_kegiatan" class="form-control" rows="2">{{ old('hasil_kegiatan', $lingkungan->hasil_kegiatan) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tambah Foto Dokumentasi Baru</label>
                            <input type="file" name="foto[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin menambah foto. Foto lama akan tetap dipertahankan.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('lingkungan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-gradient">
                                <i class="bi bi-check-lg me-1"></i>Update Dokumentasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($lingkungan->foto && count($lingkungan->foto) > 0)
            <div class="data-card mt-4">
                <div class="data-card-header">
                    <h5 class="data-card-title">Foto Terlampir Saat Ini</h5>
                </div>
                <div class="data-card-body">
                    <div class="row g-3">
                        @foreach($lingkungan->foto as $index => $fotoPath)
                            <div class="col-4 col-md-3 position-relative">
                                <img src="{{ asset('storage/' . $fotoPath) }}" class="img-fluid rounded border" alt="Foto">
                                <form action="{{ route('lingkungan.foto.delete', [$lingkungan->id, $index]) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')" class="position-absolute top-0 end-0 m-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle"><i class="bi bi-x"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
