@extends('layouts/contentNavbarLayout')

@section('title', 'Arahan Detail')

@section('content')

<div class="card">

    <div class="card-header d-flex flex-nowrap justify-content-between align-items-center gap-3">

        <div>

            <h5 class="mb-1">
                Detail Arahan
            </h5>

            <h5 class="mb-1">
                <strong>Judul :</strong> {{ $arahan->judul_arahan }}
            </h5>

        </div>

        <div class="d-flex flex-nowrap align-items-center justify-content-end gap-2 flex-grow-1">
            <select id="arahan-detail-status-filter" class="form-select w-auto" style="width: 180px;" aria-label="Filter status">
                <option value="">Semua status</option>
                <option value="Open">Open</option>
                <option value="Progress">Progress</option>
                <option value="Selesai">Selesai</option>
                <option value="Selesai Berkelanjutan">Selesai Berkelanjutan</option>
            </select>

            <select id="arahan-detail-level-filter" class="form-select w-auto" style="width: 150px;" aria-label="Filter level Excel">
                <option value="">Semua level</option>
                <option value="Level 1">Level 1</option>
                <option value="Level 2">Level 2</option>
            </select>

            <select id="arahan-detail-section-filter" class="form-select w-auto" style="width: 165px;" aria-label="Filter bagian Excel">
                <option value="">Semua bagian</option>
                @foreach($data->pluck('source_section')->filter()->unique()->sort() as $section)
                    <option value="{{ $section }}">{{ $section }}</option>
                @endforeach
            </select>

            <select id="arahan-detail-aspect-filter" class="form-select w-auto" style="width: 180px;" aria-label="Filter aspek">
                <option value="">Semua aspek</option>
                @foreach($data->pluck('aspek')->filter()->unique()->sort() as $aspek)
                    <option value="{{ $aspek }}">{{ $aspek }}</option>
                @endforeach
            </select>

            <select id="arahan-detail-evidence-filter" class="form-select w-auto" style="width: 180px;" aria-label="Filter eviden">
                <option value="">Semua eviden</option>
                <option value="available">Ada eviden</option>
                <option value="missing">Belum ada eviden</option>
            </select>

            <button id="arahan-detail-filter-clear" type="button" class="btn btn-outline-secondary text-nowrap invisible">
                Reset
            </button>

            <a href="{{ route('arahan_detail.create', $arahan->id) }}" class="btn btn-primary text-nowrap">
                Tambah Detail
            </a>
        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Level</th>
                    <th>Bagian</th>
                    <th>Aspek</th>
                    <th>Arahan</th>
                    <th>Tindak Lanjut</th>
                    <th>Status</th>
                    <th>Eviden</th>
                    <th width="180">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $key => $row)

                <tr
                    data-arahan-detail-search="{{ strtolower($row->aspek . ' ' . $row->arahan . ' ' . ($row->tindak_lanjut ?? '') . ' ' . $row->status) }}"
                    data-arahan-detail-status="{{ $row->status }}"
                    data-arahan-detail-level="{{ $row->source_level }}"
                    data-arahan-detail-section="{{ $row->source_section }}"
                    data-arahan-detail-aspect="{{ $row->aspek }}"
                    data-arahan-detail-evidence="{{ $row->eviden ? 'available' : 'missing' }}">

                    <td>{{ $key + 1 }}</td>

                    <td>
                        @if($row->source_level)
                            <span class="badge bg-label-primary">{{ $row->source_level }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td>
                        @if($row->source_section)
                            <span class="badge bg-label-info">{{ $row->source_section }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td>
                        {{ $row->aspek }}
                    </td>

                    <td style="white-space: normal;">
                        {{ $row->arahan }}
                    </td>

                    <td style="white-space: normal;">
                        {{ $row->tindak_lanjut }}
                    </td>

                    <td>

                        @if($row->status == 'Open')

                            <span class="badge bg-label-danger">
                                Open
                            </span>

                        @elseif($row->status == 'Progress')

                            <span class="badge bg-label-warning">
                                Progress
                            </span>

                        @elseif($row->status == 'Selesai')

                            <span class="badge bg-label-success">
                                Selesai
                            </span>

                        @else

                            <span class="badge bg-label-success">
                                Selesai Berkelanjutan
                            </span>

                        @endif

                    </td>

                    <td>

                        @if($row->eviden)

                            <a href="{{ asset('uploads/arahan/'.$row->eviden) }}"
                                target="_blank"
                                class="btn btn-info btn-sm">
                                View
                            </a>

                        @else

                            -

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('arahan_detail.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('arahan_detail.destroy', $row->id) }}"
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

                <tr id="arahan-detail-empty-row">

                    <td colspan="9" class="text-center">

                        <span id="arahan-detail-empty-message">Data detail arahan belum tersedia</span>

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
        const rows = Array.from(document.querySelectorAll('[data-arahan-detail-search]'));
        const emptyRow = document.getElementById('arahan-detail-empty-row');
        const statusFilter = document.getElementById('arahan-detail-status-filter');
        const levelFilter = document.getElementById('arahan-detail-level-filter');
        const sectionFilter = document.getElementById('arahan-detail-section-filter');
        const aspectFilter = document.getElementById('arahan-detail-aspect-filter');
        const evidenceFilter = document.getElementById('arahan-detail-evidence-filter');
        const clearFilter = document.getElementById('arahan-detail-filter-clear');
        const emptyMessage = document.getElementById('arahan-detail-empty-message');

        if (!searchInput || rows.length === 0) {
            return;
        }

        const filterRows = function () {
            const keyword = searchInput.value.trim().toLocaleLowerCase();
            const status = statusFilter?.value || '';
            const level = levelFilter?.value || '';
            const section = sectionFilter?.value || '';
            const aspect = aspectFilter?.value || '';
            const evidence = evidenceFilter?.value || '';
            let visibleRows = 0;

            rows.forEach(function (row) {
                const matchesSearch = keyword === '' || row.dataset.arahanDetailSearch.includes(keyword);
                const matchesStatus = status === '' || row.dataset.arahanDetailStatus === status;
                const matchesLevel = level === '' || row.dataset.arahanDetailLevel === level;
                const matchesSection = section === '' || row.dataset.arahanDetailSection === section;
                const matchesAspect = aspect === '' || row.dataset.arahanDetailAspect === aspect;
                const matchesEvidence = evidence === '' || row.dataset.arahanDetailEvidence === evidence;
                const matches = matchesSearch && matchesStatus && matchesLevel && matchesSection && matchesAspect && matchesEvidence;
                row.hidden = !matches;
                visibleRows += matches ? 1 : 0;
            });

            if (emptyRow) {
                emptyRow.hidden = visibleRows !== 0;
            }
            if (emptyMessage && visibleRows === 0) {
                emptyMessage.textContent = 'Tidak ada detail yang sesuai filter';
            }
            if (clearFilter) {
                clearFilter.classList.toggle(
                    'invisible',
                    status === '' && level === '' && section === '' && aspect === '' && evidence === '' && keyword === ''
                );
            }
        };

        searchInput.addEventListener('input', filterRows);
        statusFilter?.addEventListener('change', filterRows);
        levelFilter?.addEventListener('change', filterRows);
        sectionFilter?.addEventListener('change', filterRows);
        aspectFilter?.addEventListener('change', filterRows);
        evidenceFilter?.addEventListener('change', filterRows);
        clearFilter?.addEventListener('click', function () {
            searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (levelFilter) levelFilter.value = '';
            if (sectionFilter) sectionFilter.value = '';
            if (aspectFilter) aspectFilter.value = '';
            if (evidenceFilter) evidenceFilter.value = '';
            filterRows();
        });
        searchForm?.addEventListener('submit', function (event) {
            event.preventDefault();
        });
        filterRows();
    });
</script>
@endsection
