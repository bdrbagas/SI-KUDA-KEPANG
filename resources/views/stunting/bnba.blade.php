@extends('layouts.app')

@section('title', 'By Name By Address')
@section('page-title', 'Data By Name By Address (BNBA)')

@section('content')
    <!-- Filter & Actions -->
    <div class="data-card mb-4">
        <div class="data-card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-12 col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Nama/NIK...">
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $num => $name)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua Tahun</option>
                        @for($y = date('Y') + 1; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label">Desa</label>
                    <select name="desa_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-auto">
                    <button type="submit" class="btn btn-primary-gradient w-100 px-3">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="col-12 col-md-auto ms-auto text-end">
                    <a href="{{ route('stunting.bnba.template') }}" class="btn btn-outline-success me-1">
                        <i class="bi bi-download"></i> Template
                    </a>
                    <a href="{{ route('stunting.bnba.import') }}" class="btn btn-success">
                        <i class="bi bi-upload"></i> Import
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Button -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary-gradient" data-bs-toggle="modal" data-bs-target="#modalBnba">
            <i class="bi bi-plus-lg me-2"></i>Tambah Data
        </button>
    </div>

    <!-- Data Table -->
    <div class="data-card">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-person-lines-fill me-2"></i>Data BNBA Stunting
            </h5>
            <span class="badge bg-secondary">{{ $bnbas->total() }} data</span>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Desa</th>
                            <th>Alamat</th>
                            <th>Posyandu</th>
                            <th>BB/TB</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bnbas as $bnba)
                            <tr>
                                <td>
                                    <strong>{{ $bnba->nama }}</strong>
                                    @if($bnba->jenis_kelamin)
                                        <span
                                            class="badge {{ $bnba->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' }} ms-1">{{ $bnba->jenis_kelamin }}</span>
                                    @endif
                                    @if($bnba->tanggal_lahir)
                                        <br><small class="text-muted">{{ $bnba->tanggal_lahir->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td>{{ $bnba->desa->nama }}</td>
                                <td>
                                    <small>{{ Str::limit($bnba->alamat, 30) }}</small>
                                    @if($bnba->rt_rw)
                                        <br><small class="text-muted">RT/RW: {{ $bnba->rt_rw }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $bnba->posyandu ?: '-' }}</span>
                                </td>
                                <td>
                                    @if($bnba->berat_badan && $bnba->tinggi_badan)
                                        {{ $bnba->berat_badan }} kg / {{ $bnba->tinggi_badan }} cm
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('stunting.bnba.destroy', $bnba) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bnbas->hasPages())
            <div class="data-card-body border-top d-flex justify-content-center">
                <div class="pagination-sm-wrapper">
                    <style>
                        .pagination-sm-wrapper nav div.hidden { display: none; }
                        .pagination-sm-wrapper .pagination {
                            margin-bottom: 0;
                            --bs-pagination-padding-x: 0.6rem;
                            --bs-pagination-padding-y: 0.3rem;
                            --bs-pagination-font-size: 0.85rem;
                        }
                    </style>
                    {{ $bnbas->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Tambah BNBA -->
    <div class="modal fade" id="modalBnba" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('stunting.bnba.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data BNBA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Desa</label>
                                <select name="desa_id" class="form-select" required>
                                    <option value="">Pilih Desa</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bulan Data</label>
                                <select name="bulan" class="form-select" required>
                                    <option value="">Pilih Bulan</option>
                                    @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $num => $name)
                                        <option value="{{ $num }}" {{ date('m') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tahun Data</label>
                                <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">RT/RW</label>
                                <input type="text" name="rt_rw" class="form-control" placeholder="001/001">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Berat Badan (kg)</label>
                                <input type="number" name="berat_badan" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tinggi Badan (cm)</label>
                                <input type="number" name="tinggi_badan" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Posyandu</label>
                                <input type="text" name="posyandu" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-gradient">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection