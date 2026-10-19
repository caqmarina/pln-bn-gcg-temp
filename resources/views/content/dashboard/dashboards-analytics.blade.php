@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Arahan')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Total Arahan</span>
        <h3 class="card-title mb-2">{{ $totalArahan }}</h3>
        <small class="text-muted">Judul arahan terdaftar</small>
    </div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Total Detail</span>
        <h3 class="card-title mb-2">{{ $totalDetail }}</h3>
        <small class="text-muted">Seluruh tindak lanjut</small>
    </div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Detail Selesai</span>
        <h3 class="card-title mb-2">{{ $totalSelesai }}</h3>
        <small class="text-muted">Selesai atau berkelanjutan</small>
    </div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Progress Keseluruhan</span>
        <h3 class="card-title mb-2">{{ number_format($averageProgress, 2, ',', '.') }}%</h3>
        <small class="text-muted">Berdasarkan seluruh detail</small>
    </div></div></div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-3">
        <div>
            <h5 class="mb-1">Arahan</h5>
            <small class="text-muted">Ringkasan progress dan penyelesaian Arahan</small>
        </div>
        <a href="{{ route('arahan.index') }}" class="btn btn-outline-primary text-nowrap">Lihat Semua Arahan</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th style="min-width: 180px;">Progress</th>
                    <th>Status</th>
                    <th>Selesai</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arahan as $item)
                    <tr>
                        <td>
                            <a href="{{ route('arahan_detail.index', $item->id) }}" class="fw-medium">{{ $item->judul_arahan }}</a>
                            <div class="small text-muted">{{ $item->tanggal_arahan?->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ number_format($item->dashboard_progress, 2, ',', '.') }}%</span>
                                <span>{{ $item->dashboard_selesai }}/{{ $item->dashboard_total }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $item->dashboard_progress }}%;" aria-valuenow="{{ $item->dashboard_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = match ($item->dashboard_status) {
                                    'Selesai' => 'success',
                                    'Progress' => 'warning',
                                    'Belum Ada Detail' => 'secondary',
                                    default => 'danger',
                                };
                            @endphp
                            <span class="badge bg-label-{{ $statusClass }}">{{ $item->dashboard_status }}</span>
                        </td>
                        <td>{{ $item->dashboard_selesai }}</td>
                        <td>{{ $item->dashboard_total }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Belum ada data Arahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
