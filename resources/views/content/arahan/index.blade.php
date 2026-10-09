@extends('layouts/contentNavbarLayout')

@section('title', 'Arahan')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->has('import'))
    <div class="alert alert-danger" role="alert">{{ $errors->first('import') }}</div>
@endif

<div class="card">

    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">

        <h5 class="mb-0">
            Arahan
        </h5>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <div>
                <label for="arahan-progress-filter" class="visually-hidden">Filter progress</label>
                <select id="arahan-progress-filter" class="form-select" aria-label="Filter progress">
                    <option value="">Semua progress</option>
                    <option value="not-started">Not started</option>
                    <option value="in-progress">In progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div>
                    <label for="arahan-date-from" class="visually-hidden">Tanggal mulai</label>
                    <input id="arahan-date-from" type="date" class="form-control" aria-label="Tanggal mulai">
                </div>
                <span class="text-muted">–</span>
                <div>
                    <label for="arahan-date-to" class="visually-hidden">Tanggal selesai</label>
                    <input id="arahan-date-to" type="date" class="form-control" aria-label="Tanggal selesai">
                </div>
                <button id="arahan-date-clear" type="button" class="btn btn-outline-secondary text-nowrap" hidden>Reset</button>
            </div>

            <a href="{{ route('arahan.create') }}" class="btn btn-primary text-nowrap">
                Tambah Arahan
            </a>

            <button type="button" class="btn btn-outline-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#importArahanModal">
                <i class="bx bx-upload me-1"></i> Import Excel
            </button>
        </div>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Judul Arahan</th>
                    <th>Tanggal Arahan</th>
                    <th>Progress</th>
                    <th width="250">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $row)

                <tr
                    data-arahan-title="{{ strtolower($row->judul_arahan) }}"
                    data-arahan-progress="{{ $row->progress == 0 ? 'not-started' : ($row->progress == 100 ? 'completed' : 'in-progress') }}"
                    data-arahan-date="{{ $row->tanggal_arahan }}">

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $row->judul_arahan }}
                    </td>

                    <td>
                        {{ $row->tanggal_arahan }}
                    </td>

                    <td>

                        @if($row->progress == 100)

                            <span class="badge bg-success">
                                {{ number_format($row->progress,0) }}%
                            </span>

                        @elseif($row->progress > 0)

                            <span class="badge bg-warning">
                                {{ number_format($row->progress,0) }}%
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                0%
                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('arahan_detail.index', $row->id) }}"
                            class="btn btn-info btn-sm">
                            Detail
                        </a>

                        <a href="{{ route('arahan.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('arahan.destroy', $row->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus data?')">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr id="arahan-empty-row">

                    <td colspan="5"
                        class="text-center">

                        <span id="arahan-empty-message">
                            @if($search !== '')
                                Arahan dengan kata kunci "{{ $search }}" tidak ditemukan
                            @else
                                Data arahan belum tersedia
                            @endif
                        </span>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="modal fade" id="importArahanModal" tabindex="-1" aria-labelledby="importArahanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="arahan-import-form" action="{{ route('arahan.import') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="importArahanModalLabel">Import Arahan dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Pilih workbook ACGS. Baris yang memiliki rekomendasi akan ditambahkan sebagai detail arahan.</p>

                <div class="mb-3">
                    <label for="arahan-import-title" class="form-label">Judul Arahan</label>
                    <input id="arahan-import-title" name="judul_arahan" type="text" class="form-control" value="Import ACGS" required maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="arahan-import-date" class="form-label">Tanggal Arahan</label>
                    <input id="arahan-import-date" name="tanggal_arahan" type="date" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                <div>
                    <label for="arahan-import-file" class="form-label">File Excel</label>
                    <input id="arahan-import-file" name="file" type="file" class="form-control" accept=".xlsx,.xls" required>
                    <div id="arahan-import-feedback" class="form-text">Format yang didukung: .xlsx atau .xls.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                <button id="arahan-import-submit" type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('page-script')
@vite(['resources/assets/js/arahan-import.js'])
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('arahan-search');
        const searchForm = searchInput?.closest('form');
        const rows = Array.from(document.querySelectorAll('[data-arahan-title]'));
        const emptyRow = document.getElementById('arahan-empty-row');
        const progressFilter = document.getElementById('arahan-progress-filter');
        const dateFrom = document.getElementById('arahan-date-from');
        const dateTo = document.getElementById('arahan-date-to');
        const dateClear = document.getElementById('arahan-date-clear');
        const emptyMessage = document.getElementById('arahan-empty-message');

        if (!searchInput || rows.length === 0) {
            return;
        }

        const filterRows = function () {
            const keyword = searchInput.value.trim().toLocaleLowerCase();
            const progress = progressFilter?.value || '';
            const from = dateFrom?.value || '';
            const to = dateTo?.value || '';
            let visibleRows = 0;

            rows.forEach(function (row) {
                const matchesSearch = keyword === '' || row.dataset.arahanTitle.includes(keyword);
                const matchesProgress = progress === '' || row.dataset.arahanProgress === progress;
                const matchesDateFilter = (from === '' || row.dataset.arahanDate >= from)
                    && (to === '' || row.dataset.arahanDate <= to);
                const matches = matchesSearch && matchesProgress && matchesDateFilter;
                row.hidden = !matches;
                visibleRows += matches ? 1 : 0;
            });

            if (emptyRow) {
                emptyRow.hidden = visibleRows !== 0;
            }
            if (emptyMessage && visibleRows === 0) {
                emptyMessage.textContent = 'Tidak ada arahan yang sesuai filter';
            }
            if (dateClear) {
                dateClear.hidden = from === '' && to === '';
            }
        };

        searchInput.addEventListener('input', filterRows);
        progressFilter?.addEventListener('change', filterRows);
        dateFrom?.addEventListener('input', filterRows);
        dateFrom?.addEventListener('change', filterRows);
        dateTo?.addEventListener('input', filterRows);
        dateTo?.addEventListener('change', filterRows);
        dateClear?.addEventListener('click', function () {
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';
            filterRows();
        });
        searchForm?.addEventListener('submit', function (event) {
            event.preventDefault();
        });
        filterRows();
    });
</script>
@endsection
