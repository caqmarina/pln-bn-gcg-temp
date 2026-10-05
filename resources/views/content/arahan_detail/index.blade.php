@extends('layouts/contentNavbarLayout')

@section('title', 'Arahan Detail')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1">
                Detail Arahan
            </h5>

            <h5 class="mb-1">
                <strong>Judul :</strong> {{ $arahan->judul_arahan }}
            </h5>

        </div>

        <a href="{{ route('arahan_detail.create', $arahan->id) }}"
            class="btn btn-primary">
            Tambah Detail
        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>
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

                <tr>

                    <td>{{ $key + 1 }}</td>

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

                <tr>

                    <td colspan="7" class="text-center">

                        Data detail arahan belum tersedia

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection