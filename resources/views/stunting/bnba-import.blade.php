@extends('layouts.app')

@section('title', 'Import BNBA')
@section('page-title', 'Import Data BNBA dari Excel')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="data-card">
                <div class="data-card-header">
                    <h5 class="data-card-title">
                        <i class="bi bi-upload me-2"></i>Upload File CSV
                    </h5>
                </div>
                <div class="data-card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Petunjuk:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Download template terlebih dahulu</li>
                            <li>Isi data sesuai format template</li>
                            <li>Upload file Excel yang sudah diisi</li>
                        </ol>
                    </div>

                    <div class="mb-4 text-center">
                        <a href="{{ route('stunting.bnba.template') }}" class="btn btn-outline-success">
                            <i class="bi bi-download me-2"></i>Download Template CSV
                        </a>
                    </div>

                    <form action="{{ route('stunting.bnba.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Desa <span class="text-danger">*</span></label>
                            <select name="desa_id" class="form-select" required>
                                <option value="">Pilih Desa</option>
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Data akan diimport untuk desa yang dipilih</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bulan Data <span class="text-danger">*</span></label>
                                <select name="bulan" class="form-select" required>
                                    <option value="">Pilih Bulan</option>
                                    @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $num => $name)
                                        <option value="{{ $num }}" {{ date('m') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Periode bulan data CSV.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Data <span class="text-danger">*</span></label>
                                <select name="tahun" class="form-select" required>
                                    <option value="">Pilih Tahun</option>
                                    @for($y = date('Y') + 1; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <small class="text-muted">Periode tahun awal data dibuat.</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">File CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".csv" required>
                            <small class="text-muted">Format: .csv, maksimal 10MB</small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('stunting.bnba.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-gradient flex-fill">
                                <i class="bi bi-upload me-1"></i>Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection