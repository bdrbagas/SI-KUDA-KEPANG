@extends('layouts.app')

@section('title', 'Pengangguran')
@section('page-title', 'Data Pengangguran')

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
                <i class="bi bi-person-x-fill me-2"></i>Data Pengangguran {{ $tahun }}
            </h5>
            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalPengangguran">
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
                            <th class="text-end">Pengangguran</th>
                            <th>Keterangan</th>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <th class="text-end">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengangguran as $data)
                            <tr>
                                <td><strong>{{ $data->desa->nama }}</strong></td>
                                <td class="text-end"><span class="badge bg-danger">{{ number_format($data->jumlah_pengangguran) }}</span></td>
                                <td>{{ $data->keterangan ?? '-' }}</td>
                                @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEditPengangguran{{ $data->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 4 : 3 }}" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
        <!-- Modal Tambah Data -->
        <div class="modal fade" id="modalPengangguran" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kemiskinan.pengangguran.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Form Data Pengangguran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
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
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Pengangguran</label>
                                <input type="number" name="jumlah_pengangguran" class="form-control" required min="0" value="0">
                            </div>
                            <input type="hidden" name="usia_produktif" value="0">
                            <input type="hidden" name="angkatan_kerja" value="0">
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
        </div>

        <!-- Modal Edit (Loop) -->
        @foreach($pengangguran as $data)
            <div class="modal fade" id="modalEditPengangguran{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('kemiskinan.pengangguran.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Pengangguran</h5>
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
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Pengangguran</label>
                                    <input type="number" name="jumlah_pengangguran" class="form-control" required min="0" value="{{ $data->jumlah_pengangguran }}">
                                </div>
                                <input type="hidden" name="usia_produktif" value="{{ $data->usia_produktif }}">
                                <input type="hidden" name="angkatan_kerja" value="{{ $data->angkatan_kerja }}">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2">{{ $data->keterangan }}</textarea>
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