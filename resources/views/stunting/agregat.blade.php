@extends('layouts.app')

@section('title', 'Data Agregat Stunting')
@section('page-title', 'Data Agregat Stunting')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #mapStunting {
            height: 450px;
            width: 100%;
            border-radius: 12px;
            z-index: 10;
        }
        .map-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .info.legend {
            background: white;
            padding: 10px 14px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            font-size: 13px;
            color: #555;
            line-height: 1.6;
        }
        .info.legend i {
            width: 16px;
            height: 16px;
            float: left;
            margin-right: 8px;
            opacity: 0.8;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <div class="data-card mb-4">
        <div class="data-card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-auto">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $num => $name)
                            <option value="{{ $num }}" {{ (isset($bulanSekarang) && $bulanSekarang == $num) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
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

    <!-- Peta GIS Stunting -->
    <div class="map-card mb-4">
        <h5 class="data-card-title mb-3">
            <i class="bi bi-geo-alt-fill text-danger me-2"></i>Peta Persebaran Stunting Kecamatan Pangalengan
        </h5>
        <div id="mapStunting"></div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Summary Cards -->
        <div class="col-12 col-md-4">
            <div class="stat-card stunting">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $agregatPenduduk->sum('balita') }}</div>
                        <div class="stat-label">Total Balita</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card stunting">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $dataStunting->flatten()->sum('jumlah_stunting') }}</div>
                        <div class="stat-label">Total Stunting</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card stunting">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ $dataKek->flatten()->sum('jumlah_kek') }}</div>
                        <div class="stat-label">Ibu Hamil KEK</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-gender-female"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Agregat Penduduk -->
    <div class="data-card mb-4">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-people-fill me-2"></i>Data Agregat Penduduk {{ $tahunSekarang }}
            </h5>
            <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalPenduduk">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th class="text-end">Jumlah Penduduk</th>
                            <th class="text-end">Jumlah KK</th>
                            <th class="text-end">Balita</th>
                            <th class="text-end">Ibu Hamil</th>
                            <th class="text-end">Laki-laki</th>
                            <th class="text-end">Perempuan</th>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <th class="text-end">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agregatPenduduk as $data)
                            <tr>
                                <td><strong>{{ $data->desa->nama }}</strong></td>
                                <td class="text-end">{{ number_format($data->jumlah_penduduk) }}</td>
                                <td class="text-end">{{ number_format($data->jumlah_kk) }}</td>
                                <td class="text-end">{{ number_format($data->balita) }}</td>
                                <td class="text-end">{{ number_format($data->ibu_hamil) }}</td>
                                <td class="text-end">{{ number_format($data->laki_laki) }}</td>
                                <td class="text-end">{{ number_format($data->perempuan) }}</td>
                                @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEditPenduduk{{ $data->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 8 : 7 }}" class="text-center py-4 text-muted">
                                    Belum ada data. Klik "Tambah Data" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Stunting per Desa -->
    <div class="data-card mb-4">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-heart-pulse-fill me-2"></i>Data Stunting per Desa
            </h5>
            <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalStunting">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th>Periode</th>
                            <th class="text-end">Balita Ditimbang</th>
                            <th class="text-end">Jumlah Stunting</th>
                            <th class="text-end">Persentase</th>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <th class="text-end">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($desas as $desa)
                            @php $stunting = $dataStunting->get($desa->id)?->first(); @endphp
                            <tr>
                                <td><strong>{{ $desa->nama }}</strong></td>
                                <td>{{ $stunting?->periode?->format('M Y') ?? '-' }}</td>
                                <td class="text-end">{{ $stunting ? number_format($stunting->jumlah_balita_ditimbang) : '-' }}
                                </td>
                                <td class="text-end">
                                    @if($stunting)
                                        <span class="badge bg-danger">{{ $stunting->jumlah_stunting }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($stunting)
                                        {{ number_format($stunting->persentase_stunting, 1) }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                    <td class="text-end">
                                        @if($stunting)
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEditStunting{{ $stunting->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 6 : 5 }}" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Ibu Hamil KEK -->
    <div class="data-card">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-gender-female me-2"></i>Data Ibu Hamil KEK per Desa
            </h5>
            <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalKek">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th>Periode</th>
                            <th class="text-end">Jumlah Ibu Hamil</th>
                            <th class="text-end">Jumlah KEK</th>
                            <th class="text-end">Persentase</th>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <th class="text-end">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($desas as $desa)
                            @php $kek = $dataKek->get($desa->id)?->first(); @endphp
                            <tr>
                                <td><strong>{{ $desa->nama }}</strong></td>
                                <td>{{ $kek?->periode?->format('M Y') ?? '-' }}</td>
                                <td class="text-end">{{ $kek ? number_format($kek->jumlah_ibu_hamil) : '-' }}</td>
                                <td class="text-end">
                                    @if($kek)
                                        <span class="badge bg-warning text-dark">{{ $kek->jumlah_kek }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($kek)
                                        {{ number_format($kek->persentase_kek, 1) }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                    <td class="text-end">
                                        @if($kek)
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEditKek{{ $kek->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 6 : 5 }}" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Penduduk -->
    <div class="modal fade" id="modalPenduduk" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('stunting.agregat.penduduk.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Agregat Penduduk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Desa</label>
                            <select name="desa_id" class="form-select" required>
                                <option value="">Pilih Desa</option>
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $tahunSekarang }}" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jumlah Penduduk</label>
                                <input type="number" name="jumlah_penduduk" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jumlah KK</label>
                                <input type="number" name="jumlah_kk" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Balita</label>
                                <input type="number" name="balita" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Ibu Hamil</label>
                                <input type="number" name="ibu_hamil" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Laki-laki</label>
                                <input type="number" name="laki_laki" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Perempuan</label>
                                <input type="number" name="perempuan" class="form-control" required>
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

    <!-- Modal Tambah Stunting -->
    <div class="modal fade" id="modalStunting" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('stunting.agregat.stunting.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Stunting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Desa</label>
                            <select name="desa_id" class="form-select" required>
                                <option value="">Pilih Desa</option>
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <input type="date" name="periode" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Balita Ditimbang</label>
                                <input type="number" name="jumlah_balita_ditimbang" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jumlah Stunting</label>
                                <input type="number" name="jumlah_stunting" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
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

    <!-- Modal Tambah KEK -->
    <div class="modal fade" id="modalKek" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('stunting.agregat.kek.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Ibu Hamil KEK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Desa</label>
                            <select name="desa_id" class="form-select" required>
                                <option value="">Pilih Desa</option>
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <input type="date" name="periode" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jumlah Ibu Hamil</label>
                                <input type="number" name="jumlah_ibu_hamil" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jumlah KEK</label>
                                <input type="number" name="jumlah_kek" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
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
    @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
        <!-- Modal Edit Penduduk (Loop) -->
        @foreach($agregatPenduduk as $data)
            <div class="modal fade" id="modalEditPenduduk{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('stunting.agregat.penduduk.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Agregat Penduduk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Desa</label>
                                    <input type="text" class="form-control" value="{{ $data->desa->nama }}" readonly disabled>
                                    <input type="hidden" name="desa_id" value="{{ $data->desa_id }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tahun</label>
                                    <input type="number" name="tahun" class="form-control" value="{{ $data->tahun }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jumlah Penduduk</label>
                                        <input type="number" name="jumlah_penduduk" class="form-control" required value="{{ $data->jumlah_penduduk }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jumlah KK</label>
                                        <input type="number" name="jumlah_kk" class="form-control" required value="{{ $data->jumlah_kk }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Balita</label>
                                        <input type="number" name="balita" class="form-control" required value="{{ $data->balita }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Ibu Hamil</label>
                                        <input type="number" name="ibu_hamil" class="form-control" required value="{{ $data->ibu_hamil }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Laki-laki</label>
                                        <input type="number" name="laki_laki" class="form-control" required value="{{ $data->laki_laki }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Perempuan</label>
                                        <input type="number" name="perempuan" class="form-control" required value="{{ $data->perempuan }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary-gradient">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Edit Stunting (Loop) -->
        @foreach($dataStunting->flatten() as $stunting)
            <div class="modal fade" id="modalEditStunting{{ $stunting->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('stunting.agregat.stunting.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Stunting</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Desa</label>
                                    <input type="text" class="form-control" value="{{ $stunting->desa->nama }}" readonly disabled>
                                    <input type="hidden" name="desa_id" value="{{ $stunting->desa_id }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Periode</label>
                                    <input type="date" name="periode" class="form-control" value="{{ $stunting->periode ? $stunting->periode->format('Y-m-d') : '' }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Balita Ditimbang</label>
                                        <input type="number" name="jumlah_balita_ditimbang" class="form-control" required value="{{ $stunting->jumlah_balita_ditimbang }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jumlah Stunting</label>
                                        <input type="number" name="jumlah_stunting" class="form-control" required value="{{ $stunting->jumlah_stunting }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2">{{ $stunting->keterangan }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary-gradient">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Edit KEK (Loop) -->
        @foreach($dataKek->flatten() as $kek)
            <div class="modal fade" id="modalEditKek{{ $kek->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('stunting.agregat.kek.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Ibu Hamil KEK</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Desa</label>
                                    <input type="text" class="form-control" value="{{ $kek->desa->nama }}" readonly disabled>
                                    <input type="hidden" name="desa_id" value="{{ $kek->desa_id }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Periode</label>
                                    <input type="date" name="periode" class="form-control" value="{{ $kek->periode ? $kek->periode->format('Y-m-d') : '' }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jumlah Ibu Hamil</label>
                                        <input type="number" name="jumlah_ibu_hamil" class="form-control" required value="{{ $kek->jumlah_ibu_hamil }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jumlah KEK</label>
                                        <input type="number" name="jumlah_kek" class="form-control" required value="{{ $kek->jumlah_kek }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2">{{ $kek->keterangan }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary-gradient">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

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