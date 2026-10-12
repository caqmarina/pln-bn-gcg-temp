@extends('layouts/contentNavbarLayout')

@section('title', 'Assessment Detail')

@section('content')

<div class="card">

    <div class="card-header">

        <h5>
            Assessment Detail  {{ $assessment->framework->nama_framework }} Tahun Buku {{ $assessment->tahun_buku }}
        </h5>

        <small class="text-muted">
            Rumus skor: (Ya + N/A) / Jumlah Pertanyaan × Skor Maksimal
        </small>

    </div>
 
    <div class="table-responsive px-3 pt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Pertanyaan</th>
                    <th>Skor Maksimal</th>
                    <th>N/A</th>
                    <th>Tidak</th>
                    <th>Ya</th>
                    <th>Total Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary as $item)
                    <tr>
                        <td>{{ $item['kategori']->nama_kategori }}</td>
                        <td>{{ $item['jumlah_pertanyaan'] }}</td>
                        <td>{{ number_format($item['skor_maksimal'], 2, ',', '.') }}</td>
                        <td>{{ $item['na'] }}</td>
                        <td>{{ $item['tidak'] }}</td>
                        <td>{{ $item['ya'] }}</td>
                        <td>{{ number_format($item['total_skor'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="6">Total</td>
                    <td>{{ number_format($summary->sum('total_skor'), 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Kategori</th>
                    <th>Indikator</th>
                    <th>Eviden</th>
                    <th>File Eviden</th>
                    <th>Pemenuhan</th>
                    <th width="120">Action</th>

                </tr>

            </thead>

            <tbody>

                @foreach($data as $row)

                <tr>

                    <td>
                        {{ $row->kategori->nama_kategori }}
                    </td>

                    <td>
                        {{ $row->indikator->indikator }}
                    </td>

                    <td>

                        <form action="{{ route('assessment_detail.update', $row->id) }}"
                            method="POST"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <textarea
                                name="eviden"
                                class="form-control"
                                rows="3"
                                @disabled(!$canUpdate)>{{ $row->eviden }}</textarea>

                    </td>

                    <td>

                        <input type="file"
                            name="dokumen_softcopy"
                            class="form-control"
                            @disabled(!$canUpdate)>

                        @if($row->dokumen_softcopy)

                            <a href="{{ asset('uploads/eviden_assessment/'.$row->dokumen_softcopy) }}"
                                target="_blank"
                                class="btn btn-sm btn-info mt-2">

                                View File

                            </a>

                        @else

                            <span class="badge bg-label-warning">
                                Tidak Ada File
                            </span>

                        @endif

                    </td>

                    <td>

                        <select name="pemenuhan" class="form-select" @disabled(!$canUpdate)>
                            <option value="Tidak" @selected($row->pemenuhan === 'Tidak')>Tidak</option>
                            <option value="Ya" @selected($row->pemenuhan === 'Ya')>Ya</option>
                            <option value="N/A" @selected($row->pemenuhan === 'N/A')>Not Applicable (N/A)</option>
                        </select>

                    </td>

                    <td>

                        @if($canUpdate)
                            <button type="submit" class="btn btn-primary btn-sm">
                                Save
                            </button>
                        @else
                            <span class="badge bg-label-secondary">View only</span>
                        @endif

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
