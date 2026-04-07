@extends('layouts.app')

@section('title', 'Data AK-1')
@section('page-title', 'Data Pembuatan AK-1 (Pencari Kerja)')

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
            <i class="bi bi-file-earmark-person-fill me-2"></i>Data AK-1 Tahun {{ $tahun }}
        </h5>
        @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
        <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalAk1">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data
        </button>
        @endif
    </div>
    <div class="data-card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern border-bottom-0 mb-0">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle">Desa</th>
                        <th rowspan="2" class="align-middle text-end">Total<br>Pencaker</th>
                        <th colspan="2" class="text-center border-bottom bg-light">Jenis Kelamin</th>
                        <th colspan="5" class="text-center border-bottom bg-light">Tingkat Pendidikan</th>
                        @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                            <th rowspan="2" class="align-middle text-end">Aksi</th>
                        @endif
                    </tr>
                    <tr>
                        <th class="text-end bg-light">L</th>
                        <th class="text-end bg-light">P</th>
                        <th class="text-end bg-light">SD</th>
                        <th class="text-end bg-light">SMP</th>
                        <th class="text-end bg-light">SMA</th>
                        <th class="text-end bg-light">D3</th>
                        <th class="text-end bg-light">S1/S2</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $t_total = 0; $t_l = 0; $t_p = 0;
                        $t_sd = 0; $t_smp = 0; $t_sma = 0; $t_d3 = 0; $t_s1 = 0;
                    @endphp
                    @forelse($dataAk1 as $data)
                        @php 
                            $t_total += $data->total_pencaker;
                            $t_l += $data->laki_laki;
                            $t_p += $data->perempuan;
                            $t_sd += $data->sd_sederajat;
                            $t_smp += $data->smp_sederajat;
                            $t_sma += $data->sma_sederajat;
                            $t_d3 += $data->diploma;
                            $t_s1 += $data->sarjana;
                        @endphp
                        <tr>
                            <td><strong>{{ $data->desa->nama }}</strong></td>
                            <td class="text-end fw-bold text-primary">{{ number_format($data->total_pencaker) }}</td>
                            <td class="text-end">{{ number_format($data->laki_laki) }}</td>
                            <td class="text-end">{{ number_format($data->perempuan) }}</td>
                            <td class="text-end">{{ number_format($data->sd_sederajat) }}</td>
                            <td class="text-end">{{ number_format($data->smp_sederajat) }}</td>
                            <td class="text-end">{{ number_format($data->sma_sederajat) }}</td>
                            <td class="text-end">{{ number_format($data->diploma) }}</td>
                            <td class="text-end">{{ number_format($data->sarjana) }}</td>
                            @if(optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa())
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditAk1{{ $data->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (optional(Auth::user())->canManageAllDesa() || optional(Auth::user())->isAdminDesa()) ? 10 : 9 }}" class="text-center py-4 text-muted">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($dataAk1->count() > 0)
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td class="text-end">TOTAL KESELURUHAN</td>
                        <td class="text-end text-primary border-top">{{ number_format($t_total) }}</td>
                        <td class="text-end border-top">{{ number_format($t_l) }}</td>
                        <td class="text-end border-top">{{ number_format($t_p) }}</td>
                        <td class="text-end border-top">{{ number_format($t_sd) }}</td>
                        <td class="text-end border-top">{{ number_format($t_smp) }}</td>
                        <td class="text-end border-top">{{ number_format($t_sma) }}</td>
                        <td class="text-end border-top">{{ number_format($t_d3) }}</td>
                        <td class="text-end border-top">{{ number_format($t_s1) }}</td>
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
<div class="modal fade" id="modalAk1" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kemiskinan.ak1.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Data AK-1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" required>
                        </div>
                        
                        <div class="col-12 mb-3 mt-2">
                            <h6 class="fw-bold border-bottom pb-2">Berdasarkan Jenis Kelamin</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laki-laki</label>
                            <input type="number" name="laki_laki" id="laki_laki" class="form-control sum-jk" required min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Perempuan</label>
                            <input type="number" name="perempuan" id="perempuan" class="form-control sum-jk" required min="0" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-primary fw-bold">Total Pencari Kerja</label>
                            <input type="number" name="total_pencaker" id="total_pencaker" class="form-control bg-light" required min="0" value="0" readonly>
                            <small class="text-muted">Terisi otomatis</small>
                        </div>

                        <div class="col-12 mb-3 mt-2">
                            <h6 class="fw-bold border-bottom pb-2">Berdasarkan Pendidikan</h6>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label">SD</label>
                            <input type="number" name="sd_sederajat" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label">SMP</label>
                            <input type="number" name="smp_sederajat" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label">SMA</label>
                            <input type="number" name="sma_sederajat" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Diploma</label>
                            <input type="number" name="diploma" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Sarjana</label>
                            <input type="number" name="sarjana" class="form-control" min="0" value="0">
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
@foreach($dataAk1 as $data)
<div class="modal fade" id="modalEditAk1{{ $data->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kemiskinan.ak1.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data AK-1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Desa</label>
                            <input type="text" class="form-control" value="{{ $data->desa->nama }}" readonly disabled>
                            <input type="hidden" name="desa_id" value="{{ $data->desa_id }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ $data->tahun }}" readonly>
                        </div>
                        
                        <div class="col-12 mb-3 mt-2">
                            <h6 class="fw-bold border-bottom pb-2">Berdasarkan Jenis Kelamin</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laki-laki</label>
                            <input type="number" name="laki_laki" id="laki_laki_edit{{ $data->id }}" class="form-control" required min="0" value="{{ $data->laki_laki }}" oninput="document.getElementById('total_pencaker_edit{{ $data->id }}').value = (parseInt(this.value)||0) + (parseInt(document.getElementById('perempuan_edit{{ $data->id }}').value)||0)">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Perempuan</label>
                            <input type="number" name="perempuan" id="perempuan_edit{{ $data->id }}" class="form-control" required min="0" value="{{ $data->perempuan }}" oninput="document.getElementById('total_pencaker_edit{{ $data->id }}').value = (parseInt(document.getElementById('laki_laki_edit{{ $data->id }}').value)||0) + (parseInt(this.value)||0)">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-primary fw-bold">Total Pencari Kerja</label>
                            <input type="number" name="total_pencaker" id="total_pencaker_edit{{ $data->id }}" class="form-control bg-light" required min="0" value="{{ $data->total_pencaker }}" readonly>
                            <small class="text-muted">Terisi otomatis</small>
                        </div>

                        <div class="col-12 mb-3 mt-2">
                            <h6 class="fw-bold border-bottom pb-2">Berdasarkan Pendidikan</h6>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label">SD</label>
                            <input type="number" name="sd_sederajat" class="form-control" min="0" value="{{ $data->sd_sederajat }}">
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label">SMP</label>
                            <input type="number" name="smp_sederajat" class="form-control" min="0" value="{{ $data->smp_sederajat }}">
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label">SMA</label>
                            <input type="number" name="sma_sederajat" class="form-control" min="0" value="{{ $data->sma_sederajat }}">
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Diploma</label>
                            <input type="number" name="diploma" class="form-control" min="0" value="{{ $data->diploma }}">
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label class="form-label">Sarjana</label>
                            <input type="number" name="sarjana" class="form-control" min="0" value="{{ $data->sarjana }}">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputL = document.getElementById('laki_laki');
        const inputP = document.getElementById('perempuan');
        const inputTotal = document.getElementById('total_pencaker');

        function calcTotal() {
            const l = parseInt(inputL.value) || 0;
            const p = parseInt(inputP.value) || 0;
            inputTotal.value = l + p;
        }

        inputL.addEventListener('input', calcTotal);
        inputP.addEventListener('input', calcTotal);
    });
</script>
@endpush
@endif
@endsection
