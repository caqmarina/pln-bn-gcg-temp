@extends('layouts/contentNavbarLayout')

@section('title', 'Master Indikator')

@section('vendor-script')
@vite('resources/assets/vendor/libs/masonry/masonry.js')
@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <a href="{{ route('master_indikator.create') }}"
            class="btn btn-primary">
            Tambah Indikator
        </a>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table">

            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Kode Indikator</th>
                    <th>Indikator</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($data as $row)

                <tr>

                    <td>
                        {{ $row->kategori->nama_kategori ?? '-' }}
                    </td>

                    <td>{{ $row->kode_indikator }}</td>

                    <td>{{ $row->indikator }}</td>

                    <td>{{ $row->bobot }}</td>

                    <td>

                        <a href="{{ route('master_indikator.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('master_indikator.destroy', $row->id) }}"
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