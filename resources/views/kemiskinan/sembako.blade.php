@extends('layouts.app')

@section('title', 'Realisasi Sembako')
@section('page-title', 'Realisasi Bantuan Sembako/BPNT')

@section('content')
<div class="data-card mb-4">
    <div class="data-card-body">
        <form method="GET" class="row align-items-end g-3">
            <div class="col-auto">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h5 class="data-card-title">
            <i class="bi bi-basket-fill me-2"></i>Data Realisasi Sembako {{ $tahun }}
        </h5>
        @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
        <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalSembako">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </button>
        @endif
    </div>
    <div class="data-card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern border-bottom-0 mb-0">
                <thead>
                    <tr>
                        <th>Desa</th>
                        <th>Bulan Penyaluran</th>
                        <th class="text-end">Jumlah KPM</th>
                        <th>Keterangan</th>
                        @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                            <th class="text-end">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $totalKpm = 0; $totalAnggaran = 0; $totalRealisasi = 0; @endphp
                    @forelse($realisasiSembako as $data)
                        @php 
                            $totalKpm += $data->jumlah_kpm;
                            $totalAnggaran += $data->anggaran;
                            $totalRealisasi += $data->realisasi;
                        @endphp
                        <tr>
                            <td><strong>{{ $data->desa->nama }}</strong></td>
                            <td>{{ $data->bulan ?? '-' }}</td>
                            <td class="text-end"><span class="badge bg-warning text-dark">{{ number_format($data->jumlah_kpm) }}</span></td>
                            <td>{{ $data->keterangan ?? '-' }}</td>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditSembako{{ $data->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 5 : 4 }}" class="text-center py-4 text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($realisasiSembako->count() > 0)
                <tfoot>
                    <tr class="bg-light fw-bold">
                        <td colspan="2" class="text-end">TOTAL KESELURUHAN</td>
                        <td class="text-end border-top">{{ number_format($totalKpm) }}</td>
                        <td></td>
                        @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                            <td></td>
                        @endif
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
<!-- Modal Tambah Data -->
<div class="modal fade" id="modalSembako" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kemiskinan.sembako.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Data Realisasi Sembako</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Desa</label>
                            @if(optional(Auth::user())->canManageAllDesa())
                            <select name="desa_id" class="form-select" required>
                                <option value="">Pilih Desa</option>
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                                @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control" value="{{ optional(Auth::user())->desa)->nama }}" readonly>
                            <input type="hidden" name="desa_id" value="{{ optional(Auth::user())->desa_id }}">
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Bulan/Status</label>
                            <input type="text" name="bulan" class="form-control" placeholder="Contoh: Januari, Februari, dst.">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Jumlah KPM (Keluarga Penerima Manfaat)</label>
                            <input type="number" name="jumlah_kpm" class="form-control" required min="0" value="0">
                        </div>
                        <input type="hidden" name="anggaran" value="0">
                        <input type="hidden" name="realisasi" value="0">
                        <div class="col-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
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
</div>

<!-- Modal Edit (Loop) -->
@foreach($realisasiSembako as $data)
<div class="modal fade" id="modalEditSembako{{ $data->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kemiskinan.sembako.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Realisasi Sembako</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Desa</label>
                            <input type="text" class="form-control" value="{{ $data->desa->nama }}" readonly disabled>
                            <input type="hidden" name="desa_id" value="{{ $data->desa_id }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $data->tahun }}" readonly>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Bulan/Status</label>
                            <input type="text" name="bulan" class="form-control" value="{{ $data->bulan }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Jumlah KPM (Keluarga Penerima Manfaat)</label>
                            <input type="number" name="jumlah_kpm" class="form-control" required min="0" value="{{ $data->jumlah_kpm }}">
                        </div>
                        <input type="hidden" name="anggaran" value="0">
                        <input type="hidden" name="realisasi" value="0">
                        <div class="col-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ $data->keterangan }}</textarea>
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

@endif
@endsection
