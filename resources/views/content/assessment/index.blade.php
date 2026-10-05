@extends('layouts/contentNavbarLayout')

@section('title', 'Assessment')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Assessment
        </h5>

        <a href="{{ route('assessment.create') }}"
            class="btn btn-primary">
            Tambah Assessment
        </a>

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

                <tr>

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

                <tr>

                    <td colspan="7" class="text-center">
                        Data assessment belum tersedia
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection