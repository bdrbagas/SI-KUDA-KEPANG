@extends('layouts.app')

@section('title', 'Ojek Pangkalan')
@section('page-title', 'Data Ojek Pangkalan')

@section('content')
    <div class="data-card mb-4">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-bicycle me-2"></i>Daftar Ojek Pangkalan
            </h5>
            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalOjeng">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Pangkalan
                </button>
            @endif
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern border-bottom-0 mb-0">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th>Nama Pangkalan</th>
                            <th>Lokasi</th>
                            <th>Koordinator</th>
                            <th>Telepon/WA</th>
                            <th class="text-end">Jumlah Ojek</th>
                            <th>Keterangan</th>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <th class="text-end">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataOjeng as $data)
                            <tr>
                                <td><strong>{{ $data->desa->nama }}</strong></td>
                                <td class="fw-semibold text-primary">{{ $data->nama_pangkalan }}</td>
                                <td>{{ $data->lokasi }}</td>
                                <td>{{ $data->koordinator ?? '-' }}</td>
                                <td>
                                    @if($data->telepon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->telepon) }}" target="_blank"
                                            class="text-decoration-none">
                                            <i class="bi bi-whatsapp text-success me-1"></i>{{ $data->telepon }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end"><span
                                        class="badge bg-info text-dark">{{ number_format($data->jumlah_ojek) }}</span></td>
                                <td>{{ Str::limit($data->keterangan ?? '-', 30) }}</td>
                                @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEditOjeng{{ $data->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('kemiskinan.ojeng.destroy', $data) }}" method="POST"
                                                onsubmit="return confirm('Hapus data pangkalan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 8 : 7 }}"
                                    class="text-center py-4 text-muted">Belum ada data pangkalan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
        <!-- Modal Tambah Data -->
        <div class="modal fade" id="modalOjeng" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kemiskinan.ojeng.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Form Data Pangkalan Ojek</h5>
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
                                    <label class="form-label">Nama Pangkalan</label>
                                    <input type="text" name="nama_pangkalan" class="form-control" required
                                        placeholder="Contoh: Pangkalan Simpang Tiga">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Lokasi Detail</label>
                                    <textarea name="lokasi" class="form-control" required rows="2"
                                        placeholder="Detail alamat lokasi pangkalan"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Jumlah Anggota Ojek</label>
                                    <input type="number" name="jumlah_ojek" class="form-control" required min="1" value="1">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Nama Koordinator</label>
                                    <input type="text" name="koordinator" class="form-control"
                                        placeholder="Nama ketua/koordinator">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Telepon/WA</label>
                                    <input type="text" name="telepon" class="form-control" placeholder="Contoh: 08123456789">
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

        <!-- Modal Edit (Loop) -->
        @foreach($dataOjeng as $data)
            <div class="modal fade" id="modalEditOjeng{{ $data->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('kemiskinan.ojeng.update', $data) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Pangkalan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Desa</label>
                                        <input type="text" class="form-control" value="{{ $data->desa->nama }}" readonly disabled>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Nama Pangkalan</label>
                                        <input type="text" name="nama_pangkalan" class="form-control" required
                                            value="{{ $data->nama_pangkalan }}">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Lokasi Detail</label>
                                        <textarea name="lokasi" class="form-control" required
                                            rows="2">{{ $data->lokasi }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Jumlah Anggota Ojek</label>
                                        <input type="number" name="jumlah_ojek" class="form-control" required min="1"
                                            value="{{ $data->jumlah_ojek }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Nama Koordinator</label>
                                        <input type="text" name="koordinator" class="form-control" value="{{ $data->koordinator }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Telepon/WA</label>
                                        <input type="text" name="telepon" class="form-control" value="{{ $data->telepon }}">
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