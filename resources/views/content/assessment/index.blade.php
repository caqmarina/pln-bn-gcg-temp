@extends('layouts/contentNavbarLayout')

@section('title', 'Assessment')

@section('content')

<div class="card">

    <div class="card-header">

        <h5 class="mb-0">
            Assessment
        </h5>

        <div class="d-flex flex-nowrap align-items-center gap-2 mt-3">
            <select id="assessment-type-filter" class="form-select w-auto" style="width: 160px;" aria-label="Filter jenis asesmen">
                <option value="">Semua jenis</option>
                <option value="Self Assessment">Self Assessment</option>
                <option value="Asesor">Asesor</option>
            </select>

            <select id="assessment-year-filter" class="form-select w-auto" style="width: 130px;" aria-label="Filter tahun asesmen">
                <option value="">Tahun asesmen</option>
                @foreach($data->pluck('tahun_asesmen')->filter()->unique()->sortDesc() as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <select id="assessment-book-year-filter" class="form-select w-auto" style="width: 130px;" aria-label="Filter tahun buku">
                <option value="">Tahun buku</option>
                @foreach($data->pluck('tahun_buku')->filter()->unique()->sortDesc() as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <select id="assessment-framework-filter" class="form-select w-auto" style="width: 170px;" aria-label="Filter framework">
                <option value="">Semua framework</option>
                @foreach($data->pluck('framework.nama_framework')->filter()->unique()->sort() as $framework)
                    <option value="{{ $framework }}">{{ $framework }}</option>
                @endforeach
            </select>

            <button id="assessment-filter-clear" type="button" class="btn btn-outline-secondary text-nowrap" hidden>
                Reset
            </button>

            <a href="{{ route('assessment.create') }}" class="btn btn-primary text-nowrap">
                Tambah Assessment
            </a>

        </div>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis Asesmen</th>
                    <th>Tahun Asesmen</th>
                    <th>Tahun Buku</th>
                    <th>Framework</th>
                    <th>Skor</th>
                    <th width="250">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $row)

                <tr
                    data-assessment-search="{{ strtolower($row->jenis_asesmen . ' ' . $row->tahun_asesmen . ' ' . $row->tahun_buku . ' ' . ($row->framework->nama_framework ?? '') . ' ' . $row->skor) }}"
                    data-assessment-type="{{ $row->jenis_asesmen }}"
                    data-assessment-year="{{ $row->tahun_asesmen }}"
                    data-assessment-book-year="{{ $row->tahun_buku }}"
                    data-assessment-framework="{{ $row->framework->nama_framework ?? '' }}">

                    <td>{{ $row->id }}</td>

                    <td>
                        {{ $row->jenis_asesmen }}
                    </td>

                    <td>
                        {{ $row->tahun_asesmen }}
                    </td>

                    <td>
                        {{ $row->tahun_buku }}
                    </td>

                    <td>
                        {{ $row->framework->nama_framework }}
                    </td>

                    <td>
                        {{ $row->skor }}
                    </td>

                    <td>

                        <a href="{{ route('assessment_detail.index', $row->id) }}"
                           class="btn btn-info btn-sm">
                            Detail
                        </a>

                        <a href="{{ route('assessment.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('assessment.destroy', $row->id) }}"
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

                <tr id="assessment-empty-row">

                    <td colspan="7" class="text-center">
                        <span id="assessment-empty-message">Data assessment belum tersedia</span>
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('arahan-search');
        const searchForm = searchInput?.closest('form');
        const rows = Array.from(document.querySelectorAll('[data-assessment-search]'));
        const emptyRow = document.getElementById('assessment-empty-row');
        const typeFilter = document.getElementById('assessment-type-filter');
        const yearFilter = document.getElementById('assessment-year-filter');
        const bookYearFilter = document.getElementById('assessment-book-year-filter');
        const frameworkFilter = document.getElementById('assessment-framework-filter');
        const clearFilter = document.getElementById('assessment-filter-clear');
        const emptyMessage = document.getElementById('assessment-empty-message');

        searchForm?.addEventListener('submit', function (event) {
            event.preventDefault();
        });

        if (!searchInput || rows.length === 0) {
            return;
        }

        const filterRows = function () {
            const keyword = searchInput.value.trim().toLocaleLowerCase();
            const type = typeFilter?.value || '';
            const year = yearFilter?.value || '';
            const bookYear = bookYearFilter?.value || '';
            const framework = frameworkFilter?.value || '';
            let visibleRows = 0;

            rows.forEach(function (row) {
                const matchesSearch = keyword === '' || row.dataset.assessmentSearch.includes(keyword);
                const matchesType = type === '' || row.dataset.assessmentType === type;
                const matchesYear = year === '' || row.dataset.assessmentYear === year;
                const matchesBookYear = bookYear === '' || row.dataset.assessmentBookYear === bookYear;
                const matchesFramework = framework === '' || row.dataset.assessmentFramework === framework;
                const matches = matchesSearch && matchesType && matchesYear && matchesBookYear && matchesFramework;
                row.hidden = !matches;
                visibleRows += matches ? 1 : 0;
            });

            if (emptyRow) {
                emptyRow.hidden = visibleRows !== 0;
            }
            if (emptyMessage && visibleRows === 0) {
                emptyMessage.textContent = 'Tidak ada assessment yang sesuai filter';
            }
            if (clearFilter) {
                clearFilter.hidden = keyword === '' && type === '' && year === '' && bookYear === '' && framework === '';
            }
        };

        searchInput.addEventListener('input', filterRows);
        typeFilter?.addEventListener('change', filterRows);
        yearFilter?.addEventListener('change', filterRows);
        bookYearFilter?.addEventListener('change', filterRows);
        frameworkFilter?.addEventListener('change', filterRows);
        clearFilter?.addEventListener('click', function () {
            searchInput.value = '';
            if (typeFilter) typeFilter.value = '';
            if (yearFilter) yearFilter.value = '';
            if (bookYearFilter) bookYearFilter.value = '';
            if (frameworkFilter) frameworkFilter.value = '';
            filterRows();
        });
        filterRows();
    });
</script>
@endsection
