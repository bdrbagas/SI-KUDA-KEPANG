@extends('layouts.app')

@section('title', 'Desil Kemiskinan')
@section('page-title', 'Data Desil Kemiskinan')

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
                <i class="bi bi-bar-chart-line-fill me-2"></i>Data Desil Kemiskinan {{ $tahun }}
            </h5>
            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalDesil">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </button>
            @endif
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern border-bottom-0 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">Peringkat</th>
                            <th>Desa</th>
                            <th class="text-end">Desil 1<br><small class="text-muted fw-normal">Sangat Miskin</small></th>
                            <th class="text-end">Desil 2<br><small class="text-muted fw-normal">Miskin</small></th>
                            <th class="text-end">Desil 3<br><small class="text-muted fw-normal">Hampir Miskin</small></th>
                            <th class="text-end">Desil 4<br><small class="text-muted fw-normal">Rentan Miskin</small></th>
                            <th class="text-end">Desil 5</th>
                            <th class="text-end">Total Keluarga</th>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <th class="text-end">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($desilKemiskinan as $data)
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $loop->iteration <= 3 ? 'bg-danger' : 'bg-primary' }} rounded-circle p-2">{{ $loop->iteration }}</span>
                                </td>
                                <td class="align-middle"><strong>{{ $data->desa->nama }}</strong></td>
                                <td class="text-end"><span class="badge bg-danger">{{ number_format($data->desil_1) }}</span>
                                </td>
                                <td class="text-end"><span
                                        class="badge bg-warning text-dark">{{ number_format($data->desil_2) }}</span></td>
                                <td class="text-end"><span class="badge bg-info">{{ number_format($data->desil_3) }}</span></td>
                                <td class="text-end"><span class="badge bg-secondary">{{ number_format($data->desil_4) }}</span>
                                </td>
                                <td class="text-end">{{ number_format($data->desil_5) }}</td>
                                <td class="text-end bg-light fw-bold">{{ number_format($data->total_keluarga) }}</td>
                                @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEditDesil{{ $data->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 9 : 8 }}" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($desilKemiskinan->count() > 0)
                    <tfoot class="table-light fw-bold border-top">
                        <tr>
                            <td colspan="2">Total Keseluruhan</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_1')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_2')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_3')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_4')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_5')) }}</td>
                            <td class="text-end bg-light text-primary fs-5">{{ number_format($desilKemiskinan->sum('total_keluarga')) }}</td>
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
        <div class="modal fade" id="modalDesil" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kemiskinan.desil.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Form Data Desil Kemiskinan</h5>
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
                                <div class="col-6 mb-3">
                                    <label class="form-label">Desil 1 (Sangat Miskin)</label>
                                    <input type="number" name="desil_1" class="form-control" required min="0" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Desil 2 (Miskin)</label>
                                    <input type="number" name="desil_2" class="form-control" required min="0" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Desil 3 (Hampir Miskin)</label>
                                    <input type="number" name="desil_3" class="form-control" required min="0" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Desil 4 (Rentan Miskin)</label>
                                    <input type="number" name="desil_4" class="form-control" required min="0" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Desil 5</label>
                                    <input type="number" name="desil_5" class="form-control" min="0" value="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Total Keluarga</label>
                                    <input type="number" name="total_keluarga" class="form-control" min="0" value="0">
                                </div>
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
        @foreach($desilKemiskinan as $data)
            <div class="modal fade" id="modalEditDesil{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('kemiskinan.desil.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Desil Kemiskinan</h5>
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
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Desil 1 (Sangat Miskin)</label>
                                        <input type="number" name="desil_1" class="form-control" required min="0" value="{{ $data->desil_1 }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Desil 2 (Miskin)</label>
                                        <input type="number" name="desil_2" class="form-control" required min="0" value="{{ $data->desil_2 }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Desil 3 (Hampir Miskin)</label>
                                        <input type="number" name="desil_3" class="form-control" required min="0" value="{{ $data->desil_3 }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Desil 4 (Rentan Miskin)</label>
                                        <input type="number" name="desil_4" class="form-control" required min="0" value="{{ $data->desil_4 }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Desil 5</label>
                                        <input type="number" name="desil_5" class="form-control" min="0" value="{{ $data->desil_5 }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Total Keluarga</label>
                                        <input type="number" name="total_keluarga" class="form-control" min="0" value="{{ $data->total_keluarga }}">
                                    </div>
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