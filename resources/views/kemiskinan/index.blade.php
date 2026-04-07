@extends('layouts.app')

@section('title', 'Portal Kemiskinan')
@section('page-title', 'Overview Kemiskinan')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <div class="stat-card kemiskinan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalMiskin) }}</div>
                        <div class="stat-label">Total Keluarga Miskin (Desil 1-5)</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="stat-card kemiskinan">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value">{{ number_format($totalPengangguran) }}</div>
                        <div class="stat-label">Total Pengangguran</div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4 col-lg-2">
            <a href="{{ route('kemiskinan.desil') }}" class="data-card text-decoration-none d-block h-100 p-3 text-center">
                <i class="bi bi-bar-chart-line-fill text-primary" style="font-size: 2rem;"></i>
                <div class="mt-2 fw-semibold">Desil Kemiskinan</div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <a href="{{ route('kemiskinan.pengangguran') }}"
                class="data-card text-decoration-none d-block h-100 p-3 text-center">
                <i class="bi bi-person-x-fill text-danger" style="font-size: 2rem;"></i>
                <div class="mt-2 fw-semibold">Pengangguran</div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <a href="{{ route('kemiskinan.pkh') }}" class="data-card text-decoration-none d-block h-100 p-3 text-center">
                <i class="bi bi-house-heart-fill text-success" style="font-size: 2rem;"></i>
                <div class="mt-2 fw-semibold">Realisasi PKH</div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <a href="{{ route('kemiskinan.sembako') }}"
                class="data-card text-decoration-none d-block h-100 p-3 text-center">
                <i class="bi bi-basket-fill text-warning" style="font-size: 2rem;"></i>
                <div class="mt-2 fw-semibold">Realisasi Sembako</div>
            </a>
        </div>
        <div class="col-12 col-md-4 col-lg-2">
            <a href="{{ route('kemiskinan.ak1') }}" class="data-card text-decoration-none d-block h-100 p-3 text-center">
                <i class="bi bi-file-earmark-person-fill text-info" style="font-size: 2rem;"></i>
                <div class="mt-2 fw-semibold">Data AK-1</div>
            </a>
        </div>
    </div>

    <!-- Filter Tahun -->
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

    <!-- Desil Kemiskinan Table -->
    <div class="data-card mb-4">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-bar-chart-line-fill me-2"></i>Desil Kemiskinan {{ $tahun }}
            </h5>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th class="text-end">Desil 1</th>
                            <th class="text-end">Desil 2</th>
                            <th class="text-end">Desil 3</th>
                            <th class="text-end">Desil 4</th>
                            <th class="text-end">Desil 5</th>
                            <th class="text-end">Total Miskin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($desilKemiskinan as $data)
                            <tr>
                                <td><strong>{{ $data->desa->nama }}</strong></td>
                                <td class="text-end"><span class="badge bg-danger">{{ $data->desil_1 }}</span></td>
                                <td class="text-end"><span class="badge bg-warning text-dark">{{ $data->desil_2 }}</span></td>
                                <td class="text-end"><span class="badge bg-info">{{ $data->desil_3 }}</span></td>
                                <td class="text-end"><span class="badge bg-secondary">{{ $data->desil_4 }}</span></td>
                                <td class="text-end">{{ $data->desil_5 }}</td>
                                <td class="text-end"><strong>{{ $data->total_miskin }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($desilKemiskinan->count() > 0)
                    <tfoot class="table-light fw-bold border-top">
                        <tr>
                            <td>Total Keseluruhan</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_1')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_2')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_3')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_4')) }}</td>
                            <td class="text-end">{{ number_format($desilKemiskinan->sum('desil_5')) }}</td>
                            <td class="text-end text-primary fs-5">{{ number_format($desilKemiskinan->sum('total_miskin')) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Pengangguran Table -->
    <div class="data-card">
        <div class="data-card-header">
            <h5 class="data-card-title">
                <i class="bi bi-person-x-fill me-2"></i>Data Pengangguran {{ $tahun }}
            </h5>
        </div>
        <div class="data-card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Desa</th>
                            <th class="text-end">Pengangguran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengangguran as $data)
                            <tr>
                                <td><strong>{{ $data->desa->nama }}</strong></td>
                                <td class="text-end"><span class="badge bg-danger">{{ number_format($data->jumlah_pengangguran) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection