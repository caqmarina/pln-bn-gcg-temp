@extends('layouts/contentNavbarLayout')

@section('title', 'Master Framework')

@section('vendor-script')
@vite('resources/assets/vendor/libs/masonry/masonry.js')
@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <a href="{{ route('master_framework.create') }}"
            class="btn btn-primary">
            Tambah Framework
        </a>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table">

            <thead>
                <tr>
                    <th>Nama Framework</th>
                    <th>Kode</th>
                    <th>Versi</th>
                    <th>Tahun Berlaku</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($data as $row)

                <tr>

                    <td>{{ $row->nama_framework }}</td>

                    <td>{{ $row->kode_framework }}</td>

                    <td>{{ $row->versi }}</td>

                    <td>{{ $row->tahun_berlaku }}</td>

                    <td>{{ $row->status }}</td>

                    <td>

                        <a href="{{ route('master_framework.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('master_framework.destroy', $row->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm">
                                Hapus
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