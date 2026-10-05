@extends('layouts/contentNavbarLayout')

@section('title', 'Assessment Detail')

@section('content')

<div class="card">

    <div class="card-header">

        <h5>
            Assessment Detail  {{ $assessment->framework->nama_framework }} Tahun Buku {{ $assessment->tahun_buku }}
        </h5>

    </div>
 

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Kategori</th>
                    <th>Indikator</th>
                    <th>Eviden</th>
                    <th>File Eviden</th>
                    <th>Bobot</th>
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
                                rows="3">{{ $row->eviden }}</textarea>

                    </td>

                    <td>

                        <input type="file"
                            name="dokumen_softcopy"
                            class="form-control">

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

                        <input type="number"
                            step="0.01"
                            name="bobot"
                            value="{{ $row->bobot }}"
                            class="form-control">

                    </td>

                    <td>

                        <button type="submit"
                            class="btn btn-primary btn-sm">

                            Save

                        </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection