@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Arahan')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <div class="btn-group">
        <button id="dashboard-report-export" type="button" class="btn btn-outline-success">
            <i class="bx bx-download me-1"></i> Export Excel
        </button>
        <a href="{{ route('dashboard.export-pdf', request()->query()) }}" target="_blank" rel="noopener" class="btn btn-outline-danger">
            <i class="bx bx-file me-1"></i> Export PDF
        </a>
    </div>
</div>

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

<div class="row g-4 mb-4 mt-1">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">Status Arahan</h5>
                <small class="text-muted">Distribusi status seluruh Arahan</small>
            </div>
            <div class="card-body">
                @if($totalArahan > 0)
                    <div id="arahanStatusChart" aria-label="Donut chart status Arahan"></div>
                @else
                    <div class="text-center text-muted py-5">Belum ada data Arahan untuk ditampilkan.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">Progress Arahan</h5>
                <small class="text-muted">Progress 8 Arahan terbaru</small>
            </div>
            <div class="card-body">
                @if($arahanProgress->isNotEmpty())
                    <div id="arahanProgressChart" aria-label="Bar chart progress Arahan"></div>
                @else
                    <div class="text-center text-muted py-5">Belum ada data Arahan untuk ditampilkan.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h5 class="mb-1">Ringkasan Arahan</h5>
                <small class="text-muted">Ringkasan progress dan penyelesaian Arahan</small>
            </div>
            <a href="{{ route('arahan.index') }}" class="btn btn-outline-primary text-nowrap">Lihat Semua Arahan</a>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-end mt-3">
            <div class="col-12 col-md-3">
                <label for="dashboard-arahan-status" class="form-label mb-1 small">Status</label>
                <select id="dashboard-arahan-status" name="arahan_status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach($arahanStatuses as $status)
                        <option value="{{ $status }}" @selected($selectedArahanStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary">Terapkan</button>
                @if($selectedArahanStatus !== '')
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
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
                @forelse($arahan as $index => $item)
                    <tr class="dashboard-arahan-row" @if($index >= 5) hidden @endif>
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
    @if($arahan->count() > 5)
        <div class="card-footer text-center">
            <button type="button" class="btn btn-outline-primary dashboard-toggle-button" data-target="arahan">
                Lihat lebih banyak
            </button>
        </div>
    @endif
</div>

<div class="row g-4 mb-4 mt-1">
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Total Assessment</span>
        <h3 class="card-title mb-2">{{ $totalAssessments }}</h3>
        <small class="text-muted">Assessment terdaftar</small>
    </div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Rata-rata Skor</span>
        <h3 class="card-title mb-2">{{ number_format($averageAssessmentScore, 2, ',', '.') }}</h3>
        <small class="text-muted">Skor seluruh assessment</small>
    </div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Total Pertanyaan</span>
        <h3 class="card-title mb-2">{{ $totalAssessmentQuestions }}</h3>
        <small class="text-muted">Detail indikator</small>
    </div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body">
        <span class="fw-medium d-block mb-1">Terpenuhi</span>
        <h3 class="card-title mb-2">{{ $totalAssessmentFulfilled }}</h3>
        <small class="text-muted">Ya atau N/A</small>
    </div></div></div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">Pemenuhan Indikator</h5>
                <small class="text-muted">Status seluruh indikator assessment</small>
            </div>
            <div class="card-body">
                @if(array_sum($fulfillmentCounts) > 0)
                    <div id="fulfillmentDonutChart" aria-label="Donut chart status pemenuhan indikator"></div>
                @else
                    <div class="text-center text-muted py-5">Belum ada detail indikator untuk ditampilkan.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-1">Skor per Kategori</h5>
                <small class="text-muted">Rata-rata skor kategori dari assessment yang tersedia</small>
            </div>
            <div class="card-body">
                @if($categoryScores->isNotEmpty())
                    <div id="categoryScoreChart" aria-label="Bar chart skor per kategori"></div>
                @else
                    <div class="text-center text-muted py-5">Belum ada detail kategori untuk ditampilkan.</div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($hasAssessmentTrend)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-1">Tren Skor Assessment</h5>
            <small class="text-muted">Rata-rata skor berdasarkan tahun assessment</small>
        </div>
        <div class="card-body">
            <div id="assessmentTrendChart" aria-label="Grafik tren skor assessment per tahun"></div>
        </div>
    </div>
@elseif($selectedAssessmentBookYear !== '' || $selectedAssessmentYear !== '')
    <div class="alert alert-info mb-4" role="status">
        Grafik tren tidak ditampilkan saat filter tahun dipilih. Hapus filter tahun untuk melihat perbandingan antarperiode.
    </div>
@else
    <div class="alert alert-info mb-4" role="status">
        Grafik tren akan ditampilkan setelah tersedia assessment pada minimal dua tahun berbeda.
    </div>
@endif

<div class="card mt-4">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between gap-3">
        <div>
            <h5 class="mb-1">Ringkasan Assessment</h5>
            <small class="text-muted">Ringkasan status pemenuhan dan skor assessment</small>
        </div>
        <a href="{{ route('assessment.index') }}" class="btn btn-outline-primary text-nowrap">Lihat Semua Assessment</a>
        </div>

        <form id="dashboard-assessment-filter-form" method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-end mt-3">
            <div class="col-12 col-md-2">
                <label for="dashboard-assessment-book-year" class="form-label mb-1 small">Tahun buku</label>
                <select id="dashboard-assessment-book-year" name="assessment_book_year" class="form-select">
                    <option value="">Semua tahun</option>
                    @foreach($assessmentBookYears as $year)
                        <option value="{{ $year }}" @selected($selectedAssessmentBookYear === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label for="dashboard-assessment-year" class="form-label mb-1 small">Tahun assessment</label>
                <select id="dashboard-assessment-year" name="assessment_year" class="form-select">
                    <option value="">Semua tahun</option>
                    @foreach($assessmentYears as $year)
                        <option value="{{ $year }}" @selected($selectedAssessmentYear === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="dashboard-assessment-framework" class="form-label mb-1 small">Framework</label>
                <select id="dashboard-assessment-framework" name="assessment_framework" class="form-select">
                    <option value="">Semua framework</option>
                    @foreach($assessmentFrameworks as $framework)
                        <option value="{{ $framework->id }}" @selected($selectedAssessmentFramework === (string) $framework->id)>{{ $framework->nama_framework }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="dashboard-assessment-type" class="form-label mb-1 small">Jenis assessment</label>
                <select id="dashboard-assessment-type" name="assessment_type" class="form-select">
                    <option value="">Semua jenis</option>
                    @foreach($assessmentTypes as $type)
                        <option value="{{ $type }}" @selected($selectedAssessmentType === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <button id="dashboard-assessment-filter-submit" type="submit" class="btn btn-primary">Terapkan</button>
                @if($selectedAssessmentBookYear !== '' || $selectedAssessmentYear !== '' || $selectedAssessmentFramework !== '' || $selectedAssessmentType !== '')
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary dashboard-assessment-filter-reset">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Framework</th>
                    <th>Tahun Assessment</th>
                    <th>Tahun Buku</th>
                    <th>Jenis</th>
                    <th style="min-width: 180px;">Progress</th>
                    <th>Status</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $index => $item)
                    <tr class="dashboard-assessment-row" @if($index >= 5) hidden @endif>
                        <td>
                            <a href="{{ route('assessment_detail.index', $item->id) }}" class="fw-medium">{{ $item->framework?->nama_framework ?? 'Framework tidak tersedia' }}</a>
                        </td>
                        <td>{{ $item->tahun_asesmen }}</td>
                        <td>{{ $item->tahun_buku }}</td>
                        <td>{{ $item->jenis_asesmen }}</td>
                        <td>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ number_format($item->dashboard_progress, 2, ',', '.') }}%</span>
                                <span>{{ $item->dashboard_ya + $item->dashboard_na }}/{{ $item->dashboard_total }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $item->dashboard_progress }}%;" aria-valuenow="{{ $item->dashboard_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            @php
                                $assessmentStatusClass = match ($item->dashboard_status) {
                                    'Selesai' => 'success',
                                    'Progress' => 'warning',
                                    'Belum Ada Detail' => 'secondary',
                                    default => 'danger',
                                };
                            @endphp
                            <span class="badge bg-label-{{ $assessmentStatusClass }}">{{ $item->dashboard_status }}</span>
                        </td>
                        <td>{{ number_format((float) $item->skor, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Belum ada data Assessment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assessments->count() > 5)
        <div class="card-footer text-center">
            <button type="button" class="btn btn-outline-primary dashboard-toggle-button" data-target="assessment">
                Lihat lebih banyak
            </button>
        </div>
    @endif
</div>
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js'])
@endsection

<script type="application/json" id="dashboard-report-export-data">@json($dashboardExportData)</script>

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dashboardScrollKey = 'dashboard-assessment-scroll-y';
        const savedScrollPosition = window.sessionStorage.getItem(dashboardScrollKey);
        window.sessionStorage.removeItem(dashboardScrollKey);

        if (savedScrollPosition !== null) {
            window.requestAnimationFrame(function () {
                window.setTimeout(function () {
                    window.scrollTo({ top: Number(savedScrollPosition), left: 0, behavior: 'auto' });
                }, 100);
            });
        }

        const assessmentFilterForm = document.getElementById('dashboard-assessment-filter-form');
        const assessmentFilterSubmit = document.getElementById('dashboard-assessment-filter-submit');
        const rememberDashboardScroll = function () {
            window.sessionStorage.setItem(dashboardScrollKey, String(window.scrollY));
        };

        assessmentFilterForm?.addEventListener('submit', function () {
            rememberDashboardScroll();
            if (assessmentFilterSubmit) {
                assessmentFilterSubmit.disabled = true;
                assessmentFilterSubmit.textContent = 'Memuat...';
            }
        });
        document.querySelector('.dashboard-assessment-filter-reset')?.addEventListener('click', rememberDashboardScroll);

        document.querySelectorAll('.dashboard-toggle-button').forEach(function (button) {
            button.addEventListener('click', function () {
                const target = button.dataset.target;
                const rows = document.querySelectorAll('.dashboard-' + target + '-row');
                const showingMore = button.dataset.expanded === 'true';

                rows.forEach(function (row, index) {
                    if (index >= 5) {
                        row.hidden = showingMore;
                    }
                });

                button.dataset.expanded = showingMore ? 'false' : 'true';
                button.textContent = showingMore ? 'Lihat lebih banyak' : 'Tampilkan lebih sedikit';
            });
        });

        const fulfillmentCounts = @json($fulfillmentCounts);
        const categoryScores = @json($categoryScores);
        const assessmentTrend = @json($assessmentTrend);
        const arahanStatusCounts = @json($arahanStatusCounts);
        const arahanProgress = @json($arahanProgress);
        const chartColors = window.config?.colors || {};
        const mutedColor = chartColors.textMuted || '#697a8d';
        const cardColor = chartColors.cardColor || '#fff';

        const donutElement = document.querySelector('#fulfillmentDonutChart');
        if (donutElement && window.ApexCharts) {
            new ApexCharts(donutElement, {
                chart: { type: 'donut', height: 190, toolbar: { show: false } },
                series: [fulfillmentCounts['Ya'], fulfillmentCounts['Tidak'], fulfillmentCounts['N/A']],
                labels: ['Ya', 'Tidak', 'N/A'],
                colors: ['#71dd37', '#ff3e1d', '#8592a3'],
                stroke: { colors: [cardColor], width: 4 },
                legend: { position: 'bottom', fontSize: '13px', labels: { colors: mutedColor } },
                dataLabels: { enabled: true, formatter: value => Math.round(value) + '%' },
                plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: {
                    show: true, label: 'Indikator', color: mutedColor,
                    formatter: () => fulfillmentCounts['Ya'] + fulfillmentCounts['Tidak'] + fulfillmentCounts['N/A']
                } } } } },
                tooltip: { y: { formatter: value => value + ' indikator' } }
            }).render();
        }

        const categoryElement = document.querySelector('#categoryScoreChart');
        if (categoryElement && window.ApexCharts) {
            new ApexCharts(categoryElement, {
                chart: { type: 'bar', height: 195, toolbar: { show: false } },
                series: [{ name: 'Skor', data: categoryScores.map(item => item.score) }],
                colors: [chartColors.primary || '#696cff'],
                plotOptions: { bar: { borderRadius: 5, columnWidth: '48%', distributed: false } },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: categoryScores.map(item => item.name),
                    labels: { style: { colors: mutedColor, fontSize: '12px' }, trim: true, rotate: -35 }
                },
                yaxis: {
                    min: 0,
                    labels: { style: { colors: mutedColor }, formatter: value => value.toLocaleString('id-ID') }
                },
                grid: { borderColor: chartColors.borderColor || '#d9dee3', strokeDashArray: 5 },
                tooltip: { y: { formatter: (value, context) => {
                    const item = categoryScores[context.dataPointIndex];
                    return value.toLocaleString('id-ID') + ' dari ' + item.maximum.toLocaleString('id-ID');
                } } }
            }).render();
        }

        const trendElement = document.querySelector('#assessmentTrendChart');
        if (trendElement && window.ApexCharts) {
            new ApexCharts(trendElement, {
                chart: { type: 'line', height: 180, toolbar: { show: false } },
                series: [{ name: 'Rata-rata skor', data: assessmentTrend.map(item => item.score) }],
                colors: [chartColors.info || '#03c3ec'],
                stroke: { curve: 'smooth', width: 3 },
                markers: { size: 5 },
                dataLabels: { enabled: false },
                xaxis: { categories: assessmentTrend.map(item => item.year), labels: { style: { colors: mutedColor } } },
                yaxis: { min: 0, labels: { style: { colors: mutedColor } } },
                grid: { borderColor: chartColors.borderColor || '#d9dee3', strokeDashArray: 5 },
                tooltip: { y: { formatter: value => value.toLocaleString('id-ID') } }
            }).render();
        }

        const arahanStatusElement = document.querySelector('#arahanStatusChart');
        if (arahanStatusElement && window.ApexCharts) {
            new ApexCharts(arahanStatusElement, {
                chart: { type: 'donut', height: 190, toolbar: { show: false } },
                series: Object.values(arahanStatusCounts),
                labels: Object.keys(arahanStatusCounts),
                colors: ['#71dd37', '#ffab00', '#ff3e1d', '#8592a3'],
                stroke: { colors: [cardColor], width: 4 },
                legend: { position: 'bottom', fontSize: '13px', labels: { colors: mutedColor } },
                dataLabels: { enabled: true, formatter: value => Math.round(value) + '%' },
                plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: {
                    show: true, label: 'Arahan', color: mutedColor,
                    formatter: () => Object.values(arahanStatusCounts).reduce((sum, value) => sum + value, 0)
                } } } } },
                tooltip: { y: { formatter: value => value + ' Arahan' } }
            }).render();
        }

        const arahanProgressElement = document.querySelector('#arahanProgressChart');
        if (arahanProgressElement && window.ApexCharts) {
            new ApexCharts(arahanProgressElement, {
                chart: { type: 'bar', height: 195, toolbar: { show: false } },
                series: [{ name: 'Progress', data: arahanProgress.map(item => item.progress) }],
                colors: [chartColors.success || '#71dd37'],
                plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '55%' } },
                dataLabels: { enabled: true, formatter: value => value.toLocaleString('id-ID') + '%' },
                xaxis: {
                    categories: arahanProgress.map(item => item.title),
                    min: 0,
                    max: 100,
                    tickAmount: 5,
                    labels: { style: { colors: mutedColor }, formatter: value => value + '%' }
                },
                yaxis: {
                    labels: { style: { colors: mutedColor, fontSize: '11px' }, maxWidth: 170 }
                },
                grid: { borderColor: chartColors.borderColor || '#d9dee3', strokeDashArray: 5 },
                tooltip: { y: { formatter: value => value.toLocaleString('id-ID') + '%' } }
            }).render();
        }
    });
</script>
@vite(['resources/assets/js/dashboard-report-export.js'])
@endsection
