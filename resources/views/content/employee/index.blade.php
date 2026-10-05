@extends('layouts/contentNavbarLayout')
@section('title', 'employee')

@section('vendor-script')
@vite('resources/assets/vendor/libs/masonry/masonry.js')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('employee.create') }}" class="btn btn-primary">Tambah User</a>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Direktorat</th>
                    <th>Bidang</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                <tr>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->nip }}</td>
                    <td>{{ $row->direktorat }}</td>
                    <td>{{ $row->bidang }}</td>
                    <td>{{ $row->role->nama_role ?? '-' }}</td>
                    <td>{{ $row->email }}</td>
                    <td>
                        <a href="{{ route('employee.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('employee.destroy', $row->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection