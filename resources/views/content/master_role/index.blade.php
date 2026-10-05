@extends('layouts/contentNavbarLayout')

@section('title', 'Master Role')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Master Role
        </h5>

        <a href="{{ route('master_role.create') }}"
            class="btn btn-primary">

            Tambah Role

        </a>

    </div>

    <div class="table-responsive text-nowrap">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th width="80">No</th>
                    <th>Nama Role</th>
                    <th width="180">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $key => $row)

                <tr>

                    <td>
                        {{ $key + 1 }}
                    </td>

                    <td>
                        {{ $row->nama_role }}
                    </td>

                    <td>

                        <a href="{{ route('master_role.edit', $row->id) }}"
                            class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <form action="{{ route('master_role.destroy', $row->id) }}"
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

                    <td colspan="3" class="text-center">

                        Data belum tersedia

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection