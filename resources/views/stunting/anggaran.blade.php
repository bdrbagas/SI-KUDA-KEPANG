@extends('layouts.app')

@section('title', 'Anggaran Stunting')
@section('page-title', 'Rincian Anggaran Stunting')

@section('content')
    <!-- Summary per Sumber -->
    <div class="row g-4 mb-4">
        @foreach(['desa' => ['Dana Desa', 'primary'], 'puskesmas' => ['Puskesmas', 'success'], 'csr' => ['CSR', 'info']] as $key => $info)
            @php $summary = $summaryPerSumber->firstWhere('sumber', $key); @endphp
            <div class="col-12 col-md-4">
                <div class="stat-card stunting">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-value">Rp {{ number_format($summary->total_pagu ?? 0, 0, ',', '.') }}</div>
                            <div class="stat-label">Anggaran {{ $info[0] }}</div>
                            <small class="text-muted">Realisasi: Rp
                                {{ number_format($summary->total_realisasi ?? 0, 0, ',', '.') }}</small>
                        </div>
                        <div class="icon-wrapper">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filter -->
    <div class="data-card mb-4">
        <div class="data-card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-auto">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label">Sumber</label>
                    <select name="sumber" class="form-select">
                        <option value="">Semua Sumber</option>
                        <option value="desa" {{ $sumber == 'desa' ? 'selected' : '' }}>Dana Desa</option>
                        <option value="puskesmas" {{ $sumber == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                        <option value="csr" {{ $sumber == 'csr' ? 'selected' : '' }}>CSR</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Anggaran -->
    <div class="data-card">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-cash-stack me-2"></i>Data Anggaran Stunting {{ $tahun }}
            </h5>
            <button class="btn btn-primary-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#modalAnggaran">
                <i class="bi bi-plus-lg me-1"></i>Tambah Anggaran
            </button>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th>Sumber</th>
                            <th>Program</th>
                            <th class="text-end">Pagu</th>
                            <th class="text-end">Realisasi</th>
                            <th class="text-end">%</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggarans as $anggaran)
                            <tr>
                                <td><strong>{{ $anggaran->desa->nama }}</strong></td>
                                <td>
                                    @if($anggaran->sumber == 'desa')
                                        <span class="badge bg-primary">Dana Desa</span>
                                    @elseif($anggaran->sumber == 'puskesmas')
                                        <span class="badge bg-success">Puskesmas</span>
                                    @else
                                        <span class="badge bg-info">CSR</span>
                                    @endif
                                </td>
                                <td>{{ $anggaran->program }}</td>
                                <td class="text-end">Rp {{ number_format($anggaran->pagu, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($anggaran->realisasi, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <span
                                        class="badge {{ $anggaran->persentase_realisasi >= 80 ? 'bg-success' : ($anggaran->persentase_realisasi >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ number_format($anggaran->persentase_realisasi, 1) }}%
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('stunting.anggaran.destroy', $anggaran) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data anggaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Anggaran -->
    <div class="modal fade" id="modalAnggaran" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('stunting.anggaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Anggaran Stunting</h5>
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
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Sumber</label>
                                <select name="sumber" class="form-select" required>
                                    <option value="desa">Dana Desa</option>
                                    <option value="puskesmas">Puskesmas</option>
                                    <option value="csr">CSR</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program</label>
                            <input type="text" name="program" class="form-control" required placeholder="Nama program">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Pagu Anggaran</label>
                                <input type="number" name="pagu" class="form-control" required step="0.01" min="0">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Realisasi</label>
                                <input type="number" name="realisasi" class="form-control" required step="0.01" min="0">
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