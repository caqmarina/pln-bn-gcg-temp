@extends('layouts/contentNavbarLayout')

@section('title', 'Master Kategori')

@section('vendor-script')
@vite('resources/assets/vendor/libs/masonry/masonry.js')
@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <a href="{{ route('master_kategori.create') }}"
            class="btn btn-primary">
            Tambah Kategori
        </a>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table">

            <thead>
                <tr>
                    <th>Framework</th>
                    <th>Nama Kategori</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($data as $row)

                <tr>

                    <td>
                        {{ $row->framework->nama_framework ?? '-' }}
                    </td>

                    <td>{{ $row->nama_kategori }}</td>

                    <td>{{ $row->bobot }}</td>

                    <td>

                        <a href="{{ route('master_kategori.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('master_kategori.destroy', $row->id) }}"
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